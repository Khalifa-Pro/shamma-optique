<?php
// app/Http/Controllers/DevisController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Devis;
use App\Models\ArticleDevis;
use App\Models\Client;
use App\Models\Ordonnance;
use App\Models\Facture;
use App\Models\Produit;
use Barryvdh\DomPDF\Facade\Pdf;


class DevisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $statut = $request->get('statut');

        $devis = Devis::with('client')
            ->when($search, fn($q) => $q
                ->where('numero', 'like', "%$search%")
                ->orWhereHas('client', fn($q) => $q
                    ->where('nom', 'like', "%$search%")
                    ->orWhere('prenom', 'like', "%$search%")
                )
            )
            ->when($statut, fn($q) => $q->where('statut', $statut))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('devis.index', compact('devis', 'search', 'statut'));
    }

    public function create(Request $request)
    {
        $clients    = Client::orderBy('nom')->get();
        $produits   = Produit::where('actif', true)->orderBy('designation')->get();
        $selectedClientId      = $request->get('client_id');
        $selectedOrdonnanceId  = $request->get('ordonnance_id');

        $ordonnances = $selectedClientId
            ? Ordonnance::where('client_id', $selectedClientId)->get()
            : collect();

        $articles = [[
            'designation'  => '',
            'marque'       => '',
            'type'         => 'monture',
            'quantite'     => 1,
            'prix_unitaire'=> 0,
            'inclus'       => true,
            'produit_id'   => null,
        ]];

        return view('devis.form', [
            'devis'                => null,
            'clients'              => $clients,
            'produits'             => $produits,
            'ordonnances'          => $ordonnances,
            'articles'             => $articles,
            'selectedClientId'     => $selectedClientId,
            'selectedOrdonnanceId' => $selectedOrdonnanceId,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id'                    => 'required|exists:clients,id',
            'ordonnance_id'                => 'nullable|exists:ordonnances,id',
            'magasin'                      => 'nullable|string|max:100',
            'notes'                        => 'nullable|string',
            'articles'                     => 'required|array|min:1',
            'articles.*.designation'       => 'required|string',
            'articles.*.type'              => 'required|string',
            'articles.*.quantite'          => 'required|integer|min:1',
            'articles.*.prix_unitaire'     => 'required|numeric|min:0',
            'articles.*.marque'            => 'nullable|string',
            'articles.*.produit_id'        => 'nullable|exists:produits,id',
            'articles.*.inclus'            => 'nullable|boolean',
        ]);

        $total = 0;
        foreach ($request->articles as $a) {
            if ($a['inclus'] ?? true) {
                $total += $a['quantite'] * $a['prix_unitaire'];
            }
        }

        $devis = Devis::create([
            'numero'       => Devis::generateNumero(),
            'client_id'    => $request->client_id,
            'ordonnance_id'=> $request->ordonnance_id,
            'magasin'      => $request->magasin,
            'montant_total'=> $total,
            'statut'       => 'brouillon',
            'notes'        => $request->notes,
            'created_by'   => session('user_id'),
        ]);

        foreach ($request->articles as $a) {
            $devis->articles()->create([
                'designation'   => $a['designation'],
                'type'          => $a['type'],
                'quantite'      => $a['quantite'],
                'prix_unitaire' => $a['prix_unitaire'],
                'marque'        => $a['marque'] ?? null,
                'produit_id'    => $a['produit_id'] ?? null,
                'inclus'        => isset($a['inclus']) ? (bool)$a['inclus'] : true,
            ]);
        }

        return redirect()->route('devis.show', $devis)->with('success', 'Devis créé.');
    }

    public function show(Devis $devis)
    {
        $devis->load(['client', 'ordonnance', 'articles.produit', 'facture']);
        return view('devis.show', compact('devis'));
    }

    public function edit(Devis $devis)
    {
        if (in_array($devis->statut, ['facture', 'annule'])) {
            return redirect()->route('devis.show', $devis)
                ->with('error', 'Ce devis ne peut plus être modifié.');
        }

        $devis->load('articles');
        $clients     = Client::orderBy('nom')->get();
        $produits    = Produit::where('actif', true)->orderBy('designation')->get();
        $ordonnances = Ordonnance::where('client_id', $devis->client_id)->get();

        $articles = $devis->articles->map(fn($a) => [
            'designation'   => $a->designation,
            'marque'        => $a->marque,
            'type'          => $a->type,
            'quantite'      => $a->quantite,
            'prix_unitaire' => (float) $a->prix_unitaire,
            'inclus'        => (bool) $a->inclus,
            'produit_id'    => $a->produit_id,
        ]);

        return view('devis.form', compact('devis', 'clients', 'produits', 'ordonnances', 'articles'));
    }

    public function update(Request $request, Devis $devis)
    {
        $request->validate([
            'client_id'                => 'required|exists:clients,id',
            'ordonnance_id'            => 'nullable|exists:ordonnances,id',
            'magasin'                  => 'nullable|string|max:100',
            'notes'                    => 'nullable|string',
            'articles'                 => 'required|array|min:1',
            'articles.*.designation'   => 'required|string',
            'articles.*.type'          => 'required|string',
            'articles.*.quantite'      => 'required|integer|min:1',
            'articles.*.prix_unitaire' => 'required|numeric|min:0',
            'articles.*.marque'        => 'nullable|string',
            'articles.*.produit_id'    => 'nullable|exists:produits,id',
            'articles.*.inclus'        => 'nullable|boolean',
        ]);

        $total = 0;
        foreach ($request->articles as $a) {
            if ($a['inclus'] ?? true) {
                $total += $a['quantite'] * $a['prix_unitaire'];
            }
        }

        $devis->update([
            'client_id'     => $request->client_id,
            'ordonnance_id' => $request->ordonnance_id,
            'magasin'       => $request->magasin,
            'montant_total' => $total,
            'notes'         => $request->notes,
        ]);

        $devis->articles()->delete();
        foreach ($request->articles as $a) {
            $devis->articles()->create([
                'designation'   => $a['designation'],
                'type'          => $a['type'],
                'quantite'      => $a['quantite'],
                'prix_unitaire' => $a['prix_unitaire'],
                'marque'        => $a['marque'] ?? null,
                'produit_id'    => $a['produit_id'] ?? null,
                'inclus'        => isset($a['inclus']) ? (bool)$a['inclus'] : true,
            ]);
        }

        return redirect()->route('devis.show', $devis)->with('success', 'Devis mis à jour.');
    }

    public function destroy(Devis $devis)
    {
        $devis->delete();
        return redirect()->route('devis.index')->with('success', 'Devis supprimé.');
    }

    public function valider(Devis $devis)
    {
        abort_if($devis->statut !== 'brouillon', 403);
        abort_if($devis->articles()->count() === 0, 422, 'Ajoutez au moins un article.');

        $devis->update([
            'statut'    => 'valide',
            'valide_at' => now(),
            'valide_by' => session('user_id'),
        ]);

        return back()->with('success', 'Devis validé. Vous pouvez maintenant créer la facture.');
    }

    public function facturer(Request $request, Devis $devis)
    {
        abort_if($devis->statut !== 'valide', 403, 'Le devis doit être validé avant facturation.');

        $request->validate([
            'part_client'    => 'required|numeric|min:0',
            'part_assurance' => 'required|numeric|min:0',
            'date_echeance'  => 'required|date',
        ]);

        $facture = Facture::create([
            'numero'         => Facture::generateNumero(),
            'devis_id'       => $devis->id,
            'client_id'      => $devis->client_id,
            'montant_total'  => $devis->montant_total,
            'part_client'    => $request->part_client,
            'part_assurance' => $request->part_assurance,
            'statut'         => 'en_attente',
            'date_echeance'  => $request->date_echeance,
            'created_by'     => session('user_id'),
        ]);

        // Décrémenter le stock pour chaque article lié à un produit
        foreach ($devis->articles as $article) {
            if ($article->produit_id && $article->inclus) {
                $article->produit->sortie(
                    $article->quantite,
                    'Vente — Facture ' . $facture->numero,
                    $facture,
                    session('user_id')
                );
            }
        }

        $devis->update([
            'statut'         => 'facture',
            'part_client'    => $request->part_client,
            'part_assurance' => $request->part_assurance,
        ]);

        return redirect()->route('factures.show', $facture)->with('success', 'Facture créée.');
    }

    public function pdf(Devis $devis)
    {
        $devis->load(['client', 'ordonnance', 'articles']);

        $pdf = Pdf::loadView('devis.pdf', compact('devis'))
                ->setPaper('a4', 'portrait');

        return $pdf->download($devis->numero . '.pdf');
    }
}

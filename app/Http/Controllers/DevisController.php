<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Devis;
use App\Models\ArticleDevis;
use App\Models\Client;
use App\Models\Ordonnance;
use App\Models\Facture;

class DevisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $statut = $request->get('statut');
        $devis = Devis::with('client')
            ->when($search, function ($q) use ($search) {
                $q->where('numero', 'like', "%$search%")
                  ->orWhereHas('client', fn($q) => $q->where('nom', 'like', "%$search%")->orWhere('prenom', 'like', "%$search%"));
            })
            ->when($statut, fn($q) => $q->where('statut', $statut))
            ->latest()->paginate(15)->withQueryString();

        return view('devis.index', compact('devis', 'search', 'statut'));
    }

    public function create(Request $request)
    {
        $clients = Client::orderBy('nom')->get();

        $selectedClientId = $request->get('client_id');
        $selectedOrdonnanceId = $request->get('ordonnance_id');

        $ordonnances = $selectedClientId
            ? Ordonnance::where('client_id', $selectedClientId)->get()
            : collect();

        $articles = [
            [
                'designation' => '',
                'type' => 'monture',
                'quantite' => 1,
                'prix_unitaire' => 0
            ]
        ];

        return view('devis.form', [
            'devis' => null,
            'clients' => $clients,
            'ordonnances' => $ordonnances,
            'articles' => $articles,
            'selectedClientId' => $selectedClientId,
            'selectedOrdonnanceId' => $selectedOrdonnanceId,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'ordonnance_id' => 'nullable|exists:ordonnances,id',
            'notes' => 'nullable|string',
            'articles' => 'required|array|min:1',
            'articles.*.designation' => 'required|string',
            'articles.*.type' => 'required|string',
            'articles.*.quantite' => 'required|integer|min:1',
            'articles.*.prix_unitaire' => 'required|numeric|min:0',
        ]);

        $total = 0;
        foreach ($request->articles as $a) {
            $total += $a['quantite'] * $a['prix_unitaire'];
        }

        $devis = Devis::create([
            'numero' => Devis::generateNumero(),
            'client_id' => $request->client_id,
            'ordonnance_id' => $request->ordonnance_id,
            'montant_total' => $total,
            'statut' => 'brouillon',
            'notes' => $request->notes,
            'created_by' => session('user_id'),
        ]);

        foreach ($request->articles as $a) {
            $devis->articles()->create($a);
        }

        return redirect()->route('devis.show', $devis)->with('success', 'Devis créé.');
    }

    public function show(Devis $devis)
    {
        $devis->load(['client', 'ordonnance', 'articles', 'facture']);
        return view('devis.show', compact('devis'));
    }

    public function edit(Devis $devis)
    {
        if (in_array($devis->statut, ['facture', 'annule'])) {
            return redirect()->route('devis.show', $devis)
                ->with('error', 'Ce devis ne peut plus être modifié.');
        }

        $devis->load('articles');

        $clients = Client::orderBy('nom')->get();

        $ordonnances = Ordonnance::where('client_id', $devis->client_id)->get();

        $articles = $devis->articles->map(function ($a) {
            return [
                'designation' => $a->designation,
                'type' => $a->type,
                'quantite' => $a->quantite,
                'prix_unitaire' => (float) $a->prix_unitaire
            ];
        });

        return view('devis.form', compact('devis','clients','ordonnances','articles'));
    }

    public function update(Request $request, Devis $devis)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'ordonnance_id' => 'nullable|exists:ordonnances,id',
            'notes' => 'nullable|string',
            'articles' => 'required|array|min:1',
            'articles.*.designation' => 'required|string',
            'articles.*.type' => 'required|string',
            'articles.*.quantite' => 'required|integer|min:1',
            'articles.*.prix_unitaire' => 'required|numeric|min:0',
        ]);

        $total = 0;
        foreach ($request->articles as $a) {
            $total += $a['quantite'] * $a['prix_unitaire'];
        }

        $devis->update([
            'client_id' => $request->client_id,
            'ordonnance_id' => $request->ordonnance_id,
            'montant_total' => $total,
            'notes' => $request->notes,
        ]);

        $devis->articles()->delete();
        foreach ($request->articles as $a) {
            $devis->articles()->create($a);
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
        $devis->update(['statut' => 'valide']);
        return back()->with('success', 'Devis validé.');
    }

    public function facturer(Request $request, Devis $devis)
    {
        $request->validate([
            'part_client' => 'required|numeric|min:0',
            'part_assurance' => 'required|numeric|min:0',
            'date_echeance' => 'required|date',
        ]);

        $facture = Facture::create([
            'numero' => Facture::generateNumero(),
            'devis_id' => $devis->id,
            'client_id' => $devis->client_id,
            'montant_total' => $devis->montant_total,
            'part_client' => $request->part_client,
            'part_assurance' => $request->part_assurance,
            'statut' => 'en_attente',
            'date_echeance' => $request->date_echeance,
            'created_by' => session('user_id'),
        ]);

        $devis->update(['statut' => 'facture']);

        return redirect()->route('factures.show', $facture)->with('success', 'Facture créée.');
    }
}

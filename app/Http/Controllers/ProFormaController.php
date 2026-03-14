<?php
// app/Http/Controllers/ProFormaController.php

namespace App\Http\Controllers;

use App\Models\ProForma;
use App\Models\Facture;
use Illuminate\Http\Request;

class ProFormaController extends Controller
{
    public function index()
    {
        $proFormas = ProForma::with('client')
            ->latest()
            ->paginate(20);
        return view('pro_formas.index', compact('proFormas'));
    }

    public function show(ProForma $proForma)
    {
        $proForma->load('client', 'ordonnance', 'articles', 'facture');
        return view('pro_formas.show', compact('proForma'));
    }

    public function create()
    {
        return view('pro_formas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id'      => 'required|exists:clients,id',
            'ordonnance_id'  => 'nullable|exists:ordonnances,id',
            'magasin'        => 'nullable|string',
            'notes'          => 'nullable|string',
            'articles'       => 'required|array|min:1',
            'articles.*.type'          => 'required|string',
            'articles.*.designation'   => 'required|string',
            'articles.*.marque'        => 'nullable|string',
            'articles.*.quantite'      => 'required|integer|min:1',
            'articles.*.prix_unitaire' => 'required|numeric|min:0',
            'articles.*.inclus'        => 'nullable|boolean',
        ]);

        $proForma = ProForma::create([
            ...$data,
            'created_by' => session('user_id'),
            'statut'     => 'brouillon',
        ]);

        foreach ($data['articles'] as $article) {
            $proForma->articles()->create([
                ...$article,
                'inclus' => $article['inclus'] ?? true,
            ]);
        }

        $proForma->recalculerTotal();

        return redirect()->route('pro-formas.show', $proForma)
                         ->with('success', 'Pro-forma créé.');
    }

    // ─── Valider le pro-forma ─────────────────────────────
    public function valider(ProForma $proForma)
    {
        abort_if($proForma->statut !== 'brouillon', 403, 'Ce pro-forma ne peut pas être validé.');
        abort_if($proForma->articles()->count() === 0, 422, 'Ajoutez au moins un article.');

        $proForma->update([
            'statut'    => 'valide',
            'valide_at' => now(),
            'valide_by' => session('user_id'),
        ]);

        return back()->with('success', 'Pro-forma validé. Vous pouvez maintenant créer la facture.');
    }

    // ─── Créer la facture depuis le pro-forma ─────────────
    public function facturer(Request $request, ProForma $proForma)
    {
        // 🔒 Impossible de facturer sans validation
        abort_if($proForma->statut !== 'valide', 403,
            'Le pro-forma doit être validé avant de pouvoir être facturé.');

        $data = $request->validate([
            'part_client'    => 'required|numeric|min:0',
            'part_assurance' => 'required|numeric|min:0',
            'date_echeance'  => 'required|date',
        ]);

        // Générer numéro facture
        $last = Facture::whereYear('created_at', now()->year)->count() + 1;
        $numero = 'FAC-' . now()->format('Y') . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);

        $facture = Facture::create([
            'numero'         => $numero,
            'pro_forma_id'   => $proForma->id,
            'client_id'      => $proForma->client_id,
            'montant_total'  => $proForma->montant_total,
            'part_client'    => $data['part_client'],
            'part_assurance' => $data['part_assurance'],
            'date_echeance'  => $data['date_echeance'],
            'statut'         => 'en_attente',
            'created_by'     => session('user_id'),
        ]);

        foreach ($proForma->articles as $article) {
            if ($article->produit_id && $article->inclus) {
                $article->produit->sortie(
                    $article->quantite,
                    'Vente — Facture ' . $facture->numero,
                    $facture,
                    session('user_id')
                );
            }
        }

        $proForma->update(['statut' => 'facture']);

        return redirect()->route('factures.show', $facture)
                         ->with('success', 'Facture créée.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\MouvementStock;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->input('search');
        $categorie = $request->input('categorie');

        $produits = Produit::query()
            ->where('actif', true)
            ->when($search, fn($q) => $q
                ->where('designation', 'like', "%$search%")
                ->orWhere('marque', 'like', "%$search%")
                ->orWhere('reference', 'like', "%$search%"))
            ->when($categorie, fn($q) => $q->where('categorie', $categorie))
            ->orderBy('designation')
            ->paginate(20)
            ->withQueryString();

        $alertes = Produit::where('actif', true)
            ->whereColumn('stock_actuel', '<=', 'stock_minimum')
            ->orderBy('stock_actuel')
            ->get();

        return view('produits.index', compact('produits', 'alertes', 'search', 'categorie'));
    }

    public function show(Produit $produit)
    {
        $mouvements = $produit->mouvements()
            ->with('createdBy')
            ->latest()
            ->paginate(15);

        return view('produits.show', compact('produit', 'mouvements'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reference'     => 'nullable|string|unique:produits,reference',
            'designation'   => 'required|string|max:255',
            'marque'        => 'nullable|string|max:100',
            'categorie'     => 'required|in:monture_homme,monture_femme,monture_enfant,monture_solaire,verre_unifocal,verre_progressif,verre_degressif,lentille,produit_entretien,accessoire,autre',
            'prix_vente'    => 'required|numeric|min:0',
            'prix_achat'    => 'nullable|numeric|min:0',
            'stock_actuel'  => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0',
            'notes'         => 'nullable|string',
        ]);

        $produit = Produit::create([
            ...$data,
            'created_by' => session('user_id'),
        ]);

        if ($data['stock_actuel'] > 0) {
            $produit->mouvements()->create([
                'type'        => 'entree',
                'quantite'    => $data['stock_actuel'],
                'stock_avant' => 0,
                'stock_apres' => $data['stock_actuel'],
                'motif'       => 'Stock initial',
                'source_type' => null,
                'source_id'   => null,
                'created_by'  => session('user_id'),
            ]);
        }

        return back()->with('success', 'Produit créé avec succès.');
    }

    public function update(Request $request, Produit $produit)
    {
        $data = $request->validate([
            'reference'     => 'nullable|string|unique:produits,reference,' . $produit->id,
            'designation'   => 'required|string|max:255',
            'marque'        => 'nullable|string|max:100',
            'categorie'     => 'required|in:monture_homme,monture_femme,monture_enfant,monture_solaire,verre_unifocal,verre_progressif,verre_degressif,lentille,produit_entretien,accessoire,autre',
            'prix_vente'    => 'required|numeric|min:0',
            'prix_achat'    => 'nullable|numeric|min:0',
            'stock_actuel'  => 'required|integer|min:0',
            'stock_minimum' => 'required|integer|min:0',
            'notes'         => 'nullable|string',
        ]);

        if ((int)$data['stock_actuel'] !== $produit->stock_actuel) {
            $produit->mouvements()->create([
                'type'        => 'ajustement',
                'quantite'    => abs($data['stock_actuel'] - $produit->stock_actuel),
                'stock_avant' => $produit->stock_actuel,
                'stock_apres' => $data['stock_actuel'],
                'motif'       => 'Ajustement manuel',
                'source_type' => null,
                'source_id'   => null,
                'created_by'  => session('user_id'),
            ]);
        }

        $produit->update($data);

        return back()->with('success', 'Produit mis à jour.');
    }

    public function destroy(Produit $produit)
    {
        $produit->update(['actif' => false]);

        return back()->with('success', "Produit « {$produit->designation} » désactivé.");
    }

    public function entree(Request $request, Produit $produit)
    {
        $data = $request->validate([
            'quantite' => 'required|integer|min:1',
            'motif'    => 'nullable|string|max:255',
        ]);

        $produit->entree(
            $data['quantite'],
            $data['motif'] ?? 'Réapprovisionnement',
            null,
            session('user_id')
        );

        return back()->with('success', 'Stock mis à jour. Nouveau stock : ' . $produit->fresh()->stock_actuel);
    }
}

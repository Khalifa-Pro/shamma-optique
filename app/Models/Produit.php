<?php
// app/Models/Produit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'reference', 'designation', 'marque', 'categorie',
        'prix_vente', 'prix_achat', 'stock_actuel', 'stock_minimum',
        'actif', 'notes', 'created_by',
    ];

    protected $casts = ['actif' => 'boolean'];

    public function mouvements()    { return $this->hasMany(MouvementStock::class); }
    public function articles()      { return $this->hasMany(ArticleProForma::class); }

    public function getCategorieLabelAttribute(): string
    {
        return match($this->categorie) {
            'monture_adulte'    => 'Monture adulte',
            'monture_enfant'    => 'Monture enfant',
            'monture_solaire'   => 'Monture solaire',
            'verre_unifocal'    => 'Verre unifocal',
            'verre_progressif'  => 'Verre progressif',
            'verre_degressif'   => 'Verre dégressif',
            'lentille'          => 'Lentille',
            'produit_entretien' => 'Produit entretien',
            'accessoire'        => 'Accessoire',
            default             => 'Autre',
        };
    }

    public function getStockAlertAttribute(): bool
    {
        return $this->stock_actuel <= $this->stock_minimum;
    }

    public function getStockColorAttribute(): string
    {
        if ($this->stock_actuel === 0)          return 'bg-red-100 text-red-700';
        if ($this->stock_actuel <= $this->stock_minimum) return 'bg-orange-100 text-orange-700';
        return 'bg-green-100 text-green-700';
    }

    public function getStockLabelAttribute(): string
    {
        if ($this->stock_actuel === 0)          return 'Rupture';
        if ($this->stock_actuel <= $this->stock_minimum) return 'Stock faible';
        return 'En stock';
    }

    // Décrémenter le stock et enregistrer le mouvement
    public function sortie(int $quantite, string $motif, Model $source, int $userId = null): void
    {
        $avant = $this->stock_actuel;
        $this->decrement('stock_actuel', $quantite);
        $this->mouvements()->create([
            'type'        => 'sortie',
            'quantite'    => $quantite,
            'stock_avant' => $avant,
            'stock_apres' => $avant - $quantite,
            'motif'       => $motif,
            'source_type' => get_class($source),
            'source_id'   => $source->id,
            'created_by'  => $userId,
        ]);
    }

    public function entree(int $quantite, string $motif, Model $source = null, int $userId = null): void
    {
        $avant = $this->stock_actuel;
        $this->increment('stock_actuel', $quantite);
        $this->mouvements()->create([
            'type'        => 'entree',
            'quantite'    => $quantite,
            'stock_avant' => $avant,
            'stock_apres' => $avant + $quantite,
            'motif'       => $motif,
            'source_type' => $source ? get_class($source) : null,
            'source_id'   => $source?->id,
            'created_by'  => $userId,
        ]);
    }
}

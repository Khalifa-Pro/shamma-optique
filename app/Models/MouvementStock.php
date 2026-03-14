<?php
// app/Models/MouvementStock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    protected $table = 'mouvements_stock';

    protected $fillable = [
        'produit_id',
        'type',
        'quantite',
        'stock_avant',
        'stock_apres',
        'motif',
        'source_type',
        'source_id',
        'created_by',
    ];

    // ─── Relations ───────────────────────────────────────
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relation polymorphique (pro_forma, facture, etc.)
    public function source()
    {
        return $this->morphTo();
    }

    // ─── Accesseurs ──────────────────────────────────────
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'entree'      => 'Entrée',
            'sortie'      => 'Sortie',
            'ajustement'  => 'Ajustement',
            default       => $this->type,
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'entree'     => 'bg-green-100 text-green-700',
            'sortie'     => 'bg-red-100 text-red-700',
            'ajustement' => 'bg-blue-100 text-blue-700',
            default      => 'bg-gray-100 text-gray-700',
        };
    }

    public function getSigneAttribute(): string
    {
        return $this->type === 'entree' ? '+' : '-';
    }
}

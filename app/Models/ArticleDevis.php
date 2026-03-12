<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleDevis extends Model
{
    protected $table = 'articles_devis';

    protected $fillable = [
        'devis_id', 'designation', 'type', 'quantite', 'prix_unitaire',
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire' => 'decimal:2',
    ];

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'monture' => 'Monture',
            'verre_droit' => 'Verre OD',
            'verre_gauche' => 'Verre OG',
            'accessoire' => 'Accessoire',
            'autre' => 'Autre',
            default => $this->type,
        };
    }

    public function getTotalAttribute(): float
    {
        return $this->quantite * $this->prix_unitaire;
    }
}

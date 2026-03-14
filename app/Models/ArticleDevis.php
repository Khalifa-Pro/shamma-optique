<?php
// app/Models/ArticleDevis.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleDevis extends Model
{
    protected $table = 'articles_devis';
    
    protected $fillable = [
        'devis_id', 'produit_id', 'marque', 'inclus',
        'designation', 'type', 'quantite', 'prix_unitaire',
    ];

    protected $casts = ['inclus' => 'boolean'];

    public function devis()   { return $this->belongsTo(Devis::class); }
    public function produit() { return $this->belongsTo(Produit::class); }

    public function getTotalAttribute(): float
    {
        return $this->inclus ? $this->prix_unitaire * $this->quantite : 0;
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'monture'      => 'Monture',
            'verre_droit'  => 'Verre OD',
            'verre_gauche' => 'Verre OG',
            'photogray'    => 'Photogray',
            'antireflet'   => 'Antireflet',
            'accessoire'   => 'Accessoire',
            'autre'        => 'Autre',
            default        => $this->type,
        };
    }
}

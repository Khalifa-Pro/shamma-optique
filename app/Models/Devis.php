<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Devis extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero', 'client_id', 'ordonnance_id',
        'montant_total', 'statut', 'notes', 'created_by',
    ];

    // Statuts: brouillon | valide | facture | annule
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function ordonnance()
    {
        return $this->belongsTo(Ordonnance::class);
    }

    public function articles()
    {
        return $this->hasMany(ArticleDevis::class);
    }

    public function facture()
    {
        return $this->hasOne(Facture::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateNumero(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'DEV-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'brouillon' => 'Brouillon',
            'valide' => 'Validé',
            'facture' => 'Facturé',
            'annule' => 'Annulé',
            default => $this->statut,
        };
    }

    public function getStatutColorAttribute(): string
    {
        return match($this->statut) {
            'brouillon' => 'bg-gray-100 text-gray-700',
            'valide' => 'bg-blue-100 text-blue-700',
            'facture' => 'bg-green-100 text-green-700',
            'annule' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}

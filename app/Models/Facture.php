<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero', 'devis_id', 'client_id',
        'montant_total', 'part_client', 'part_assurance',
        'statut', 'date_echeance', 'created_by',
    ];

    protected $casts = [
        'date_echeance' => 'date',
        'montant_total' => 'decimal:2',
        'part_client' => 'decimal:2',
        'part_assurance' => 'decimal:2',
    ];

    // Statuts: en_attente | payee | annulee
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function vente()
    {
        return $this->hasOne(Vente::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateNumero(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'FAC-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'payee' => 'Payée',
            'annulee' => 'Annulée',
            default => $this->statut,
        };
    }

    public function getStatutColorAttribute(): string
    {
        return match($this->statut) {
            'en_attente' => 'bg-yellow-100 text-yellow-700',
            'payee' => 'bg-green-100 text-green-700',
            'annulee' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}

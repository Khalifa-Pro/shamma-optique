<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vente extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero', 'facture_id', 'devis_id', 'client_id',
        'montant_total', 'part_client', 'part_assurance',
        'mode_paiement', 'date_paiement', 'notes', 'created_by',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant_total' => 'decimal:2',
        'part_client' => 'decimal:2',
        'part_assurance' => 'decimal:2',
    ];

    // Modes: especes | carte | virement | cheque | mutuelle
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateNumero(): string
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'VTE-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function getModePaiementLabelAttribute(): string
    {
        return match($this->mode_paiement) {
            'especes' => 'Espèces',
            'carte' => 'Carte bancaire',
            'virement' => 'Virement',
            'cheque' => 'Chèque',
            'mutuelle' => 'Mutuelle',
            default => $this->mode_paiement,
        };
    }
}

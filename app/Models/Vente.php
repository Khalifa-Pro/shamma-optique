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
        'mode_paiement',
        'mode_paiement_assurance',
        'date_paiement', 'notes', 'created_by',
    ];

    protected $casts = [
        'date_paiement'  => 'date',
        'montant_total'  => 'decimal:2',
        'part_client'    => 'decimal:2',
        'part_assurance' => 'decimal:2',
    ];

    // ─── Constantes modes de paiement ────────────────────
    const MODES_CLIENT = [
        'especes'      => 'Espèces',
        'carte'        => 'Carte bancaire',
        'virement'     => 'Virement',
        'cheque'       => 'Chèque',
        'mobile_wave'  => 'Wave',
        'mobile_orange'=> 'Orange Money',
        'mobile_mtn'   => 'MTN Money',
    ];

    const MODES_ASSURANCE = [
        'mutuelle' => 'Mutuelle / Tiers payant',
        'virement' => 'Virement bancaire',
        'autre'    => 'Autre',
    ];

    // ─── Relations ───────────────────────────────────────
    public function client()    { return $this->belongsTo(Client::class); }
    public function facture()   { return $this->belongsTo(Facture::class); }
    public function devis()     { return $this->belongsTo(Devis::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }

    // ─── Numérotation ────────────────────────────────────
    public static function generateNumero(): string
    {
        $year  = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'VTE-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // ─── Label complet (client + assurance si applicable) ─
    public function getModePaiementLabelAttribute(): string
    {
        $labelClient = self::MODES_CLIENT[$this->mode_paiement] ?? $this->mode_paiement;

        if ($this->mode_paiement_assurance && $this->part_assurance > 0) {
            $labelAssurance = self::MODES_ASSURANCE[$this->mode_paiement_assurance]
                           ?? $this->mode_paiement_assurance;
            return $labelClient . ' + ' . $labelAssurance;
        }

        return $labelClient;
    }

    // ─── Label mode paiement client seul ─────────────────
    public function getModePaiementClientLabelAttribute(): string
    {
        return self::MODES_CLIENT[$this->mode_paiement] ?? $this->mode_paiement;
    }

    // ─── Label mode paiement assurance seul ──────────────
    public function getModePaiementAssuranceLabelAttribute(): string
    {
        return self::MODES_ASSURANCE[$this->mode_paiement_assurance] ?? '—';
    }

    // ─── Est un paiement mobile ──────────────────────────
    public function getEstMobileAttribute(): bool
    {
        return str_starts_with($this->mode_paiement ?? '', 'mobile_');
    }

    // ─── Opérateur mobile ────────────────────────────────
    public function getOperateurMobileAttribute(): ?string
    {
        return match($this->mode_paiement) {
            'mobile_wave'   => 'Wave',
            'mobile_orange' => 'Orange Money',
            'mobile_mtn'    => 'MTN Money',
            default         => null,
        };
    }
}

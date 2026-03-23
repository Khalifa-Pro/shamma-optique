<?php
// app/Models/Devis.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Devis extends Model
{
    use HasFactory;

    protected $fillable = [
    'numero', 'client_id', 'ordonnance_id', 'created_by',
    'magasin', 'valide_at', 'valide_by',
    'part_assurance', 'part_client',
    'avance',        // ← nouveau
        'montant_total', 'statut', 'notes',
    ];

    protected $casts = [
        'valide_at' => 'datetime',
        'avance'    => 'decimal:2',  // ← nouveau
    ];

    // ─── Accesseurs avance ───────────────────────────────
    public function getResteAttribute(): float
    {
        return max(0, (float)$this->part_client - (float)$this->avance);
    }

    public function getEstSoldeAttribute(): bool
    {
        return (float)$this->avance >= (float)$this->part_client;
    }

    public function getAAvancePartielleAttribute(): bool
    {
        return (float)$this->avance > 0 && (float)$this->avance < (float)$this->part_client;
    }

    // ─── Relations ───────────────────────────────────────
    public function client()      { return $this->belongsTo(Client::class); }
    public function ordonnance()  { return $this->belongsTo(Ordonnance::class); }
    public function articles()    { return $this->hasMany(ArticleDevis::class); }
    public function facture()     { return $this->hasOne(Facture::class); }
    public function createdBy()   { return $this->belongsTo(User::class, 'created_by'); }
    public function valideBy()    { return $this->belongsTo(User::class, 'valide_by'); }

    // ─── Numérotation ────────────────────────────────────
    public static function generateNumero(): string
    {
        $year  = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'DEV-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    // ─── Accesseurs statut ───────────────────────────────
    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'brouillon' => 'Brouillon',
            'valide'    => 'Validé',
            'facture'   => 'Facturé',
            'annule'    => 'Annulé',
            default     => $this->statut,
        };
    }

    public function getStatutColorAttribute(): string
    {
        return match($this->statut) {
            'brouillon' => 'bg-gray-100 text-gray-700',
            'valide'    => 'bg-blue-100 text-blue-700',
            'facture'   => 'bg-green-100 text-green-700',
            'annule'    => 'bg-red-100 text-red-700',
            default     => 'bg-gray-100 text-gray-700',
        };
    }

    // ─── Accesseurs métier ───────────────────────────────
    public function getEstValidableAttribute(): bool
    {
        return $this->statut === 'brouillon' && $this->articles()->count() > 0;
    }

    public function getEstFacturableAttribute(): bool
    {
        return $this->statut === 'valide';
    }

    // ─── Recalcul total ──────────────────────────────────
    public function recalculerTotal(): void
    {
        $total = $this->articles->sum(fn($a) => $a->inclus ? $a->prix_unitaire * $a->quantite : 0);
        $this->update(['montant_total' => $total]);
    }
}

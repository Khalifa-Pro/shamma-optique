<?php
// app/Models/ProForma.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProForma extends Model
{
    protected $fillable = [
        'numero', 'client_id', 'ordonnance_id', 'created_by',
        'magasin', 'statut', 'valide_at', 'valide_by',
        'montant_total', 'part_assurance', 'part_client', 'notes',
    ];

    protected $casts = [
        'valide_at' => 'datetime',
    ];

    // ─── Relations ───────────────────────────────────────
    public function client()      { return $this->belongsTo(Client::class); }
    public function ordonnance()  { return $this->belongsTo(Ordonnance::class); }
    public function articles()    { return $this->hasMany(ArticleProForma::class); }
    public function facture()     { return $this->hasOne(Facture::class); }
    public function createdBy()   { return $this->belongsTo(User::class, 'created_by'); }
    public function valideBy()    { return $this->belongsTo(User::class, 'valide_by'); }

    // ─── Accesseurs ──────────────────────────────────────
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
            'brouillon' => 'bg-gray-100 text-gray-600',
            'valide'    => 'bg-blue-100 text-blue-700',
            'facture'   => 'bg-green-100 text-green-700',
            'annule'    => 'bg-red-100 text-red-700',
            default     => 'bg-gray-100 text-gray-600',
        };
    }

    public function getEstValidableAttribute(): bool
    {
        // Doit avoir au moins un article pour être validé
        return $this->statut === 'brouillon' && $this->articles()->count() > 0;
    }

    public function getEstFacturableAttribute(): bool
    {
        // Seulement si validé ET pas encore facturé
        return $this->statut === 'valide';
    }

    // ─── Méthodes métier ─────────────────────────────────
    public function recalculerTotal(): void
    {
        $total = $this->articles->sum(fn($a) => $a->inclus ? $a->prix_unitaire * $a->quantite : 0);
        $this->update(['montant_total' => $total]);
    }

    protected static function booted(): void
    {
        static::creating(function ($proForma) {
            if (empty($proForma->numero)) {
                $last = static::whereYear('created_at', now()->year)->count() + 1;
                $proForma->numero = 'PF-' . now()->format('Y') . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}

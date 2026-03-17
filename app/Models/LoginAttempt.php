<?php
// app/Models/LoginAttempt.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LoginAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'email', 'ip_address', 'succes', 'tentative_at', 'bloque_jusqu_au'
    ];

    protected $casts = [
        'succes'           => 'boolean',
        'tentative_at'     => 'datetime',
        'bloque_jusqu_au'  => 'datetime',
    ];

    const MAX_TENTATIVES  = 3;
    const FENETRE_MINUTES = 15;  // Fenêtre de comptage
    const BLOCAGE_MINUTES = 30;  // Durée de blocage

    // ─── Vérifier si bloqué ──────────────────────────────
    public static function estBloque(string $email, string $ip): bool
    {
        return static::where(function ($q) use ($email, $ip) {
                $q->where('email', $email)->orWhere('ip_address', $ip);
            })
            ->whereNotNull('bloque_jusqu_au')
            ->where('bloque_jusqu_au', '>', now())
            ->exists();
    }

    // ─── Récupérer le blocage actif ──────────────────────
    public static function getBlocage(string $email, string $ip): ?self
    {
        return static::where(function ($q) use ($email, $ip) {
                $q->where('email', $email)->orWhere('ip_address', $ip);
            })
            ->whereNotNull('bloque_jusqu_au')
            ->where('bloque_jusqu_au', '>', now())
            ->orderByDesc('bloque_jusqu_au')
            ->first();
    }

    // ─── Compter les tentatives récentes ─────────────────
    public static function compterTentatives(string $email, string $ip): int
    {
        return static::where(function ($q) use ($email, $ip) {
                $q->where('email', $email)->orWhere('ip_address', $ip);
            })
            ->where('succes', false)
            ->where('tentative_at', '>=', now()->subMinutes(static::FENETRE_MINUTES))
            ->whereNull('bloque_jusqu_au')
            ->count();
    }

    // ─── Enregistrer une tentative ───────────────────────
    public static function enregistrer(
        string $email,
        string $ip,
        bool   $succes,
        bool   $bloquer = false
    ): void {
        static::create([
            'email'            => $email,
            'ip_address'       => $ip,
            'succes'           => $succes,
            'tentative_at'     => now(),
            'bloque_jusqu_au'  => $bloquer
                ? now()->addMinutes(static::BLOCAGE_MINUTES)
                : null,
        ]);
    }

    // ─── Nettoyer après succès ───────────────────────────
    public static function reinitialiser(string $email, string $ip): void
    {
        static::where('email', $email)
            ->orWhere('ip_address', $ip)
            ->delete();
    }
}

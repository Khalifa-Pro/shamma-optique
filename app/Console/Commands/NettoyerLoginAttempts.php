<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LoginAttempt;

class NettoyerLoginAttempts extends Command
{
    protected $signature   = 'auth:nettoyer-tentatives';
    protected $description = 'Supprime les anciennes tentatives de connexion';

    public function handle(): void
    {
        $supprimees = LoginAttempt::where('tentative_at', '<', now()->subHours(24))
            ->whereNull('bloque_jusqu_au')
            ->delete();

        $this->info("$supprimees tentative(s) supprimée(s).");
    }
}

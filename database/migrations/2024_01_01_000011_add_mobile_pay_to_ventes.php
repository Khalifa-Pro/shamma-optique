<?php
// database/migrations/2024_01_01_000011_add_mobile_pay_to_ventes.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modifier l'enum pour ajouter mobile_wave, mobile_orange, mobile_mtn
        \DB::statement("ALTER TABLE ventes MODIFY COLUMN mode_paiement ENUM(
            'especes','carte','virement','cheque','mobile_wave','mobile_orange','mobile_mtn'
        ) NOT NULL DEFAULT 'especes'");
    }

    public function down(): void
    {
        \DB::statement("ALTER TABLE ventes MODIFY COLUMN mode_paiement ENUM(
            'especes','carte','virement','cheque','mutuelle'
        ) NOT NULL DEFAULT 'especes'");
    }
};

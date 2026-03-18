<?php
// database/migrations/2024_01_01_000010_add_mode_paiement_assurance_to_ventes.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->string('mode_paiement_assurance')->nullable()->after('mode_paiement');
        });
    }

    public function down(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->dropColumn('mode_paiement_assurance');
        });
    }
};

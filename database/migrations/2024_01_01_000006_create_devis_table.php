<?php
// 2024_01_01_000004_create_devis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ordonnance_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            // ── Champs pro-forma ──────────────────────────
            $table->string('magasin')->nullable();
            $table->timestamp('valide_at')->nullable();
            $table->foreignId('valide_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('part_assurance', 10, 2)->default(0); // Part MCI CARE
            $table->decimal('part_client', 10, 2)->default(0);    // Part assuré

            $table->decimal('montant_total', 10, 2)->default(0);
            $table->enum('statut', ['brouillon', 'valide', 'facture', 'annule'])->default('brouillon');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('articles_devis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devis_id')->constrained()->cascadeOnDelete();

            // ── Champs pro-forma ──────────────────────────
            $table->foreignId('produit_id')->nullable()->constrained('produits')->nullOnDelete();
            $table->string('marque')->nullable();
            $table->boolean('inclus')->default(true); // Photogray, Antireflet, etc.

            $table->string('designation');
            $table->enum('type', [
                'monture',
                'verre_droit',
                'verre_gauche',
                'photogray',
                'antireflet',
                'accessoire',
                'autre'
            ])->default('autre');
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles_devis');
        Schema::dropIfExists('devis');
    }
};

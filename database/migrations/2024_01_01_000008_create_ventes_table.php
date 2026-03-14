<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('facture_id')->constrained()->cascadeOnDelete();
            $table->foreignId('devis_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->decimal('part_client', 10, 2)->default(0);
            $table->decimal('part_assurance', 10, 2)->default(0);
            $table->enum('mode_paiement', ['especes', 'carte', 'virement', 'cheque', 'mutuelle'])->default('especes');
            $table->date('date_paiement');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};

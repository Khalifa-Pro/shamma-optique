<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Catalogue produits
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique()->nullable();
            $table->string('designation');
            $table->string('marque')->nullable();
            $table->enum('categorie', [
                'monture_homme',
                'monture_femme',
                'monture_enfant',
                'monture_solaire',
                'verre_unifocal',
                'verre_progressif',
                'verre_degressif',
                'lentille',
                'produit_entretien',
                'accessoire',
                'autre'
            ])->default('autre');
            $table->decimal('prix_vente', 10, 2)->default(0);
            $table->decimal('prix_achat', 10, 2)->default(0);
            $table->integer('stock_actuel')->default(0);
            $table->integer('stock_minimum')->default(2);
            $table->boolean('actif')->default(true);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Mouvements de stock (entrées / sorties / ajustements)
        Schema::create('mouvements_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['entree', 'sortie', 'ajustement'])->default('sortie');
            $table->integer('quantite');
            $table->integer('stock_avant');
            $table->integer('stock_apres');
            $table->string('motif')->nullable();
            $table->nullableMorphs('source'); // pro_forma, facture, etc.
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_stock');
        Schema::dropIfExists('produits');
    }
};

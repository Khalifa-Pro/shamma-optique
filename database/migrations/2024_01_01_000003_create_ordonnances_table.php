<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordonnances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->date('date_ordonnance');
            $table->string('medecin')->nullable();
            // Oeil droit
            $table->string('od_sphere')->nullable();
            $table->string('od_cylindre')->nullable();
            $table->string('od_axe')->nullable();
            $table->string('od_addition')->nullable();
            // Oeil gauche
            $table->string('og_sphere')->nullable();
            $table->string('og_cylindre')->nullable();
            $table->string('og_axe')->nullable();
            $table->string('og_addition')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordonnances');
    }
};

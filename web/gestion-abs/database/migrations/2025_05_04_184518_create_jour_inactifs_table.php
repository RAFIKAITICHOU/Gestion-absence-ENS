<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jour_inactifs', function (Blueprint $table) {
            $table->id();
            $table->string('titre');             // Exemple : "Vacances de printemps"
            $table->date('date_debut');          // Date de début de l'inactivité
            $table->date('date_fin')->nullable(); // Date de fin (nullable si c’est un seul jour)
            $table->timestamps();                // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jour_inactifs');
    }
};

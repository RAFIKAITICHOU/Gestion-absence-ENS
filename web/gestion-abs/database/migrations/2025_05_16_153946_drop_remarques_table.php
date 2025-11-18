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
        Schema::dropIfExists('remarques');

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('remarques', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_presence')->constrained('presences')->onDelete('cascade');
        $table->text('remarque')->nullable();
        $table->float('bonus')->default(0);
        $table->timestamps();
    });
    }
};

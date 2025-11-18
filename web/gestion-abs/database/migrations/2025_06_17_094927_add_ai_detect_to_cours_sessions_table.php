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
        Schema::table('cours_sessions', function (Blueprint $table) {
                    $table->integer('ai_detect')->nullable()->after('id_professeur');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cours_sessions', function (Blueprint $table) {
            //
        });
    }
};

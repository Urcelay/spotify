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
        Schema::create('artists', function (Blueprint $table) {
            $table->id();
            // Se quitó id_artist porque no debe existir aquí
            $table->string('name');
            $table->string('cover_image')->nullable();
            $table->text('bio')->nullable(); // <-- Agregado
            $table->integer('release_year')->nullable();
            $table->integer('total_songs')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artists');
    }
};

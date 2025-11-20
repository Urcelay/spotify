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
        Schema::create('albums', function (Blueprint $table) {
            $table->id();

            // Relación con artistas
            $table->foreignId('id_artist')
                  ->constrained('artists')
                  ->onDelete('cascade');

            // Campos reales del álbum
            $table->string('name');
            $table->string('cover_image')->nullable();
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
        Schema::dropIfExists('albums');
    }
};

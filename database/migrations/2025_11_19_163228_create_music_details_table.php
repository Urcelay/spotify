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
        Schema::create('music_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_lista')->constrained('lista')->onDelete('cascade');
            $table->foreignId('id_music')->constrained('music')->onDelete('cascade');
            $table->integer('order')->default(0); // orden dentro de la lista
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music_details');
    }
};
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
        Schema::create('music', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('id_category')->constrained('categories')->onDelete('cascade');
            $table->foreignId('id_artist')->constrained('artists')->onDelete('cascade');
            $table->foreignId('id_album')->nullable()->constrained('albums')->onDelete('set null');
            $table->time('duration')->nullable();
            $table->integer('size')->nullable(); // en bytes
            $table->string('file_name');
            $table->string('file_url');
            $table->string('cover_image')->nullable();
            $table->integer('play_count')->default(0);
            $table->bigInteger('likes')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music');
    }
};
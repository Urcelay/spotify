<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuarios (si quieres más de los 10 por defecto de Breeze)
        \App\Models\User::factory(50)->create();

        // Categorías (Géneros)
        \App\Models\Category::factory(10)->create();

        // Artistas
        \App\Models\Artist::factory(20)->create();

        // Álbumes
        \App\Models\Album::factory(30)->create();

        // Canciones
        \App\Models\Music::factory(100)->create();

        // Favoritos
        \App\Models\Favorite::factory(50)->create();

        // Historial de reproducción
        \App\Models\History::factory(100)->create();

        \App\Models\Lista::factory(50)->create();

        // Relación lista-música
        \App\Models\MusicDetail::factory(200)->create();

    }
}

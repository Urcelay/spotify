<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebMusicController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//creamos rutas
//solo puede acceder un usuario AUTENTICADO

Route::middleware('auth')->group(function () {
    // Listar canciones
    Route::get('/music', [WebMusicController::class, 'index'])->name('music.index');

    // Formulario para crear nueva canción
    Route::get('/music/create', [WebMusicController::class, 'create'])->name('music.create');

    // Guardar nueva canción (subida a Cloudinary)
    Route::post('/music', [WebMusicController::class, 'store'])->name('music.store');

    // Eliminar canción
    Route::delete('/music/{id}', [WebMusicController::class, 'destroy'])->name('music.destroy');

    // Reproductor de música (vista especial)
    Route::get('/player', [WebMusicController::class, 'player'])->name('music.player');
});


require __DIR__.'/auth.php';

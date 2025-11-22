<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiUserController;
use App\Http\Controllers\ApiMusicController;
use App\Http\Controllers\ApiFavoriteController;
use App\Http\Controllers\ApiListaController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [ApiUserController::class, 'register']);
Route::post('/login', [ApiUserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [ApiUserController::class, 'logout']);

    Route::post('/music/{id}/favorite',[ApiFavoriteController::class, 'favorite']);
    Route::get('/favorites', [ApiFavoritesController::class, 'favorites']);

    Route::post('/lista/create', [ApiListaController::class, 'create']);
    Route::put('/lista/{id}/edit', [ApiListaController::class, 'edit']);
    Route::delete('/lista/{id}/delete', [ApiListaController::class, 'delete']);
    Route::get('/lista/{user_id}', [ApiListaController::class, 'getByUser']);

});

Route::get('/music/top', [ApiMusicController::class, 'top']);
Route::get('/music/filter', [ApiMusicController::class, 'filter']);
Route::get('/music/search', [ApiMusicController::class, 'search']);
Route::get('/music/{id}', [ApiMusicController::class, 'show']);
Route::post('/music/like/{id}', [ApiMusicController::class, 'like']);
Route::post('/music/play/{id}', [ApiMusicController::class, 'play']);


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Music;
use Illuminate\Support\Facades\Auth;

class ApiFavoriteController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/music/{id}/favorite",
     *     summary="Agregar o quitar una canción de favoritos (toggle)",
     *     tags={"Música"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la canción a marcar o desmarcar como favorita",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Resultado de la acción",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Canción eliminada de favoritos"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="music_id", type="integer", example=5)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Canción no encontrada o no autenticado")
     * )
     */
    public function favorite($id)
    {
        $user = Auth::user();
        $song = Music::find($id);

        if (!$song) {
            return response()->json(['message' => 'Canción no encontrada'], 404);
        }

        $favorite = Favorite::where('user_id', $user->id)
                            ->where('music_id', $id)
                            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'message' => 'Canción eliminada de favoritos',
                'data' => [
                    'user_id' => $user->id,
                    'music_id' => $id
                ]
            ], 200);
        }

        $newFavorite = Favorite::create([
            'user_id' => $user->id,
            'music_id' => $id
        ]);

        return response()->json([
            'message' => 'Canción agregada a favoritos',
            'data' => $newFavorite
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     summary="Obtener canciones favoritas del usuario autenticado",
     *     tags={"Música"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de canciones favoritas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=7),
     *                 @OA\Property(property="title", type="string", example="Uptown Funk"),
     *                 @OA\Property(property="file_url", type="string", example="https://..."),
     *                 @OA\Property(property="cover_image", type="string", example="https://..."),
     *                 @OA\Property(property="duration", type="string", example="00:04:31"),
     *                 @OA\Property(property="play_count", type="integer", example=700),
     *                 @OA\Property(property="likes", type="integer", example=300),
     *                 @OA\Property(property="artist", type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="name", type="string", example="Bruno Mars")
     *                 ),
     *                 @OA\Property(property="album", type="object",
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="name", type="string", example="24K Magic")
     *                 ),
     *                 @OA\Property(property="category", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Funk")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function favorites()
    {
        $user = Auth::user();

        $favorites = Favorite::where('user_id', $user->id)
            ->with([
                'music.artist:id,name',
                'music.album:id,name',
                'music.category:id,name'
            ])
            ->get()
            ->pluck('music');

        return response()->json($favorites, 200);
    }
}

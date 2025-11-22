<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lista;
use App\Models\MusicDetail;
use Illuminate\Support\Facades\Auth;

class ApiMusicDetailController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/lista/{id}/add",
     *     summary="Agregar canciones a una lista de reproducción",
     *     tags={"Lista Musica Detalle"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la lista a modificar",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"music_ids"},
     *             @OA\Property(
     *                 property="music_ids",
     *                 type="array",
     *                 @OA\Items(type="integer"),
     *                 example={1,2,5}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Canciones agregadas a la lista",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="Canciones agregadas correctamente"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="No tienes permiso para modificar esta lista"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Lista no encontrada"
     *     )
     * )
     */
    public function addToLista(Request $request, $id)
    {
        $lista = Lista::find($id);

        if (!$lista) {
            return response()->json(['message' => 'Lista no encontrada'], 404);
        }

        if ($lista->id_user !== Auth::id()) {
            return response()->json(['message' => 'No tienes permiso para modificar esta lista'], 403);
        }

        $request->validate([
            'music_ids' => 'required|array',
            'music_ids.*' => 'exists:music,id',
        ]);

        foreach ($request->music_ids as $music_id) {
            // evitar duplicados
            MusicDetail::firstOrCreate([
                'id_lista' => $lista->id,
                'id_music' => $music_id,
            ]);
        }

        return response()->json([
            'message' => 'Canciones agregadas correctamente'
        ], 200);
    }

/**
 * @OA\Get(
 *     path="/api/lista/{id}/songs",
 *     summary="Obtener las canciones de una lista de reproducción",
 *     tags={"Lista Musica Detalle"},
 *     security={{"sanctum": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la lista",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Contenido de la lista",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="lista",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=3),
 *                 @OA\Property(property="name", type="string", example="Mi playlist 2025"),
 *                 @OA\Property(property="is_public", type="boolean", example=true),
 *                 @OA\Property(property="user", type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="AnderCode"),
 *                 ),
 *             ),
 *             @OA\Property(
 *                 property="songs",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=12),
 *                     @OA\Property(property="title", type="string", example="Viva La Vida"),
 *                     @OA\Property(property="file_url", type="string", example="https://..."),
 *                     @OA\Property(property="duration", type="string", example="00:04:03"),
 *                     @OA\Property(property="artist", type="object",
 *                         @OA\Property(property="id", type="integer", example=7),
 *                         @OA\Property(property="name", type="string", example="Coldplay"),
 *                     ),
 *                     @OA\Property(property="album", type="object",
 *                         @OA\Property(property="id", type="integer", example=3),
 *                         @OA\Property(property="name", type="string", example="Viva la Vida"),
 *                     ),
 *                     @OA\Property(property="category", type="object",
 *                         @OA\Property(property="id", type="integer", example=1),
 *                         @OA\Property(property="name", type="string", example="Alternativo"),
 *                     ),
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(response=403, description="No tienes permiso para ver esta lista"),
 *     @OA\Response(response=404, description="Lista no encontrada")
 * )
 */
public function getSongs($id)
{
    $lista = Lista::with('user:id,name')->find($id);

    if (!$lista) {
        return response()->json(['message' => 'Lista no encontrada'], 404);
    }

    if (!$lista->is_public && $lista->id_user !== Auth::id()) {
        return response()->json(['message' => 'No tienes permiso para ver esta lista'], 403);
    }

    $songs = $lista->music()->with(['artist:id,name', 'album:id,name', 'category:id,name'])->get();

    return response()->json([
        'lista' => $lista,
        'songs' => $songs
    ]);
}

/**
 * @OA\Delete(
 *     path="/api/lista/{id}/remove/{music_id}",
 *     summary="Eliminar una canción de una lista de reproducción",
 *     tags={"Lista Musica Detalle"},
 *     security={{"sanctum": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la lista",
 *         @OA\Schema(type="integer", example=3)
 *     ),
 *     @OA\Parameter(
 *         name="music_id",
 *         in="path",
 *         required=true,
 *         description="ID de la canción a eliminar",
 *         @OA\Schema(type="integer", example=7)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Canción eliminada de la lista",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Canción eliminada de la lista")
 *         )
 *     ),
 *     @OA\Response(response=403, description="No tienes permiso para modificar esta lista"),
 *     @OA\Response(response=404, description="Lista o canción no encontrada")
 * )
 */
public function removeFromLista($id, $music_id)
{
    $lista = Lista::find($id);

    if (!$lista) {
        return response()->json(['message' => 'Lista no encontrada'], 404);
    }

    if ($lista->id_user !== Auth::id()) {
        return response()->json(['message' => 'No tienes permiso para modificar esta lista'], 403);
    }

    $detalle = MusicDetail::where('id_lista', $id)
        ->where('id_music', $music_id)
        ->first();

    if (!$detalle) {
        return response()->json(['message' => 'Canción no encontrada en esta lista'], 404);
    }

    $detalle->delete();

    return response()->json([
        'message' => 'Canción eliminada de la lista'
    ], 200);
}



}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Lista;
use App\Models\User;

class ApiListaController extends Controller
{

/**
 * @OA\Post(
 *     path="/api/lista/create",
 *     summary="Crear una nueva lista de reproducción",
 *     tags={"Listas"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="Gym Power Playlist"),
 *             @OA\Property(property="is_public", type="boolean", example=true)
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista creada exitosamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Lista creada exitosamente"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=4),
 *                 @OA\Property(property="name", type="string", example="Gym Power Playlist"),
 *                 @OA\Property(property="is_public", type="boolean", example=true)
 *             )
 *         )
 *     ),
 *     @OA\Response(response=422, description="Error de validación")
 * )
 */
public function create(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'is_public' => 'boolean'
    ]);

    $lista = Lista::create([
        'id_user' => Auth::id(),
        'name' => $request->name,
        'is_public' => $request->is_public ?? false
    ]);

    return response()->json([
        'message' => 'Lista creada exitosamente',
        'data' => $lista
    ], 200);
}

    /**
     * @OA\Put(
     *     path="/api/lista/{id}/edit",
     *     summary="Editar una lista de reproducción",
     *     tags={"Listas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la lista a editar",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Workout Vibes"),
     *             @OA\Property(property="is_public", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista actualizada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lista actualizada"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=5),
     *                 @OA\Property(property="name", type="string", example="Workout Vibes"),
     *                 @OA\Property(property="is_public", type="boolean", example=false)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=403, description="No tienes permiso para editar esta lista"),
     *     @OA\Response(response=404, description="Lista no encontrada")
     * )
     */
    public function edit(Request $request, $id)
    {
        $lista = Lista::find($id);

        if (!$lista) {
            return response()->json(['message' => 'Lista no encontrada'], 404);
        }

        if ($lista->id_user !== Auth::id()) {
            return response()->json(['message' => 'No tienes permiso para editar esta lista'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_public' => 'boolean'
        ]);

        $lista->update([
            'name' => $validated['name'],
            'is_public' => $validated['is_public'] ?? false
        ]);

        return response()->json([
            'message' => 'Lista actualizada',
            'data' => $lista
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/lista/{id}/delete",
     *     summary="Eliminar una lista de reproducción",
     *     tags={"Listas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la lista a eliminar",
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lista eliminada"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=7)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=403, description="No tienes permiso para eliminar esta lista"),
     *     @OA\Response(response=404, description="Lista no encontrada")
     * )
     */
    public function delete($id)
    {
        $lista = Lista::find($id);

        if (!$lista) {
            return response()->json(['message' => 'Lista no encontrada'], 404);
        }

        // Validar que el usuario es dueño de la lista
        if ($lista->id_user !== Auth::id()) {
            return response()->json(['message' => 'No tienes permiso para eliminar esta lista'], 403);
        }

        $lista->delete();

        return response()->json([
            'message' => 'Lista eliminada',
            'data' => [
                'id' => $id
            ]
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/lista/{user_id}",
     *     summary="Obtener todas las listas del usuario autenticado",
     *     tags={"Listas"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="user_id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario autenticado",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listas del usuario",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=4),
     *                 @OA\Property(property="name", type="string", example="Chill Mix"),
     *                 @OA\Property(property="is_public", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=403, description="No tienes permiso para ver estas listas"),
     *     @OA\Response(response=404, description="Usuario no encontrado")
     * )
     */
    public function getByUser($user_id)
    {
        if (Auth::id() != $user_id) {
            return response()->json(['message' => 'No tienes permiso para ver estas listas'], 403);
        }

        $listas = Lista::where('id_user', $user_id)->get();

        return response()->json($listas, 200);
    }


/**
 * @OA\Get(
 *     path="/api/lista/public",
 *     summary="Obtener todas las listas de reproducción públicas",
 *     tags={"Listas"},
 *     @OA\Response(
 *         response=200,
 *         description="Listado de listas públicas",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Chill Mix"),
 *                 @OA\Property(property="is_public", type="boolean", example=true),
 *                 @OA\Property(property="user", type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="AnderCode")
 *                 )
 *             )
 *         )
 *     )
 * )
 */
public function publiclistas()
{
    $listas = Lista::where('is_public', true)
        ->with('user:id,name')
        ->get();

    return response()->json($listas, 200);
}



}

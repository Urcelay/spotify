<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Music;
use App\Models\Favorite;

class ApiMusicController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/music/top",
     *     summary="Obtener el Top 10 de canciones más reproducidas",
     *     tags={"Música"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de las canciones top",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Bohemian Rhapsody"),
     *                 @OA\Property(property="file_url", type="string", example="https://res.cloudinary.com/csong/file.mp3"),
     *                 @OA\Property(property="cover_image", type="string", example="https://res.cloudinary.com/csong/img.jpg"),
     *                 @OA\Property(property="duration", type="string", example="00:05:55"),
     *                 @OA\Property(property="play_count", type="integer", example=1520),
     *                 @OA\Property(property="likes", type="integer", example=300),
     *                 @OA\Property(property="is_featured", type="boolean", example=true),
     *                 @OA\Property(
     *                     property="artist",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=4),
     *                     @OA\Property(property="name", type="string", example="Queen")
     *                 ),
     *                 @OA\Property(
     *                     property="album",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="name", type="string", example="Greatest Hits")
     *                 ),
     *                 @OA\Property(
     *                     property="category",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Rock")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function top()
    {
        $topSongs = Music::with([
            'artist:id,name',
            'album:id,name',
            'category:id,name'
        ])
        ->orderByDesc('play_count')
        ->take(10)
        ->get([
            'id',
            'title',
            'file_url',
            'cover_image',
            'duration',
            'play_count',
            'likes',
            'is_featured',
            'id_artist',
            'id_album',
            'id_category'
        ]);

        return response()->json($topSongs, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/music/filter",
     *     summary="Filtrar canciones por categoría, artista o álbum",
     *     tags={"Música"},
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="ID de la categoría",
     *         required=false,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Parameter(
     *         name="artist",
     *         in="query",
     *         description="ID del artista",
     *         required=false,
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\Parameter(
     *         name="album",
     *         in="query",
     *         description="ID del álbum",
     *         required=false,
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Canciones filtradas",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Nombre de canción"),
     *                 @OA\Property(property="file_url", type="string", example="https://..."),
     *                 @OA\Property(property="duration", type="string", example="00:03:45"),
     *                 @OA\Property(property="play_count", type="integer", example=500),
     *                 @OA\Property(property="cover_image", type="string", example="https://..."),
     *                 @OA\Property(
     *                     property="artist",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=4),
     *                     @OA\Property(property="name", type="string", example="Nombre del artista")
     *                 ),
     *                 @OA\Property(
     *                     property="album",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="name", type="string", example="Nombre del álbum")
     *                 ),
     *                 @OA\Property(
     *                     property="category",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Rock")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function filter(Request $request)
    {
        $query = Music::query()->with([
            'artist:id,name',
            'album:id,name',
            'category:id,name',
        ]);

        if ($request->has('category')) {
            $query->where('id_category', $request->category);
        }

        if ($request->has('artist')) {
            $query->where('id_artist', $request->artist);
        }

        if ($request->has('album')) {
            $query->where('id_album', $request->album);
        }

        $songs = $query->get([
            'id',
            'title',
            'file_url',
            'cover_image',
            'duration',
            'play_count',
            'likes',
            'is_featured',
            'id_artist',
            'id_album',
            'id_category'
        ]);

        return response()->json($songs, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/music/search",
     *     summary="Buscar canciones por título",
     *     tags={"Música"},
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Palabra clave del título de la canción",
     *         required=true,
     *         @OA\Schema(type="string", example="love")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de canciones que coinciden",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Love Story"),
     *                 @OA\Property(property="file_url", type="string", example="https://..."),
     *                 @OA\Property(property="cover_image", type="string", example="https://..."),
     *                 @OA\Property(property="duration", type="string", example="00:04:12"),
     *                 @OA\Property(property="play_count", type="integer", example=320),
     *                 @OA\Property(property="likes", type="integer", example=50),
     *                 @OA\Property(property="is_featured", type="boolean", example=true),
     *                 @OA\Property(
     *                     property="artist",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="name", type="string", example="Taylor Swift")
     *                 ),
     *                 @OA\Property(
     *                     property="album",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=2),
     *                     @OA\Property(property="name", type="string", example="Fearless")
     *                 ),
     *                 @OA\Property(
     *                     property="category",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Pop")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Consulta vacía"
     *     )
     * )
     */
    public function search(Request $request)
    {
        $query = $request->query('query');

        if (!$query) {
            return response()->json(['message' => 'El parámetro "query" es requerido.'], 400);
        }

        $songs = Music::with(['artist:id,name', 'album:id,name', 'category:id,name'])
            ->where('title', 'like', '%' . $query . '%')
            ->get([
                'id',
                'title',
                'file_url',
                'cover_image',
                'duration',
                'play_count',
                'likes',
                'is_featured',
                'id_artist',
                'id_album',
                'id_category'
            ]);

        return response()->json($songs, 200);
    }
/**
 * @OA\Get(
 *     path="/api/music/{id}",
 *     summary="Obtener detalle de una canción por ID",
 *     tags={"Música"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la canción",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Detalle de la canción",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", example=1),
 *             @OA\Property(property="title", type="string", example="Shape of You"),
 *             @OA\Property(property="file_url", type="string", example="https://..."),
 *             @OA\Property(property="cover_image", type="string", example="https://..."),
 *             @OA\Property(property="duration", type="string", example="00:03:54"),
 *             @OA\Property(property="play_count", type="integer", example=1800),
 *             @OA\Property(property="likes", type="integer", example=200),
 *             @OA\Property(property="is_featured", type="boolean", example=false),
 *             @OA\Property(
 *                 property="artist",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=4),
 *                 @OA\Property(property="name", type="string", example="Ed Sheeran")
 *             ),
 *             @OA\Property(
 *                 property="album",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=3),
 *                 @OA\Property(property="name", type="string", example="Divide")
 *             ),
 *             @OA\Property(
 *                 property="category",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=2),
 *                 @OA\Property(property="name", type="string", example="Pop")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Canción no encontrada"
 *     )
 * )
 */
public function show($id)
{
    $song = Music::with(['artist:id,name', 'album:id,name', 'category:id,name'])
        ->find($id, [
            'id',
            'title',
            'file_url',
            'cover_image',
            'duration',
            'play_count',
            'likes',
            'is_featured',
            'id_artist',
            'id_album',
            'id_category'
        ]);

    if (!$song) {
        return response()->json(['message' => 'Canción no encontrada'], 404);
    }

    return response()->json($song, 200);
}

/**
 * @OA\Post(
 *     path="/api/music/like/{id}",
 *     summary="Registrar un like a una canción",
 *     tags={"Música"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la canción a la que se dará like",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Like registrado correctamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Like registrado"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="title", type="string", example="Shape of You"),
 *                 @OA\Property(property="likes", type="integer", example=151)
 *             )
 *         )
 *     ),
 *     @OA\Response(response=404, description="Canción no encontrada")
 * )
 */
public function like($id)
{
    $song = Music::find($id);

    if (!$song) {
        return response()->json(['message' => 'Canción no encontrada'], 404);
    }

    $song->increment('likes');

    return response()->json([
        'message' => 'Like registrado',
        'data' => $song
    ], 200);
}

/**
 * @OA\Post(
 *     path="/api/music/play/{id}",
 *     summary="Registrar una reproducción (play) a una canción",
 *     tags={"Música"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la canción a reproducir",
 *         @OA\Schema(type="integer", example=2)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reproducción registrada",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Reproducción registrada"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="id", type="integer", example=2),
 *                 @OA\Property(property="title", type="string", example="Thunderstruck"),
 *                 @OA\Property(property="play_count", type="integer", example=542)
 *             )
 *         )
 *     ),
 *     @OA\Response(response=404, description="Canción no encontrada")
 * )
 */
public function play($id)
{
    $song = Music::find($id);

    if (!$song) {
        return response()->json(['message' => 'Canción no encontrada'], 404);
    }

    $song->increment('play_count');

    return response()->json([
        'message' => 'Reproducción registrada',
        'data' => $song
    ], 200);
}


}
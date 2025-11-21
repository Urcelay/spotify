<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Music;
use App\Models\Category;
use App\Models\Artist;
use App\Models\Album;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class WebMusicController extends Controller
{
    //Listar toda la música
    public function index(){
        $musicas = Music::all(); 
        return view('music.index', compact('musicas'));
    }

    //Formulario de creación
    public function create(){
        $categories = Category::all();
        $artists   = Artist::all();
        $albums   = Album::all();

        return view('music.create', compact('categories','artists','albums'));
    }

    //Insertar la información
    public function store(Request $request){
        try {

            // Validación
            $request->validate([
                'title'       => 'required|string|max:255',
                'id_category' => 'required|exists:categories,id',
                'id_artist'   => 'required|exists:artists,id',
                'id_album'    => 'nullable|exists:albums,id',
                'duration'    => 'required|integer',
                'file'        => 'required|mimes:mp3|max:20480'
            ]);

            // Obtener archivo
            $file = $request->file('file');

            // Subir a Cloudinary
            $upload = Cloudinary::uploadFile(
                $file->getRealPath(),
                [
                    'resource_type' => 'auto',
                    'folder'        => 'music'
                ]
            );

            // URL del archivo subido
            $fileUrl = $upload->getSecurePath();

            // Crear registro en base de datos
            $music = Music::create([
                'title'       => $request->title,
                'id_category' => $request->id_category,
                'id_artist'   => $request->id_artist,
                'id_album'    => $request->id_album,
                'duration'    => gmdate("H:i:s", $request->duration),
                'size'        => $file->getSize() / 1024,
                'file_name'   => $file->getClientOriginalName(),
                'file_url'    => $fileUrl,
                'cover_image' => null,
                'play_count'  => 0,
                'likes'       => 0,
                'is_featured' => false
            ]);

            return response()->json([
                'message' => 'Canción subida exitosamente',
                'data'    => $music
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error al subir la canción',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    //Eliminar canción
    public function destroy($id){
        $musica = Music::find($id);

        if (!$musica) {
            return redirect()->route('music.index')
                ->with('error', 'Canción no encontrada.');
        }

        $musica->delete();

        return redirect()->route('music.index')
            ->with('success', 'Canción eliminada correctamente.');
    }

    //Reproductor
    public function player(){
        $musicas = Music::all();
        return view('music.player', compact('musicas'));
    }
}

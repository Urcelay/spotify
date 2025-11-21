<!--ingresar una nueva cancion-->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista de canciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-md rounded-lg p-6">
                <!-- Botón para agregar nueva canción -->
                <div class="mb-4 flex justify-between">
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                        🎵 Canciones Disponibles
                    </h3>
                    <a href="{{ route('music.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        + Agregar Nueva Canción
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!--Tabla, listado de canciones-->
    <div class="overflow-x-auto rounded-lg">
        <table class="w-full border-collapse bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-sm text-gray-800 dark:text-gray-200 ">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 border">ID</th>
                    <th class="px-6 py-3 border">Título</th>
                    <th class="px-6 py-3 border">Categoría</th>
                    <th class="px-6 py-3 border">Artista</th>
                    <th class="px-6 py-3 border">Álbum</th>
                    <th class="px-6 py-3 border">Tamaño</th>
                    <th class="px-6 py-3 border">Reproducir</th>
                    <th class="px-6 py-3 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($musicas as $musica)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-3 border text-center">{{ $musica->id }}</td>
                        <td class="px-6 py-3 border">{{ $musica->title }}</td>
                        <td class="px-6 py-3 border">{{ $musica->category->name ?? 'Sin categoría' }}</td>
                        <td class="px-6 py-3 border">{{ $musica->artist->name ?? 'Sin artista' }}</td>
                        <td class="px-6 py-3 border">{{ $musica->album->name ?? 'Sin álbum' }}</td>
                        <td class="px-6 py-3 border text-center">{{ $musica->size }} KB</td>
                        <td class="px-6 py-3 border text-center">
                            <audio controls class="w-32">
                                <source src="{{ $musica->file_url }}" type="audio/mpeg" />
                                Tu navegador no soporta audio.
                            </audio>
                        </td>
                        <td class="px-6 py-3 border text-center">
                            <form action="{{ route('music.destroy', $musica->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-1 bg-red-600 text-white rounded-md">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach 
            </tbody>
        </table>
    </div>
</x-app-layout>
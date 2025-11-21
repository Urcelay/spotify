<!-- Registrar musica -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Agregar Nueva Canción') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-md rounded-lg p-6">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Oops!</strong> Corrige los siguientes errores:<br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="uploadForm" action="{{ route('music.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Archivo MP3 -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Archivo MP3:</label>
                        <input type="file" id="file" name="file" accept="audio/mp3"
                               class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800"
                               required onchange="cargarDatosAudio()">
                    </div>

                    <!-- Título -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">
                            Título de la canción
                        </label>
                        <input type="text" id="title" name="title"
                               class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800"
                               required>
                    </div>

                    <!-- Categoría -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Categoría:</label>
                        <select id="id_category" name="id_category"
                                class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800"
                                required>
                            <option value="">Seleccione una categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Artista -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Artista:</label>
                        <select id="id_artist" name="id_artist"
                                class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800"
                                required>
                            <option value="">Seleccione un artista</option>
                            @foreach ($artists as $artist)
                                <option value="{{ $artist->id }}">{{ $artist->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Álbum -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">Álbum:</label>
                        <select id="id_album" name="id_album"
                                class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800">
                            <option value="">Sin álbum</option>
                            @foreach ($albums as $album)
                                <option value="{{ $album->id }}">{{ $album->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Duración -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200 text-sm font-bold mb-2">
                            Duración (segundos):
                        </label>
                        <input type="number" id="duration" name="duration"
                               class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800"
                               required>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-between">
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            🎵 Subir Canción
                        </button>

                        <button type="button"
                                onclick="window.location.href='{{ route('music.index') }}'"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            ❌ Cancelar
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                console.log("✅ Script cargado correctamente");

                let form = document.getElementById("uploadForm");
                if (!form) {
                    console.error("❌ No se encontró el formulario con ID 'uploadForm'");
                    return;
                }

                form.addEventListener("submit", async function(event) {
                    event.preventDefault();
                    console.log("📌 Evento submit capturado correctamente");

                    let formData = new FormData(form);

                    // Mostrar loading
                    Swal.fire({
                        title: "⬆️ Subiendo Canción...",
                        text: "Por favor, espera mientras se procesa la subida.",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    try {
                        let response = await fetch("{{ route('music.store') }}", {
                            method: "POST",
                            body: formData
                        });

                        if (!response.ok) throw new Error("Respuesta no OK");

                        let result = await response.json();
                        console.log("✅ Respuesta del servidor:", result);

                        Swal.fire({
                            title: "✅ Canción Subida",
                            text: result.message,
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.location.href = "{{ route('music.index') }}";
                        });

                    } catch (error) {
                        console.error("❌ Error al subir la canción:", error);

                        Swal.fire({
                            title: "❌ Error",
                            text: "Hubo un problema al subir la canción.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            });
        </script>

        <script>
            function cargarDatosAudio(){
                let fileInput = document.getElementById("file");
                let file = fileInput.files[0];

                if (!file) return;

                console.log("🎵 Archivo seleccionado:", file.name);

                // Autocompletar título
                let fileName = file.name.replace(/\.[^/.]+$/, "");
                document.getElementById("title").value = fileName;

                let audio = new Audio();
                audio.src = URL.createObjectURL(file);

                audio.addEventListener("loadedmetadata", function () {
                    let duration = Math.round(audio.duration);
                    console.log("⏱️ Duración:", duration, "segundos");
                    document.getElementById("duration").value = duration;
                });

                audio.addEventListener("ended", function () {
                    URL.revokeObjectURL(audio.src);
                });
            }
        </script>
    @endsection
</x-app-layout>

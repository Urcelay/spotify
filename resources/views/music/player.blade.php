<!-- Reproductor de música -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reproductor de música') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">🎵 Lista de Canciones</h3>
            <ul id="playlist" class="space-y-3">
                @foreach ($musicas as $musica)
                <li class="p-2 bg-gray-100 dark:bg-gray-700 rounded-md flex justify-between items-center">
                    <a href="#" onclick="playMusic('{{ $musica->file_url }}', '{{ addslashes($musica->title) }}'); return false;" 
                       class="text-gray-800 dark:text-gray-200 hover:underline flex items-center">
                        <span class="ml-2">{{ $musica->title }}</span>
                    </a>
                    <button onclick="playMusic('{{ $musica->file_url }}', '{{ addslashes($musica->title) }}')" 
                        class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        ▶️ Reproducir
                    </button>
                </li>
                @endforeach
            </ul>

            <!-- Controles del Reproductor -->
            <div class="mt-6 text-center">
                <h3 id="currentTitle" class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                    Seleccione una canción</h3>
                <div class="mt-3 flex justify-center space-x-4">
                    <button onclick="prevTrack()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">◀️ Anterior</button>
                    <button onclick="togglePlay()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">⏯️ Play</button>
                    <button onclick="nextTrack()" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">▶️ Siguiente</button>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js"> </script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js"></script>
<script>
let playlist = @json($musicas);
let currentTrackIndex = 0;
let sound = null;

function playMusic(url, title) {
    if (sound) sound.stop();
    sound = new Howl({
        src: [url],
        html5: true,
        volume: 1.0,
        onend: function () {
            nextTrack();
        }
    });
    sound.play();
    document.getElementById("currentTitle").innerText = title;
    currentTrackIndex = playlist.findIndex(m => m.file_url === url);
}

function togglePlay() {
    if (sound && sound.playing()) {
        sound.pause();
    } else if (sound) {
        sound.play();
    }
}

function nextTrack() {
    if (currentTrackIndex < playlist.length - 1) {
        currentTrackIndex++;
    } else {
        currentTrackIndex = 0;
    }
    playMusic(playlist[currentTrackIndex].file_url, playlist[currentTrackIndex].title);
}

function prevTrack() {
    if (currentTrackIndex > 0) {
        currentTrackIndex--;
    } else {
        currentTrackIndex = playlist.length - 1;
    }
    playMusic(playlist[currentTrackIndex].file_url, playlist[currentTrackIndex].title);
}
</script>



@endsection

</x-app-layout>

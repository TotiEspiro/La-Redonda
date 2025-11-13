@extends('layouts.app')

@section('content')
    <!-- Sección Introducción -->
    <section class="py-16">
        <div class="container max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Texto Introducción -->
                <div>
                    <h1 class="text-4xl font-semibold text-text-dark mb-8 border-b-2 border-black pb-2">Bienvenidos a La Redonda Joven</h1>

                    <div class="space-y-4">
                        <p class="text-text-dark">La Iglesia de la Inmaculada Concepción, conocida cariñosamente como "La Redonda", es un faro de fe y comunidad en el corazón de Belgrano.</p>
                        <p class="text-text-dark">Con casi 150 años de historia, nuestra iglesia combina la rica tradición católica con una vibrante vida comunitaria que acoge a personas de todas las edades.</p>
                        <p class="text-text-dark">En La Redonda Joven, creemos en el poder transformador del evangelio y en la importancia de construir una comunidad donde cada persona se sienta valorada y acompañada.</p>
                        <p class="text-text-dark">Ofrecemos diversos grupos y actividades para jóvenes, adultos y familias, buscando crecer juntos en la fe y el servicio a los demás.</p>
                        <p class="text-text-dark">Te invitamos a ser parte de nuestra familia parroquial, donde juntos podemos construir un mundo más fraterno según el corazón de Dios.</p>
                    </div>
                </div>

                <!-- Imagen -->
                <div>
                    <div>
                        <img src="img/iglesia_la_redonda.jpg" alt="La Redonda" class="m-auto">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Avisos Parroquiales -->
<!-- Avisos Parroquiales -->
<section class="py-8 bg-background-light">
    <div class="container max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-semibold text-center text-text-dark mb-8 border-b-2 border-black pb-2">Avisos Parroquiales</h2>
        
        @if(isset($announcements) && $announcements->count() > 0)
        <div class="carousel-container relative max-w-4xl mx-auto overflow-hidden">
            <div class="carousel-track flex transition-transform duration-300 ease-in-out" id="carouselTrack">
                @foreach($announcements as $index => $announcement)
                <!-- Tarjeta {{ $index + 1 }} -->
                <div class="announcement-card mx-4 bg-white rounded-xl overflow-hidden shadow-lg text-center flex flex-col">
                    <div class="announcement-image w-full h-48 bg-gray-200 flex items-center justify-center text-text-light overflow-hidden flex-shrink-0">
                        @if($announcement->image_url)
                            <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                [IMAGEN AVISO {{ $index + 1 }}]
                            </div>
                        @endif
                    </div>
                    <div class="announcement-content p-6 flex flex-col flex-1">
                        <h3 class="announcement-title text-xl mb-4 text-text-dark">{{ $announcement->title }}</h3>
                        <p class="text-text-dark mb-4 flex-1">{{ $announcement->short_description }}</p>
                        <button class="read-more-btn bg-button text-white px-6 py-2 border-none rounded cursor-pointer font-poppins hover:bg-blue-500 transition-colors mt-auto" data-modal="{{ $announcement->modal_id }}">
                            Leer Más
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            @if($announcements->count() > 2)
            <div class="carousel-nav flex justify-center gap-4 mt-8">
                <button class="carousel-arrow bg-button text-white w-12 h-12 rounded-full border-none cursor-pointer text-xl flex items-center justify-center hover:bg-blue-500 hover:scale-110 disabled:bg-gray-300 disabled:cursor-not-allowed disabled:opacity-60 transition-all" id="prevBtn">‹</button>
                <button class="carousel-arrow bg-button text-white w-12 h-12 rounded-full border-none cursor-pointer text-xl flex items-center justify-center hover:bg-blue-500 hover:scale-110 transition-all disabled:bg-gray-300 disabled:cursor-not-allowed disabled:opacity-60" id="nextBtn">›</button>
            </div>
            @endif
        </div>
        @else
        <div class="text-center py-8">
            <p class="text-text-light text-lg">No hay avisos parroquiales en este momento.</p>
            @auth
                @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.announcements.create') }}" class="inline-block mt-4 bg-button text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-500 transition-colors">
                    Crear Primer Anuncio
                </a>
                @endif
            @endauth
        </div>
        @endif
    </div>
</section>

    <!-- Evangelio del Día -->
<section class="py-12 bg-gospel-bg">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="gospel-content text-left max-w-4xl mx-auto">
            <h2 class="gospel-title text-3xl text-center font-semibold text-text-dark mb-6">Evangelio del Día</h2>
            
            @php
                $evangelioHoy = \App\Models\EvangelioDiario::obtenerEvangelioHoy();
            @endphp
            
            @if($evangelioHoy && $evangelioHoy->contenido)
                <p class="gospel-text italic text-lg mb-4 leading-loose">
                    {!! nl2br(e($evangelioHoy->contenido)) !!}
                </p>
                <p class="gospel-reference text-text-light text-right font-semibold">{{ $evangelioHoy->referencia }}</p>
            @else
                <!-- Texto por defecto -->
                <p class="gospel-text italic text-lg mb-4 leading-loose">
                    "Porque tanto amó Dios al mundo que dio a su Hijo único, para que todo el que crea en él no perezca, sino que tenga vida eterna. Porque Dios no envió a su Hijo para juzgar al mundo, sino para que el mundo se salve por él."
                </p>
                <p class="gospel-reference text-text-light text-right font-semibold">Juan 3:16-18</p>
            @endif
        </div>
    </div>
</section>

    <!-- Grupos Parroquiales -->
    <section class="py-8 bg-background-light">
        <div class="container max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-semibold text-center text-text-dark mb-8 border-b-2 border-black pb-2">Grupos Parroquiales</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Imagen -->
                <div>
                    <div class="groups-image w-full h-72 bg-gray-100 rounded-lg flex items-center justify-center text-text-light border-2 border-dashed border-gray-300">
                        [IMAGEN GRUPOS PARROQUIALES]
                    </div>
                </div>
                <!-- Texto -->
                <div class="space-y-4">
                    <p class="text-text-dark">En La Redonda Joven contamos con una diversidad de grupos que enriquecen nuestra vida comunitaria y espiritual:</p>
                    <p class="text-text-dark"><strong>Juventud:</strong> Grupo de jóvenes (18-35 años) que se reúne los viernes para compartir, orar y servir.</p>
                    <p class="text-text-dark"><strong>Adultos:</strong> Encuentros semanales de formación y oración para adultos y familias.</p>
                    <p class="text-text-dark"><strong>Mayores:</strong> Grupo de la tercera edad que se reúne los martes para compartir y apoyarse mutuamente.</p>
                    <p class="text-text-dark">Cada grupo ofrece un espacio de crecimiento espiritual, amistad y servicio comunitario.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Horarios -->
    <section class="py-8 bg-white">
        <div class="container max-w-7xl mx-auto px-4">
            <h2 class="text-3xl font-semibold text-center text-text-dark mb-8 border-b-2 border-black pb-2">Horarios</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Misas -->
                <div class="schedule-card text-center p-6 bg-white rounded-xl shadow-lg">
                    <div class="w-16 h-16 mx-auto mb-4 bg-nav-footer rounded-full flex items-center justify-center group-hover:bg-button transition-colors">
                    <img src="{{ asset('img/icono_misas.png') }}" alt="Misas" class="h-10">
                </div>
                    <h4 class="schedule-title text-sm mb-4 text-text-light uppercase">MISAS</h4>
                    <div class="schedule-times text-lg text-text-dark space-y-2">
                        <div><strong>Lunes a sabado:</strong>  10:00 | 17:30 | 19:30</div>
                        <div><strong>Domingo:</strong> 8:00 | 9:30 | 11:00 | 12:30 | 18:00 | 19:00 | 20:00</div>
                        <a class="text-button hover:text-blue-500 transition-all"  href="">Misas en vivo</a>
                    </div>
                </div>

                <!-- Confesiones -->
                <div class="schedule-card text-center p-6 bg-white rounded-xl shadow-lg">
                    <div class="w-16 h-16 mx-auto mb-4 bg-nav-footer rounded-full flex items-center justify-center group-hover:bg-button transition-colors">
                    <img src="{{ asset('img/icono_confesiones.png') }}" alt="Confesiones" class="h-10">
                </div>
                    <h4 class="schedule-title text-sm mb-4 text-text-light uppercase">CONFESIONES</h4>
                    <div class="schedule-times text-lg text-text-dark space-y-2">
                        <div><strong>Lunes a sabado:</strong> 10:30 a 12:00 y 18:00 a 19:00</div>
                        <div><strong>Domingo:</strong> Durante la misa</div>
                    </div>
                </div>

                <!-- Secretaría -->
                <div class="schedule-card text-center p-6 bg-white rounded-xl shadow-lg">
                    <div class="w-16 h-16 mx-auto mb-4 bg-nav-footer rounded-full flex items-center justify-center group-hover:bg-button transition-colors">
                    <img src="{{ asset('img/icono_secretaria.png') }}" alt="Secretaría" class="h-10">
                </div>
                    <h4 class="schedule-title text-sm mb-4 text-text-light uppercase">SECRETARÍA</h4>
                    <div class="schedule-times text-lg text-text-dark space-y-2">
                        <div><strong>Lunes a viernes:</strong> 16:00 a 19:00 </div>
                        <div><strong>Atención via Email:</strong> secretaria @inmaculada.org.ar</div>
                    </div>
                </div>

                <!-- Donaciones -->
                <div class="schedule-card text-center p-6 bg-white rounded-xl shadow-lg">
                   <div class="w-16 h-16 mx-auto mb-4 bg-nav-footer rounded-full flex items-center justify-center group-hover:bg-button transition-colors">
                    <img src="{{ asset('img/icono_donaciones.png') }}" alt="Donaciones" class="h-10">
                </div>
                    <h4 class="schedule-title text-sm mb-4 text-text-light uppercase">DONACIONES PRESENCIALES</h4>
                    <div class="schedule-times text-lg text-text-dark space-y-2">
                        <div>De ropas y alimentos. Traerlas por el templo</div>
                        <div><strong>Lunes a sabado:</strong>  9:00 a 21:00</div>
                        <div><strong>Domingo:</strong> 7:30 a 21:30</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modales para Avisos Dinámicos -->
@if(isset($announcements) && $announcements->count() > 0)
    @foreach($announcements as $announcement)
    <div id="{{ $announcement->modal_id }}" class="modal hidden fixed inset-0 bg-black bg-opacity-70 z-50 items-center justify-center p-4">
        <div class="modal-content bg-white p-8 rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <span class="modal-close absolute top-4 right-6 cursor-pointer text-2xl text-text-light hover:text-button transition-colors z-10 bg-white rounded-full w-8 h-8 flex items-center justify-center shadow-md">&times;</span>
            
            <!-- Imagen del anuncio -->
            @if($announcement->image_url)
            <div class="mb-6">
                <img src="{{ $announcement->image_url }}" 
                     alt="{{ $announcement->title }}" 
                     class="w-full h-64 object-cover rounded-lg shadow-md">
            </div>
            @endif
            
            <!-- Título -->
            <h3 class="text-3xl font-bold text-button mb-4 border-b-2 border-button pb-2">{{ $announcement->title }}</h3>
            
            <!-- Descripción corta -->
            @if($announcement->short_description)
            <div class="mb-4">
                <p class="text-lg text-text-dark font-semibold italic border-l-4 border-button pl-4 py-1 bg-gray-50 rounded">
                    {{ $announcement->short_description }}
                </p>
            </div>
            @endif
            
            <!-- Descripción completa -->
            <div class="text-text-dark mb-6 whitespace-pre-line leading-relaxed text-base">
                {!! nl2br(e($announcement->full_description)) !!}
            </div>
            
            <!-- Información adicional -->
            <div class="border-t border-gray-200 pt-4 mt-4">
                <div class="flex flex-wrap gap-4 text-sm text-text-light">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Publicado: {{ $announcement->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection

<script src="js/home.js"></script>
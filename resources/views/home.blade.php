@extends('layouts.app')

@section('content')
<section class="py-12 md:py-16">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
            
            <div class="order-1 lg:order-1">
                                <h1 class="text-3xl md:text-4xl font-semibold text-text-dark mb-6 md:mb-8 border-b-2 border-black pb-2 text-center md:text-left">Bienvenidos a La Redonda Joven</h1>


                <div class="space-y-4 text-base md:text-lg">
                    <p class="text-text-dark">La Iglesia de la Inmaculada Concepción, conocida cariñosamente como  <strong>"La Redonda"</strong>, es un faro de fe y comunidad en el corazón de Belgrano.</p>
                    <p class="text-text-dark">Con casi <strong>150 años</strong> de historia, nuestra iglesia combina la rica tradición católica con una vibrante vida comunitaria que acoge a personas de todas las edades.</p>
                    <p class="text-text-dark">En La Redonda Joven, creemos en el poder transformador del evangelio y en la importancia de construir una comunidad donde cada persona se sienta valorada y acompañada.</p>
                    <p class="text-text-dark">Ofrecemos diversos <strong>grupos y actividades para jóvenes, adultos y familias</strong>, buscando crecer juntos en la fe y el servicio a los demás.</p>
                    <p class="text-text-dark">Te invitamos a ser parte de nuestra familia parroquial, donde juntos podemos construir un mundo más fraterno según el corazón de Dios.</p>
                </div>
            </div>

            <div class="order-2 lg:order-2 mb-6 lg:mb-0 mt-8 lg:mt-0">
                <div>
                    <img src="img/iglesia_la_redonda.jpg" alt="La Redonda" class="m-auto rounded-xl shadow-2xl transform transition duration-500 hover:scale-105 hover:shadow-3xl w-full max-w-md h-auto object-cover">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-8 bg-background-light">
    <div class="container max-w-7xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-semibold text-center text-text-dark mb-8 border-b-2 border-black pb-2">Avisos Parroquiales</h2>

        @if(isset($announcements) && $announcements->count() > 0)
        <div class="carousel-outer-container relative max-w-5xl mx-auto">
            <div class="overflow-hidden py-8" id="carouselContainer">
                <div class="flex transition-transform duration-300 ease-in-out" id="carouselTrack">
                    @foreach($announcements as $index => $announcement)
                    <div class="announcement-wrapper flex-shrink-0 px-3 box-border flex justify-center">
                        <div class="announcement-card bg-white rounded-xl overflow-hidden shadow-lg text-center flex flex-col h-full border border-gray-100 w-full max-w-md mx-auto relative group hover:shadow-xl transition-shadow duration-300">
                            <div class="announcement-image w-full aspect-video bg-gray-200 flex items-center justify-center text-text-light overflow-hidden flex-shrink-0 relative">
                                @if($announcement->image_url)
                                    <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex flex-col items-center justify-center w-full h-full bg-gray-100 text-gray-400">
                                        <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span class="text-sm">Sin imagen</span>
                                    </div>
                                @endif
                            </div>
                            <div class="announcement-content p-5 flex flex-col flex-1">
                                <h3 class="announcement-title text-lg md:text-xl mb-3 text-text-dark font-bold line-clamp-2">{{ $announcement->title }}</h3>
                                <p class="text-text-light mb-4 flex-1 text-sm md:text-base line-clamp-3">{{ $announcement->short_description }}</p>
                                <button class="read-more-btn bg-button text-white px-5 py-2.5 border-none rounded-lg cursor-pointer font-poppins font-medium hover:bg-blue-500 transition-colors mt-auto w-full md:w-auto mx-auto" 
                                        data-modal="modal-{{ $announcement->id }}">
                                    Leer Más
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            @if($announcements->count() > 1)
            <div class="carousel-nav flex justify-center gap-4 mt-2">
                <button class="carousel-arrow bg-white text-button border-2 border-button w-10 h-10 md:w-12 md:h-12 rounded-full cursor-pointer text-xl flex items-center justify-center hover:bg-button hover:text-white disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm" id="prevBtn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button class="carousel-arrow bg-white text-button border-2 border-button w-10 h-10 md:w-12 md:h-12 rounded-full cursor-pointer text-xl flex items-center justify-center hover:bg-button hover:text-white disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm" id="nextBtn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
            @endif
        </div>
        @else
        <div class="text-center py-12 bg-white rounded-xl shadow-sm border border-gray-100 mx-4">
            <div class="text-gray-400 mb-3">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            </div>
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

<section class="py-12 bg-gospel-bg">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="gospel-content text-left max-w-4xl mx-auto">
            <h2 class="gospel-title text-2xl md:text-3xl text-center font-semibold text-text-dark mb-6">Evangelio del Día</h2>

            @php
                $evangelioHoy = \App\Models\EvangelioDiario::obtenerEvangelioHoy();
            @endphp

            @if($evangelioHoy && $evangelioHoy->contenido)
                <p class="gospel-text italic text-lg mb-4 leading-loose">
                    {!! nl2br(e($evangelioHoy->contenido)) !!}
                </p>
                <p class="gospel-reference text-text-light text-right font-semibold">{{ $evangelioHoy->referencia }}</p>
            @else
                <p class="gospel-text italic md:text-lg mb-4 leading-loose">
                    "Porque tanto amó Dios al mundo que dio a su Hijo único, para que todo el que crea en él no perezca, sino que tenga vida eterna. Porque Dios no envió a su Hijo para juzgar al mundo, sino para que el mundo se salve por él."
                </p>
                <p class="gospel-reference text-text-light text-right font-semibold">Juan 3:16-18</p>
            @endif
        </div>
    </div>
</section>

<section class="py-8 bg-background-light">
    <div class="container max-w-7xl mx-auto px-4 mt-1">
        <h2 class="text-2xl md:text-3xl font-semibold text-center text-text-dark mb-8 border-b-2 border-black pb-2">Grupos Parroquiales</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="flex items-center justify-center">
                   <img src="../img/imagen_grupos.jpg" class="rounded-lg" alt="Grupos">
                </div>
            </div>
            <div class="space-y-8">
                <p class="text-text-dark">En La Redonda Joven contamos con una diversidad de grupos que enriquecen nuestra vida comunitaria y espiritual:</p>
                <p class="text-text-dark"><strong>Juventud:</strong> Grupo de jóvenes (18-35 años) que se reúne los viernes para compartir, orar y servir.</p>
                <p class="text-text-dark"><strong>Adultos:</strong> Encuentros semanales de formación y oración para adultos y familias.</p>
                <p class="text-text-dark"><strong>Mayores:</strong> Grupo de la tercera edad que se reúne los martes para compartir y apoyarse mutuamente.</p>
                <p class="text-text-dark">Cada grupo ofrece un espacio de crecimiento espiritual, amistad y servicio comunitario.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="container max-w-7xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-semibold text-center text-text-dark mb-8 border-b-2 border-black pb-2">Horarios</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="schedule-card bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-auto md:h-full">
                <div class="schedule-header p-6 flex flex-row md:flex-col items-center justify-between md:justify-center cursor-pointer md:cursor-default select-none group bg-white relative z-10">
                    <div class="flex items-center gap-4 md:flex-col md:gap-6">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-nav-footer rounded-full flex items-center justify-center text-button shrink-0 group-hover:bg-button transition-colors">
                            <img src="{{ asset('img/icono_misas.png') }}" alt="Misas" class="h-8 md:h-9 w-auto">
                        </div>
                        <h4 class="text-sm font-bold  text-black uppercase md:mb-2 m-0">Misas</h4>
                    </div>
                    <div class="schedule-chevron md:hidden text-gray-400 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                
                <div class="schedule-content hidden md:block px-6 pb-8 pt-0 md:pt-2 bg-white border-t md:border-t-0 border-gray-50">
                    <div class="w-10 h-1 bg-button mx-auto mb-6 hidden md:block rounded-full opacity-20"></div>
                    <div class="space-y-5 text-text-dark text-center">
                        <div>
                            <span class="block font-bold text-gray-800 text-sm uppercase mb-1">Lunes a Sábado</span>
                            <p class="text-gray-600 text-base">10:00 | 17:30 | 19:30</p>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-800 text-sm uppercase mb-1">Domingo</span>
                            <p class="text-gray-600 text-base leading-relaxed">
                                08:00 | 09:30 | 11:00<br>
                                12:30 | 18:00 | 19:00<br>
                                20:00
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                        <a href="#" class="inline-flex items-center justify-center px-4 py-2 bg-blue-50 text-button rounded-lg text-sm font-semibold hover:bg-button hover:text-white transition-all duration-300 w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            Misas en vivo
                        </a>
                    </div>
                </div>
            </div>

            <div class="schedule-card bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-auto md:h-full">
                <div class="schedule-header p-6 flex flex-row md:flex-col items-center justify-between md:justify-center cursor-pointer md:cursor-default select-none group bg-white relative z-10">
                    <div class="flex items-center gap-4 md:flex-col md:gap-6">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-nav-footer rounded-full flex items-center justify-center text-button shrink-0 group-hover:bg-button transition-colors">
                            <img src="{{ asset('img/icono_confesiones.png') }}" alt="Confesiones" class="h-8 md:h-9 w-auto">
                        </div>
                        <h4 class="text-sm font-bold text-black uppercase md:mb-2 m-0">Confesiones</h4>
                    </div>
                    <div class="schedule-chevron md:hidden text-gray-400 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                
                <div class="schedule-content hidden md:block px-6 pb-8 pt-0 md:pt-2 bg-white border-t md:border-t-0 border-gray-50">
                    <div class="w-10 h-1 bg-button mx-auto mb-6 hidden md:block rounded-full opacity-20"></div>
                    <div class="space-y-6 text-text-dark text-center">
                        <div>
                            <span class="block font-bold text-gray-800 text-sm uppercase mb-2">Lunes a Sábado</span>
                            <div class="bg-gray-50 rounded-lg p-3 inline-block w-full">
                                <p class="text-gray-700 font-medium text-base">10:30 a 12:00</p>
                                <div class="h-px w-16 bg-gray-200 mx-auto my-2"></div>
                                <p class="text-gray-700 font-medium text-base">18:00 a 19:00</p>
                            </div>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-800 text-sm uppercase mb-1">Domingo</span>
                            <p class="text-gray-600 text-base italic">Durante las misas</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="schedule-card bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-auto md:h-full">
                <div class="schedule-header p-6 flex flex-row md:flex-col items-center justify-between md:justify-center cursor-pointer md:cursor-default select-none group bg-white relative z-10">
                    <div class="flex items-center gap-4 md:flex-col md:gap-6">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-nav-footer rounded-full flex items-center justify-center text-button shrink-0 group-hover:bg-button transition-colors">
                            <img src="{{ asset('img/icono_secretaria.png') }}" alt="Secretaría" class="h-8 md:h-9 w-auto">
                        </div>
                        <h4 class="text-sm font-bold text-black uppercase md:mb-2 m-0">Secretaría</h4>
                    </div>
                    <div class="schedule-chevron md:hidden text-gray-400 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                
                <div class="schedule-content hidden md:block px-6 pb-8 pt-0 md:pt-2 bg-white border-t md:border-t-0 border-gray-50">
                    <div class="w-10 h-1 bg-button mx-auto mb-6 hidden md:block rounded-full opacity-20"></div>
                    <div class="space-y-6 text-text-dark text-center">
                        <div>
                            <span class="block font-bold text-gray-800 text-sm uppercase mb-1">Atención Presencial</span>
                            <p class="block font-bold text-gray-800 text-sm uppercase mb-1 mt-5">Lunes a Viernes</p>
                            <p class="text-gray-800 text-base">16:00 a 19:00</p>
                        </div>
                        
                        <div class="bg-blue-50 p-4 rounded-xl">
                            <span class="block font-bold text-button text-xs uppercase mb-1">Consultas Online</span>
                            <a href="mailto:secretaria@inmaculada.org.ar" class="text-gray-700 font-medium text-xs hover:text-button hover:underline break-all block">
                                secretaria@inmaculada.org.ar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="schedule-card bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 flex flex-col h-auto md:h-full">
                <div class="schedule-header p-6 flex flex-row md:flex-col items-center justify-between md:justify-center cursor-pointer md:cursor-default select-none group bg-white relative z-10">
                    <div class="flex items-center gap-4 md:flex-col md:gap-6">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-nav-footer rounded-full flex items-center justify-center text-button shrink-0 group-hover:bg-button transition-colors">
                            <img src="{{ asset('img/icono_donaciones.png') }}" alt="Donaciones" class="h-8 md:h-9 w-auto">
                        </div>
                        <h4 class="text-sm font-bold  text-black uppercase md:mb-2 m-0">Donaciones</h4>
                    </div>
                    <div class="schedule-chevron md:hidden text-gray-400 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                
                <div class="schedule-content hidden md:block px-6 pb-8 pt-0 md:pt-2 bg-white border-t md:border-t-0 border-gray-50">
                    <div class="w-10 h-1 bg-button mx-auto mb-6 hidden md:block rounded-full opacity-20"></div>
                    <div class="space-y-5 text-text-dark text-center">
                        <p class="text-sm text-gray-500 italic bg-gray-50 px-2 py-1 rounded inline-block">Recepción de ropa y alimentos</p>
                        
                        <div>
                            <span class="block font-bold text-gray-800 text-sm uppercase mb-1">Lunes a Sábado</span>
                            <p class="text-gray-600 text-base">09:00 a 21:00</p>
                        </div>
                        <div>
                            <span class="block font-bold text-gray-800 text-sm uppercase mb-1">Domingo</span>
                            <p class="text-gray-600 text-base">07:30 a 21:30</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@if(isset($announcements) && $announcements->count() > 0)
    @foreach($announcements as $announcement)
    <div id="modal-{{ $announcement->id }}" class="modal hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70">
        <div class="modal-content bg-white w-full rounded-xl max-w-4xl h-auto max-h-[90vh] shadow-2xl flex flex-col overflow-hidden relative">
            
            <div class="absolute top-4 right-4 z-20">
                <button type="button" class="modal-close bg-white/90 hover:bg-white text-gray-500 hover:text-red-500 rounded-full w-10 h-10 flex items-center justify-center shadow-md transition-colors focus:outline-none">
                    <span class="text-2xl font-bold leading-none">&times;</span>
                </button>
            </div>

            <div class="p-6 md:p-8 overflow-y-auto custom-scrollbar">
                @if($announcement->image_url)
                <div class="mb-6 rounded-lg overflow-hidden shadow-sm">
                    <img src="{{ $announcement->image_url }}" alt="{{ $announcement->title }}" class="w-full h-auto max-h-[50vh] object-contain bg-gray-50 mx-auto">
                </div>
                @endif
                
                <h3 class="text-2xl md:text-3xl font-bold text-button mb-4 border-b-2 border-button pb-2 pr-10">{{ $announcement->title }}</h3>
                
                @if($announcement->short_description)
                <div class="mb-4">
                    <p class="text-base md:text-lg text-text-dark font-semibold italic border-l-4 border-button pl-4 py-2 bg-gray-50 rounded">{{ $announcement->short_description }}</p>
                </div>
                @endif
                
                <div class="text-text-dark mb-6 whitespace-pre-line leading-relaxed text-base text-justify">
                    {!! nl2br(e($announcement->full_description)) !!}
                </div>
                
                <div class="border-t border-gray-200 pt-4 mt-auto">
                    <div class="flex flex-wrap gap-4 text-sm text-text-light">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                            Publicado: {{ $announcement->created_at->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection

<script src="js/home.js"></script>

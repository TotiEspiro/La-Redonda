@extends('layouts.app')

@section('content')
<div class="py-8 md:py-12">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="text-center mb-8 md:mb-12">
            <h1 class="text-3xl md:text-4xl font-semibold text-text-dark mb-4 border-b-2 border-black pb-2 w-full md:w-auto">Grupos Parroquiales</h1>
            <p class="text-text-dark text-base md:text-lg max-w-3xl mx-auto text-center md:text-center mt-4 leading-relaxed">
                En <strong>La Redonda</strong> contamos con una diversidad de grupos que enriquecen nuestra vida comunitaria y espiritual. 
                Cada grupo ofrece un espacio de crecimiento espiritual, amistad y servicio comunitario.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">

            <a href="{{ route('grupos.catequesis') }}" class="group bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition-all transform hover:-translate-y-1 border border-gray-100">
                <div class="w-16 h-16 mx-auto mb-4 bg-nav-footer rounded-full flex items-center justify-center group-hover:bg-button transition-colors">
                    <img src="{{ asset('img/icono_catequesis.png') }}" alt="Catequesis" class="h-8 md:h-10 w-auto">
                </div>
                <h3 class="text-xl font-semibold text-text-dark mb-2 group-hover:text-button transition-colors">Catequesis</h3>
                <p class="text-text-light text-sm">Primeros pasos para formarte en tu camino de fe</p>
            </a>

            <a href="{{ route('grupos.jovenes') }}" class="group bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition-all transform hover:-translate-y-1 border border-gray-100">
                <div class="w-16 h-16 mx-auto mb-4 bg-nav-footer rounded-full flex items-center justify-center group-hover:bg-button transition-colors">
                    <img src="{{ asset('img/icono_gruposjovenes.png') }}" alt="Jovenes" class="h-8 md:h-10 w-auto">
                </div>
                <h3 class="text-xl font-semibold text-text-dark mb-2 group-hover:text-button transition-colors">Jóvenes</h3>
                <p class="text-text-light text-sm">Grupos para jóvenes entre 11 y 35 años</p>
            </a>

            <a href="{{ route('grupos.mayores') }}" class="group bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition-all transform hover:-translate-y-1 border border-gray-100">
                <div class="w-16 h-16 mx-auto mb-4 bg-nav-footer rounded-full flex items-center justify-center group-hover:bg-button transition-colors">
                    <img src="{{ asset('img/icono_gruposmayores.png') }}" alt="Mayores" class="h-8 md:h-10 w-auto">
                </div>
                <h3 class="text-xl font-semibold text-text-dark mb-2 group-hover:text-button transition-colors">Mayores</h3>
                <p class="text-text-light text-sm">Grupos para la tercera edad</p>
            </a>

            <a href="{{ route('grupos.especiales') }}" class="group bg-white rounded-xl shadow-lg p-6 text-center hover:shadow-xl transition-all transform hover:-translate-y-1 border border-gray-100">
                <div class="w-16 h-16 mx-auto mb-4 bg-nav-footer rounded-full flex items-center justify-center group-hover:bg-button transition-colors">
                     <img src="{{ asset('img/icono_gruposespeciales.png') }}" alt="Grupos Especiales" class="h-8 md:h-10 w-auto">
                </div>
                <h3 class="text-xl font-semibold text-text-dark mb-2 group-hover:text-button transition-colors">Más Grupos</h3>
                <p class="text-text-light text-sm">Grupos especializados y ministerios</p>
            </a>
        </div>
    </div>
</div>
@endsection
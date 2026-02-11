@extends('layouts.app')

@section('content')
<div class="w-full">
    {{-- Barra de Navegación entre Categorías --}}
    <div class="flex items-center justify-between mb-10 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 max-w-6xl mx-auto">
        <a href="{{ route('grupos.mayores') }}" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[-4px] transition-transform group">
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            </div>
            <span class="hidden sm:inline">Mayores</span>
        </a>
        <a href="{{ route('grupos.catequesis') }}" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[4px] transition-transform group">
            <span class="hidden sm:inline">Catequesis</span>
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 justify-center items-stretch max-w-7xl mx-auto">
        
        {{-- COMEDOR --}}
        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('img/grupo_comedor.jpg') }}" onerror="this.src='{{ asset('img/logo_redonda.png') }}'; this.classList.add('p-12', 'opacity-50');" alt="Comedor" class="w-full h-full object-cover">
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex flex-col mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Comedor</h3>
                    <span class="text-[10px] text-button font-medium bg-blue-50 px-2 py-0.5 rounded-full inline-block w-fit mt-1">Caridad</span>
                </div>
                <p class="text-text-light text-sm mb-6 flex-grow leading-relaxed">Servicio de alimentación y acompañamiento para hermanos en situación de calle.</p>
                @include('partials.group-join-button', ['slug' => 'comedor', 'nombre' => 'Comedor'])
            </div>
        </div>

        {{-- CARITAS --}}
        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('img/grupo_caritas.png') }}" onerror="this.src='{{ asset('img/logo_redonda.png') }}'; this.classList.add('p-12', 'opacity-50');" alt="Caritas" class="w-full h-full object-cover">
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex flex-col mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Cáritas</h3>
                    <span class="text-[10px] text-button font-medium bg-blue-50 px-2 py-0.5 rounded-full inline-block w-fit mt-1">Asistencia</span>
                </div>
                <p class="text-text-light text-sm mb-6 flex-grow leading-relaxed">Asistencia inmediata y promoción humana para familias con necesidades básicas.</p>
                @include('partials.group-join-button', ['slug' => 'caritas', 'nombre' => 'Cáritas'])
            </div>
        </div>

        {{-- NOCHE DE CARIDAD --}}
        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('img/grupo_caridad.jpg') }}" onerror="this.src='{{ asset('img/logo_redonda.png') }}'; this.classList.add('p-12', 'opacity-50');" alt="Noche de Caridad" class="w-full h-full object-cover">
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex flex-col mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Noche de Caridad</h3>
                    <span class="text-[10px] text-button font-medium bg-blue-50 px-2 py-0.5 rounded-full inline-block w-fit mt-1">Servicio</span>
                </div>
                <p class="text-text-light text-sm mb-6 flex-grow leading-relaxed">Recorrida nocturna semanal para llevar alimento, escucha y compañía.</p>
                @include('partials.group-join-button', ['slug' => 'caridad', 'nombre' => 'Noche de Caridad'])
            </div>
        </div>

    </div>
</div>
@endsection
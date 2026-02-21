@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4">
    <div class="max-w-2xl w-full text-center space-y-8 bg-white p-10 md:p-16 rounded-[3rem] shadow-2xl border border-blue-50 relative overflow-hidden">
        
        {{-- Decoración de fondo --}}
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-sky-50 rounded-full opacity-50"></div>
        
        <div class="relative z-10">
            {{-- Icono de Herramientas / Construcción --}}
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-2xl bg-blue-50 mb-8 transform rotate-3 shadow-sm">
                <svg class="h-10 w-10 text-button" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" />
                </svg>
            </div>

            {{-- Logo de La Redonda reemplazando el H1 --}}
            <div class="mb-10">
                <img src="{{ asset('img/logo_redonda.png') }}" alt="La Redonda" class="w-48 md:w-64 mx-auto h-auto animate-pulse">
                <h2 class="mt-6 text-xl md:text-2xl font-black text-text-dark uppercase tracking-widest">
                    Estamos en mantenimiento
                </h2>
            </div>

            <p class="text-lg text-text-light font-medium max-w-md mx-auto leading-relaxed">
                Estamos realizando algunas tareas para mejorar tu experiencia en nuestra comunidad parroquial. Volveremos a estar en línea muy pronto.
            </p>

            <div class="mt-12 space-y-6">
                <div class="flex items-center justify-center space-x-4">
                    <div class="h-1.5 w-1.5 bg-button rounded-full animate-bounce"></div>
                    <div class="h-1.5 w-1.5 bg-button rounded-full animate-bounce [animation-delay:0.2s]"></div>
                    <div class="h-1.5 w-1.5 bg-button rounded-full animate-bounce [animation-delay:0.4s]"></div>
                </div>
                
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">
                    Gracias por tu paciencia
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
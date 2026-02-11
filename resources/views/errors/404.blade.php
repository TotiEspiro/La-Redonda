@extends('layouts.app')

@section('content')
<div class="bg-background-light min-h-[70vh] flex items-center justify-center py-12 md:py-20">
    <div class="container max-w-4xl mx-auto px-4 text-center">
        {{-- Ilustración o Icono --}}
        <div class="mb-8 animate-bounce">
            <div class="inline-flex items-center justify-center w-24 h-24 md:w-32 md:h-32 bg-white rounded-full shadow-xl border-4 border-button">
                <svg class="w-12 h-12 md:w-16 md:h-16 text-button" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        {{-- Texto de Error --}}
        <h1 class="text-6xl md:text-8xl font-black text-button mb-4">404</h1>
        <h2 class="text-2xl md:text-3xl font-bold text-text-dark mb-6 uppercase tracking-tight">¡Ups! Página no encontrada</h2>
        
        <div class="max-w-md mx-auto mb-10">
            <p class="text-text-light text-lg">
                Parece que te has perdido en el camino. La página que buscas no existe o ha sido movida a una nueva ubicación.
            </p>
        </div>

        {{-- Acciones --}}
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('home') }}" class="w-full sm:w-auto bg-button text-white px-8 py-4 rounded-2xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-900 hover:scale-105 transition-all uppercase tracking-widest text-sm">
                Volver al Inicio
            </a>
            
            <button onclick="window.history.back()" class="w-full sm:w-auto bg-white text-text-dark border-2 border-gray-200 px-8 py-4 rounded-2xl font-bold hover:bg-gray-50 transition-all uppercase tracking-widest text-sm">
                Página Anterior
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .container {
        animation: fade-in 0.8s ease-out;
    }
</style>
@endsection
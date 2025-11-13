@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-background-light py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-text-dark">
                Recuperar Contraseña
            </h2>
            <p class="mt-2 text-center text-sm text-text-light">
                Te enviaremos un enlace para restablecer tu contraseña
            </p>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <div class="text-yellow-600 mb-2">
                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-yellow-800 mb-2">Funcionalidad en Desarrollo</h3>
            <p class="text-yellow-700 mb-4">El sistema de recuperación de contraseña estará disponible próximamente.</p>
            <p class="text-sm text-yellow-600">Por ahora, contacta con un administrador si necesitas ayuda.</p>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-button hover:underline">
                ← Volver al inicio de sesión
            </a>
        </div>
    </div>
</div>
@endsection
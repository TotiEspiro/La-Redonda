@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100 text-center">
        {{-- Icono de Reloj / Expirado --}}
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-50 mb-6">
            <svg class="h-10 w-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase tracking-tight">Enlace Expirado</h2>
            <p class="mt-4 text-sm text-text-light font-medium leading-relaxed">
                Lo sentimos, el enlace de verificación ha caducado por razones de seguridad. Los enlaces de activación son válidos por 60 minutos.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mt-4 p-4 text-[10px] font-black text-green-700 bg-green-50 rounded-2xl border border-green-100 uppercase tracking-widest">
                ¡Listo! Enviamos un nuevo enlace a tu casilla de correo.
            </div>
        @endif

        <div class="mt-8 flex flex-col gap-4">
            {{-- Formulario para solicitar nuevo link --}}
            <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
                @csrf
                <button type="submit" class="w-full py-4 px-4 bg-button text-white font-black rounded-2xl shadow-lg hover:scale-[1.02] transition-transform text-sm uppercase tracking-widest shadow-blue-100">
                    Solicitar nuevo enlace
                </button>
            </form>

            <a href="{{ route('login') }}" class="text-[10px] text-gray-400 font-bold uppercase hover:underline tracking-widest">
                Volver al inicio de sesión
            </a>
        </div>
    </div>
</div>

{{-- Pantalla de Carga integrada --}}
<div id="loadingScreenProgress" class="fixed inset-0 bg-white/90 backdrop-blur-sm flex flex-col items-center justify-center z-50" style="display: none;">
    <div class="text-center px-4">
        <img src="{{ asset('img/logo_redonda.png') }}" alt="La Redonda" class="w-24 md:w-28 mx-auto mb-6 h-auto animate-pulse">
        <div class="w-64 bg-gray-200 rounded-full h-1.5 mb-4 mx-auto overflow-hidden">
            <div id="loadingProgress" class="bg-button h-full rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <p class="text-text-dark font-black uppercase tracking-[0.2em] text-[10px]">Enviando correo <span id="loadingPercent">0</span>%</p>
    </div>
</div>

<script src="{{ asset('js/login.js') }}"></script>
<script>
    // Vinculamos el formulario de reenvío a la pantalla de carga de login.js
    document.getElementById('resendForm').addEventListener('submit', function() {
        if (typeof showProgressLoading === 'function') {
            showProgressLoading();
        }
    });
</script>
@endsection
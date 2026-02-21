@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100 text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-50 mb-6">
            <svg class="h-8 w-8 text-button" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        
        <h2 class="text-2xl font-black text-text-dark uppercase tracking-tight">Verificá tu cuenta</h2>
        <p class="mt-4 text-sm text-text-light font-medium leading-relaxed">
            ¡Gracias por sumarte! Antes de empezar, ¿podrías verificar tu dirección de correo haciendo clic en el enlace que te acabamos de enviar? Si no recibiste nada, con gusto te enviamos otro.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mt-4 p-4 text-[10px] font-black text-green-700 bg-green-50 rounded-2xl border border-green-100 uppercase tracking-widest">
                Se envió un nuevo enlace de verificación a tu mail.
            </div>
        @endif

        <div class="mt-8 flex flex-col gap-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full py-4 px-4 bg-button text-white font-black rounded-2xl shadow-lg hover:scale-[1.02] transition-transform text-sm uppercase tracking-widest">
                    Reenviar Correo
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-[10px] text-gray-400 font-bold uppercase hover:underline">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
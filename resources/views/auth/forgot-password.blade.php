@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase tracking-tighter">Recuperar</h2>
            <p class="mt-2 text-sm text-text-light font-medium">Ingresá tu email para recibir instrucciones</p>
        </div>

        {{-- Mensaje de éxito si Laravel envía el mail --}}
        @if (session('status'))
            <div class="p-4 mb-4 text-xs text-green-700 bg-green-50 rounded-2xl border border-green-100 font-bold">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-4 mb-4 text-xs text-red-700 bg-red-50 rounded-2xl border border-red-100">
                {{ $errors->first('email') }}
            </div>
        @endif

        {{-- Formulario real que apunta a la ruta de Laravel --}}
        <form action="{{ route('password.email') }}" method="POST" id="forgotPasswordForm" class="mt-8 space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Email de la cuenta</label>
                <input id="email" name="email" type="email" required 
                       class="mt-1 block w-full px-5 py-4 border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50"
                       placeholder="ejemplo@correo.com">
            </div>

            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-xs font-black text-white bg-button hover:bg-blue-900 transition-all active:scale-95 shadow-blue-100 uppercase tracking-widest">
                Enviar Enlace
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-[10px] text-button font-black uppercase hover:underline">Volver al inicio</a>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DE ÉXITO (Opcional, se puede disparar si hay session('status')) --}}
@if(session('status'))
<div id="successModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl p-8 mx-4 transform transition-all animate-fade-in">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-3xl bg-blue-50 mb-6 shadow-inner">
                <img src="{{ asset('img/icono_activo.png') }}" alt="Activo" class="h-12 w-12">
            </div>
            <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter mb-2">¡Enlace enviado!</h3>
            <p class="text-xs text-gray-500 font-medium leading-relaxed mb-8">Revisá tu bandeja de entrada para restablecer tu contraseña.</p>
            <button type="button" onclick="this.closest('#successModal').remove()" class="w-full py-4 rounded-2xl bg-button text-xs font-black text-white shadow-lg hover:bg-blue-900 transition-all uppercase tracking-widest active:scale-95">
                Entendido
            </button>
        </div>
    </div>
</div>
@endif
@endsection
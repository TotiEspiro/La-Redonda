@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase tracking-tighter">Recuperar</h2>
            <p class="mt-2 text-sm text-text-light font-medium">Ingresá tu email para recibir instrucciones</p>
        </div>

        {{-- Éxito al enviar --}}
        @if (session('status'))
            <div class="p-4 mb-4 text-xs text-green-700 bg-green-50 rounded-2xl border border-green-100 font-bold">
                {{ session('status') }}
            </div>
        @endif

        {{-- Error si el email no existe --}}
        @error('email')
            <div class="p-4 mb-4 text-xs text-red-700 bg-red-50 rounded-2xl border border-red-100">
                {{ $message }}
            </div>
        @enderror

        <form action="{{ route('password.email') }}" method="POST" id="forgotPasswordForm" class="mt-8 space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Email de la cuenta</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required 
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
@endsection
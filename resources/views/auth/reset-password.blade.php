@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase tracking-tighter">Nueva Contraseña</h2>
            <p class="mt-2 text-sm text-text-light font-medium">Ingresá tu nueva clave para acceder</p>
        </div>

        {{-- Alerta de Errores --}}
        @if ($errors->any())
            <div class="p-4 mb-4 text-xs text-red-700 bg-red-50 rounded-2xl border border-red-100">
                <ul class="list-disc list-inside font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="mt-8 space-y-6">
            @csrf

            {{-- Token de seguridad obligatorio enviado por Laravel en el mail --}}
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email (generalmente se pasa por la URL para seguridad) --}}
            <div>
                <label for="email" class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Confirmar Email</label>
                <input id="email" name="email" type="email" value="{{ $email ?? old('email') }}" required 
                       class="block w-full px-5 py-4 border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50"
                       placeholder="ejemplo@correo.com">
            </div>

            {{-- Nueva Contraseña --}}
            <div>
                <label for="password" class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Nueva Contraseña</label>
                <input id="password" name="password" type="password" required 
                       class="block w-full px-5 py-4 border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50">
            </div>

            {{-- Confirmar Contraseña --}}
            <div>
                <label for="password_confirmation" class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Repetir Contraseña</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required 
                       class="block w-full px-5 py-4 border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50">
            </div>

            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-xs font-black text-white bg-button hover:bg-blue-900 transition-all active:scale-95 shadow-blue-100 uppercase tracking-widest">
                Actualizar Contraseña
            </button>
        </form>
    </div>
</div>
@endsection
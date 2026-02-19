@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase tracking-tighter">Nueva Contraseña</h2>
            <p class="mt-2 text-sm text-text-light font-medium">
                Hola <strong>{{ $user->name ?? 'Feligrés' }}</strong>, ingresá tu nueva clave.
            </p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="mt-8 space-y-6">
            @csrf
            {{-- Usamos request()->route('token') como respaldo si la variable falla --}}
            <input type="hidden" name="token" value="{{ $token ?? request()->route('token') }}">

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Email Confirmado</label>
                <input type="email" name="email" value="{{ $email ?? request()->email }}" readonly 
                       class="block w-full px-5 py-4 border border-gray-100 rounded-2xl bg-gray-50 text-gray-400 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Nueva Contraseña</label>
                <input name="password" type="password" required autofocus 
                       class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm bg-gray-50/50" placeholder="Ingresá tu nueva contraseña">
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Confirmar Contraseña</label>
                <input name="password_confirmation" type="password" required 
                       class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm bg-gray-50/50"placeholder="Confirmá tu nueva contraseña">
            </div>

            <button type="submit" class="w-full py-4 bg-button text-white rounded-2xl font-black text-sm hover:bg-blue-900 transition-all">
                Actualizar Contraseña
            </button>
        </form>
    </div>
</div>
@endsection
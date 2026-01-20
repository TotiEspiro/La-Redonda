@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 md:space-y-8 bg-white p-6 md:p-8 rounded-xl shadow-lg">
        <div>
            <h2 class="mt-2 text-center text-2xl md:text-3xl font-extrabold text-text-dark">
                Crear Cuenta
            </h2>
            <p class="mt-2 text-center text-sm text-text-light">
                Registrate para acceder a todas las funciones
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form class="mt-6 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-text-dark">Nombre Completo</label>
                    <input id="name" name="name" type="text" required 
                           class="mt-1 block w-full px-4 py-3 md:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-button focus:border-button text-base"
                           value="{{ old('name') }}" placeholder="Ingresá tu nombre completo">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-text-dark">Email</label>
                    <input id="email" name="email" type="email" required 
                           class="mt-1 block w-full px-4 py-3 md:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-button focus:border-button text-base"
                           value="{{ old('email') }}" placeholder="Ingresá tu email">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-text-dark">Contraseña</label>
                    <input id="password" name="password" type="password" required 
                           class="mt-1 block w-full px-4 py-3 md:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-button focus:border-button text-base" placeholder="Mínimo 6 caracteres">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-text-dark">Confirmar Contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="mt-1 block w-full px-4 py-3 md:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-button focus:border-button text-base" placeholder="Confirma tu contraseña">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-button hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button transition-colors">
                    Registrarse
                </button>
            </div>

            <div class="text-center mt-4">
                <p class="text-sm text-text-light">
                    ¿Ya tenés cuenta? 
                    <a href="{{ route('login') }}" class="font-medium text-button hover:text-blue-500 hover:underline">Inicia sesión aca</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
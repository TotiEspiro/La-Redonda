@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 md:space-y-8 bg-white p-6 md:p-8 rounded-xl shadow-lg">
        <div>
            <h2 class="mt-2 text-center text-2xl md:text-3xl font-extrabold text-text-dark">
                Iniciar Sesión
            </h2>
            <p class="mt-2 text-center text-sm text-text-light">
                Accede a tu cuenta
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm">
                {{ session('success') }}
            </div>
        @endif

        <form id="loginForm" class="mt-6 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-4">
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
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-button hover:text-blue-500 hover:underline">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-button hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button transition-colors">
                    Iniciar Sesión
                </button>
            </div>

            <div class="text-center mt-4">
                <p class="text-sm text-text-light">
                    ¿No tenés cuenta? 
                    <a href="{{ route('register') }}" class="font-medium text-button hover:text-blue-500 hover:underline">Registrate aca</a>
                </p>
            </div>
        </form>
    </div>
</div>

<div id="loadingScreenProgress" class="fixed inset-0 bg-nav-footer flex flex-col items-center justify-center z-50" style="display: none;">
    <div class="text-center px-4">
        <img src="{{ asset('img/logo_redonda.png') }}" alt="La Redonda Joven" class="w-24 md:w-28 mx-auto mb-6 h-auto">
        
        <div class="w-64 bg-gray-200 rounded-full h-2 mb-4 mx-auto">
            <div id="loadingProgress" class="bg-button h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        
        <p class="text-text-dark">Cargando <span id="loadingPercent">0</span>%</p>
    </div>
</div>

<script src="js/login.js"></script>
@endsection
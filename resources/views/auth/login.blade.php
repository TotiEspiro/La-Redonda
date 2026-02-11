@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 md:space-y-8 bg-white p-6 md:p-8 rounded-xl shadow-lg">
        <div>
            <h2 class="mt-2 text-center text-2xl md:text-3xl font-extrabold text-text-dark">
                Iniciar Sesión
            </h2>
            <p class="mt-2 text-center text-sm text-text-light font-medium">
                Accede a tu cuenta
            </p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm font-bold animate-fade-in">
                {{ session('success') }}
            </div>
        @endif

        <form id="loginForm" class="mt-6 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label for="email" class="block text-xs font-black text-text-dark mb-1">Email</label>
                    <input id="email" name="email" type="email" required 
                           class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-base transition-all bg-gray-50/50"
                           value="{{ old('email') }}" placeholder="Ingresá tu email">
                    @error('email')
                        <p class="text-red-500 text-xs font-bold mt-2 uppercase">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-black text-text-dark mb-1">Contraseña</label>
                    <div class="relative group">
                        <input id="password" name="password" type="password" required 
                               class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-base transition-all bg-gray-50/50 pr-12" 
                               placeholder="Mínimo 8 caracteres">
                        
                        {{-- BOTÓN TOGGLE PASSWORD --}}
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-button transition-colors focus:outline-none">
                            <svg id="eyeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs font-bold mt-2 uppercase">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('password.request') }}" class="text-xs font-black text-button hover:underline ">
                    ¿Olvidaste tu contraseña?
                </a>
            </div>

            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm  font-black text-white bg-button hover:bg-blue-900 transition-all active:scale-95 shadow-blue-100">
                    Iniciar Sesión
                </button>
            </div>

            <div class="text-center mt-6">
                <p class="text-xs text-gray-400 font-bold">
                    ¿No tenés cuenta? 
                    <a href="{{ route('register') }}" class="text-button hover:underline ml-1">Registrate acá</a>
                </p>
            </div>
        </form>
    </div>
</div>

{{-- Pantalla de Carga --}}
<div id="loadingScreenProgress" class="fixed inset-0 bg-nav-footer flex flex-col items-center justify-center z-50" style="display: none;">
    <div class="text-center px-4">
        <img src="{{ asset('img/logo_redonda.png') }}" alt="La Redonda Joven" class="w-24 md:w-28 mx-auto mb-6 h-auto">
        
        <div class="w-64 bg-gray-200 rounded-full h-2 mb-4 mx-auto">
            <div id="loadingProgress" class="bg-button h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        
        <p class="text-text-dark">Cargando <span id="loadingPercent">0</span>%</p>
    </div>
</div>

<script src="{{ asset('js/login.js') }}"></script>
@endsection
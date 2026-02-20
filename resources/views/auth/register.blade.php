@extends('layouts.app')

{{-- Cargamos el script de Google Captcha en el head --}}
@section('head')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase">Crear Cuenta</h2>
            <p class="mt-2 text-sm text-text-light font-medium">Unite a la comunidad de La Redonda</p>
        </div>

        {{-- BOTONES SOCIALES --}}
        <div class="grid gap-4">
            <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center gap-3 py-3 px-4 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-all shadow-sm active:scale-95">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
                <span class="text-xs font-black text-text-dark uppercase tracking-widest">Unirse con Google</span>
            </a>
        </div>

        <div class="relative py-2">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
            <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-4 text-gray-400 font-bold tracking-widest">O con tu email</span></div>
        </div>

        {{-- Bloque de errores detallado --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-2xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-[10px] font-black text-red-700 uppercase tracking-widest">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="mt-8 space-y-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Nombre Completo</label>
                    <input name="name" type="text" required value="{{ old('name') }}"
                           class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50" placeholder="Ingresá tu nombre completo">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Email</label>
                    <input name="email" type="email" required value="{{ old('email') }}"
                           class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50" placeholder="Ingresá tu email">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Contraseña (Mín. 8 caracteres, números y mayúsculas)</label>
                    <input name="password" type="password" required 
                           class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50" placeholder="Ingresá tu contraseña">
                </div>
            
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Confirmar Contraseña</label>
                    <input name="password_confirmation" type="password" required 
                           class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50" placeholder="Confirmá tu contraseña">
                </div>
            </div>

            {{-- Widget de Google reCAPTCHA --}}
            <div class="flex justify-center py-2">
                {{-- Se agrega el fallback env por si el config no está mapeado --}}
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') ?? env('RECAPTCHA_SITE_KEY') }}"></div>
            </div>

            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-black text-white bg-button hover:bg-blue-900 transition-all active:scale-95 shadow-blue-100">
                Registrarse
            </button>

            <div class="text-center mt-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase">
                    ¿Ya tenés cuenta? 
                    <a href="{{ route('login') }}" class="text-button hover:underline ml-1">Iniciá Sesión</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
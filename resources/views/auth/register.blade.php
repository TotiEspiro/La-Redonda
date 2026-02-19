@extends('layouts.app')

{{-- Cargamos el script de Google Captcha en el head --}}
@section('head')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase tracking-tighter">Crear Cuenta</h2>
            <p class="mt-2 text-sm text-text-light font-medium">Únete a la comunidad de La Redonda</p>
        </div>

        {{-- BOTONES SOCIALES --}}
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center gap-3 py-3 px-4 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-all shadow-sm active:scale-95">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
                <span class="text-xs font-bold text-gray-600">Google</span>
            </a>
            <a href="{{ route('social.redirect', 'facebook') }}" class="flex items-center justify-center gap-3 py-3 px-4 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-all shadow-sm active:scale-95">
                <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-5 h-5" alt="Facebook">
                <span class="text-xs font-bold text-gray-600">Facebook</span>
            </a>
        </div>

        <div class="relative py-4">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
            <div class="relative flex justify-center text-xs uppercase"><span class="px-2 bg-white text-gray-400 font-bold tracking-widest">O con tu email</span></div>
        </div>

        {{-- Alertas de Errores Generales --}}
        @if ($errors->any())
            <div class="p-4 mb-4 text-xs text-red-700 bg-red-50 rounded-2xl border border-red-100">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="mt-4 space-y-5" id="registrationForm">
            @csrf
            
            {{-- Nombre --}}
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Nombre Completo</label>
                <input name="name" type="text" value="{{ old('name') }}" required 
                       class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50 @error('name') border-red-500 @enderror">
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Correo Electrónico</label>
                <input name="email" type="email" value="{{ old('email') }}" required 
                       class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50 @error('email') border-red-500 @enderror">
            </div>

            {{-- Password --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Contraseña</label>
                    <input name="password" type="password" required 
                           class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Confirmar</label>
                    <input name="password_confirmation" type="password" required 
                           class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50">
                </div>
            </div>

            
            {{-- Widget de Google reCAPTCHA --}}
            <div class="flex justify-center py-2">
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            </div>

            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-xs font-black text-white bg-button hover:bg-blue-900 transition-all active:scale-95 shadow-blue-100 uppercase tracking-widest">
                Crear Mi Cuenta
            </button>

            <div class="text-center mt-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                    ¿Ya tenés cuenta? 
                    <a href="{{ route('login') }}" class="text-button hover:underline ml-1">Inicia sesión acá</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
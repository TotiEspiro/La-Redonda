@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase">Iniciar Sesión</h2>
            <p class="mt-2 text-sm text-text-light font-medium">Accedé a tu cuenta en La Redonda</p>
        </div>

        {{-- BOTONES SOCIALES --}}
        <div class="grid gap-4">
            <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center gap-3 py-3 px-4 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-all shadow-sm active:scale-95">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
                <span class="text-xs font-bold text-gray-600">Google</span>
            </a>
        </div>

        <div class="relative py-4">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
            <div class="relative flex justify-center text-xs uppercase"><span class="px-2 bg-white text-gray-400 font-bold tracking-widest">O con tus datos</span></div>
        </div>

        {{-- Mensajes de Error de Autenticación --}}
        @if ($errors->any())
            <div class="p-4 mb-4 text-xs text-red-700 bg-red-50 rounded-2xl border border-red-100">
                <p class="font-bold">Error de ingreso:</p>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="mt-4 space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Email</label>
                <input name="email" type="email" value="{{ old('email') }}" required 
                       class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50" placeholder="Ingresá tu email">
            </div>

            <div>
                <div class="flex justify-between items-center mb-2 ml-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase">Contraseña</label>
                    <a href="{{ route('password.request') }}" class="text-[9px] font-black text-button uppercase hover:underline">¿Olvidaste tu contraseña?</a>
                </div>
                <input name="password" type="password" required 
                       class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50" placeholder="Ingresá tu contraseña">
            </div>

            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-black text-white bg-button hover:bg-blue-900 transition-all active:scale-95 shadow-blue-100">
                Iniciar Sesión
            </button>

            <div class="text-center mt-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase">
                    ¿No tenés cuenta? 
                    <a href="{{ route('register') }}" class="text-button hover:underline ml-1">Registrate acá</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
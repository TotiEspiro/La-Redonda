@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase tracking-tight">Iniciar Sesión</h2>
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

        <form action="{{ route('login') }}" method="POST" id="loginForm" class="mt-4 space-y-6">
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
                <div class="relative">
                    <input name="password" id="password" type="password" required 
                           class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50" placeholder="Ingresá tu contraseña">
                    <button type="button" onclick="togglePassword('password', 'eye-icon-login')" class="absolute inset-y-0 right-0 pr-5 flex items-center text-gray-400 hover:text-button transition-colors">
                        <svg id="eye-icon-login" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </button>
                </div>
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

{{-- Pantalla de Carga --}}
<div id="loadingScreenProgress" class="fixed inset-0 bg-nav-footer backdrop-blur-sm flex flex-col items-center justify-center z-50" style="display: none;">
    <div class="text-center px-4">
        <img src="{{ asset('img/logo_redonda.png') }}" alt="La Redonda" class="w-24 md:w-28 mx-auto mb-6 h-auto">
        <div class="w-64 bg-gray-200 rounded-full h-2 mb-4 mx-auto overflow-hidden">
            <div id="loadingProgress" class="bg-button h-full rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <p class="text-[10px] font-black text-text-dark uppercase tracking-widest">Accediendo <span id="loadingPercent">0</span>%</p>
    </div>
</div>

<script src="{{ asset('js/login.js') }}"></script>
<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />';
    }
}
</script>
@endsection
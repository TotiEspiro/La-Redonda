@extends('layouts.app')

{{-- 1. Cargamos el script de Google Captcha en el head --}}
@section('head')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase">Recuperar Contraseña</h2>
            <p class="mt-2 text-sm text-text-light font-medium">Ingresá tu email para recibir instrucciones</p>
        </div>

        {{-- Alerta de éxito de Laravel (status) --}}
        @if (session('status'))
            <div class="p-4 mb-4 text-xs text-green-700 bg-green-50 rounded-2xl border border-green-100 font-bold animate-pulse">
                {{ session('status') }}
            </div>
        @endif

        {{-- Alerta de Errores (Validación o Captcha) --}}
        @if ($errors->any())
            <div class="p-4 mb-4 text-xs text-red-700 bg-red-50 rounded-2xl border border-red-100">
                <ul class="list-disc list-inside font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORMULARIO: Action apunta a la ruta de Laravel y method POST --}}
        <form action="{{ route('password.email') }}" method="POST" id="forgotPasswordForm" class="mt-8 space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Email de tu cuenta</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required 
                       class="mt-1 block w-full px-5 py-4 border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50"
                       placeholder="Ingresá tu email">
            </div>

            {{-- 2. Widget de reCAPTCHA (Obligatorio para validación en el servidor) --}}
            <div class="flex justify-center py-2">
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            </div>

            <button type="submit" id="submitBtn" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-black text-white bg-button hover:bg-blue-900 transition-all active:scale-95 shadow-blue-100">
                Enviar Enlace
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-[10px] text-button font-black uppercase hover:underline">Volver al inicio</a>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DE ÉXITO VISUAL --}}
@if(session('status'))
<div id="successModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl p-8 mx-4 transform transition-all animate-in fade-in zoom-in duration-300">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-3xl bg-blue-50 mb-6 shadow-inner">
                <img src="{{ asset('img/icono_activo.png') }}" alt="Activo" class="h-12 w-12">
            </div>
            <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter mb-2">¡Mail Enviado!</h3>
            <p class="text-xs text-gray-500 font-medium leading-relaxed mb-8">Si el correo está registrado, recibirás un enlace de recuperación. No olvides revisar la carpeta de Spam.</p>
            <button type="button" onclick="this.closest('#successModal').remove()" class="w-full py-4 rounded-2xl bg-button text-xs font-black text-white shadow-lg hover:bg-blue-900 transition-all uppercase tracking-widest active:scale-95">
                Entendido
            </button>
        </div>
    </div>
</div>
@endif

<script>
// Prevenir envíos múltiples deshabilitando el botón tras el primer clic
document.getElementById('forgotPasswordForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = 'Enviando...';
    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-not-allowed');
});
</script>
@endsection
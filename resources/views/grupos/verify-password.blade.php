@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 py-12">
    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-3xl shadow-2xl border border-gray-100">
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-blue-50 rounded-2xl flex items-center justify-center shadow-inner mb-6">
                <img src="{{ asset('img/icono_grupos.png') }}" class="h-12 w-12 object-contain" alt="Icono de Grupo">
            </div>
            <h2 class="text-3xl font-black text-gray-900 uppercase tracking-tighter leading-none">
                Acceso Privado
            </h2>
            <p class="mt-4 text-sm text-gray-500 font-medium">
                Has sido aprobado para unirte a <br>
                <span class="font-black text-button uppercase text-lg">{{ $group->name }}</span>.
            </p>
            <p class="mt-7 text-[10px] text-gray-400 font-bold uppercase tracking-widest text-center">
                Ingresá el código para continuar
            </p>
        </div>

        {{-- Formulario con ruta sincronizada --}}
        <form class="mt-8 space-y-6" action="{{ route('grupos.verify-password', $groupRole) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div class="relative">
                    <input id="password" name="password" type="password" required autofocus
                        class="appearance-none rounded-2xl relative block w-full px-4 py-5 pr-14 bg-gray-50 border border-gray-200 text-gray-900 focus:outline-none focus:ring-2 focus:ring-button focus:border-transparent transition-all font-bold text-xs" 
                        placeholder="Ingresá el código de acceso">
                    
                    {{-- BOTÓN MOSTRAR/OCULTAR --}}
                    <button type="button" onclick="togglePasswordVisibility()" class="absolute right-4 top-1/2 -translate-y-1/2 p-2 text-gray-400 hover:text-button transition-colors focus:outline-none">
                        <svg id="eyeShow" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eyeHide" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.057 10.057 0 012.183-4.403M15 12a3 3 0 11-6 0 3 3 0 016 0zm6.362-3.638A9.956 9.956 0 0121.542 12c-1.274 4.057-5.064 7-9.542 7-1.447 0-2.812-.324-4.032-.904m3.582-11.096A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l3.582 3.582" />
                        </svg>
                    </button>
                </div>
            </div>

            @if ($errors->has('password'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-2xl animate-pulse">
                    <p class="text-[10px] font-black text-red-700 uppercase tracking-widest text-center">
                        {{ $errors->first('password') }}
                    </p>
                </div>
            @endif

            <button type="submit" 
                class="w-full flex justify-center py-5 px-4 border border-transparent text-sm font-black rounded-2xl text-white bg-button hover:bg-blue-900 shadow-xl shadow-blue-100 transition-all active:scale-95">
                Validar e Ingresar
            </button>
        </form>

        <div class="text-center border-t border-gray-50">
            <a href="{{ route('dashboard') }}" class="text-[10px] font-black text-gray-400 hover:text-button uppercase tracking-widest transition-colors">
                ← Volver al inicio
            </a>
        </div>
    </div>
</div>

<script>
    /**
     * Alterna la visibilidad de la contraseña
     */
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const showIcon = document.getElementById('eyeShow');
        const hideIcon = document.getElementById('eyeHide');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            showIcon.classList.add('hidden');
            hideIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            showIcon.classList.remove('hidden');
            hideIcon.classList.add('hidden');
        }
    }
</script>
@endsection
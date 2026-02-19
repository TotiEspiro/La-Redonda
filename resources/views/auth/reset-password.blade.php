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

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Confirmar Contraseña</label>
                <input name="password_confirmation" type="password" required 
                       class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm bg-gray-50/50"placeholder="Confirmá tu nueva contraseña">
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

            <button type="submit" class="w-full py-4 bg-button text-white rounded-2xl font-black text-sm hover:bg-blue-900 transition-all">
                Actualizar Contraseña
            </button>
        </form>
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
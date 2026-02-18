@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase tracking-tighter">Recuperar</h2>
            <p class="mt-2 text-sm text-text-light font-medium">Ingresá tu email para recibir instrucciones</p>
        </div>

        <form id="forgotPasswordForm" class="mt-8 space-y-6">
            @csrf
            <div>
                <label for="email" class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Email de la cuenta</label>
                <input id="email" name="email" type="email" required 
                       class="mt-1 block w-full px-5 py-4 border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50"
                       placeholder="ejemplo@correo.com">
            </div>

            <button type="submit" 
                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-xs font-black text-white bg-button hover:bg-blue-900 transition-all active:scale-95 shadow-blue-100 uppercase tracking-widest">
                Enviar Enlace
            </button>
        </form>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="inline-flex items-center text-[10px] font-black text-button hover:text-blue-700 transition-colors uppercase tracking-widest">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al inicio
            </a>
        </div>
    </div>
</div>

{{-- MODAL DE ÉXITO --}}
<div id="successModal" class="relative z-[250] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white p-8 text-center shadow-2xl transition-all w-full max-w-sm animate-fade-in">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-3xl bg-blue-50 mb-6 shadow-inner">
                    <img src="{{ asset('img/icono_activo.png') }}" alt="Activo" class="h-12 w-12">
                </div>
                <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tighter mb-2" id="modal-title">¡Enlace enviado!</h3>
                <p class="text-xs text-gray-500 font-medium leading-relaxed mb-8">Si el correo está registrado, recibirás un enlace de recuperación en unos instantes.</p>
                <button type="button" id="closeModalBtn" class="w-full py-4 rounded-2xl bg-button text-xs font-black text-white shadow-lg hover:bg-blue-900 transition-all uppercase tracking-widest active:scale-95">
                    Entendido
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('forgotPasswordForm');
        const modal = document.getElementById('successModal');
        const closeBtn = document.getElementById('closeModalBtn');

        if(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); 
                modal.classList.remove('hidden');
            });
        }

        if(closeBtn) {
            closeBtn.addEventListener('click', function() {
                modal.classList.add('hidden');
                form.reset(); 
            });
        }
    });
</script>
@endsection
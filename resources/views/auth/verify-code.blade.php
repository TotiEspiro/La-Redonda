@extends('layouts.app')

@section('content')
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-button/10 mb-4">
                <svg class="h-6 w-6 text-button" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-2xl font-black text-text-dark uppercase tracking-tight">Validación de Cuenta</h2>
            <p class="mt-2 text-[10px] text-text-light font-bold uppercase tracking-widest">Código de 6 dígitos enviado a tu mail</p>
        </div>

        @if ($errors->any())
            <div class="p-4 mb-4 text-[10px] text-red-700 bg-red-50 rounded-2xl border border-red-100 font-bold uppercase tracking-widest text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('auth.verify.code.post') }}" method="POST" id="verifyForm" class="mt-8 space-y-6">
            @csrf
            <div>
                <input name="code" type="text" required maxlength="6" 
                       class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-center text-3xl font-black tracking-[0.4em] transition-all bg-gray-50/50" 
                       placeholder="000000">
            </div>

            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-black text-white bg-button hover:bg-blue-900 transition-all active:scale-95 shadow-blue-100 uppercase tracking-widest">
                Validar Identidad
            </button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-[9px] text-gray-400 font-bold uppercase hover:underline tracking-widest">Volver al inicio</a>
            </div>
        </form>
    </div>
</div>

<div id="loadingScreenProgress" class="fixed inset-0 bg-nav-footer flex flex-col items-center justify-center z-50" style="display: none;">
    <div class="text-center px-4">
        <img src="{{ asset('img/logo_redonda.png') }}" alt="La Redonda" class="w-24 md:w-28 mx-auto mb-6 h-auto animate-pulse">
        <div class="w-64 bg-gray-200 rounded-full h-1.5 mb-4 mx-auto">
            <div id="loadingProgress" class="bg-button h-full rounded-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <p class="text-text-dark font-black uppercase tracking-[0.2em] text-[10px]">Verificando <span id="loadingPercent">0</span>%</p>
    </div>
</div>

<script src="{{ asset('js/login.js') }}"></script>
@endsection
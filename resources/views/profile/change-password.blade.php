@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background-light py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-button px-6 py-4 text-white">
                <h1 class="text-xl font-bold">Cambiar Contraseña</h1>
            </div>

            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.change-password') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-text-dark mb-2">Contraseña Actual</label>
                            <input type="password" name="current_password" id="current_password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-button focus:border-button"
                                   required>
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password" class="block text-sm font-medium text-text-dark mb-2">Nueva Contraseña</label>
                            <input type="password" name="new_password" id="new_password" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-button focus:border-button"
                                   required>
                            @error('new_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-sm font-medium text-text-dark mb-2">Confirmar Nueva Contraseña</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-button focus:border-button"
                                   required>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <button type="submit" 
                                    class="bg-button text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-500 transition-colors">
                                Cambiar Contraseña
                            </button>
                            <a href="{{ route('profile.show') }}" 
                               class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold text-center hover:bg-gray-400 transition-colors no-underline">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
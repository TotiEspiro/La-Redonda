@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background-light py-6 md:py-12">
    <div class="max-w-xl mx-auto px-4">
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="bg-button px-6 py-4 text-white">
                <h1 class="text-xl font-bold flex items-center">
                    <span class="mr-2"></span> Editar Perfil
                </h1>
            </div>

            <div class="p-6 md:p-8">
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nombre Completo</label>
                        <input type="text" name="name" id="name" 
                               value="{{ old('name', $user->name) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-button focus:border-button outline-none transition-all text-base"
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" 
                               value="{{ old('email', $user->email) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-button focus:border-button outline-none transition-all text-base"
                               required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                        <h3 class="text-sm font-bold text-blue-800 mb-1">Rol Actual</h3>
                        <p class="text-blue-600 text-sm">
                            @if($user->isAdmin())
                                Administrador
                            @else
                                Usuario Estandard
                            @endif
                        </p>
                        <p class="text-xs text-blue-400 mt-2 border-t border-blue-200 pt-2">
                            * Los cambios de rol son gestionados por la administración.
                        </p>
                    </div>

                    <div class="flex flex-col md:flex-row gap-3 pt-4">
                        <button type="submit" 
                                class="w-full md:w-auto bg-button text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-500 transition-all shadow-sm">
                            Guardar Cambios
                        </button>
                        <a href="{{ route('profile.show') }}" 
                           class="w-full md:w-auto bg-gray-100 text-gray-700 px-8 py-3 rounded-lg font-semibold text-center hover:bg-gray-200 transition-all no-underline">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
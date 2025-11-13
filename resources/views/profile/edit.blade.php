@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background-light py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-button px-6 py-4 text-white">
                <h1 class="text-xl font-bold">Editar Perfil</h1>
            </div>

            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-text-dark mb-2">Nombre Completo</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-button focus:border-button"
                                   required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-text-dark mb-2">Email</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-button focus:border-button"
                                   required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold text-text-dark mb-2">Información del Rol</h3>
                            <p class="text-text-light">
                                @if($user->isAdmin())
                                    Tu rol actual es: <span class="font-semibold text-yellow-600">Administrador</span>
                                @else
                                    Tu rol actual es: <span class="font-semibold text-blue-600">Usuario</span>
                                @endif
                            </p>
                            <p class="text-sm text-text-light mt-2">
                                Los cambios de rol deben ser gestionados por un administrador.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <button type="submit" 
                                    class="bg-button text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-500 transition-colors">
                                Guardar Cambios
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
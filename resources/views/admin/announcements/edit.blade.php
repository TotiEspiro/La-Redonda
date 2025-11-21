@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-button px-6 py-4 text-white">
            <h1 class="text-2xl font-bold">Editar Anuncio</h1>
            <p class="text-blue-100">Modificar aviso parroquial</p>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data" id="announcementForm">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Imagen actual -->
                    @if($announcement->image_url)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Imagen Actual</label>
                        <img src="{{ $announcement->image_url }}" 
                             alt="{{ $announcement->title }}" 
                             class="h-32 w-auto object-cover rounded border">
                        <p class="mt-1 text-sm text-gray-500">Ruta: {{ $announcement->getRawImagePath() }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Título *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $announcement->title) }}" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-button focus:border-button">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="short_description" class="block text-sm font-medium text-gray-700">Descripción Corta *</label>
                        <textarea id="short_description" name="short_description" required rows="3"
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-button focus:border-button"
                                  placeholder="Descripción breve que aparece en la tarjeta">{{ old('short_description', $announcement->short_description) }}</textarea>
                        @error('short_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="full_description" class="block text-sm font-medium text-gray-700">Descripción Completa *</label>
                        <textarea id="full_description" name="full_description" required rows="6"
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-button focus:border-button"
                                  placeholder="Contenido completo que aparece en el modal">{{ old('full_description', $announcement->full_description) }}</textarea>
                        @error('full_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Subir Imagen -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">
                            {{ $announcement->image_url ? 'Cambiar Imagen' : 'Imagen' }}
                        </label>
                        <input type="file" id="image" name="image" accept="image/*" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2
                               @error('image') border-red-300 @enderror">
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @else
                            <p class="mt-1 text-sm text-gray-500">Formatos: JPEG, PNG, JPG, GIF, WEBP. Máx: 2MB</p>
                        @enderror
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="order" class="block text-sm font-medium text-gray-700">Orden</label>
                            <input type="number" id="order" name="order" value="{{ old('order', $announcement->order) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-button focus:border-button">
                            @error('order')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1" 
                                           {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-button focus:ring-button">
                                    <span class="ml-2">Activo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('admin.announcements.index') }}" 
                       class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-button text-white px-4 py-2 rounded text-sm hover:bg-blue-500 transition-colors">
                        Actualizar Anuncio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
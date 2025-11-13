@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Editar Anuncio</h2>
            <p class="text-gray-600">Modificar aviso parroquial</p>
        </div>
        <a href="{{ route('admin.announcements.index') }}" 
           class="bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
            ← Volver
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Información del Anuncio</h3>
    </div>
    
    <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data" class="p-6" id="announcementForm">
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
                       @error('image') @enderror">
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @else
                    <p class="mt-1 text-sm text-gray-500">Formatos: JPEG, PNG, JPG, GIF, WEBP. Máx: 2MB</p>
                @enderror>
                
            </div>
            
            <div class="grid grid-cols-2 gap-6">
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
               class="bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-button text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-500 transition-colors">
                Actualizar Anuncio
            </button>
        </div>
    </form>
</div>
@endsection

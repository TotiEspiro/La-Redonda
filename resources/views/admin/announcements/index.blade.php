@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-button px-6 py-4 text-white">
            <h1 class="text-2xl font-bold">Gestión de Anuncios</h1>
            <p class="text-blue-100">Administra los avisos parroquiales</p>
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

            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('admin.announcements.create') }}" 
                   class="bg-button text-white px-4 py-2 rounded text-sm hover:bg-blue-500 transition-colors">
                    + Nuevo Anuncio
                </a>
            </div>

            @if($announcements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-3 px-4 text-left">Imagen</th>
                                <th class="py-3 px-4 text-left">Título</th>
                                <th class="py-3 px-4 text-left">Descripción Corta</th>
                                <th class="py-3 px-4 text-left">Orden</th>
                                <th class="py-3 px-4 text-left">Estado</th>
                                <th class="py-3 px-4 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($announcements as $announcement)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-4">
                                        @if($announcement->image_url)
                                            <img src="{{ $announcement->image_url }}" 
                                                 alt="{{ $announcement->title }}" 
                                                 class="h-12 w-12 object-cover rounded">
                                        @else
                                            <div class="h-12 w-12 bg-gray-200 rounded flex items-center justify-center">
                                                <span class="text-gray-500 text-xs">Sin imagen</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="font-medium text-gray-900">{{ $announcement->title }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="text-gray-900 max-w-xs truncate">{{ $announcement->short_description }}</div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ $announcement->order }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 inline-flex text-xs font-semibold rounded-full 
                                            {{ $announcement->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $announcement->is_active ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.announcements.edit', $announcement) }}" 
                                               class="text-blue-600 hover:text-blue-900 text-sm mt-1">Editar</a>
                                            <form action="{{ route('admin.announcements.destroy', $announcement) }}" 
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 text-sm ml-2"
                                                        onclick="return confirm('¿Estás seguro de eliminar este anuncio?')">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-8 text-center">
                    <p class="text-gray-500 mb-4">No hay anuncios registrados.</p>
                    <a href="{{ route('admin.announcements.create') }}" 
                       class="bg-button text-white px-4 py-2 rounded text-sm hover:bg-blue-500 transition-colors">
                        Crear primer anuncio
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
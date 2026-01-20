@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 md:bg-white md:min-h-0 pb-20 md:pb-0">

    <div class="bg-button text-white shadow-md md:shadow-none md:rounded-t-xl sticky top-0 z-20 md:static">
        <div class="container mx-auto px-4 py-4 md:px-6 md:py-5 md:mt-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold">Gestión de Anuncios</h1>
                    <p class="text-blue-100 text-xs md:text-sm hidden md:block">Administra los avisos parroquiales visibles en la app</p>
                </div>
                <a href="{{ route('admin.announcements.create') }}" class="md:hidden bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg text-xs font-bold backdrop-blur-sm transition-colors border border-white/30 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nuevo
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-2 md:px-0 md:border md:border-t-0 md:border-gray-100 md:rounded-b-xl md:shadow-lg md:mb-8 bg-transparent md:bg-white">
        <div class="hidden md:block px-6 py-4">
            <a href="{{ route('admin.announcements.create') }}" 
               class="inline-flex items-center bg-button text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-blue-600 transition-colors shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Anuncio
            </a>
        </div>

        <div class="md:hidden space-y-3 pb-4 mt-2">
            @forelse($announcements as $announcement)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mx-1">
                    <div class="flex">
                        <div class="w-24 bg-gray-100 flex-shrink-0 relative border-r border-gray-100">
                            @if($announcement->image_url)
                                <img src="{{ $announcement->image_url }}" alt="" class="w-full h-full object-cover absolute inset-0">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 p-2">
                                    <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[10px]">Sin foto</span>
                                </div>
                            @endif
                            <div class="absolute top-0 left-0 bg-black/50 text-white text-[10px] px-1.5 py-0.5 rounded-br font-bold backdrop-blur-sm">
                                #{{ $announcement->order }}
                            </div>
                        </div>

                        <div class="flex-1 p-3 flex flex-col justify-between min-w-0">
                            <div>
                                <div class="flex justify-between items-start">
                                    <h3 class="text-sm font-bold text-gray-900 leading-tight mb-1 line-clamp-2">{{ $announcement->title }}</h3>
                                </div>
                                <p class="text-xs text-gray-500 line-clamp-2 mb-2 leading-relaxed">
                                    {{ $announcement->short_description ?? 'Sin descripción' }}
                                </p>
                            </div>
                            
                            <div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border
                                    {{ $announcement->is_active ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100' }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $announcement->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    {{ $announcement->is_active ? 'Publicado' : 'Borrador' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex divide-x divide-gray-100 border-t border-gray-100 bg-gray-50">
                        <a href="{{ route('admin.announcements.edit', $announcement) }}" 
                           class="flex-1 py-2.5 text-xs font-bold text-button hover:bg-blue-50 active:bg-blue-100 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Editar
                        </a>
                        
                        <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="flex-1 flex">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="flex-1 py-2.5 text-xs font-bold text-red-500 hover:bg-red-50 active:bg-red-100 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide"
                                    onclick="return confirm('¿Estás seguro de eliminar este anuncio?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Borrar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                    <div class="bg-gray-100 p-4 rounded-full mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <h3 class="text-gray-900 font-medium mb-1">No hay anuncios</h3>
                    <p class="text-gray-500 text-sm mb-4">Comienza creando el primer aviso parroquial.</p>
                    <a href="{{ route('admin.announcements.create') }}" class="bg-button text-white px-6 py-2 rounded-lg text-sm font-bold shadow-sm">
                        Crear Anuncio
                    </a>
                </div>
            @endforelse
        </div>

        <div class="hidden md:block overflow-x-auto p-6 pt-2">
            @if($announcements->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Info</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Descripción</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($announcements as $announcement)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12 bg-gray-100 rounded-lg overflow-hidden border border-gray-200">
                                            @if($announcement->image_url)
                                                <img src="{{ $announcement->image_url }}" alt="" class="h-12 w-12 object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center text-gray-400 text-xs">N/A</div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $announcement->title }}</div>
                                            <div class="text-xs text-gray-500 bg-gray-100 inline-block px-1.5 rounded mt-0.5">Orden: {{ $announcement->order }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 px-4 hidden sm:table-cell">
                                    <div class="text-sm text-gray-500 max-w-xs truncate">{{ $announcement->short_description }}</div>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $announcement->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $announcement->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.announcements.edit', $announcement) }}" 
                                           class="text-button hover:bg-blue-50 p-2 rounded-lg transition-colors" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors"
                                                    onclick="return confirm('¿Eliminar anuncio?')" title="Eliminar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-12 text-center bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    <p class="text-gray-500 mb-4">No hay anuncios registrados.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
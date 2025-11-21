@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <img src="../img/icono_biblia.png" class="w-8 h-8" alt="Diario"> 
                        <h1 class="text-2xl font-bold text-gray-900">Diario de La Redonda</h1>
                    </div>
                    <span class="text-sm text-gray-500">Tu espacio de creación</span>
                </div>
                <button id="createDocumentBtn" 
                        class="bg-button text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-500 transition-colors shadow-sm flex items-center space-x-2">
                    <span class="text-lg">+</span>
                    <span>Nuevo Documento</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Lista de Documentos -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filtros y Búsqueda -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div class="flex space-x-2 overflow-x-auto" id="filterButtons">
                <button data-filter="all" 
                        class="filter-btn px-4 py-2 rounded-lg bg-button text-white text-sm font-medium flex items-center space-x-2">
                    <span>Todos</span>
                </button>
                <button data-filter="favorite" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300 flex items-center space-x-2">
                    <img src="../img/icono_favoritos.png" class="w-4 h-4" alt="Favoritos"> 
                    <span>Favoritos</span>
                </button>
                <button data-filter="texto" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300 flex items-center space-x-2">
                        <img src="../img/icono_reflexion.png" class="w-4 h-4" alt="Reflexión">
                    <span>Reflexiones</span>
                </button>
                <button data-filter="mapa_conceptual" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300 flex items-center space-x-2">
                        <img src="../img/icono_mapa.png" class="w-4 h-4" alt="Mapas">
                    <span>Mapas</span>
                </button>
                <button data-filter="lista" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300 flex items-center space-x-2">
                      <img src="../img/icono_activo.png" class="w-4 h-4" alt="Listas"> 
                    <span>Listas</span>
                </button>
            </div>
            
            <div class="relative">
                <input type="text" id="searchDocuments" placeholder="Buscar documentos..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600 w-full sm:w-64">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                  <img src="../img/icono_buscar.png" class="w-6 h-6" alt="Buscar"> 
                </div>
            </div>
        </div>

        <!-- Grid de Documentos -->
        <div id="documentsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($entries as $entry)
                <div class="document-card bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow cursor-pointer"
                     data-type="{{ $entry->type }}"
                     data-favorite="{{ $entry->is_favorite ? 'true' : 'false' }}"
                     data-title="{{ strtolower($entry->title) }}"
                     data-id="{{ $entry->id }}">
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-bold"
                                 style="background-color: {{ $entry->color }}">
                                {{ strtoupper(substr($entry->title, 0, 1)) }}
                            </div>
                            <button class="favorite-btn text-gray-400 hover:text-yellow-500 transition-colors {{ $entry->is_favorite ? 'text-yellow-500' : '' }} flex items-center"
                                    data-id="{{ $entry->id }}">
                            <img src="../img/icono_favoritos.png" class="w-6 h-6" alt="Favorito">
                            </button>
                        </div>
                        
                        <h3 class="font-semibold text-gray-900 mb-2 truncate">{{ $entry->title }}</h3>
                        
                        <div class="text-xs text-gray-500 mb-3">
                            <span class="inline-block px-2 py-1 bg-gray-100 rounded flex items-center space-x-1">
                                <span>{{ $entry->type_display }}</span>
                            </span>
                        </div>
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>{{ $entry->created_at->format('d/m/Y') }}</span>
                            <div class="flex space-x-2">
                                <button class="edit-btn text-gray-400 hover:text-blue-600 transition-colors flex items-center space-x-1"
                                        data-id="{{ $entry->id }}">
                                   <img src="../img/icono_editar.png" class="w-4 h-4" alt="Editar"> 
                                    <span class="hidden sm:inline">Editar</span>
                                </button>
                                <button class="delete-btn text-gray-400 hover:text-red-500 transition-colors flex items-center space-x-1"
                                        data-id="{{ $entry->id }}">
                                    <img src="../img/icono_eliminar.png" class="w-4 h-4" alt="Eliminar">
                                    <span class="hidden sm:inline">Eliminar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Mensaje vacío -->
        @if(count($entries) === 0)
        <div class="text-center py-16">
            <div class="text-6xl mb-4 text-gray-300"></div><img src="../img/icono_biblia.png" class="w-24 h-24 mx-auto mb-4 opacity-50" alt="Vacío"> 
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No hay documentos aún</h3>
            <p class="text-gray-500 mb-6">Crea tu primer documento para comenzar</p>
            <button id="createFirstDocumentBtn" 
                    class="bg-button text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-500 transition-colors flex items-center space-x-2 mx-auto">
                <!-- ICONO CREAR PRIMER DOCUMENTO -->
                <span class="text-lg">＋</span> <!-- Reemplazar con: <img src="ruta/tu-icono-primero.png" class="w-5 h-5" alt="Primero"> -->
                <span>Crear Primer Documento</span>
            </button>
        </div>
        @endif

        <!-- Paginación -->
        @if($paginator->hasPages())
        <div class="mt-8">
            {{ $paginator->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Incluir el modal del editor -->
@include('diario.partials.editor-modal')

<!-- Modal de confirmación para eliminar -->
<div id="deleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <!-- ICONO MODAL ELIMINAR -->
                <span class="text-red-600 text-xl">🗑️</span> <!-- Reemplazar con: <img src="ruta/tu-icono-modal-eliminar.png" class="w-6 h-6" alt="Eliminar"> -->
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">¿Eliminar entrada?</h3>
            <p class="text-gray-500 mb-6">Esta acción no se puede deshacer. La entrada se eliminará permanentemente.</p>
            
            <div class="flex space-x-3 justify-center">
                <button id="cancelDeleteBtn" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors border border-gray-300 rounded-lg flex items-center space-x-2">
                    <!-- ICONO CANCELAR -->
                    <span>✕</span> <!-- Reemplazar con: <img src="ruta/tu-icono-cancelar.png" class="w-4 h-4" alt="Cancelar"> -->
                    <span>Cancelar</span>
                </button>
                <button id="confirmDeleteBtn" 
                        class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-500 transition-colors flex items-center space-x-2">
                    <!-- ICONO CONFIRMAR ELIMINAR -->
                    <span>✓</span> <!-- Reemplazar con: <img src="ruta/tu-icono-confirmar.png" class="w-4 h-4" alt="Confirmar"> -->
                    <span>Sí, eliminar</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notificaciones -->
<div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

<!-- Cargar los scripts JavaScript -->
<script src="{{ asset('js/diario/app.js') }}"></script>
<script src="{{ asset('js/diario/editor-modal.js') }}"></script>
@endsection
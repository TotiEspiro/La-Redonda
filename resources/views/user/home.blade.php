@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold text-gray-900">📖 Diario de La Redonda</h1>
                    <span class="text-sm text-gray-500">Tu espacio de creación</span>
                </div>
                <button onclick="createNewDocument()" 
                        class="bg-button text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-500 transition-colors shadow-sm">
                    ＋ Nuevo Documento
                </button>
            </div>
        </div>
    </div>

    <!-- Lista de Documentos -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Filtros y Búsqueda -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div class="flex space-x-2 overflow-x-auto">
                <button onclick="filterDocuments('all')" 
                        class="filter-btn px-4 py-2 rounded-lg bg-button text-white text-sm font-medium">
                    Todos
                </button>
                <button onclick="filterDocuments('favorite')" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300">
                    ⭐ Favoritos
                </button>
                <button onclick="filterDocuments('texto')" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300">
                    📝 Texto
                </button>
                <button onclick="filterDocuments('mapa_conceptual')" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300">
                    🗺️ Mapas
                </button>
            </div>
            
            <div class="relative">
                <input type="text" id="searchDocuments" placeholder="Buscar documentos..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-button focus:border-button w-full sm:w-64"
                       onkeyup="searchDocuments()">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                    🔍
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
                     onclick="openDocument({{ $entry->id }})">
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm font-bold"
                                 style="background-color: {{ $entry->color }}">
                                {{ substr($entry->title, 0, 1) }}
                            </div>
                            <button onclick="event.stopPropagation(); toggleFavorite({{ $entry->id }}, this)" 
                                    class="text-gray-400 hover:text-yellow-500 transition-colors {{ $entry->is_favorite ? 'text-yellow-500' : '' }}">
                                ⭐
                            </button>
                        </div>
                        
                        <h3 class="font-semibold text-gray-900 mb-2 truncate">{{ $entry->title }}</h3>
                        
                        <div class="text-xs text-gray-500 mb-3">
                            <span class="inline-block px-2 py-1 bg-gray-100 rounded">
                                {{ $entry->type_display }}
                            </span>
                        </div>
                        
                        <div class="text-sm text-gray-600 line-clamp-2 mb-4">
                            {!! Str::limit(strip_tags($entry->content), 100) !!}
                        </div>
                        
                        <div class="flex justify-between items-center text-xs text-gray-500">
                            <span>{{ $entry->created_at->format('d/m/Y') }}</span>
                            <div class="flex space-x-1">
                                <button onclick="event.stopPropagation(); editDocument({{ $entry->id }})" 
                                        class="text-gray-400 hover:text-button transition-colors">
                                    ✏️
                                </button>
                                <button onclick="event.stopPropagation(); deleteDocument({{ $entry->id }})" 
                                        class="text-gray-400 hover:text-red-500 transition-colors">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Mensaje vacío -->
        @if($entries->isEmpty())
        <div class="text-center py-16">
            <div class="text-6xl mb-4 text-gray-300">📖</div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No hay documentos aún</h3>
            <p class="text-gray-500 mb-6">Crea tu primer documento para comenzar</p>
            <button onclick="createNewDocument()" 
                    class="bg-button text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-500 transition-colors">
                Crear Primer Documento
            </button>
        </div>
        @endif

        <!-- Paginación -->
        @if($entries->hasPages())
        <div class="mt-8">
            {{ $entries->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Editor de Documentos (Tipo Google Docs) -->
<div id="documentEditor" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl mx-4 max-h-[95vh] overflow-hidden flex flex-col">
        <!-- Toolbar -->
        <div class="bg-gray-50 border-b border-gray-200 px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Tipo de Documento -->
                <select id="docType" class="border border-gray-300 rounded px-3 py-1 text-sm focus:ring-button focus:border-button">
                    <option value="texto">📝 Documento de Texto</option>
                    <option value="mapa_conceptual">🗺️ Mapa Conceptual</option>
                    <option value="lista">📋 Lista</option>
                    <option value="reflexion">💭 Reflexión</option>
                </select>
                
                <!-- Color -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-600">Color:</label>
                    <input type="color" id="docColor" value="#3b82f6" class="w-8 h-8 border border-gray-300 rounded cursor-pointer">
                </div>
            </div>
            
            <div class="flex items-center space-x-2">
                <button onclick="closeDocumentEditor()" 
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                    Cancelar
                </button>
                <button onclick="saveDocument()" 
                        class="bg-button text-white px-6 py-2 rounded font-semibold hover:bg-blue-500 transition-colors">
                    Guardar
                </button>
            </div>
        </div>

        <!-- Editor Principal -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Título del Documento -->
            <div class="border-b border-gray-200 px-6 py-4">
                <input type="text" id="docTitle" placeholder="Título del documento..." 
                       class="w-full text-2xl font-bold border-none outline-none placeholder-gray-400">
            </div>

            <!-- Toolbar de Formato -->
            <div class="bg-white border-b border-gray-200 px-6 py-3 flex items-center space-x-4 flex-wrap">
                <!-- Fuente -->
                <select id="fontFamily" class="border border-gray-300 rounded px-2 py-1 text-sm">
                    <option value="Arial">Arial</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Verdana">Verdana</option>
                </select>
                
                <!-- Tamaño -->
                <select id="fontSize" class="border border-gray-300 rounded px-2 py-1 text-sm">
                    <option value="1">8pt</option>
                    <option value="2">10pt</option>
                    <option value="3">12pt</option>
                    <option value="4">14pt</option>
                    <option value="5">18pt</option>
                    <option value="6">24pt</option>
                    <option value="7">36pt</option>
                </select>

                <!-- Botones de Formato -->
                <div class="flex items-center space-x-1 border-l border-gray-300 pl-4">
                    <button onclick="formatText('bold')" class="format-btn p-2 rounded hover:bg-gray-100" title="Negrita">𝐁</button>
                    <button onclick="formatText('italic')" class="format-btn p-2 rounded hover:bg-gray-100" title="Itálica">𝐼</button>
                    <button onclick="formatText('underline')" class="format-btn p-2 rounded hover:bg-gray-100" title="Subrayado">𝐔</button>
                    <button onclick="formatText('strikethrough')" class="format-btn p-2 rounded hover:bg-gray-100" title="Tachado">𝐒</button>
                </div>

                <!-- Alineación -->
                <div class="flex items-center space-x-1 border-l border-gray-300 pl-4">
                    <button onclick="formatText('justifyLeft')" class="format-btn p-2 rounded hover:bg-gray-100" title="Alinear izquierda">⫷</button>
                    <button onclick="formatText('justifyCenter')" class="format-btn p-2 rounded hover:bg-gray-100" title="Centrar">⫸</button>
                    <button onclick="formatText('justifyRight')" class="format-btn p-2 rounded hover:bg-gray-100" title="Alinear derecha">⫹</button>
                    <button onclick="formatText('justifyFull')" class="format-btn p-2 rounded hover:bg-gray-100" title="Justificar">⫺</button>
                </div>

                <!-- Listas -->
                <div class="flex items-center space-x-1 border-l border-gray-300 pl-4">
                    <button onclick="formatText('insertUnorderedList')" class="format-btn p-2 rounded hover:bg-gray-100" title="Lista con viñetas">•</button>
                    <button onclick="formatText('insertOrderedList')" class="format-btn p-2 rounded hover:bg-gray-100" title="Lista numerada">1.</button>
                </div>

                <!-- Sangría -->
                <div class="flex items-center space-x-1 border-l border-gray-300 pl-4">
                    <button onclick="formatText('outdent')" class="format-btn p-2 rounded hover:bg-gray-100" title="Disminuir sangría">←</button>
                    <button onclick="formatText('indent')" class="format-btn p-2 rounded hover:bg-gray-100" title="Aumentar sangría">→</button>
                </div>
            </div>

            <!-- Área de Edición -->
            <div class="flex-1 overflow-auto bg-gray-50">
                <div id="editorContent" class="bg-white min-h-full p-8 max-w-4xl mx-auto shadow-inner" 
                     style="min-height: 500px;" contenteditable="true">
                    <p>Comienza a escribir aquí...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
let currentDocumentId = null;
let isEditorDirty = false;

// Funciones de Navegación
function createNewDocument() {
    currentDocumentId = null;
    resetEditor();
    openDocumentEditor();
}

function openDocument(documentId) {
    currentDocumentId = documentId;
    loadDocument(documentId);
    openDocumentEditor();
}

function openDocumentEditor() {
    const editor = document.getElementById('documentEditor');
    editor.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Enfocar el título
    setTimeout(() => {
        document.getElementById('docTitle').focus();
    }, 100);
}

function closeDocumentEditor() {
    if (isEditorDirty) {
        if (!confirm('Tienes cambios sin guardar. ¿Estás seguro de que quieres salir?')) {
            return;
        }
    }
    
    const editor = document.getElementById('documentEditor');
    editor.classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentDocumentId = null;
    isEditorDirty = false;
}

// Funciones del Editor
function resetEditor() {
    document.getElementById('docTitle').value = '';
    document.getElementById('docType').value = 'texto';
    document.getElementById('docColor').value = '#3b82f6';
    document.getElementById('editorContent').innerHTML = '<p>Comienza a escribir aquí...</p>';
    isEditorDirty = false;
}

async function loadDocument(documentId) {
    try {
        const response = await fetch(`/diario/entrada/${documentId}`);
        const document = await response.json();
        
        document.getElementById('docTitle').value = document.title;
        document.getElementById('docType').value = document.type;
        document.getElementById('docColor').value = document.color;
        document.getElementById('editorContent').innerHTML = document.content;
        
        isEditorDirty = false;
    } catch (error) {
        console.error('Error cargando documento:', error);
        alert('Error al cargar el documento');
    }
}

async function saveDocument() {
    const title = document.getElementById('docTitle').value.trim();
    const type = document.getElementById('docType').value;
    const color = document.getElementById('docColor').value;
    const content = document.getElementById('editorContent').innerHTML;

    if (!title) {
        alert('Por favor, ingresa un título para el documento');
        return;
    }

    const formData = new FormData();
    formData.append('title', title);
    formData.append('type', type);
    formData.append('color', color);
    formData.append('content', content);

    const url = currentDocumentId ? `/diario/entrada/${currentDocumentId}/actualizar` : '/diario/entrada/crear';
    const method = currentDocumentId ? 'PUT' : 'POST';

    try {
        const response = await fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        });

        const data = await response.json();
        
        if (data.success) {
            closeDocumentEditor();
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Error al guardar'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al guardar el documento');
    }
}

// Funciones de Formato de Texto
function formatText(command, value = null) {
    document.execCommand(command, false, value);
    document.getElementById('editorContent').focus();
    isEditorDirty = true;
}

// Detectar cambios en el editor
document.getElementById('editorContent').addEventListener('input', function() {
    isEditorDirty = true;
});

document.getElementById('docTitle').addEventListener('input', function() {
    isEditorDirty = true;
});

// Funciones de Gestión de Documentos
async function toggleFavorite(documentId, button) {
    try {
        const response = await fetch(`/diario/entrada/${documentId}/favorito`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            button.classList.toggle('text-yellow-500');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function editDocument(documentId) {
    openDocument(documentId);
}

async function deleteDocument(documentId) {
    if (!confirm('¿Estás seguro de que quieres eliminar este documento?')) return;
    
    try {
        const response = await fetch(`/diario/entrada/${documentId}/eliminar`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            alert('Error al eliminar el documento');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al eliminar el documento');
    }
}

// Filtros y Búsqueda
function filterDocuments(filter) {
    const documents = document.querySelectorAll('.document-card');
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(btn => {
        btn.classList.remove('bg-button', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
    });
    
    event.target.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
    event.target.classList.add('bg-button', 'text-white');
    
    documents.forEach(doc => {
        if (filter === 'all') {
            doc.style.display = 'block';
        } else if (filter === 'favorite') {
            doc.style.display = doc.getAttribute('data-favorite') === 'true' ? 'block' : 'none';
        } else {
            doc.style.display = doc.getAttribute('data-type') === filter ? 'block' : 'none';
        }
    });
}

function searchDocuments() {
    const searchTerm = document.getElementById('searchDocuments').value.toLowerCase();
    const documents = document.querySelectorAll('.document-card');
    
    documents.forEach(doc => {
        const title = doc.getAttribute('data-title');
        if (title.includes(searchTerm)) {
            doc.style.display = 'block';
        } else {
            doc.style.display = 'none';
        }
    });
}

// Event Listeners
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDocumentEditor();
    }
    
    // Ctrl+S para guardar
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        saveDocument();
    }
});

document.getElementById('documentEditor').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDocumentEditor();
    }
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.format-btn.active {
    background-color: #e5e7eb;
}

#editorContent:focus {
    outline: none;
}

#editorContent {
    line-height: 1.6;
    font-family: 'Times New Roman', serif;
    font-size: 12pt;
}

#editorContent h1, #editorContent h2, #editorContent h3 {
    margin: 1em 0 0.5em 0;
}

#editorContent p {
    margin: 0.5em 0;
}

#editorContent ul, #editorContent ol {
    margin: 0.5em 0;
    padding-left: 2em;
}
</style>
@endsection
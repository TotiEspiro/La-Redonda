@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900">Materiales - {{ $groupName }}</h1>
                <p class="text-gray-600 mt-2">Recursos y documentos del grupo</p>
                
                <!-- Información del grupo -->
                <div class="mt-4 inline-flex items-center px-4 py-2 bg-yellow-500 text-white rounded-full text-sm font-medium">
                    Grupo {{ $groupName }}
                </div>
            </div>
        </div>

        <!-- Filtros y Búsqueda -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
            <div class="flex space-x-2 overflow-x-auto">
                <button onclick="filterMaterials('all')" 
                        class="filter-btn px-4 py-2 rounded-lg bg-button text-white text-sm font-medium">
                    Todos
                </button>
                <button onclick="filterMaterials('pdf')" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300">
                    📄 PDF
                </button>
                <button onclick="filterMaterials('doc')" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300">
                    📝 Documentos
                </button>
                <button onclick="filterMaterials('image')" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300">
                    🖼️ Imágenes
                </button>
                <button onclick="filterMaterials('video')" 
                        class="filter-btn px-4 py-2 rounded-lg bg-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-300">
                    🎬 Videos
                </button>
            </div>
            
            <div class="relative">
                <input type="text" id="searchMaterials" placeholder="Buscar materiales..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-button focus:border-button w-full sm:w-64"
                       onkeyup="searchMaterials()">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                    🔍
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 bg-nav-footer rounded-lg">
                        <img src="{{ asset('img/icono_materiales.png') }}" alt="Materiales">                      
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Total Materiales</p>
                        <p class="text-lg font-bold text-gray-900">{{ $materials->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 bg-nav-footer rounded-lg">
                        <img src="{{ asset('img/icono_documentos.png') }}" alt="Documentos">                      
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Documentos</p>
                        <p class="text-lg font-bold text-gray-900">{{ $materials->whereIn('file_type', ['pdf', 'doc', 'docx'])->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 bg-nav-footer rounded-lg">
                        <img src="{{ asset('img/icono_multimedia.png') }}" alt="Multimedia">                      
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-600">Multimedia</p>
                        <p class="text-lg font-bold text-gray-900">{{ $materials->whereIn('file_type', ['image', 'video', 'audio'])->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid de Materiales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($materials as $material)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow material-card"
                     data-type="{{ $material->file_type }}"
                     data-title="{{ strtolower($material->title) }}">
                    <div class="p-5">
                        <!-- Icono y tipo -->
                        <div class="flex justify-between items-start mb-3">
                            <div class="text-3xl">
                                {{ $material->file_icon }}
                            </div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $material->file_type === 'pdf' ? 'bg-red-100 text-red-800' : 
                                   ($material->file_type === 'doc' ? 'bg-blue-100 text-blue-800' : 
                                   ($material->file_type === 'image' ? 'bg-green-100 text-green-800' : 
                                   ($material->file_type === 'video' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'))) }}">
                                {{ strtoupper($material->file_type) }}
                            </span>
                        </div>

                        <!-- Título y descripción -->
                        <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">{{ $material->title }}</h3>
                        
                        @if($material->description)
                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $material->description }}</p>
                        @endif

                        <!-- Información del archivo -->
                        <div class="space-y-2 text-xs text-gray-500 mb-4">
                            <div class="flex justify-between">
                                <span>Tamaño:</span>
                                <span class="font-medium">{{ $material->file_size_formatted }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Subido:</span>
                                <span class="font-medium">{{ $material->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Por:</span>
                                <span class="font-medium">{{ $material->user->name }}</span>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="flex space-x-2">
                            <a href="{{ route('groups.download', $material->id) }}" 
                               class="flex-1 bg-button text-white text-center py-2 px-3 rounded-lg text-sm font-medium hover:bg-blue-500 transition-colors no-underline">
                                ↓ Descargar
                            </a>
                            <button onclick="showMaterialInfo({{ $material->id }})" 
                                    class="bg-nav-footer text-black py-2 px-3 rounded-lg text-sm font-medium hover:bg-blue-300 hover:text-white transition-colors">
                               Abrir
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Mensaje vacío -->
        @if($materials->isEmpty())
        <div class="text-center py-16 bg-white rounded-lg border border-gray-200">
            <div class="text-6xl text-gray-300 mb-4">📚</div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No hay materiales disponibles</h3>
            <p class="text-gray-500 mb-4">Aún no se han subido materiales para este grupo.</p>
            <p class="text-sm text-gray-400">Los administradores del grupo podrán subir material pronto.</p>
        </div>
        @endif

        <!-- Paginación -->
        @if($materials->hasPages())
        <div class="mt-8">
            {{ $materials->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal de Información del Material -->
<div id="materialInfoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Información del Material</h2>
        </div>
        
        <div class="p-6">
            <div id="materialInfoContent">
                <!-- La información se cargará dinámicamente aquí -->
            </div>
            
            <div class="flex justify-end mt-6 pt-4 border-t border-gray-200">
                <button onclick="closeMaterialInfoModal()" 
                        class="bg-button text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-500 transition-colors">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Filtros de materiales
function filterMaterials(type) {
    const materials = document.querySelectorAll('.material-card');
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    // Actualizar botones activos
    filterButtons.forEach(btn => {
        btn.classList.remove('bg-button', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
    });
    
    event.target.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
    event.target.classList.add('bg-button', 'text-white');
    
    // Filtrar materiales
    materials.forEach(material => {
        if (type === 'all') {
            material.style.display = 'block';
        } else {
            material.style.display = material.getAttribute('data-type') === type ? 'block' : 'none';
        }
    });
}

// Búsqueda de materiales
function searchMaterials() {
    const searchTerm = document.getElementById('searchMaterials').value.toLowerCase();
    const materials = document.querySelectorAll('.material-card');
    
    materials.forEach(material => {
        const title = material.getAttribute('data-title');
        if (title.includes(searchTerm)) {
            material.style.display = 'block';
        } else {
            material.style.display = 'none';
        }
    });
}

// Mostrar información del material
async function showMaterialInfo(materialId) {
    try {
        // En una implementación real, aquí harías una petición AJAX para obtener la información completa
        // Por ahora, mostramos información básica de los materiales existentes
        
        const materialCard = document.querySelector(`.material-card a[href*="${materialId}"]`)?.closest('.material-card');
        if (materialCard) {
            const title = materialCard.querySelector('h3').textContent;
            const description = materialCard.querySelector('p')?.textContent || 'Sin descripción';
            const fileType = materialCard.querySelector('span').textContent;
            const fileSize = materialCard.querySelector('.text-xs .font-medium:first-child').textContent;
            const uploadDate = materialCard.querySelector('.text-xs .font-medium:nth-child(2)').textContent;
            const uploadedBy = materialCard.querySelector('.text-xs .font-medium:last-child').textContent;
            
            const content = `
                <div class="space-y-4">
                    <div class="text-center">
                        <div class="text-4xl mb-2">${materialCard.querySelector('.text-3xl').innerHTML}</div>
                        <h3 class="text-lg font-semibold text-gray-900">${title}</h3>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tipo:</span>
                            <span class="font-medium">${fileType}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tamaño:</span>
                            <span class="font-medium">${fileSize}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Fecha de subida:</span>
                            <span class="font-medium">${uploadDate}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subido por:</span>
                            <span class="font-medium">${uploadedBy}</span>
                        </div>
                    </div>
                    
                    ${description !== 'Sin descripción' ? `
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Descripción:</h4>
                        <p class="text-gray-600 text-sm">${description}</p>
                    </div>
                    ` : ''}
                    
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <p class="text-sm text-blue-700">
                            💡 <strong>Consejo:</strong> Puedes descargar este material para consultarlo sin conexión a internet.
                        </p>
                    </div>
                </div>
            `;
            
            document.getElementById('materialInfoContent').innerHTML = content;
            openMaterialInfoModal();
        }
    } catch (error) {
        console.error('Error al cargar información del material:', error);
    }
}

function openMaterialInfoModal() {
    document.getElementById('materialInfoModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeMaterialInfoModal() {
    document.getElementById('materialInfoModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Cerrar modal al hacer clic fuera
document.getElementById('materialInfoModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMaterialInfoModal();
    }
});

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMaterialInfoModal();
    }
});

// Inicializar tooltips para botones de información
document.addEventListener('DOMContentLoaded', function() {
    const infoButtons = document.querySelectorAll('button[onclick*="showMaterialInfo"]');
    infoButtons.forEach(button => {
        button.setAttribute('title', 'Ver información del material');
    });
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.material-card {
    transition: all 0.3s ease;
}

.material-card:hover {
    transform: translateY(-2px);
}

.filter-btn.active {
    background-color: #3b82f6;
    color: white;
}
</style>
@endsection
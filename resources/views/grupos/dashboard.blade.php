@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard - {{ $groupName }}</h1>
                    <p class="text-gray-600 mt-2">Gestión de material para el grupo</p>
                </div>
                <button onclick="openUploadModal()" 
                        class="bg-button text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-500 transition-colors">
                    + Subir Material
                </button>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                   <div class="p-3 bg-nav-footer rounded-lg">
                        <img src="{{ asset('img/icono_materiales.png') }}" alt="Materiales">                      
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Materiales</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $materials->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center">
                    <div class="p-3 bg-nav-footer rounded-lg">
                        <img src="{{ asset('img/icono_activo.png') }}" alt="Activo">                      
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Activos</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $materials->where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Materiales -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Materiales del Grupo</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tamaño</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($materials as $material)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="text-2xl mr-3">{{ $material->file_icon }}</div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $material->title }}</div>
                                        @if($material->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($material->description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ strtoupper($material->file_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $material->file_size_formatted }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $material->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('groups.download', $material->id) }}" 
                                       class="text-button hover:text-blue-500 transition-colors" title="Descargar">
                                       Descargar
                                    </a>
                                    <button onclick="deleteMaterial({{ $material->id }})" 
                                            class="text-red-500 hover:text-red-700 transition-colors" title="Eliminar">
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($materials->isEmpty())
            <div class="text-center py-12">
                <h3 class="text-lg font-semibold text-gray-600 mb-2">No hay materiales aún</h3>
                <p class="text-gray-500">Comienza subiendo el primer material para el grupo.</p>
            </div>
            @endif

            <!-- Paginación -->
            @if($materials->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $materials->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para subir material -->
<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Subir Material</h2>
        </div>
        
        <form id="uploadForm" class="p-6" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Título del material</label>
                    <input type="text" name="title" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-button focus:border-button">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción (opcional)</label>
                    <textarea name="description" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-button focus:border-button"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Archivo</label>
                    <input type="file" name="file" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-button focus:border-button"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.mp4,.mp3,.zip">
                    <p class="text-xs text-gray-500 mt-1">Formatos permitidos: PDF, Word, Excel, PowerPoint, imágenes, video, audio, ZIP (Máx. 10MB)</p>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeUploadModal()" 
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                        class="bg-button text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-500 transition-colors">
                    Subir
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openUploadModal() {
    document.getElementById('uploadModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Subir material
document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    submitBtn.textContent = 'Subiendo...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch(`/grupos/{{ $groupRole }}/upload`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            closeUploadModal();
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Error al subir el archivo'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al subir el archivo');
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

// Eliminar material
async function deleteMaterial(materialId) {
    if (!confirm('¿Estás seguro de que quieres eliminar este material?')) return;
    
    try {
        const response = await fetch(`/grupos/material/${materialId}/delete`, {
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
            alert('Error al eliminar el material');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al eliminar el material');
    }
}

// Cerrar modal al hacer clic fuera
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUploadModal();
    }
});

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeUploadModal();
    }
});
</script>
@endsection
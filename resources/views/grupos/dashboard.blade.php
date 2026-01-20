@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6 md:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard - {{ $groupName }}</h1>
                <p class="text-gray-600 mt-1 text-sm md:text-base">Gestión de material para el grupo</p>
            </div>
            <button onclick="openUploadModal()" class="w-full md:w-auto bg-button text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-500 transition-colors flex items-center justify-center shadow-sm">
                <span class="text-xl mr-2 leading-none">+</span> Subir Material
            </button>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6 mb-6 md:mb-8">
            <div class="bg-white p-4 md:p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col md:flex-row items-center md:items-start text-center md:text-left">
                <div class="p-2 md:p-3 bg-nav-footer rounded-lg flex-shrink-0 mb-2 md:mb-0">
                    <img src="{{ asset('img/icono_archivo.png') }}" alt="Materiales" class="w-6 h-6 md:w-8 md:h-8">                      
                </div>
                <div class="md:ml-4">
                    <p class="text-xs md:text-sm font-medium text-gray-500 uppercase">Total</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-900">{{ $materials->total() }}</p>
                </div>
            </div>
            
            <div class="bg-white p-4 md:p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col md:flex-row items-center md:items-start text-center md:text-left">
                <div class="p-2 md:p-3 bg-nav-footer rounded-lg flex-shrink-0 mb-2 md:mb-0">
                    <img src="{{ asset('img/icono_activo.png') }}" alt="Activo" class="w-6 h-6 md:w-8 md:h-8">                      
                </div>
                <div class="md:ml-4">
                    <p class="text-xs md:text-sm font-medium text-gray-500 uppercase">Activos</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-900">{{ $materials->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-transparent md:bg-white md:rounded-xl md:shadow-sm md:border md:border-gray-200 overflow-hidden">
            <div class="hidden md:block px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">Materiales del Grupo</h2>
            </div>
            
            <div class="md:hidden space-y-3">
                @foreach($materials as $material)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <img src="{{ asset($material->file_icon) }}" 
                                     class="w-10 h-10 object-contain" 
                                     alt="Icono"
                                     onerror="this.src='{{ asset('img/icono_docs.png') }}'">
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start">
                                    <h3 class="text-sm font-bold text-gray-900 leading-tight mb-1 truncate pr-2">{{ $material->title }}</h3>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide flex-shrink-0 {{ $material->is_active ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-red-50 text-red-700 border border-red-100' }}">
                                        {{ $material->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mb-2 line-clamp-1">{{ $material->description ?? 'Sin descripción' }}</p>
                                
                                <div class="flex items-center text-[10px] text-gray-400 font-medium space-x-3">
                                    <span class="uppercase bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded border border-blue-100">{{ $material->file_type }}</span>
                                    <span>{{ $material->file_size_formatted }}</span>
                                    <span>{{ $material->created_at->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex divide-x divide-gray-100 border-t border-gray-100 bg-gray-50">
                        <a href="{{ route('groups.download', $material->id) }}" class="flex-1 py-3 text-center text-xs font-bold text-gray-600 hover:bg-gray-100 transition-colors flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Descargar
                        </a>
                        <button onclick='openEditModal(@json($material))' class="flex-1 py-3 text-center text-xs font-bold text-blue-600 hover:bg-blue-50 transition-colors flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Editar
                        </button>
                        <button onclick="deleteMaterial({{ $material->id }})" class="flex-1 py-3 text-center text-xs font-bold text-red-600 hover:bg-red-50 transition-colors flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Borrar
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider min-w-[200px]">Material</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">Tamaño</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Fecha</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($materials as $material)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <img src="{{ asset($material->file_icon) }}" 
                                         class="w-8 h-8 mr-3 flex-shrink-0 object-contain" 
                                         alt="Icono"
                                         onerror="this.src='{{ asset('img/icono_docs.png') }}'">
                                    
                                    <div class="min-w-0">
                                        <div class="text-sm font-medium text-gray-900 truncate max-w-[150px] md:max-w-xs">{{ $material->title }}</div>
                                        @if($material->description)
                                        <div class="text-xs text-gray-500 truncate max-w-[150px] md:max-w-xs">{{ Str::limit($material->description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ strtoupper($material->file_type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $material->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $material->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden sm:table-cell">{{ $material->file_size_formatted }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden md:table-cell">{{ $material->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('groups.download', $material->id) }}" class="text-gray-500 hover:text-blue-600 transition-colors p-1" title="Descargar">
                                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>

                                    <button onclick='openEditModal(@json($material))' 
                                            class="text-blue-500 hover:text-blue-700 transition-colors p-1" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>

                                    <button onclick="deleteMaterial({{ $material->id }})" class="text-red-500 hover:text-red-700 transition-colors p-1" title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($materials->isEmpty())
            <div class="text-center py-16">
                <div class="flex justify-center items-center mb-4 opacity-60 text-6xl">
                <img src="{{ asset('img/icono_archivo.png') }}" alt="Materiales" class="w-20 h-20"></div>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">No hay materiales aún</h3>
                <p class="text-gray-500 text-sm">Comienza subiendo el primer material para el grupo.</p>
            </div>
            @endif

            @if($materials->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $materials->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-end md:items-center justify-center z-50 hidden p-0 md:p-4 backdrop-blur-sm">
    <div class="bg-white w-full md:w-full md:max-w-md rounded-t-xl md:rounded-xl shadow-2xl">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-900">Subir Material</h2>
            <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        
        <form id="uploadForm" class="p-6" enctype="multipart/form-data">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Título del material</label>
                    <input type="text" name="title" required class="w-full px-4 py-3 md:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-button focus:border-button text-base">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción (opcional)</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 md:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-button focus:border-button text-base"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Archivo</label>
                    <input type="file" name="file" required class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-button focus:border-button file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-button hover:file:bg-blue-100" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.mp4,.mp3,.zip">
                    <p class="text-xs text-gray-500 mt-2">Formatos: PDF, Office, Imágenes, Audio/Video (Máx. 150MB)</p>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-end gap-3 mt-8 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeUploadModal()" class="w-full md:w-auto bg-white text-gray-700 border border-gray-300 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors">Cancelar</button>
                <button type="submit" class="w-full md:w-auto bg-button text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-500 transition-colors shadow-sm">Subir Archivo</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-end md:items-center justify-center z-50 hidden p-0 md:p-4 backdrop-blur-sm">
    <div class="bg-white w-full md:w-full md:max-w-md rounded-t-xl md:rounded-xl shadow-2xl">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-900">Editar Material</h2>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        
        <form id="editForm" class="p-6" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="editMaterialId" name="id">
            
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Título del material</label>
                    <input type="text" id="editTitle" name="title" required 
                           class="w-full px-4 py-3 md:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-button focus:border-button text-base">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                    <textarea id="editDescription" name="description" rows="3" 
                              class="w-full px-4 py-3 md:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-button focus:border-button text-base"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select id="editIsActive" name="is_active" class="w-full px-4 py-3 md:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-button focus:border-button text-base bg-white">
                        <option value="1">Activo </option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reemplazar Archivo (Opcional)</label>
                    <input type="file" name="file" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-button focus:border-button file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-button hover:file:bg-blue-100"
                           accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.gif,.mp4,.mp3,.zip">
                    <p class="text-xs text-gray-500 mt-2">Deja vacío para mantener el archivo actual.</p>
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row justify-end gap-3 mt-8 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeEditModal()" 
                        class="w-full md:w-auto bg-white text-gray-700 border border-gray-300 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                        class="w-full md:w-auto bg-button text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-600 transition-colors shadow-sm">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openUploadModal() { document.getElementById('uploadModal').classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
function closeUploadModal() { document.getElementById('uploadModal').classList.add('hidden'); document.body.style.overflow = 'auto'; }

document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Subiendo...';
    submitBtn.disabled = true;
    try {
        const response = await fetch(`/grupos/{{ $groupRole }}/upload`, {
            method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value }
        });
        const data = await response.json();
        if (data.success) { closeUploadModal(); location.reload(); } else { alert('Error: ' + (data.error || 'Error al subir el archivo')); }
    } catch (error) { console.error('Error:', error); alert('Error al subir el archivo'); } finally { submitBtn.textContent = originalText; submitBtn.disabled = false; }
});

// --- EDICIÓN ---
function openEditModal(material) {
    document.getElementById('editMaterialId').value = material.id;
    document.getElementById('editTitle').value = material.title;
    document.getElementById('editDescription').value = material.description || '';
    const isActive = material.is_active === true || material.is_active == 1 ? '1' : '0';
    document.getElementById('editIsActive').value = isActive;
    document.getElementById('editModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.querySelector('#editForm input[type="file"]').value = '';
}

document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const materialId = document.getElementById('editMaterialId').value;
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Guardando...';
    submitBtn.disabled = true;
    try {
        const response = await fetch(`/grupos/material/${materialId}/update`, {
            method: 'POST', body: formData, headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value }
        });
        const data = await response.json();
        if (data.success) { closeEditModal(); location.reload(); } else { alert('Error: ' + (data.error || 'Error al actualizar el material')); }
    } catch (error) { console.error('Error:', error); alert('Error al actualizar el material'); } finally { submitBtn.textContent = originalText; submitBtn.disabled = false; }
});

// --- ELIMINAR ---
async function deleteMaterial(materialId) {
    if (!confirm('¿Estás seguro de que quieres eliminar este material?')) return;
    try {
        const response = await fetch(`/grupos/material/${materialId}/delete`, {
            method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        if (data.success) { location.reload(); } else { alert('Error al eliminar el material'); }
    } catch (error) { console.error('Error:', error); alert('Error al eliminar el material'); }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { closeUploadModal(); closeEditModal(); }
});
</script>
@endsection
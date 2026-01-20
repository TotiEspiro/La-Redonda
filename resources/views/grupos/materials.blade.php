@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-6 md:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6 text-center md:text-left">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Materiales - {{ $groupName }}</h1>
                    <p class="text-gray-600 mt-1">Recursos y documentos del grupo</p>
                </div>
                <span class="px-4 py-1.5 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold border border-yellow-200">
                    Grupo {{ $groupName }}
                </span>
            </div>
        </div>

        <div class="mb-8 flex flex-col lg:flex-row gap-4">
            
            <div class="w-full lg:w-auto">
                <div class="grid grid-cols-3 gap-2">
                    <button onclick="filterMaterials('all')" 
                            class="filter-btn active justify-center px-2 py-2.5 rounded-lg bg-button text-white text-xs sm:text-sm font-medium transition-all shadow-sm flex items-center border border-transparent">
                        Todos
                    </button>
                    <button onclick="filterMaterials('doc')" 
                            class="filter-btn justify-center px-2 py-2.5 rounded-lg border border-gray-200 text-gray-700 text-xs sm:text-sm font-medium transition-all flex items-center hover:bg-button hover:text-white bg-white shadow-sm">
                            <span class="mr-1.5"><img src="{{ asset('img/icono_docs.png') }}" class="w-4 h-4" onerror="this.style.display='none'"></span> 
                            <span>Documentos</span>
                    </button>
                    <button onclick="filterMaterials('video')" 
                            class="filter-btn justify-center px-2 py-2.5 rounded-lg border border-gray-200 text-gray-700 text-xs sm:text-sm font-medium transition-all flex items-center hover:bg-button hover:text-white bg-white shadow-sm">
                            <span class="mr-1.5"><img src="{{ asset('img/icono_multimedia.png') }}" class="w-4 h-4" onerror="this.style.display='none'"></span> 
                            <span>Multimedia</span>
                    </button>
                </div>
            </div>
            
            <div class="relative w-full lg:w-72 lg:ml-auto">
                <input type="text" id="searchMaterials" placeholder="Buscar materiales..." 
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-button focus:border-button text-sm shadow-sm transition-all"
                       onkeyup="searchMaterials()">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 opacity-50">
                    <img src="{{ asset('img/icono_buscar.png') }}" class="w-4 h-4" alt="Buscar">
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2 md:gap-4 mb-6 md:mb-8">
            <div class="bg-white p-3 md:p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col md:flex-row items-center justify-center md:justify-start text-center md:text-left">
                <div class="p-2 bg-nav-footer rounded-lg md:mr-4 mb-2 md:mb-0">
                    <img src="{{ asset('img/icono_archivo.png') }}" alt="Total" class="w-5 h-5 md:w-6 md:h-6">
                </div>
                <div>
                    <p class="text-[10px] md:text-xs font-bold text-gray-500 uppercase">Total</p>
                    <p class="text-lg md:text-xl font-bold text-gray-900">{{ $materials->total() }}</p>
                </div>
            </div>
            
            <div class="bg-white p-3 md:p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col md:flex-row items-center justify-center md:justify-start text-center md:text-left">
                <div class="p-2 bg-nav-footer rounded-lg md:mr-4 mb-2 md:mb-0">
                    <img src="{{ asset('img/icono_docs.png') }}" alt="Docs" class="w-5 h-5 md:w-6 md:h-6">
                </div>
                <div>
                    <p class="text-[10px] md:text-xs font-bold text-gray-500 uppercase">Docs</p>
                    <p class="text-lg md:text-xl font-bold text-gray-900">{{ $materials->whereIn('file_type', ['pdf', 'doc', 'docx'])->count() }}</p>
                </div>
            </div>
            
            <div class="bg-white p-3 md:p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col md:flex-row items-center justify-center md:justify-start text-center md:text-left">
                <div class="p-2 bg-nav-footer rounded-lg md:mr-4 mb-2 md:mb-0">
                    <img src="{{ asset('img/icono_multimedia.png') }}" alt="Media" class="w-5 h-5 md:w-6 md:h-6">
                </div>
                <div>
                    <p class="text-[10px] md:text-xs font-bold text-gray-500 uppercase">Media</p>
                    <p class="text-lg md:text-xl font-bold text-gray-900">{{ $materials->whereIn('file_type', ['image', 'video', 'audio'])->count() }}</p>
                </div>
            </div>
        </div>

        {{-- Grid de Materiales --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
            @foreach($materials as $material)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300 material-card flex flex-col"
                     data-type="{{ $material->file_type }}"
                     data-title="{{ strtolower($material->title) }}">
                    
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-shrink-0">
                                <img src="{{ asset($material->file_icon) }}" 
                                     alt="{{ $material->file_type }}" 
                                     class="w-12 h-12 object-contain"
                                     onerror="this.src='{{ asset('img/icono_docs.png') }}'">
                            </div>
                            
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide ml-2
                                {{ in_array($material->file_type, ['pdf']) ? 'bg-red-50 text-red-700 border border-red-100' : 
                                   (in_array($material->file_type, ['doc', 'docx', 'doc']) ? 'bg-blue-50 text-blue-700 border border-blue-100' : 
                                   (in_array($material->file_type, ['xls', 'xlsx']) ? 'bg-green-50 text-green-700 border border-green-100' : 
                                   (in_array($material->file_type, ['image']) ? 'bg-purple-50 text-purple-700 border border-purple-100' : 
                                   'bg-gray-50 text-gray-700 border border-gray-100'))) }}">
                                {{ $material->file_type }}
                            </span>
                        </div>

                        <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 text-lg">{{ $material->title }}</h3>
                        
                        @if($material->description)
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2 flex-1">{{ $material->description }}</p>
                        @else
                            <div class="flex-1"></div>
                        @endif

                        <div class="border-t border-gray-100 pt-3 mt-2 space-y-1.5 text-xs text-gray-400">
                            <div class="flex justify-between">
                                <span>Tamaño:</span>
                                <span class="font-medium text-gray-600">{{ $material->file_size_formatted }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Fecha:</span>
                                <span class="font-medium text-gray-600">{{ $material->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-5 py-3 border-t border-gray-200 flex gap-2">
                        <a href="{{ route('groups.download', $material->id) }}" class="flex-1 bg-white text-button border border-button border-opacity-30 text-center py-2 rounded-lg text-sm font-semibold hover:bg-button hover:text-white transition-colors no-underline shadow-sm">
                            Descargar
                        </a>
                        
                        @if($material->can_preview)
                        <button onclick="previewMaterial({{ $material->id }}, '{{ $material->file_type }}', '{{ route('groups.view', $material->id) }}', '{{ $material->title }}')" class=" text-sm px-3 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors" title="Ver archivo">
                           Abrir
                        </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($materials->isEmpty())
        <div class="text-center py-16 md:py-24 bg-white rounded-xl border border-dashed border-gray-300">
            <div class="flex justify-center items-center mb-4 opacity-60 text-6xl">
                <img src="{{ asset('img/icono_archivo.png') }}" alt="Materiales" class="w-20 h-20"></div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No hay materiales disponibles</h3>
            <p class="text-gray-500 mb-4">Aún no se han subido materiales para este grupo.</p>
        </div>
        @endif

        @if($materials->hasPages())
        <div class="mt-8">
            {{ $materials->links() }}
        </div>
        @endif
    </div>
</div>

<div id="materialInfoModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-end md:items-center justify-center z-50 hidden p-0 md:p-4 backdrop-blur-sm">
    <div class="bg-white w-full md:w-full md:max-w-md rounded-t-xl md:rounded-xl shadow-2xl">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-900">Detalles del Archivo</h2>
            <button onclick="closeMaterialInfoModal()" class="text-gray-400 hover:text-gray-600 text-3xl md:text-2xl p-2">&times;</button>
        </div>
        <div class="p-6" id="materialInfoContent"></div>
        <div class="p-4 border-t border-gray-200 bg-gray-50 text-right rounded-b-xl">
            <button onclick="closeMaterialInfoModal()" class="bg-button text-white px-6 py-3 md:py-2 rounded-lg font-semibold hover:bg-blue-600 transition-colors shadow-sm w-full md:w-auto">Cerrar</button>
        </div>
    </div>
</div>

<div id="previewModal" class="fixed inset-0 bg-black bg-opacity-90 flex items-center justify-center z-[60] hidden backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white w-full h-full md:h-[90vh] md:w-[90vw] md:rounded-xl shadow-2xl flex flex-col relative overflow-hidden">
        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center bg-gray-50 md:rounded-t-xl z-10 shadow-sm">
            <div class="flex flex-col min-w-0">
                <h2 id="previewTitle" class="text-lg font-bold text-gray-900 truncate pr-2">Vista Previa</h2>
                <p id="previewSubtitle" class="text-xs text-gray-500 md:hidden">...</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="closePreviewModal()" class="text-gray-500 hover:text-white hover:bg-red-500 transition-colors bg-gray-100 rounded-lg p-2 md:p-1.5 shadow-sm">
                    <span class="sr-only">Cerrar</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
        <div id="previewContent" class="flex-1 bg-gray-100 flex items-center justify-center overflow-auto relative w-full h-full">
            <div class="animate-pulse flex flex-col items-center text-gray-400">
                <span class="font-medium">Cargando...</span>
            </div>
        </div>
        <div id="previewActions" class="hidden absolute bottom-6 right-6 md:bottom-8 md:right-8 z-20">
            <a id="externalLink" href="#" target="_blank" class="flex items-center gap-2 bg-button text-white px-4 py-3 rounded-full shadow-lg hover:bg-blue-600 transition-transform transform hover:scale-105 font-semibold text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span class="md:hidden">Abrir</span>
                <span class="hidden md:inline">Abrir en pestaña nueva</span>
            </a>
        </div>
    </div>
</div>

<style>
    #previewContent iframe { -webkit-overflow-scrolling: touch; overflow-y: scroll; }
</style>

<script>
function filterMaterials(type) {
    const materials = document.querySelectorAll('.material-card');
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(btn => {
        btn.classList.remove('bg-button', 'text-white');
        btn.classList.add('bg-white', 'text-gray-700', 'hover:bg-button', 'hover:text-white');
    });
    
    const activeBtn = event.currentTarget; 
    activeBtn.classList.remove('bg-white', 'text-gray-700', 'hover:bg-button', 'hover:text-white');
    activeBtn.classList.add('bg-button', 'text-white');
    
    materials.forEach(material => {
        if (type === 'all') {
            material.style.display = 'block';
        } else if (type === 'doc') {
            const t = material.getAttribute('data-type');
            material.style.display = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(t) ? 'block' : 'none';
        } else if (type === 'video') {
            const t = material.getAttribute('data-type');
            material.style.display = ['video', 'audio', 'image'].includes(t) ? 'block' : 'none';
        } else {
             material.style.display = material.getAttribute('data-type') === type ? 'block' : 'none';
        }
    });
}

function searchMaterials() {
    const searchTerm = document.getElementById('searchMaterials').value.toLowerCase();
    const materials = document.querySelectorAll('.material-card');
    materials.forEach(material => {
        const title = material.getAttribute('data-title');
        material.style.display = title.includes(searchTerm) ? 'block' : 'none';
    });
}

function previewMaterial(id, type, url, title) {
    const modal = document.getElementById('previewModal');
    const content = document.getElementById('previewContent');
    const titleEl = document.getElementById('previewTitle');
    const subTitleEl = document.getElementById('previewSubtitle');
    const actions = document.getElementById('previewActions');
    const externalLink = document.getElementById('externalLink');
    
    titleEl.textContent = title;
    subTitleEl.textContent = 'Cargando ' + type + '...';
    content.innerHTML = `<div class="animate-pulse flex flex-col items-center text-gray-400"><span class="font-medium">Cargando archivo...</span></div>`;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    let downloadUrl = url.replace('view', 'download');
    externalLink.href = downloadUrl;
    actions.classList.remove('hidden'); 
    
    type = String(type).toLowerCase();
    let html = '';
    
    setTimeout(() => {
        if (type === 'image') {
            html = `<img src="${url}" class="max-w-full max-h-full object-contain p-1 md:p-4 shadow-sm">`;
            actions.classList.add('hidden');
            subTitleEl.textContent = 'Imagen';
        } else if (type === 'pdf') {
            html = `<iframe src="${url}" class="w-full h-full border-0 bg-white" allowfullscreen></iframe>`;
            subTitleEl.textContent = 'Documento PDF';
        } else if (type === 'video') {
            html = `<div class="w-full h-full flex items-center justify-center bg-black"><video controls class="max-w-full max-h-full" autoplay playsinline><source src="${url}"><p class="text-white p-4 text-center">Tu navegador no soporta este formato.<br>Usa el botón "Abrir" para descargarlo.</p></video></div>`;
            actions.classList.remove('hidden');
            subTitleEl.textContent = 'Video';
        } else if (type === 'audio') {
            html = `<div class="bg-white p-8 md:p-12 rounded-xl shadow-lg text-center w-11/12 md:w-auto flex flex-col items-center"><div class="text-6xl mb-6 animate-bounce">🎵</div><h3 class="font-bold text-gray-800 mb-4 text-lg line-clamp-2">${title}</h3><audio controls class="w-full min-w-[250px] md:min-w-[400px]"><source src="${url}"></audio></div>`;
            subTitleEl.textContent = 'Audio';
        } else if (['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(type)) {
            if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
                html = `<div class="text-center p-8 bg-white rounded-xl shadow-lg m-4 max-w-md"><div class="text-4xl mb-4">🚧</div><h3 class="text-lg font-bold text-gray-800 mb-2">Modo Local</h3><p class="text-gray-600 mb-6 text-sm">Google Viewer no funciona en localhost.<br>En producción se verá correctamente.</p><a href="${downloadUrl}" class="bg-button text-white px-6 py-2 rounded-lg text-sm w-full block">Descargar</a></div>`;
            } else {
                const fullUrl = window.location.origin + url; 
                const googleViewerUrl = `https://docs.google.com/gview?url=${encodeURIComponent(fullUrl)}&embedded=true`;
                html = `<iframe src="${googleViewerUrl}" class="w-full h-full border-0 bg-white" onerror="this.innerHTML='Error al cargar vista previa'"></iframe>`;
            }
            subTitleEl.textContent = 'Documento Office';
        } else {
            html = `<div class="text-center p-6 bg-white rounded-xl shadow-md m-4"><div class="text-4xl mb-4">📦</div><p class="mb-4 text-gray-600 font-medium">Vista previa no disponible.</p><a href="${downloadUrl}" class="bg-button text-white px-6 py-3 rounded-lg font-semibold inline-block shadow-lg">Descargar Archivo</a></div>`;
            actions.classList.add('hidden');
            subTitleEl.textContent = 'Archivo';
        }
        content.innerHTML = html;
    }, 200);
}

function closePreviewModal() {
    const modal = document.getElementById('previewModal');
    document.getElementById('previewContent').innerHTML = ''; 
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function closeMaterialInfoModal() {
    document.getElementById('materialInfoModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') { 
        closeMaterialInfoModal(); 
        closePreviewModal(); 
    }
});
</script>

<style>
.line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.material-card:hover { transform: translateY(-2px); }
</style>
@endsection
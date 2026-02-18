@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-4 md:py-8">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        
        {{-- Encabezado Adaptado --}}
        <div class="mb-6 text-center md:text-left pt-2 md:pt-0">
            <div class="flex flex-col md:flex-row justify-between items-center gap-3">
                <div class="w-full md:w-auto">
                    <h1 class="text-xl md:text-3xl font-black text-gray-900 uppercase tracking-tighter leading-tight">Materiales - {{ $groupName }}</h1>
                    <p class="text-gray-500 text-[11px] md:text-sm mt-1 font-medium uppercase tracking-wide">Recursos compartidos por coordinadores</p>
                </div>
                <div class="flex justify-center md:justify-end w-full md:w-auto">
                    <span class="px-4 py-1.5 bg-yellow-100 text-yellow-800 rounded-full text-[9px] md:text-[10px] font-black border border-yellow-200 uppercase tracking-[0.2em]">
                        {{ $groupName }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Barra de Herramientas Móvil --}}
        <div class="mb-8 flex flex-col lg:flex-row gap-4">
            
            {{-- Filtros Centrados en móvil con iconos --}}
            <div class="w-full lg:w-auto">
                <div class="flex justify-center md:grid md:grid-cols-3 gap-2 overflow-x-auto pb-2 md:pb-0 no-scrollbar">
                    <button onclick="filterMaterials('all')" 
                            class="filter-btn active whitespace-nowrap min-w-[80px] justify-center px-4 py-3 rounded-2xl bg-button text-white text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-100 flex items-center border border-transparent">
                        Todos
                    </button>
                    <button onclick="filterMaterials('doc')" 
                            class="filter-btn whitespace-nowrap min-w-[110px] justify-center px-4 py-3 rounded-2xl border border-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest transition-all flex items-center  bg-white shadow-sm">
                            <span class="mr-1.5"><img src="{{ asset('img/icono_docs.png') }}" class="w-4 h-4" onerror="this.style.display='none'"></span>
                            <span>Documentos</span>
                    </button>
                    <button onclick="filterMaterials('video')" 
                            class="filter-btn whitespace-nowrap min-w-[110px] justify-center px-4 py-3 rounded-2xl border border-gray-100 text-gray-400 text-[10px] font-black uppercase tracking-widest transition-all flex items-center bg-white shadow-sm">
                            <span class="mr-1.5"><img src="{{ asset('img/icono_multimedia.png') }}" class="w-4 h-4" onerror="this.style.display='none'"></span>
                            <span>Multimedia</span>
                    </button>
                </div>
            </div>
            
            <div class="relative w-full lg:w-72 lg:ml-auto">
                <input type="text" id="searchMaterials" placeholder="Buscar por título..." 
                       class="w-full pl-10 pr-4 py-3.5 md:py-3 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button outline-none text-sm transition-all shadow-sm"
                       onkeyup="searchMaterials()">
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2 opacity-30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- Grid de Materiales: 1 col móvil / 2-4 cols PC --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
            @foreach($materials as $material)
                <div class="bg-white rounded-[2rem] md:rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl hover:shadow-blue-50 transition-all duration-500 material-card flex flex-col group overflow-hidden"
                     data-type="{{ $material->file_type }}"
                     data-title="{{ strtolower($material->title) }}">
                    
                    <div class="p-5 md:p-6 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-4 md:mb-5">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-gray-50 rounded-2xl flex items-center justify-center border border-gray-100 group-hover:bg-blue-50 group-hover:border-blue-100 transition-colors">
                                <img src="{{ asset($material->file_icon) }}" class="w-6 h-6 md:w-7 md:h-7 object-contain opacity-60 group-hover:opacity-100 transition-opacity" onerror="this.src='{{ asset('img/icono_docs.png') }}'">
                            </div>
                            
                            <div class="flex flex-col items-end gap-1.5">
                                <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase bg-gray-50 text-gray-400 border border-gray-100 group-hover:bg-white transition-colors">
                                    {{ $material->file_type }}
                                </span>
                                
                                @if($material->created_at->gt(now()->subHours(48)))
                                    <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase bg-green-500 text-white animate-pulse shadow-sm">
                                        ¡NUEVO!
                                    </span>
                                @endif
                            </div>
                        </div>

                        <h3 class="font-black text-text-dark mb-2 line-clamp-2 text-sm md:text-base uppercase tracking-tight group-hover:text-button transition-colors leading-tight">{{ $material->title }}</h3>
                        <p class="text-[11px] md:text-xs text-text-light mb-4 md:mb-6 line-clamp-3 flex-1 leading-relaxed font-medium">
                            {{ $material->description ?? 'Recurso informativo compartido con la comunidad del grupo.' }}
                        </p>

                        <div class="border-t border-gray-50 pt-4 mt-2 text-[8px] md:text-[9px] text-gray-300 font-black uppercase tracking-widest flex justify-between">
                            <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg> {{ $material->file_size_formatted }}</span>
                            <span>{{ $material->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="bg-gray-50/50 p-3 md:p-4 border-t border-gray-50 flex gap-2">
                        <a href="{{ route('groups.download', $material->id) }}" class="flex-1 bg-white text-button border border-gray-200 text-center py-3 md:py-2.5 rounded-xl text-[10px] font-black uppercase hover:bg-button hover:text-white hover:border-button transition-all shadow-sm active:scale-95">Descargar</a>
                        @if($material->can_preview)
                        <button onclick="previewMaterial({{ $material->id }}, '{{ $material->file_type }}', '{{ route('groups.view', $material->id) }}', '{{ $material->title }}')" class="px-5 py-3 md:py-2.5 bg-blue-100 text-blue-600 rounded-xl text-[10px] font-black uppercase hover:bg-blue-600 hover:text-white transition-all active:scale-95">Abrir</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($materials->isEmpty())
        <div class="text-center py-20 md:py-32 bg-white rounded-[2rem] md:rounded-[2.5rem] border-2 border-dashed border-gray-100 flex flex-col items-center">
            <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                 <img src="{{ asset('img/icono_archivo.png') }}" class="w-10 h-10 opacity-20">
            </div>
            <p class="text-gray-400 font-black uppercase text-[10px] tracking-[0.2em] px-4 text-center">Aún no hay archivos compartidos en esta sección</p>
        </div>
        @endif

        <div class="mt-12 flex justify-center pb-10">
            {{ $materials->links() }}
        </div>
    </div>
</div>

{{-- MODAL DE VISTA PREVIA --}}
<div id="previewModal" class="fixed inset-0 bg-black/95 z-[100] hidden flex flex-col backdrop-blur-md">
    <div class="h-16 flex items-center justify-between px-4 md:px-6 bg-white shadow-xl z-10">
        <div class="flex flex-col min-w-0 flex-1 pr-4">
            <h2 id="previewTitle" class="text-xs md:text-sm font-black text-gray-800 uppercase truncate">Vista Previa</h2>
            <p id="previewSubtitle" class="text-[8px] md:text-[9px] text-gray-400 font-bold uppercase tracking-widest">Cargando...</p>
        </div>
        <div class="flex items-center gap-2 md:gap-3">
            <button onclick="toggleFullScreen()" class="p-2 md:p-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all hidden sm:block">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            </button>
            <button onclick="closePreviewModal()" class="p-2 md:p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                <svg class="w-6 h-6 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
    
    <div id="previewContainer" class="flex-1 relative overflow-hidden flex items-center justify-center bg-gray-900">
        <div id="previewContent" class="w-full h-full flex items-center justify-center p-2">
            <div class="text-white font-black animate-pulse uppercase text-[10px] tracking-widest text-center px-4">Iniciando motor de visualización...</div>
        </div>
    </div>
</div>

<script>
function previewMaterial(id, type, url, title) {
    const modal = document.getElementById('previewModal');
    const content = document.getElementById('previewContent');
    const titleEl = document.getElementById('previewTitle');
    const subTitleEl = document.getElementById('previewSubtitle');
    
    titleEl.textContent = title;
    subTitleEl.textContent = type.toUpperCase();
    content.innerHTML = `<div class="text-white font-black animate-pulse text-[10px] uppercase tracking-[0.3em] text-center">Cargando recurso multimedia...</div>`;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        type = String(type).toLowerCase();
        
        if (type === 'image' || ['jpg', 'png', 'jpeg'].includes(type)) {
            content.innerHTML = `<img src="${url}" class="max-w-full max-h-full object-contain shadow-2xl rounded-2xl scale-95 animate-fade-in">`;
        } 
        else if (type === 'pdf') {
            content.innerHTML = `<iframe src="${url}#toolbar=0" class="w-full h-full border-0 bg-white md:rounded-xl" allowfullscreen></iframe>`;
        } 
        else if (['mp4', 'mov', 'avi', 'video'].includes(type)) {
            content.innerHTML = `
                <video controls autoplay class="max-w-full max-h-[80vh] md:max-h-[85vh] rounded-2xl md:rounded-3xl shadow-2xl bg-black border border-white/10">
                    <source src="${url}" type="video/mp4">
                    Tu dispositivo no soporta este formato de video.
                </video>`;
        }
        else if (['mp3', 'wav', 'audio'].includes(type)) {
            content.innerHTML = `
                <div class="bg-white p-8 md:p-20 rounded-[2.5rem] md:rounded-[3rem] shadow-2xl text-center flex flex-col items-center animate-slide-up w-[90%] max-w-sm">
                    <div class="w-20 h-20 md:w-24 md:h-24 bg-blue-50 rounded-[1.5rem] md:rounded-[2rem] flex items-center justify-center mb-6 md:mb-8 shadow-inner">
                        <img src="{{ asset('img/icono_audio.png') }}" class="w-10 h-10 md:w-12 md:h-12 opacity-40">
                    </div>
                    <audio controls autoplay class="w-full mb-6">
                        <source src="${url}" type="audio/mpeg">
                        Tu navegador no soporta audio.
                    </audio>
                    <p class="text-gray-400 font-black text-[9px] md:text-[10px] uppercase tracking-[0.3em]">Reproductor Parroquial</p>
                </div>`;
        }
        else {
            content.innerHTML = `
                <div class="text-center p-10 bg-white rounded-[2.5rem] shadow-2xl w-[90%] max-w-sm">
                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                         <img src="{{ asset('img/icono_docs.png') }}" class="w-8 h-8 opacity-30">
                    </div>
                    <h3 class="font-black text-gray-800 mb-4 uppercase tracking-tighter text-sm">VISTA PREVIA NO SOPORTADA</h3>
                    <a href="${url.replace('view', 'download')}" class="inline-block bg-button text-white px-8 py-3.5 rounded-2xl font-black uppercase text-xs shadow-lg shadow-blue-100 active:scale-95 transition-transform">Descargar archivo</a>
                </div>`;
        }
    }, 450);
}

function toggleFullScreen() {
    const element = document.getElementById('previewContainer');
    if (!document.fullscreenElement) {
        if (element.requestFullscreen) element.requestFullscreen();
        else if (element.webkitRequestFullscreen) element.webkitRequestFullscreen();
        else if (element.msRequestFullscreen) element.msRequestFullscreen();
    } else {
        if (document.exitFullscreen) document.exitFullscreen();
    }
}

function closePreviewModal() {
    if (document.fullscreenElement) document.exitFullscreen();
    document.getElementById('previewModal').classList.add('hidden');
    document.getElementById('previewContent').innerHTML = '';
    document.body.style.overflow = 'auto';
}

function filterMaterials(type) {
    const materials = document.querySelectorAll('.material-card');
    const btns = document.querySelectorAll('.filter-btn');
    
    btns.forEach(b => { 
        b.classList.remove('bg-button', 'text-white', 'shadow-lg', 'shadow-blue-100'); 
        b.classList.add('bg-white', 'text-gray-400'); 
    });
    
    const activeBtn = event.currentTarget;
    activeBtn.classList.add('bg-button', 'text-white', 'shadow-lg', 'shadow-blue-100');
    activeBtn.classList.remove('bg-white', 'text-gray-400');

    materials.forEach(m => {
        const mType = m.dataset.type.toLowerCase();
        if (type === 'all') m.style.display = 'flex';
        else if (type === 'doc') m.style.display = ['pdf', 'doc', 'docx'].includes(mType) ? 'flex' : 'none';
        else if (type === 'video') m.style.display = ['image', 'video', 'mp4', 'mov', 'avi', 'audio', 'mp3', 'wav'].includes(mType) ? 'flex' : 'none';
        else m.style.display = mType === type ? 'flex' : 'none';
    });
}

function searchMaterials() {
    const q = document.getElementById('searchMaterials').value.toLowerCase();
    document.querySelectorAll('.material-card').forEach(m => {
        const matches = m.dataset.title.includes(q);
        m.style.display = matches ? 'flex' : 'none';
    });
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closePreviewModal(); });
</script>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
    @keyframes slide-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slide-up { animation: slide-up 0.4s ease-out forwards; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .line-clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endsection
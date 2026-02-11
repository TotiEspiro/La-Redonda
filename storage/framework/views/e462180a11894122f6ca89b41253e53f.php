

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-6 md:py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6 text-center md:text-left">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Materiales - <?php echo e($groupName); ?></h1>
                    <p class="text-gray-600 mt-1">Recursos y documentos del grupo</p>
                </div>
                <span class="px-4 py-1.5 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold border border-yellow-200 uppercase tracking-widest">
                    <?php echo e($groupName); ?>

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
                            <span class="mr-1.5"><img src="<?php echo e(asset('img/icono_docs.png')); ?>" class="w-4 h-4" onerror="this.style.display='none'"></span> 
                            <span>Documentos</span>
                    </button>
                    <button onclick="filterMaterials('video')" 
                            class="filter-btn justify-center px-2 py-2.5 rounded-lg border border-gray-200 text-gray-700 text-xs sm:text-sm font-medium transition-all flex items-center hover:bg-button hover:text-white bg-white shadow-sm">
                            <span class="mr-1.5"><img src="<?php echo e(asset('img/icono_multimedia.png')); ?>" class="w-4 h-4" onerror="this.style.display='none'"></span> 
                            <span>Multimedia</span>
                    </button>
                </div>
            </div>
            
            <div class="relative w-full lg:w-72 lg:ml-auto">
                <input type="text" id="searchMaterials" placeholder="Buscar materiales..." 
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-button focus:border-button text-sm shadow-sm transition-all"
                       onkeyup="searchMaterials()">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 opacity-50">
                    <img src="<?php echo e(asset('img/icono_buscar.png')); ?>" class="w-4 h-4" alt="Buscar">
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
            <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300 material-card flex flex-col"
                     data-type="<?php echo e($material->file_type); ?>"
                     data-title="<?php echo e(strtolower($material->title)); ?>">
                    
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-4">
                            <img src="<?php echo e(asset($material->file_icon)); ?>" class="w-10 h-10 object-contain" onerror="this.src='<?php echo e(asset('img/icono_docs.png')); ?>'">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black uppercase bg-gray-100 text-gray-600 border border-gray-200">
                                <?php echo e($material->file_type); ?>

                            </span>
                        </div>

                        <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 text-lg uppercase tracking-tight"><?php echo e($material->title); ?></h3>
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2 flex-1"><?php echo e($material->description ?? 'Sin descripción disponible.'); ?></p>

                        <div class="border-t border-gray-100 pt-3 mt-2 text-[10px] text-gray-400 font-bold uppercase tracking-widest flex justify-between">
                            <span><?php echo e($material->file_size_formatted); ?></span>
                            <span><?php echo e($material->created_at->format('d/m/Y')); ?></span>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-5 py-3 border-t border-gray-200 flex gap-2">
                        <a href="<?php echo e(route('groups.download', $material->id)); ?>" class="flex-1 bg-white text-button border border-button border-opacity-30 text-center py-2 rounded-lg text-xs font-black uppercase hover:bg-button hover:text-white transition-all shadow-sm">Descargar</a>
                        <?php if($material->can_preview): ?>
                        <button onclick="previewMaterial(<?php echo e($material->id); ?>, '<?php echo e($material->file_type); ?>', '<?php echo e(route('groups.view', $material->id)); ?>', '<?php echo e($material->title); ?>')" class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg text-xs font-black uppercase hover:bg-blue-200 transition-all">Abrir</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if($materials->isEmpty()): ?>
        <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200">
            <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">No hay materiales en esta sección</p>
        </div>
        <?php endif; ?>

        <div class="mt-8">
            <?php echo e($materials->links()); ?>

        </div>
    </div>
</div>


<div id="previewModal" class="fixed inset-0 bg-black/95 z-[100] hidden flex flex-col backdrop-blur-md">
    
    <div class="h-16 flex items-center justify-between px-6 bg-white shadow-xl z-10">
        <div class="flex flex-col min-w-0">
            <h2 id="previewTitle" class="text-sm font-black text-gray-800 uppercase truncate">Vista Previa</h2>
            <p id="previewSubtitle" class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">Cargando recurso...</p>
        </div>
        <div class="flex items-center gap-3">
            
            <button onclick="toggleFullScreen()" class="p-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all" title="Pantalla Completa">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
            </button>
            
            <button onclick="closePreviewModal()" class="p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
    </div>
    
    
    <div id="previewContainer" class="flex-1 relative overflow-hidden flex items-center justify-center bg-gray-900">
        <div id="previewContent" class="w-full h-full flex items-center justify-center">
            <div class="text-white font-bold animate-pulse uppercase text-xs tracking-widest">Cargando...</div>
        </div>
    </div>
</div>


<div id="statusModal" class="fixed inset-0 bg-black/70 backdrop-blur-md flex items-center justify-center z-[120] hidden p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl p-8 text-center animate-fade-in">
        <div id="statusIcon" class="mx-auto mb-6"></div>
        <h3 id="statusTitle" class="text-xl font-black text-gray-800 uppercase tracking-tight mb-2"></h3>
        <p id="statusMsg" class="text-gray-500 mb-8 text-sm font-medium leading-relaxed"></p>
        <button onclick="closeStatus()" class="w-full py-4 bg-button text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg">Entendido</button>
    </div>
</div>

<script>
function closeStatus() { document.getElementById('statusModal').classList.add('hidden'); }

function previewMaterial(id, type, url, title) {
    const modal = document.getElementById('previewModal');
    const content = document.getElementById('previewContent');
    const titleEl = document.getElementById('previewTitle');
    const subTitleEl = document.getElementById('previewSubtitle');
    
    titleEl.textContent = title;
    subTitleEl.textContent = type.toUpperCase();
    content.innerHTML = `<div class="text-white font-bold animate-pulse text-xs uppercase tracking-widest">Preparando visualización...</div>`;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        type = String(type).toLowerCase();
        if (type === 'image' || ['jpg', 'png', 'jpeg'].includes(type)) {
            content.innerHTML = `<img src="${url}" class="max-w-full max-h-full object-contain shadow-2xl">`;
        } else if (type === 'pdf') {
            content.innerHTML = `<iframe src="${url}#toolbar=0" class="w-full h-full border-0 bg-white" allowfullscreen></iframe>`;
        } else {
            content.innerHTML = `<div class="text-center p-10 bg-white rounded-3xl"><h3 class="font-black text-gray-800 mb-4">VISTA PREVIA NO DISPONIBLE</h3><a href="${url.replace('view', 'download')}" class="bg-button text-white px-8 py-3 rounded-xl font-black uppercase text-xs">Descargar para ver</a></div>`;
        }
    }, 300);
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
    
    btns.forEach(b => { b.classList.remove('bg-button', 'text-white'); b.classList.add('bg-white', 'text-gray-700'); });
    event.currentTarget.classList.add('bg-button', 'text-white');

    materials.forEach(m => {
        if (type === 'all') m.style.display = 'flex';
        else if (type === 'doc') m.style.display = ['pdf', 'doc', 'docx'].includes(m.dataset.type) ? 'flex' : 'none';
        else if (type === 'video') m.style.display = ['image', 'video', 'audio'].includes(m.dataset.type) ? 'flex' : 'none';
        else m.style.display = m.dataset.type === type ? 'flex' : 'none';
    });
}

function searchMaterials() {
    const q = document.getElementById('searchMaterials').value.toLowerCase();
    document.querySelectorAll('.material-card').forEach(m => {
        m.style.display = m.dataset.title.includes(q) ? 'flex' : 'none';
    });
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') closePreviewModal(); });
</script>

<style>
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/grupos/materials.blade.php ENDPATH**/ ?>
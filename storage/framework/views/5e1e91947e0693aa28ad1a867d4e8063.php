

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-background-light">
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex justify-between items-center mb-3 md:mb-0">
                <div class="flex items-center space-x-2">
                    <img src="../img/icono_biblia.png" class="w-8 h-8" alt="Diario"> 
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900">Diario de La Redonda</h1>
                </div>

                <div class="hidden md:block flex-1 max-w-md mx-6">
                    <div class="relative">
                        <input type="text" id="searchDocumentsDesktop" placeholder="Buscar documentos..." 
                               class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-button focus:border-button outline-none transition-all text-sm">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <img src="../img/icono_buscar.png" class="w-4 h-4 opacity-50" alt="Buscar"> 
                        </div>
                    </div>
                </div>

                <button id="createDocumentBtn" 
                        class="bg-button text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-900 transition-all shadow-sm flex items-center space-x-1 text-sm">
                    <span class="text-xl leading-none">+</span>
                    <span class="hidden sm:inline">Nuevo Documento</span>
                </button>
            </div>

            <div class="md:hidden pb-1">
                <div class="relative">
                    <input type="text" id="searchDocumentsMobile" placeholder="Buscar en tu diario..." 
                           class="w-full px-4 py-2.5 pl-10 border border-gray-300 rounded-lg bg-gray-50 focus:bg-white focus:ring-2 focus:ring-button focus:border-button outline-none transition-all text-sm">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                        <img src="../img/icono_buscar.png" class="w-4 h-4 opacity-50" alt="Buscar"> 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
        
        <div class="mb-6">
            <div class="grid grid-cols-6 md:flex md:flex-wrap gap-2" id="filterButtons">
                
                <button data-filter="all" 
                        class="col-span-2 filter-btn justify-center px-3 py-2 rounded-lg bg-button text-white text-xs font-medium transition-all shadow-sm flex items-center space-x-1 border border-transparent">
                    <span>Todos</span>
                </button>

                <button data-filter="favorite" 
                        class="col-span-2 filter-btn justify-center px-3 py-2 rounded-lg border border-gray-300 text-gray-700 text-xs font-medium hover:bg-button hover:text-white transition-colors flex items-center space-x-1 shadow-sm">
                    <img src="../img/icono_favoritos.png" class="w-3.5 h-3.5" alt="Fav"> 
                    <span>Favoritos</span>
                </button>

                <button data-filter="texto" 
                        class="col-span-2 filter-btn justify-center px-3 py-2 rounded-lg border border-gray-300 text-gray-700 text-xs font-medium hover:bg-button hover:text-white transition-all flex items-center space-x-1 shadow-sm">
                    <img src="../img/icono_reflexion.png" class="w-3.5 h-3.5" alt="Ref">
                    <span>Reflexión</span>
                </button>

                <button data-filter="mapa_conceptual" 
                        class="col-span-3 filter-btn justify-center px-3 py-2 rounded-lg border border-gray-300 text-gray-700 text-xs font-medium hover:bg-button hover:text-white transition-all flex items-center space-x-1 shadow-sm">
                    <img src="../img/icono_mapa.png" class="w-3.5 h-3.5" alt="Map">
                    <span>Mapas</span>
                </button>

                <button data-filter="lista" 
                        class="col-span-3 filter-btn justify-center px-3 py-2 rounded-lg border border-gray-300 text-gray-700 text-xs font-medium hover:bg-button hover:text-white transition-all flex items-center space-x-1 shadow-sm">
                    <img src="../img/icono_activo.png" class="w-3.5 h-3.5" alt="Lis"> 
                    <span>Listas</span>
                </button>
            </div>
        </div>

        <div id="documentsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <?php $__currentLoopData = $entries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="document-card bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-all duration-200 cursor-pointer group h-full flex flex-col"
                     data-type="<?php echo e($entry->type); ?>"
                     data-favorite="<?php echo e($entry->is_favorite ? 'true' : 'false'); ?>"
                     data-title="<?php echo e(strtolower($entry->title)); ?>"
                     data-id="<?php echo e($entry->id); ?>">
                    
                    <div class="p-4 flex flex-col h-full">
                        <div class="flex justify-between items-start mb-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white text-lg font-bold shadow-sm"
                                 style="background-color: <?php echo e($entry->color); ?>">
                                <?php echo e(strtoupper(substr($entry->title, 0, 1))); ?>

                            </div>
                            <button class="favorite-btn p-1.5 rounded-full transition-colors <?php echo e($entry->is_favorite ? : 'text-gray-200'); ?>"
                                    data-id="<?php echo e($entry->id); ?>">
                                <img src="../img/icono_favoritos.png" class="w-8 h-8" alt="Favorito">
                            </button>
                        </div>
                        
                        <h3 class="font-bold text-gray-800 mb-1 truncate text-base leading-tight"><?php echo e($entry->title); ?></h3>
                        
                        <div class="mb-3">
                            <span class="inline-flex items-center px-2 py-0.5 bg-gray-50 text-gray-500 text-[10px] uppercase tracking-wide font-bold rounded border border-gray-100">
                                <?php echo e($entry->type_display); ?>

                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center pt-3 border-t border-gray-50 mt-auto">
                            <span class="text-[10px] text-gray-400"><?php echo e($entry->created_at->format('d M Y')); ?></span>
                            <div class="flex space-x-1  transition-opacity">
                                <button class="edit-btn p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors"
                                        data-id="<?php echo e($entry->id); ?>">
                                   <img src="../img/icono_editar.png" class="w-6 h-6" alt="Editar"> 
                                </button>
                                <button class="delete-btn p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded transition-colors"
                                        data-id="<?php echo e($entry->id); ?>">
                                    <img src="../img/icono_eliminar.png" class="w-6 h-6" alt="Eliminar">
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php if(count($entries) === 0): ?>
        <div class="text-center py-12">
            <div class="inline-block p-4 rounded-full bg-gray-50 mb-4">
                <img src="../img/icono_biblia.png" class="w-12 h-12 opacity-30" alt="Vacío"> 
            </div>
            <h3 class="text-lg font-bold text-gray-900">Tu diario está vacío</h3>
            <p class="text-gray-500 text-sm mt-1 mb-6">Comienza a escribir tu historia hoy.</p>
            <button id="createFirstDocumentBtn" class="bg-button text-white px-6 py-2.5 rounded-lg font-semibold hover:bg-blue-900 transition-colors shadow-sm text-sm">
                + Crear Primer Documento
            </button>
        </div>
        <?php endif; ?>

        <?php if($paginator->hasPages()): ?>
        <div class="mt-8">
            <?php echo e($paginator->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>

<?php echo $__env->make('diario.partials.editor-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script src="<?php echo e(asset('js/diario/app.js')); ?>"></script>
<script src="<?php echo e(asset('js/diario/editor-modal.js')); ?>"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchMobile = document.getElementById('searchDocumentsMobile');
        const searchDesktop = document.getElementById('searchDocumentsDesktop');
        
        function triggerSearch(val) {
        }

        if(searchMobile && searchDesktop){
            searchMobile.addEventListener('input', (e) => {
                searchDesktop.value = e.target.value;
                // Disparar evento de búsqueda real
                const event = new Event('input', { bubbles: true });
                searchDesktop.dispatchEvent(event);
            });
            
            searchDesktop.addEventListener('input', (e) => {
                searchMobile.value = e.target.value;
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/diario/index.blade.php ENDPATH**/ ?>
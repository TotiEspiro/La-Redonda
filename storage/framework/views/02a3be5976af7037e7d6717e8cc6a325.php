

<?php $__env->startSection('content'); ?>
<div class="py-8 md:py-12 bg-background-light min-h-screen">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="text-center mb-10 md:mb-12">
            <h1 class="text-3xl md:text-4xl font-black text-text-dark mb-4 border-b-4 border-button pb-2 inline-block px-4 uppercase tracking-tighter"><?php echo e($categoria); ?></h1>
            <p class="text-text-dark text-base md:text-lg max-w-3xl mx-auto mt-4 leading-relaxed px-2">
                <?php echo e($descripcion); ?>

            </p>
        </div>

        
        <?php
            $esCategoriaManual = in_array($categoria, ['Catequesis', 'Jóvenes', 'Mayores', 'Más Grupos']);
        ?>

        <?php if($esCategoriaManual): ?>
            
            <div class="w-full">
                <?php if($categoria == 'Catequesis'): ?>
                    <?php echo $__env->make('grupos.catequesis', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($categoria == 'Jóvenes'): ?>
                    <?php echo $__env->make('grupos.jovenes', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($categoria == 'Mayores'): ?>
                    <?php echo $__env->make('grupos.mayores', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php elseif($categoria == 'Más Grupos'): ?>
                    <?php echo $__env->make('grupos.especiales', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            
            <?php if($groups->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-xl shadow-lg p-5 border border-gray-100 flex flex-col h-full">
                            <?php if($group->image): ?>
                                <img src="<?php echo e(Storage::url($group->image)); ?>" class="w-full h-48 object-cover rounded-lg mb-4">
                            <?php endif; ?>
                            <h3 class="text-xl font-bold text-text-dark mb-2"><?php echo e($group->name); ?></h3>
                            <p class="text-gray-600 text-sm mb-4 flex-grow"><?php echo e($group->description ?? 'Sin descripción.'); ?></p>
                            <?php echo $__env->make('partials.group-join-button', ['slug' => $group->category, 'nombre' => $group->name], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">No hay grupos disponibles en esta sección</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="text-center mt-8 md:mt-12">
            <a href="<?php echo e(route('grupos.index')); ?>" class="inline-flex items-center bg-white text-gray-700 border border-gray-300 px-6 py-3 rounded-full font-semibold hover:bg-gray-50 hover:text-black transition-all shadow-sm">
                <span class="mr-2">←</span> Volver a Grupos
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/grupos/categoria.blade.php ENDPATH**/ ?>
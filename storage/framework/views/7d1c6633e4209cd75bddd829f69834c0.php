

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 md:bg-white md:min-h-0 pb-10 md:pb-0">

    <div class="bg-button text-white shadow-md md:shadow-none md:rounded-t-xl">
        <div class="container mx-auto px-4 py-4 md:px-6 md:py-5 md:mt-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold">Gestión de Donaciones</h1>
                    <p class="text-blue-100 text-xs md:text-sm hidden md:block">Administra y supervisa las donaciones recibidas</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-2 md:px-0 md:border md:border-t-0 md:border-gray-100 md:rounded-b-xl md:shadow-lg md:mb-8 bg-transparent md:bg-white">
        <div class="md:hidden space-y-3 pb-4">
            <?php $__empty_1 = true; $__currentLoopData = $donations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mx-1">
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-1">
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 leading-tight"><?php echo e($donation->card_holder); ?></h3>
                                <p class="text-xs text-gray-500 mt-0.5"><?php echo e($donation->email); ?></p>
                            </div>
                            <div class="text-right flex-shrink-0 ml-2">
                                <div class="text-lg font-bold text-green-600">$<?php echo e(number_format($donation->amount, 2)); ?></div>
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2 my-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-green-50 text-green-700 border border-green-100">
                                Completada
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-blue-50 text-blue-700 border border-blue-100">
                                <?php echo e($donation->formatted_frequency); ?>

                            </span>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-gray-100 text-xs text-gray-400">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                <span class="font-mono">•••• <?php echo e($donation->card_last_four); ?></span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <?php echo e($donation->created_at->format('d/m/Y')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-10 px-4 bg-white rounded-lg border border-gray-200 border-dashed mx-1">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay donaciones</h3>
                    <p class="mt-1 text-sm text-gray-500">Aún no se han registrado transacciones.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="hidden md:block overflow-x-auto p-6 pt-2">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donante</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frecuencia</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarjeta</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $donations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $donation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900"><?php echo e($donation->card_holder); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e($donation->email); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-green-600">$<?php echo e(number_format($donation->amount, 2)); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?php echo e($donation->formatted_frequency); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="font-mono flex items-center gap-1">
                                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                •••• <?php echo e($donation->card_last_four); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($donation->created_at->format('d/m/Y')); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Completada
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="py-8 px-4 text-center text-gray-500">
                            No hay donaciones registradas
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($donations->hasPages()): ?>
        <div class="px-4 py-4 md:px-6 border-t border-gray-100">
            <?php echo e($donations->links()); ?>

        </div>
        <?php endif; ?>
    </div>

    <div class="container mx-auto px-2 md:px-0">
        <div class="bg-white shadow-sm md:shadow-lg rounded-xl overflow-hidden border border-gray-200 md:border-gray-100 mb-8 mx-1 md:mx-0">
            <div class="bg-gray-50 px-4 md:px-6 py-3 border-b border-gray-100">
                <h3 class="text-base md:text-lg font-bold text-gray-800 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"></path></svg>
                    Resumen Financiero
                </h3>
            </div>
            <div class="p-4 md:p-6 space-y-4 md:space-y-0 md:grid md:grid-cols-2 md:gap-6">
                <div class="flex flex-col justify-center items-center p-4 bg-green-50 rounded-xl border border-green-100">
                    <span class="text-green-800 font-medium text-sm uppercase tracking-wider mb-1">Total Recaudado</span>
                    <span class="text-3xl font-bold text-green-600 tracking-tight">
                        $<?php echo e(number_format($donations->sum('amount'), 2)); ?>

                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-3 md:gap-4">
                    <div class="p-3 md:p-4 bg-gray-50 rounded-xl border border-gray-100 text-center flex flex-col justify-center">
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Transacciones</p>
                        <p class="text-xl font-bold text-gray-800"><?php echo e($donations->count()); ?></p>
                    </div>
                    <div class="p-3 md:p-4 bg-gray-50 rounded-xl border border-gray-100 text-center flex flex-col justify-center">
                        <p class="text-xs text-gray-500 uppercase font-bold mb-1">Promedio</p>
                        <p class="text-xl font-bold text-gray-800">$<?php echo e(number_format($donations->avg('amount') ?? 0, 0)); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/admin/donations/index.blade.php ENDPATH**/ ?>
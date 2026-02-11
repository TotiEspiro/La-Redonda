

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-background-light py-6 md:py-12">
    <div class="max-w-xl mx-auto px-4">
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="bg-button px-6 py-4 text-white">
                <h1 class="text-xl font-bold flex items-center">
                    <span class="mr-2"></span> Cambiar Contraseña
                </h1>
            </div>

            <div class="p-6 md:p-8">
                <?php if(session('success')): ?>
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6 text-sm">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('profile.change-password')); ?>" method="POST" class="space-y-6">
                    <?php echo csrf_field(); ?>

                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">Contraseña Actual</label>
                        <input type="password" name="current_password" id="current_password" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-button focus:border-button outline-none transition-all text-base"
                               required placeholder="••••••••">
                        <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">Nueva Contraseña</label>
                        <input type="password" name="new_password" id="new_password" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-button focus:border-button outline-none transition-all text-base"
                               required placeholder="••••••••">
                        <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirmar Nueva Contraseña</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-button focus:border-button outline-none transition-all text-base"
                               required placeholder="••••••••">
                    </div>

                    <div class="flex flex-col md:flex-row gap-3 pt-4">
                        <button type="submit" 
                                class="w-full md:w-auto bg-button text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-500 transition-all shadow-sm">
                            Actualizar Contraseña
                        </button>
                        <a href="<?php echo e(route('profile.show')); ?>" 
                           class="w-full md:w-auto bg-gray-100 text-gray-700 px-8 py-3 rounded-lg font-semibold text-center hover:bg-gray-200 transition-all no-underline">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/profile/change-password.blade.php ENDPATH**/ ?>
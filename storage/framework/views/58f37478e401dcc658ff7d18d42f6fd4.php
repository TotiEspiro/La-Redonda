

<?php $__env->startSection('content'); ?>
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 md:space-y-8 bg-white p-6 md:p-8 rounded-xl shadow-lg">
        <div>
            <h2 class="mt-2 text-center text-2xl md:text-3xl font-extrabold text-text-dark ">
                Crear Cuenta
            </h2>
            <p class="mt-2 text-center text-sm text-text-light font-medium">
                Registrate para acceder a todas las funciones
            </p>
        </div>

        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl text-sm font-bold">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form id="registerForm" class="mt-6 space-y-6" action="<?php echo e(route('register')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-xs font-black text-text-dark">Nombre Completo</label>
                    <input id="name" name="name" type="text" required 
                           class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-base transition-all bg-gray-50/50"
                           value="<?php echo e(old('name')); ?>" placeholder="Ingresá tu nombre completo">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs font-bold mt-2 "><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="email" class="block text-xs font-black text-text-dark mb-1">Email</label>
                    <input id="email" name="email" type="email" required 
                           class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-base transition-all bg-gray-50/50"
                           value="<?php echo e(old('email')); ?>" placeholder="Ingresá tu email">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs font-bold mt-2 "><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="password" class="block text-xs font-black text-text-dark mb-1">Contraseña</label>
                    <div class="relative group">
                        <input id="password" name="password" type="password" required 
                               class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-base transition-all bg-gray-50/50 pr-12" 
                               placeholder="Mínimo 6 caracteres">
                        
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-button transition-colors focus:outline-none">
                            <svg id="eyeIcon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs font-bold mt-2"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div>
                    <label for="password_confirmation" class="block text-xs font-black text-text-dark mb-1">Confirmar Contraseña</label>
                    <div class="relative group">
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="mt-1 block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-base transition-all bg-gray-50/50 pr-12" 
                               placeholder="Confirma tu contraseña">
                        
                        <button type="button" id="toggleConfirmPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-button transition-colors focus:outline-none">
                            <svg id="eyeIconConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-black text-white bg-button hover:bg-blue-900 transition-all active:scale-95 shadow-blue-100">
                    Registrarse
                </button>
            </div>

            <div class="text-center mt-6">
                <p class="text-xs text-gray-400 font-bold">
                    ¿Ya tenés cuenta? 
                    <a href="<?php echo e(route('login')); ?>" class="text-button hover:underline ml-1">Inicia sesión acá</a>
                </p>
            </div>
        </form>
    </div>
</div>


<div id="loadingScreenProgress" class="fixed inset-0 bg-white/95 backdrop-blur-sm flex flex-col items-center justify-center z-[200]" style="display: none;">
    <div class="text-center px-4 animate-fade-in">
        <img src="<?php echo e(asset('img/logo_redonda.png')); ?>" alt="La Redonda" class="w-24 md:w-28 mx-auto mb-8 h-auto shadow-2xl rounded-full">
        <div class="w-64 bg-gray-200 rounded-full h-1.5 mb-6 mx-auto overflow-hidden">
            <div id="loadingProgress" class="bg-button h-full rounded-full transition-all duration-300 shadow-sm" style="width: 0%"></div>
        </div>
        <p class="text-xs font-black text-text-dark ">Procesando <span id="loadingPercent">0</span>%</p>
    </div>
</div>

<script src="<?php echo e(asset('js/login.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/auth/register.blade.php ENDPATH**/ ?>
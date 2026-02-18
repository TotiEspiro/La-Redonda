


<?php $__env->startSection('head'); ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase tracking-tighter">Crear Cuenta</h2>
            <p class="mt-2 text-sm text-text-light font-medium">Únete a la comunidad de La Redonda</p>
        </div>

        
        <div class="grid grid-cols-2 gap-4">
            <a href="<?php echo e(route('social.redirect', 'google')); ?>" class="flex items-center justify-center gap-3 py-3 px-4 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-all shadow-sm active:scale-95">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
                <span class="text-[10px] font-black uppercase text-gray-600">Google</span>
            </a>
            <a href="<?php echo e(route('social.redirect', 'facebook')); ?>" class="flex items-center justify-center gap-3 py-3 px-4 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-all shadow-sm active:scale-95">
                <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" class="w-5 h-5" alt="Facebook">
                <span class="text-[10px] font-black uppercase text-gray-600">Facebook</span>
            </a>
        </div>

        <div class="relative flex items-center justify-center py-2">
            <div class="flex-grow border-t border-gray-100"></div>
            <span class="flex-shrink mx-4 text-gray-400 text-[9px] font-black uppercase tracking-widest">O con tu email</span>
            <div class="flex-grow border-t border-gray-100"></div>
        </div>

        <form id="registerForm" class="space-y-6" action="<?php echo e(route('register')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Nombre Completo</label>
                    <input id="name" name="name" type="text" required 
                           class="mt-1 block w-full px-5 py-4 border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50"
                           value="<?php echo e(old('name')); ?>" placeholder="Tu nombre y apellido">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-[10px] font-black mt-2 uppercase ml-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="email" class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Email</label>
                    <input id="email" name="email" type="email" required 
                           class="mt-1 block w-full px-5 py-4 border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50"
                           value="<?php echo e(old('email')); ?>" placeholder="ejemplo@correo.com">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-[10px] font-black mt-2 uppercase ml-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Contraseña</label>
                        <input id="password" name="password" type="password" required 
                               class="mt-1 block w-full px-5 py-4 border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50" 
                               placeholder="Mín. 8 caracteres">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Confirmar</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                               class="mt-1 block w-full px-5 py-4 border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50" 
                               placeholder="Repite clave">
                    </div>
                </div>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-[10px] font-black mt-1 uppercase ml-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="flex justify-center py-2">
                <div class="g-recaptcha" data-sitekey="<?php echo e(env('RECAPTCHA_SITE_KEY')); ?>"></div>
            </div>
            <?php $__errorArgs = ['captcha'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-[10px] font-black text-center uppercase"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <button type="submit" 
                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-xs font-black text-white bg-button hover:bg-blue-900 transition-all active:scale-95 shadow-blue-100 uppercase tracking-widest">
                Crear Mi Cuenta
            </button>

            <div class="text-center mt-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
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
        <p class="text-[10px] font-black text-text-dark uppercase tracking-[0.2em]">Procesando Registro <span id="loadingPercent">0</span>%</p>
    </div>
</div>

<script src="<?php echo e(asset('js/login.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/auth/register.blade.php ENDPATH**/ ?>
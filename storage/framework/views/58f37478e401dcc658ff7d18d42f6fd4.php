


<?php $__env->startSection('head'); ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase">Crear Cuenta</h2>
            <p class="mt-2 text-sm text-text-light font-medium">Unite a la comunidad de La Redonda</p>
        </div>

        
        <div class="grid gap-4">
            <a href="<?php echo e(route('social.redirect', 'google')); ?>" class="flex items-center justify-center gap-3 py-3 px-4 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-all shadow-sm active:scale-95">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" class="w-5 h-5" alt="Google">
                <span class="text-xs font-black text-text-dark uppercase tracking-widest">Unirse con Google</span>
            </a>
        </div>

        <div class="relative py-2">
            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-100"></div></div>
            <div class="relative flex justify-center text-xs uppercase"><span class="bg-white px-4 text-gray-400 font-bold tracking-widest">O con tu email</span></div>
        </div>

        
        <?php if($errors->any()): ?>
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-2xl">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <ul class="list-disc list-inside space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="text-[10px] font-black text-red-700 uppercase tracking-widest"><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <form action="<?php echo e(route('register')); ?>" method="POST" class="mt-8 space-y-6">
            <?php echo csrf_field(); ?>
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Nombre Completo</label>
                    <input name="name" type="text" required value="<?php echo e(old('name')); ?>"
                           class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50" placeholder="Ingresá tu nombre completo">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Email</label>
                    <input name="email" type="email" required value="<?php echo e(old('email')); ?>"
                           class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50" placeholder="Ingresá tu email">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Contraseña (Mín. 8 caracteres, números y mayúsculas)</label>
                    <div class="relative">
                        <input name="password" id="password" type="password" required 
                               class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50" placeholder="Ingresá tu contraseña">
                        <button type="button" onclick="togglePassword('password', 'eye-icon-reg')" class="absolute inset-y-0 right-0 pr-5 flex items-center text-gray-400 hover:text-button transition-colors">
                            <svg id="eye-icon-reg" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 ml-1">Confirmar Contraseña</label>
                    <div class="relative">
                        <input name="password_confirmation" id="password_confirmation" type="password" required 
                               class="block w-full px-5 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50" placeholder="Confirmá tu contraseña">
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-conf')" class="absolute inset-y-0 right-0 pr-5 flex items-center text-gray-400 hover:text-button transition-colors">
                            <svg id="eye-icon-conf" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            
            <div class="flex justify-center py-2">
                <div class="g-recaptcha" data-sitekey="<?php echo e(config('services.recaptcha.site_key') ?? env('RECAPTCHA_SITE_KEY')); ?>"></div>
            </div>

            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-sm font-black text-white bg-button hover:bg-blue-900 transition-all active:scale-95 shadow-blue-100">
                Registrarse
            </button>

            <div class="text-center mt-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase">
                    ¿Ya tenés cuenta? 
                    <a href="<?php echo e(route('login')); ?>" class="text-button hover:underline ml-1">Iniciá Sesión</a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />';
    } else {
        input.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />';
    }
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/auth/register.blade.php ENDPATH**/ ?>
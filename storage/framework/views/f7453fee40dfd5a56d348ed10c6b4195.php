

<?php $__env->startSection('content'); ?>
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 md:p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="text-center">
            <h2 class="text-3xl font-black text-text-dark uppercase tracking-tighter">Iniciar Sesión</h2>
            <p class="mt-2 text-sm text-text-light font-medium">Accede a tu cuenta en La Redonda</p>
        </div>

        
        <div class="grid grid-cols-2 gap-4">
            <a href="<?php echo e(route('social.redirect', 'google')); ?>" class="flex items-center justify-center gap-3 py-3 px-4 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-all shadow-sm active:scale-95">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#EA4335" d="M5.266 9.765A7.077 7.077 0 0 1 12 4.909c1.69 0 3.218.6 4.418 1.582L19.91 3C17.782 1.145 15.055 0 12 0 7.273 0 3.191 2.691 1.145 6.655l4.121 3.11z"/>
                    <path fill="#34A853" d="M16.04 18.013c-1.09.593-2.325.927-3.64.927a7.062 7.062 0 0 1-7.182-4.838l-4.12 3.103C3.144 21.327 7.25 24 12 24c3.11 0 5.891-1.018 8.055-2.782l-4.015-3.205z"/>
                    <path fill="#4285F4" d="M19.834 7.595C20.397 8.976 20.705 10.468 20.705 12c0 .41-.032.814-.09 1.209H12v4.8h8.045c.834-1.455 1.305-3.136 1.305-4.909 0-1.996-.541-3.864-1.482-5.464l-4.034 3.149z"/>
                    <path fill="#FBBC05" d="M5.266 14.235l-4.12 3.103A11.947 11.947 0 0 1 0 12c0-1.92.45-3.736 1.25-5.345l4.121 3.11a7.062 7.062 0 0 0-.105 4.47z"/>
                </svg>
                <span class="text-[10px] font-black uppercase text-gray-600">Google</span>
            </a>
            <a href="<?php echo e(route('social.redirect', 'facebook')); ?>" class="flex items-center justify-center gap-3 py-3 px-4 border border-gray-200 rounded-2xl hover:bg-gray-50 transition-all shadow-sm active:scale-95">
                <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                <span class="text-[10px] font-black uppercase text-gray-600">Facebook</span>
            </a>
        </div>

        <div class="relative flex items-center justify-center py-2">
            <div class="flex-grow border-t border-gray-100"></div>
            <span class="flex-shrink mx-4 text-gray-400 text-[9px] font-black uppercase tracking-widest">O con tu email</span>
            <div class="flex-grow border-t border-gray-100"></div>
        </div>

        
        <?php if(session('error')): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-2xl text-[10px] font-black uppercase leading-tight animate-fade-in shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <?php echo e(session('error')); ?>

                </div>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-2xl text-xs font-bold animate-fade-in uppercase">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form id="loginForm" class="space-y-6" action="<?php echo e(route('login')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="space-y-5">
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

                <div>
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label for="password" class="block text-[10px] font-black text-gray-400 uppercase">Contraseña</label>
                        <a href="<?php echo e(route('password.request')); ?>" class="text-[9px] font-black text-button hover:underline uppercase">¿La olvidaste?</a>
                    </div>
                    <div class="relative group">
                        <input id="password" name="password" type="password" required 
                               class="block w-full px-5 py-4 border border-gray-200 rounded-2xl shadow-sm focus:outline-none focus:ring-2 focus:ring-button/20 focus:border-button text-sm transition-all bg-gray-50/50 pr-12" 
                               placeholder="Ingresa tu clave">
                        
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
                        <p class="text-red-500 text-[10px] font-black mt-2 uppercase ml-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <button type="submit" 
                    class="w-full flex justify-center py-4 px-4 border border-transparent rounded-2xl shadow-lg text-xs font-black text-white bg-button hover:bg-blue-900 transition-all active:scale-95 shadow-blue-100 uppercase tracking-widest">
                Iniciar Sesión
            </button>

            <div class="text-center mt-6">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                    ¿No tenés cuenta? 
                    <a href="<?php echo e(route('register')); ?>" class="text-button hover:underline ml-1">Registrate acá</a>
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
        <p class="text-[10px] font-black text-text-dark uppercase tracking-[0.2em]">Autenticando <span id="loadingPercent">0</span>%</p>
    </div>
</div>

<script src="<?php echo e(asset('js/login.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/auth/login.blade.php ENDPATH**/ ?>
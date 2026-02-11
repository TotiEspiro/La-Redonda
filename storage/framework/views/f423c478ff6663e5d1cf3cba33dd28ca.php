

<?php $__env->startSection('content'); ?>
<div class="min-h-[calc(100vh-180px)] flex items-center justify-center bg-background-light py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 md:space-y-8 bg-white p-6 md:p-8 rounded-xl shadow-lg border border-gray-100">
        <div>
            <h2 class="mt-2 text-center text-2xl md:text-3xl font-extrabold text-text-dark">
                Recuperar Contraseña
            </h2>
            <p class="mt-2 text-center text-sm md:text-base text-text-light">
                Ingresá tu email y te enviaremos las instrucciones.
            </p>
        </div>

        <form id="forgotPasswordForm" class="mt-8 space-y-6">
            <?php echo csrf_field(); ?>
            <div>
                <label for="email" class="block text-sm font-medium text-text-dark">Email</label>
                <input id="email" name="email" type="email" required 
                       class="mt-1 block w-full px-4 py-3 md:py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-button focus:border-button text-base"
                       placeholder="Ingresá tu email">
            </div>

            <div>
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-button hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button transition-colors">
                    Enviar enlace de recuperación
                </button>
            </div>
        </form>

        <div class="text-center pt-2">
            <a href="<?php echo e(route('login')); ?>" class="inline-flex items-center text-button hover:text-blue-700 font-medium transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al inicio de sesión
            </a>
        </div>
    </div>
</div>

<div id="successModal" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6 mx-auto">
                <div>
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-nav-footer">
                        <img src="<?php echo e(asset('img/icono_activo.png')); ?>" alt="Activo" class="h-12 w-12">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-5">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">¡Enlace enviado!</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">Si existe una cuenta asociada a este correo, recibirás un enlace para restablecer tu contraseña en unos instantes.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6">
                    <button type="button" id="closeModalBtn" class="inline-flex w-full justify-center rounded-md bg-button px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Entendido
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('forgotPasswordForm');
        const modal = document.getElementById('successModal');
        const closeBtn = document.getElementById('closeModalBtn');

        form.addEventListener('submit', function(e) {
            e.preventDefault(); 
            modal.classList.remove('hidden');
        });

        closeBtn.addEventListener('click', function() {
            modal.classList.add('hidden');
            form.reset(); 
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>
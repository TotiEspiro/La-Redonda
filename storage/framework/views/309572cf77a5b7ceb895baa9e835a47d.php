

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-8 md:py-12">
    <div class="max-w-2xl mx-auto px-4">
        
        
        <nav class="flex mb-6 uppercase tracking-widest text-[10px] font-bold text-gray-400">
            <a href="<?php echo e(route('dashboard')); ?>" class="hover:text-button transition-colors">Inicio</a> 
            <span class="mx-2">/</span> 
            <a href="<?php echo e(route('profile.show')); ?>" class="hover:text-button transition-colors">Perfil</a>
            <span class="mx-2">/</span> 
            <span class="text-text-dark">Editar</span>
        </nav>

        <div class="bg-white shadow-xl rounded-[2.5rem] overflow-hidden border border-gray-100">
            <div class="bg-button px-8 py-8 text-white relative">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                </div>
                <h1 class="text-2xl font-black uppercase tracking-tighter flex items-center">
                    Configuración del Perfil
                </h1>
                <p class="text-blue-100 text-xs font-medium opacity-80 mt-1 uppercase tracking-widest">Completa tus datos para participar en grupos</p>
            </div>

            <div class="p-8 md:p-10">
                <?php if(session('success')): ?>
                    <div class="bg-green-50 border border-green-100 text-green-700 p-4 rounded-2xl mb-8 text-sm font-bold flex items-center gap-3 animate-fade-in">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?>

                <form action="<?php echo e(route('profile.update')); ?>" method="POST" class="space-y-8">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    
                    <div class="space-y-6">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50 pb-2">Información Básica</h3>
                        
                        <div>
                            <label for="name" class="block text-xs font-black text-text-dark mb-2 uppercase tracking-tight">Nombre Completo</label>
                            <input type="text" name="name" id="name" 
                                   value="<?php echo e(old('name', $user->name)); ?>"
                                   class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button focus:bg-white outline-none transition-all font-medium"
                                   placeholder="Tu nombre y apellido" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-bold mt-2 uppercase"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-black text-text-dark mb-2 uppercase tracking-tight">Correo Electrónico</label>
                            <input type="email" name="email" id="email" 
                                   value="<?php echo e(old('email', $user->email)); ?>"
                                   class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-button focus:bg-white outline-none transition-all font-medium"
                                   placeholder="ejemplo@correo.com" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-bold mt-2 uppercase"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    
                    <div class="space-y-6 pt-4">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50 pb-2">Requisitos de Inscripción</h3>
                        
                        <div class="bg-blue-50/50 p-6 rounded-[2rem] border border-blue-100 flex flex-col md:flex-row items-center gap-6">
                            <div class="flex-1">
                                <label for="age" class="block text-xs font-black text-button mb-2 uppercase tracking-tight text-center md:text-left">Tu Edad Real</label>
                                <input type="number" name="age" id="age" 
                                       value="<?php echo e(old('age', $user->age)); ?>"
                                       min="5" max="99"
                                       class="w-full px-5 py-4 bg-white border border-blue-200 rounded-2xl focus:ring-2 focus:ring-button outline-none transition-all text-center text-xl font-black text-button"
                                       placeholder="Ej: 25" required>
                                <p class="text-[9px] text-blue-400 font-bold mt-3 uppercase tracking-widest text-center md:text-left italic">
                                    * Necesaria para validar el rango de los grupos.
                                </p>
                            </div>
                            <div class="hidden md:block w-px h-20 bg-blue-100"></div>
                            <div class="flex-1 text-center md:text-left">
                                <p class="text-[11px] text-blue-700 font-medium leading-relaxed">
                                    Nuestro sistema utiliza tu edad para mostrarte solo los grupos donde puedes participar, garantizando un ambiente adecuado para cada etapa de vida.
                                </p>
                            </div>
                        </div>
                        <?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-red-500 text-[10px] font-bold mt-2 uppercase"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="space-y-4 pt-4">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] border-b border-gray-50 pb-2">Notificaciones</h3>
                        <label class="flex items-center p-5 bg-gray-50 rounded-2xl cursor-pointer hover:bg-gray-100 transition-colors group">
                            <input type="checkbox" name="notify_announcements" value="1" 
                                   <?php echo e(old('notify_announcements', $user->notify_announcements) ? 'checked' : ''); ?>

                                   class="w-6 h-6 text-button rounded-lg border-gray-200 focus:ring-button">
                            <div class="ml-4">
                                <span class="block text-xs font-black text-text-dark uppercase">Recibir Avisos Parroquiales</span>
                                <span class="text-[10px] text-gray-400 font-medium">Te notificaremos sobre nuevos anuncios y eventos.</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4 pt-8">
                        <button type="submit" 
                                class="flex-1 bg-button text-white px-8 py-5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-600 hover:translate-y-[-2px] active:scale-95 transition-all">
                            Actualizar mi Perfil
                        </button>
                        <a href="<?php echo e(route('profile.show')); ?>" 
                           class="flex-1 bg-gray-100 text-gray-400 px-8 py-5 rounded-2xl font-black text-xs uppercase tracking-widest text-center hover:bg-gray-200 transition-all">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="mt-8 text-center">
            <a href="<?php echo e(route('profile.change-password')); ?>" class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] hover:text-button transition-colors">
                ¿Deseas cambiar tu contraseña? Haz clic aquí
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes slide-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slide-up { animation: slide-up 0.4s ease-out forwards; }
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    .animate-fade-in { animation: fade-in 0.5s ease-out; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/profile/edit.blade.php ENDPATH**/ ?>
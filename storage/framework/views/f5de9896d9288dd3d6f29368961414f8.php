

<?php $__env->startSection('content'); ?>
<div class="bg-background-light min-h-screen pb-20">
    <div class="container max-w-7xl mx-auto px-4 pt-8">
        
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 mb-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-2 h-full bg-button"></div>
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="w-24 h-24 bg-blue-600 rounded-3xl flex items-center justify-center text-white text-4xl font-bold shadow-lg uppercase">
                    <?php echo e(substr($user->name, 0, 1)); ?>

                </div>
                <div class="text-center md:text-left flex-1">
                    <h1 class="text-3xl font-black text-text-dark uppercase tracking-tighter"><?php echo e($user->name); ?></h1>
                    <p class="text-text-light flex items-center justify-center md:justify-start gap-2 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <?php echo e($user->email); ?>

                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="<?php echo e(route('profile.edit')); ?>" class="px-8 py-4 bg-button text-white rounded-2xl font-black hover:bg-blue-600 transition-all text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-blue-100">
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                
                
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-8">Datos Personales</h3>
                    <div class="space-y-8">
                        
                        <div class="relative">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Edad Registrada</label>
                            <?php if($user->age): ?>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-black text-text-dark tracking-tighter"><?php echo e($user->age); ?></span>
                                    <span class="text-xs font-bold text-gray-400 uppercase">Años</span>
                                </div>
                                <p class="text-[9px] text-green-500 font-black uppercase mt-2 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    Requisito validado
                                </p>
                            <?php else: ?>
                                <div class="p-4 bg-red-50 border border-red-100 rounded-2xl">
                                    <p class="text-red-500 font-black text-[10px] uppercase mb-1">Dato Faltante</p>
                                    <p class="text-red-400 text-xs font-medium leading-tight">Debes ingresar tu edad para inscribirte a grupos.</p>
                                    <a href="<?php echo e(route('profile.edit')); ?>" class="text-red-600 font-black text-[9px] uppercase mt-3 block underline">Completar ahora</a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="pt-6 border-t border-gray-50">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-1">Miembro desde</label>
                            <p class="text-text-dark font-bold text-sm"><?php echo e($user->created_at->format('d M, Y')); ?></p>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-8 border-t border-gray-50">
                        <a href="<?php echo e(route('profile.change-password')); ?>" class="text-button font-black text-[10px] uppercase tracking-widest hover:underline flex items-center gap-3">
                            <div class="p-2 bg-blue-50 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            </div>
                            Seguridad de la cuenta
                        </a>
                    </div>
                </div>

                
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Preferencias de Alertas</h3>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between group">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-text-dark uppercase tracking-tight">Avisos Parroquiales</span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase">Push y Campana</span>
                            </div>
                            <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                'w-10 h-6 rounded-full relative transition-all shadow-inner',
                                'bg-button' => $user->notify_announcements,
                                'bg-gray-200' => !$user->notify_announcements
                            ]); ?>">
                                <div class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                    'absolute top-1 w-4 h-4 rounded-full bg-white shadow-sm transition-all',
                                    'right-1' => $user->notify_announcements,
                                    'left-1' => !$user->notify_announcements
                                ]); ?>"></div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between opacity-50">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-text-dark uppercase tracking-tight">Nuevos Materiales</span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase italic">Activado por Sistema</span>
                            </div>
                            <div class="w-10 h-6 rounded-full bg-button relative shadow-inner">
                                <div class="absolute top-1 right-1 w-4 h-4 rounded-full bg-white shadow-sm"></div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="<?php echo e(route('profile.edit')); ?>" class="block w-full mt-8 py-4 bg-gray-50 text-gray-400 text-center text-[9px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-gray-100 transition-all border border-gray-100">
                        Cambiar Ajustes
                    </a>
                </div>

                
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Mis Comunidades</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php $__empty_1 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <span class="px-3 py-1.5 bg-blue-50 text-button text-[9px] font-black rounded-xl uppercase border border-blue-100">
                                <?php echo e(str_replace('admin_', 'Gestor ', $role->display_name ?? $role->name)); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-4 w-full">
                                <p class="text-[10px] text-gray-400 font-bold uppercase italic">Aún no eres miembro de grupos</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col h-full overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                        <div>
                            <h3 class="text-xl font-black text-text-dark uppercase tracking-tighter">Historial de Actividad</h3>
                            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-1">Tus avisos personalizados</p>
                        </div>
                        <button onclick="markAllAsRead()" class="text-[10px] font-black text-button uppercase tracking-widest hover:underline px-4 py-2 bg-white rounded-xl shadow-sm border border-gray-100 transition-all">
                            Marcar Leído
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto max-h-[850px] custom-scrollbar">
                        <?php $__empty_1 = true; $__currentLoopData = $notifications ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="p-6 border-b border-gray-50 hover:bg-gray-50/50 transition-colors relative group <?php echo e(is_null($notification->read_at) ? 'bg-blue-50/20' : ''); ?>">
                            <?php if(is_null($notification->read_at)): ?>
                                <div class="absolute left-0 top-0 w-1.5 h-full bg-button"></div>
                            <?php endif; ?>
                            <div class="flex items-start gap-6">
                                <div class="p-4 rounded-2xl bg-white shadow-sm border border-gray-100 transform group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                    <?php 
                                        $nData = is_array($notification->data) ? $notification->data : json_decode($notification->data, true); 
                                    ?>
                                    <?php switch($notification->type):
                                        case ('anuncio'): ?>
                                            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.297A2.453 2.453 0 019.297 21.75H4.25A2.25 2.25 0 012 19.5V4.5A2.25 2.25 0 014.25 2.25h5.047a2.25 2.25 0 012.25 2.25v1.382z"></path></svg>
                                            <?php break; ?>
                                        <?php case ('donacion'): ?>
                                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <?php break; ?>
                                        <?php case ('material'): ?>
                                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <svg class="w-6 h-6 text-button" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                    <?php endswitch; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-black text-text-dark text-sm uppercase tracking-tight truncate pr-4"><?php echo e($nData['title'] ?? 'Aviso Parroquial'); ?></h4>
                                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest flex-shrink-0"><?php echo e(\Carbon\Carbon::parse($notification->created_at)->diffForHumans()); ?></span>
                                    </div>
                                    <p class="text-xs text-text-light leading-relaxed mb-4 font-medium"><?php echo e($nData['message'] ?? 'Tienes una nueva actualización en tu cuenta.'); ?></p>
                                    <?php if($nData['link'] ?? null): ?>
                                        <a href="<?php echo e($nData['link']); ?>" class="inline-flex items-center text-[10px] font-black text-button uppercase tracking-widest hover:translate-x-1 transition-transform gap-1">
                                            VER DETALLES
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-32 text-center">
                            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-gray-100 shadow-inner">
                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0h-1.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <p class="text-xs text-gray-400 font-black uppercase tracking-[0.3em]">Sin actividad reciente</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function markAllAsRead() {
    try {
        const response = await fetch('<?php echo e(route('notifications.read-all')); ?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        if (response.ok) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/profile/show.blade.php ENDPATH**/ ?>
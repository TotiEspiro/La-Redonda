

<?php $__env->startSection('content'); ?>
<div class="bg-gray-50 min-h-screen pb-20">
    
    
    <div class="bg-button text-white pt-10 pb-20 shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
        <div class="container max-w-7xl mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter leading-none mb-2"><?php echo e($groupName); ?></h1>
                    <div class="flex items-center gap-3">
                        <p class="text-blue-50 font-bold uppercase text-[10px] tracking-widest opacity-80">Gestión Integral del Grupo</p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button onclick="openUploadModal()" class="bg-white text-button px-6 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-2xl hover:scale-105 active:scale-95 transition-all">
                        + Subir Material
                    </button>
                    <a href="<?php echo e(route('grupos.materials', $group->category)); ?>" class="bg-blue-900 text-white border border-white/20 px-6 py-3 rounded-2xl font-black uppercase text-[10px] tracking-widest backdrop-blur-sm hover:scale-105 active:scale-95  transition-all">
                        Ver Biblioteca
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="container max-w-7xl mx-auto px-4 -mt-12 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            
            <div class="lg:col-span-8 space-y-8">
                
                
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Peticiones Pendientes</h3>
                        <span class="bg-yellow-100 text-yellow-700 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest"><?php echo e(count($requests)); ?></span>
                    </div>
                    <div class="p-4">
                        <?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between p-6 bg-gray-50 rounded-2xl mb-3 border-2 border-transparent hover:border-button/20 transition-all">
                            <div>
                                <p class="text-sm font-black text-gray-800 uppercase tracking-tight"><?php echo e($req->name); ?></p>
                                <p class="text-[11px] text-gray-400 font-medium"><?php echo e($req->email); ?></p>
                            </div>
                            <div class="flex gap-3">
                                <form action="<?php echo e(route('grupos.handle-request', $req->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?> <input type="hidden" name="action" value="approve">
                                    <button class="w-11 h-11 bg-green-500 text-white rounded-xl shadow-lg flex items-center justify-center hover:bg-green-600 transition-all active:scale-90"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg></button>
                                </form>
                                <form action="<?php echo e(route('grupos.handle-request', $req->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?> <input type="hidden" name="action" value="reject">
                                    <button class="w-11 h-11 bg-red-500 text-white rounded-xl shadow-lg flex items-center justify-center hover:bg-red-600 transition-all active:scale-90"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="py-16 text-center text-gray-300 font-black uppercase text-[10px] tracking-widest">Sin solicitudes</div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-8">Archivos Recientes</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="p-5 border border-gray-100 rounded-3xl hover:bg-blue-50/50 transition-all group flex items-center justify-between">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center border border-gray-100 shadow-sm group-hover:bg-white transition-colors">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-gray-700 truncate uppercase tracking-tight"><?php echo e($m->title); ?></p>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase"><?php echo e($m->created_at->format('d/m/Y')); ?></p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="confirmDeleteResource(<?php echo e($m->id); ?>, '<?php echo e($m->title); ?>')" class="p-2 text-red-300 hover:text-red-500 hover:bg-white rounded-lg transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            
            <div class="lg:col-span-4 space-y-8">
                
                
                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-8">Estado Operativo</h3>
                    <div class="space-y-6">
                        
                        <div class="p-5 bg-blue-50 rounded-3xl border border-blue-100 group hover:bg-blue-100 transition-colors flex items-center justify-between">
                            <div>
                                <span class="block text-[8px] font-black text-button uppercase tracking-widest mb-1">Total Miembros</span>
                                <span class="text-3xl font-black text-button leading-none"><?php echo e(count($members)); ?></span>
                            </div>
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <img src="<?php echo e(asset('img/icono_usuarios.png')); ?>" class="w-8 h-8">
                            </div>
                        </div>
                        
                        <div class="p-5 bg-blue-100 rounded-3xl border border-purple-100 group hover:bg-blue-200 transition-colors flex items-center justify-between">
                            <div>
                                <span class="block text-[8px] font-black text-blue-900 uppercase tracking-widest mb-1">Recursos Digitales</span>
                                <span class="text-3xl font-black text-blue-900 leading-none"><?php echo e(count($materials)); ?></span>
                            </div>
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <img src="<?php echo e(asset('img/icono_archivo.png')); ?>" class="w-8 h-8">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Comunidad</h3>
                    </div>
                    <div class="p-3 max-h-[400px] overflow-y-auto custom-scrollbar">
                        <?php $__currentLoopData = $members; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-2xl transition-all group">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-gray-900 text-white flex items-center justify-center font-black text-xs uppercase shadow-sm group-hover:bg-button transition-colors"><?php echo e(substr($member->name, 0, 1)); ?></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-gray-800 uppercase tracking-tight truncate"><?php echo e($member->name); ?></p>
                                    <?php if($member->isAdminOfGroup($group->category)): ?> <span class="text-[7px] font-black text-button bg-blue-50 px-1 py-0.5 rounded uppercase border border-blue-100">Coordinador</span> <?php endif; ?>
                                </div>
                            </div>
                            <?php if(Auth::id() !== $member->id): ?>
                            <button onclick="confirmRemoveMember(<?php echo e($member->id); ?>, '<?php echo e($member->name); ?>')" class="text-gray-300 hover:text-red-500 transition-colors p-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="uploadModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 hidden p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-slide-up">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-xl font-black text-gray-800 uppercase tracking-tighter">Subir Nuevo Material</h2>
            <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <form id="uploadForm" class="p-8 space-y-6">
            <?php echo csrf_field(); ?>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Título</label>
                <input type="text" name="title" required class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-medium focus:ring-2 focus:ring-button">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Tipo de Archivo</label>
                <select name="type" required class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-medium focus:ring-2 focus:ring-button appearance-none cursor-pointer">
                    <option value="pdf">Documento PDF</option>
                    <option value="image">Imagen / Foto</option>
                    <option value="doc">Documento Word / Excel</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Descripción</label>
                <textarea name="description" rows="3" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-medium focus:ring-2 focus:ring-button resize-none" placeholder="Breve detalle sobre el contenido del archivo..."></textarea>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Archivo</label>
                <input type="file" name="file" required class="w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:bg-blue-50 file:text-button file:font-black cursor-pointer">
            </div>
            <button type="submit" class="w-full py-4 bg-button text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-900 transition-all">SUBIR AHORA</button>
        </form>
    </div>
</div>


<div id="confirmActionModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-[110] hidden p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl p-8 text-center animate-slide-up">
        <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <h3 id="confirmTitle" class="text-xl font-black text-gray-800 uppercase tracking-tight mb-2">¿Confirmar Acción?</h3>
        <p id="confirmMsg" class="text-gray-500 mb-8 text-sm font-medium leading-relaxed"></p>
        <div class="flex gap-3">
            <button onclick="closeConfirmModal()" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-black uppercase text-[10px] tracking-widest">Cancelar</button>
            <button id="btnFinalConfirm" class="flex-1 py-4 bg-red-500 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-red-100 hover:bg-red-600 transition-all">Confirmar</button>
        </div>
    </div>
</div>


<div id="statusModal" class="fixed inset-0 bg-black/70 backdrop-blur-md flex items-center justify-center z-[120] hidden p-4">
    <div class="bg-white w-full max-w-sm rounded-3xl shadow-2xl p-8 text-center animate-fade-in">
        <div id="statusIcon" class="mx-auto mb-6"></div>
        <h3 id="statusTitle" class="text-xl font-black text-gray-800 uppercase tracking-tight mb-2"></h3>
        <p id="statusMsg" class="text-gray-500 mb-8 text-sm font-medium leading-relaxed"></p>
        <button onclick="closeStatus()" class="w-full py-4 bg-button text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg">Entendido</button>
    </div>
</div>

<script>
    let currentActionId = null;
    let currentActionType = null;

    function openUploadModal() { document.getElementById('uploadModal').classList.remove('hidden'); }
    function closeUploadModal() { document.getElementById('uploadModal').classList.add('hidden'); }
    function closeStatus() { document.getElementById('statusModal').classList.add('hidden'); }
    function closeConfirmModal() { document.getElementById('confirmActionModal').classList.add('hidden'); }

    function showUIStatus(title, message, success = true) {
        const modal = document.getElementById('statusModal');
        const icon = document.getElementById('statusIcon');
        document.getElementById('statusTitle').textContent = title;
        document.getElementById('statusMsg').textContent = message;
        
        icon.innerHTML = success ? 
            `<div class="w-16 h-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center mx-auto"><svg class="w-8 h-8" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>` :
            `<div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg></div>`;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // SUBIDA DE ARCHIVOS
    document.getElementById('uploadForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.textContent;
        btn.disabled = true; btn.textContent = 'SUBIENDO...';

        try {
            const res = await fetch("<?php echo e(route('grupos.upload-material', $groupRole)); ?>", {
                method: 'POST',
                body: new FormData(this),
                headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' }
            });
            const data = await res.json();
            closeUploadModal();
            if (data.success) {
                showUIStatus('¡Éxito!', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showUIStatus('Error', data.message || 'Ocurrió un problema.', false);
            }
        } catch (e) {
            showUIStatus('Error Fatal', 'No se pudo conectar con el servidor.', false);
        } finally {
            btn.disabled = false; btn.textContent = originalText;
        }
    });

    // CONFIRMACIÓN DE ELIMINACIÓN DE RECURSO
    function confirmDeleteResource(id, name) {
        currentActionId = id;
        currentActionType = 'resource';
        document.getElementById('confirmTitle').textContent = '¿Eliminar Material?';
        document.getElementById('confirmMsg').innerHTML = `Estás por borrar: <br><span class="font-bold text-red-500">${name}</span>`;
        document.getElementById('confirmActionModal').classList.remove('hidden');
        document.getElementById('confirmActionModal').classList.add('flex');
    }

    // CONFIRMACIÓN DE RETIRO DE MIEMBRO
    function confirmRemoveMember(id, name) {
        currentActionId = id;
        currentActionType = 'member';
        document.getElementById('confirmTitle').textContent = '¿Retirar Miembro?';
        document.getElementById('confirmMsg').innerHTML = `Estás por remover de la comunidad a: <br><span class="font-bold text-red-500">${name}</span>`;
        document.getElementById('confirmActionModal').classList.remove('hidden');
        document.getElementById('confirmActionModal').classList.add('flex');
    }

    document.getElementById('btnFinalConfirm')?.addEventListener('click', async function() {
        this.disabled = true;
        
        try {
            let url = '';
            let method = 'DELETE';
            
            if(currentActionType === 'resource') {
                url = `/grupos/material/${currentActionId}/delete`;
            } else if(currentActionType === 'member') {
                url = `/grupos/panel/<?php echo e($group->category); ?>/members/${currentActionId}`;
            }

            const res = await fetch(url, {
                method: method,
                headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' }
            });

            closeConfirmModal();
            location.reload();
        } catch (err) {
            showUIStatus('Error', 'No se pudo completar la acción.', false);
        } finally {
            this.disabled = false;
        }
    });
</script>

<style>
    @keyframes slide-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slide-up { animation: slide-up 0.3s ease-out forwards; }
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/grupos/dashboard-grupos.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 pb-24 overflow-x-hidden">
    
    
    <div class="bg-button text-white shadow-md md:rounded-b-[2.5rem]">
        <div class="container mx-auto px-6 py-8 md:px-12">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 text-center md:text-left">
                <div>
                    <h1 class="text-2xl md:text-4xl font-black uppercase tracking-tighter text-white">Gestión de Usuarios</h1>
                    <p class="text-blue-100 text-xs md:text-sm font-medium opacity-80 uppercase tracking-widest mt-1">Administración de accesos y roles</p>
                </div>
                <div class="bg-white/10 px-6 py-3 rounded-2xl backdrop-blur-md border border-white/10 min-w-[140px]">
                    <span class="block text-[10px] uppercase font-black text-blue-200 tracking-widest mb-1">Registrados</span>
                    <span class="text-2xl font-black" id="activeUserCount"><?php echo e($users->total()); ?></span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="container mx-auto px-4 -mt-6">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-4 md:p-6 mb-8">
            <div class="flex flex-col lg:flex-row gap-4 items-center">
                
                <div class="w-full lg:flex-1 relative">
                    <input type="text" id="userSearch" placeholder="Buscar por nombre o email..." 
                           class="w-full pl-12 pr-4 py-4 bg-gray-50 border-2 border-transparent focus:border-button/20 focus:bg-white rounded-2xl transition-all text-sm font-bold outline-none">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>                
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4">
        
        
        <div class="hidden md:block bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Usuario</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Roles y Comunidades</th>
                        <th class="px-8 py-5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="user-row hover:bg-blue-50/20 transition-colors" 
                            data-user-id="<?php echo e($user->id); ?>"
                            data-name="<?php echo e(strtolower($user->name)); ?>" 
                            data-email="<?php echo e(strtolower($user->email)); ?>"
                            data-roles="<?php echo e($user->roles->pluck('slug')->filter()->implode(',')); ?>">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-2xl bg-blue-900 text-white flex items-center justify-center font-black shadow-sm uppercase">
                                        <?php echo e(substr($user->name, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-text-dark uppercase tracking-tight"><?php echo e($user->name); ?></div>
                                        <div class="text-[11px] text-text-light font-bold"><?php echo e($user->email); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-wrap gap-1.5">
                                    <?php $__empty_1 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase border <?php echo e(str_contains($role->slug, 'admin') ? 'bg-blue-50 text-button border-blue-100' : 'bg-gray-50 text-gray-400 border-gray-100'); ?>">
                                            <?php echo e($role->display_name); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <span class="text-[10px] text-gray-300 italic font-bold uppercase">Sin roles</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <button onclick="openRoleModal(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>')" class="p-3 bg-gray-50 text-button hover:bg-button hover:text-white rounded-xl transition-all border border-gray-100 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                    <?php if(!$user->isSuperAdmin() && $user->id !== auth()->id()): ?>
                                        <button onclick="confirmDelete(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>')" class="p-3 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition-all border border-red-100 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        
        <div class="md:hidden space-y-4" id="userCardsMobile">
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="user-row bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100 flex flex-col gap-5 transition-all active:scale-[0.98]"
                     data-user-id="<?php echo e($user->id); ?>"
                     data-name="<?php echo e(strtolower($user->name)); ?>" 
                     data-email="<?php echo e(strtolower($user->email)); ?>"
                     data-roles="<?php echo e($user->roles->pluck('slug')->filter()->implode(',')); ?>">
                    
                    
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-blue-900 text-white flex items-center justify-center font-black text-xl shadow-md uppercase flex-shrink-0">
                            <?php echo e(substr($user->name, 0, 1)); ?>

                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="text-sm font-black text-gray-900 uppercase tracking-tight truncate leading-tight"><?php echo e($user->name); ?></h4>
                            <p class="text-[11px] text-gray-400 font-bold truncate mt-1"><?php echo e($user->email); ?></p>
                        </div>
                    </div>

                    
                    <div class="flex flex-wrap gap-2 py-2 border-t border-gray-50 mt-1 pt-4">
                        <?php $__empty_1 = true; $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <span class="px-3 py-1.5 rounded-lg text-[8px] font-black uppercase border shadow-sm <?php echo e(str_contains($role->slug, 'admin') ? 'bg-blue-50 text-button border-blue-100' : 'bg-gray-50 text-gray-400 border-gray-100'); ?>">
                                <?php echo e($role->display_name); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <span class="text-[9px] text-gray-300 italic font-bold uppercase tracking-widest">Sin roles asignados</span>
                        <?php endif; ?>
                    </div>

                    
                    <div class="flex gap-3 pt-2">
                        <button onclick="openRoleModal(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>')" 
                                class="flex-1 py-4 bg-button text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-blue-100 flex items-center justify-center gap-2 active:bg-blue-900 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Roles
                        </button>
                        <?php if(!$user->isSuperAdmin() && $user->id !== auth()->id()): ?>
                            <button onclick="confirmDelete(<?php echo e($user->id); ?>, '<?php echo e($user->name); ?>')" 
                                    class="w-14 py-4 bg-red-50 text-red-500 rounded-2xl border border-red-100 flex items-center justify-center transition-all active:bg-red-500 active:text-white">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="mt-12 mb-8" id="paginationContainer">
            <?php echo e($users->links()); ?>

        </div>
    </div>
</div>


<div id="roleModal" class="fixed inset-0 bg-black/80 backdrop-blur-md flex items-end sm:items-center justify-center z-50 hidden p-0 sm:p-4">
    <div class="bg-white w-full md:max-w-3xl rounded-t-[2.5rem] sm:rounded-[3rem] shadow-2xl max-h-[92vh] flex flex-col overflow-hidden animate-slide-up sm:animate-pop">
        <div class="bg-button px-8 py-8 text-white flex justify-between items-center relative">
            <div>
                <h2 id="modalUserName" class="text-xl md:text-2xl font-black uppercase tracking-tight leading-none mb-1"></h2>
                <p class="text-blue-200 text-[9px] font-black uppercase tracking-[0.2em] opacity-80">Permisos y Comunidades</p>
            </div>
            <button onclick="closeRoleModal()" class="w-12 h-12 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition-all font-black">✕</button>
        </div>
        
        <form id="activeRoleForm" method="POST" class="flex-1 overflow-y-auto p-6 md:p-10 bg-gray-50/30 custom-scrollbar">
            <?php echo csrf_field(); ?>
            
            
            <div class="bg-white p-6 rounded-[2rem] border border-gray-100 mb-8 shadow-sm">
                <h3 class="text-[10px] font-black text-gray-400 uppercase mb-6 tracking-widest flex items-center gap-3">
                    <span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span> Roles de Sistema
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php $__currentLoopData = ['admin' => ['Admin General', 'Acceso al panel'], 'usuario' => ['Usuario', 'Acceso estándar']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="group flex items-center p-4 bg-gray-50 border-2 border-transparent rounded-2xl cursor-pointer hover:bg-white hover:border-button/20 transition-all">
                        <input type="checkbox" name="basic_roles[]" value="<?php echo e($slug); ?>" class="w-6 h-6 text-button rounded-lg border-gray-200 focus:ring-0">
                        <div class="ml-4">
                            <span class="block text-xs font-black text-text-dark uppercase tracking-tight"><?php echo e($data[0]); ?></span>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest"><?php echo e($data[1]); ?></span>
                        </div>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="bg-white rounded-[2rem] border border-gray-100 overflow-hidden shadow-sm">
                <div class="px-8 py-5 bg-gray-50/50 border-b border-gray-100 flex items-center gap-3">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Comunidades Parroquiales</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50/30 text-[9px] text-gray-400 uppercase font-black tracking-widest">
                            <tr>
                                <th class="px-8 py-4">Comunidad</th>
                                <th class="px-4 py-4 text-center">Miembro</th>
                                <th class="px-4 py-4 text-center">Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            <?php $__currentLoopData = \App\Models\Group::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grupo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-5">
                                        <span class="font-black text-text-dark text-[11px] uppercase tracking-tight"><?php echo e($grupo->name); ?></span>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <input type="checkbox" name="roles[]" value="<?php echo e($grupo->category); ?>" 
                                               id="member_<?php echo e($grupo->category); ?>" 
                                               class="w-6 h-6 text-button rounded-md border-gray-200">
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <input type="checkbox" name="roles[]" value="admin_<?php echo e($grupo->category); ?>" 
                                               id="admin_<?php echo e($grupo->category); ?>" 
                                               onchange="if(this.checked) document.getElementById('member_<?php echo e($grupo->category); ?>').checked = true" 
                                               class="w-6 h-6 text-sky-400 rounded-md border-gray-200">
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <div class="p-8 bg-white border-t border-gray-100 flex flex-col sm:flex-row gap-3">
            <button type="submit" form="activeRoleForm" class="flex-1 bg-button text-white py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl shadow-blue-100 transition-all active:scale-95">
                Guardar Cambios
            </button>
            <button onclick="closeRoleModal()" class="w-full sm:w-auto px-10 py-5 text-[10px] font-black uppercase text-gray-400 hover:text-gray-600 transition-colors">Cancelar</button>
        </div>
    </div>
</div>


<div id="deleteModal" class="hidden fixed inset-0 z-[110] items-center justify-center p-6 bg-black/80 backdrop-blur-md">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm p-10 text-center animate-pop">
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-3xl bg-red-50 mb-8 shadow-inner transform rotate-3">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </div>
        <h3 class="text-2xl font-black text-text-dark mb-3 uppercase tracking-tighter">¿Eliminar Usuario?</h3>
        <p class="text-text-light mb-10 text-sm font-medium leading-relaxed">
            Vas a eliminar a <span id="deleteUserName" class="text-red-500 font-black italic"></span>. Esta acción es definitiva.
        </p>
        <div class="flex flex-col gap-3">
            <form id="deleteForm" method="POST" class="w-full">
                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                <button type="submit" class="w-full py-5 bg-red-500 text-white font-black rounded-2xl shadow-xl shadow-red-100 uppercase text-xs tracking-widest active:scale-95 transition-all">Confirmar Baja</button>
            </form>
            <button onclick="closeDeleteModal()" class="w-full py-4 text-gray-400 font-black uppercase text-[10px] tracking-widest hover:text-gray-600 transition-colors">No, mantener usuario</button>
        </div>
    </div>
</div>

<script>
// Filtro mejorado para ambas vistas (Tabla y Cards)
const searchInput = document.getElementById('userSearch');
const userRows = document.querySelectorAll('.user-row');

searchInput.addEventListener('input', function() {
    const term = this.value.toLowerCase();
    userRows.forEach(row => {
        const name = row.getAttribute('data-name');
        const email = row.getAttribute('data-email');
        const match = name.includes(term) || email.includes(term);
        
        row.style.display = match ? '' : 'none';
        
        // Animación suave al filtrar
        if(match) {
            row.classList.add('animate-fade-in');
        }
    });
});

// Lógica de Modales
function openRoleModal(userId, userName) {
    const userRow = document.querySelector(`.user-row[data-user-id="${userId}"]`);
    const form = document.getElementById('activeRoleForm');
    
    document.getElementById('modalUserName').textContent = userName;
    form.action = `/admin/users/${userId}/update-roles`;
    
    form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    
    const currentRoles = userRow.dataset.roles.split(',').filter(r => r !== '');
    
    currentRoles.forEach(slug => {
        const basicCb = form.querySelector(`input[name="basic_roles[]"][value="${slug}"]`);
        if(basicCb) basicCb.checked = true;
        
        if(slug.startsWith('admin_') && slug !== 'admin_grupo_parroquial') {
            const groupKey = slug.replace('admin_', '');
            const adminCb = document.getElementById(`admin_${groupKey}`);
            const memberCb = document.getElementById(`member_${groupKey}`);
            if(adminCb) adminCb.checked = true;
            if(memberCb) memberCb.checked = true;
        } else {
            const memberCb = document.getElementById(`member_${slug}`);
            if(memberCb) memberCb.checked = true;
        }
    });

    const modal = document.getElementById('roleModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeRoleModal() {
    const modal = document.getElementById('roleModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function confirmDelete(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    const modal = document.getElementById('deleteModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Esc para cerrar todo
document.addEventListener('keydown', e => { if(e.key === 'Escape') { closeRoleModal(); closeDeleteModal(); } });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    
    @keyframes slide-up { 
        from { opacity: 0; transform: translateY(50px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    .animate-slide-up { animation: slide-up 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    
    @keyframes pop { 
        from { opacity: 0; transform: scale(0.95); } 
        to { opacity: 1; transform: scale(1); } 
    }
    .animate-pop { animation: pop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

    @keyframes fade-in { 
        from { opacity: 0; } 
        to { opacity: 1; } 
    }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\copia_laredo\La-Redonda\resources\views/admin/users/index.blade.php ENDPATH**/ ?>
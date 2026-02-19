@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 md:bg-white pb-20">
    
    {{-- Encabezado --}}
    <div class="bg-button text-white shadow-md md:rounded-t-xl md:mt-8">
        <div class="container mx-auto px-4 py-6 md:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold uppercase tracking-tight text-white">Gestión de Usuarios</h1>
                    <p class="text-blue-100 text-xs md:text-sm font-medium opacity-80">Administración de accesos y roles de comunidad</p>
                </div>
                <div class="flex gap-2">
                    <div class="bg-white/10 px-4 py-2 rounded-2xl backdrop-blur-md border border-white/10 text-center min-w-[100px]">
                        <span class="block text-[10px] uppercase font-bold text-blue-200">Registrados</span>
                        <span class="text-xl font-black" id="activeUserCount">{{ $users->total() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Barra de Herramientas --}}
    <div class="container mx-auto px-4 mt-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-4 md:p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                {{-- Buscador en tiempo real --}}
                <div class="lg:col-span-8 relative">
                    <input type="text" id="userSearch" placeholder="Buscar por nombre o correo electrónico..." 
                           class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-button transition-all text-sm font-medium">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                {{-- Ordenamiento --}}
                <div class="lg:col-span-4">
                    <button onclick="sortByRecent()" class="w-full px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest border-2 border-gray-100 text-gray-400 hover:border-button hover:text-button transition-all flex items-center justify-center gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full"></span> Sincronizar Vista
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-2 md:px-4">
        {{-- Listado de Usuarios --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Usuario</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Roles y Grupos</th>
                            <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" id="userTableBody">
                        @foreach($users as $user)
                            <tr class="user-row hover:bg-blue-50/20 transition-colors" 
                                data-user-id="{{ $user->id }}"
                                data-name="{{ strtolower($user->name) }}" 
                                data-email="{{ strtolower($user->email) }}"
                                {{-- Pasamos SLUGS para que el JS marque los checkboxes correctamente --}}
                                data-roles="{{ $user->roles->pluck('slug')->filter()->implode(',') }}">
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-4">
                                        <div class="h-10 w-10 rounded-xl bg-sky-50 text-button flex items-center justify-center font-bold border border-sky-100">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-text-dark uppercase tracking-tight">{{ $user->name }}</div>
                                            <div class="text-[11px] text-text-light font-medium">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($user->roles as $role)
                                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase border {{ str_contains($role->slug, 'admin') ? 'bg-blue-50 text-button border-blue-100' : 'bg-gray-50 text-gray-400 border-gray-100' }}">
                                                {{ $role->display_name }}
                                            </span>
                                        @empty
                                            <span class="text-[10px] text-gray-300 italic">Sin roles asignados</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}')" 
                                                class="p-2.5 bg-gray-50 text-button hover:bg-button hover:text-white rounded-xl transition-all border border-gray-100 shadow-sm"
                                                title="Gestionar Roles">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        
                                        @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
                                            <button onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')" 
                                                    class="p-2.5 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition-all border border-red-100 shadow-sm"
                                                    title="Eliminar Usuario">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginación --}}
        <div class="mt-8 px-4" id="paginationContainer">
            {{ $users->links() }}
        </div>
    </div>
</div>

{{-- MODAL GESTIÓN DE ROLES --}}
<div id="roleModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white w-full md:max-w-3xl rounded-3xl shadow-2xl max-h-[90vh] flex flex-col overflow-hidden mx-4 animate-in fade-in zoom-in duration-200">
        <div class="bg-button px-8 py-6 text-white flex justify-between items-center">
            <div>
                <h2 id="modalUserName" class="text-xl font-black uppercase tracking-tight"></h2>
                <p class="text-blue-100 text-[10px] font-bold uppercase tracking-widest opacity-80">Permisos del Sistema y Grupos</p>
            </div>
            <button onclick="closeRoleModal()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors">✕</button>
        </div>
        
        <form id="activeRoleForm" method="POST" class="flex-1 overflow-y-auto p-8 bg-gray-50/50 custom-scrollbar">
            @csrf
            {{-- Roles Base --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 mb-8 shadow-sm">
                <h3 class="text-[10px] font-black text-gray-400 uppercase mb-4 tracking-[0.2em]">Roles de Sistema</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center p-4 border-2 border-gray-50 rounded-2xl cursor-pointer hover:border-button/20 transition-all">
                        <input type="checkbox" name="basic_roles[]" value="admin" class="w-6 h-6 text-button rounded-lg border-gray-200">
                        <div class="ml-4">
                            <span class="block text-sm font-bold text-text-dark uppercase">Admin General</span>
                            <span class="text-[9px] text-gray-400">Acceso total al panel de control</span>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-50 rounded-2xl cursor-pointer hover:border-button/20 transition-all">
                        <input type="checkbox" name="basic_roles[]" value="usuario" class="w-6 h-6 text-button rounded-lg border-gray-200">
                        <div class="ml-4">
                            <span class="block text-sm font-bold text-text-dark uppercase">Usuario</span>
                            <span class="text-[9px] text-gray-400">Acceso estándar de feligrés</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Tabla de Grupos Dinámica --}}
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Comunidades Parroquiales</h3>
                </div>
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50/50 text-[10px] text-gray-400 uppercase font-black">
                        <tr>
                            <th class="px-6 py-4">Grupo</th>
                            <th class="px-6 py-4 text-center">Miembro</th>
                            <th class="px-6 py-4 text-center">Coordinador</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        {{-- Cargamos los grupos directamente de la BD para que siempre esté actualizado --}}
                        @foreach(\App\Models\Group::orderBy('name')->get() as $grupo)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-bold text-text-dark text-xs uppercase">{{ $grupo->name }}</td>
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="roles[]" value="{{ $grupo->category }}" 
                                           id="member_{{ $grupo->category }}" 
                                           class="w-5 h-5 text-button rounded-md border-gray-200">
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="roles[]" value="admin_{{ $grupo->category }}" 
                                           id="admin_{{ $grupo->category }}" 
                                           onchange="if(this.checked) document.getElementById('member_{{ $grupo->category }}').checked = true" 
                                           class="w-5 h-5 text-blue-600 rounded-md border-gray-200">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>

        <div class="p-6 bg-white border-t border-gray-100 flex justify-end gap-3">
            <button onclick="closeRoleModal()" class="px-8 py-3 text-xs font-black uppercase text-gray-400 hover:text-gray-600">Cancelar</button>
            <button type="submit" form="activeRoleForm" class="bg-button text-white px-10 py-3 rounded-2xl font-black text-xs uppercase shadow-lg shadow-blue-100 hover:scale-[1.02] transition-transform">
                Guardar Cambios
            </button>
        </div>
    </div>
</div>

{{-- MODAL ELIMINAR --}}
<div id="deleteModal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4 bg-black/75 backdrop-blur-md">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-red-50 mb-6">
            <img src="{{ asset('img/icono_eliminar.png') }}" class="w-8 h-8">
        </div>
        <h3 class="text-xl font-black text-text-dark mb-2 uppercase tracking-tight">¿Eliminar Usuario?</h3>
        <p class="text-text-light mb-8 text-sm leading-relaxed">
            Vas a eliminar a <span id="deleteUserName" class="font-bold text-red-500"></span>. Esta acción no se puede deshacer.
        </p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-4 border-2 border-gray-100 text-gray-400 font-bold rounded-2xl uppercase text-[10px]">Cancelar</button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-4 bg-red-500 text-white font-bold rounded-2xl shadow-lg shadow-red-100 uppercase text-[10px]">Confirmar</button>
            </form>
        </div>
    </div>
</div>

<script>
// Lógica de Filtros
const searchInput = document.getElementById('userSearch');
const userRows = document.querySelectorAll('.user-row');

searchInput.addEventListener('input', function() {
    const term = this.value.toLowerCase();
    userRows.forEach(row => {
        const text = row.dataset.name + ' ' + row.dataset.email;
        row.style.display = text.includes(term) ? '' : 'none';
    });
});

// Gestión de Roles
function openRoleModal(userId, userName) {
    const userRow = document.querySelector(`[data-user-id="${userId}"]`);
    const form = document.getElementById('activeRoleForm');
    
    document.getElementById('modalUserName').textContent = userName;
    form.action = `/admin/users/${userId}/update-roles`;
    
    // Limpiar todos los checks
    form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    
    // Obtener los roles actuales del dataset (Slugs)
    const currentRoles = userRow.dataset.roles.split(',').filter(r => r !== '');
    
    currentRoles.forEach(slug => {
        // 1. Roles básicos (admin, usuario)
        const basicCb = form.querySelector(`input[name="basic_roles[]"][value="${slug}"]`);
        if(basicCb) basicCb.checked = true;
        
        // 2. Roles de Grupos
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

    document.getElementById('roleModal').classList.remove('hidden');
    document.getElementById('roleModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function confirmDelete(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

function sortByRecent() { location.reload(); }

// Cerrar con Escape
document.addEventListener('keydown', e => { if(e.key === 'Escape') { closeRoleModal(); closeDeleteModal(); } });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
@endsection
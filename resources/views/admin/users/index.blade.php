@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 md:bg-white pb-20">
    
    {{-- Encabezado --}}
    <div class="bg-button text-white shadow-md md:rounded-t-xl md:mt-8">
        <div class="container mx-auto px-4 py-6 md:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">Gestión de Usuarios</h1>
                    <p class="text-blue-100 text-xs md:text-sm font-medium opacity-80">Control de acceso y roles parroquiales</p>
                </div>
                <div class="flex gap-2">
                    <div class="bg-white/10 px-4 py-2 rounded-2xl backdrop-blur-md border border-white/10 text-center min-w-[100px]">
                        <span class="block text-[10px] uppercase font-bold text-blue-200">Mostrando</span>
                        <span class="text-xl font-black" id="activeUserCount">{{ $users->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Barra de Filtros y Herramientas --}}
    <div class="container mx-auto px-4 mt-6">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-4 md:p-6 mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                {{-- Buscador --}}
                <div class="lg:col-span-6 relative">
                    <input type="text" id="userSearch" placeholder="Buscar por nombre o email..." 
                           class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-button transition-all text-sm font-medium">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                {{-- Filtro por Rol (Select) --}}
                <div class="lg:col-span-4 relative">
                    <select id="roleFilter" onchange="filterAll()" 
                            class="w-full pl-4 pr-10 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-button transition-all text-sm font-bold text-gray-600 appearance-none cursor-pointer tracking-tight">
                        <option value="all">Todos los roles</option>
                        @foreach($allRoles as $role)
                            <option value="{{ $role->name }}">{{ $role->display_name }} ({{ $role->users_count ?? 0 }})</option>
                        @endforeach
                    </select>
                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                {{-- Ordenar por Recientes --}}
                <div class="lg:col-span-2">
                    <button onclick="sortByRecent()" class="w-full px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest border-2 border-gray-100 text-gray-400 hover:border-button hover:text-button transition-all flex items-center justify-center gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Recientes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-2 md:px-4">
        {{-- Vista Móvil (Cards) --}}
        <div class="md:hidden space-y-4" id="userContainerMobile">
            @foreach($users as $user)
                <div class="user-card bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden" 
                     data-name="{{ strtolower($user->name) }}" 
                     data-email="{{ strtolower($user->email) }}"
                     data-roles="{{ $user->roles->pluck('name')->implode(',') }}"
                     data-created="{{ $user->created_at->timestamp }}">
                    <div class="p-5">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="h-12 w-12 rounded-2xl bg-sky-100 text-button flex items-center justify-center font-black text-xl shadow-inner">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-bold text-text-dark truncate uppercase tracking-tight">{{ $user->name }}</h3>
                                <p class="text-[11px] text-text-light truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-1">
                            @foreach($user->roles as $role)
                                <span class="px-2 py-1 rounded-lg text-[9px] font-black uppercase border {{ str_contains($role->name, 'admin') ? 'bg-blue-50 text-button border-blue-100' : 'bg-gray-50 text-gray-400 border-gray-100' }}">
                                    {{ $role->display_name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="flex border-t border-gray-50 bg-gray-50/30">
                        <button  onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}')"" 
                                    class="flex-1 py-2.5 text-xs font-bold text-button hover:bg-blue-50 active:bg-blue-100 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Gestionar
                                </button>
                        @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
                        <button onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                    class="flex-1 py-2.5 text-xs font-bold text-red-500 hover:bg-red-50 active:bg-red-100 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Eliminar
                        </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Vista Desktop (Tabla) --}}
        <div class="hidden md:block bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Usuario</th>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Roles Activos</th>
                        <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="userTableBody">
                    @foreach($users as $user)
                        <tr class="user-row hover:bg-blue-50/20 transition-colors" 
                            data-name="{{ strtolower($user->name) }}" 
                            data-email="{{ strtolower($user->email) }}"
                            data-roles="{{ $user->roles->pluck('name')->implode(',') }}"
                            data-created="{{ $user->created_at->timestamp }}">
                            <td class="px-8 py-5 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-xl bg-gray-50 text-button flex items-center justify-center font-bold shadow-sm border border-gray-100">
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
                                    @foreach($user->roles as $role)
                                        <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase border {{ str_contains($role->name, 'admin') ? 'bg-blue-50 text-button border-blue-100' : 'bg-gray-50 text-gray-400 border-gray-100' }}" data-role-name="{{ $role->name }}">
                                            {{ $role->display_name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-3">
                                <button  onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}')"" 
                                    class="flex-1 py-2.5 text-xs font-bold text-button hover:bg-blue-50 active:bg-blue-100 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide rounded">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                    @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
                                    <button onclick="confirmDelete({{ $user->id }}, '{{ $user->name }}')"
                                    class="flex-1 py-2.5 text-xs font-bold text-red-500 hover:bg-red-50 active:bg-red-100 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-8 px-4" id="paginationContainer">
            {{ $users->links() }}
        </div>
    </div>
</div>

{{-- MODAL GESTIÓN DE ROLES --}}
<div id="roleModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white w-full md:max-w-3xl rounded-3xl shadow-2xl max-h-[90vh] flex flex-col overflow-hidden animate-slide-up mx-4">
        <div class="bg-button px-8 py-6 text-white flex justify-between items-center">
            <div>
                <h2 id="modalUserName" class="text-xl font-black uppercase tracking-tight"></h2>
                <p class="text-blue-100 text-[10px] font-bold uppercase tracking-widest opacity-80">Ajuste de permisos granulares</p>
            </div>
            <button onclick="closeRoleModal()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors">✕</button>
        </div>
        
        <form id="activeRoleForm" method="POST" class="flex-1 overflow-y-auto p-8 bg-gray-50/50 custom-scrollbar">
            @csrf
            {{-- Roles de Sistema --}}
            <div class="bg-white p-6 rounded-3xl border border-gray-100 mb-8 shadow-sm">
                <h3 class="text-[10px] font-black text-gray-400 uppercase mb-4 tracking-[0.2em]">Roles de Sistema</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center p-4 border-2 border-gray-50 rounded-2xl cursor-pointer hover:border-button/20 transition-all group">
                        <input type="checkbox" name="basic_roles[]" value="admin" class="w-6 h-6 text-button rounded-lg border-gray-200 focus:ring-button">
                        <div class="ml-4">
                            <span class="block text-sm font-bold text-text-dark group-hover:text-button transition-colors uppercase">Admin General</span>
                            <span class="text-[10px] text-gray-400 font-medium">Acceso total al panel</span>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-50 rounded-2xl cursor-pointer hover:border-button/20 transition-all group">
                        <input type="checkbox" name="basic_roles[]" value="user" class="w-6 h-6 text-button rounded-lg border-gray-200 focus:ring-button">
                        <div class="ml-4">
                            <span class="block text-sm font-bold text-text-dark group-hover:text-button transition-colors uppercase">Usuario</span>
                            <span class="text-[10px] text-gray-400 font-medium">Acceso estándar</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Grupos --}}
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Grupos Parroquiales</h3>
                    <span class="text-[9px] font-bold text-button bg-blue-50 px-2 py-1 rounded-md uppercase">Asignación Directa</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-[10px] text-gray-400 uppercase font-black">
                            <tr>
                                <th class="px-6 py-4">Comunidad</th>
                                <th class="px-6 py-4 text-center">Miembro</th>
                                <th class="px-6 py-4 text-center">Gestor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @php
                                $grupos = [
                                    'catequesis_ninos' => 'Catequesis Niños', 'catequesis_adolescentes' => 'Catequesis Adolescentes', 'catequesis_adultos' => 'Catequesis Adultos','juveniles' => 'Juveniles', 'acutis' => 'Acutis',
                                    'juan_pablo' => 'Juan Pablo II', 'coro' => 'Coro', 'misioneros' => 'Misioneros','san_joaquin' => 'San Joaquín','santa_ana' => 'Santa Ana', 'ardillas' => 'Ardillas', 'costureras' => 'Costureras', 'caridad' => 'Caridad', 'comedor' => 'Comedor','caritas' => 'Cáritas', 
                                ];
                            @endphp
                            @foreach($grupos as $key => $label)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4 font-bold text-text-dark text-xs uppercase">{{ $label }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="checkbox" name="member_{{ $key }}" id="member_{{ $key }}" class="w-5 h-5 text-button rounded-md border-gray-200">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="checkbox" name="admin_{{ $key }}" id="admin_{{ $key }}" 
                                               onchange="document.getElementById('member_{{ $key }}').checked = this.checked" 
                                               class="w-5 h-5 text-blue-600 rounded-md border-gray-200">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <div class="p-6 bg-white border-t border-gray-100 flex flex-col md:flex-row justify-end gap-3">
            <button onclick="closeRoleModal()" class="px-8 py-3 text-xs font-black uppercase tracking-widest text-gray-400 hover:text-gray-600 transition-colors">Cancelar</button>
            <button type="submit" form="activeRoleForm" class="bg-button text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.1em] shadow-lg shadow-blue-100 hover:bg-blue-900 transition-all">
                Guardar Cambios
            </button>
        </div>
    </div>
</div>

{{-- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN --}}
<div id="deleteModal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4 bg-black/75 backdrop-blur-md">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center animate-slide-up">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-red-50 mb-6">
            <img src="{{ asset('img/icono_eliminar.png') }}" class="w-8 h-8">
        </div>
        <h3 class="text-xl font-black text-text-dark mb-2 uppercase tracking-tight">¿Eliminar Usuario?</h3>
        <p class="text-text-light mb-8 text-sm leading-relaxed">
            Estás por eliminar a <span id="deleteUserName" class="font-bold text-red-500"></span>. Esta acción es irreversible y el usuario perderá todos sus accesos.
        </p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-4 border-2 border-gray-100 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all uppercase text-[10px] tracking-widest">Cancelar</button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-4 bg-red-500 text-white font-bold rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-red-100 uppercase text-[10px] tracking-widest">Eliminar</button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DE ÉXITO/ERROR (Status) --}}
<div id="statusModal" class="hidden fixed inset-0 z-[110] items-center justify-center p-4 bg-black/75 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center animate-fade-in">
        <div id="statusModalIcon" class="mb-6"></div>
        <h3 id="statusModalTitle" class="text-2xl font-black text-text-dark mb-2 uppercase tracking-tighter"></h3>
        <p id="statusModalMessage" class="text-text-light mb-8 text-sm leading-relaxed"></p>
        <button onclick="closeStatusModal()" class="w-full bg-button text-white py-4 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-blue-900 transition-all shadow-lg">Entendido</button>
    </div>
</div>

<script>
// --- LÓGICA DE FILTROS Y BÚSQUEDA ---
const searchInput = document.getElementById('userSearch');
const roleSelect = document.getElementById('roleFilter');
const userRows = document.querySelectorAll('.user-row');
const userCards = document.querySelectorAll('.user-card');
const activeCounter = document.getElementById('activeUserCount');
const pagination = document.getElementById('paginationContainer');

searchInput.addEventListener('input', filterAll);

function filterAll() {
    const term = searchInput.value.toLowerCase();
    const roleTerm = roleSelect.value;
    let visibleCount = 0;

    // Filtrar Tabla
    userRows.forEach(row => {
        const matches = checkMatch(row, term, roleTerm);
        row.classList.toggle('hidden', !matches);
        if(matches) visibleCount++;
    });

    // Filtrar Cards Móvil
    userCards.forEach(card => {
        const matches = checkMatch(card, term, roleTerm);
        card.classList.toggle('hidden', !matches);
    });

    // Actualizar Contador
    activeCounter.textContent = visibleCount;

    // Ocultar paginación si hay filtros activos para no crear confusión
    if(term !== '' || roleTerm !== 'all') {
        pagination.style.opacity = '0.3';
        pagination.style.pointerEvents = 'none';
    } else {
        pagination.style.opacity = '1';
        pagination.style.pointerEvents = 'auto';
    }
}

function checkMatch(element, term, roleTerm) {
    const name = element.dataset.name;
    const email = element.dataset.email;
    const roles = element.dataset.roles.split(',');
    
    const matchesSearch = name.includes(term) || email.includes(term);
    const matchesRole = roleTerm === 'all' || roles.includes(roleTerm);
    
    return matchesSearch && matchesRole;
}

function sortByRecent() {
    const container = document.getElementById('userTableBody');
    const rows = Array.from(container.querySelectorAll('.user-row'));
    rows.sort((a, b) => b.dataset.created - a.dataset.created);
    rows.forEach(row => container.appendChild(row));
    
    const mobContainer = document.getElementById('userContainerMobile');
    const cards = Array.from(mobContainer.querySelectorAll('.user-card'));
    cards.sort((a, b) => b.dataset.created - a.dataset.created);
    cards.forEach(card => mobContainer.appendChild(card));
}

// --- LÓGICA DE MODALES ---
function openRoleModal(userId, userName) {
    const userItem = document.querySelector(`[data-name="${userName.toLowerCase()}"]`);
    const form = document.getElementById('activeRoleForm');
    
    document.getElementById('modalUserName').textContent = userName;
    form.action = `/admin/users/${userId}/update-roles`;
    
    form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    
    const rolesAttr = userItem.dataset.roles;
    if(rolesAttr) {
        rolesAttr.split(',').forEach(role => {
            const basic = form.querySelector(`input[name="basic_roles[]"][value="${role}"]`);
            if(basic) basic.checked = true;
            
            if(role.startsWith('admin_') && role !== 'admin_grupo_parroquial') {
                const key = role.replace('admin_', '');
                if(document.getElementById(`admin_${key}`)) document.getElementById(`admin_${key}`).checked = true;
                if(document.getElementById(`member_${key}`)) document.getElementById(`member_${key}`).checked = true;
            } else if(document.getElementById(`member_${role}`)) {
                document.getElementById(`member_${role}`).checked = true;
            }
        });
    }

    document.getElementById('roleModal').classList.remove('hidden');
    document.getElementById('roleModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
    document.getElementById('roleModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function confirmDelete(userId, userName) {
    document.getElementById('deleteUserName').textContent = userName;
    document.getElementById('deleteForm').action = `/admin/users/${userId}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// --- MENSAJES DE ESTADO ---
@if(session('success') || session('error'))
    window.onload = function() {
        const isSuccess = {{ session('success') ? 'true' : 'false' }};
        const msg = "{{ session('success') ?? session('error') }}";
        showStatus(isSuccess ? '¡Excelente!' : 'Hubo un problema', msg, isSuccess);
    };
@endif

function showStatus(title, message, isSuccess) {
    const iconContainer = document.getElementById('statusModalIcon');
    document.getElementById('statusModalTitle').textContent = title;
    document.getElementById('statusModalMessage').textContent = message;
    
    iconContainer.innerHTML = isSuccess ? 
        `<div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-green-50"><svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg></div>` :
        `<div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-red-50"><svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg></div>`;

    document.getElementById('statusModal').classList.remove('hidden');
    document.getElementById('statusModal').classList.add('flex');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('statusModal').classList.remove('flex');
}

document.addEventListener('keydown', e => { if(e.key === 'Escape') { closeRoleModal(); closeDeleteModal(); closeStatusModal(); } });
</script>

<style>
    @keyframes slide-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slide-up { animation: slide_up 0.3s ease-out forwards; }
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
@endsection
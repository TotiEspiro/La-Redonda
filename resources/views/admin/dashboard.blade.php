@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-black text-text-dark">Adminstración del Sistema</h2>
    <p class="text-text-light text-sm font-medium">Control general y actividad reciente de La Redonda</p>
</div>

{{-- Tarjetas de Estadísticas --}}
<div class="mb-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <a href="{{ route('admin.users') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="p-3 bg-nav-footer rounded-lg flex-shrink-0">
                    <img src="{{ asset('img/icono_usuarios.png') }}" alt="Usuarios" class="h-8 md:h-10 w-auto">
                </div>
                <div class="ml-4 min-w-0">
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wide truncate">Usuarios</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.intentions') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="p-3 bg-nav-footer rounded-lg flex-shrink-0">
                    <img src="{{ asset('img/icono_intenciones.png') }}" alt="Intenciones" class="h-8 md:h-10 w-auto">
                </div>
                <div class="ml-4 min-w-0">
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wide truncate">Intenciones</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800">{{ $stats['total_intentions'] }}</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.donations') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="p-3 bg-nav-footer rounded-lg flex-shrink-0">
                    <img src="{{ asset('img/icono_donaciones_admin.png') }}" alt="Donaciones" class="h-8 md:h-10 w-auto">
                </div>
                <div class="ml-4 min-w-0">
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wide truncate">Donaciones</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800">{{ $stats['total_donations'] }}</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.announcements.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all transform hover:-translate-y-1">
            <div class="flex items-center">
                <div class="p-3 bg-nav-footer rounded-lg flex-shrink-0">
                    <img src="{{ asset('img/icono_avisos.png') }}" alt="Avisos" class="h-8 md:h-10 w-auto">
                </div>
                <div class="ml-4 min-w-0">
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wide truncate">Avisos</p>
                    <p class="text-xl md:text-2xl font-bold text-gray-800">{{ $stats['total_announcements'] ?? 0 }}</p>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    {{-- ÚLTIMOS AVISOS (Administrable) --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <h3 class="text-lg font-black text-text-dark uppercase tracking-tight">Últimos Avisos</h3>
            <a href="{{ route('admin.announcements.create') }}" class="text-[10px] font-black text-button uppercase tracking-widest hover:underline">+ Nuevo Anuncio</a>
        </div>
        <div class="p-4 flex-1">
            @forelse($stats['recent_announcements'] ?? [] as $announcement)
            <div class="p-4 rounded-2xl hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 group">
                <div class="flex justify-between items-start">
                    <div class="flex-1 min-w-0 pr-4">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full {{ $announcement->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            <p class="font-bold text-text-dark truncate uppercase text-sm tracking-tight">{{ $announcement->title }}</p>
                        </div>
                        <p class="text-xs text-text-light line-clamp-1 mb-3">{{ $announcement->short_description }}</p>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="text-[10px] font-black text-button uppercase tracking-widest hover:underline">Editar</a>
                            <button onclick="confirmDeleteAnnouncement({{ $announcement->id }}, '{{ $announcement->title }}')" class="text-[10px] font-black text-red-400 uppercase tracking-widest hover:text-red-600 transition-colors">Eliminar</button>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-gray-400 bg-white px-2 py-1 rounded-lg border border-gray-100 shadow-sm">{{ $announcement->created_at->format('d/m') }}</span>
                </div>
            </div>
            @empty
            <div class="h-full flex items-center justify-center py-12 text-gray-400 italic text-sm">No hay avisos registrados</div>
            @endforelse
        </div>
    </div>

    {{-- ÚLTIMAS INTENCIONES --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <h3 class="text-lg font-black text-text-dark uppercase tracking-tight">Ultimas Intenciones</h3>
            <a href="{{ route('admin.intentions') }}" class="text-[10px] font-black text-button uppercase tracking-widest hover:underline">Ver todas</a>
        </div>
        <div class="p-4 flex-1">
            @forelse($stats['recent_intentions'] as $intention)
            <div class="p-4 border-b border-gray-50 last:border-0 hover:bg-blue-50/30 transition-colors rounded-2xl">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-bold text-text-dark text-xs uppercase tracking-tight truncate">{{ $intention->name }}</p>
                            <span class="px-2 py-0.5 bg-sky-50 text-button text-[9px] font-black rounded-md border border-sky-100 uppercase">{{ $intention->formatted_type }}</span>
                        </div>
                        <p class="text-xs text-text-light italic line-clamp-2">"{{ $intention->message }}"</p>
                    </div>
                    <span class="text-[10px] font-bold text-gray-400 mt-1">{{ $intention->created_at->format('H:i') }}</span>
                </div>
            </div>
            @empty
            <div class="h-full flex items-center justify-center py-12 text-gray-400 italic text-sm">Sin actividad hoy</div>
            @endforelse
        </div>
    </div>
</div>

{{-- ÚLTIMOS USUARIOS (Administrable) --}}
<div class="mt-8 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <h3 class="text-lg font-black text-text-dark uppercase tracking-tight">Usuarios Recientes</h3>
        <a href="{{ route('admin.users') }}" class="text-[10px] font-black text-button uppercase tracking-widest hover:underline">Gestionar Todos</a>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($stats['recent_users'] as $user)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 hover:border-button/30 transition-all"
                 data-user-id="{{ $user->id }}"
                 data-user-name="{{ $user->name }}"
                 data-user-roles="{{ $user->roles->pluck('name')->implode(',') }}">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-button text-white flex items-center justify-center font-black text-sm shadow-sm flex-shrink-0">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-text-dark text-sm uppercase tracking-tight truncate">{{ $user->name }}</p>
                        <p class="text-[10px] text-text-light truncate">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex gap-2 ml-4">
                    <button  onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}')"" 
                        class="flex-1 py-2.5 text-xs font-bold text-button hover:bg-blue-50 active:bg-blue-100 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide rounded">
                        <svg class="w-6 h-" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    @if(!$user->isSuperAdmin() && $user->id !== auth()->id())
                    <button onclick="confirmDeleteUser({{ $user->id }}, '{{ $user->name }}')"
                        class="flex-1 py-2.5 text-xs font-bold text-red-500 hover:bg-red-50 active:bg-red-100 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-2 py-8 text-center text-gray-400 italic text-sm">No hay registros nuevos</div>
            @endforelse
        </div>
    </div>
</div>

{{-- MODALES --}}

{{-- Modal Gestión de Roles (Versión 15 Grupos) --}}
<div id="roleModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="bg-white w-full md:max-w-3xl rounded-3xl shadow-2xl max-h-[90vh] flex flex-col overflow-hidden mx-4">
        <div class="bg-button px-8 py-6 text-white flex justify-between items-center">
            <div>
                <h2 id="modalUserName" class="text-xl font-black uppercase tracking-tight"></h2>
                <p class="text-blue-100 text-[10px] font-bold uppercase tracking-widest opacity-80">Permisos de Sistema y Grupos</p>
            </div>
            <button onclick="closeRoleModal()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-white/10 transition-colors">✕</button>
        </div>
        
        <form id="activeRoleForm" method="POST" class="flex-1 overflow-y-auto p-8 bg-gray-50/50 custom-scrollbar">
            @csrf
            <div class="bg-white p-6 rounded-3xl border border-gray-100 mb-8 shadow-sm">
                <h3 class="text-[10px] font-black text-gray-400 uppercase mb-4 tracking-[0.2em]">Acceso al Sistema</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center p-4 border-2 border-gray-50 rounded-2xl cursor-pointer hover:border-button/20 transition-all group">
                        <input type="checkbox" name="basic_roles[]" value="admin" class="w-6 h-6 text-button rounded-lg border-gray-200">
                        <div class="ml-4">
                            <span class="block text-sm font-bold text-text-dark uppercase">Admin General</span>
                        </div>
                    </label>
                    <label class="flex items-center p-4 border-2 border-gray-50 rounded-2xl cursor-pointer hover:border-button/20 transition-all group">
                        <input type="checkbox" name="basic_roles[]" value="user" class="w-6 h-6 text-button rounded-lg border-gray-200">
                        <div class="ml-4">
                            <span class="block text-sm font-bold text-text-dark uppercase">Usuario</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-gray-50 text-[10px] text-gray-400 uppercase font-black">
                            <tr>
                                <th class="px-6 py-4 tracking-widest">Grupo Parroquial</th>
                                <th class="px-6 py-4 text-center tracking-widest">Miembro</th>
                                <th class="px-6 py-4 text-center tracking-widest">Gestor</th>
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
                                <tr class="hover:bg-gray-50/50">
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

        <div class="p-6 bg-white border-t border-gray-100 flex justify-end gap-3">
            <button onclick="closeRoleModal()" class="px-6 py-3 text-xs font-black uppercase text-gray-400">Cancelar</button>
            <button type="submit" form="activeRoleForm" class="bg-button text-white px-8 py-3 rounded-2xl font-black text-xs uppercase shadow-lg shadow-blue-100">Guardar Cambios</button>
        </div>
    </div>
</div>

{{-- Modal Eliminar (Genérico) --}}
<div id="deleteModal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4 bg-black/75 backdrop-blur-md">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-red-50 mb-6">
            <img src="{{ asset('img/icono_eliminar.png') }}" class="w-8 h-8">
        </div>
        <h3 id="deleteTitle" class="text-xl font-black text-text-dark mb-2 uppercase tracking-tight"></h3>
        <p id="deleteMessage" class="text-text-light mb-8 text-sm leading-relaxed"></p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-4 border-2 border-gray-100 text-gray-400 font-bold rounded-2xl uppercase text-[10px]">Cancelar</button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-4 bg-red-500 text-white font-bold rounded-2xl shadow-lg shadow-red-100 uppercase text-[10px]">Eliminar</button>
            </form>
        </div>
    </div>
</div>

{{-- Modal Estado --}}
<div id="statusModal" class="hidden fixed inset-0 z-[110] items-center justify-center p-4 bg-black/75 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center animate-fade-in">
        <div id="statusModalIcon" class="mb-6"></div>
        <h3 id="statusModalTitle" class="text-2xl font-black text-text-dark mb-2 uppercase tracking-tighter"></h3>
        <p id="statusModalMessage" class="text-text-light mb-8 text-sm leading-relaxed"></p>
        <button onclick="closeStatusModal()" class="w-full bg-button text-white py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-blue-100">Entendido</button>
    </div>
</div>

<script>
// Roles Modal
function openRoleModal(userId, userName) {
    const userItem = document.querySelector(`[data-user-id="${userId}"]`);
    const form = document.getElementById('activeRoleForm');
    document.getElementById('modalUserName').textContent = userName;
    form.action = `/admin/users/${userId}/update-roles`;
    form.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
    
    const roles = userItem.dataset.userRoles.split(',');
    roles.forEach(role => {
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
    document.getElementById('roleModal').classList.remove('hidden');
    document.getElementById('roleModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeRoleModal() {
    document.getElementById('roleModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Eliminación de Anuncios
function confirmDeleteAnnouncement(id, title) {
    document.getElementById('deleteTitle').textContent = '¿Eliminar Aviso?';
    document.getElementById('deleteMessage').innerHTML = `Vas a eliminar permanentemente el aviso: <br><span class="font-bold text-red-500">${title}</span>`;
    document.getElementById('deleteForm').action = `/admin/announcements/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

// Eliminación de Usuarios
function confirmDeleteUser(id, name) {
    document.getElementById('deleteTitle').textContent = '¿Eliminar Usuario?';
    document.getElementById('deleteMessage').innerHTML = `Vas a eliminar permanentemente a: <br><span class="font-bold text-red-500">${name}</span>`;
    document.getElementById('deleteForm').action = `/admin/users/${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Mensajes de Flash
@if(session('success') || session('error'))
    window.onload = function() {
        const isSuccess = {{ session('success') ? 'true' : 'false' }};
        showStatus(isSuccess ? '¡Completado!' : 'Error', "{{ session('success') ?? session('error') }}", isSuccess);
    };
@endif

function showStatus(title, message, isSuccess) {
    const icon = document.getElementById('statusModalIcon');
    document.getElementById('statusModalTitle').textContent = title;
    document.getElementById('statusModalMessage').textContent = message;
    icon.innerHTML = isSuccess ? 
        `<div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-green-50"><svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>` :
        `<div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-red-50"><svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg></div>`;
    document.getElementById('statusModal').classList.remove('hidden');
    document.getElementById('statusModal').classList.add('flex');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

document.addEventListener('keydown', e => { if(e.key === 'Escape') { closeRoleModal(); closeDeleteModal(); closeStatusModal(); } });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>
@endsection
@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 md:bg-white md:min-h-0 pb-10 md:pb-0">
    
    <div class="bg-button text-white shadow-md md:shadow-none md:rounded-t-xl">
        <div class="container mx-auto px-4 py-4 md:px-6 md:py-5 md:mt-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold">Gestión de Usuarios</h1>
                    <p class="text-blue-100 text-xs md:text-sm hidden md:block">Administra los roles y permisos del sistema</p>
                </div>
                <div class="md:hidden bg-white/20 px-3 py-1 rounded-full text-xs font-bold backdrop-blur-sm">
                    {{ $users->total() }} Usuarios
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-2 md:px-0 md:border md:border-t-0 md:border-gray-100 md:rounded-b-xl md:shadow-lg md:mb-8 bg-transparent md:bg-white">
        <div class="md:hidden space-y-3 pb-4">
            @foreach($users as $user)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mx-1"
                     data-user-id="{{ $user->id }}" 
                     data-user-name="{{ $user->name }}"
                     data-mobile-card="true"> <div class="p-4">
                        <div class="flex items-start gap-3">
                            <div class="h-10 w-10 rounded-full bg-button text-white flex items-center justify-center text-sm font-bold shadow-sm flex-shrink-0 mt-0.5">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            
                            <div class="flex-1 min-w-0"> <h3 class="text-sm font-bold text-gray-900 leading-tight">{{ $user->name }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5 break-words">{{ $user->email }}</p>
                                
                                <div class="flex flex-wrap gap-1.5 mt-3">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border
                                            {{ $role->name === 'superadmin' ? 'bg-red-50 text-red-700 border-red-100' : 
                                               ($role->name === 'admin' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : 
                                               ($role->name === 'user' ? 'bg-blue-50 text-blue-700 border-blue-100' : 'bg-green-50 text-green-700 border-green-100')) }}"
                                            data-role-name="{{ $role->name }}">
                                            {{ $role->display_name }}
                                        </span>
                                    @endforeach
                                    @if($user->roles->isEmpty())
                                        <span class="text-[10px] text-gray-400 italic py-0.5">Sin roles</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex divide-x divide-gray-100 border-t border-gray-100 bg-gray-50">
                        @if(!$user->isSuperAdmin() || auth()->user()->isSuperAdmin())
                            <button onclick="openRoleModal({{ $user->id }})" 
                                    class="flex-1 py-3 text-xs font-bold text-button hover:bg-blue-50 active:bg-blue-100 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Roles
                            </button>
                        @endif
                        
                        @if($user->id !== auth()->id() && !$user->isSuperAdmin())
                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="flex-1 flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="flex-1 py-3 text-xs font-bold text-red-500 hover:bg-red-50 active:bg-red-100 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide"
                                        onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    Borrar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="hidden md:block overflow-x-auto p-6 pt-2">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-9 w-9 rounded-full bg-button text-white flex items-center justify-center text-sm font-bold mr-3">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border
                                            {{ $role->name === 'superadmin' ? 'bg-red-50 text-red-800 border-red-100' : 
                                               ($role->name === 'admin' ? 'bg-yellow-50 text-yellow-800 border-yellow-100' : 
                                               ($role->name === 'user' ? 'bg-blue-50 text-blue-800 border-blue-100' : 'bg-green-50 text-green-800 border-green-100')) }}"
                                            data-role-name="{{ $role->name }}">
                                            {{ $role->display_name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    @if(!$user->isSuperAdmin() || auth()->user()->isSuperAdmin())
                                        <button onclick="openRoleModal({{ $user->id }})" 
                                                class="text-button hover:bg-blue-50 p-2 rounded-lg transition-colors" title="Gestionar Roles">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        </button>
                                    @endif
                                    
                                    @if($user->id !== auth()->id() && !$user->isSuperAdmin())
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-gray-400 hover:text-red-600 hover:bg-red-50 p-2 rounded-lg transition-colors"
                                                    title="Eliminar Usuario"
                                                    onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-4 py-4 md:px-6 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>

<div id="roleModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-end md:items-center justify-center z-50 hidden p-0 md:p-4 backdrop-blur-sm transition-opacity">
    <div class="bg-white w-full md:w-full md:max-w-2xl rounded-t-2xl md:rounded-xl shadow-2xl max-h-[85vh] flex flex-col overflow-hidden">
        
        <div class="bg-button px-6 py-4 text-white flex-shrink-0 flex justify-between items-center">
            <div class="overflow-hidden mr-4">
                <h2 class="text-xl font-bold leading-tight">Gestionar Roles</h2>
                <p id="modalUserInfo" class="text-blue-100 text-sm truncate leading-tight mt-1"></p>
            </div>
            <button onclick="closeRoleModal()" class="text-white hover:text-blue-200 p-2 rounded-full bg-white/10 hover:bg-white/20 transition-colors flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="roleForm" method="POST" class="flex-1 overflow-y-auto p-4 md:p-6 bg-gray-50">
            @csrf
            <input type="hidden" id="modalUserId" name="user_id">
            
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-4">
                <h3 class="font-bold text-gray-900 mb-3 text-sm uppercase tracking-wide flex items-center">
                    <span class="w-2 h-2 bg-button rounded-full mr-2"></span>
                    Roles Principales
                </h3>
                <div class="space-y-2">
                    @foreach($allRoles->whereIn('name', ['admin', 'admin_grupo_parroquial']) as $role)
                        <label class="flex items-start p-3 rounded-lg border border-gray-200 hover:border-button cursor-pointer transition-all bg-white hover:bg-blue-50/50">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                   class="mt-1 w-5 h-5 rounded border-gray-300 text-button focus:ring-button modal-role transition-colors"
                                   data-role-type="principal">
                            <div class="ml-3">
                                <span class="font-bold text-gray-900 block text-sm">{{ $role->display_name }}</span>
                                <p class="text-xs text-gray-500 mt-0.5 leading-snug">{{ $role->description }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wide flex items-center">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        Grupos Parroquiales
                    </h3>
                    <button type="button" onclick="toggleAllGroups()" 
                            class="text-[10px] font-bold bg-green-100 text-green-700 px-3 py-1.5 rounded-full hover:bg-green-200 transition-colors uppercase tracking-wide">
                        Todos
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    @foreach($allRoles->whereIn('name', ['catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor']) as $role)
                        <label class="flex items-center p-2.5 rounded-lg border border-gray-200 hover:border-green-500 bg-white hover:bg-green-50/30 cursor-pointer transition-all">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                   class="w-4 h-4 rounded border-gray-300 text-green-600 focus:ring-green-500 mr-3 modal-role group-role transition-colors"
                                   data-role-type="grupo">
                            <span class="text-sm font-medium text-gray-700">{{ $role->display_name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </form>

        <div class="p-4 bg-white border-t border-gray-100 flex flex-col-reverse md:flex-row justify-end gap-3 flex-shrink-0">
            <button type="button" onclick="closeRoleModal()" 
                    class="w-full md:w-auto bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-lg font-bold hover:bg-gray-50 active:bg-gray-100 transition-colors text-sm">
                Cancelar
            </button>
            <button type="button" onclick="document.getElementById('roleForm').dispatchEvent(new Event('submit'))"
                    class="w-full md:w-auto bg-button text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-600 active:bg-blue-700 transition-colors shadow-sm text-sm">
                Guardar Cambios
            </button>
        </div>
    </div>
</div>

<script>
function openRoleModal(userId) {
    const modal = document.getElementById('roleModal');
    const userInfo = document.getElementById('modalUserInfo');
    const form = document.getElementById('roleForm');
    
    let userElement = document.querySelector(`.md\\:hidden [data-user-id="${userId}"]`); 
    if (!userElement) {
        userElement = document.querySelector(`tr[data-user-id="${userId}"]`); 
    }
    
    if (!userElement) return;
    
    const userName = userElement.getAttribute('data-user-name');
    
    userInfo.textContent = `${userName}`;
    document.getElementById('modalUserId').value = userId;
    form.action = `/admin/users/${userId}/update-roles`;
    
    const checkboxes = document.querySelectorAll('#roleForm input[type="checkbox"]');
    checkboxes.forEach(checkbox => checkbox.checked = false);
    
    const roleSpans = userElement.querySelectorAll('span[data-role-name]');
    roleSpans.forEach(span => {
        const roleName = span.getAttribute('data-role-name');
        const checkbox = document.querySelector(`#roleForm input[value="${roleName}"]`);
        if (checkbox) checkbox.checked = true;
    });
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRoleModal() {
    const modal = document.getElementById('roleModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function toggleAllGroups() {
    const groupCheckboxes = document.querySelectorAll('#roleForm .group-role');
    const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
    groupCheckboxes.forEach(checkbox => checkbox.checked = !allChecked);
}

document.getElementById('roleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const tempForm = document.createElement('form');
    tempForm.method = 'POST';
    tempForm.action = this.action;
    tempForm.style.display = 'none';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('input[name="_token"]').value;
    tempForm.appendChild(csrfInput);
    
    const selectedRoles = document.querySelectorAll('#roleForm input[name="roles[]"]:checked');
    selectedRoles.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'roles[]';
        input.value = checkbox.value;
        tempForm.appendChild(input);
    });
    
    document.body.appendChild(tempForm);
    tempForm.submit();
});

document.addEventListener('keydown', e => { if(e.key === 'Escape') closeRoleModal(); });
document.getElementById('roleModal').addEventListener('click', e => { if(e.target === document.getElementById('roleModal')) closeRoleModal(); });
</script>
@endsection
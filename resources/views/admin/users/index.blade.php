@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-button px-6 py-4 text-white">
            <h1 class="text-2xl font-bold">Gestión de Usuarios</h1>
            <p class="text-blue-100">Administra los roles de los usuarios</p>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-3 px-4 text-left">Nombre</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Roles Actuales</th>
                            <th class="py-3 px-4 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="border-b border-gray-200 hover:bg-gray-50" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}" data-user-email="{{ $user->email }}">
                                <td class="py-3 px-4">{{ $user->name }}</td>
                                <td class="py-3 px-4">{{ $user->email }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold 
                                                {{ $role->name === 'superadmin' ? 'bg-red-100 text-red-800' : 
                                                   ($role->name === 'admin' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($role->name === 'admin_grupo_parroquial' ? 'bg-purple-100 text-purple-800' :
                                                   ($role->name === 'user' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'))) }}"
                                                data-role-name="{{ $role->name }}">
                                                {{ $role->display_name }}
                                            </span>
                                        @endforeach
                                        @if($user->roles->isEmpty())
                                            <span class="text-xs text-gray-500">Sin roles asignados</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex space-x-2">
                                        @if(!$user->isSuperAdmin() || auth()->user()->isSuperAdmin())
                                            <button onclick="openRoleModal({{ $user->id }})" 
                                                    class="bg-button text-white px-3 py-1 rounded text-sm hover:bg-blue-500 transition-colors">
                                                Gestionar Roles
                                            </button>
                                        @endif
                                        
                                        @if($user->id !== auth()->id() && !$user->isSuperAdmin())
                                            <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition-colors"
                                                        onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-500 px-2 py-1">No eliminable</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal para gestionar roles -->
<div id="roleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="bg-button px-6 py-4 text-white rounded-t-lg">
            <h2 class="text-xl font-bold">Gestionar Roles</h2>
            <p id="modalUserInfo" class="text-blue-100 text-sm"></p>
        </div>
        
        <form id="roleForm" method="POST" class="p-6">
            @csrf
            <input type="hidden" id="modalUserId" name="user_id">
            
            <!-- Roles Principales -->
            <div class="mb-6">
                <h3 class="font-semibold text-text-dark mb-3 text-lg border-b pb-2">Roles Principales</h3>
                <div class="space-y-2">
                    @foreach($allRoles->whereIn('name', ['admin', 'admin_grupo_parroquial']) as $role)
                        <label class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors cursor-pointer">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                   class="rounded border-gray-300 text-button focus:ring-button mr-3 transform scale-125 modal-role"
                                   data-role-type="principal">
                            <div>
                                <span class="font-medium text-text-dark">{{ $role->display_name }}</span>
                                <p class="text-sm text-text-light mt-1">{{ $role->description }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Grupos Parroquiales -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-text-dark text-lg">Grupos Parroquiales</h3>
                    <button type="button" onclick="toggleAllGroups()" 
                            class="text-sm bg-green-100 text-green-700 px-3 py-1 rounded hover:bg-green-200 transition-colors">
                        Seleccionar Todos
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 max-h-60 overflow-y-auto p-2 border border-gray-200 rounded-lg">
                    @foreach($allRoles->whereIn('name', ['catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor']) as $role)
                        <label class="flex items-center p-2 rounded border border-green-200 hover:bg-green-50 transition-colors cursor-pointer">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                   class="rounded border-gray-300 text-green-600 focus:ring-green-500 mr-2 modal-role group-role"
                                   data-role-type="grupo">
                            <span class="text-sm text-green-700">{{ $role->display_name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeRoleModal()" 
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-400 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                        class="bg-button text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-500 transition-colors">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRoleModal(userId) {
    console.log('Abriendo modal para usuario:', userId);
    
    const modal = document.getElementById('roleModal');
    const userInfo = document.getElementById('modalUserInfo');
    const form = document.getElementById('roleForm');
    
    // Buscar la fila del usuario
    const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
    if (!userRow) {
        console.error('No se encontró la fila del usuario');
        return;
    }
    
    const userName = userRow.getAttribute('data-user-name');
    const userEmail = userRow.getAttribute('data-user-email');
    
    // Actualizar información del usuario en el modal
    userInfo.textContent = `Editando roles de: ${userName} (${userEmail})`;
    document.getElementById('modalUserId').value = userId;
    
    // Actualizar action del formulario
    form.action = `/admin/users/${userId}/update-roles`;
    
    // Cargar roles actuales del usuario
    loadUserRoles(userId, userRow);
    
    // Mostrar modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRoleModal() {
    const modal = document.getElementById('roleModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function loadUserRoles(userId, userRow) {
    console.log('Cargando roles para usuario:', userId);
    
    // Resetear todos los checkboxes
    const checkboxes = document.querySelectorAll('#roleForm input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Obtener roles actuales del usuario desde los spans de roles
    const roleSpans = userRow.querySelectorAll('td:nth-child(3) span[data-role-name]');
    console.log('Roles encontrados:', roleSpans.length);
    
    roleSpans.forEach(span => {
        const roleName = span.getAttribute('data-role-name');
        console.log('Buscando checkbox para rol:', roleName);
        
        const checkbox = document.querySelector(`#roleForm input[value="${roleName}"]`);
        if (checkbox) {
            checkbox.checked = true;
            console.log('Checkbox marcado:', roleName);
        } else {
            console.log('Checkbox no encontrado para:', roleName);
        }
    });
}

function toggleAllGroups() {
    const groupCheckboxes = document.querySelectorAll('#roleForm .group-role');
    const allChecked = Array.from(groupCheckboxes).every(cb => cb.checked);
    
    groupCheckboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
}

// Configurar el formulario del modal - VERSIÓN SIMPLIFICADA
document.getElementById('roleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userId = document.getElementById('modalUserId').value;
    const formData = new FormData(this);
    
    console.log('Enviando formulario para usuario:', userId);
    
    // Mostrar loading
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Guardando...';
    submitBtn.disabled = true;
    
    // Enviar el formulario de forma tradicional (no AJAX)
    // Crear un formulario temporal y enviarlo
    const tempForm = document.createElement('form');
    tempForm.method = 'POST';
    tempForm.action = this.action;
    tempForm.style.display = 'none';
    
    // Agregar CSRF token
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = document.querySelector('input[name="_token"]').value;
    tempForm.appendChild(csrfInput);
    
    // Agregar los roles seleccionados
    const selectedRoles = document.querySelectorAll('#roleForm input[name="roles[]"]:checked');
    selectedRoles.forEach(checkbox => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'roles[]';
        input.value = checkbox.value;
        tempForm.appendChild(input);
    });
    
    // Agregar al body y enviar
    document.body.appendChild(tempForm);
    tempForm.submit();
});

// Versión alternativa si quieres usar AJAX pero necesitas ajustar el controlador
/*
document.getElementById('roleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const userId = document.getElementById('modalUserId').value;
    const formData = new FormData(this);
    
    console.log('Enviando formulario para usuario:', userId);
    
    // Mostrar loading
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Guardando...';
    submitBtn.disabled = true;
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => {
        if (response.redirected) {
            // Si hay redirección, seguirla
            window.location.href = response.url;
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.success) {
            console.log('Roles actualizados correctamente');
            closeRoleModal();
            location.reload();
        } else if (data && data.error) {
            alert('Error: ' + data.error);
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        } else {
            // Si no hay JSON, asumimos que fue exitoso y recargamos
            closeRoleModal();
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar roles. Por favor, recarga la página e intenta nuevamente.');
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});
*/

// Cerrar modal al hacer clic fuera
document.getElementById('roleModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRoleModal();
    }
});

// Cerrar modal con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeRoleModal();
    }
});
</script>
@endsection
<!-- Sección de Roles -->
<div class="mb-8">
    <h2 class="text-xl font-semibold text-text-dark mb-4">Rol y Permisos</h2>
    <div class="bg-gray-50 p-6 rounded-lg">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-text-dark">{{ $user->role_name }}</h3>
                <p class="text-text-light text-sm">Tu rol actual en el sistema</p>
            </div>
            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold 
                {{ $user->isSuperAdmin() ? 'bg-red-100 text-red-800' : 
                   ($user->isAdmin() ? 'bg-yellow-100 text-yellow-800' : 
                   ($user->role === 'admin_grupo_parroquial' ? 'bg-purple-100 text-purple-800' :
                   ($user->role === 'user' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'))) }}">
                {{ $user->role }}
            </span>
        </div>
        
        <!-- Información de permisos según el rol -->
        <div class="mt-4">
            <h4 class="font-semibold text-text-dark mb-2">Permisos disponibles:</h4>
            <ul class="text-sm text-text-light space-y-1">
                @if($user->isSuperAdmin())
                    <li>✓ Acceso completo al sistema</li>
                    <li>✓ Gestión de todos los usuarios</li>
                    <li>✓ Administración de todos los grupos</li>
                @elseif($user->isAdmin())
                    <li>✓ Gestión de usuarios (excepto SuperAdmin)</li>
                    <li>✓ Administración de grupos</li>
                    <li>✓ Acceso al panel de administración</li>
                @elseif($user->isAdminGrupoParroquial())
                    <li>✓ Gestión de grupos parroquiales</li>
                    <li>✓ Administración de subgrupos</li>
                @else
                    <li>✓ Acceso a funcionalidades específicas del grupo</li>
                    <li>✓ Gestión de contenido relacionado con su área</li>
                @endif
            </ul>
        </div>
    </div>
</div>
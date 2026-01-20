@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-0 md:px-4 py-6">
    <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-100">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-800">Detalles del Usuario</h2>
        </div>
        
        <div class="p-6">
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-text-dark mb-4">Rol y Permisos</h2>
                <div class="bg-blue-50 p-5 rounded-lg border border-blue-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-text-dark flex items-center gap-2">
                                {{ $user->name }}
                                <span class="text-sm font-normal text-gray-500">({{ $user->email }})</span>
                            </h3>
                            <p class="text-text-light text-sm mt-1">Tu rol actual en el sistema</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide w-fit
                            {{ $user->isSuperAdmin() ? 'bg-red-100 text-red-800' : 
                               ($user->isAdmin() ? 'bg-yellow-100 text-yellow-800' : 
                               ($user->role === 'admin_grupo_parroquial' ? 'bg-purple-100 text-purple-800' :
                               'bg-green-100 text-green-800')) }}">
                            {{ $user->role }}
                        </span>
                    </div>
                    
                    <div class="mt-4 border-t border-blue-200 pt-4">
                        <h4 class="font-semibold text-blue-800 mb-3 text-sm uppercase tracking-wide">Permisos disponibles:</h4>
                        <ul class="text-sm text-gray-700 space-y-2">
                            @if($user->isSuperAdmin())
                                <li class="flex items-center"><span class="mr-2 text-green-500">✓</span> Acceso completo al sistema</li>
                                <li class="flex items-center"><span class="mr-2 text-green-500">✓</span> Gestión de todos los usuarios</li>
                                <li class="flex items-center"><span class="mr-2 text-green-500">✓</span> Administración de todos los grupos</li>
                            @elseif($user->isAdmin())
                                <li class="flex items-center"><span class="mr-2 text-green-500">✓</span> Gestión de usuarios</li>
                                <li class="flex items-center"><span class="mr-2 text-green-500">✓</span> Administración de grupos</li>
                                <li class="flex items-center"><span class="mr-2 text-green-500">✓</span> Acceso al panel</li>
                            @else
                                <li class="flex items-center"><span class="mr-2 text-green-500">✓</span> Acceso a funcionalidades del grupo</li>
                                <li class="flex items-center"><span class="mr-2 text-green-500">✓</span> Gestión de contenido asignado</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('admin.users') }}" class="w-full sm:w-auto bg-gray-100 text-gray-700 px-6 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center">
                    Volver a Usuarios
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
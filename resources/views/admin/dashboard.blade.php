@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h2 class="text-2xl font-bold text-gray-800">Administración del Sistema</h2>
    <p class="text-gray-600">Resumen general del sistema</p>
</div>

<!-- Accesos Rápidos -->
<div class="mb-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.users') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-nav-footer rounded-lg">
                    <img src="../img/icono_usuarios.png" alt="Usuarios" class="h-10">
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Gestión de Usuarios</p>
                    <p class="text-lg font-bold text-gray-800">{{ $stats['total_users'] }} usuarios</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.intentions') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-nav-footer rounded-lg">
                    <img src="../img/icono_intenciones.png" alt="Intenciones" class="h-10">
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Gestión de Intenciones</p>
                    <p class="text-lg font-bold text-gray-800">{{ $stats['total_intentions'] }} intenciones</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.donations') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-nav-footer rounded-lg">
                    <img src="../img/icono_donaciones_admin.png" alt="Donaciones" class="h-10">
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Gestión de Donaciones</p>
                    <p class="text-lg font-bold text-gray-800">{{ $stats['total_donations'] }} donaciones</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.announcements.index') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-nav-footer rounded-lg">
                    <img src="../img/icono_avisos.png" alt="Avisos" class="h-10">
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-600">Gestión de Avisos</p>
                    <p class="text-lg font-bold text-gray-800">{{ $stats['total_announcements'] ?? 0 }} avisos</p>
                </div>
            </div>
        </a>
    </div>
</div>



<!-- Últimas Intenciones y Anuncios -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Últimas Intenciones</h3>
            <a href="{{ route('admin.intentions') }}" class="text-button hover:text-blue-500 text-sm">Ver todas</a>
        </div>
        <div class="p-6">
            @forelse($stats['recent_intentions'] as $intention)
            <div class="border-b border-gray-200 py-3 last:border-b-0">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $intention->name }}</p>
                        <p class="text-sm text-gray-600">{{ $intention->formatted_type }}</p>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $intention->message }}</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $intention->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">No hay intenciones recientes</p>
            @endforelse
        </div>
    </div>

    <!-- Últimos Anuncios -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Últimos Avisos</h3>
            <a href="{{ route('admin.announcements.index') }}" class="text-button hover:text-blue-500 text-sm">Ver todos</a>
        </div>
        <div class="p-6">
            @forelse($stats['recent_announcements'] ?? [] as $announcement)
            <div class="border-b border-gray-200 py-3 last:border-b-0">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="font-semibold text-gray-800">{{ $announcement->title }}</p>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs 
                                @if($announcement->is_active) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $announcement->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $announcement->short_description }}</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $announcement->created_at->format('d/m/Y') }}</span>
                </div>
            </div>
            @empty
            <div class="text-center py-4">
                <p class="text-gray-500 mb-3">No hay avisos creados</p>
                <a href="{{ route('admin.announcements.create') }}" class="bg-button text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-500 transition-colors">
                    Crear Primer Aviso
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Últimos Usuarios -->
<div class="mt-8 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Últimos Usuarios Registrados</h3>
        <a href="{{ route('admin.users') }}" class="text-button hover:text-blue-500 text-sm">Ver todos</a>
    </div>
    <div class="p-6">
        @forelse($stats['recent_users'] as $user)
        <div class="border-b border-gray-200 py-3 last:border-b-0">
            <div class="flex justify-between items-center">
                <div>
                    <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                    <p class="text-sm text-gray-600">{{ $user->email }}</p>
                    <span class="inline-block mt-1 px-2 py-1 text-xs rounded-full 
                        {{ $user->isAdmin() ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $user->isAdmin() ? 'Administrador' : 'Usuario' }}
                    </span>
                </div>
                <span class="text-xs text-gray-500">{{ $user->created_at->format('d/m/Y') }}</span>
            </div>
        </div>
        @empty
        <p class="text-gray-500 text-center py-4">No hay usuarios registrados</p>
        @endforelse
    </div>
</div>
@endsection
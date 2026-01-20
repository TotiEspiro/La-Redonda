@extends('layouts.admin')

@section('content')
<div class="mb-6 md:mb-8">
    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">Administración del Sistema</h2>
    <p class="text-gray-600 text-sm md:text-base">Resumen general del sistema</p>
</div>

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

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-full">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Últimas Intenciones</h3>
            <a href="{{ route('admin.intentions') }}" class="text-button hover:text-blue-600 text-sm font-medium hover:underline flex-shrink-0 ml-2">Ver todas</a>
        </div>
        <div class="p-4 md:p-6 flex-1">
            @forelse($stats['recent_intentions'] as $intention)
            <div class="border-b border-gray-100 py-3 last:border-b-0 last:pb-0 first:pt-0">
                <div class="flex justify-between items-start gap-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <p class="font-semibold text-gray-800 text-sm md:text-base truncate max-w-full">{{ $intention->name }}</p>
                            <span class="inline-block bg-gray-100 text-gray-600 text-[10px] md:text-xs px-2 py-0.5 rounded-full whitespace-nowrap">{{ $intention->formatted_type }}</span>
                        </div>
                        <p class="text-xs md:text-sm text-gray-500 line-clamp-2 break-words">{{ $intention->message }}</p>
                    </div>
                    <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0 mt-1">{{ $intention->created_at->format('d/m') }}</span>
                </div>
            </div>
            @empty
            <div class="h-full flex items-center justify-center py-8 text-gray-400">
                <p>No hay intenciones recientes</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col h-full">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Últimos Avisos</h3>
            <a href="{{ route('admin.announcements.index') }}" class="text-button hover:text-blue-600 text-sm font-medium hover:underline flex-shrink-0 ml-2">Ver todos</a>
        </div>
        <div class="p-4 md:p-6 flex-1">
            @forelse($stats['recent_announcements'] ?? [] as $announcement)
            <div class="border-b border-gray-100 py-3 last:border-b-0 last:pb-0 first:pt-0">
                <div class="flex justify-between items-start gap-3">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 mb-1">
                            <p class="font-semibold text-gray-800 text-sm md:text-base truncate">{{ $announcement->title }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] md:text-xs font-medium self-start sm:self-auto
                                @if($announcement->is_active) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $announcement->is_active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <p class="text-xs md:text-sm text-gray-500 line-clamp-1">{{ $announcement->short_description }}</p>
                    </div>
                    <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0 mt-1">{{ $announcement->created_at->format('d/m') }}</span>
                </div>
            </div>
            @empty
            <div class="h-full flex flex-col items-center justify-center py-8 text-gray-400 gap-2">
                <p>No hay avisos creados</p>
                <a href="{{ route('admin.announcements.create') }}" class="text-button text-sm hover:underline">Crear uno nuevo</a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-800">Últimos Usuarios</h3>
        <a href="{{ route('admin.users') }}" class="text-button hover:text-blue-600 text-sm font-medium hover:underline flex-shrink-0 ml-2">Ver todos</a>
    </div>
    <div class="p-4 md:p-6">
        @forelse($stats['recent_users'] as $user)
        <div class="border-b border-gray-100 py-3 last:border-b-0">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0 w-full">
                    <div class="w-8 h-8 rounded-full bg-button text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-semibold text-gray-800 text-sm md:text-base truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 truncate block">{{ $user->email }}</p>
                    </div>
                </div>
                
                <div class="flex flex-row sm:flex-col md:flex-row items-center gap-2 pl-11 sm:pl-0 self-start sm:self-auto">
                    @php
                        $groupRoleName = null;
                        $parishRoles = [
                            'catequesis' => 'Catequesis',
                            'juveniles' => 'Juveniles',
                            'acutis' => 'Acutis',
                            'juan_pablo' => 'Juan Pablo',
                            'coro' => 'Coro',
                            'san_joaquin' => 'San Joaquín',
                            'santa_ana' => 'Santa Ana',
                            'ardillas' => 'Ardillas',
                            'costureras' => 'Costureras',
                            'misioneros' => 'Misioneros',
                            'caridad_comedor' => 'Caridad',
                            'admin_grupo_parroquial' => 'Admin Grupos'
                        ];

                        foreach ($parishRoles as $key => $label) {
                            if ($user->hasRole($key)) {
                                $groupRoleName = $label;
                                break;
                            }
                        }
                    @endphp

                    @if($groupRoleName)
                        <span class="inline-block px-2 py-1 text-xs rounded border bg-blue-50 text-blue-700 border-blue-200 font-semibold whitespace-nowrap">
                            {{ $groupRoleName }}
                        </span>
                    @endif

                    <span class="inline-block px-2 py-1 text-xs rounded border whitespace-nowrap
                        {{ $user->isAdmin() ? 'bg-yellow-50 text-yellow-700 border-yellow-200' : 'bg-gray-50 text-gray-600 border-gray-200' }}">
                        {{ $user->isAdmin() ? 'Admin' : 'Usuario' }}
                    </span>
                </div>
            </div>
        </div>
        @empty
        <p class="text-gray-500 text-center py-4">No hay usuarios registrados</p>
        @endforelse
    </div>
</div>
@endsection
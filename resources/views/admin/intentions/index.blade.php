@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-button px-6 py-4 text-white">
            <h1 class="text-2xl font-bold">Gestión de Intenciones</h1>
            <p class="text-blue-100">Administra las intenciones de oración</p>
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

            <div class="flex justify-between items-center mb-6">
                <a href="{{ route('admin.intentions.print') }}" target="_blank" 
                   class="bg-button text-white px-4 py-2 rounded text-sm hover:bg-blue-500 transition-colors">
                    Imprimir Intenciones
                </a>
            </div>

            <div class="overflow-x-auto">
                <!-- Versión Desktop -->
                <table class="min-w-full bg-white hidden md:table">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-3 px-4 text-left">Nombre</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Tipo</th>
                            <th class="py-3 px-4 text-left">Mensaje</th>
                            <th class="py-3 px-4 text-left">Fecha</th>
                            <th class="py-3 px-4 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($intentions as $intention)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <div class="font-medium text-gray-900">{{ $intention->name }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-gray-900">{{ $intention->email }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <span @class([
                                    'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                                    'bg-green-100 text-green-800' => $intention->type == 'salud',
                                    'bg-gray-100 text-gray-800' => $intention->type == 'difuntos',
                                    'bg-yellow-100 text-yellow-800' => $intention->type == 'accion-gracias',
                                    'bg-blue-100 text-blue-800' => !in_array($intention->type, ['salud', 'difuntos', 'accion-gracias'])
                                ])>
                                    {{ $intention->formatted_type }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-gray-900 max-w-xs truncate" title="{{ $intention->message }}">
                                    {{ $intention->message }}
                                </div>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-500">
                                {{ $intention->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-4">
                                <form action="{{ route('admin.intentions.delete', $intention->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta intención?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors text-sm">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 px-4 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    No hay intenciones registradas
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Versión Móvil -->
                <div class="md:hidden space-y-4">
                    @forelse($intentions as $intention)
                    <div class="bg-white rounded-lg border border-gray-200 p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $intention->name }}</h3>
                                <p class="text-sm text-gray-600 truncate">{{ $intention->email }}</p>
                            </div>
                            <span @class([
                                'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium shrink-0 ml-2',
                                'bg-green-100 text-green-800' => $intention->type == 'salud',
                                'bg-gray-100 text-gray-800' => $intention->type == 'difuntos',
                                'bg-yellow-100 text-yellow-800' => $intention->type == 'accion-gracias',
                                'bg-blue-100 text-blue-800' => !in_array($intention->type, ['salud', 'difuntos', 'accion-gracias'])
                            ])>
                                {{ $intention->formatted_type }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <p class="text-sm text-gray-700 line-clamp-2">{{ $intention->message }}</p>
                        </div>

                        <div class="flex justify-between items-center text-sm text-gray-500 mb-3">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ $intention->created_at->format('d/m/Y') }}
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $intention->created_at->format('H:i') }}
                            </span>
                        </div>

                        <div class="flex justify-end space-x-3 pt-3 border-t border-gray-200">
                            <form action="{{ route('admin.intentions.delete', $intention->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar esta intención?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center text-red-600 hover:text-red-900 text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white rounded-lg border border-gray-200 p-8 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No hay intenciones registradas</h3>
                        <p class="text-gray-500">Las intenciones aparecerán aquí cuando sean enviadas.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            @if($intentions->hasPages())
            <div class="mt-6">
                {{ $intentions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
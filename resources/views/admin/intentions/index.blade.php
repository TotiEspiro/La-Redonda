@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-0 md:px-4 py-6 md:py-8">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
        <div class="bg-button px-6 py-5 text-white">
            <h1 class="text-2xl font-bold">Gestión de Intenciones</h1>
            <p class="text-blue-100 text-sm mt-1">Administra las intenciones de oración</p>
        </div>

        <div class="p-4 md:p-6">
            <div class="flex justify-end mb-6">
                <a href="{{ route('admin.intentions.print') }}" target="_blank" 
                   class="w-full md:w-auto text-center bg-white text-button border border-button px-4 py-2 rounded-lg text-sm font-semibold hover:bg-button hover:text-white transition-colors shadow-sm flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Imprimir Lista
                </a>
            </div>

            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre / Email</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mensaje</th>
                            <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($intentions as $intention)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-4">
                                <div class="text-sm font-medium text-gray-900">{{ $intention->name }}</div>
                                <div class="text-xs text-gray-500">{{ $intention->email }}</div>
                            </td>
                            <td class="py-4 px-4">
                                <span @class([
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    'bg-green-100 text-green-800' => $intention->type == 'salud',
                                    'bg-gray-100 text-gray-800' => $intention->type == 'difuntos',
                                    'bg-yellow-100 text-yellow-800' => $intention->type == 'accion-gracias',
                                    'bg-blue-100 text-blue-800' => !in_array($intention->type, ['salud', 'difuntos', 'accion-gracias'])
                                ])>
                                    {{ $intention->formatted_type }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <div class="text-sm text-gray-500 max-w-xs truncate" title="{{ $intention->message }}">
                                    {{ $intention->message }}
                                </div>
                            </td>
                            <td class="py-4 px-4 text-sm text-gray-500 whitespace-nowrap">
                                {{ $intention->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                <form action="{{ route('admin.intentions.delete', $intention->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar intención?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">No hay intenciones registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="md:hidden space-y-4">
                @forelse($intentions as $intention)
                <div class="bg-white rounded-xl border border-gray-200 p-4 shadow-sm">
                    <div class="flex justify-between items-start mb-3 gap-3">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">{{ $intention->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $intention->email }}</p>
                        </div>
                        <span @class([
                            'inline-flex items-center px-2 py-1 rounded-full text-xs font-bold shrink-0',
                            'bg-green-100 text-green-800' => $intention->type == 'salud',
                            'bg-gray-100 text-gray-800' => $intention->type == 'difuntos',
                            'bg-yellow-100 text-yellow-800' => $intention->type == 'accion-gracias',
                            'bg-blue-100 text-blue-800' => !in_array($intention->type, ['salud', 'difuntos', 'accion-gracias'])
                        ])>
                            {{ $intention->formatted_type }}
                        </span>
                    </div>

                    <div class="bg-gray-50 rounded p-3 mb-3">
                        <p class="text-sm text-gray-700 italic">"{{ $intention->message }}"</p>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-100 pt-3">
                        <span class="text-xs text-gray-400 font-medium">
                            {{ $intention->created_at->format('d/m/Y H:i') }}
                        </span>
                        <form action="{{ route('admin.intentions.delete', $intention->id) }}" method="POST" onsubmit="return confirm('¿Eliminar?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 text-sm font-medium flex items-center hover:bg-red-50 px-2 py-1 rounded transition-colors">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                    No hay intenciones.
                </div>
                @endforelse
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
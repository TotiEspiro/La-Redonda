@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestión de Donaciones</h2>
            <p class="text-gray-600">Administra las donaciones recibidas</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" 
           class="bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-600 transition-colors ml-4">
            ← Volver al Panel
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Lista de Donaciones</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Donante</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frecuencia</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarjeta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($donations as $donation)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $donation->card_holder }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $donation->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-green-600">${{ number_format($donation->amount, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                            {{ $donation->formatted_frequency }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        **** **** **** {{ $donation->card_last_four }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $donation->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                            Completada
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No hay donaciones registradas
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($donations->hasPages())
    <div class="px-6 py-4 border-t">
        {{ $donations->links() }}
    </div>
    @endif
</div>

<!-- Estadísticas de donaciones -->
<div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-lg shadow p-6">
        <h4 class="text-lg font-semibold text-gray-800 mb-4">Resumen de Donaciones</h4>
        <div class="space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Recaudado:</span>
                <span class="font-semibold text-green-600">
                    ${{ number_format($donations->sum('amount'), 2) }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Total Donaciones:</span>
                <span class="font-semibold">{{ $donations->count() }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Donación Promedio:</span>
                <span class="font-semibold">
                    ${{ number_format($donations->avg('amount') ?? 0, 2) }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
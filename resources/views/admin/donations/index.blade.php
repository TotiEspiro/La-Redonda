@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-button px-6 py-4 text-white">
            <h1 class="text-2xl font-bold">Gestión de Donaciones</h1>
            <p class="text-blue-100">Administra las donaciones recibidas</p>
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
                            <th class="py-3 px-4 text-left">Donante</th>
                            <th class="py-3 px-4 text-left">Email</th>
                            <th class="py-3 px-4 text-left">Monto</th>
                            <th class="py-3 px-4 text-left">Frecuencia</th>
                            <th class="py-3 px-4 text-left">Tarjeta</th>
                            <th class="py-3 px-4 text-left">Fecha</th>
                            <th class="py-3 px-4 text-left">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($donations as $donation)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-4">
                                <div class="font-medium text-gray-900">{{ $donation->card_holder }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="text-gray-900">{{ $donation->email }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-semibold text-green-600">${{ number_format($donation->amount, 2) }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                    {{ $donation->formatted_frequency }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-500">
                                **** **** **** {{ $donation->card_last_four }}
                            </td>
                            <td class="py-3 px-4 text-sm text-gray-500">
                                {{ $donation->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                    Completada
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-4 px-4 text-center text-gray-500">
                                No hay donaciones registradas
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($donations->hasPages())
            <div class="mt-6">
                {{ $donations->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas de donaciones -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-button px-6 py-4 text-white">
                <h3 class="text-lg font-bold">Resumen de Donaciones</h3>
            </div>
            <div class="p-6 space-y-3">
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
</div>
@endsection
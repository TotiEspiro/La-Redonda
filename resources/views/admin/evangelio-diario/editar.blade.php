@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Editar Evangelio del Día</h2>
            <p class="text-gray-600">Modificar el evangelio que se muestra en la página principal</p>
            <p class="text-sm text-gray-500 mt-1">Fecha: {{ now()->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" 
           class="bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-600 transition-colors ml-4">
            ← Volver al Panel
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-800">Contenido del Evangelio</h3>
    </div>
    
    <form action="{{ route('admin.evangelio-diario.actualizar') }}" method="POST" class="p-6">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 gap-6">
            <div>
                <label for="contenido" class="block text-sm font-medium text-gray-700">Texto del Evangelio *</label>
                <textarea id="contenido" name="contenido" required rows="6"
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-button focus:border-button"
                          placeholder="Ingresa el texto del evangelio...">{{ old('contenido', $evangelio->contenido) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">Máximo 1000 caracteres. Incluye las comillas si es necesario.</p>
                @error('contenido')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="referencia" class="block text-sm font-medium text-gray-700">Referencia Bíblica *</label>
                <input type="text" id="referencia" name="referencia" value="{{ old('referencia', $evangelio->referencia) }}" required 
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:ring-button focus:border-button"
                       placeholder="Ej: Juan 3:16-18">
                <p class="mt-1 text-sm text-gray-500">Máximo 100 caracteres.</p>
                @error('referencia')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Vista Previa -->
            <div class="bg-gray-50 p-6 rounded-lg border">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Vista Previa:</h4>
                <div class="bg-gospel-bg p-6 rounded">
                    <h3 class="text-2xl font-semibold text-center text-gray-800 mb-4">Evangelio del Día</h3>
                    <p class="italic text-lg mb-4 leading-loose text-gray-700" id="vistaPreviaContenido">
                        {{ $evangelio->contenido ?: 'El texto del evangelio aparecerá aquí...' }}
                    </p>
                    <p class="text-gray-600 text-right font-semibold" id="vistaPreviaReferencia">
                        {{ $evangelio->referencia ?: 'Referencia bíblica...' }}
                    </p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end gap-4">
            <a href="{{ route('admin.dashboard') }}" 
               class="bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-button text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-500 transition-colors">
                Actualizar Evangelio
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const contenidoTextarea = document.getElementById('contenido');
    const referenciaInput = document.getElementById('referencia');
    const vistaPreviaContenido = document.getElementById('vistaPreviaContenido');
    const vistaPreviaReferencia = document.getElementById('vistaPreviaReferencia');

    // Actualizar vista previa en tiempo real
    contenidoTextarea.addEventListener('input', function() {
        vistaPreviaContenido.textContent = this.value || 'El texto del evangelio aparecerá aquí...';
    });

    referenciaInput.addEventListener('input', function() {
        vistaPreviaReferencia.textContent = this.value || 'Referencia bíblica...';
    });
});
</script>
@endsection
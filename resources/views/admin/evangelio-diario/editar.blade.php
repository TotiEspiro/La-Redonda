@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-button px-6 py-4 text-white">
            <h1 class="text-2xl font-bold">Editar Evangelio del Día</h1>
            <p class="text-blue-100">Modificar el evangelio que se muestra en la página principal</p>
            <p class="text-blue-100 text-sm mt-1">Fecha: {{ now()->format('d/m/Y') }}</p>
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

            <form action="{{ route('admin.evangelio-diario.actualizar') }}" method="POST">
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
                       class="bg-gray-500 text-white px-4 py-2 rounded text-sm hover:bg-gray-600 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-button text-white px-4 py-2 rounded text-sm hover:bg-blue-500 transition-colors">
                        Actualizar Evangelio
                    </button>
                </div>
            </form>
        </div>
    </div>
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
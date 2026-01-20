@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-0 md:px-4 py-6 md:py-8">
    <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
        <div class="bg-button px-6 py-5 text-white">
            <h1 class="text-2xl font-bold">Evangelio del Día</h1>
            <div class="flex flex-col md:flex-row md:justify-between md:items-center mt-2 gap-2">
                <p class="text-blue-100 text-sm">Modificar contenido de la página principal</p>
                <span class="inline-block bg-blue-600 bg-opacity-30 px-3 py-1 rounded-full text-xs font-mono text-white">
                    {{ now()->format('d/m/Y') }}
                </span>
            </div>
        </div>

        <div class="p-4 md:p-6">
            <form action="{{ route('admin.evangelio-diario.actualizar') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="contenido" class="block text-sm font-bold text-gray-700 mb-2">Texto del Evangelio <span class="text-red-500">*</span></label>
                        <textarea id="contenido" name="contenido" required rows="8"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-button focus:border-button text-base transition-all"
                                  placeholder="Ingresa el texto del evangelio...">{{ old('contenido', $evangelio->contenido) }}</textarea>
                        <p class="mt-2 text-xs text-gray-500 flex justify-end">Máximo 1000 caracteres.</p>
                    </div>
                    
                    <div>
                        <label for="referencia" class="block text-sm font-bold text-gray-700 mb-2">Referencia Bíblica <span class="text-red-500">*</span></label>
                        <input type="text" id="referencia" name="referencia" value="{{ old('referencia', $evangelio->referencia) }}" required 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-button focus:border-button text-base transition-all"
                               placeholder="Ej: Juan 3:16-18">
                    </div>

                    <div class="mt-4">
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-3">Vista Previa en Sitio</h4>
                        <div class="bg-gospel-bg p-6 md:p-8 rounded-xl border border-blue-100 shadow-inner">
                            <h3 class="text-xl md:text-2xl font-semibold text-center text-gray-800 mb-6">Evangelio del Día</h3>
                            <p class="italic text-base md:text-lg mb-4 leading-relaxed text-gray-700 text-justify" id="vistaPreviaContenido">
                                {{ $evangelio->contenido ?: 'El texto del evangelio aparecerá aquí...' }}
                            </p>
                            <p class="text-gray-600 text-right font-bold text-sm md:text-base" id="vistaPreviaReferencia">
                                {{ $evangelio->referencia ?: 'Referencia bíblica...' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col-reverse md:flex-row justify-end gap-3">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="w-full md:w-auto bg-white text-gray-700 border border-gray-300 px-6 py-3 rounded-lg font-semibold hover:bg-gray-50 text-center transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="w-full md:w-auto bg-button text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-500 text-center transition-colors shadow-md">
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

    contenidoTextarea.addEventListener('input', function() {
        vistaPreviaContenido.textContent = this.value || 'El texto del evangelio aparecerá aquí...';
    });

    referenciaInput.addEventListener('input', function() {
        vistaPreviaReferencia.textContent = this.value || 'Referencia bíblica...';
    });
});
</script>
@endsection
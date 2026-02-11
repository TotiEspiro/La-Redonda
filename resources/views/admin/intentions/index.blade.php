@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-0 md:px-4 py-6 md:py-8">
    <div class="bg-white shadow-lg rounded-3xl overflow-hidden border border-gray-100">
        
        {{-- Encabezado --}}
        <div class="bg-button px-8 py-6 text-white">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black">Gestión de Intenciones</h1>
                    <p class="text-blue-100 text-xs md:text-sm font-medium opacity-80">Administra las peticiones de oración de la comunidad</p>
                </div>
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    {{-- Botón Eliminar Todo --}}
                    @if($intentions->count() > 0)
                    <button onclick="confirmDeleteAll()" 
                            class="flex-1 md:flex-none px-6 py-2.5 bg-red-500/20 hover:bg-red-500 text-white border border-white/20 rounded-xl text-xs font-black uppercase tracking-widest transition-all backdrop-blur-sm flex items-center justify-center gap-2">
                        <img src="{{ asset('img/icono_eliminar.png') }}" class="w-4 h-4 brightness-0 invert">
                        Vaciar Lista
                    </button>
                    @endif

                    <a href="{{ route('admin.intentions.print') }}" target="_blank" 
                       class="flex-1 md:flex-none px-6 py-2.5 bg-white text-button rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-50 transition-all shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Imprimir Lista
                    </a>
                </div>
            </div>
        </div>

        <div class="p-4 md:p-8">
            {{-- Vista Desktop (Tabla) --}}
            <div class="hidden md:block overflow-x-auto rounded-2xl border border-gray-100">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="py-4 px-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Nombre / Email</th>
                            <th class="py-4 px-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Tipo</th>
                            <th class="py-4 px-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Mensaje</th>
                            <th class="py-4 px-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Fecha</th>
                            <th class="py-4 px-6 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @forelse($intentions as $intention)
                        <tr class="hover:bg-blue-50/20 transition-colors">
                            <td class="py-5 px-6 whitespace-nowrap">
                                <div class="text-sm font-bold text-text-dark uppercase tracking-tight">{{ $intention->name }}</div>
                                <div class="text-[11px] text-text-light font-medium">{{ $intention->email }}</div>
                            </td>
                            <td class="py-5 px-6">
                                <span @class([
                                    'inline-flex items-center px-3 py-1 rounded-lg text-[9px] font-black uppercase border',
                                    'bg-green-50 text-green-700 border-green-100' => $intention->type == 'salud',
                                    'bg-gray-50 text-gray-700 border-gray-100' => $intention->type == 'difuntos',
                                    'bg-yellow-50 text-yellow-700 border-yellow-100' => $intention->type == 'accion-gracias',
                                    'bg-blue-50 text-blue-700 border-blue-100' => !in_array($intention->type, ['salud', 'difuntos', 'accion-gracias'])
                                ])>
                                    {{ $intention->formatted_type ?? $intention->type }}
                                </span>
                            </td>
                            <td class="py-5 px-6">
                                <div class="text-xs text-text-light max-w-xs truncate font-medium" title="{{ $intention->message }}">
                                    {{ $intention->message }}
                                </div>
                            </td>
                            <td class="py-5 px-6 text-[11px] text-gray-400 font-bold whitespace-nowrap">
                                {{ $intention->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-5 px-6 text-right">
                                <button type="button" 
                                        onclick="confirmSingleDelete({{ $intention->id }}, '{{ $intention->name }}')" 
                                        class="text-red-400 hover:text-red-600 p-2 hover:bg-red-50 rounded-xl transition-all">
                                    <img src="{{ asset('img/icono_eliminar.png') }}" class="w-5 h-5">
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-16 text-center bg-gray-50/50">
                                <p class="text-sm text-gray-400 italic">No hay intenciones registradas para el día de hoy.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Vista Móvil (Cards) --}}
            <div class="md:hidden space-y-4">
                @forelse($intentions as $intention)
                <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                    <div class="flex justify-between items-start mb-4 gap-3">
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-text-dark uppercase tracking-tight truncate">{{ $intention->name }}</h3>
                            <p class="text-[10px] text-text-light font-medium truncate">{{ $intention->email }}</p>
                        </div>
                        <span @class([
                            'inline-flex items-center px-2 py-1 rounded-lg text-[8px] font-black uppercase border shrink-0',
                            'bg-green-50 text-green-700 border-green-100' => $intention->type == 'salud',
                            'bg-gray-50 text-gray-700 border-gray-100' => $intention->type == 'difuntos',
                            'bg-yellow-50 text-yellow-700 border-yellow-100' => $intention->type == 'accion-gracias',
                            'bg-blue-50 text-blue-700 border-blue-100' => !in_array($intention->type, ['salud', 'difuntos', 'accion-gracias'])
                        ])>
                            {{ $intention->formatted_type ?? $intention->type }}
                        </span>
                    </div>

                    <div class="bg-gray-50/50 rounded-2xl p-4 mb-4 border border-gray-100">
                        <p class="text-xs text-text-light italic leading-relaxed">"{{ $intention->message }}"</p>
                    </div>

                    <div class="flex justify-between items-center border-t border-gray-50 pt-4">
                        <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                            {{ $intention->created_at->format('d/m/Y H:i') }}
                        </span>
                        <button type="button" 
                                onclick="confirmSingleDelete({{ $intention->id }}, '{{ $intention->name }}')" 
                                class="flex items-center gap-2 text-red-500 text-[10px] font-black uppercase tracking-widest hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors">
                            <img src="{{ asset('img/icono_eliminar.png') }}" class="w-4 h-4">
                            Eliminar
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-100">
                    <p class="text-xs italic">Sin intenciones registradas.</p>
                </div>
                @endforelse
            </div>

            @if($intentions->hasPages())
            <div class="mt-8">
                {{ $intentions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL ELIMINAR INDIVIDUAL --}}
<div id="deleteModal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4 bg-black/75 backdrop-blur-md">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center animate-slide-up">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-red-50 mb-6">
            <img src="{{ asset('img/icono_eliminar.png') }}" class="w-8 h-8">
        </div>
        <h3 class="text-xl font-black text-text-dark mb-2 uppercase tracking-tight">¿Eliminar Intención?</h3>
        <p class="text-text-light mb-8 text-sm leading-relaxed">
            Estás por borrar la intención de <span id="deleteIntentionName" class="font-bold text-red-500"></span>. Esta acción no se puede deshacer.
        </p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-4 border-2 border-gray-100 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all uppercase text-[10px] tracking-widest">Cancelar</button>
            <form id="deleteForm" method="POST" class="flex-1">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-4 bg-red-500 text-white font-bold rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-red-100 uppercase text-[10px] tracking-widest">Eliminar</button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL ELIMINAR TODAS --}}
<div id="deleteAllModal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4 bg-black/75 backdrop-blur-md">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center animate-slide-up">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-red-600 mb-6 shadow-xl shadow-red-100">
            <img src="{{ asset('img/icono_eliminar.png') }}" class="w-8 h-8 brightness-0 invert">
        </div>
        <h3 class="text-xl font-black text-text-dark mb-2 uppercase tracking-tight">¡VACIAR LISTA!</h3>
        <p class="text-text-light mb-8 text-sm leading-relaxed">
            Se eliminarán **TODAS** las intenciones registradas permanentemente. <br><span class="font-bold text-red-500">¿Estás seguro de continuar?</span>
        </p>
        <div class="flex flex-col gap-3">
            <form action="{{ route('admin.intentions.delete-all') }}" method="POST" class="w-full">
                @csrf @method('DELETE')
                <button type="submit" class="w-full py-4 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition-all shadow-lg shadow-red-100 uppercase text-xs tracking-widest">SÍ, ELIMINAR TODO</button>
            </form>
            <button onclick="closeDeleteAllModal()" class="w-full py-4 border-2 border-gray-100 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition-all uppercase text-[10px] tracking-widest">Cancelar</button>
        </div>
    </div>
</div>

<script>
// --- LÓGICA DE MODALES DE ELIMINACIÓN ---

function confirmSingleDelete(id, name) {
    const modal = document.getElementById('deleteModal');
    document.getElementById('deleteIntentionName').textContent = name;
    document.getElementById('deleteForm').action = `/admin/intentions/${id}`;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function confirmDeleteAll() {
    const modal = document.getElementById('deleteAllModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeDeleteAllModal() {
    const modal = document.getElementById('deleteAllModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Cerrar con Escape
document.addEventListener('keydown', e => {
    if(e.key === 'Escape') {
        closeDeleteModal();
        closeDeleteAllModal();
    }
});
</script>

<style>
    @keyframes slide-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slide-up { animation: slide-up 0.3s ease-out forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
@endsection
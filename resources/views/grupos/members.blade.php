@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="container max-w-5xl mx-auto px-4 text-left">
        
        {{-- Cabecera con Navegación Estilo Gmail --}}
        <div class="bg-button p-10 rounded-[2.5rem] border border-gray-100 shadow-sm flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('grupos.dashboard', $groupRole) }}" class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-gray-400 hover:bg-blue-900 hover:text-white transition-all shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-white uppercase tracking-tighter leading-none">{{ $group->name }}</h1>
                    <p class="text-[9px] font-black text-white uppercase tracking-widest mt-1 opacity-80">Directorio de Miembros Parroquiales</p>
                </div>
            </div>

            {{-- Navegación Estilo Gmail (Arrows) --}}
            <div id="paginationNav" class="flex items-center bg-white/10 backdrop-blur-md rounded-2xl p-1 gap-1 border border-white/20">
                <div class="px-4 text-[10px] font-black text-white uppercase">
                    <span id="itemsRange">{{ $members->firstItem() ?? 0 }} - {{ $members->lastItem() ?? 0 }}</span> de <span id="itemsTotal">{{ $members->total() }}</span>
                </div>
                <div class="flex gap-1" id="paginationButtons">
                    @if($members->onFirstPage())
                        <span class="w-10 h-10 rounded-xl bg-white/5 text-white/20 flex items-center justify-center cursor-not-allowed border border-white/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                        </span>
                    @else
                        <a href="{{ $members->previousPageUrl() }}" class="pagination-link w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center hover:bg-white hover:text-button transition-all shadow-sm border border-white/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                    @endif

                    @if($members->hasMorePages())
                        <a href="{{ $members->nextPageUrl() }}" class="pagination-link w-10 h-10 rounded-xl bg-white/20 text-white flex items-center justify-center hover:bg-white hover:text-button transition-all shadow-sm border border-white/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <span class="w-10 h-10 rounded-xl bg-white/5 text-white/20 flex items-center justify-center cursor-not-allowed border border-white/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- BUSCADOR --}}
        <div class="mb-6">
            <form action="{{ route('grupos.members', $groupRole) }}" method="GET" class="relative group" id="searchForm" onsubmit="return false;">
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                       placeholder="Buscar miembro por nombre o correo electrónico..." 
                       class="w-full pl-14 pr-4 py-5 bg-white border border-gray-100 rounded-[1.5rem] shadow-sm outline-none focus:ring-4 focus:ring-button/5 focus:border-button transition-all text-sm font-medium"
                       autocomplete="off">
                
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-button transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <div id="searchLoader" class="hidden absolute right-16 top-1/2 -translate-y-1/2">
                    <svg class="animate-spin h-5 w-5 text-button" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>

                @if(request('search'))
                    <div id="clearSearchBtn" class="absolute right-5 top-1/2 -translate-y-1/2 flex items-center gap-3">
                        <a href="{{ route('grupos.members', $groupRole) }}" class="text-[10px] font-black text-red-400 hover:text-red-600 uppercase tracking-widest bg-red-50 px-3 py-1 rounded-full transition-colors">
                            Limpiar
                        </a>
                    </div>
                @endif
            </form>
        </div>

        {{-- Contenedor de Tabla Dinámica --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden relative" id="membersTableContainer">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100">
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Miembro</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</th>
                            <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" id="membersTableBody">
                        @forelse($members as $m)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4 text-left">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-black text-gray-400 uppercase text-xs group-hover:bg-button group-hover:text-white transition-all shadow-inner">
                                        {{ substr($m->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-text-dark uppercase leading-tight">{{ $m->name }}</p>
                                        @php 
                                            $joinedDate = $m->joined_at_group ? \Carbon\Carbon::parse($m->joined_at_group) : $m->created_at;
                                        @endphp
                                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">Miembro desde {{ $joinedDate->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-left">
                                <p class="text-[11px] font-medium text-text-light">{{ $m->email }}</p>
                            </td>
                            <td class="px-8 py-6 text-right">
                                @if(Auth::id() !== $m->id)
                                    <button onclick="confirmRemoveMember({{ $m->id }}, '{{ $m->name }}', '{{ $groupRole }}')" class="p-2 text-red-200 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                @else
                                    <span class="text-[9px] font-black text-gray-300 uppercase italic px-4">Tú</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-20 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    <p class="text-[11px] font-black text-gray-300 uppercase tracking-[0.2em] italic">
                                        No se encontraron resultados
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN --}}
<div id="confirmDeleteModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/75 backdrop-blur-md">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm p-8 text-center animate-fade-in">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-red-50 mb-6 text-red-500">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <h3 class="text-xl font-black text-text-dark mb-2 uppercase tracking-tight">¿Remover Miembro?</h3>
        <p class="text-text-light mb-8 text-sm leading-relaxed">
            Vas a retirar a <span id="deleteMemberName" class="font-bold text-red-500"></span> de la comunidad. Esta acción no se puede deshacer.
        </p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-4 border-2 border-gray-100 text-gray-400 font-bold rounded-2xl uppercase text-[10px] tracking-widest">Cancelar</button>
            <form id="deleteMemberForm" method="POST" class="flex-1">
                @csrf 
                @method('DELETE')
                <button type="submit" class="w-full py-4 bg-red-500 text-white font-bold rounded-2xl shadow-lg shadow-red-100 uppercase text-[10px] tracking-widest">Confirmar</button>
            </form>
        </div>
    </div>
</div>

<script>
    /**
     * Buscador en tiempo real con Fetch API
     */
    const searchInput = document.getElementById('searchInput');
    const searchLoader = document.getElementById('searchLoader');
    const tableBody = document.getElementById('membersTableBody');
    const paginationNav = document.getElementById('paginationNav');
    const groupRole = "{{ $groupRole }}";
    let typingTimer;

    searchInput.addEventListener('input', () => {
        clearTimeout(typingTimer);
        searchLoader.classList.remove('hidden');
        
        typingTimer = setTimeout(async () => {
            const query = searchInput.value;
            const url = new URL(window.location.href);
            url.searchParams.set('search', query);
            
            try {
                const response = await fetch(url.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await response.text();
                
                // Parseamos el HTML recibido
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Extraemos y reemplazamos los componentes clave
                const newTableBody = doc.getElementById('membersTableBody');
                const newPaginationNav = doc.getElementById('paginationNav');
                
                if(newTableBody) tableBody.innerHTML = newTableBody.innerHTML;
                if(newPaginationNav) paginationNav.innerHTML = newPaginationNav.innerHTML;

                // Actualizamos la URL sin recargar para que el botón "Limpiar" funcione
                window.history.replaceState({}, '', url.toString());

                // Manejamos visibilidad del botón limpiar de forma manual para evitar parpadeos
                const clearBtn = document.getElementById('clearSearchBtn');
                if (query.length > 0) {
                    if (!clearBtn) {
                        const btnHtml = `<div id="clearSearchBtn" class="absolute right-5 top-1/2 -translate-y-1/2 flex items-center gap-3">
                            <a href="{{ route('grupos.members', $groupRole) }}" class="text-[10px] font-black text-red-400 hover:text-red-600 uppercase tracking-widest bg-red-50 px-3 py-1 rounded-full transition-colors">Limpiar</a>
                        </div>`;
                        searchInput.parentElement.insertAdjacentHTML('beforeend', btnHtml);
                    }
                } else if (clearBtn) {
                    clearBtn.remove();
                }

            } catch (error) {
                console.error('Error en búsqueda:', error);
            } finally {
                searchLoader.classList.add('hidden');
            }
        }, 300); // 300ms de retraso para mayor fluidez
    });

    /**
     * Lógica del Modal
     */
    function confirmRemoveMember(id, name, groupRole) {
        const modal = document.getElementById('confirmDeleteModal');
        const nameSpan = document.getElementById('deleteMemberName');
        const form = document.getElementById('deleteMemberForm');
        
        nameSpan.textContent = name;
        form.action = `/grupos/panel/${groupRole}/members/${id}`;
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        const modal = document.getElementById('confirmDeleteModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('confirmDeleteModal');
        if (event.target == modal) closeDeleteModal();
    }
    
    document.addEventListener('keydown', e => { 
        if (e.key === 'Escape') closeDeleteModal(); 
    });
</script>

<style>
    @keyframes fade-in { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
    .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    
    /* Estilo para los links de paginación que se cargan dinámicamente */
    .pagination-link {
        transition: all 0.2s ease;
    }
</style>
@endsection
@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50 md:bg-white md:min-h-0 pb-20 md:pb-0">

    <div class="bg-button text-white shadow-md md:shadow-none md:rounded-t-xl sticky top-0 z-20 md:static">
        <div class="container mx-auto px-4 py-4 md:px-6 md:py-5 md:mt-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold">Gestión de Anuncios</h1>
                    <p class="text-blue-100 text-xs md:text-sm hidden md:block">Administra los avisos parroquiales visibles en la app</p>
                </div>
                <a href="{{ route('admin.announcements.create') }}" class="md:hidden bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg text-xs font-bold backdrop-blur-sm transition-colors border border-white/30 flex items-center gap-1 uppercase">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nuevo
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-2 md:px-0 md:border md:border-t-0 md:border-gray-100 md:rounded-b-xl md:shadow-lg md:mb-8 bg-transparent md:bg-white">
        <div class="hidden md:block px-6 py-4 border-b border-gray-50">
            <a href="{{ route('admin.announcements.create') }}" 
               class="inline-flex items-center bg-button text-white px-5 py-2.5 rounded-xl text-sm font-black hover:bg-blue-900 transition-all shadow-lg shadow-blue-100">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Anuncio
            </a>
        </div>

        <div class="md:hidden space-y-3 pb-4 mt-2">
            @forelse($announcements as $announcement)
                <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden mx-1">
                    <div class="flex">
                        <div class="w-24 bg-gray-100 flex-shrink-0 relative border-r border-gray-100">
                            @if($announcement->image_url)
                                <img src="{{ $announcement->image_url }}" alt="" class="w-full h-full object-cover absolute inset-0">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-300 p-2 text-center">
                                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-[8px] font-bold uppercase">Sin foto</span>
                                </div>
                            @endif
                            <div class="absolute top-0 left-0 bg-button text-white text-[9px] px-2 py-1 rounded-br-xl font-black shadow-md">
                                #{{ $announcement->order }}
                            </div>
                        </div>

                        <div class="flex-1 p-4 flex flex-col justify-between min-w-0">
                            <div>
                                <h3 class="text-sm font-black text-text-dark leading-tight mb-1 line-clamp-2 uppercase tracking-tight">{{ $announcement->title }}</h3>
                                <p class="text-[11px] text-text-light line-clamp-2 mb-3 font-medium">
                                    {{ $announcement->short_description ?? 'Sin descripción' }}
                                </p>
                            </div>
                            
                            <div>
                                <span @class([
                                    'inline-flex items-center px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest border',
                                    'bg-green-50 text-green-700 border-green-100' => $announcement->is_active,
                                    'bg-red-50 text-red-700 border-red-100' => !$announcement->is_active
                                ])>
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $announcement->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    {{ $announcement->is_active ? 'Publicado' : 'Borrador' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex divide-x divide-gray-100 border-t border-gray-100 bg-gray-50/50">
                         <a href="{{ route('admin.announcements.edit', $announcement) }}" 
                            class="flex-1 py-2.5 text-xs font-bold text-button hover:bg-blue-50 active:bg-blue-100 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide rounded">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Gestionar
                         </a>
    
                         <button type="button" 
                            onclick="confirmDeleteAnnouncement({{ $announcement->id }}, '{{ $announcement->title }}')"
                            class="flex-1 py-2.5 text-xs font-bold text-red-500 hover:bg-red-50 active:bg-red-100 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Eliminar
                        </button> 
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 px-4 text-center">
                    <div class="bg-gray-100 p-6 rounded-3xl mb-4 border border-gray-200 border-dashed">
                        <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <h3 class="text-text-dark font-black uppercase text-sm mb-2">No hay anuncios</h3>
                    <p class="text-text-light text-xs mb-6 max-w-xs mx-auto">Comienza creando el primer aviso parroquial para la comunidad.</p>
                    <a href="{{ route('admin.announcements.create') }}" class="bg-button text-white px-8 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-100">
                        Crear Anuncio
                    </a>
                </div>
            @endforelse
        </div>

        <div class="hidden md:block overflow-x-auto p-6 pt-2">
            @if($announcements->count() > 0)
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="py-4 px-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Info Principal</th>
                            <th class="py-4 px-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest hidden lg:table-cell">Resumen</th>
                            <th class="py-4 px-6 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Estado</th>
                            <th class="py-4 px-6 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-50">
                        @foreach($announcements as $announcement)
                            <tr class="hover:bg-blue-50/20 transition-colors group">
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-14 w-14 bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 shadow-inner group-hover:border-button/30 transition-all">
                                            @if($announcement->image_url)
                                                <img src="{{ $announcement->image_url }}" alt="" class="h-14 w-14 object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center text-gray-300 text-[10px] font-black uppercase tracking-tighter">N/A</div>
                                            @endif
                                        </div>
                                        <div class="ml-5">
                                            <div class="text-sm font-black text-text-dark uppercase tracking-tight">{{ $announcement->title }}</div>
                                            <div class="flex items-center mt-1">
                                                <span class="text-[9px] font-black text-button bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100 uppercase tracking-widest">Posición: {{ $announcement->order }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 hidden lg:table-cell">
                                    <div class="text-xs text-text-light max-w-xs truncate font-medium">{{ $announcement->short_description }}</div>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <span @class([
                                        'px-3 py-1 inline-flex text-[9px] leading-5 font-black rounded-lg border uppercase tracking-widest',
                                        'bg-green-50 text-green-700 border-green-100' => $announcement->is_active,
                                        'bg-red-50 text-red-700 border-red-100' => !$announcement->is_active
                                    ])>
                                        {{ $announcement->is_active ? 'Publicado' : 'Borrador' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.announcements.edit', $announcement) }}" 
                                            class="flex-1 py-2.5 text-xs font-bold text-button hover:bg-blue-50 active:bg-blue-100 transition-colors flex items-center justify-center  rounded">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <button type="button" 
                                                onclick="confirmDeleteAnnouncement({{ $announcement->id }}, '{{ $announcement->title }}')"
                                                class="flex-1 py-2.5 text-xs font-bold text-red-500 hover:bg-red-50 active:bg-red-100 transition-colors flex items-center justify-center gap-2 uppercase tracking-wide"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="py-20 text-center bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                    <p class="text-text-light text-sm font-medium italic">No hay anuncios registrados actualmente.</p>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL DE CONFIRMACIÓN DE ELIMINACIÓN --}}
<div id="deleteModal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4 bg-black/75 backdrop-blur-md">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center animate-slide-up">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-red-50 mb-6">
            <img src="{{ asset('img/icono_eliminar.png') }}" class="w-8 h-8">
        </div>
        <h3 class="text-xl font-black text-text-dark mb-2 uppercase tracking-tight">¿Eliminar Aviso?</h3>
        <p class="text-text-light mb-8 text-sm leading-relaxed font-medium">
            Estás por eliminar permanentemente: <br><span id="deleteAnnouncementTitle" class="font-black text-red-500"></span>. <br>Esta acción es irreversible.
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

{{-- MODAL DE ESTADO (Éxito/Error) --}}
<div id="statusModal" class="hidden fixed inset-0 z-[110] items-center justify-center p-4 bg-black/75 backdrop-blur-sm">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm p-8 text-center animate-fade-in">
        <div id="statusModalIcon" class="mb-6"></div>
        <h3 id="statusModalTitle" class="text-2xl font-black text-text-dark mb-2 uppercase tracking-tighter"></h3>
        <p id="statusModalMessage" class="text-text-light mb-8 text-sm font-medium leading-relaxed"></p>
        <button onclick="closeStatusModal()" class="w-full bg-button text-white py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-900 transition-all shadow-lg shadow-blue-100">Entendido</button>
    </div>
</div>

<script>
/**
 * Lógica de Eliminación con Modal
 */
function confirmDeleteAnnouncement(id, title) {
    const modal = document.getElementById('deleteModal');
    document.getElementById('deleteAnnouncementTitle').textContent = title;
    document.getElementById('deleteForm').action = `/admin/announcements/${id}`;
    
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

/**
 * Gestión de Mensajes de Éxito/Error (Flash Messages)
 */
@if(session('success') || session('error'))
    window.addEventListener('load', function() {
        const isSuccess = {{ session('success') ? 'true' : 'false' }};
        const msg = "{{ session('success') ?? session('error') }}";
        showStatusModal(isSuccess ? '¡Completado!' : 'Hubo un error', msg, isSuccess);
    });
@endif

function showStatusModal(title, message, isSuccess) {
    const modal = document.getElementById('statusModal');
    const icon = document.getElementById('statusModalIcon');
    document.getElementById('statusModalTitle').textContent = title;
    document.getElementById('statusModalMessage').textContent = message;
    
    icon.innerHTML = isSuccess ? 
        `<div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-green-50"><svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>` :
        `<div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-red-50"><svg class="h-8 w-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg></div>`;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
    document.getElementById('statusModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Cerrar con Escape
document.addEventListener('keydown', e => {
    if(e.key === 'Escape') {
        closeDeleteModal();
        closeStatusModal();
    }
});
</script>

<style>
    @keyframes slide-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slide-up { animation: slide-up 0.3s ease-out forwards; }
    @keyframes fade-in { from { opacity: 0; } to { opacity: 1; } }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
@endsection
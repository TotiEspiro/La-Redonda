@extends('layouts.app')

@section('content')
<div class="bg-background-light min-h-screen pb-20">
    <div class="container max-w-7xl mx-auto px-4 pt-8">
        {{-- Encabezado de Perfil --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 mb-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-2 h-full bg-button"></div>
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="w-24 h-24 bg-blue-900 rounded-3xl flex items-center justify-center text-white text-4xl font-bold shadow-lg uppercase">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="text-center md:text-left flex-1">
                    <h1 class="text-3xl font-black text-text-dark uppercase tracking-tighter">{{ $user->name }}</h1>
                    <p class="text-text-light flex items-center justify-center md:justify-start gap-2 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        {{ $user->email }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('profile.edit') }}" class="px-8 py-4 bg-button text-white rounded-2xl font-black hover:bg-blue-900 transition-all text-sm shadow-lg shadow-blue-100">
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Columna Izquierda --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-8">Datos Personales</h3>
                    <div class="space-y-8">
                        <div class="relative">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Edad Registrada</label>
                            @if($user->age)
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-black text-text-dark tracking-tighter">{{ $user->age }}</span>
                                    <span class="text-xs font-bold text-gray-400 uppercase">Años</span>
                                </div>
                            @else
                                <div class="p-4 bg-red-50 border border-red-100 rounded-2xl">
                                    <p class="text-red-500 font-black text-[10px] uppercase mb-1">Dato Faltante</p>
                                    <a href="{{ route('profile.edit') }}" class="text-red-600 font-black text-[9px] uppercase underline">Completar ahora</a>
                                </div>
                            @endif
                        </div>
                        <div class="mt-8 pt-8 border-t border-gray-50">
                        <a href="{{ route('profile.change-password') }}" class="text-button font-black text-[10px] uppercase tracking-widest hover:underline flex items-center gap-3">
                            <div class="p-2 bg-blue-50 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                            </div>
                            Seguridad de la cuenta
                        </a>
                    </div>
                    </div>
                </div>

                {{-- Preferencias de Alertas --}}
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Preferencias de Alertas</h3>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between group">
                            <div class="flex flex-col">
                                <span class="text-xs font-black text-text-dark uppercase tracking-tight">Notificaciones Push</span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase">PC y Celular</span>
                            </div>
                            <button onclick="toggleGlobalNotifications()" id="pushToggleBtn" 
                                    @class([
                                        'w-12 h-7 rounded-full relative transition-all shadow-inner',
                                        'bg-button' => $user->notify_announcements,
                                        'bg-gray-200' => !$user->notify_announcements
                                    ])>
                                <div id="pushToggleCircle" @class([
                                    'absolute top-1 w-5 h-5 rounded-full bg-white shadow-sm transition-all',
                                    'right-1' => $user->notify_announcements,
                                    'left-1' => !$user->notify_announcements
                                ])></div>
                            </button>
                        </div>

                        <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100">
                            <p class="text-[9px] text-blue-600 font-bold leading-relaxed uppercase">
                                * Al desactivar, dejarás de recibir avisos en tus dispositivos, pero podrás verlos en tu historial de la web.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Columna Derecha: Historial de Actividad --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col h-full overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50/30">
                        <div>
                            <h3 class="text-xl font-black text-text-dark uppercase tracking-tighter">Historial de Actividad</h3>
                            <p class="text-[10px] text-gray-400 font-black uppercase mt-1">Gestión de avisos recientes</p>
                        </div>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button onclick="clearAllNotifications()" class="flex-1 sm:flex-none text-[9px] font-black text-red-500 uppercase tracking-widest px-4 py-2.5 bg-red-50 rounded-xl shadow-sm border border-red-100 hover:bg-red-500 hover:text-white transition-all">
                                Borrar Historial
                            </button>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto max-h-[850px] custom-scrollbar" id="notif-history-container">
                        @forelse($notifications as $notification)
                        <div class="p-6 border-b border-gray-50 hover:bg-gray-50/50 transition-colors relative group {{ is_null($notification->read_at) ? 'bg-blue-50/20' : '' }}">
                            @if(is_null($notification->read_at))
                                <div class="absolute left-0 top-0 w-1.5 h-full bg-button"></div>
                            @endif
                            <div class="flex items-start gap-6">
                                <div class="p-4 rounded-2xl bg-white shadow-sm border border-gray-100 transform group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                    @php 
                                        $nData = is_array($notification->data) ? $notification->data : json_decode($notification->data, true); 
                                        $type = strtolower($notification->type);
                                    @endphp
                                    @if(str_contains($type, 'anuncio'))
                                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.297A2.453 2.453 0 019.297 21.75H4.25A2.25 2.25 0 012 19.5V4.5A2.25 2.25 0 014.25 2.25h5.047a2.25 2.25 0 012.25 2.25v1.382z"></path></svg>
                                    @elseif(str_contains($type, 'donacion'))
                                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @elseif(str_contains($type, 'material'))
                                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    @elseif(str_contains($type, 'solicitud'))
                                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                    @elseif(str_contains($type, 'aceptado'))
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @else
                                        <svg class="w-6 h-6 text-button" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-black text-text-dark text-sm uppercase tracking-tight truncate pr-4">{{ $nData['title'] ?? 'Aviso Parroquial' }}</h4>
                                        <span class="text-[9px] text-gray-400 font-black uppercase tracking-widest flex-shrink-0">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-text-light leading-relaxed mb-4 font-medium">{{ $nData['message'] ?? 'Actualización en tu cuenta.' }}</p>
                                    @if($nData['link'] ?? null)
                                        <a href="{{ $nData['link'] }}" class="inline-flex items-center text-[10px] font-black text-button uppercase tracking-widest hover:translate-x-1 transition-transform gap-1">
                                            VER DETALLES <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-32 text-center" id="empty-notif-state">
                            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 border border-gray-100 shadow-inner">
                                <svg class="w-10 h-10 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0h-1.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            </div>
                            <p class="text-xs text-gray-400 font-black uppercase tracking-[0.3em]">Sin actividad reciente</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DE CONFIRMACIÓN DE LIMPIEZA --}}
<div id="clearConfirmModal" class="hidden fixed inset-0 z-[130] items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm p-8 text-center animate-slide-up">
        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <h3 class="text-2xl font-black text-text-dark mb-2 uppercase tracking-tighter">¿Borrar Historial?</h3>
        <p class="text-gray-500 mb-8 text-sm leading-relaxed font-medium px-4">
            Se eliminarán permanentemente todas las notificaciones de tu cuenta. Esta acción no se puede deshacer.
        </p>
        <div class="flex flex-col gap-3">
            <button onclick="executeClearNotifications()" class="w-full py-4 bg-red-500 text-white rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg shadow-red-100 hover:bg-red-600 transition-all active:scale-95">
               Eliminar
            </button>
            <button onclick="closeClearModal()" class="w-full py-4 text-gray-400 font-bold uppercase text-[10px] tracking-[0.2em] hover:text-gray-600 transition-colors">
                Cancelar
            </button>
        </div>
    </div>
</div>

{{-- MODAL DE ESTADO --}}
<div id="statusModal" class="hidden fixed inset-0 bg-black/70 backdrop-blur-md flex items-center justify-center z-[140] hidden p-4">
    <div class="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl p-8 text-center animate-fade-in">
        <div id="statusIcon" class="mx-auto mb-6"></div>
        <h3 id="statusTitle" class="text-xl font-black text-gray-800 uppercase tracking-tight mb-2"></h3>
        <p id="statusMsg" class="text-gray-500 mb-8 text-sm font-medium leading-relaxed"></p>
        <button onclick="closeStatus()" class="w-full py-4 bg-button text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg">Entendido</button>
    </div>
</div>

<script>
/**
 * Alterna el estado global de notificaciones (PC y Móvil)
 */
async function toggleGlobalNotifications() {
    const btn = document.getElementById('pushToggleBtn');
    const circle = document.getElementById('pushToggleCircle');
    const currentState = btn.classList.contains('bg-button');
    const newState = !currentState;

    try {
        const response = await fetch('{{ route('profile.update-preference') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ notify: newState ? 1 : 0 })
        });
        
        const data = await response.json();
        
        if (data.success) {
            if (newState) {
                btn.classList.replace('bg-gray-200', 'bg-button');
                circle.classList.replace('left-1', 'right-1');
            } else {
                btn.classList.replace('bg-button', 'bg-gray-200');
                circle.classList.replace('right-1', 'left-1');
            }
        }
    } catch (error) {
        console.error('Error actualizando preferencias:', error);
    }
}

/**
 * Marcar todas como leídas
 */
async function markAllAsRead() {
    try {
        const response = await fetch('{{ route('notifications.read-all') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        if (response.ok) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

/**
 * Abre el modal de confirmación
 */
function clearAllNotifications() {
    const modal = document.getElementById('clearConfirmModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

/**
 * Cierra el modal de confirmación
 */
function closeClearModal() {
    const modal = document.getElementById('clearConfirmModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

/**
 * Ejecuta el borrado físico de las notificaciones
 */
async function executeClearNotifications() {
    closeClearModal();
    
    try {
        const response = await fetch('{{ route('profile.notifications.clear') }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (response.ok) {
            const container = document.getElementById('notif-history-container');
            container.style.opacity = '0';
            container.style.transition = 'opacity 0.4s ease';
            setTimeout(() => window.location.reload(), 450);
        }
    } catch (error) {
        console.error('Error vaciando historial:', error);
    }
}

function closeStatus() {
    document.getElementById('statusModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
    @keyframes slide-up { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slide-up { animation: slide-up 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endsection
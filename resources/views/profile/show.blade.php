@extends('layouts.app')

@section('content')
<div class="bg-background-light min-h-screen pb-24 overflow-x-hidden">
    <div class="container max-w-7xl mx-auto px-4 pt-6 md:pt-12 text-left">
        
        {{-- Encabezado de Perfil Adaptable --}}
        <div class="bg-white rounded-[2rem] md:rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 mb-8 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1.5 md:w-2 md:h-full bg-button"></div>
            <div class="flex flex-col md:flex-row items-center gap-6">
                <div class="w-20 h-20 md:w-24 md:h-24 bg-blue-900 rounded-[1.8rem] flex items-center justify-center text-white text-3xl md:text-4xl font-black shadow-lg uppercase flex-shrink-0">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div class="text-center md:text-left flex-1 min-w-0 w-full">
                    <h1 class="text-2xl md:text-3xl font-black text-text-dark uppercase tracking-tighter truncate">{{ $user->name }}</h1>
                    <p class="text-text-light flex items-center justify-center md:justify-start gap-2 font-bold text-[11px] md:text-sm mt-1 truncate">
                        <svg class="w-4 h-4 text-button flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        {{ $user->email }}
                    </p>
                </div>
                <div class="w-full md:w-auto">
                    <a href="{{ route('profile.edit') }}" class="block w-full text-center px-8 py-4 bg-button text-white rounded-2xl font-black hover:bg-blue-900 transition-all text-xs shadow-lg shadow-blue-100 uppercase tracking-widest active:scale-95">
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
            {{-- Columna Izquierda: Datos y Privacidad --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- Datos Personales --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-8">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Datos Personales</h3>
                    <div class="space-y-6">
                        <div class="relative">
                            <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-2">Edad Registrada</label>
                            @if($user->age)
                                <div class="flex items-baseline gap-2">
                                    <span class="text-4xl font-black text-text-dark tracking-tighter">{{ $user->age }}</span>
                                    <span class="text-xs font-bold text-gray-300 uppercase">Años</span>
                                </div>
                            @else
                                <div class="p-4 bg-red-50 border border-red-100 rounded-2xl">
                                    <p class="text-red-500 font-black text-[9px] uppercase mb-1">Dato Faltante</p>
                                    <a href="{{ route('profile.edit') }}" class="text-red-600 font-black text-[9px] uppercase underline tracking-widest">Completar ahora</a>
                                </div>
                            @endif
                        </div>
                        <div class="pt-6 border-t border-gray-50">
                            <a href="{{ route('profile.change-password') }}" class="group text-button font-black text-[10px] uppercase tracking-widest hover:underline flex items-center gap-3 transition-all">
                                <div class="p-2.5 bg-blue-50 rounded-xl group-hover:bg-button group-hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                </div>
                                Seguridad de la cuenta
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Panel de Privacidad Táctil --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 md:p-8">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-8">Preferencias de Notificaciones</h3>
                    <div class="space-y-8">
                        
                        {{-- 1. ANUNCIOS --}}
                        <div class="flex items-center justify-between group">
                            <div class="flex flex-col pr-4 min-w-0">
                                <span class="text-xs font-black text-text-dark uppercase tracking-tight truncate">Noticias generales</span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase truncate">Avisos Parroquiales</span>
                            </div>
                            <button onclick="toggleNotification('announcements')" id="btn-announcements" 
                                    @class([
                                        'w-12 h-7 rounded-full relative transition-all shadow-inner flex-shrink-0',
                                        'bg-button' => $user->notify_announcements,
                                        'bg-gray-200' => !$user->notify_announcements
                                    ])>
                                <div id="circle-announcements" @class([
                                    'absolute top-1 w-5 h-5 rounded-full bg-white shadow-sm transition-all',
                                    'right-1' => $user->notify_announcements,
                                    'left-1' => !$user->notify_announcements
                                ])></div>
                            </button>
                        </div>

                        {{-- 2. COMUNIDAD --}}
                        <div class="flex items-center justify-between group">
                            <div class="flex flex-col pr-4 min-w-0">
                                <span class="text-xs font-black text-text-dark uppercase tracking-tight truncate">Comunidad Parroquial</span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase truncate">Grupos y Materiales</span>
                            </div>
                            <button onclick="toggleNotification('community')" id="btn-community" 
                                    @class([
                                        'w-12 h-7 rounded-full relative transition-all shadow-inner flex-shrink-0',
                                        'bg-button' => $user->notify_community,
                                        'bg-gray-200' => !$user->notify_community
                                    ])>
                                <div id="circle-community" @class([
                                    'absolute top-1 w-5 h-5 rounded-full bg-white shadow-sm transition-all',
                                    'right-1' => $user->notify_community,
                                    'left-1' => !$user->notify_community
                                ])></div>
                            </button>
                        </div>

                        {{-- 3. DONACIONES --}}
                        <div class="flex items-center justify-between group">
                            <div class="flex flex-col pr-4 min-w-0">
                                <span class="text-xs font-black text-text-dark uppercase tracking-tight truncate">Contribución</span>
                                <span class="text-[9px] text-gray-400 font-bold uppercase truncate">Intenciones y Donaciones</span>
                            </div>
                            <button onclick="toggleNotification('donations_intentions')" id="btn-donations_intentions" 
                                    @class([
                                        'w-12 h-7 rounded-full relative transition-all shadow-inner flex-shrink-0',
                                        'bg-button' => $user->notify_donations_intentions,
                                        'bg-gray-200' => !$user->notify_donations_intentions
                                    ])>
                                <div id="circle-donations_intentions" @class([
                                    'absolute top-1 w-5 h-5 rounded-full bg-white shadow-sm transition-all',
                                    'right-1' => $user->notify_donations_intentions,
                                    'left-1' => !$user->notify_donations_intentions
                                ])></div>
                            </button>
                        </div>

                        @php $inGroup = $user->roles->whereNotIn('name', ['admin', 'superadmin', 'usuario'])->isNotEmpty(); @endphp
                        @if(!$inGroup)
                        <div class="p-4 bg-orange-50 rounded-2xl border border-orange-100 mt-4">
                            <p class="text-[9px] text-orange-600 font-bold leading-relaxed uppercase tracking-tight">
                                * Nota: Únete a un grupo para recibir alertas de materiales.
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Columna Derecha: Historial (Lista Responsiva) --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 flex flex-col h-full overflow-hidden">
                    <div class="p-6 md:p-8 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/30 text-center sm:text-left">
                        <div class="w-full sm:w-auto">
                            <h3 class="text-lg md:text-xl font-black text-text-dark uppercase tracking-tighter leading-none">Historial de Actividad</h3>
                            <p class="text-[9px] text-gray-400 font-black uppercase mt-1 tracking-widest">Gestión de avisos recientes</p>
                        </div>
                        <button onclick="clearAllNotifications()" class="w-full sm:w-auto text-[9px] font-black text-red-500 uppercase tracking-widest px-6 py-3 bg-red-50 rounded-xl border border-red-100 hover:bg-red-500 hover:text-white transition-all active:scale-95">
                            Borrar Historial
                        </button>
                    </div>

                    <div class="flex-1 overflow-y-auto max-h-[700px] md:max-h-[850px] custom-scrollbar" id="notif-history-container">
                        @forelse($notifications as $notification)
                        <div class="p-5 md:p-6 border-b border-gray-50 hover:bg-gray-50/50 transition-colors relative group {{ is_null($notification->read_at) ? 'bg-blue-50/20' : '' }}">
                            @if(is_null($notification->read_at))
                                <div class="absolute left-0 top-0 w-1 md:w-1.5 h-full bg-button"></div>
                            @endif
                            <div class="flex items-start gap-4 md:gap-6">
                                <div class="p-3 md:p-4 rounded-xl md:rounded-2xl bg-white shadow-sm border border-gray-100 transform group-hover:scale-105 transition-transform flex-shrink-0">
                                    @php 
                                        $nData = is_array($notification->data) ? $notification->data : json_decode($notification->data, true); 
                                        $type = strtolower($notification->type);
                                    @endphp
                                    @if(str_contains($type, 'anuncio'))
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5.882V19.297A2.453 2.453 0 019.297 21.75H4.25A2.25 2.25 0 012 19.5V4.5A2.25 2.25 0 014.25 2.25h5.047a2.25 2.25 0 012.25 2.25v1.382z"></path></svg>
                                    @elseif(str_contains($type, 'donacion'))
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    @else
                                        <svg class="w-5 h-5 md:w-6 md:h-6 text-button" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-black text-text-dark text-xs md:text-sm uppercase tracking-tight truncate pr-2">{{ $nData['title'] ?? 'Aviso' }}</h4>
                                        <span class="text-[8px] md:text-[9px] text-gray-400 font-bold uppercase flex-shrink-0 tracking-tighter">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-[11px] md:text-xs text-text-light leading-relaxed mb-3 font-medium line-clamp-2">{{ $nData['message'] ?? 'Actualización.' }}</p>
                                    @if($nData['link'] ?? ($nData['url'] ?? null))
                                        <a href="{{ $nData['link'] ?? $nData['url'] }}" class="inline-flex items-center text-[9px] md:text-[10px] font-black text-button uppercase tracking-widest hover:translate-x-1 transition-transform gap-1">
                                            DETALLES <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="py-32 text-center opacity-40">
                            <img src="{{ asset('img/icono_campana.png') }}" class="w-16 h-16 mx-auto mb-4 grayscale">
                            <p class="text-xs text-gray-400 font-black uppercase tracking-[0.3em]">Sin actividad reciente</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ELIMINAR --}}
<div id="clearConfirmModal" class="hidden fixed inset-0 z-[130] items-center justify-center p-6 bg-black/80 backdrop-blur-md">
    <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm p-10 text-center animate-slide-up">
        <div class="w-20 h-20 bg-red-50 text-red-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </div>
        <h3 class="text-2xl font-black text-text-dark mb-3 uppercase tracking-tighter">¿Borrar Historial?</h3>
        <p class="text-gray-500 mb-10 text-sm font-medium leading-relaxed px-2">Esta acción eliminará todas tus notificaciones de forma permanente.</p>
        <div class="flex flex-col gap-3">
            <button onclick="executeClearNotifications()" class="w-full py-5 bg-red-500 text-white rounded-2xl font-black uppercase text-xs shadow-xl active:scale-95 transition-all">Eliminar Todo</button>
            <button onclick="closeClearModal()" class="w-full py-4 text-gray-400 font-black uppercase text-[10px] tracking-widest hover:text-gray-600">Cancelar</button>
        </div>
    </div>
</div>

<script>
async function toggleNotification(type) {
    const btn = document.getElementById('btn-' + type);
    const circle = document.getElementById('circle-' + type);
    const currentState = btn.classList.contains('bg-button');
    const newState = !currentState;

    if (newState) {
        btn.classList.replace('bg-gray-200', 'bg-button');
        circle.classList.replace('left-1', 'right-1');
    } else {
        btn.classList.replace('bg-button', 'bg-gray-200');
        circle.classList.replace('right-1', 'left-1');
    }

    try {
        const response = await fetch('{{ route('profile.update-preference') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ type: type, notify: newState ? 1 : 0 })
        });
        const data = await response.json();
        if (!data.success) throw new Error();
    } catch (error) {
        if (currentState) {
            btn.classList.replace('bg-gray-200', 'bg-button');
            circle.classList.replace('left-1', 'right-1');
        } else {
            btn.classList.replace('bg-button', 'bg-gray-200');
            circle.classList.replace('right-1', 'left-1');
        }
    }
}

function clearAllNotifications() {
    const modal = document.getElementById('clearConfirmModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeClearModal() {
    const modal = document.getElementById('clearConfirmModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

async function executeClearNotifications() {
    closeClearModal();
    try {
        const response = await fetch('{{ route('profile.notifications.clear') }}', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        });
        if (response.ok) {
            document.getElementById('notif-history-container').style.opacity = '0.3';
            setTimeout(() => window.location.reload(), 400);
        }
    } catch (error) { console.error(error); }
}
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
    @keyframes slide-up { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slide-up { animation: slide-up 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
@endsection
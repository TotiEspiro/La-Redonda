@auth
    @php
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isMember = $user->hasRole($slug);
        
        $pendingRequest = \Illuminate\Support\Facades\DB::table('group_requests')
            ->where('user_id', $user->id)
            ->where('group_role', $slug)
            ->where('status', 'pending')
            ->exists();

        $groupInfo = \App\Models\Group::where('category', $slug)->first();
        
        $noAge = is_null($user->age);
        
        $ageOk = true;
        if ($groupInfo && $user->age) {
            $ageOk = ($user->age >= $groupInfo->min_age && $user->age <= $groupInfo->max_age);
        }

        $pendingCount = \Illuminate\Support\Facades\DB::table('group_requests')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
        $limitReached = ($pendingCount >= 2 && !$pendingRequest);
    @endphp

    @if($isMember)
        <button disabled class="w-full py-4 bg-green-100 text-green-700 rounded-2xl font-black text-[10px] uppercase tracking-widest cursor-not-allowed">
            Ya eres miembro
        </button>
    @elseif($pendingRequest)
        <button disabled class="w-full py-4 bg-gray-100 text-gray-400 rounded-2xl font-black text-[10px] uppercase tracking-widest cursor-not-allowed italic">
            Solicitud Pendiente
        </button>
    @elseif($noAge)
        <a href="{{ route('profile.edit') }}" class="block w-full py-4 bg-yellow-50 text-yellow-700 text-center rounded-2xl font-black text-[10px] uppercase tracking-widest border-2 border-dashed border-yellow-200">
            Completa tu edad para unirte
        </a>
    @elseif(!$ageOk)
        <button disabled class="w-full py-4 bg-red-50 text-red-300 rounded-2xl font-black text-[10px] uppercase tracking-widest cursor-not-allowed opacity-50">
            Edad fuera de rango
        </button>
    @elseif($limitReached)
        <button disabled title="Límite de solicitudes alcanzado" class="w-full py-4 bg-gray-50 text-gray-300 rounded-2xl font-black text-[10px] uppercase tracking-widest cursor-not-allowed">
            Límite: 2 Solicitudes
        </button>
    @else
        <button onclick="openJoinModal('{{ $slug }}', '{{ $groupInfo->name ?? str_replace('_', ' ', $slug) }}')" 
                class="w-full py-4 bg-button text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-900 transition-all shadow-lg active:scale-95">
            Enviar Solicitud
        </button>

        {{-- MODAL DE COMPROMISO --}}
        <div id="modal-join-{{ $slug }}" class="fixed inset-0 z-[150] hidden items-center justify-center p-4 bg-black/70 backdrop-blur-sm animate-fade-in">
            <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-sm p-8 text-center animate-slide-up">
                <div class="w-16 h-16 bg-blue-50 text-button rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight mb-3">¿Confirmar Compromiso?</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                    Participar en <strong>{{ $groupInfo->name ?? str_replace('_', ' ', $slug) }}</strong> requiere asistencia y compromiso. ¿Estás seguro de que puedes participar de sus actividades?
                </p>
                <div class="flex flex-col gap-3">
                    <form action="{{ route('grupos.send-request', $slug) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-4 bg-button text-white rounded-2xl font-black uppercase text-xs shadow-lg shadow-blue-100">SÍ, ME COMPROMETO</button>
                    </form>
                    <button onclick="closeJoinModal('{{ $slug }}')" class="w-full py-4 text-gray-400 font-bold uppercase text-[10px] tracking-widest">Aún no</button>
                </div>
            </div>
        </div>
    @endif
@else
    <a href="{{ route('login') }}" class="block w-full py-4 bg-gray-50 text-gray-400 text-center rounded-2xl font-black text-[10px] uppercase tracking-widest border border-gray-100 hover:bg-gray-100">
        Inicia sesión para unirte
    </a>
@endauth

<script>
    if (typeof openJoinModal !== 'function') {
        function openJoinModal(slug) {
            const modal = document.getElementById('modal-join-' + slug);
            if (modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); document.body.style.overflow = 'hidden'; }
        }
        function closeJoinModal(slug) {
            const modal = document.getElementById('modal-join-' + slug);
            if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); document.body.style.overflow = 'auto'; }
        }
    }
</script>
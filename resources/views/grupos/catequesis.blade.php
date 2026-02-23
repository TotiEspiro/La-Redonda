@extends('layouts.app')

@section('content')
<style>
    .mobile-content {
        max-height: 0;
        opacity: 0;
        transition: max-height 0.7s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.5s ease;
        overflow: hidden;
    }
    .group-card.is-open .mobile-content {
        max-height: 500px;
        opacity: 1;
        margin-bottom: 1.5rem;
    }
    @media (min-width: 1024px) {
        .mobile-content {
            max-height: none !important;
            opacity: 1 !important;
            display: block !important;
        }
    }
    .rotate-icon { transition: transform 0.3s ease; }
    .group-card.is-open .rotate-icon { transform: rotate(180deg); }
</style>

<div class="w-full">
     <div class="text-center mt-12 mb-18 md:mb-12">
        <h1 class="text-3xl md:text-4xl font-black text-text-dark mb-4 border-b-4 border-button pb-2 inline-block px-4 uppercase tracking-tighter">Catequesis</h1>
        <p class="text-text-dark text-base md:text-lg max-w-3xl mx-auto mt-4 leading-relaxed px-2 pb-6">
            Formación sacramental para niños, adolescentes y adultos.
        </p>
    </div>
    <div class="flex items-center justify-between mb-10 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 max-w-6xl mx-auto">
        <a href="{{ route('grupos.especiales') }}" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[-4px] transition-transform group">
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            </div>
            <span class="hidden sm:inline">Más Grupos</span>
        </a>
        <a href="{{ route('grupos.jovenes') }}" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[4px] transition-transform group">
            <span class="hidden sm:inline">Jóvenes</span>
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto px-4 pb-20">
        {{-- NIÑOS --}}
        <div class="group-card group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full cursor-pointer lg:cursor-default" onclick="toggleCard(this)">
            <div class="w-full bg-gray-100 md:h-64 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('img/catequesis_niños.jpg') }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('img/logo_redonda.png') }}'; this.classList.add('p-12', 'opacity-20')">
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Catequesis Niños</h3>
                </div>
                
                <div class="mobile-content">
                    <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4 inline-block">7 a 12 años</span>
                    <span class="text-[9px] font-black text-button bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wider ml-2 whitespace-nowrap">Domingos 17:30hs</span>
                    <p class="text-text-light text-sm leading-relaxed mb-4">En el grupo de catequesis preparamos a las familias, tanto a los chicos como a los padres al mismo tiempo. 
                    <p class="text-text-light text-sm leading-relaxed mb-4">La catequesis de primera comunión es un proceso que dura dos años, donde los chicos asisten semanalmente junto a sus padres o algún familiar para que este los acompañe en el camino de la fe.</p> </p>
                </div>

                <div class="mt-auto flex flex-col gap-3">
                    @include('partials.group-join-button', ['slug' => 'catequesis_ninos', 'nombre' => 'Catequesis Niños'])
                    <div class="lg:hidden text-center">
                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest flex items-center justify-center gap-1">
                            Toca para más info <svg class="w-2 h-2 rotate-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- CONFIRMACIÓN --}}
        <div class="group-card group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full cursor-pointer lg:cursor-default" onclick="toggleCard(this)">
            <div class="w-full bg-gray-100 md:h-64 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('img/catequesis_adolescentes.png') }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('img/logo_redonda.png') }}'; this.classList.add('p-12', 'opacity-20')">
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Catequesis Adolescentes</h3>
                </div>

                <div class="mobile-content">
                    <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4 inline-block">13 a 17 años</span>
                    <span class="text-[9px] font-black text-button bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wider ml-2 whitespace-nowrap">Lunes 18:00 hs</span>
                    <p class="text-text-light text-sm leading-relaxed">Crecimiento en el Espíritu Santo y vida comunitaria para jóvenes de secundaria.</p>
                </div>

                <div class="mt-auto flex flex-col gap-3">
                    @include('partials.group-join-button', ['slug' => 'confirmacion', 'nombre' => 'Confirmación'])
                    <div class="lg:hidden text-center">
                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest flex items-center justify-center gap-1">
                            Toca para más info <svg class="w-2 h-2 rotate-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ADULTOS --}}
        <div class="group-card group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full cursor-pointer lg:cursor-default" onclick="toggleCard(this)">
            <div class="w-full bg-gray-100 md:h-64 flex items-center justify-center overflow-hidden">
                <img src="{{ asset('img/catequesis_mayores.jpg') }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('img/logo_redonda.png') }}'; this.classList.add('p-12', 'opacity-20')">
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Catequesis Adultos</h3>
                </div>

                <div class="mobile-content">
                    <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4 inline-block">Mayores de 18</span>
                    <span class="text-[9px] font-black text-button bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wider ml-2 whitespace-nowrap">Martes 17:30 hs</span>
                    <p class="text-text-light text-sm leading-relaxed">Iniciación cristiana, sacramentos y profundización en la fe para adultos.</p>
                </div>

                <div class="mt-auto flex flex-col gap-3">
                    @include('partials.group-join-button', ['slug' => 'catequesis_adultos', 'nombre' => 'Catequesis Adultos'])
                    <div class="lg:hidden text-center">
                        <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest flex items-center justify-center gap-1">
                            Toca para más info <svg class="w-2 h-2 rotate-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-8 md:mt-12">
        <a href="{{ route('grupos.index') }}" class="inline-flex items-center bg-white text-button px-8 py-3 rounded-full font-black uppercase text-[10px] tracking-widest border-2 border-button hover:bg-button hover:text-white transition-all shadow-lg active:scale-95 mb-20">
            Ver Todos los Grupos
        </a>
    </div>
</div>

<script>
    function toggleCard(card) {
        if (window.innerWidth < 1024) {
            // Cerramos otras tarjetas abiertas
            document.querySelectorAll('.group-card').forEach(c => {
                if(c !== card) c.classList.remove('is-open');
            });
            card.classList.toggle('is-open');
        }
    }
</script>
@endsection
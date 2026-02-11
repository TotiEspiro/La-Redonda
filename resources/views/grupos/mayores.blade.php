<div class="w-full">
    {{-- Barra de Navegación entre Categorías --}}
    <div class="flex items-center justify-between mb-10 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 max-w-6xl mx-auto">
        <a href="{{ route('grupos.jovenes') }}" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[-4px] transition-transform group">
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            </div>
            <span class="hidden sm:inline">Jóvenes</span>
        </a>
        <a href="{{ route('grupos.especiales') }}" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[4px] transition-transform group">
            <span class="hidden sm:inline">Más Grupos</span>
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 justify-center max-w-5xl mx-auto items-stretch">
        
        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full"> 
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="{{ asset('img/grupo_santana.png') }}" class="w-full h-full object-cover"></div>
            <div class="p-6 flex flex-col flex-grow">
                <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Santa Ana</h3>
                <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4">Mujeres Mayores</span>
                <p class="text-text-light text-sm mb-6 flex-grow leading-relaxed">Oración y encuentro fraterno dedicado a la Palabra de Dios entre mujeres.</p>
                @include('partials.group-join-button', ['slug' => 'santa_ana', 'nombre' => 'Santa Ana'])
            </div>
        </div>

        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="{{ asset('img/grupos_sanjoaquin.png') }}" class="w-full h-full object-cover"></div>
            <div class="p-6 flex flex-col flex-grow">
                <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">San Joaquín</h3>
                <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4">Hombres Mayores</span>
                <p class="text-text-light text-sm mb-6 flex-grow leading-relaxed">Oración, reflexión y fraternidad para hombres de la tercera edad.</p>
                @include('partials.group-join-button', ['slug' => 'san_joaquin', 'nombre' => 'San Joaquín'])
            </div>
        </div>

        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="{{ asset('img/grupo_ardillas.jpg') }}" class="w-full h-full object-cover"></div>
            <div class="p-6 flex flex-col flex-grow">
                <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Grupo Ardillas</h3>
                <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4">Recreación</span>
                <p class="text-text-light text-sm mb-6 flex-grow leading-relaxed">Esparcimiento y formación continua para adultos mayores.</p>
                @include('partials.group-join-button', ['slug' => 'ardillas', 'nombre' => 'Grupo Ardillas'])
            </div>
        </div>

        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="{{ asset('img/grupo_costureras.jpg') }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('img/logo_redonda.png') }}'; this.classList.add('p-12', 'opacity-20')"></div>
            <div class="p-6 flex flex-col flex-grow">
                <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Costureras</h3>
                <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4">Servicio</span>
                @include('partials.group-join-button', ['slug' => 'costureras', 'nombre' => 'Costureras'])
            </div>
        </div>
    </div>
</div>
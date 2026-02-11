<div class="w-full">
    {{-- Barra de Navegación entre Categorías --}}
    <div class="flex items-center justify-between mb-10 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 max-w-6xl mx-auto">
        <a href="{{ route('grupos.catequesis') }}" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[-4px] transition-transform group">
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            </div>
            <span class="hidden sm:inline">Catequesis</span>
        </a>
        <a href="{{ route('grupos.mayores') }}" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[4px] transition-transform group">
            <span class="hidden sm:inline">Mayores</span>
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 justify-center items-stretch max-w-7xl mx-auto">
        
        
        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="{{ asset('img/grupos_juveniles.jpg') }}" class="w-full h-full object-cover"></div>
            <div class="p-6 flex flex-col flex-grow">
                <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Juveniles</h3>
                <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4">10 a 17 años</span>
                <p class="text-text-light text-sm mb-6 flex-grow leading-relaxed">Crecemos en la fe mediante dinámicas, recreación y amistad.</p>
                @include('partials.group-join-button', ['slug' => 'juveniles', 'nombre' => 'Juveniles'])
            </div>
        </div>

        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="{{ asset('img/grupo_acutis.jpg') }}" class="w-full h-full object-cover"></div>
            <div class="p-6 flex flex-col flex-grow">
                <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">San Carlo Acutis</h3>
                <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4">18 a 24 años</span>
                <p class="text-text-light text-sm mb-6 flex-grow leading-relaxed">Comunidad, formación y oración para jóvenes universitarios.</p>
                @include('partials.group-join-button', ['slug' => 'acutis', 'nombre' => 'Grupo Acutis'])
            </div>
        </div>


        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="{{ asset('img/grupo_juanpablo.png') }}" class="w-full h-full object-cover"></div>
            <div class="p-6 flex flex-col flex-grow">
                <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Juan Pablo II</h3>
                <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4">25 a 35 años</span>
                <p class="text-text-light text-sm mb-6 flex-grow leading-relaxed">Maduramos la fe compartiendo vida, oración y servicio.</p>
                @include('partials.group-join-button', ['slug' => 'juan_pablo', 'nombre' => 'Juan Pablo II'])
            </div>
        </div>

        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="{{ asset('img/grupo_coro.jpg') }}" class="w-full h-full object-cover"></div>
            <div class="p-6 flex flex-col flex-grow">
                <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Coro Parroquial</h3>
                <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4">Música y Canto</span>
                @include('partials.group-join-button', ['slug' => 'coro', 'nombre' => 'Coro Parroquial'])
            </div>
        </div>

        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="{{ asset('img/grupo_misionero.jpg') }}" class="w-full h-full object-cover"></div>
            <div class="p-6 flex flex-col flex-grow">
                <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Misioneros</h3>
                <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4">Misión</span>
                @include('partials.group-join-button', ['slug' => 'misioneros', 'nombre' => 'Misioneros'])
            </div>
        </div>
    </div>
</div>
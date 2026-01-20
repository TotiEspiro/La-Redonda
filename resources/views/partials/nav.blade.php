<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    .slide-up-fade { animation: slideUpFade 0.2s ease-out forwards; }
    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<nav class="bg-nav-footer py-6 md:sticky md:top-0 z-50 border-b-2 border-sky-400">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center">      
            <div class="flex-1">
                <div class="flex items-center justify-center md:justify-start">
                    
                    <div class="h-px flex-1 bg-button md:hidden mr-4"></div>

                    <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center">
                        <img class="block md:hidden h-16 w-auto" src="{{ asset('img/logo_redonda_texto_vertical.png') }}" alt="Logo Móvil">
                        <img class="hidden md:block h-12 w-auto" src="{{ asset('img/logo_redonda_texto.png') }}" alt="La Redonda Joven">
                    </a>
                    <div class="h-px flex-1 bg-button md:hidden ml-4"></div>

                </div>
            </div>
            <div class="hidden md:block mr-8">
                <a href="{{ url('/donaciones') }}" class="bg-button text-white px-6 py-2 rounded-lg font-semibold no-underline hover:bg-blue-500 transition-colors">
                    Donaciones
                </a>
            </div>

            <button class="hamburger-menu hidden md:flex relative w-8 h-8 flex-col items-center justify-center cursor-pointer z-50 focus:outline-none space-y-1.5" id="hamburgerMenu" type="button">
                <span class="block w-6 h-0.5 bg-text-dark transition-all duration-300 transform" id="line1"></span>
                <span class="block w-6 h-0.5 bg-text-dark transition-all duration-300" id="line2"></span>
                <span class="block w-6 h-0.5 bg-text-dark transition-all duration-300 transform" id="line3"></span>
            </button>

            <div class="mobile-menu hidden absolute top-full right-0 bg-nav-footer w-72 shadow-2xl rounded-bl-lg z-40 border-2 border-button" id="mobileMenu">
                
                <a href="{{ url('/') }}" class="block px-6 py-4 text-text-dark hover:bg-button hover:text-white transition-all font-medium border-b border-gray-100">Inicio</a>
                
                <div class="border-b border-gray-100">
                    <button class="w-full text-left px-6 py-4 text-text-dark font-medium hover:bg-button hover:text-white transition-all flex justify-between items-center" id="pcGruposToggle">
                        <span>Grupos</span> <span class="transform transition-transform duration-300 text-sm" id="pcGruposArrow">▸</span>
                    </button>
                    <div class="hidden transition-all p-1" id="pcGruposSubmenu">
                        <a href="{{ url('/grupos') }}" class="block px-8 py-2 text-sm text-gray-700 hover:text-button font-bold">Todos los Grupos</a>
                        <a href="{{ url('/grupos/catequesis') }}" class="block px-8 py-2 text-sm text-gray-700 hover:text-button">Catequesis</a>
                        <a href="{{ url('/grupos/jovenes') }}" class="block px-8 py-2 text-sm text-gray-700 hover:text-button">Jóvenes</a>
                        <a href="{{ url('/grupos/mayores') }}" class="block px-8 py-2 text-sm text-gray-700 hover:text-button">Mayores</a>
                        <a href="{{ url('/grupos/especiales') }}" class="block px-8 py-2 text-sm text-gray-700 hover:text-button">Más Grupos</a>
                    </div>
                </div>

            
                <a href="{{ url('/intenciones') }}" class="block px-6 py-4 text-text-dark hover:bg-button hover:text-white transition-all font-medium border-b border-gray-100">Intenciones</a>

                <div class="p-4 border-t border-gray-200">
                    @auth
                        <div class="bg-button border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                            <button class="w-full flex items-center justify-between p-3 hover:bg-blue-500 transition-colors focus:outline-none" onclick="toggleAccordion('pcUserCard', 'pcUserArrow')">
                                <div class="flex items-center overflow-hidden">
                                            <img src="{{ asset('img/icono_perfil.png') }}" alt="Perfil" class="h-6 w-6">
                                            <p class="text-sm font-bold text-white truncate w-32">{{ Auth::user()->name }}</p>
                                    
                                </div>
                                <span id="pcUserArrow" class="text-white text-xs transform transition-transform duration-300">▼</span>
                            </button>

                            <div id="pcUserCard" class="hidden border-t border-gray-100 bg-nav-footer">
                                
                                @if(Auth::user()->hasAnyRole(['admin_grupo_parroquial', 'catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor']) || Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                                    <a href="{{ route('diario.index') }}" class="flex items-center px-4 py-4 text-xs font-semibold border border-white text-gray-600 hover:bg-button hover:text-white transition">
                                        <img src="{{ asset('img/icono_biblia.png') }}" class="w-4 h-4 mr-2 object-contain"> Diario La Redonda
                                    </a>
                                @endif

                                @if(Auth::user()->hasRole('admin_grupo_parroquial') || Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                                    <div>
                                        <button class="w-full flex items-center justify-between px-4 py-4 text-xs font-semibold text-gray-600 hover:bg-button hover:text-white transition border border-white" onclick="toggleAccordion('pcGestionSub', 'pcGestionArr')">
                                            <span class="flex items-center"><img src="{{ asset('img/icono_gestion.png') }}" class="w-4 h-4 mr-2 object-contain"> Gestión</span>
                                            <span id="pcGestionArr" class="text-[10px]">▸</span>
                                        </button>
                                        <div id="pcGestionSub" class="hidden px-2 py-2 bg-nav-footer">
                                                @php
                                                    $userGroupsAdmin = [];
                                                    if (Auth::user()->hasRole('admin_grupo_parroquial')) { $userGroupsAdmin = Auth::user()->roles->filter(fn($role) => in_array($role->name, ['catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor'])); }
                                                    if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
                                                        $userGroupsAdmin = collect([
                                                            (object)['name' => 'catequesis', 'display_name' => 'Catequesis'], (object)['name' => 'juveniles', 'display_name' => 'Juveniles'],
                                                            (object)['name' => 'acutis', 'display_name' => 'Acutis'], (object)['name' => 'juan_pablo', 'display_name' => 'Juan Pablo'],
                                                            (object)['name' => 'coro', 'display_name' => 'Coro'], (object)['name' => 'san_joaquin', 'display_name' => 'San Joaquín'],
                                                            (object)['name' => 'santa_ana', 'display_name' => 'Santa Ana'], (object)['name' => 'ardillas', 'display_name' => 'Ardillas'],
                                                            (object)['name' => 'costureras', 'display_name' => 'Costureras'], (object)['name' => 'misioneros', 'display_name' => 'Misioneros'],
                                                            (object)['name' => 'caridad_comedor', 'display_name' => 'Caridad']
                                                        ]);
                                                    }
                                                @endphp
                                                <div class="grid grid-cols-2 gap-1 max-h-32 overflow-y-auto custom-scrollbar">
                                                    @foreach($userGroupsAdmin as $group)
                                                        <a href="{{ route('grupos.dashboard', $group->name) }}" class="text-[10px] text-center bg-white p-1 border rounded hover:bg-button hover:text-white text-gray-600 truncate">{{ $group->display_name ?? $group->name }}</a>
                                                    @endforeach
                                                </div>
                                        </div>
                                    </div>
                                @endif

                                @if(Auth::user()->hasAnyRole(['catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor']) || Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                                    <div>
                                        <button class="w-full flex items-center justify-between px-4 py-4 text-xs font-semibold text-gray-600 hover:bg-button hover:text-white transition border border-white" onclick="toggleAccordion('pcMatSub', 'pcMatArr')">
                                            <span class="flex items-center"><img src="{{ asset('img/icono_archivo.png') }}" class="w-4 h-4 mr-2 object-contain"> Materiales</span>
                                            <span id="pcMatArr" class="text-[10px]">▸</span>
                                        </button>
                                        <div id="pcMatSub" class="hidden px-2 py-2 bg-nav-footer">
                                                @php
                                                    $userMemberGroups = Auth::user()->roles->filter(fn($role) => in_array($role->name, ['catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor']));
                                                    if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
                                                        $userMemberGroups = collect([
                                                            (object)['name' => 'catequesis', 'display_name' => 'Catequesis'], (object)['name' => 'juveniles', 'display_name' => 'Juveniles'],
                                                            (object)['name' => 'acutis', 'display_name' => 'Acutis'], (object)['name' => 'juan_pablo', 'display_name' => 'Juan Pablo'],
                                                            (object)['name' => 'coro', 'display_name' => 'Coro'], (object)['name' => 'san_joaquin', 'display_name' => 'San Joaquín'],
                                                            (object)['name' => 'santa_ana', 'display_name' => 'Santa Ana'], (object)['name' => 'ardillas', 'display_name' => 'Ardillas'],
                                                            (object)['name' => 'costureras', 'display_name' => 'Costureras'], (object)['name' => 'misioneros', 'display_name' => 'Misioneros'],
                                                            (object)['name' => 'caridad_comedor', 'display_name' => 'Caridad']
                                                        ]);
                                                    }
                                                @endphp
                                                <div class="grid grid-cols-2 gap-1 max-h-32 overflow-y-auto custom-scrollbar">
                                                    @foreach($userMemberGroups as $group)
                                                        <a href="{{ route('groups.materials', $group->name) }}" class="text-[10px] text-center p-1 border rounded bg-white hover:bg-button hover:text-white  text-gray-600 truncate">{{ $group->display_name ?? $group->name }}</a>
                                                    @endforeach
                                                </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="flex border-t border-gray-200">
                                    <a href="{{ url('/perfil') }}" 
                                        class="flex-1 py-3 flex items-center justify-center text-center text-xs bg-button text-white font-semibold hover:bg-blue-500 transition-colors border-r border-gray-200" 
                                        title="Mi Perfil">
                                        Perfil
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}" class="flex-1 m-0 p-0">
                                        @csrf
                                            <button type="submit" 
                                                class="w-full h-full py-3 flex items-center justify-center text-center text-xs text-white bg-red-500 hover:bg-red-600 font-semibold transition-colors" 
                                                title="Salir">
                                                    Cerrar Sesión
                                            </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->isAdmin())
                            <a href="/admin" class="flex items-center w-full px-3 py-3 mt-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-400 transition">
                            <img src="{{ asset('img/icono_admin.png') }}" alt="Admin" class="w-5 h-5 mr-3 object-contain"> <span class="font-semibold text-sm">Panel de Administración</span>
                            </a>
                        @endif

                    @else
                        <div class="space-y-2">
                            <a href="/login" class="block w-full text-center py-2 bg-button text-white rounded-lg font-semibold hover:bg-blue-500">Iniciar Sesión</a>
                            <a href="/register" class="block w-full bg-imprimir text-center py-2  text-white rounded-lg font-semibold hover:bg-blue-300">Registrarse</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<div class="md:hidden">
    <nav class="fixed bottom-0 left-0 w-full bg-nav-footer border-t-2 border-sky-400 z-50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
        <div class="flex justify-around items-center h-16 px-1">
            <a href="{{ url('/') }}" class="flex flex-col items-center justify-center w-full h-full text-gray-600 hover:text-white hover:bg-button transition-colors">
                <img src="{{ asset('img/icono_inicio.png') }}" alt="Inicio" class="h-6 w-6 mb-1 object-contain">
                <span class="text-[10px] font-medium leading-none">Inicio</span>
            </a>
            <a href="{{ url('/grupos') }}" class="flex flex-col items-center justify-center w-full h-full text-gray-600 hover:text-white hover:bg-button transition-colors">
                <img src="{{ asset('img/icono_grupos.png') }}" alt="Grupos" class="h-6 w-6 mb-1 object-contain">
                <span class="text-[10px] font-medium leading-none">Grupos</span>
            </a>
            <a href="{{ url('/donaciones') }}" class="flex flex-col items-center justify-center w-full h-full text-gray-600 hover:text-white hover:bg-button transition-colors">
                <img src="{{ asset('img/icono_donaciones.png') }}" alt="Donar" class="h-6 w-6 mb-1 object-contain">
                <span class="text-[10px] font-medium leading-none">Donación</span>
            </a>
            <a href="{{ url('/intenciones') }}" class="flex flex-col items-center justify-center w-full h-full text-gray-600 hover:text-white hover:bg-button transition-colors">
                <img src="{{ asset('img/icono_intenciones.png') }}" alt="Rezar" class="h-6 w-6 mb-1 object-contain">
                <span class="text-[10px] font-medium leading-none">Intención</span>
            </a>
            @auth
                <button id="bottomMenuTrigger" class="flex flex-col items-center justify-center w-full h-full text-gray-600 hover:text-white hover:bg-button  transition-colors focus:outline-none relative">
                    <div class="relative mb-1">
                         <img src="{{ asset('img/icono_perfil.png') }}" alt="Perfil" class="h-6 w-6 mb-1 object-contain">                    
                    </div>
                    <span class="text-[10px] font-medium leading-none">Cuenta</span>
                </button>
            @else
                <a href="/login" class="flex flex-col items-center justify-center w-full h-full text-gray-600 hover:bg-gray-50">
                    <img src="{{ asset('img/icono_perfil.png') }}" alt="Login" class="h-6 w-6 mb-1 object-contain">
                    <span class="text-[10px] font-medium leading-none">Ingresar</span>
                </a>
            @endauth
        </div>
    </nav>

    @auth
    <div id="bottomMenuCard" class="hidden fixed bottom-20 right-2 left-2 md:left-auto md:w-72 bg-white rounded-xl shadow-2xl border border-gray-200 z-50 slide-up-fade overflow-hidden">
        
        <div class="bg-button p-4 border-b border-gray-200 flex items-center justify-between">
            <a href="{{ url('/perfil') }}" class="flex flex-col no-underline group">
                 <span class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</span>
                 <span class="text-xs text-nav-footer font-semibold group-hover:underline">Ver mi Perfil →</span>
            </a>
            <button id="closeBottomMenu" class="text-gray-400 hover:text-gray-600 focus:outline-none p-2">✕</button>
        </div>

        <div class="max-h-[60vh] overflow-y-auto custom-scrollbar p-2 space-y-2 bg-nav-footer">

            @if(Auth::user()->hasAnyRole(['admin_grupo_parroquial', 'catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor']) || Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                <a href="{{ route('diario.index') }}" class="flex items-center w-full px-3 py-2 hover:bg-button hover:text-white text-gray-700 rounded-lg transition border border-gray-100">
                    <img src="{{ asset('img/icono_biblia.png') }}" alt="Diario" class="w-5 h-5 mr-3 object-contain"> <span class="font-medium text-sm">Diario La Redonda</span>
                </a>
            @endif

            @if(Auth::user()->hasRole('admin_grupo_parroquial') || Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button class="w-full flex justify-between items-center px-3 py-2  hover:bg-button hover:text-white text-left text-sm  text-gray-700 focus:outline-none" onclick="toggleAccordion('mobGestionSub', 'mobGestionArr')">
                        <div class="flex items-center"><img src="{{ asset('img/icono_gestion.png') }}" alt="Gestion" class="w-5 h-5 mr-3 object-contain"> Gestión Grupos</div>
                        <span id="mobGestionArr" class="transform transition-transform duration-200 text-xs text-gray-400">▼</span>
                    </button>
                    <div id="mobGestionSub" class="hidden bg-white border-t border-gray-100">
                         <div class="grid grid-cols-2 gap-2 p-2 max-h-40 overflow-y-auto">
                            @foreach($userGroupsAdmin as $group)
                                <a href="{{ route('grupos.dashboard', $group->name) }}" class="block px-2 py-1.5 text-xs text-center bg-gray-50 rounded hover:bg-indigo-50 border border-gray-100 truncate text-gray-600">{{ $group->display_name ?? ucfirst($group->name) }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if(Auth::user()->hasAnyRole(['catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor']) || Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <button class="w-full flex justify-between items-center px-3 py-2  hover:bg-button hover:text-white text-left text-sm  text-gray-700 focus:outline-none" onclick="toggleAccordion('mobMatSub', 'mobMatArr')">
                        <div class="flex items-center"><img src="{{ asset('img/icono_archivo.png') }}" alt="Materiales" class="w-5 h-5 mr-3 object-contain"> Materiales</div>
                        <span id="mobMatArr" class="transform transition-transform duration-200 text-xs text-gray-400">▼</span>
                    </button>
                    <div id="mobMatSub" class="hidden bg-white border-t border-gray-100">
                         <div class="grid grid-cols-2 gap-2 p-2 max-h-40 overflow-y-auto">
                            @foreach($userMemberGroups as $group)
                                <a href="{{ route('groups.materials', $group->name) }}" class="block px-2 py-1.5 text-xs text-center bg-gray-50 rounded hover:bg-blue-50 border border-gray-100 truncate text-gray-600">{{ $group->display_name ?? ucfirst($group->name) }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if(Auth::user()->isAdmin())
                <a href="/admin" class="flex items-center w-full px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-400 transition">
                    <img src="{{ asset('img/icono_admin.png') }}" alt="Admin" class="w-5 h-5 mr-3 object-contain"> <span class="font-semibold text-sm">Panel de Administración</span>
                </a>
            @endif
        </div>

        <div class="p-2 border-t border-gray-200 bg-nav-footer">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex justify-center items-center px-4 py-2 bg-red-500  border border-red-200 text-white rounded-lg hover:bg-red-600 text-sm font-semibold transition">
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </div>
    @endauth
</div>

<script>
    function toggleAccordion(contentId, arrowId) {
        const content = document.getElementById(contentId);
        const arrow = document.getElementById(arrowId);
        if(content && arrow) {
            content.classList.toggle('hidden');
            arrow.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            if(arrow.textContent === '▸' || arrow.textContent === '▾') {
                arrow.textContent = content.classList.contains('hidden') ? '▸' : '▾';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerMenu = document.getElementById('hamburgerMenu');
        const mobileMenu = document.getElementById('mobileMenu');
        
        if (hamburgerMenu && mobileMenu) {
            hamburgerMenu.addEventListener('click', (e) => {
                e.stopPropagation();
                const isHidden = mobileMenu.classList.toggle('hidden');
                const l1=document.getElementById('line1'), l2=document.getElementById('line2'), l3=document.getElementById('line3');
                if(!isHidden) { 
                    l1.classList.add('rotate-45','translate-y-2'); l2.classList.add('opacity-0'); l3.classList.add('-rotate-45','-translate-y-2'); 
                } else { 
                    l1.classList.remove('rotate-45','translate-y-2'); l2.classList.remove('opacity-0'); l3.classList.remove('-rotate-45','-translate-y-2'); 
                }
            });
        }

        const pcGruposToggle = document.getElementById('pcGruposToggle');
        if(pcGruposToggle) {
            pcGruposToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleAccordion('pcGruposSubmenu', 'pcGruposArrow');
            });
        }

        const bottomMenuTrigger = document.getElementById('bottomMenuTrigger');
        const bottomMenuCard = document.getElementById('bottomMenuCard');
        const closeBottomMenu = document.getElementById('closeBottomMenu');

        if (bottomMenuTrigger && bottomMenuCard) {
            bottomMenuTrigger.addEventListener('click', (e) => {
                e.stopPropagation();
                bottomMenuCard.classList.toggle('hidden');
            });
            if(closeBottomMenu) {
                closeBottomMenu.addEventListener('click', (e) => {
                    e.stopPropagation();
                    bottomMenuCard.classList.add('hidden');
                });
            }
        }

        document.addEventListener('click', (event) => {
            if (mobileMenu && !mobileMenu.classList.contains('hidden') && !mobileMenu.contains(event.target) && !hamburgerMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
                document.getElementById('line1').classList.remove('rotate-45','translate-y-2');
                document.getElementById('line2').classList.remove('opacity-0');
                document.getElementById('line3').classList.remove('-rotate-45','-translate-y-2');
            }
            if (bottomMenuCard && !bottomMenuCard.classList.contains('hidden')) {
                if (!bottomMenuCard.contains(event.target) && !bottomMenuTrigger.contains(event.target)) {
                    bottomMenuCard.classList.add('hidden');
                }
            }
        });
    });
</script>
<nav class="bg-nav-footer py-5 sticky top-0 z-50 border-b-2 border-sky-400">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <div class="flex-1">
                <a href="{{ url('/') }}" class="inline-block">
                    <div class="h-15 w-38 border-dashed flex items-center justify-center m-auto">
                        <img src="{{ asset('img/logo_redonda_texto.png') }}" alt="logo_redonda" class="h-[70px]">
                    </div>
                </a>
            </div>

            <!-- Botón Donaciones -->
            <div class="mr-8">
                <a href="{{ url('/donaciones') }}" class="bg-button text-white px-6 py-2 rounded-lg font-semibold no-underline hover:bg-blue-500 transition-colors">
                    Donaciones
                </a>
            </div>

            <!-- Menú Hamburguesa CORREGIDO -->
            <button class="hamburger-menu relative w-8 h-8 flex flex-col items-center justify-center cursor-pointer z-50 focus:outline-none space-y-1.5" id="hamburgerMenu" type="button" aria-label="Menú principal">
                <!-- Línea 1 -->
                <span class="block w-6 h-0.5 bg-text-dark transition-all duration-300 transform" id="line1"></span>
                <!-- Línea 2 -->
                <span class="block w-6 h-0.5 bg-text-dark transition-all duration-300" id="line2"></span>
                <!-- Línea 3 -->
                <span class="block w-6 h-0.5 bg-text-dark transition-all duration-300 transform" id="line3"></span>
            </button>

            <!-- Menú Desplegable -->
            <div class="mobile-menu hidden absolute top-full right-0 bg-nav-footer w-64 shadow-lg rounded-bl-lg z-40 border-2 border-button" id="mobileMenu">
                <!-- Grupos - Versión Móvil -->
                <div class="border-b border-gray-200">
                    <button class="w-full text-left px-6 py-4 text-text-dark font-medium hover:bg-button hover:text-white hover:pl-8 transition-all cursor-pointer flex justify-between items-center" id="mobileGruposToggle" type="button">
                        <span>Grupos</span>
                        <span class="transform transition-transform duration-300" id="gruposArrow">▸</span>
                    </button>
                    <div class="mobile-submenu hidden bg-nav-footer transition-all duration-300" id="mobileGruposSubmenu">
                        <a href="{{ url('/grupos') }}" class="block px-10 py-3 text-text-dark no-underline text-sm hover:bg-button hover:text-white transition-all font-semibold">
                            Todos los Grupos
                        </a>
                        <a href="{{ url('/grupos/catequesis') }}" class="block px-10 py-3 text-text-dark no-underline text-sm hover:bg-button hover:text-white transition-all">
                            Catequesis
                        </a>
                        <a href="{{ url('/grupos/jovenes') }}" class="block px-10 py-3 text-text-dark no-underline text-sm hover:bg-button hover:text-white transition-all">
                           Jóvenes
                        </a>
                        <a href="{{ url('/grupos/mayores') }}" class="block px-10 py-3 text-text-dark no-underline text-sm hover:bg-button hover:text-white transition-all">
                            Mayores
                        </a>
                        <a href="{{ url('/grupos/especiales') }}" class="block px-10 py-3 text-text-dark no-underline text-sm hover:bg-button hover:text-white transition-all">
                            Más Grupos
                        </a>

                        <!-- Materiales de Grupos - Para todos los miembros -->
                        @auth
                            @if(Auth::user()->hasAnyRole(['catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor']) || Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                            <div class="border-t border-gray-200 mt-2 pt-2">
                                <button class="w-full text-left px-10 py-3 text-text-dark text-sm hover:bg-button hover:text-white transition-all cursor-pointer flex justify-between items-center" id="mobileMaterialesToggle" type="button">
                                    <span>Materiales</span>
                                    <span class="transform transition-transform duration-300" id="materialesArrow">▸</span>
                                </button>
                                <div class="mobile-submenu hidden bg-nav-footer transition-all duration-300 ml-4" id="mobileMaterialesSubmenu">
                                    @php
                                        $userMemberGroups = Auth::user()->roles->filter(function($role) {
                                            return in_array($role->name, ['catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor']);
                                        });

                                        // Si es admin o superadmin, mostrar todos los grupos
                                        if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
                                            $userMemberGroups = collect([
                                                (object)['name' => 'catequesis', 'display_name' => 'Catequesis'],
                                                (object)['name' => 'juveniles', 'display_name' => 'Juveniles'],
                                                (object)['name' => 'acutis', 'display_name' => 'Acutis'],
                                                (object)['name' => 'juan_pablo', 'display_name' => 'Juan Pablo'],
                                                (object)['name' => 'coro', 'display_name' => 'Coro'],
                                                (object)['name' => 'san_joaquin', 'display_name' => 'San Joaquín'],
                                                (object)['name' => 'santa_ana', 'display_name' => 'Santa Ana'],
                                                (object)['name' => 'ardillas', 'display_name' => 'Ardillas'],
                                                (object)['name' => 'costureras', 'display_name' => 'Costureras'],
                                                (object)['name' => 'misioneros', 'display_name' => 'Misioneros'],
                                                (object)['name' => 'caridad_comedor', 'display_name' => 'Caridad y Comedor']
                                            ]);
                                        }
                                    @endphp

                                    @foreach($userMemberGroups as $group)
                                        <a href="{{ route('groups.materials', $group->name) }}" class="block px-6 py-2 text-text-dark no-underline text-xs hover:bg-button hover:text-white transition-all">
                                            📁 {{ $group->display_name ?? $group->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endauth

                        <!-- Gestión de Grupos - Solo para AdminGrupoParroquial -->
                        @auth
                            @if(Auth::user()->hasRole('admin_grupo_parroquial') || Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                            <div class="border-t border-gray-200 mt-2 pt-2">
                                <button class="w-full text-left px-10 py-3 text-text-dark text-sm hover:bg-button hover:text-white transition-all cursor-pointer flex justify-between items-center" id="mobileGestionGruposToggle" type="button">
                                    <span>Gestión Grupos</span>
                                    <span class="transform transition-transform duration-300" id="gestionGruposArrow">▸</span>
                                </button>
                                <div class="mobile-submenu hidden bg-nav-footer transition-all duration-300 ml-4" id="mobileGestionGruposSubmenu">
                                    @php
                                        $userGroups = [];
                                        if (Auth::user()->hasRole('admin_grupo_parroquial')) {
                                            $userGroups = Auth::user()->roles->filter(function($role) {
                                                return in_array($role->name, ['catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor']);
                                            });
                                        }

                                        // Si es admin o superadmin, mostrar todos los grupos
                                        if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
                                            $userGroups = collect([
                                                (object)['name' => 'catequesis', 'display_name' => 'Catequesis'],
                                                (object)['name' => 'juveniles', 'display_name' => 'Juveniles'],
                                                (object)['name' => 'acutis', 'display_name' => 'Acutis'],
                                                (object)['name' => 'juan_pablo', 'display_name' => 'Juan Pablo'],
                                                (object)['name' => 'coro', 'display_name' => 'Coro'],
                                                (object)['name' => 'san_joaquin', 'display_name' => 'San Joaquín'],
                                                (object)['name' => 'santa_ana', 'display_name' => 'Santa Ana'],
                                                (object)['name' => 'ardillas', 'display_name' => 'Ardillas'],
                                                (object)['name' => 'costureras', 'display_name' => 'Costureras'],
                                                (object)['name' => 'misioneros', 'display_name' => 'Misioneros'],
                                                (object)['name' => 'caridad_comedor', 'display_name' => 'Caridad y Comedor']
                                            ]);
                                        }
                                    @endphp

                                    @foreach($userGroups as $group)
                                        <a href="{{ route('grupos.dashboard', $group->name) }}" class="block px-6 py-2 text-text-dark no-underline text-xs hover:bg-button hover:text-white transition-all">
                                            {{ $group->display_name ?? $group->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        @endauth
                    </div>
                </div>

                <a href="{{ url('/intenciones') }}" class="block px-6 py-4 text-text-dark no-underline border-b border-gray-200 font-medium hover:bg-button hover:text-white hover:pl-8 transition-all">
                    Intenciones
                </a>

                <!-- Diario La Redonda - Solo para usuarios autorizados -->
                @auth
                    @if(Auth::user()->hasAnyRole(['admin_grupo_parroquial', 'catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor']) || Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                        <a href="{{ route('diario.index') }}" class="block px-6 py-4 text-text-dark no-underline border-b border-gray-200 font-medium hover:bg-button hover:text-white hover:pl-8 transition-all">
                            Diario La Redonda
                        </a>
                    @endif
                @endauth
                <!-- Sección de autenticación -->
                <div class="p-4 border-t border-gray-300">
                    @auth
                        <!-- Usuario logueado -->
                        <div class="space-y-3">
                            <!-- Información del usuario logueado con enlace al perfil -->
                            <a href="{{ url('/perfil') }}" class="block text-center mb-4 p-3 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors no-underline">
                                <p class="text-sm font-semibold text-text-dark">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-text-light">{{ Auth::user()->email }}</p>
                                @if(Auth::user()->isAdmin())
                                    <span class="inline-block mt-1 px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Administrador</span>
                                @else
                                    <span class="inline-block mt-1 px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">Usuario</span>
                                @endif
                            </a>

                            @if(Auth::user()->isAdmin())
                                <a href="/admin" class="block bg-yellow-500 text-white px-4 py-2 rounded-lg font-semibold no-underline text-center hover:bg-yellow-600 transition-colors">
                                    Panel Admin
                                </a>
                            @endif

                            <!-- Botón para ir al perfil -->
                            <a href="{{ url('/perfil') }}" class="block bg-button text-white px-4 py-2 rounded-lg font-semibold no-underline text-center hover:bg-blue-500 transition-colors">
                                Mi Perfil
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="inline-block w-full">
                                @csrf
                                <button type="submit" class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg font-semibold no-underline text-center hover:bg-gray-600 transition-colors">
                                    Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    @else
                        <!-- Usuario no logueado -->
                        <div class="space-y-3">
                            <a href="/login" class="block bg-button text-white px-6 py-2 rounded-lg font-semibold no-underline text-center hover:bg-blue-500 transition-colors">
                                Iniciar Sesión
                            </a>
                            <a href="/register" class="block bg-imprimir text-white px-6 py-2 rounded-lg font-semibold no-underline text-center hover:bg-indigo-300 transition-colors">
                                Registrarse
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerMenu = document.getElementById('hamburgerMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileGruposToggle = document.getElementById('mobileGruposToggle');
    const mobileGruposSubmenu = document.getElementById('mobileGruposSubmenu');
    const gruposArrow = document.getElementById('gruposArrow');

    // Nuevos elementos para gestión de grupos
    const mobileGestionGruposToggle = document.getElementById('mobileGestionGruposToggle');
    const mobileGestionGruposSubmenu = document.getElementById('mobileGestionGruposSubmenu');
    const gestionGruposArrow = document.getElementById('gestionGruposArrow');

    const mobileMaterialesToggle = document.getElementById('mobileMaterialesToggle');
    const mobileMaterialesSubmenu = document.getElementById('mobileMaterialesSubmenu');
    const materialesArrow = document.getElementById('materialesArrow');

    // Elementos de las líneas del hamburguesa
    const line1 = document.getElementById('line1');
    const line2 = document.getElementById('line2');
    const line3 = document.getElementById('line3');

    if (hamburgerMenu && mobileMenu) {
        // Toggle menú principal
        hamburgerMenu.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = mobileMenu.classList.toggle('hidden');

            // Animación del hamburguesa a X
            if (isHidden) {
                // Estado: Hamburguesa (volver a estado normal)
                line1.classList.remove('rotate-45', 'translate-y-2');
                line2.classList.remove('opacity-0');
                line3.classList.remove('-rotate-45', '-translate-y-2');
            } else {
                // Estado: X
                line1.classList.add('rotate-45', 'translate-y-2');
                line2.classList.add('opacity-0');
                line3.classList.add('-rotate-45', '-translate-y-2');
            }
        });

        // Toggle submenú de grupos en móvil
        if (mobileGruposToggle && mobileGruposSubmenu) {
            mobileGruposToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = mobileGruposSubmenu.classList.toggle('hidden');

                // Animación de la flecha
                if (isHidden) {
                    gruposArrow.textContent = '▸';
                    gruposArrow.classList.remove('rotate-90');
                } else {
                    gruposArrow.textContent = '▾';
                    gruposArrow.classList.add('rotate-90');
                }
            });
        }

        // Toggle submenú de gestión de grupos
        if (mobileGestionGruposToggle && mobileGestionGruposSubmenu) {
            mobileGestionGruposToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = mobileGestionGruposSubmenu.classList.toggle('hidden');

                // Animación de la flecha
                if (isHidden) {
                    gestionGruposArrow.textContent = '▸';
                    gestionGruposArrow.classList.remove('rotate-90');
                } else {
                    gestionGruposArrow.textContent = '▾';
                    gestionGruposArrow.classList.add('rotate-90');
                }
            });
        }

        // Toggle submenú de materiales
        if (mobileMaterialesToggle && mobileMaterialesSubmenu) {
            mobileMaterialesToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = mobileMaterialesSubmenu.classList.toggle('hidden');

                // Animación de la flecha
                if (isHidden) {
                    materialesArrow.textContent = '▸';
                    materialesArrow.classList.remove('rotate-90');
                } else {
                    materialesArrow.textContent = '▾';
                    materialesArrow.classList.add('rotate-90');
                }
            });
        }

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function(event) {
            const isClickInsideHamburger = hamburgerMenu.contains(event.target);
            const isClickInsideMenu = mobileMenu.contains(event.target);

            if (!isClickInsideHamburger && !isClickInsideMenu && !mobileMenu.classList.contains('hidden')) {
                closeAllMenus();
            }
        });

        // Cerrar con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                closeAllMenus();
            }
        });

        // Función para cerrar todos los menús
        function closeAllMenus() {
            mobileMenu.classList.add('hidden');
            if (mobileGruposSubmenu) {
                mobileGruposSubmenu.classList.add('hidden');
            }
            if (mobileGestionGruposSubmenu) {
                mobileGestionGruposSubmenu.classList.add('hidden');
            }
            if (mobileMaterialesSubmenu) {
                mobileMaterialesSubmenu.classList.add('hidden');
            }

            // Resetear hamburguesa a estado normal
            line1.classList.remove('rotate-45', 'translate-y-2');
            line2.classList.remove('opacity-0');
            line3.classList.remove('-rotate-45', '-translate-y-2');

            // Resetear flechas
            if (gruposArrow) {
                gruposArrow.textContent = '▸';
                gruposArrow.classList.remove('rotate-90');
            }
            if (gestionGruposArrow) {
                gestionGruposArrow.textContent = '▸';
                gestionGruposArrow.classList.remove('rotate-90');
            }
            if (materialesArrow) {
                materialesArrow.textContent = '▸';
                materialesArrow.classList.remove('rotate-90');
            }
        }

        // Prevenir que el menú se cierre al hacer clic dentro de él
        mobileMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });

    } else {
        console.error('Elementos del menú no encontrados');
    }
});
</script>

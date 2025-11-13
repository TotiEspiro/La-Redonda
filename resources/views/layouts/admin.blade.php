<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - La Redonda Joven</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo_redonda.png') }}">
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
</head>
<body class="font-poppins bg-gray-100">
    <!-- Navbar Admin -->
   <nav class="bg-nav-footer py-7 sticky top-0 z-50 border-b-2 border-sky-400">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-2">
            <!-- Logo y título -->
            <div class="flex items-center">
                <h1 class="text-xl font-semibold text-black">Panel de Administración</h1>
            </div>
            <!-- Botón menú hamburguesa CORREGIDO -->
            <button class="hamburger-menu relative w-8 h-8 flex flex-col items-center justify-center cursor-pointer z-50 focus:outline-none space-y-1.5" id="hamburgerMenu" type="button" aria-label="Menú de administración">
                <!-- Línea 1 -->
                <span class="block w-6 h-0.5 bg-black transition-all duration-300 transform" id="line1"></span>
                <!-- Línea 2 -->
                <span class="block w-6 h-0.5 bg-black transition-all duration-300" id="line2"></span>
                <!-- Línea 3 -->
                <span class="block w-6 h-0.5 bg-black transition-all duration-300 transform" id="line3"></span>
            </button>
        </div>

        <!-- Menú móvil (oculto por defecto) -->
        <div class="mobile-menu hidden absolute top-full right-0 bg-nav-footer w-80 max-w-full shadow-lg rounded-bl-lg z-40 border-2 border-button overflow-y-auto" id="mobileMenu">
            <div class="flex flex-col space-y-1 p-4">
                <!-- Enlaces de navegación -->
                <a href="{{ route('admin.dashboard') }}" class="block px-6 py-4 text-text-dark no-underline border-b border-gray-200 font-medium hover:bg-button hover:text-white hover:pl-8 transition-all">
                    Inicio
                </a>
                <a href="{{ route('admin.users') }}" class="block px-6 py-4 text-text-dark no-underline border-b border-gray-200 font-medium hover:bg-button hover:text-white hover:pl-8 transition-all">
                    Usuarios
                </a>
                <a href="{{ route('admin.intentions') }}" class="block px-6 py-4 text-text-dark no-underline border-b border-gray-200 font-medium hover:bg-button hover:text-white hover:pl-8 transition-all">
                    Intenciones
                </a>
                <a href="{{ route('admin.donations') }}" class="block px-6 py-4 text-text-dark no-underline border-b border-gray-200 font-medium hover:bg-button hover:text-white hover:pl-8 transition-all">
                    Donaciones
                </a>
                <a href="{{ route('admin.announcements.index') }}" class="block px-6 py-4 text-text-dark no-underline border-b border-gray-200 font-medium hover:bg-button hover:text-white hover:pl-8 transition-all">
                    Anuncios
                </a>
                <a href="{{ route('admin.evangelio-diario.editar') }}" class="block px-6 py-4 text-text-dark no-underline border-b border-gray-200 font-medium hover:bg-button hover:text-white hover:pl-8 transition-all">
                    Evangelio del Día
                </a>
                <!-- Separador -->
                <div class="border-t border-gray-300 pt-4 mt-4">      
                    <!-- Botones de acción -->
                    <div class="flex flex-col space-y-2">
                        <a href="/" class="flex items-center justify-center bg-button text-white py-3 px-4 rounded-lg hover:bg-blue-500 transition-colors font-semibold shadow-sm">
                            Ver Sitio Web
                        </a>
                        <a href="/logout" class="flex items-center justify-center bg-red-500 text-white py-3 px-4 rounded-lg hover:bg-red-600 transition-colors font-semibold shadow-sm">
                            Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


    <!-- Contenido -->
    <main class="max-w-7xl mx-auto py-4 md:py-6 px-3 md:px-4">
        <!-- Mensajes -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 md:mb-6 text-sm md:text-base">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 md:mb-6 text-sm md:text-base">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
    <footer class="bg-nav-footer p-6 mt-40">
        <div class="container max-w-7xl mx-auto px-4">
            <div class="copyright text-center text-text-light mb-4 md:mb-0">
                &copy; 2025 La Redonda Joven - Inmaculada Concepción de Belgrano
            </div>
        </div>
    </footer>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hamburgerMenu = document.getElementById('hamburgerMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    
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
            
            // Resetear hamburguesa a estado normal
            line1.classList.remove('rotate-45', 'translate-y-2');
            line2.classList.remove('opacity-0');
            line3.classList.remove('-rotate-45', '-translate-y-2');
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

</body>
</html>
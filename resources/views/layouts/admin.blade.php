<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - La Redonda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo_nav_redonda.png') }}">
    <link rel="stylesheet" href="/css/app.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    
    @stack('styles')
</head>
<body class="font-poppins bg-gray-100 flex flex-col min-h-screen">
    <nav class="bg-nav-footer py-7 sticky top-0 z-50 border-b-2 border-sky-400">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <img class="block h-10 w-auto mr-3 md:h-14" src="{{ asset('img/logo_redonda_texto_vertical.png') }}" alt="Logo Móvil">
                    <h1 class="text-lg font-semibold text-black md:text-xl">Panel de Administración</h1>
                </div>
                
                <button class="hamburger-menu relative w-8 h-8 flex flex-col items-center justify-center cursor-pointer z-50 focus:outline-none space-y-1.5" id="hamburgerMenu" type="button">
                    <span class="block w-6 h-0.5 bg-black transition-all duration-300 transform" id="line1"></span>
                    <span class="block w-6 h-0.5 bg-black transition-all duration-300" id="line2"></span>
                    <span class="block w-6 h-0.5 bg-black transition-all duration-300 transform" id="line3"></span>
                </button>
            </div>

            <div class="mobile-menu hidden absolute top-full right-0 bg-nav-footer w-80 max-w-full shadow-lg rounded-bl-lg z-40 border-2 border-button" id="mobileMenu">
                <div class="flex flex-col space-y-1 p-4">
                    <a href="{{ route('admin.dashboard') }}" class="block px-6 py-4 text-text-dark font-medium hover:bg-button hover:text-white rounded transition-colors">Inicio</a>
                    <a href="{{ route('admin.users') }}" class="block px-6 py-4 text-text-dark font-medium hover:bg-button hover:text-white rounded transition-colors">Usuarios</a>
                    <a href="{{ route('admin.intentions') }}" class="block px-6 py-4 text-text-dark font-medium hover:bg-button hover:text-white rounded transition-colors">Intenciones</a>
                    <a href="{{ route('admin.donations') }}" class="block px-6 py-4 text-text-dark font-medium hover:bg-button hover:text-white rounded transition-colors">Donaciones</a>
                    <a href="{{ route('admin.announcements.index') }}" class="block px-6 py-4 text-text-dark font-medium hover:bg-button hover:text-white rounded transition-colors">Anuncios</a>
                    <a href="{{ route('admin.evangelio-diario.editar') }}" class="block px-6 py-4 text-text-dark font-medium hover:bg-button hover:text-white rounded transition-colors">Evangelio del Día</a>
                    
                    <div class="border-t border-gray-300 pt-4 mt-4 space-y-2">      
                        <a href="/" class="block text-center bg-button text-white py-2 px-4 rounded hover:bg-blue-500 transition-colors">Ver Sitio Web</a>
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit" class="w-full bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition-colors">Cerrar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 flex-grow w-full">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-nav-footer p-6 mt-auto">
        <div class="container max-w-7xl mx-auto px-4 text-center text-text-light">
            &copy; 2025 La Redonda - Inmaculada Concepción de Belgrano
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerMenu = document.getElementById('hamburgerMenu');
            const mobileMenu = document.getElementById('mobileMenu');
            const lines = [document.getElementById('line1'), document.getElementById('line2'), document.getElementById('line3')];

            if (hamburgerMenu && mobileMenu) {
                hamburgerMenu.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = mobileMenu.classList.toggle('hidden');
                    if (isHidden) {
                        lines[0].classList.remove('rotate-45', 'translate-y-2');
                        lines[1].classList.remove('opacity-0');
                        lines[2].classList.remove('-rotate-45', '-translate-y-2');
                    } else {
                        lines[0].classList.add('rotate-45', 'translate-y-2');
                        lines[1].classList.add('opacity-0');
                        lines[2].classList.add('-rotate-45', '-translate-y-2');
                    }
                });
                
                document.addEventListener('click', (e) => {
                    if (!hamburgerMenu.contains(e.target) && !mobileMenu.contains(e.target)) {
                        mobileMenu.classList.add('hidden');
                        lines[0].classList.remove('rotate-45', 'translate-y-2');
                        lines[1].classList.remove('opacity-0');
                        lines[2].classList.remove('-rotate-45', '-translate-y-2');
                    }
                });
            }
        });
    </script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    
    @stack('scripts')
</body>
</html>
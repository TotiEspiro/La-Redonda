<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Redonda Joven - Inmaculada Concepción de Belgrano</title>

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#5cb1e3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <link rel="icon" type="image/x-icon" href="{{ asset('img/logo_redonda.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nav-footer': '#a4e0f3',
                        'button': '#5cb1e3',
                        'text-dark': '#333333',
                        'text-light': '#666666',
                        'background-light': '#f9f9f9',
                        'gospel-bg': '#a4e0f3',
                        'imprimir': '#8facd6',
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- Fuentes Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#5cb1e3">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="La Redonda Joven">
    <meta name="description" content="Aplicación de la Iglesia La Redonda Joven">
    <meta name="keywords" content="iglesia, católica, comunidad, fe, jóvenes">
    <meta name="mobile-web-app-capable" content="yes">
 

    <style>
        .fade-out {
            opacity: 0;
            transition: opacity 0.5s ease-out;
        }
    </style>
</head>
<body class="font-poppins font-normal leading-relaxed text-text-dark">

    <!-- Navbar -->
    @include('partials.nav')

    <!-- Contenido Principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Scripts -->
    <script src="../js/home.js"></script>

</body>
</html>
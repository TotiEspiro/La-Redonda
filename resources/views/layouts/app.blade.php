<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Redonda | Inmaculada Concepción de Belgrano</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="vapid-pub" content="{{ env('VAPID_PUBLIC_KEY') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" href="{{ asset('img/logo_nav_redonda.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nav-footer': '#a4e0f3',
                        'button': '#5cb1e3',
                        'text-dark': '#333333',
                        'text-light': '#666666',
                        'gospel-bg': '#a4e0f3',
                    },
                    fontFamily: { 'poppins': ['Poppins', 'sans-serif'] },
                }
            }
        }
    </script>
    @yield('head')
</head>
<body class="font-poppins">

    @include('partials.nav')
    <main>@yield('content')</main>
    @include('partials.footer')

    @auth
    <script src="{{ asset('js/push-manager.js') }}"></script>
    <script>
        // SCRIPT PARA PC (Polling cada 30 seg)
        async function checkPCNotifications() {
            if (Notification.permission !== "granted") return;
            try {
                const res = await fetch('{{ route("notifications.unread-count") }}');
                const data = await res.json();

                if (data.count > 0 && data.latest) {
                    const lastId = localStorage.getItem('last_notif_id');
                    if (lastId != data.latest.id) {
                        // Laravel guarda el contenido en el campo 'data' como JSON
                        const content = typeof data.latest.data === 'string' ? JSON.parse(data.latest.data) : data.latest.data;
                        
                        new Notification(content.title || "La Redonda", {
                            body: content.message,
                            icon: "{{ asset('img/logo_notificacion_redonda.png') }}"
                        }).onclick = () => { window.focus(); location.href = content.link || '#'; };

                        localStorage.setItem('last_notif_id', data.latest.id);
                    }
                }
            } catch (e) {}
        }
        setInterval(checkPCNotifications, 30000);
    </script>
    @endauth

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(reg => {
                    @auth 
                        setTimeout(() => { if(window.subscribeUserToPush) window.subscribeUserToPush(); }, 2000);
                    @endauth
                });
            });
        }
    </script>
</body>
</html>
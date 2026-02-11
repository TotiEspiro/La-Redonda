const CACHE_NAME = 'la-redonda-pwa';
const ASSETS = ['/', '/manifest.json', '/img/logo_notificacion_redonda.png', '/img/badge_logo_redonda.png'];

self.addEventListener('install', event => {
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(self.clients.claim());
});

// ESCUCHA DE NOTIFICACIONES PUSH
self.addEventListener('push', function(event) {

    // 1. Verificación de Permisos
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    // 2. Preparación de datos por defecto (Fallback)
    let data = {
        title: 'La Redonda Joven',
        body: 'Nueva actividad en la comunidad.',
        link: '/dashboard',
        icon: '/img/logo_notificacion_redonda.png',
        badge: '/img/badge_logo_redonda.png' 
    };

    // 3. Procesamiento del payload
    if (event.data) {
        try {
            const payload = event.data.json();
            data.title = payload.title || data.title;
            data.body = payload.body || payload.message || data.body;
            
            data.link = payload.link || (payload.data ? payload.data.url : null) || payload.action_url || data.link;
            
            data.icon = payload.icon || data.icon;
            data.badge = payload.badge || data.badge;

        } catch (e) {
            data.body = event.data.text();
        }
    }

    // 4. Configuración de la notificación
    const options = {
        body: data.body,
        icon: data.icon, 
        badge: data.badge, 
        vibrate: [300, 100, 300],
        tag: 'laredonda-notif', 
        renotify: true,
        data: {
            url: data.link
        }
    };

    // 5. Mostrar la notificación
    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    
    const targetUrl = event.notification.data.url;

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(clientList) {
            for (let i = 0; i < clientList.length; i++) {
                let client = clientList[i];
                if (client.url.includes(targetUrl) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
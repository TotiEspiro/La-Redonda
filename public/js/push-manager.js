/**
 * Este script se encarga de pedir permiso al usuario y 
 * vincular su navegador/celular con Laravel.
 */
window.subscribeUserToPush = async function() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        return;
    }

    try {
        const registration = await navigator.serviceWorker.ready;
        
        // Obtenemos la llave pública VAPID del meta tag que pusimos en el layout
        const vapidPublicKey = document.querySelector('meta[name="vapid-pub"]').content;
        
        if (!vapidPublicKey) {
            return;
        }

        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: vapidPublicKey
        });

        // Enviamos la suscripción al servidor (AuthController@updatePushSubscription)
        const response = await fetch('/notifications/subscribe', {
            method: 'POST',
            body: JSON.stringify(subscription),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
        } else {
        }

    } catch (error) {
    }
};

// Solicitar permiso automáticamente al cargar si no se ha decidido
if (Notification.permission === 'default') {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            window.subscribeUserToPush();
        }
    });
} else if (Notification.permission === 'granted') {
    window.subscribeUserToPush();
}
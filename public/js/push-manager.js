/**
 * Este script se encarga de pedir permiso al usuario y 
 * vincular su navegador/celular con Laravel.
 */
window.subscribeUserToPush = async function() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        console.warn('Push notifications are not supported in this browser.');
        return;
    }

    try {
        const registration = await navigator.serviceWorker.ready;
        
        // Obtenemos la llave pública VAPID del meta tag
        const vapidPublicKey = document.querySelector('meta[name="vapid-pub"]').content;
        
        if (!vapidPublicKey) {
            console.error('VAPID public key not found in meta tags.');
            return;
        }

        // Función necesaria para convertir la llave base64 a Uint8Array
        const convertedKey = urlBase64ToUint8Array(vapidPublicKey);

        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: convertedKey
        });

        // Enviamos la suscripción al servidor
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
            console.log('Successfully subscribed to Push Notifications');
        }

    } catch (error) {
        console.error('Error during push subscription:', error);
    }
};

/**
 * Auxiliar para convertir la llave pública VAPID
 */
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

// Iniciar solicitud de permiso
if (Notification.permission === 'default') {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            window.subscribeUserToPush();
        }
    });
} else if (Notification.permission === 'granted') {
    window.subscribeUserToPush();
}
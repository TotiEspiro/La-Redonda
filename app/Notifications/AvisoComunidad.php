<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class AvisoComunidad extends Notification
{
    use Queueable;

    public $titulo;
    public $mensaje;
    public $url;

    /**
     * Crea una nueva instancia de notificación.
     * Se asegura que la URL nunca sea nula para evitar enlaces vacíos (#).
     */
    public function __construct($titulo, $mensaje, $url = null)
    {
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->url = $url ?? route('dashboard');
    }

    /**
     * Canales de envío.
     */
    public function via($notifiable)
    {
        // 'database' para la lista interna en la web y WebPushChannel para notificaciones de sistema
        return ['database', WebPushChannel::class];
    }

    /**
     * IMPORTANTE: Define los datos que se guardan en la tabla 'notifications'.
     * Estos campos son los que consume el componente de notificaciones en la página.
     */
    public function toArray($notifiable)
    {
        return [
            'title'   => $this->titulo,
            'message' => $this->mensaje,
            'url'     => $this->url, // Esto corrige el problema del '#' en la web
        ];
    }

    /**
     * Lógica para la notificación Push de navegador/sistema (Celulares/PC)
     */
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->titulo)
            ->icon('/img/logo_notificacion_redonda.png')
            ->badge('/img/badge_logo_redonda.png')
            ->body($this->mensaje)
            ->action('Ver ahora', 'view_app')
            ->data(['url' => $this->url]) // Envía la URL al Service Worker
            ->options(['TTL' => 1000]);
    }
}
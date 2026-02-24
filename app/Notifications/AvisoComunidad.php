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
        return ['database', WebPushChannel::class];
    }

    /**
     * Define los datos que se guardan en la tabla 'notifications'.
     */
    public function toArray($notifiable)
    {
        return [
            'title'   => $this->titulo,
            'message' => $this->mensaje,
            'url'     => $this->url,
        ];
    }

    /**
     * Lógica para la notificación de navegador/sistema (Celulares/PC)
     */
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->titulo)
            ->icon('/img/logo_notificacion_redonda.png')
            ->badge('/img/badge_logo_redonda.png')
            ->body($this->mensaje)
            ->action('Ver ahora', 'view_app')
            ->data(['url' => $this->url]) 
            ->options(['TTL' => 1000]);
    }
}
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

    public function __construct($titulo, $mensaje, $url = '#')
    {
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        // Agregamos WebPushChannel para que llegue al sistema operativo
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->titulo,
            'message' => $this->mensaje,
            'url' => $this->url,
        ];
    }

    /**
     * Lógica para la notificación Push de navegador/sistema
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
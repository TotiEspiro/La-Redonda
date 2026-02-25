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

    public function __construct($titulo, $mensaje, $url = null)
    {
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->url = $url ?? route('dashboard');
    }

    /**
     * AQUÍ SE DECIDE SI SALTA EN EL CELULAR:
     * Si 'notify_community' es false, solo se devuelve ['database'].
     */
    public function via($notifiable)
    {
        $channels = ['database'];

        // Si el usuario tiene activo el permiso, enviamos a WebPush (Celular/PC)
        if ($notifiable->notify_community) {
            $channels[] = WebPushChannel::class;
        }

        return $channels;
    }

    public function toArray($notifiable)
    {
        return [
            'title'   => $this->titulo,
            'message' => $this->mensaje,
            'url'     => $this->url,
        ];
    }

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
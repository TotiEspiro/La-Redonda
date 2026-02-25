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
     * VALIDACIÓN BLINDADA:
     * El canal 'database' (campanita web) es permanente.
     * El canal 'WebPushChannel' (celular/PC) solo se activa si la preferencia es estrictamente true.
     */
    public function via($notifiable)
    {
        $channels = ['database'];

        // Solo si el usuario tiene activo el permiso explícito enviamos a WebPush
        if ($notifiable->notify_community === true) {
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

    /**
     * Formato para notificación Push nativa con TTL optimizado.
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
            ->options(['TTL' => 60]);
    }
}
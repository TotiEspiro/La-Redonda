<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class NuevaIntencion extends Notification
{
    use Queueable;

    public $intencion;

    public function __construct($intencion)
    {
        $this->intencion = $intencion;
    }

    /**
     * VALIDACIÓN BLINDADA:
     * Si el usuario desactivó la opción, el canal WebPushChannel nunca se activa.
     */
    public function via($notifiable)
    {
        // Siempre guardamos en la base de datos (campanita web)
        $channels = ['database'];

        // Solo si la preferencia es estrictamente true enviamos el aviso al dispositivo
        if ($notifiable->notify_donations_intentions === true) {
            $channels[] = WebPushChannel::class;
        }

        return $channels;
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Nueva Intención Recibida',
            'message' => "Se ha registrado tu intención para la misa.",
            'url' => route('dashboard'),
            'type' => 'intention'
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Nueva Intención de Misa')
            ->icon('/img/icono_intenciones.png')
            ->badge('/img/badge_logo_redonda.png')
            ->body("¡Gracias! Tu intención ha sido recibida.")
            ->options(['TTL' => 60]);
    }
}
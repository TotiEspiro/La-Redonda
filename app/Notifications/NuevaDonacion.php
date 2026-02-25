<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class NuevaDonacion extends Notification
{
    use Queueable;

    public $donacion;

    public function __construct($donacion)
    {
        $this->donacion = $donacion;
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
            'title' => 'Nueva Donación',
            'message' => "Se ha recibido una donación de $" . number_format($this->donacion->amount, 2),
            'url' => route('admin.donations'),
            'type' => 'donation'
        ];
    }

    /**
     * Formato para notificación Push nativa
     */
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('¡Nueva Donación Recibida!')
            ->icon('/img/icono_donaciones_admin.png')
            ->badge('/img/badge_logo_redonda.png')
            ->body("Monto: $" . number_format($this->donacion->amount, 2))
            ->action('Ver detalle', 'view_donations')
            ->data(['url' => route('admin.donations')])
            ->options(['TTL' => 60]);
    }
}
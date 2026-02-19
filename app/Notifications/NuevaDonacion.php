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

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
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
            ->body("Monto: $" . number_format($this->donacion->amount, 2) . "\n¡Gracias por la generosidad!")
            ->action('Ver detalle', 'view_donations')
            ->data(['url' => route('admin.donations')])
            ->options(['TTL' => 1000]);
    }
}
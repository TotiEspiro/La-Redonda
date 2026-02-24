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

    public function via($notifiable)
    {
        // Envia a la base de datos y al canal push
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Nueva Intención Recibida',
            'message' => "{$this->intencion->name} ha dejado una intención: " . substr($this->intencion->message, 0, 50) . "...",
            'url' => route('admin.intentions'),
            'type' => 'intention'
        ];
    }

    /**
     * Formato para notificación Push nativa
     */
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Nueva Intención de Misa')
            ->icon('/img/icono_intenciones.png')
            ->badge('/img/badge_logo_redonda.png')
            ->body("De: {$this->intencion->name}\nTipo: {$this->intencion->type}")
            ->action('Revisar', 'view_intentions')
            ->data(['url' => route('admin.intentions')])
            ->options(['TTL' => 1000]);
    }
}
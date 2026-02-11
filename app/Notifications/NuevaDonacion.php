<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class NuevaDonacion extends Notification
{
    use Queueable;

    protected $donation;

    public function __construct($donation)
    {
        $this->donation = $donation;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => '¡Gracias por tu Donación!',
            'message' => 'Hemos recibido tu aporte de $' . number_format($this->donation->amount, 2),
            'link' => route('home'),
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Donación Confirmada')
            ->icon('/img/logo_notificacion_redonda.png')
            ->badge('/img/badge_logo_redonda.png')
            ->body('Muchas gracias por tu generosidad. Tu aporte ayuda a mantener nuestra comunidad activa.')
            ->options(['TTL' => 1000]);
    }
}
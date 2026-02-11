<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class NuevaIntencion extends Notification
{
    use Queueable;

    protected $intention;

    /**
     * Recibe el objeto de la intención creada.
     */
    public function __construct($intention)
    {
        $this->intention = $intention;
    }

    /**
     * Determina los canales de envío.
     */
    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    /**
     * Estructura para la base de datos (lo que lee el PC).
     */
    public function toArray($notifiable)
    {
        return [
            'title' => 'Intención Recibida',
            'message' => 'Tu petición "' . $this->intention->type . '" ha sido registrada correctamente.',
            'link' => route('profile.show'),
        ];
    }

    /**
     * Estructura para el Push (lo que hace vibrar el celular).
     */
    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('Intención Registrada')
            ->icon('/img/logo_notificacion_redonda.png')
            ->badge('/img/badge_logo_redonda.png')
            ->body('Tu petición de oración ha sido recibida. Rezaremos por ella en la próxima misa.')
            ->action('Ver Perfil', 'view_profile')
            ->options(['TTL' => 1000]);
    }
}
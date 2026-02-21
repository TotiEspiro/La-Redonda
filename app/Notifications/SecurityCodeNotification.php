<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SecurityCodeNotification extends Notification
{
    use Queueable;

    protected $code;

    /**
     * Recibe el código generado en el controlador.
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Definimos los canales. En este caso, solo vía mail.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Construcción del correo con el diseño de La Redonda.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Código de seguridad - La Redonda')
            ->greeting('¡Hola, ' . $notifiable->name . '!')
            ->line('Por seguridad, al no haber ingresado en más de una semana, necesitamos validar tu identidad.')
            ->line('Utilizá el siguiente código para completar tu acceso:')
            ->action($this->code, '#') 
            ->line('Este código fue generado automáticamente para proteger tu cuenta.')
            ->salutation('Comunidad Parroquial La Redonda');
    }
}
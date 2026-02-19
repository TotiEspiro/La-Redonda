<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * El token de restablecimiento de contraseña.
     *
     * @var string
     */
    public $token;

    /**
     * Crea una nueva instancia de notificación.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Determina los canales de notificación.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Construye el mensaje de correo electrónico.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Restablecer Contraseña - La Redonda Joven')
            ->greeting('¡Hola, ' . $notifiable->name . '!')
            ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta en nuestra comunidad.')
            ->action('Restablecer Contraseña', $url)
            ->line('Este enlace de restablecimiento expirará en 60 minutos.')
            ->line('Si no realizaste esta solicitud, no es necesario realizar ninguna otra acción.')
            ->salutation('¡Nos vemos pronto en la parroquia!');
    }
}
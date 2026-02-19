<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    /**
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Construye el mensaje usando la vista personalizada.
     */
    public function toMail($notifiable)
    {
        // Generamos la URL para el botón
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        /**
         * NOTA: Usamos 'emails.reset-password' (PLURAL) 
         * para que coincida con resources/views/emails/reset-password.blade.php
         */
        return (new MailMessage)
            ->subject('Restablecer Contraseña - La Redonda Joven')
            ->view('emails.reset-password', [
                'url' => $url,
                'user' => $notifiable
            ]);
    }
}
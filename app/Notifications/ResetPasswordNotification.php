<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // IMPORTANTE: Aquí apuntamos al diseño del mail, NO al de la carpeta auth
        return (new MailMessage)
            ->subject('Restablecer Contraseña - La Redonda')
            ->view('emails.email-reset', [
                'url' => $url,
                'user' => $notifiable
            ]);
    }
}
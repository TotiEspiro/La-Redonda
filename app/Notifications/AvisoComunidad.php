<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class AvisoComunidad extends Notification
{
    use Queueable;

    public $title;
    public $message;
    public $link;

    /**
     * Notificación genérica para avisos de grupos y comunidad.
     */
    public function __construct($title, $message, $link = null)
    {
        $this->title = $title;
        $this->message = $message;
        $this->link = $link;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'link' => $this->link ?? route('dashboard'),
        ];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title('' . $this->title)
            ->icon('/img/logo_notificacion_redonda.png')
            ->badge('/img/badge_logo_redonda.png')
            ->body($this->message)
            ->action('Abrir', 'open_url')
            ->options(['TTL' => 3600]);
    }
}
<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function createNotification($type, $title, $message, $data = null)
    {
        return Notification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'is_read' => false,
        ]);
    }

    public static function notifyNewIntention($intention)
    {
        return self::createNotification(
            'intention_created',
            'Nueva Intención de Oración',
            "Se ha recibido una nueva intención de {$intention->name}",
            [
                'intention_id' => $intention->id,
                'user_name' => $intention->name,
                'type' => $intention->type
            ]
        );
    }

    public static function notifyNewDonation($donation)
    {
        return self::createNotification(
            'donation_received', 
            'Nueva Donación Recibida',
            "Se ha recibido una donación de $ {$donation->amount} de {$donation->card_holder}",
            [
                'donation_id' => $donation->id,
                'amount' => $donation->amount,
                'donor_name' => $donation->card_holder
            ]
        );
    }

    public static function notifyNewUser($user)
    {
        return self::createNotification(
            'user_registered',
            'Nuevo Usuario Registrado',
            "El usuario {$user->name} se ha registrado en el sistema",
            [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'email' => $user->email
            ]
        );
    }

    public static function getUnreadCount()
    {
        return Notification::unread()->count();
    }

    public static function getRecentNotifications($limit = 5)
    {
        return Notification::latest()->limit($limit)->get();
    }
}
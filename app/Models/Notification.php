<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title', 
        'message',
        'is_read',
        'data'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
        'created_at' => 'datetime',
    ];

    // Scope para notificaciones no leídas
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Marcar como leída
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
}
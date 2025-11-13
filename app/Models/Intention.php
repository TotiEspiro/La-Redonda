<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intention extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'email',
        'message',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación con usuario (si quieres asociar intenciones a usuarios registrados)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope para filtrar por tipo
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Getter para tipo formateado
    public function getFormattedTypeAttribute()
    {
        $types = [
            'salud' => 'Salud',
            'intenciones' => 'Intenciones',
            'accion-gracias' => 'Acción de gracias',
            'difuntos' => 'Difuntos'
        ];

        return $types[$this->type] ?? $this->type;
    }
}
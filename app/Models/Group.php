<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     * CORRECCIÓN: Se añade 'group_password' para permitir que el controlador 
     * guarde la clave configurada por el admin.
     */
    protected $fillable = [
        'name',
        'category',
        'group_password', // <--- CAMBIO CRÍTICO: Ahora Laravel permitirá guardar este campo
        'description',
        'min_age',
        'max_age',
        'is_active',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'min_age' => 'integer',
        'max_age' => 'integer',
    ];

    /**
     * Relación con los materiales del grupo.
     */
    public function materials()
    {
        return $this->hasMany(GroupMaterial::class, 'group_role', 'category');
    }

    /**
     * Relación con los usuarios (miembros oficiales)
     * Utilizada para la tabla pivot si decides persistir la pertenencia.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * Los atributos que se pueden asignar masivamente.
     * Esto permite que el Seeder y los controladores guarden los datos.
     */
    protected $fillable = [
        'name',
        'category',
        'description',
        'min_age',
        'max_age',
        'is_active',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     * Ayuda a que 'is_active' siempre sea un booleano (true/false) 
     * y las edades sean números enteros.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'min_age' => 'integer',
        'max_age' => 'integer',
    ];

    /**
     * Relación con los materiales del grupo.
     * Aunque usamos 'group_role' en el controlador, esta relación 
     * permite acceder a los materiales desde el objeto Grupo.
     */
    public function materials()
    {
        return $this->hasMany(GroupMaterial::class, 'group_role', 'category');
    }
}
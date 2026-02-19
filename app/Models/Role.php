<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'display_name',
        'description',
    ];

    // Relación muchos a muchos con usuarios
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }
}
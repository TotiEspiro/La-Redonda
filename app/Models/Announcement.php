<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description',
        'full_description',
        'image',
        'modal_id',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Accessor para obtener la URL completa de la imagen
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        // Si ya es una URL completa, retornarla directamente
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        // Si es una ruta relativa, generar la URL completa
        return asset('storage/' . $this->image);
    }

    // Método para obtener la ruta original de la imagen (sin URL)
    public function getRawImagePath()
    {
        return $this->getRawOriginal('image');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Accesor para obtener la URL de la imagen.
     * Funciona automáticamente para Local y Supabase/S3.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        // Si ya es una URL completa (ej. de un seeder o link externo), la devuelve tal cual
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        
        return Storage::url($this->image);
    }

    /**
     * Obtiene la ruta pura guardada en la BD para procesos de borrado.
     */
    public function getRawImagePath()
    {
        return $this->getRawOriginal('image');
    }
}
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
     * Esta versión es más robusta para evitar el error de PortableVisibilityConverter.
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }
        
        // Si ya es una URL completa (ej. externa), la devuelve tal cual
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        try {
            // Intentamos obtener la URL mediante el driver oficial
            return Storage::disk(config('filesystems.default'))->url($this->image);
        } catch (\Exception $e) {
            // FALLBACK: Si el driver falla por falta de clases de S3, construimos la URL manualmente
            // Basándonos en la AWS_URL configurada en el .env
            $baseUrl = rtrim(config('filesystems.disks.s3.url'), '/');
            return $baseUrl . '/' . ltrim($this->image, '/');
        }
    }

    /**
     * Obtiene la ruta pura guardada en la BD para procesos de borrado físico.
     */
    public function getRawImagePath()
    {
        return $this->getRawOriginal('image');
    }
}
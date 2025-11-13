<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class DiarioEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type',
        'color',
        'is_favorite'
    ];

    protected $casts = [
        'is_favorite' => 'boolean'
    ];

    // Atributos que NO se guardan en la base, solo para uso interno
    protected $appends = ['title', 'content'];

    // Atributos que deben ser encriptados
    protected $encrypted = ['title', 'content'];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Mutator para título - encripta al guardar
    public function setTitleAttribute($value)
    {
        $this->attributes['title_encrypted'] = Crypt::encryptString($value);
    }

    // Accessor para título - desencripta al leer
    public function getTitleAttribute()
    {
        try {
            return Crypt::decryptString($this->attributes['title_encrypted'] ?? '');
        } catch (DecryptException $e) {
            return 'Título no disponible';
        }
    }

    // Mutator para contenido - encripta al guardar
    public function setContentAttribute($value)
    {
        $this->attributes['content_encrypted'] = Crypt::encryptString($value);
    }

    // Accessor para contenido - desencripta al leer
    public function getContentAttribute()
    {
        try {
            return Crypt::decryptString($this->attributes['content_encrypted'] ?? '');
        } catch (DecryptException $e) {
            return 'Contenido no disponible';
        }
    }

    // Scope para obtener solo las entradas del usuario autenticado
    public function scopeByUser($query, $userId = null)
    {
        return $query->where('user_id', $userId ?? auth()->id());
    }

    // Scope para entradas favoritas
    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    // Accesor para el tipo de entrada
    public function getTypeDisplayAttribute()
    {
        $types = [
            'texto' => '📝 Texto',
            'mapa_conceptual' => '🗺️ Mapa Conceptual',
            'lista' => '📋 Lista',
            'reflexion' => '💭 Reflexión'
        ];

        return $types[$this->type] ?? $this->type;
    }

    // Accesor para contenido truncado (para previews)
    public function getExcerptAttribute()
    {
        try {
            $content = Crypt::decryptString($this->attributes['content_encrypted'] ?? '');
            return Str::limit(strip_tags($content), 100);
        } catch (DecryptException $e) {
            return 'Contenido no disponible';
        }
    }

    // Verificar si la entrada pertenece al usuario
    public function belongsToUser($userId = null)
    {
        return $this->user_id === ($userId ?? auth()->id());
    }

    // Método para crear entrada de forma segura
    public static function createEncrypted(array $attributes = [])
    {
        $entry = new static($attributes);
        $entry->save();
        return $entry;
    }
}
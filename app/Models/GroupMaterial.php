<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'group_role',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer'
    ];

    // Relación con el usuario que subió el material
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope para materiales de un grupo específico
    public function scopeForGroup($query, $groupRole)
    {
        return $query->where('group_role', $groupRole)->where('is_active', true);
    }

    // Scope para materiales del usuario
    public function scopeByUser($query, $userId = null)
    {
        return $query->where('user_id', $userId ?? auth()->id());
    }

    // Accesor para el nombre del grupo
    public function getGroupNameAttribute()
    {
        $groupNames = [
            'catequesis' => 'Catequesis',
            'juveniles' => 'Juveniles',
            'acutis' => 'Acutis',
            'juan_pablo' => 'Juan Pablo',
            'coro' => 'Coro',
            'san_joaquin' => 'San Joaquín',
            'santa_ana' => 'Santa Ana',
            'ardillas' => 'Ardillas',
            'costureras' => 'Costureras',
            'misioneros' => 'Misioneros',
            'caridad_comedor' => 'Caridad y Comedor'
        ];

        return $groupNames[$this->group_role] ?? $this->group_role;
    }

    // Accesor para el icono según el tipo de archivo
    public function getFileIconAttribute()
    {
        $icons = [
            'pdf' => '📄',
            'doc' => '📝',
            'docx' => '📝',
            'xls' => '📊',
            'xlsx' => '📊',
            'ppt' => '📽️',
            'pptx' => '📽️',
            'image' => '🖼️',
            'video' => '🎬',
            'audio' => '🎵',
            'zip' => '📦'
        ];

        return $icons[$this->file_type] ?? '📎';
    }

    // Accesor para tamaño formateado
    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
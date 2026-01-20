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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForGroup($query, $groupRole)
    {
        return $query->where('group_role', $groupRole)->where('is_active', true);
    }

    public function scopeByUser($query, $userId = null)
    {
        return $query->where('user_id', $userId ?? auth()->id());
    }

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

    public function getFileIconAttribute()
    {
        $basePath = 'img/'; 
        
        // Mapeo de tipos a nombres de archivo de imagen
        $icons = [
            'pdf' => 'icono_docs.png',
            
            // Microsoft Office
            'doc' => 'icono_docs.png', 
            'docx' => 'word.png',
            'xls' => 'excel.png', 
            'xlsx' => 'excel.png',
            'ppt' => 'icono_docs.png', 
            'pptx' => 'powerpoint.png',
            
            // Multimedia
            'image' => 'image.png',
            'video' => 'icono_video.png',
            'audio' => 'audio.png',
            
            // Otros
            'zip' => 'icono_archivo.png'
        ];

        // Obtiene el nombre del archivo o usa default.png
        $filename = $icons[$this->file_type] ?? 'default.png';
        
        return $basePath . $filename;
    }

    // Tipos permitidos para vista previa
    public function getCanPreviewAttribute()
    {
        return in_array($this->file_type, [
            'image', 'video', 'audio', 'pdf', 
            'doc', 'xls', 'ppt', 
            'jpg', 'jpeg', 'png', 'gif', 'webp',
            'mp4', 'webm', 'mp3', 'wav',
            'docx', 'xlsx', 'pptx'
        ]);
    }

    public function getFileSizeFormattedAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
        elseif ($bytes >= 1048576) return number_format($bytes / 1048576, 2) . ' MB';
        elseif ($bytes >= 1024) return number_format($bytes / 1024, 2) . ' KB';
        else return $bytes . ' bytes';
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Accesor para obtener la URL pública del archivo.
     * Soporta local y Supabase automáticamente.
     */
    public function getFileUrlAttribute()
    {
        if (!$this->file_path) return null;
        
        try {
            return Storage::disk(config('filesystems.default'))->url($this->file_path);
        } catch (\Exception $e) {
            // Fallback manual para Supabase si el driver tiene errores de visibilidad
            $baseUrl = rtrim(config('filesystems.disks.s3.url'), '/');
            return $baseUrl . '/' . ltrim($this->file_path, '/');
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForGroup($query, $groupRole)
    {
        return $query->where('group_role', $groupRole)->where('is_active', true);
    }

    public function getFileIconAttribute()
    {
        $basePath = 'img/'; 
        
        $icons = [
            'pdf' => 'icono_pdf.png',
            'doc' => 'icono_docs.png', 
            'docx' => 'word.png',
            'xls' => 'excel.png', 
            'xlsx' => 'excel.png',
            'ppt' => 'icono_docs.png', 
            'pptx' => 'powerpoint.png',
            'image' => 'icono_imagen.png',
            'jpg' => 'icono_imagen.png',
            'jpeg' => 'icono_imagen.png',
            'png' => 'icono_imagen.png',
            'video' => 'icono_video.png',
            'mp4' => 'icono_video.png',
            'audio' => 'icono_audio.png',
            'mp3' => 'icono_audio.png',
            'zip' => 'icono_archivo.png'
        ];

        $filename = $icons[strtolower($this->file_type)] ?? 'icono_docs.png';
        return $basePath . $filename;
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
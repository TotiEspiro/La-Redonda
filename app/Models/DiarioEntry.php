<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class);
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
            'texto' => 'Texto',
            'mapa_conceptual' => 'Mapa Conceptual',
            'lista' => 'Lista',
            'reflexion' => 'Reflexión'
        ];

        return $types[$this->type] ?? $this->type;
    }

    // Accesor para contenido truncado (para previews) 
    public function getExcerptAttribute()
    {
        $content = $this->content;
        
        if ($this->type === 'texto' || $this->type === 'reflexion') {
            $cleanContent = strip_tags($content);
            return Str::limit($cleanContent, 100) ?: 'Sin contenido';
        }
        
        if ($this->type === 'lista') {
            try {
                if ($this->isJson($content)) {
                    $tasks = json_decode($content, true);
                    if (is_array($tasks) && count($tasks) > 0) {
                        $totalTasks = count($tasks);
                        $completed = count(array_filter($tasks, function($task) {
                            return ($task['completed'] ?? false) === true;
                        }));
                        return " {$completed}/{$totalTasks} tareas completadas";
                    }
                }
            } catch (\Exception $e) {
            }
            return 'Lista de tareas';
        }
        
        // Si es mapa conceptual, mostrar información de nodos
        if ($this->type === 'mapa_conceptual') {
            try {
                if ($this->isJson($content)) {
                    $mapData = json_decode($content, true);
                    if (is_array($mapData) && isset($mapData['nodes']) && count($mapData['nodes']) > 0) {
                        $nodeCount = count($mapData['nodes']);
                        return " Mapa con {$nodeCount} nodos";
                    }
                }
            } catch (\Exception $e) {
            }
            return ' Mapa conceptual';
        }
        
        // Fallback para tipos desconocidos o contenido inválido
        return 'Contenido';
    }

    // Método auxiliar para verificar si es JSON válido
    private function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    // Verificar si la entrada pertenece al usuario
    public function belongsToUser($userId = null)
    {
        return $this->user_id === ($userId ?? auth()->id());
    }
}
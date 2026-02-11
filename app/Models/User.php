<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use Notifiable, HasPushSubscriptions;

    protected $fillable = [
        'name', 'email', 'password', 'diario_data', 'last_diario_entry', 
        'age', 'onboarding_completed', 'notify_announcements'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_diario_entry' => 'datetime',
        'onboarding_completed' => 'boolean',
        'notify_announcements' => 'boolean',
    ];

    // =========================================================================
    // LÓGICA DE ROLES Y PERMISOS (Optimizado para Nav y Rendimiento)
    // =========================================================================

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    /**
     * Verifica si el usuario tiene un rol específico.
     * Usa 'contains' para aprovechar la carga en memoria y mejorar el Nav.
     */
    public function hasRole($roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }

    /**
     * Verifica si el usuario tiene al menos uno de los roles listados.
     */
    public function hasAnyRole($roles): bool
    {
        return $this->roles->whereIn('name', (array)$roles)->isNotEmpty();
    }

    /**
     * Verifica específicamente el rol de Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    /**
     * Verifica si es Administrador General o Super Administrador.
     */
    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['admin', 'superadmin']);
    }

    /**
     * Verifica si el usuario coordina un grupo o es admin general.
     */
    public function isAdminOfGroup($groupCategory): bool
    {
        $slug = str_replace('admin_', '', strtolower($groupCategory));
        return $this->hasRole('admin_' . $slug) || $this->isAdmin();
    }

    // =========================================================================
    // LÓGICA DEL DIARIO ESPIRITUAL
    // =========================================================================

    public function getDiarioDataAttribute($value)
    {
        if (!$value || $value === 'null' || $value === '[]') return [];
        return json_decode($value, true) ?: [];
    }

    public function setDiarioDataAttribute($value)
    {
        $this->attributes['diario_data'] = json_encode(is_array($value) ? $value : []);
    }

    public function canAccessDiario(): bool
    {
        // Los admins y miembros de grupos específicos pueden acceder
        $allowed = ['superadmin', 'admin', 'catequesis', 'juveniles', 'acutis', 'coro', 'misioneros'];
        return $this->hasAnyRole($allowed);
    }

    public function getDiarioEntries()
    {
        return collect($this->diario_data)->sortByDesc('created_at')->values()->all();
    }

    public function getDiarioEntry($entryId)
    {
        foreach ($this->diario_data as $entry) {
            if ($entry['id'] == $entryId) return $entry;
        }
        return null;
    }

    public function addDiarioEntry($data)
    {
        if (!$this->canAccessDiario()) throw new \Exception('No tiene permisos para usar el diario.');
        
        $diarioData = $this->diario_data;
        $entryId = count($diarioData) > 0 ? max(array_column($diarioData, 'id')) + 1 : 1;
        
        $entry = [
            'id' => $entryId,
            'title' => $data['title'] ?? '', 
            'content' => $data['content'] ?? '', 
            'type' => $data['type'] ?? 'texto',
            'color' => $data['color'] ?? '#3b82f6',
            'is_favorite' => (bool)($data['is_favorite'] ?? false),
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ];
        
        $diarioData[] = $entry;
        $this->diario_data = $diarioData;
        $this->last_diario_entry = now();
        $this->save();
        
        return $entry;
    }

    public function updateDiarioEntry($entryId, $data)
    {
        $diarioData = $this->diario_data;
        $updated = false;
        
        foreach ($diarioData as &$entry) {
            if ($entry['id'] == $entryId) {
                $entry['title'] = $data['title'] ?? $entry['title'];
                $entry['content'] = $data['content'] ?? $entry['content'];
                $entry['type'] = $data['type'] ?? $entry['type'];
                $entry['color'] = $data['color'] ?? $entry['color'];
                $entry['is_favorite'] = isset($data['is_favorite']) ? (bool)$data['is_favorite'] : (bool)$entry['is_favorite'];
                $entry['updated_at'] = now()->toDateTimeString();
                $updated = true;
                break;
            }
        }
        
        if ($updated) {
            $this->diario_data = $diarioData;
            $this->save();
            return true;
        }
        return false;
    }

    public function deleteDiarioEntry($entryId)
    {
        $diarioData = $this->diario_data;
        $newData = array_filter($diarioData, fn($e) => $e['id'] != $entryId);
        
        if (count($diarioData) !== count($newData)) {
            $this->diario_data = array_values($newData);
            $this->save();
            return true;
        }
        return false;
    }

    public function getFavoriteDiarioEntries()
    {
        return collect($this->diario_data)
            ->filter(fn($e) => ($e['is_favorite'] ?? false))
            ->sortByDesc('created_at')
            ->values()
            ->all();
    }

    public function searchDiarioEntries($query)
    {
        $q = strtolower($query);
        return collect($this->diario_data)->filter(function($e) use ($q) {
            return str_contains(strtolower($e['title'] ?? ''), $q) || 
                   str_contains(strtolower($e['content'] ?? ''), $q);
        })->sortByDesc('created_at')->values()->all();
    }
}
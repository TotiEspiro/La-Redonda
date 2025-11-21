<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'diario_data',
        'last_diario_entry',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_diario_entry' => 'datetime',
    ];

    // ========== MÉTODOS PARA DIARIO ==========
    
    // Accesor para diario_data
    public function getDiarioDataAttribute($value)
    {
        if (!$value || $value === 'null' || $value === '[]') {
            return [];
        }
        
        try {
            $data = json_decode($value, true);
            return is_array($data) ? $data : [];
        } catch (\Exception $e) {
            return [];
        }
    }

    // Mutator para diario_data
    public function setDiarioDataAttribute($value)
    {
        $dataToStore = is_array($value) ? $value : [];
        $this->attributes['diario_data'] = json_encode($dataToStore);
    }

    // Método para agregar una nueva entrada al diario
    public function addDiarioEntry($data)
    {
        if (!$this->canAccessDiario()) {
            throw new \Exception('No tiene permisos para acceder al diario');
        }

        $diarioData = $this->diario_data;
        
        // Generar nuevo ID
        $entryId = count($diarioData) > 0 ? max(array_column($diarioData, 'id')) + 1 : 1;
        
        $entry = [
            'id' => $entryId,
            'title' => $data['title'] ?? '', // ✅ NO encriptar
            'content' => $data['content'] ?? '', // ✅ NO encriptar
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

    // Obtener una entrada específica del diario
    public function getDiarioEntry($entryId)
    {
        if (!$this->canAccessDiario()) {
            return null;
        }

        $diarioData = $this->diario_data;
        
        foreach ($diarioData as $entry) {
            if ($entry['id'] == $entryId) {
                return $entry;
            }
        }
        
        return null;
    }

    // Actualizar una entrada del diario
    public function updateDiarioEntry($entryId, $data)
    {
        if (!$this->canAccessDiario()) {
            return false;
        }

        $diarioData = $this->diario_data;
        $updated = false;
        
        foreach ($diarioData as &$entry) {
            if ($entry['id'] == $entryId) {
                $entry['title'] = $data['title'] ?? $entry['title']; // ✅ NO encriptar
                $entry['content'] = $data['content'] ?? $entry['content']; // ✅ NO encriptar
                $entry['type'] = $data['type'] ?? $entry['type'];
                $entry['color'] = $data['color'] ?? $entry['color'];
                $entry['is_favorite'] = (bool)($data['is_favorite'] ?? $entry['is_favorite']);
                $entry['updated_at'] = now()->toDateTimeString();
                $updated = true;
                break;
            }
        }
        
        if ($updated) {
            $this->diario_data = $diarioData;
            $this->last_diario_entry = now();
            $this->save();
            return true;
        }
        
        return false;
    }

    // Eliminar una entrada del diario
    public function deleteDiarioEntry($entryId)
    {
        if (!$this->canAccessDiario()) {
            return false;
        }

        $diarioData = $this->diario_data;
        $newData = [];
        $deleted = false;
        
        foreach ($diarioData as $entry) {
            if ($entry['id'] != $entryId) {
                $newData[] = $entry;
            } else {
                $deleted = true;
            }
        }
        
        if ($deleted) {
            $this->diario_data = array_values($newData);
            $this->save();
            return true;
        }
        
        return false;
    }

    // Obtener todas las entradas del diario
    public function getDiarioEntries($decryptTitles = true)
    {
        if (!$this->canAccessDiario()) {
            return [];
        }

        $entries = $this->diario_data;
        
        // Ordenar por fecha de creación (más reciente primero)
        usort($entries, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $entries;
    }

    // Obtener entradas favoritas
    public function getFavoriteDiarioEntries()
    {
        $entries = $this->getDiarioEntries();
        return array_filter($entries, function($entry) {
            return $entry['is_favorite'] === true;
        });
    }

    // Buscar entradas por término
    public function searchDiarioEntries($searchTerm)
    {
        if (!$this->canAccessDiario()) {
            return [];
        }

        $entries = $this->getDiarioEntries();
        $results = [];
        
        foreach ($entries as $entry) {
            $title = $entry['title'] ?? '';
            $content = $entry['content'] ?? '';
            
            if (stripos($title, $searchTerm) !== false || stripos($content, $searchTerm) !== false) {
                $results[] = $entry;
            }
        }
        
        return $results;
    }

    // ========== ROLES ==========
    
    // Relación muchos a muchos con roles
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    // Verificar si tiene un rol específico
    public function hasRole($roleName)
    {
        if (is_array($roleName)) {
            return $this->roles->whereIn('name', $roleName)->count() > 0;
        }
        
        return $this->roles->contains('name', $roleName);
    }

    // Verificar si tiene alguno de los roles
    public function hasAnyRole($roles)
    {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        return $this->roles->whereIn('name', $roles)->count() > 0;
    }

    // Asignar roles
    public function assignRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role && !$this->hasRole($roleName)) {
            $this->roles()->attach($role->id);
        }
    }

    // Remover rol
    public function removeRole($roleName)
    {
        $role = Role::where('name', $roleName)->first();
        if ($role && $this->hasRole($roleName)) {
            $this->roles()->detach($role->id);
        }
    }

    // Métodos específicos para verificar roles importantes
    public function isSuperAdmin()
    {
        return $this->hasRole('superadmin');
    }

    public function isAdmin()
    {
        return $this->hasRole('admin') || $this->isSuperAdmin();
    }

    public function isAdminGrupoParroquial()
    {
        return $this->hasRole('admin_grupo_parroquial') || $this->isAdmin();
    }

    public function isCatequesis()
    {
        return $this->hasRole('catequesis') || $this->isAdminGrupoParroquial();
    }

    public function isJuveniles()
    {
        return $this->hasRole('juveniles') || $this->isAdminGrupoParroquial();
    }

    public function isAcutis()
    {
        return $this->hasRole('acutis') || $this->isAdminGrupoParroquial();
    }

    public function isJuanPablo()
    {
        return $this->hasRole('juan_pablo') || $this->isAdminGrupoParroquial();
    }

    public function isCoro()
    {
        return $this->hasRole('coro') || $this->isAdminGrupoParroquial();
    }

    public function isSanJoaquin()
    {
        return $this->hasRole('san_joaquin') || $this->isAdminGrupoParroquial();
    }

    public function isSantaAna()
    {
        return $this->hasRole('santa_ana') || $this->isAdminGrupoParroquial();
    }

    public function isArdillas()
    {
        return $this->hasRole('ardillas') || $this->isAdminGrupoParroquial();
    }

    public function isCostureras()
    {
        return $this->hasRole('costureras') || $this->isAdminGrupoParroquial();
    }

    public function isMisioneros()
    {
        return $this->hasRole('misioneros') || $this->isAdminGrupoParroquial();
    }

    public function isCaridadComedor()
    {
        return $this->hasRole('caridad_comedor') || $this->isAdminGrupoParroquial();
    }

    public function isUser()
    {
        return $this->hasRole('user') || $this->roles->isEmpty();
    }

    // Obtener todos los nombres de roles
    public function getRoleNamesAttribute()
    {
        return $this->roles->pluck('display_name')->toArray();
    }

    // Obtener roles como string
    public function getRolesStringAttribute()
    {
        return implode(', ', $this->role_names);
    }

    // Verificar permisos
    public function canAccess($module)
    {
        $permissions = [
            'admin_panel' => ['superadmin', 'admin'],
            'grupos_management' => ['superadmin', 'admin', 'admin_grupo_parroquial'],
            'diario' => ['superadmin', 'admin', 'admin_grupo_parroquial', 'catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor'],
        ];

        return $this->hasAnyRole($permissions[$module] ?? []);
    }

    // Verificar si tiene acceso al diario
    public function canAccessDiario()
    {
        $diarioRoles = [
            'superadmin', 'admin', 'admin_grupo_parroquial', 'catequesis', 'juveniles', 
            'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 
            'costureras', 'misioneros', 'caridad_comedor'
        ];

        return $this->hasAnyRole($diarioRoles);
    }
}
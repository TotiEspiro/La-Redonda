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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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

    // Verificar permisos (puedes expandir esto según necesites)
    public function canAccess($module)
    {
        $permissions = [
            'admin_panel' => ['superadmin', 'admin'],
            'grupos_management' => ['superadmin', 'admin', 'admin_grupo_parroquial'],
            'diario' => ['superadmin', 'admin', 'admin_grupo_parroquial', 'catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor'],
            // Agregar más permisos según necesites
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
<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification; 
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasPushSubscriptions;

    protected $fillable = [
        'name', 'email', 'password', 'diario_data', 'last_diario_entry', 
        'age', 'onboarding_completed', 'notify_announcements',
        // Campos para Social Login (Google/Facebook)
        'provider_id', 'provider_name', 'avatar',
        // Campos de Seguridad y Validación
        'last_login_at', 'security_code'
    ];

    protected $hidden = ['password', 'remember_token', 'security_code'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_diario_entry' => 'datetime',
        'last_login_at' => 'datetime',
        'onboarding_completed' => 'boolean',
        'notify_announcements' => 'boolean',
    ];

    /**
     * SOBRESCRITURA DE NOTIFICACIÓN DE VERIFICACIÓN
     * Usa nuestro diseño personalizado de "La Redonda".
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    /**
     * SOBRESCRITURA DE NOTIFICACIÓN DE CONTRASEÑA
     * Este método hace que Laravel use tu archivo personalizado ResetPasswordNotification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    // =========================================================================
    // LÓGICA DE ROLES Y PERMISOS
    // =========================================================================

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')->withTimestamps();
    }

    public function hasRole($roleName): bool
    {
        return $this->roles->contains('name', $roleName);
    }

    public function hasAnyRole($roles): bool
    {
        return $this->roles->whereIn('name', (array)$roles)->isNotEmpty();
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('superadmin');
    }

    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['admin', 'superadmin']);
    }

    public function isAdminOfGroup($groupCategory): bool
    {
        $slug = str_replace('admin_', '', strtolower($groupCategory));
        return $this->hasRole('admin_' . $slug) || $this->isAdmin();
    }

    // =========================================================================
    // LÓGICA DEL DIARIO ESPIRITUAL (CON SEGURIDAD DE ENCRIPTACIÓN)
    // =========================================================================

    /**
     * Helper para desencriptar valores de forma segura.
     * Si el valor no está encriptado (datos antiguos), lo devuelve tal cual.
     */
    private function safelyDecrypt($value)
    {
        if (!$value) return '';
        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e) {
            return $value;
        }
    }

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
        $allowed = ['superadmin', 'admin', 'catequesis_niños', 'catequesis_adolescentes', 'catequesis_adultos', 'acutis', 'juveniles', 'juan_pablo', 'coro', 'misioneros'];
        return $this->hasAnyRole($allowed);
    }

    /**
     * Obtiene las entradas desencriptando los datos sensibles en memoria.
     */
    public function getDiarioEntries()
    {
        return collect($this->diario_data)->map(function($entry) {
            $entry['title'] = $this->safelyDecrypt($entry['title'] ?? '');
            $entry['content'] = $this->safelyDecrypt($entry['content'] ?? '');
            return $entry;
        })->sortByDesc('created_at')->values()->all();
    }

    /**
     * Obtiene una entrada específica desencriptada.
     */
    public function getDiarioEntry($entryId)
    {
        foreach ($this->diario_data as $entry) {
            if ($entry['id'] == $entryId) {
                $entry['title'] = $this->safelyDecrypt($entry['title'] ?? '');
                $entry['content'] = $this->safelyDecrypt($entry['content'] ?? '');
                return $entry;
            }
        }
        return null;
    }

    /**
     * Agrega una entrada ENCRIPTANDO el título y el contenido.
     */
    public function addDiarioEntry($data)
    {
        if (!$this->canAccessDiario()) throw new \Exception('No tiene permisos para usar el diario.');
        
        $diarioData = $this->diario_data;
        $entryId = count($diarioData) > 0 ? max(array_column($diarioData, 'id')) + 1 : 1;
        
        $entry = [
            'id' => $entryId,
            'title' => Crypt::encryptString($data['title'] ?? ''), 
            'content' => Crypt::encryptString($data['content'] ?? ''), 
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
        
        // Retornamos la versión limpia para la respuesta inmediata
        $entry['title'] = $data['title'] ?? '';
        $entry['content'] = $data['content'] ?? '';
        return $entry;
    }

    /**
     * Actualiza una entrada ENCRIPTANDO los nuevos valores.
     */
    public function updateDiarioEntry($entryId, $data)
    {
        $diarioData = $this->diario_data;
        $updated = false;
        
        foreach ($diarioData as &$entry) {
            if ($entry['id'] == $entryId) {
                if (isset($data['title'])) $entry['title'] = Crypt::encryptString($data['title']);
                if (isset($data['content'])) $entry['content'] = Crypt::encryptString($data['content']);
                
                $entry['type'] = $data['type'] ?? $entry['type'];
                $entry['color'] = $data['color'] ?? $entry['color'];
                $entry['is_favorite'] = isset($data['is_favorite']) ? (bool)$data['is_favorite'] : (bool)($entry['is_favorite'] ?? false);
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
            ->map(function($entry) {
                $entry['title'] = $this->safelyDecrypt($entry['title'] ?? '');
                $entry['content'] = $this->safelyDecrypt($entry['content'] ?? '');
                return $entry;
            })
            ->sortByDesc('created_at')
            ->values()
            ->all();
    }

    /**
     * Búsqueda en el diario: Desencripta en memoria para poder filtrar por texto.
     */
    public function searchDiarioEntries($query)
    {
        $q = strtolower($query);
        return collect($this->getDiarioEntries())->filter(function($e) use ($q) {
            return str_contains(strtolower($e['title'] ?? ''), $q) || 
                   str_contains(strtolower($e['content'] ?? ''), $q);
        })->values()->all();
    }
}
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'id',
        'name',
        'matricula',
        'email',
        'password',
        'estado',
        'id_grupo',
        'id_personal',
    ];
    public $timestamps = false;
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    private $resolvedPermissionKeys;

    public function group()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo');
    }

    public function isSuperAdmin(): bool
    {
        return (bool) optional($this->group)->is_super_admin;
    }

    public function permissionKeys(): array
    {
        if ($this->isSuperAdmin()) {
            return array_keys(config('rbac.permissions', []));
        }

        if (!$this->group || (int) $this->group->estado !== 1) {
            return [];
        }

        if ($this->resolvedPermissionKeys === null) {
            $this->resolvedPermissionKeys = $this->group
                ? $this->group->permissions()->pluck('permissions.key')->all()
                : [];
        }

        return $this->resolvedPermissionKeys;
    }

    public function hasPermissionTo(string $permission): bool
    {
        return $this->isSuperAdmin() || in_array($permission, $this->permissionKeys(), true);
    }
}

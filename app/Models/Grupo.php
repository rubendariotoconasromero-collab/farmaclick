<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;
    
    protected $table = 'grupo';
    protected $fillable = [ 
        'id',
        'nombre',
        'descripcion',
        'estado',
        'slug',
        'is_super_admin'
    ];
    public $timestamps = false;

    protected $casts = ['is_super_admin' => 'boolean'];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'group_permission', 'id_grupo', 'permission_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id_grupo');
    }
}

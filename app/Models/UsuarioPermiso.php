<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioPermiso extends Model
{
    protected $table = 'usuario_permisos';
    protected $fillable = [
        'id_usuario',
        'id_permiso'
    ];
    public $timestamps = false;
}

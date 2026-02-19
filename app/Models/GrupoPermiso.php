<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoPermiso extends Model
{
    use HasFactory;
    protected $table = 'grupo_permiso';
    protected $fillable = [ 
        'id',
        'id_grupo',
        'id_permiso',
    ];
    public $timestamps = false;
    protected $primaryKey = ['id_grupo', 'id_permiso'];
    public $incrementing = false;
}

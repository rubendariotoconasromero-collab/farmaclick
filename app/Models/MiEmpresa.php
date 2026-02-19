<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiEmpresa extends Model
{
    use HasFactory;
    protected $table = 'mi_empresa';
    protected $fillable = [ 
        'id',
        'nombre',
        'nit',
        'representante',
        'direccion',
        'telefono',
        'descripcion',
        'localidad',
        'foto',
        'correo',
        'sitio_web',
        'logo_sistema',
    ];
    public $timestamps = false;
}

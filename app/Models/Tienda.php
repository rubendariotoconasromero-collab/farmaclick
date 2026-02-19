<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tienda extends Model
{
    use HasFactory;
    protected $table = 'tienda';
    protected $fillable = [ 
        'id',
        'cod_tienda',
        'nombre',
        'direccion',
        'cod_almacen',
        'estado',
        'id_mi_empresa'
    ];
    public $timestamps = false;
}

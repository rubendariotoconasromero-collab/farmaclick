<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenServicio extends Model
{
    use HasFactory;
    protected $table = 'orden_servicio';
    protected $fillable = [ 
        'id',
        'fecha',
        'sub_total',
        'descuento',
        'descripcion',
        'total',
        'estado',
        'id_cliente',
        'id_personal',
        'id_usuario',
        'id_tienda'
    ];
    public $timestamps = false;
}

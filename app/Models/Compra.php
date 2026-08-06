<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;
    protected $table = 'compra';
    protected $fillable = [ 
        'id',
        'fecha',
        'descripcion',
        'sub_total',
        'descuento',
        'total',
        'estado',
        'id_proveedor',
        'id_usuario',
        'id_tipo_pago',
        'id_forma_pago',
        'total_efectivo',
        'total_deposito',
        'control',
    ];
    public $timestamps = false;
}

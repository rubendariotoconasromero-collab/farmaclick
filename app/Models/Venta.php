<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $table = 'venta';
    protected $fillable = [ 
        'id',
        'fecha',
        'descripcion',
        'sub_total',
        'descuento',
        'tipo_venta',
        'total',
        'estado',
        'id_cliente',
        'id_tipo_pago',
        'id_forma_pago',
        'id_usuario',
        'id_tienda',
        'total_efectivo',
        'total_deposito',
        'efectivo',
        'cambio',

        
        
    ];
    public $timestamps = false;
}

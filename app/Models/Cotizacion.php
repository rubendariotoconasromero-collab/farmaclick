<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;
    protected $table = 'cotizacion';
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
        'id_tienda'
        
        
    ];
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCotizacion extends Model
{
    use HasFactory;
    protected $table = 'detalle_cotizacion';
    protected $fillable = [ 
        'id_venta',
        'id_producto',
        'cantidad',
        'costo_venta',
        'sub_total',
    ];
    public $timestamps = false;
    protected $primaryKey = ['id_cotizacion', 'id_producto'];
    public $incrementing = false;
}

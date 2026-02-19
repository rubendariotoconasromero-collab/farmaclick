<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleOrdenServicio extends Model
{
    use HasFactory;
    protected $table = 'detalle_orden_servicio';
    protected $fillable = [ 
        'id_orden_servicio',
        'id_producto',
        'cantidad',
        'costo_venta',
        'sub_total',
    ];
    public $timestamps = false;
}

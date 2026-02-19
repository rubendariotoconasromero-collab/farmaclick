<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVentaPaquete extends Model
{
    use HasFactory;
    protected $table = 'detalle_venta_paquete';
    protected $fillable = [ 
        'id',
        'id_venta',
        'id_paquete',
        'cantidad',
        'costo_venta',
        'sub_total',
    ];
    public $timestamps = false;
}

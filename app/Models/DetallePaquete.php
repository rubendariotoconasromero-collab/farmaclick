<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePaquete extends Model
{
    use HasFactory;
    protected $table = 'detalle_paquete';
    protected $fillable = [ 
        'id_paquete',
        'id_producto',
        'cantidad',
        'costo_venta',
        'sub_total',
    ];
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleAntiparasitario extends Model
{
    use HasFactory;
    protected $table = 'detalle_antiparasitario';
    protected $fillable = [ 
        'id',
        'id_antiparasitario',
        'id_producto',
        'fecha',
        'prox_fecha',
        'edad',
        'peso',
        'cantidad',
        'costo_venta',
        'sub_total'
    ];
    public $timestamps = false;
}

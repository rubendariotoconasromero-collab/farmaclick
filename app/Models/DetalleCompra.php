<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    use HasFactory;
    protected $table = 'detalle_compra';
    protected $fillable = [ 
        'id_compra',
        'id_producto',
        'id_lote',
        'cantidad',
        'costo_compra',
        'sub_total',
    ];
    public $timestamps = false;
    protected $primaryKey = ['id_compra', 'id_producto'];
    public $incrementing = false;
}

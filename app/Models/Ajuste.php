<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ajuste extends Model
{
    use HasFactory;
    protected $table = 'ajuste';
    protected $fillable = [
        'id',
        'stock',
        'costo_compra',
        'costo_venta',
        'observacion',
        'id_articulo',
        'id_motivo_ajuste',
        'fecha',
        'hora',
        'id_usuario',
        'id_venta',
        'id_compra',
        'id_transaccion',
        'descuento',
        'stock_general',
        'stock_general_anterior',
    ];
    public $timestamps = false;
}

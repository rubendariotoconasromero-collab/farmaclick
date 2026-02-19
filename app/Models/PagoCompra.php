<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoCompra extends Model
{
    use HasFactory;
    protected $table = 'pago_compra';
    protected $fillable = [ 
        'id',
        'fecha',
        'fecha_final',
        'monto',
        'descripcion',
        'id_tipo_pago',
        'id_compra',
    ];
    public $timestamps = false;
}

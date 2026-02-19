<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;
    protected $table = 'pago';
    protected $fillable = [ 
        'id',
        'fecha',
        'fecha_final',
        'monto',
        'descripcion',
        'id_tipo_pago',
        'id_venta',
    ];
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CXCobrar extends Model
{
    use HasFactory;
    protected $table = 'c_x_cobrar';
    protected $fillable = [ 
        'id',
        'fecha',
        'anticipo',
        'monto_total',
        'amortizacion',
        'saldo',
        'descripcion',
        'id_pago'

    ];
    public $timestamps = false;
}

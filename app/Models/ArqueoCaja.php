<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArqueoCaja extends Model
{
    use HasFactory;
    protected $table = 'arqueo_caja';
    protected $fillable = [ 
        'id',
        'fecha_apertura',
        'fecha_cierre',
        'doscientos',
        'cien',
        'cincuenta',
        'veinte',
        'diez',
        'cinco',
        'dos',
        'uno',
        'cerocinco',
        'ceroveinte',
        'cien_dolar',
        'registro_venta',
        'apertura',
        'total',
        'gastos',
        'registro_compra',
        'saldo_sistema',
        'saldo_efectivo',
        'diferencia',
        'estado',
        'id_usuario',
    ];
    public $timestamps = false;
}

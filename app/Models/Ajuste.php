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
        'id_motivo_ajuste'
    ];    
    public $timestamps = false;
}

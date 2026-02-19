<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
    use HasFactory;
    protected $table = 'paquetes';
    protected $fillable = [ 
        'id',
        'nombre',
        'fecha_inicio',
        'fecha_final',
        'sub_total',
        'descuento',
        'descripcion',
        'total',
        'estado'
    ];
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleControlVacuna extends Model
{
    use HasFactory;
    protected $table = 'detalle_control_vacuna';
    protected $fillable = [ 
        'id',
        'id_control_vacuna',
        'id_vacuna',
        'fecha',
        'prox_fecha',
        'edad',
        'cantidad',
        'costo_venta',
        'sub_total'
    ];
    public $timestamps = false;
}

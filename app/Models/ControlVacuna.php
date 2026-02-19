<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlVacuna extends Model
{
    use HasFactory;
    protected $table = 'control_vacuna';
    protected $fillable = [ 
        'id',
        'id_paciente',
        'fecha',
        'prox_fecha',
        'sub_total',
        'descuento',
        'total',
        'estado'
        
    ];
    public $timestamps = false;






   
}

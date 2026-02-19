<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antiparasitario extends Model
{
    use HasFactory;
    protected $table = 'antiparasitario';
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

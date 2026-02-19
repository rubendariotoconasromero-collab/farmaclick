<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    use HasFactory;
    protected $table = 'paciente';
    protected $fillable = [ 
        'id',
        'nombre',
        'especie',
        'edad',
        'color',
        'raza',
        'sexo',
        'peso',
        'cirugias',
        'enfermedades',
        'vacunas',
        'estado',
        'id_cliente',
        'id_animal'
        
    ];
    public $timestamps = false;
}



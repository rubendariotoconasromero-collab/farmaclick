<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialClinico extends Model
{
    use HasFactory;
    protected $table = 'historial_clinico';
    protected $fillable = [ 
        'id',
        'nro_historia',
        'id_cliente',
        'id_paciente',
        'id_usuario'
    ];
    public $timestamps = false;
}

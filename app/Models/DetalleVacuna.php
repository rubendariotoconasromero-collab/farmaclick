<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleVacuna extends Model
{
    use HasFactory;
    protected $table = 'detalle_vacuna';
    protected $fillable = [ 
        'id',
        'id_animal',
        'id_producto',
        'tipo_vacuna'
    ];
    public $timestamps = false;
}

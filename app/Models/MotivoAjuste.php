<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivoAjuste extends Model
{
    use HasFactory;
    protected $table = 'motivo_ajuste';
    protected $fillable = [ 
        'id',
        'nombre',
        'descripcion'
    ];
    public $timestamps = false;
}

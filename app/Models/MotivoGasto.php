<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivoGasto extends Model
{
    use HasFactory;
    protected $table = 'motivo_gasto';
    protected $fillable = [ 
        'id',
        'nombre',
        'descripcion'
    ];
    public $timestamps = false;
}

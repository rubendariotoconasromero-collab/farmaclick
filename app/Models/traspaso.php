<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class traspaso extends Model
{
    use HasFactory;
    protected $table = 'traspasos';
    protected $fillable = [ 
        'id',
        'fecha',
        'hora',
        'glosa',
        'estado',
        'id_tienda1',
        'id_tienda2',
        'id_usuario',
    ];
    public $timestamps = false;
}

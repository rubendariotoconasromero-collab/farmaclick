<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleTraspaso extends Model
{
    use HasFactory;
    protected $table = 'detalle_traspasos';
    protected $fillable = [ 
        'id_tienda_articulo',
        'id_traspaso',
        'cantidad',
    ];
    public $timestamps = false;
}

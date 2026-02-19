<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;
    protected $table = 'bitacora';
    protected $fillable = [
       'id',
       'fecha',
       'hora',
       'tabla',
       'codigo_tabla',
       'transaccion',
       'id_usuario'
    ];
    public $timestamps = false;
}

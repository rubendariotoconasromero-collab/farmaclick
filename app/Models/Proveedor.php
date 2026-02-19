<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;
    protected $table = 'proveedor';
    protected $fillable = [ 
        'id',
        'nombre',
        'nit',
        'contacto',
        'direccion',
        'telefono',
        'descripcion',
        'estado'
    ];
    public $timestamps = false;
}

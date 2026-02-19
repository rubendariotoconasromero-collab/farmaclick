<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleForm extends Model
{
    protected $table = 'detalle_forms';
    protected $fillable = [
        'id',
        'id_permiso_form',
        'id_formulario'        
    ];    
    public $timestamps = false;
}

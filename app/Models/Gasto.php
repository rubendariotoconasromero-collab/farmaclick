<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    use HasFactory;
    protected $table = 'gasto';
    protected $fillable = [
        'id',
        'fecha',
        'monto',
        'descripcion',
        'id_motivo_gasto',
        'id_forma_pago',
        'id_usuario',
        'efectivo',
        'deposito',
        'control',
    ];
    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;
    protected $table = 'lote';
    protected $fillable = [
        'id',
        'cantidad',
        'fecha_vencimiento',
        'lote',
        'estado',
        'id_producto',
    ];    
    public $timestamps = false;
}

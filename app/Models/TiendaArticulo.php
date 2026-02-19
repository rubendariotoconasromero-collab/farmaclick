<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiendaArticulo extends Model
{
    use HasFactory;
    protected $table = 'tienda_articulo';
    protected $fillable = [
        'id',
        'id_articulo',
        'id_tienda',
        'stock'
    ];    
    public $timestamps = false;
}

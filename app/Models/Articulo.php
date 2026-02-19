<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;
    protected $table = 'articulo';
    protected $fillable = [
        'id',
        'cod_sistema',
        'cod_proveedor',
        'cod_barra',
        'nombre_comercial',
        'nombre_generico',
        'presentacion',
        'contenido_unidad',
        'costo_compra',
        'costo_compra_caja',
        'costo_unitario',
        'costo_mayorista',
        'costo_preferencial',
        'stock_minimo',
        'ubicacion',
        'descripcion',
        'composicion',
        'cantidad_caja',
        'cantidad_blister',
        'psicotropico',
        'refrigerado',
        'estado',
        'id_categoria',
        'id_unidad',
        'id_proveedor',
        'id_marca',
    ];    
    public $timestamps = false;
}

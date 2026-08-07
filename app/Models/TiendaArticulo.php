<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /**
     * Recalcula el stock de un producto sumando la cantidad de sus lotes activos.
     * Reemplaza al antiguo procedimiento almacenado `stock`.
     */
    public static function recalcularStock($idTiendaArticulo): void
    {
        DB::table('tienda_articulo')
            ->where('id', $idTiendaArticulo)
            ->update([
                'stock' => DB::table('lote')
                    ->where('id_producto', $idTiendaArticulo)
                    ->where('estado', '!=', 0)
                    ->sum('cantidad'),
            ]);
    }
}

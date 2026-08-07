<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStockProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS stock');
        DB::unprepared('
            CREATE PROCEDURE stock(IN id_producto FLOAT)
            BEGIN
                UPDATE tienda_articulo
                SET tienda_articulo.stock = (
                    SELECT SUM(lote.cantidad) FROM lote
                    WHERE lote.id_producto = id_producto AND lote.estado != 0
                )
                WHERE tienda_articulo.id = id_producto;
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS stock');
    }
}

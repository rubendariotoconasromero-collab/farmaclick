<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPurchaseHistoryFilterIndexes extends Migration
{
    public function up()
    {
        Schema::table('compra', function (Blueprint $table) {
            $table->index('fecha', 'compra_fecha_index');
            $table->index('estado', 'compra_estado_index');
        });
    }

    public function down()
    {
        Schema::table('compra', function (Blueprint $table) {
            $table->dropIndex('compra_fecha_index');
            $table->dropIndex('compra_estado_index');
        });
    }
}

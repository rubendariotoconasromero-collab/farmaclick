<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lote;
use App\Models\Ajuste;
use App\Models\TiendaArticulo;
use DB;
use DateTime;

class LoteController extends BitacoraController
{
    public function guardar(Request $request){
        try{
            DB::beginTransaction();
            $objdate = new DateTime();
            $fechaactual= $objdate->format('Y-m-d');
            $hora= $objdate->format('H:i:s');
            $id_usuario=\Auth::user()->id;

            
            $detalles = $request->detalle;
            //dd($detalles);
            foreach($detalles as $ep=>$det){
                //dd($det['lote']);
                $obj = new Lote();
                $obj->cantidad=$det['cantidad'];
                $obj->fecha_vecimiento=$det['fecha_vecimiento'];
                $obj->lote=$det['lote'];
                $obj->id_producto=$det['id_tienda_articulo'];
                $obj->estado=1;
                $obj->save();
                
                TiendaArticulo::recalcularStock($det['id_tienda_articulo']);

                $tienda_articulo=DB::select("SELECT ta.stock
                FROM tienda_articulo ta
                WHERE ta.id = '$obj->id_producto'");

                $ajuste = new Ajuste();
                $ajuste->stock=$det['cantidad'];
                $ajuste->costo_compra=$det['costo_compra'];
                $ajuste->costo_unitario=0;
                $ajuste->costo_mayorista=0;
                $ajuste->costo_preferencial=0;
                $ajuste->stock_anterior=0;
                $ajuste->stock_actual=$det['cantidad'];
                $ajuste->costo_unitario=0;
                $ajuste->costo_mayorista=0;
                $ajuste->costo_preferencial=0;
                $ajuste->costo_venta= 0;
                $ajuste->observacion='';
                $ajuste->id_lote=$obj->id;
                $ajuste->fecha=$fechaactual;
                $ajuste->id_usuario=$id_usuario;
                $ajuste->stock_general_anterior=0;
                $ajuste->stock_general=$tienda_articulo[0]->stock;
                $ajuste->id_venta=0;
                $ajuste->id_compra=0;
                $ajuste->id_motivo_ajuste=1;
                $ajuste->id_transaccion=$obj->id;
                $ajuste->hora=$hora;
                $ajuste->save();
            }
            $datos = [
                'tabla' => 'lote',
                'codigo_tabla' => $obj->id,
                'transaccion' => 'guardar',
            ];
            $this->guardarBitacora($datos);
            
            DB::commit();
            return[
                'id'=>$obj->id
            ];
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function anular(Request $request){

        $id = $request->id;
        $objdate = new DateTime();
        $fechaactual= $objdate->format('Y-m-d');
        $hora= $objdate->format('H:i:s');
        $id_usuario=\Auth::user()->id;

       $detalles = Lote::where('id',$request->id)->get();
       $id_producto = $detalles[0]->id_producto;
       $cantidad = $detalles[0]->cantidad;
       // dd($id_producto);


        DB::table('lote')
        ->where('lote.id', $id)
        ->update(['lote.estado' => 0]) ;
        $datos = [
            'tabla' => 'lote',
            'codigo_tabla' => $id,
            'transaccion' => 'anular',
        ];
        $this->guardarBitacora($datos);

        DB::table('lote')
        ->where('lote.id', $id)
        ->update(['lote.estado' => 0]) ;

        

        $tienda_articulo=DB::select("SELECT ta.stock
        FROM tienda_articulo ta
        WHERE ta.id = '$id_producto'");

        $ajuste = new Ajuste();
        $ajuste->stock=$cantidad;
        $ajuste->costo_compra=0;
        $ajuste->costo_unitario=0;
        $ajuste->costo_mayorista=0;
        $ajuste->costo_preferencial=0;
        $ajuste->stock_anterior=$cantidad;
        $ajuste->stock_actual=$cantidad - $cantidad;
        $ajuste->costo_unitario=0;
        $ajuste->costo_mayorista=0;
        $ajuste->costo_preferencial=0;
        $ajuste->costo_venta= 0;
        $ajuste->observacion='';
        $ajuste->id_lote=$id;
        $ajuste->fecha=$fechaactual;
        $ajuste->id_usuario=$id_usuario;
        $ajuste->stock_general_anterior=$tienda_articulo[0]->stock;
        $ajuste->stock_general=$tienda_articulo[0]->stock - $cantidad;
        $ajuste->id_venta=0;
        $ajuste->id_compra=0;
        $ajuste->id_motivo_ajuste=12;
        $ajuste->id_transaccion=$id;
        $ajuste->hora=$hora;
        $ajuste->save();

        TiendaArticulo::recalcularStock($id_producto);

    }
}

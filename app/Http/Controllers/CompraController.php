<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\MiEmpresa;
use App\Models\Articulo;
use App\Models\Lote;
use App\Models\TiendaArticulo;
use App\Models\Ajuste;
use App\Models\Control;
use App\Models\ArqueoCaja;
use App\Models\PagoCompra;
use App\Models\CXPagar;
use DB;
use DateTime;

class CompraController extends BitacoraController
{

    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Compra::join('users','compra.id_usuario','=','users.id')
            ->join('proveedor','compra.id_proveedor','=','proveedor.id')
            ->join('forma_pago','compra.id_forma_pago','=','forma_pago.id')
            ->join('tipo_pago','compra.id_tipo_pago','=','tipo_pago.id')
            ->select('compra.id','compra.fecha','compra.descripcion','compra.sub_total','compra.descuento',
            'compra.total','compra.estado','users.name','proveedor.nombre as proveedor','forma_pago.nombre as formaP','tipo_pago.nombre as tipo','compra.total_efectivo','compra.total_deposito','compra.id_forma_pago','compra.id_proveedor','compra.id_tipo_pago')
            ->where('compra.estado','!=','Anulado')
            ->orderBy('compra.id','desc')->paginate(15);
        }
        else{
            $obj= Compra::join('users','compra.id_usuario','=','users.id')
            ->join('proveedor','compra.id_proveedor','=','proveedor.id')
            ->join('forma_pago','compra.id_forma_pago','=','forma_pago.id')
            ->join('tipo_pago','compra.id_tipo_pago','=','tipo_pago.id')
            ->select('compra.id','compra.fecha','compra.descripcion','compra.sub_total','compra.descuento',
            'compra.total','compra.estado','users.name','proveedor.nombre as proveedor','forma_pago.nombre as formaP','tipo_pago.nombre as tipo','compra.total_efectivo','compra.total_deposito','compra.id_forma_pago','compra.id_proveedor','compra.id_tipo_pago')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->where('compra.estado','!=','Anulado')
            ->orderBy('compra.id','desc')->paginate(15);
        }
        return $obj;
    }

    private function actualizarStock($id_producto,$cantVenta){
        DB::table('tienda_articulo')->where('tienda_articulo.id_articulo','=',$id_producto)
        ->where('tienda_articulo.id_tienda','=',1)
        ->increment('stock', $cantVenta);

        // $variable=DB::select("UPDATE tienda_articulo
        //     SET tienda_articulo.stock = ( SELECT SUM(lote.cantidad) FROM lote WHERE(lote.id_producto = $id_producto))
        //     WHERE tienda_articulo.id = $id_producto");
        // dd($variable);
    }

    private function actualizarCaja($monto,$id_usuario){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.registro_compra', $monto);
    }

    private function descontarCaja($monto,$id_usuario){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->decrement('arqueo_caja.registro_compra', $monto);
    }
    private function descontarCajaDeposito($monto,$id_usuario){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->decrement('arqueo_caja.registro_compra', $monto);
    }

    private function actualizarCajaContado($monto,$id_usuario){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.total_contado_compra', $monto);
    }
    private function actualizarCajaContadoDeposito($monto,$id_usuario){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.total_contado_deposito_compra', $monto);
    }
    private function descontarCajaContado($monto,$id_usuario){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->decrement('arqueo_caja.total_contado_compra', $monto);
    }
    private function descontarCajaContadoDeposito($monto,$id_usuario){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->decrement('arqueo_caja.total_contado_deposito_compra', $monto);
    }
    private function descontarCajaCredito($monto,$id_usuario){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->decrement('arqueo_caja.total_credito_compra', $monto);
    }
    private function descontarCajaCreditoDeposito($monto,$id_usuario){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->decrement('arqueo_caja.total_credito_deposito_compra', $monto);
    }

    public function guardar(Request $request){
        try{
            DB::beginTransaction();
            $registro_compra = $request->total;
            $compra_efectivo = $request->total_efectivo;
            $compra_deposito = $request->total_deposito;
            $id=\Auth::user()->id;
            $compra = new Compra();
            $compra->fecha=$request->fecha;
            $compra->descripcion=$request->descripcion;
            $compra->sub_total=$request->sub_total;
            $compra->descuento=$request->descuento;
            $compra->total=$request->total;
            $compra->total_efectivo = $request->total_efectivo == '' ? '0' : $request->total_efectivo;
            $compra->total_deposito = $request->total_deposito == '' ? '0' : $request->total_deposito;
            $compra->estado='Registrado';
            $compra->id_proveedor=$request->id_proveedor;
            $compra->id_tipo_pago=$request->id_tipo_pago;
            if($request->id_forma_pago == 6){
                $compra->total_efectivo = $request->total_efectivo == '' ? '0' : $request->total_efectivo;
                $compra->total_deposito = $request->total_deposito == '' ? '0' : $request->total_deposito;
            }else if($request->id_forma_pago == 2)
            {
                $compra->total_efectivo =$request->total;
                $compra->total_deposito = 0;

            }else if($request->id_forma_pago == 3)
            {
                $compra->total_efectivo = 0;
                $compra->total_deposito =$request->total;

            }else if($request->id_forma_pago == 4)
            {
                $compra->total_efectivo = 0;
                $compra->total_deposito =$request->total;

            }else if($request->id_forma_pago == 5)
            {
                $compra->total_efectivo = 0;
                $compra->total_deposito =$request->total;
            }else
            {
                
            }
            if($request->id_tipo_pago == 1) {
                $compra->id_forma_pago=$request->id_forma_pago;
            }else if ($request->id_tipo_pago == 2) {
                $compra->id_forma_pago=$request->id_forma_pago;
            } else {
                
            }
            $compra->id_usuario=\Auth::user()->id;
            $compra->save();

            if($request->id_tipo_pago == 1) {
                $pago = new PagoCompra();
                $pago->id = $compra->id;
                $pago->fecha = $request->fecha;
                $pago->fecha_final = $request->fecha_final;
                $pago->monto = $request->total;
                $pago->saldo = $request->total;
                $pago->descripcion = "";
                $pago->id_tipo_pago = $request->id_tipo_pago;
                $pago->id_compra = $compra->id;
                $pago->save();


            } else if($request->id_tipo_pago == 2) {

                $pago = new PagoCompra();
                $pago->id = $compra->id;
                $pago->fecha = $request->fecha;
                $pago->fecha_final = $request->fecha_final;
                $pago->monto = $request->total;
                $pago->saldo = $request->total;
                $pago->descripcion = $request->descripcion = "";
                $pago->id_tipo_pago = $request->id_tipo_pago;
                $pago->id_compra = $compra->id;
                $pago->save();

                $cxcobrar = new CXPagar();
                $cxcobrar->fecha = $request->fecha;

                $cxcobrar->monto_total = $request->monto_total;
                $cxcobrar->descripcion = $request->descripcion_pago;
                $cxcobrar->saldo = $request->monto_total;
                $cxcobrar->id_pago = $compra->id;
                $cxcobrar->id_forma_pago = 0;
                $cxcobrar->save();

            } else {
                
            }

            $objdate = new DateTime();
            $fechaactual= $objdate->format('Y-m-d');
            $hora= $objdate->format('H:i:s');
            $year = $objdate->format('y');
            $correlativo = $this->correlativoControl();

            $control = new control();
            $control->tabla = $request->tabla = "Compra";
            $control->codigo = $request->codigo = 'C'.strval($correlativo + 1).$year;
            $control->fecha = $fechaactual;
            $control->save();

            $detalles = $request->detalle;
            foreach($detalles as $ep=>$det){          
                $stock_anterior_lote = 0;
                $stock_actual_lote = 0;

                $lote_existente = Lote::where('id_producto', $det['id_tienda_articulo'])
                      ->where('lote', $det['lote'])
                      ->where('lote.estado', '!=', 0)
                      ->first();

                if ($lote_existente) {
                    $stock_anterior_lote = $lote_existente->cantidad;
                    $lote_existente->cantidad += $det['cantidad'];
                    $lote_existente->save();
                    $lote = $lote_existente; 
                    $stock_actual_lote = $lote->cantidad;
                } else {
                    $stock_anterior_lote = 0;
                    $lote = new Lote();
                    $lote->id_producto = $det['id_tienda_articulo'];
                    $lote->fecha_vecimiento = $det['fecha_vecimiento'];
                    $lote->cantidad = $det['cantidad'];
                    $lote->lote = $det['lote'];
                    $lote->estado = 1;
                    $lote->save();

                    $stock_actual_lote = $lote->cantidad;
                }

                $obj = new DetalleCompra();
                $obj->id_compra= $compra->id;
                $obj->id_producto= $det['id_tienda_articulo'];
                $obj->cantidad= $det['cantidad'];
                $obj->costo_compra= $det['costo_compra'];
                $obj->sub_total= $det['sub_total'];
                $obj->descuento= $det['descuento'];
                $obj->id_lote= $lote->id;
                $obj->save();
                $consulta = DB::select('CALL stock(?)', [$det['id_tienda_articulo']]);


                $articulo = Articulo::findOrFail($det['id_articulo']);
                $articulo->costo_compra=$det['costo_compra'];
                $articulo->save();

                $tienda_articulo=DB::select("SELECT ta.stock
                FROM tienda_articulo ta
                WHERE ta.id = '$obj->id_producto'");

                $ajuste = new Ajuste();
                $ajuste->stock=$det['cantidad'];
                $ajuste->costo_compra=$det['costo_compra'];
                $ajuste->costo_venta=0;
                $ajuste->costo_unitario=0;
                $ajuste->costo_mayorista=0;
                $ajuste->costo_preferencial=0;

                $ajuste->stock_anterior=$stock_anterior_lote;
                $ajuste->stock_actual=$stock_actual_lote;
                $ajuste->stock_general_anterior=$tienda_articulo[0]->stock - $det['cantidad'];
                $ajuste->stock_general=$tienda_articulo[0]->stock;
                $ajuste->observacion=$request->descripcion;
                $ajuste->id_lote=$lote->id;
                $ajuste->fecha=$compra->fecha;
                $ajuste->id_usuario=$id;
                $ajuste->id_venta=0;
                $ajuste->id_compra=$compra->id;
                $ajuste->id_motivo_ajuste=6;
                $ajuste->id_transaccion=$compra->id;
                $ajuste->hora=$hora;
                $ajuste->descuento=$det['descuento'];
                $ajuste->save();
            }


            $datos = [
                'tabla' => 'compra',
                'codigo_tabla' => $compra->id,
                'transaccion' => 'guardar',
            ];

            $this->guardarBitacora($datos);

            DB::commit();
            return response()->json([
                'message' => 'Compra registrada exitosamente.',
                'id' => $compra->id,
            ], 201);
        } catch (\Throwable $e){
            DB::rollBack();
            report($e);

            return response()->json([
                'message' => 'No se pudo registrar la compra.',
            ], 500);
        }
    }

    public function obtenerCabecera(Request $request){

        $id=$request->id;
        $obj= Compra::join('proveedor','compra.id_proveedor','=','proveedor.id')
        ->select('compra.id','compra.fecha','compra.descripcion','compra.total','proveedor.nombre as proveedor')
        ->where('compra.id','=',$id)
        ->get();

        return $obj;
    }

    // public function detalleCompra(Request $request){

    //     $id=$request->id;
    //     $obj= detalleCompra::join('tienda_articulo','detalle_compra.id_producto','=','tienda_articulo.id')
    //     ->join('lote','detalle_compra.id_lote','=','lote.id')
    //     ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
    //     ->leftjoin('categoria', function($join){
    //         $join->orOn('articulo.id_categoria','=','categoria.id');
    //     })
    //     ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
    //     ->select('detalle_compra.id_compra','detalle_compra.costo_compra','detalle_compra.id_compra','detalle_compra.cantidad',
    //     'detalle_compra.sub_total','articulo.nombre_comercial as articulo','tienda.nombre as tienda','categoria.nombre as categoria'
    //     ,'detalle_compra.id_lote','detalle_compra.id_producto','lote.cantidad as stock')
    //     ->where('detalle_compra.id_compra','=',$id)
    //     ->get();
    //     return $obj;
    // }

    public function detalleCompra(Request $request){
        // id_tienda_articulo
        $id=$request->id;
        $obj= detalleCompra::join('tienda_articulo','detalle_compra.id_producto','=','tienda_articulo.id')
        ->join('lote','detalle_compra.id_lote','=','lote.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->leftjoin('categoria', function($join){
            $join->orOn('articulo.id_categoria','=','categoria.id');
        })
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_compra.id_compra','detalle_compra.costo_compra','detalle_compra.id_compra','detalle_compra.cantidad',
        'detalle_compra.sub_total','articulo.nombre_comercial as articulo','tienda.nombre as tienda','categoria.nombre as categoria'
        ,'detalle_compra.id_lote','detalle_compra.id_producto','lote.cantidad as stock','articulo.id as id_articulo','detalle_compra.eliminado','lote.fecha_vecimiento','lote.lote'
        ,'detalle_compra.descuento','tienda_articulo.id as id_tienda_articulo')
        ->where('detalle_compra.id_compra','=',$id)
        ->get();
        return $obj;
    }

    /*public function anular(Request $request){
        $objdate = new DateTime();
        $fechaactual= $objdate->format('Y-m-d');
        $hora= $objdate->format('H:i:s');
        $registro_compra = DB::select("SELECT id, total,id_tipo_pago,id_forma_pago,total_efectivo,total_deposito FROM compra WHERE compra.id = $request->id");
        $total = $registro_compra[0]->total;
        $total_efectivo = $registro_compra[0]->total_efectivo;
        $total_deposito = $registro_compra[0]->total_deposito;
        $tipo_pago = $registro_compra[0]->id_tipo_pago;
        $forma_pago = $registro_compra[0]->id_forma_pago;
        $id_usuario=\Auth::user()->id;
        $obj = Compra::findOrFail($request->id);
        $obj->estado = 'Anulado';
        $obj->save();

        $detalles = DetalleCompra::select('id_compra','id_lote','cantidad','id_producto','costo_compra')
        ->where('detalle_compra.id_compra',$request->id)->get();
        foreach($detalles as $ep=>$det){

                $tienda_articulo=DB::select("SELECT ta.stock
                FROM tienda_articulo ta
                WHERE ta.id = '$det->id_producto'");
                $ajuste = new Ajuste();
                $ajuste->stock=$det['cantidad'];
                $ajuste->costo_compra=$det['costo_compra'];
                $ajuste->costo_venta=0;
                $ajuste->costo_unitario=0;
                $ajuste->costo_mayorista=0;
                $ajuste->costo_preferencial=0;
                $ajuste->stock_anterior=$det['cantidad'];
                $ajuste->stock_actual=$det['cantidad']- $det['cantidad'];
                $ajuste->stock_general_anterior=$tienda_articulo[0]->stock;
                $ajuste->stock_general=$tienda_articulo[0]->stock - $det['cantidad'];
                $ajuste->observacion='';
                $ajuste->id_lote=$det['id_lote'];
                $ajuste->fecha=$fechaactual;
                $ajuste->id_usuario=$id_usuario;
                $ajuste->id_venta=0;
                $ajuste->id_compra=$request->id;
                $ajuste->id_motivo_ajuste=9;
                $ajuste->id_transaccion=$request->id;
                $ajuste->hora=$hora;
                $ajuste->save();
        }

        DB::table('lote')
        ->join('detalle_compra','lote.id','=','detalle_compra.id_lote')
        ->join('compra','detalle_compra.id_compra','=','compra.id')
        ->where('compra.id', $obj->id)
        ->update(['lote.estado' => 0]) ;



        if($tipo_pago == "1"){

        }else if ($tipo_pago == "2"){
            $total_monto_credito= 0;
            $registro_venta_pago = DB::select("SELECT id, id_compra FROM pago_compra WHERE id_compra= $request->id");
            $id_pago = $registro_venta_pago[0]->id;
            $registro_venta_credito = DB::select("SELECT id, id_pago, amortizacion, id_forma_pago FROM c_x_pagar WHERE c_x_pagar.id_pago = $id_pago");
            foreach($registro_venta_credito as $credito){
                $total_monto_credito = $total_monto_credito + floatval($credito->amortizacion);
            }
        }

    }*/

    public function anular(Request $request)
    {
        try {
            DB::beginTransaction();

            $objdate = new DateTime();
            $fechaactual = $objdate->format('Y-m-d');
            $hora = $objdate->format('H:i:s');
            $id_usuario = \Auth::user()->id;
            $compra = Compra::findOrFail($request->id);
            
            if ($compra->estado === 'Anulado') {
                return response()->json(['error' => 'La compra ya se encuentra anulada'], 400);
            }

            $compra->estado = 'Anulado';
            $compra->save();

            $detalles = DetalleCompra::where('id_compra', $compra->id)->get();

            foreach ($detalles as $det) {
                
                $lote = Lote::findOrFail($det->id_lote);
                $stock_anterior_lote = $lote->cantidad;
                
                $lote->cantidad -= $det->cantidad;
                if ($lote->cantidad <= 0) {
                    $lote->cantidad = 0;
                    $lote->estado = 0; 
                }
                $lote->save();
                $stock_actual_lote = $lote->cantidad;
                $tienda_articulo_ant = DB::table('tienda_articulo')->where('id', $det->id_producto)->first();
                $stock_general_anterior = $tienda_articulo_ant ? $tienda_articulo_ant->stock : 0;
                DB::statement('CALL stock(?)', [$det->id_producto]);
                $tienda_articulo_act = DB::table('tienda_articulo')->where('id', $det->id_producto)->first();
                $stock_general_actual = $tienda_articulo_act ? $tienda_articulo_act->stock : 0;

                $ajuste = new Ajuste();
                $ajuste->stock = $det->cantidad;
                $ajuste->costo_compra = $det->costo_compra;
                $ajuste->costo_venta = 0;
                $ajuste->costo_unitario = 0;
                $ajuste->costo_mayorista = 0;
                $ajuste->costo_preferencial = 0;

                $ajuste->stock_anterior = $stock_anterior_lote;
                $ajuste->stock_actual = $stock_actual_lote;

                $ajuste->stock_general_anterior = $stock_general_anterior;
                $ajuste->stock_general = $stock_general_actual;
                
                $ajuste->observacion = 'Anulación de Compra Nro: ' . $compra->id;
                $ajuste->id_lote = $lote->id;
                $ajuste->fecha = $fechaactual;
                $ajuste->id_usuario = $id_usuario;
                $ajuste->id_venta = 0;
                $ajuste->id_compra = $compra->id;
                $ajuste->id_motivo_ajuste = 9;
                $ajuste->id_transaccion = $compra->id;
                $ajuste->hora = $hora;
                $ajuste->descuento = $det->descuento;
                $ajuste->save();
            }

            // 3. Registrar en Bitácora (Igual que en tu método guardar)
            $datosBitacora = [
                'tabla' => 'compra',
                'codigo_tabla' => $compra->id,
                'transaccion' => 'anular',
            ];
            $this->guardarBitacora($datosBitacora);

            // Si la lógica de las Cajas la vas a implementar luego, iría aquí
            // if($compra->id_tipo_pago == 1) { ... }

            // Confirmamos los cambios en la BD
            DB::commit();
            
            return response()->json(['message' => 'Compra anulada y stock recalculado correctamente.'], 200);

        } catch (\Exception $e) {
            // Revertimos TODO si falla cualquier paso
            DB::rollBack();
            return response()->json(['error' => 'Error al anular la compra: ' . $e->getMessage()], 500);
        }
    }

    private function correlativoControl(){
        $mayor = DB::table('control')->where('control.tabla','=','Compra')->count();
         return $mayor;
    }
    private function datosNotaCompra($id)
    {
        $compra = Compra::join('users', 'compra.id_usuario', '=', 'users.id')
            ->join('proveedor', 'compra.id_proveedor', '=', 'proveedor.id')
            ->join('forma_pago', 'compra.id_forma_pago', '=', 'forma_pago.id')
            ->join('tipo_pago', 'compra.id_tipo_pago', '=', 'tipo_pago.id')
            ->select(
                'compra.id', 'compra.fecha', 'compra.descripcion', 'compra.sub_total',
                'compra.descuento', 'compra.total', 'compra.estado',
                'compra.total_efectivo', 'compra.total_deposito',
                'users.name as usuario', 'proveedor.nombre as proveedor',
                'forma_pago.nombre as forma_pago', 'tipo_pago.nombre as tipo_pago'
            )
            ->where('compra.id', $id)
            ->firstOrFail();

        $detalles = DetalleCompra::join('tienda_articulo', 'detalle_compra.id_producto', '=', 'tienda_articulo.id')
            ->join('articulo', 'tienda_articulo.id_articulo', '=', 'articulo.id')
            ->join('tienda', 'tienda_articulo.id_tienda', '=', 'tienda.id')
            ->leftJoin('categoria', 'articulo.id_categoria', '=', 'categoria.id')
            ->leftJoin('lote', 'detalle_compra.id_lote', '=', 'lote.id')
            ->select(
                'detalle_compra.costo_compra', 'detalle_compra.cantidad',
                'detalle_compra.sub_total', 'detalle_compra.descuento',
                'articulo.nombre_comercial as articulo', 'tienda.nombre as tienda',
                'categoria.nombre as categoria', 'lote.lote', 'lote.fecha_vecimiento'
            )
            ->where('detalle_compra.id_compra', $id)
            ->orderBy('detalle_compra.id_producto')
            ->get();

        $empresa = MiEmpresa::select(
            'nombre', 'nit', 'representante', 'direccion', 'telefono',
            'localidad', 'Correo as correo', 'sitio_web', 'foto'
        )->first();

        return [
            'compra' => $compra,
            'detalles' => $detalles,
            'empresa' => $empresa,
            'logo' => $this->logoNotaCompra($empresa),
            'fecha_impresion' => now()->format('d/m/Y H:i'),
        ];
    }

    private function logoNotaCompra($empresa)
    {
        $candidatos = [];

        if ($empresa && $empresa->foto) {
            $archivo = basename(str_replace('\\', '/', $empresa->foto));
            $candidatos[] = public_path('img/logo/' . $archivo);
            $candidatos[] = public_path('img/mi_empresa/' . $archivo);
        }

        $candidatos[] = public_path('img/FarmaClick_logo_horizontal.png');

        foreach ($candidatos as $ruta) {
            if (is_file($ruta) && is_readable($ruta)) {
                $mime = function_exists('mime_content_type') ? mime_content_type($ruta) : 'image/png';
                return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($ruta));
            }
        }

        return null;
    }

    private function renderNotaCompra($id, $formato = 'carta')
    {
        abort_unless(in_array($formato, ['carta', 'ticket'], true), 404);

        $datos = $this->datosNotaCompra($id);
        $vista = $formato === 'ticket' ? 'pdf.compra.nota-ticket' : 'pdf.compra.nota-carta';
        $pdf = \PDF::loadView($vista, $datos);

        if ($formato === 'ticket') {
            $alto = max(360, 285 + ($datos['detalles']->count() * 42));
            $pdf->setPaper([0, 0, 226.77, $alto], 'portrait');
        } else {
            $pdf->setPaper('letter', 'portrait');
        }

        return $pdf->stream(sprintf('nota-compra-%s-%06d.pdf', $formato, $id));
    }

    public function notaCompra($id, $formato)
    {
        return $this->renderNotaCompra((int) $id, $formato);
    }

    public function pdfComprasGeneral(Request $request)
    {
        return $this->renderNotaCompra((int) $request->id, 'carta');
    }

    public function pdfComprasGeneral2(Request $request)
    {
        $id = Compra::max('id');
        abort_if(!$id, 404);

        return $this->renderNotaCompra((int) $id, 'carta');
    }
    public function montoT(){
        //$compra=compra::sum('compra.total');
        $compra=compra::select(DB::raw('SUM(compra.total) as total'))->where('compra.estado','!=','Anulado')->get();
        return $compra;
    }
    public function modificar(Request $request){
       // dd($request->id);
        $compra= Compra::findOrFail($request->id);
        $compra->fecha=$request->fecha;
        $compra->save();

    }
    // public function modificarCantidad(Request $request){
    //     try{
    //         DB::beginTransaction();
    //         //dd($request->total);
    //         $id_usuario=\Auth::user()->id;

    //         $compra= Compra::findOrFail($request->id);
    //         $compra->fecha=$request->fecha;
    //         $compra->id_proveedor=$request->id_proveedor;
    //         $compra->descripcion=$request->descripcion;
    //         $compra->id_tipo_pago=$request->id_tipo_pago;
    //         $compra->id_usuario=$id_usuario;
    //         $compra->descuento=$request->descuento;
    //         // if($request->id_tipo_pago_anterior == 1){
    //         //     if($request->id_tipo_pago == 2){
    //         //         $compra->id_forma_pago=1;
    //         //     }
    //         // }else{
    //         //     $compra->id_forma_pago=$request->id_forma_pago;
    //         // }

    //         $compra->id_forma_pago=$request->id_forma_pago;


    //         $compra->save();

    //         $total_d = $request->total_aux;
    //         $total = $request->total;
    //         $total_efectivo = $request->total_efectivo;
    //         $total_deposito = $request->total_deposito;
    //         $total_efectivo_aux = $request->total_efectivo_aux;
    //         $total_deposito_aux = $request->total_deposito_aux;
    //         $objdate = new DateTime();
    //         $fechaactual= $objdate->format('Y-m-d');
    //         $hora= $objdate->format('H:i:s');

    //         //dd($total_efectivo);

    //         if($request->formaPago == 'Efectivo'){
    //             $affected = DB::table('compra')
    //             ->where('id', $request->id)
    //             ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_efectivo' => $request->total]);
    //         }elseif($request->formaPago == 'Transferencia'){
    //             $affected = DB::table('compra')
    //             ->where('id', $request->id)
    //             ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_deposito' =>$request->total]);
    //         }elseif($request->formaPago == 'Pago por QR'){
    //             $affected = DB::table('compra')
    //             ->where('id', $request->id)
    //             ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_deposito' =>$request->total]);
    //         }elseif($request->formaPago == 'Depósito'){
    //             $affected = DB::table('compra')
    //             ->where('id', $request->id)
    //             ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_deposito' =>$request->total]);
    //         }elseif($request->formaPago == 'Mixta'){
    //             $affected = DB::table('compra')
    //             ->where('id', $request->id)
    //             ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_efectivo' => $request->total_efectivo,'total_deposito' => $request->total_deposito]);
    //         }


    //         $detalles = $request->detalle;
    //         foreach($detalles as $ep=>$det){
    //               //dd($det);
    //             $articulo = Articulo::findOrFail($det['id_articulo']);
    //             $articulo->costo_compra=$det['costo_compra'];
    //             $articulo->save();

    //             if($det['articulo_nuevo']!=1){
    //                 $detall = DB::table('detalle_compra')->where('id_compra',$request->id)->where('id_lote',$det['id_lote'])->first();

    //                 $affected = DB::table('detalle_compra')
    //                 ->where('id_compra', $request->id)
    //                 ->where('id_lote',$det['id_lote'])
    //                 ->update(['eliminado' => $det['eliminado']]);
    //             }

    //             if($det['eliminado']==0){
    //             // if($det['id_producto'] == 7468)
    //             // {
    //             //     dd($detall->cantidad,$det['articulo_nuevo'],$det['articulo_nuevo']);
    //             // }
    //                 if ($detall->cantidad >=$det['cantidad']) {
    //                     //dd($detall->cantidad <$det['cantidad']);

    //                     if($det['articulo_nuevo'] == 1){
    //                         $lote = new Lote();
    //                         $lote->id_producto=$det['id_tienda_articulo'];
    //                         $lote->fecha_vecimiento=$det['fecha_vecimiento'];;
    //                         $lote->cantidad= $det['cantidad'];
    //                         $lote->lote= $det['lote'];
    //                         $lote->estado=1;
    //                         $lote->save();


    //                         $obj = new DetalleCompra();
    //                         $obj->id_compra= $compra->id;
    //                         $obj->id_producto= $det['id_tienda_articulo'];
    //                         $obj->cantidad= $det['cantidad'];
    //                         $obj->costo_compra= $det['costo_compra'];
    //                         $obj->sub_total= $det['sub_total'];
    //                         $obj->id_lote= $lote->id;
    //                         $obj->descuento= $det['descuento'];
    //                         $obj->save();

    //                         $tienda_articulo=DB::select("SELECT ta.stock
    //                         FROM tienda_articulo ta
    //                         WHERE ta.id = '$obj->id_producto'");

    //                         $ajuste = new Ajuste();
    //                         $ajuste->stock=$det['cantidad'];
    //                         $ajuste->costo_compra=$det['costo_compra'];
    //                         $ajuste->costo_venta=0;
    //                         $ajuste->costo_unitario=0;
    //                         $ajuste->costo_mayorista=0;
    //                         $ajuste->costo_preferencial=0;
    //                         $ajuste->stock_anterior=0;
    //                         $ajuste->stock_actual=$det['cantidad'];
    //                         $ajuste->stock_general_anterior=$tienda_articulo[0]->stock;
    //                         $ajuste->stock_general=$tienda_articulo[0]->stock +$det['cantidad'];
    //                         $ajuste->observacion=$request->descripcion;
    //                         $ajuste->id_lote=$lote->id;
    //                         $ajuste->fecha=$compra->fecha;
    //                         $ajuste->id_usuario=$id_usuario;
    //                         $ajuste->id_venta=0;
    //                         $ajuste->id_compra=$compra->id;
    //                         $ajuste->id_motivo_ajuste=8;
    //                         $ajuste->id_transaccion=$compra->id;
    //                         $ajuste->descuento=$det['descuento'];
    //                         $ajuste->hora=$hora;
    //                         $ajuste->save();



    //                     }else{

    //                         $affected = DB::table('detalle_compra')
    //                         ->where('id_compra', $request->id)
    //                         ->where('id_producto',$det['id_producto'])
    //                         ->update(['cantidad' => $det['cantidad'],'costo_compra' => $det['costo_compra'],'descuento'=>$det['descuento'],'sub_total'=>$det['cantidad']*$det['costo_compra']-$det['descuento']]);
    //                         DB::table('lote')->where('lote.id','=',$det['id_lote'])->increment('cantidad', $det['cantidad']-$detall->cantidad);

    //                         $affected = DB::table('lote')
    //                         ->where('id',$det['id_lote'])
    //                         ->update(['fecha_vecimiento' => $det['fecha_vecimiento'],'lote' => $det['lote']]);
                            
    //                         //$consulta = DB::select('CALL stock(?)', [$detall->id_producto]);
    //                         $id_producto = $det['id_producto'];
    //                         $tienda_articulo=DB::select("SELECT ta.stock
    //                         FROM tienda_articulo ta
    //                         WHERE ta.id = '$id_producto'");

    //                         //dd($tienda_articulo);

    //                         $total_cantidad = $detall->cantidad - $det['cantidad'];



    //                         $ajuste = new Ajuste();
    //                         $ajuste->stock=$det['cantidad'];
    //                         $ajuste->costo_compra=$det['costo_compra'];
    //                         $ajuste->costo_venta=0;
    //                         $ajuste->costo_unitario=0;
    //                         $ajuste->costo_mayorista=0;
    //                         $ajuste->costo_preferencial=0;
    //                         $ajuste->stock_anterior=$det['stock'];
    //                         $ajuste->stock_actual=$det['stock']- $total_cantidad;
    //                         $ajuste->stock_general_anterior=$tienda_articulo[0]->stock;
    //                         $ajuste->stock_general=$tienda_articulo[0]->stock - $det['cantidad'];
    //                         $ajuste->observacion=$request->descripcion;
    //                         $ajuste->id_lote=$det['id_lote'];
    //                         $ajuste->fecha=$fechaactual;
    //                         $ajuste->id_usuario=$id_usuario;
    //                         $ajuste->id_venta=0;
    //                         $ajuste->id_compra=$request->id;
    //                         $ajuste->id_motivo_ajuste=8;
    //                         $ajuste->id_transaccion=$request->id;
    //                         $ajuste->descuento=$det['descuento'];
    //                         $ajuste->hora=$hora;
    //                         $ajuste->save();
    //                     }

    //                 }

    //                 else {
    //                     if($det['articulo_nuevo'] == 1){
    //                         $lote = new Lote();
    //                         $lote->id_producto=$det['id_tienda_articulo'];
    //                         $lote->fecha_vecimiento=$det['fecha_vecimiento'];;
    //                         $lote->cantidad= $det['cantidad'];
    //                         $lote->lote= $det['lote'];
    //                         $lote->estado=1;
    //                         $lote->save();


    //                         $obj = new DetalleCompra();
    //                         $obj->id_compra= $compra->id;
    //                         $obj->id_producto= $det['id_tienda_articulo'];
    //                         $obj->cantidad= $det['cantidad'];
    //                         $obj->costo_compra= $det['costo_compra'];
    //                         $obj->sub_total= $det['sub_total'];
    //                         $obj->id_lote= $lote->id;
    //                         $obj->save();
    //                     }else
    //                     {
    //                         if($detall->cantidad <=$det['cantidad']){
                            
    //                             $affected = DB::table('detalle_compra')
    //                             ->where('id_compra', $request->id)
    //                             ->where('id_producto',$det['id_producto'])
    //                             ->update(['cantidad' => $det['cantidad'],'costo_compra' => $det['costo_compra'],'descuento'=>$det['descuento'],'sub_total'=>$det['cantidad']*$det['costo_compra']-$det['descuento']]);
    //                             DB::table('lote')->where('lote.id','=',$det['id_lote'])->decrement('cantidad',$detall->cantidad - $det['cantidad']);
    
    //                             $affected = DB::table('lote')
    //                             ->where('id',$det['id_lote'])
    //                             ->update(['fecha_vecimiento' => $det['fecha_vecimiento'],'lote' => $det['lote']]);
    
    
    //                             $ajuste = new Ajuste();
    //                             $ajuste->stock=$det['cantidad'];
    //                             $ajuste->costo_compra=$det['costo_compra'];
    //                             $ajuste->costo_venta=0;
    //                             $ajuste->costo_unitario=0;
    //                             $ajuste->costo_mayorista=0;
    //                             $ajuste->costo_preferencial=0;
    //                             $ajuste->stock_anterior=$det['stock'];
    //                             $ajuste->stock_actual=$det['stock']- ($detall->cantidad - $det['cantidad']);
    //                             $ajuste->observacion=$request->descripcion;
    //                             $ajuste->id_lote=$det['id_lote'];
    //                             $ajuste->fecha=$fechaactual;
    //                             $ajuste->id_usuario=$id_usuario;
    //                             $ajuste->id_venta=0;
    //                             $ajuste->id_compra=$request->id;
    //                             $ajuste->id_motivo_ajuste=8;
    //                             $ajuste->id_transaccion=$request->id;
    //                             $ajuste->descuento=$det['descuento'];
    //                             $ajuste->hora=$hora;
    //                             $ajuste->save();
    
                                
    //                         }else
    //                         {
    //                             //
    //                         }
    //                     }
    //                 }
    //             }else if($det['eliminado'] == 1){
    //                 $affected = DB::table('lote')
    //                 ->where('lote.id', $det['id_lote'])
    //                 ->update(['cantidad' => 0]);

    //                 $affected = DB::table('detalle_compra')
    //                 ->where('id_compra', $request->id)
    //                 ->where('id_producto',$det['id_producto'])
    //                 ->update(['cantidad' => 0,'descuento'=>0,'sub_total'=>0]);

    //                 $affected = DB::table('lote')
    //                 ->where('id',$det['id_lote'])
    //                 ->update(['fecha_vecimiento' => $det['fecha_vecimiento'],'lote' => $det['lote']]);

    //                 $ajuste = new Ajuste();
    //                 $ajuste->stock=$det['cantidad'];
    //                 $ajuste->costo_compra=$det['costo_compra'];
    //                 $ajuste->costo_venta=0;
    //                 $ajuste->costo_unitario=0;
    //                 $ajuste->costo_mayorista=0;
    //                 $ajuste->costo_preferencial=0;
    //                 $ajuste->stock_anterior=$det['stock'];
    //                 $ajuste->stock_actual=0;
    //                 $ajuste->observacion=$request->descripcion;
    //                 $ajuste->id_lote=$det['id_lote'];
    //                 $ajuste->fecha=$fechaactual;
    //                 $ajuste->id_usuario=$id_usuario;
    //                 $ajuste->id_venta=0;
    //                 $ajuste->id_compra=$request->id;
    //                 $ajuste->id_motivo_ajuste=8;
    //                 $ajuste->id_transaccion=$request->id;
    //                 $ajuste->descuento=$det['descuento'];
    //                 $ajuste->hora=$hora;
    //                 $ajuste->save();

    //             }

    //         }
    //         if($request->formaPago == 'Efectivo'){
    //             //dd($request->formaPago);
    //             $this->descontarCaja($total_d,$id_usuario);
    //             $this->actualizarCaja($total,$id_usuario);

    //             $this->descontarCajaContado($total_d,$id_usuario);
    //             $this->actualizarCajaContado($total,$id_usuario);

    //         }
    //         // elseif($request->formaPago == 'Mixta'){

    //         //     $this->descontarCaja($total_efectivo_aux,$id_usuario);
    //         //     $this->actualizarCaja($total_efectivo,$id_usuario);

    //         //     $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
    //         //     $this->actualizarCajaContado($total_efectivo,$id_usuario);

    //         //     //agregado

    //         //     $this->descontarCaja($total_deposito_aux,$id_usuario);
    //         //     $this->actualizarCaja($total_deposito,$id_usuario);

    //         //     $this->descontarCajaContadoDeposito($total_deposito_aux,$id_usuario);
    //         //     $this->actualizarCajaContadoDeposito($total_deposito,$id_usuario);
    //         //     //fin
    //         //     $affected = DB::table('pago_compra')
    //         //     ->where('id_compra', $request->id)
    //         //     ->update(['monto' => $total_efectivo,'saldo'=>$total_efectivo]);
    //         // }
    //         elseif($request->formaPago == 'Cuenta por Cobrar'){

    //             $affected = DB::table('pago_compra')
    //             ->where('id_compra', $request->id)
    //             ->update(['monto' => $total,'saldo'=>$total]);

    //             $affected = DB::table('c_x_pagar')
    //             ->where('id_pago', $request->id)
    //             ->update(['monto_total' => $total,'saldo'=>$total]);
    //         }else
    //         {
    //             //
    //         }




    //         //MODIFICAR FORMA DE PAGO

    //         if($request->formaPagoAux=='Efectivo'){
    //             if($request->formaPago == 'Transferencia' || $request->formaPago == 'Pago por QR' || $request->formaPago == 'Depósito'){
    //                 //dd($total,$total_d);
    //                     $this->descontarCaja($total_d,$id_usuario);
    //                     $this->descontarCajaContado($total_d,$id_usuario);
    //                 //nuevo
    //                     $this->actualizarCaja($total,$id_usuario);
    //                     $this->actualizarCajaContadoDeposito($total,$id_usuario);
    //                 //fin

    //                 $affected = DB::table('compra')
    //                 ->where('id', $request->id)
    //                 ->update(['total_efectivo' => 0,'total_deposito' => $total]);
    //             }else {

    //             }
    //         }
    //         if($request->formaPagoAux == 'Transferencia' || $request->formaPagoAux == 'Pago por QR' || $request->formaPagoAux == 'Depósito'){
                
    //             if($request->formaPago=='Efectivo'){
                    
    //                 //nuevo
    //                     $this->descontarCaja($total_d,$id_usuario);
    //                     $this->descontarCajaContadoDeposito($total_d,$id_usuario);
    //                 //fin
    //                 $this->actualizarCaja($total_d,$id_usuario);
    //                 $this->actualizarCajaContado($total_d,$id_usuario);

    //                 $affected = DB::table('compra')
    //                 ->where('id', $request->id)
    //                 ->update(['total_efectivo' => $total,'total_deposito' => 0]);
    //             }else
    //             {
    //                 if($request->formaPago=='Transferencia' || $request->formaPago=='Pago por QR' || $request->formaPago=='Depósito'){
    //                     //dd($request->formaPago);
    //                     $this->descontarCaja($total_d,$id_usuario);
    //                     $this->descontarCajaContadoDeposito($total_d,$id_usuario);
    //                     //nuevo
    //                     $this->actualizarCaja($total,$id_usuario);
    //                     $this->actualizarCajaContadoDeposito($total,$id_usuario);
    //                     //fin
    //                  }
    //             }
    //             if($request->formaPago == 'Mixta')
    //             {
    //                 $this->descontarCajaContadoDeposito($total_deposito_aux,$id_usuario);
    //                 $this->descontarCaja($total_deposito_aux,$id_usuario);

    //                 $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
    //                 $this->descontarCaja($total_efectivo_aux,$id_usuario);


    //                 $this->actualizarCajaContado($total_efectivo,$id_usuario);
    //                 $this->actualizarCaja($total_efectivo,$id_usuario);
                    
    //                 $this->actualizarCajaContadoDeposito($total_deposito,$id_usuario);
    //                 $this->actualizarCaja($total_deposito,$id_usuario);

    //             }
    //         }

    //         if($request->formaPagoAux=='Mixta'){
    //             if($request->formaPago == 'Mixta')
    //             {
    //                 $this->descontarCajaContadoDeposito($total_deposito_aux,$id_usuario);
    //                 $this->descontarCaja($total_deposito_aux,$id_usuario);

    //                 $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
    //                 $this->descontarCaja($total_efectivo_aux,$id_usuario);


    //                 $this->actualizarCajaContado($total_efectivo,$id_usuario);
    //                 $this->actualizarCaja($total_efectivo,$id_usuario);
                    
    //                 $this->actualizarCajaContadoDeposito($total_deposito,$id_usuario);
    //                 $this->actualizarCaja($total_deposito,$id_usuario);

    //             }
    //             if($request->formaPago == 'Efectivo'){
    //                 $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
    //                 $this->actualizarCajaContado($total_d,$id_usuario);
    //                 $this->descontarCaja($total_efectivo_aux,$id_usuario);
    //                 //nuevo 
    //                 $this->descontarCajaContadoDeposito($total_deposito_aux,$id_usuario);
    //                 $this->descontarCaja($total_deposito_aux,$id_usuario);
    //                 //
    //                 $this->actualizarCaja($total_d,$id_usuario);
    //                 $affected = DB::table('compra')
    //                 ->where('id', $request->id)
    //                 ->update(['total_efectivo' => $total,'total_deposito' => 0]);

    //             }elseif($request->formaPago == 'Transferencia' || $request->formaPago == 'Pago por QR' || $request->formaPago == 'Depósito'){
    //                 $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
    //                 $this->descontarCaja($total_efectivo_aux,$id_usuario);
    //                 //nuevo
    //                 $this->descontarCajaContadoDeposito($total_deposito_aux,$id_usuario);
    //                 $this->descontarCaja($total_deposito_aux,$id_usuario);

    //                 $this->actualizarCaja($total,$id_usuario);
    //                 $this->actualizarCajaContadoDeposito($total,$id_usuario);
    //                 //fin

    //                 $affected = DB::table('compra')
    //                 ->where('id', $request->id)
    //                 ->update(['total_efectivo' => 0,'total_deposito' => $total]);
    //             }
    //         }




    //         if($request->id_tipo_pago_anterior == 2){
    //             if($request->id_tipo_pago == 1){

    //                 $total_monto_credito= 0;
    //                 $registro_venta_pago = DB::select("SELECT id, id_compra FROM pago_compra WHERE id_compra= $request->id");

    //                 $id_pago = $registro_venta_pago[0]->id;

    //                 $registro_venta_credito = DB::select("SELECT id, id_pago, amortizacion FROM c_x_pagar WHERE c_x_pagar.id_pago = $id_pago");
    //                 foreach($registro_venta_credito as $credito){
    //                     $total_monto_credito = $total_monto_credito + floatval($credito->amortizacion);
    //                 }

    //                 $obj6 = CXPagar::where('c_x_pagar.id_pago','=',$id_pago);
    //                 $obj6->delete();

    //                 $this->descontarCaja($total_monto_credito,$id_usuario);
    //                 $this->descontarCajaCredito($total_monto_credito,$id_usuario);

    //                 $affected = DB::table('pago_compra')
    //                 ->where('id', $id_pago)
    //                 ->update(['id_tipo_pago' => $request->id_tipo_pago]);

    //                 if($request->formaPago=='Efectivo'){

    //                         $this->actualizarCaja($total,$id_usuario);
    //                         $this->actualizarCajaContado($total,$id_usuario);

    //                         $affected = DB::table('compra')
    //                         ->where('id', $request->id)
    //                         ->update(['total_efectivo' => $total,'total_deposito' => 0]);

    //                 }
    //                 if($request->formaPago == 'Transferencia' || $request->formaPago == 'Pago por QR' || $request->formaPago == 'Depósito'){
    //                         //dd($total);

    //                     //    $this->descontarCaja($total_d,$id_usuario);
    //                     //     $this->descontarCajaContadoDeposito($total_d,$id_usuario);
    //                         // //nuevo
    //                         $this->actualizarCaja($total,$id_usuario);
    //                         $this->actualizarCajaContadoDeposito($total,$id_usuario);
    //                         //fin
                         
    //                         $affected = DB::table('compra')
    //                         ->where('id', $request->id)
    //                         ->update(['total_efectivo' => 0,'total_deposito' => $total]);

    //                 }

    //                 if($request->formaPago == 'Mixta'){
    //                     //dd($request->formaPago);
    //                         $this->actualizarCajaContado($total_efectivo,$id_usuario);
    //                         $this->actualizarCajaContadoDeposito($total_deposito,$id_usuario);
    //                         $this->actualizarCaja($total_efectivo,$id_usuario);
    //                         $this->actualizarCaja($total_deposito,$id_usuario);

    //                         $affected = DB::table('compra')
    //                         ->where('id', $request->id)
    //                         ->update(['total_efectivo' => $total_efectivo,'total_deposito' => $total_deposito]);
    //                 }
    //             }
    //         }elseif($request->id_tipo_pago_anterior == 1){
    //             if($request->id_tipo_pago == 2){

    //                 $registro_venta_pago = DB::select("SELECT id, id_compra FROM pago_compra WHERE id_compra= $request->id");
    //                 $id_pago = $registro_venta_pago[0]->id;

    //                 $affected = DB::table('pago_compra')
    //                 ->where('id', $id_pago)
    //                 ->update(['id_tipo_pago' => $request->id_tipo_pago]);


    //                 $cxcobrar = new CXPagar();
    //                 $cxcobrar->fecha = $request->fecha;
    //                 //$cxcobrar->anticipo = $request->anticipo;
    //                 $cxcobrar->monto_total = $request->total;
    //                 $cxcobrar->descripcion = "";
    //                 $cxcobrar->saldo = $request->total;
    //                 $cxcobrar->id_pago = $id_pago;
    //                 $cxcobrar->save();


    //                 if($request->formaPagoAux=='Efectivo'){
    //                         $this->descontarCaja($total_d,$id_usuario);
    //                         $this->descontarCajaContado($total_d,$id_usuario);

    //                         $affected = DB::table('compra')
    //                         ->where('id', $request->id)
    //                         ->update(['total_efectivo' => 0,'total_deposito' => 0]);

    //                 }
    //                 if($request->formaPagoAux == 'Transferencia' || $request->formaPagoAux == 'Pago por QR' || $request->formaPagoAux == 'Depósito'){
    //                     if($request->formaPagoAux=='Transferencia' || $request->formaPagoAux=='Pago por QR' || $request->formaPagoAux=='Depósito'){
    //                         $this->descontarCaja($total_d,$id_usuario);
    //                         $this->descontarCajaContadoDeposito($total_d,$id_usuario);
    //                         ////nuevo
    //                      }
    //                     if($request->formaPago=='Efectivo'){
    //                         $this->actualizarCaja($total_d,$id_usuario);
    //                         $this->actualizarCajaContado($total_d,$id_usuario);

    //                         $affected = DB::table('compra')
    //                         ->where('id', $request->id)
    //                         ->update(['total_efectivo' => $total,'total_deposito' => 0]);
    //                     }else
    //                     {
    //                         //
    //                     }
    //                 }

    //                 if($request->formaPagoAux=='Mixta'){
    //                         $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
    //                         $this->descontarCajaContadoDeposito($total_deposito_aux,$id_usuario);

    //                         $this->descontarCaja($total_efectivo_aux,$id_usuario);
    //                         $this->descontarCaja($total_deposito_aux,$id_usuario);

    //                         $affected = DB::table('compra')
    //                         ->where('id', $request->id)
    //                         ->update(['total_efectivo' => 0,'total_deposito' => 0]);
    //                 }
    //             }
    //         }






    //         $affected = DB::table('pago_compra')
    //         ->where('id_compra', $request->id)
    //         ->update(['monto' => $total,'saldo'=>$total]);

    //         DB::commit();
    //     } catch (Exception $e){
    //         DB::rollBack();
    //     }
    // }
    public function modificarCantidad(Request $request){
        try{
            DB::beginTransaction();
            //dd($request->total);
            $id_usuario=\Auth::user()->id;

            $compra= Compra::findOrFail($request->id);
            $compra->fecha=$request->fecha;
            $compra->id_proveedor=$request->id_proveedor;
            $compra->descripcion=$request->descripcion;
            $compra->id_tipo_pago=$request->id_tipo_pago;
            $compra->id_usuario=$id_usuario;
            $compra->descuento=$request->descuento;
            // if($request->id_tipo_pago_anterior == 1){
            //     if($request->id_tipo_pago == 2){
            //         $compra->id_forma_pago=1;
            //     }
            // }else{
            //     $compra->id_forma_pago=$request->id_forma_pago;
            // }

            $compra->id_forma_pago=$request->id_forma_pago;


            $compra->save();

            $total_d = $request->total_aux;
            $total = $request->total;
            $total_efectivo = $request->total_efectivo;
            $total_deposito = $request->total_deposito;
            $total_efectivo_aux = $request->total_efectivo_aux;
            $total_deposito_aux = $request->total_deposito_aux;
            $objdate = new DateTime();
            $fechaactual= $objdate->format('Y-m-d');
            $hora= $objdate->format('H:i:s');

            //dd($total_efectivo);

            if($request->formaPago == 'Efectivo'){
                $affected = DB::table('compra')
                ->where('id', $request->id)
                ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_efectivo' => $request->total]);
            }elseif($request->formaPago == 'Transferencia'){
                $affected = DB::table('compra')
                ->where('id', $request->id)
                ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_deposito' =>$request->total]);
            }elseif($request->formaPago == 'Pago por QR'){
                $affected = DB::table('compra')
                ->where('id', $request->id)
                ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_deposito' =>$request->total]);
            }elseif($request->formaPago == 'Depósito'){
                $affected = DB::table('compra')
                ->where('id', $request->id)
                ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_deposito' =>$request->total]);
            }elseif($request->formaPago == 'Mixta'){
                $affected = DB::table('compra')
                ->where('id', $request->id)
                ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_efectivo' => $request->total_efectivo,'total_deposito' => $request->total_deposito]);
            }


            $detalles = $request->detalle;
            //dd($detalles);
            foreach($detalles as $ep=>$det){
                $articulo = Articulo::findOrFail($det['id_articulo']);
                $articulo->costo_compra=$det['costo_compra'];
                //$articulo->descuento=$det['descuento'];
                $articulo->save();

                if($det['articulo_nuevo']!=1){
                    $detall = DB::table('detalle_compra')->where('id_compra',$request->id)->where('id_lote',$det['id_lote'])->first();

                    $affected = DB::table('detalle_compra')
                    ->where('id_compra', $request->id)
                    ->where('id_lote',$det['id_lote'])
                    ->update(['eliminado' => $det['eliminado']]);
                }

                if($det['eliminado']==0){
                // if($det['id_producto'] == 7468)
                // {
                //     dd($detall->cantidad,$det['articulo_nuevo'],$det['articulo_nuevo']);
                // }
                //dd($detall->cantidad >=$det['cantidad']);
                    if ($detall->cantidad >=$det['cantidad']) {
                        //dd($detall->cantidad <$det['cantidad']);

                        if($det['articulo_nuevo'] == 1){
                            $lote = new Lote();
                            $lote->id_producto=$det['id_tienda_articulo'];
                            $lote->fecha_vecimiento=$det['fecha_vecimiento'];;
                            $lote->cantidad= $det['cantidad'];
                            $lote->lote= $det['lote'];
                            $lote->estado=1;
                            $lote->save();


                            $obj = new DetalleCompra();
                            $obj->id_compra= $compra->id;
                            $obj->id_producto= $det['id_tienda_articulo'];
                            $obj->cantidad= $det['cantidad'];
                            $obj->costo_compra= $det['costo_compra'];
                            $obj->sub_total= $det['sub_total'];
                            $obj->id_lote= $lote->id;
                            $obj->descuento= $det['descuento'];
                            $obj->save();

                            $tienda_articulo=DB::select("SELECT ta.stock
                            FROM tienda_articulo ta
                            WHERE ta.id = '$obj->id_producto'");

                            $ajuste = new Ajuste();
                            $ajuste->stock=$det['cantidad'];
                            $ajuste->costo_compra=$det['costo_compra'];
                            $ajuste->costo_venta=0;
                            $ajuste->costo_unitario=0;
                            $ajuste->costo_mayorista=0;
                            $ajuste->costo_preferencial=0;
                            $ajuste->stock_anterior=0;
                            $ajuste->stock_actual=$det['cantidad'];
                            $ajuste->stock_general_anterior=$tienda_articulo[0]->stock;
                            $ajuste->stock_general=$tienda_articulo[0]->stock +$det['cantidad'];
                            $ajuste->observacion=$request->descripcion;
                            $ajuste->id_lote=$lote->id;
                            $ajuste->fecha=$compra->fecha;
                            $ajuste->id_usuario=$id_usuario;
                            $ajuste->id_venta=0;
                            $ajuste->id_compra=$compra->id;
                            $ajuste->id_motivo_ajuste=8;
                            $ajuste->id_transaccion=$compra->id;
                            $ajuste->descuento=$det['descuento'];
                            $ajuste->hora=$hora;
                            $ajuste->save();



                        }else{

                            $affected = DB::table('detalle_compra')
                            ->where('id_compra', $request->id)
                            ->where('id_producto',$det['id_producto'])
                            ->update(['cantidad' => $det['cantidad'],'costo_compra' => $det['costo_compra'],'descuento'=>$det['descuento'],'sub_total'=>$det['cantidad']*$det['costo_compra']-$det['descuento']]);
                            DB::table('lote')->where('lote.id','=',$det['id_lote'])->increment('cantidad', $det['cantidad']-$detall->cantidad);

                            $affected = DB::table('lote')
                            ->where('id',$det['id_lote'])
                            ->update(['fecha_vecimiento' => $det['fecha_vecimiento'],'lote' => $det['lote']]);
                            
                            $consulta = DB::select('CALL stock(?)', [$detall->id_producto]);
                            
                            $id_producto = $det['id_producto'];
                            $tienda_articulo=DB::select("SELECT ta.stock
                            FROM tienda_articulo ta
                            WHERE ta.id = '$detall->id_producto'");

                            //dd($tienda_articulo);

                            $total_cantidad = $detall->cantidad - $det['cantidad'];
                            //DD($total_cantidad);


                            $ajuste = new Ajuste();
                            $ajuste->stock=$total_cantidad;
                            $ajuste->costo_compra=$det['costo_compra'];
                            $ajuste->costo_venta=0;
                            $ajuste->costo_unitario=0;
                            $ajuste->costo_mayorista=0;
                            $ajuste->costo_preferencial=0;
                            $ajuste->stock_anterior=$det['stock'];
                            $ajuste->stock_actual=$det['stock']- $total_cantidad;
                            $ajuste->stock_general_anterior=$tienda_articulo[0]->stock + $total_cantidad;
                            $ajuste->stock_general=$tienda_articulo[0]->stock;
                            $ajuste->observacion=$request->descripcion;
                            $ajuste->id_lote=$det['id_lote'];
                            $ajuste->fecha=$fechaactual;
                            $ajuste->id_usuario=$id_usuario;
                            $ajuste->id_venta=0;
                            $ajuste->id_compra=$request->id;
                            $ajuste->id_motivo_ajuste=8;
                            $ajuste->id_transaccion=$request->id;
                            $ajuste->descuento=$det['descuento'];
                            $ajuste->hora=$hora;
                            $ajuste->save();
                        }

                    }

                    else {
                        if($det['articulo_nuevo'] == 1){
                            $lote = new Lote();
                            $lote->id_producto=$det['id_tienda_articulo'];
                            $lote->fecha_vecimiento=$det['fecha_vecimiento'];;
                            $lote->cantidad= $det['cantidad'];
                            $lote->lote= $det['lote'];
                            $lote->estado=1;
                            $lote->save();


                            $obj = new DetalleCompra();
                            $obj->id_compra= $compra->id;
                            $obj->id_producto= $det['id_tienda_articulo'];
                            $obj->cantidad= $det['cantidad'];
                            $obj->costo_compra= $det['costo_compra'];
                            $obj->sub_total= $det['sub_total'];
                            $obj->id_lote= $lote->id;
                            $obj->save();
                        }else
                        {
                            if($detall->cantidad <=$det['cantidad']){
                            
                                $affected = DB::table('detalle_compra')
                                ->where('id_compra', $request->id)
                                ->where('id_producto',$det['id_producto'])
                                ->update(['cantidad' => $det['cantidad'],'costo_compra' => $det['costo_compra'],'descuento'=>$det['descuento'],'sub_total'=>$det['cantidad']*$det['costo_compra']-$det['descuento']]);
                                DB::table('lote')->where('lote.id','=',$det['id_lote'])->decrement('cantidad',$detall->cantidad - $det['cantidad']);
    
                                $affected = DB::table('lote')
                                ->where('id',$det['id_lote'])
                                ->update(['fecha_vecimiento' => $det['fecha_vecimiento'],'lote' => $det['lote']]);
    
                                $consulta = DB::select('CALL stock(?)', [$detall->id_producto]);
                            
                                $id_producto = $det['id_producto'];
                                $tienda_articulo=DB::select("SELECT ta.stock
                                FROM tienda_articulo ta
                                WHERE ta.id = '$detall->id_producto'");
    
                                $ajuste = new Ajuste();
                                $ajuste->stock=$det['cantidad'] - $detall->cantidad;
                                $ajuste->costo_compra=$det['costo_compra'];
                                $ajuste->costo_venta=0;
                                $ajuste->costo_unitario=0;
                                $ajuste->costo_mayorista=0;
                                $ajuste->costo_preferencial=0;
                                $ajuste->stock_anterior=$det['stock'];
                                $ajuste->stock_actual=$det['stock']- ($detall->cantidad - $det['cantidad']);
                                $ajuste->stock_general_anterior=$tienda_articulo[0]->stock + ($detall->cantidad - $det['cantidad']);
                                $ajuste->stock_general=$tienda_articulo[0]->stock;
                                $ajuste->observacion=$request->descripcion;
                                $ajuste->id_lote=$det['id_lote'];
                                $ajuste->fecha=$fechaactual;
                                $ajuste->id_usuario=$id_usuario;
                                $ajuste->id_venta=0;
                                $ajuste->id_compra=$request->id;
                                $ajuste->id_motivo_ajuste=8;
                                $ajuste->id_transaccion=$request->id;
                                $ajuste->descuento=$det['descuento'];
                                $ajuste->hora=$hora;
                                $ajuste->save();
    
                                
                            }else
                            {
                                //
                            }
                        }
                    }
                }else if($det['eliminado'] == 1){
                    $affected = DB::table('lote')
                    ->where('lote.id', $det['id_lote'])
                    ->update(['cantidad' => 0]);

                    $affected = DB::table('detalle_compra')
                    ->where('id_compra', $request->id)
                    ->where('id_producto',$det['id_producto'])
                    ->update(['cantidad' => 0,'descuento'=>0,'sub_total'=>0]);

                    $affected = DB::table('lote')
                    ->where('id',$det['id_lote'])
                    ->update(['fecha_vecimiento' => $det['fecha_vecimiento'],'lote' => $det['lote']]);

                    $ajuste = new Ajuste();
                    $ajuste->stock=$det['cantidad'];
                    $ajuste->costo_compra=$det['costo_compra'];
                    $ajuste->costo_venta=0;
                    $ajuste->costo_unitario=0;
                    $ajuste->costo_mayorista=0;
                    $ajuste->costo_preferencial=0;
                    $ajuste->stock_anterior=$det['stock'];
                    $ajuste->stock_actual=0;
                    $ajuste->observacion=$request->descripcion;
                    $ajuste->id_lote=$det['id_lote'];
                    $ajuste->fecha=$fechaactual;
                    $ajuste->id_usuario=$id_usuario;
                    $ajuste->id_venta=0;
                    $ajuste->id_compra=$request->id;
                    $ajuste->id_motivo_ajuste=8;
                    $ajuste->id_transaccion=$request->id;
                    $ajuste->descuento=$det['descuento'];
                    $ajuste->hora=$hora;
                    $ajuste->save();

                }

            }
            if($request->formaPago == 'Efectivo'){
                //dd($request->formaPago);
                // $this->descontarCaja($total_d,$id_usuario);
                // $this->actualizarCaja($total,$id_usuario);

                // $this->descontarCajaContado($total_d,$id_usuario);
                // $this->actualizarCajaContado($total,$id_usuario);

            }
            // elseif($request->formaPago == 'Mixta'){

            //     $this->descontarCaja($total_efectivo_aux,$id_usuario);
            //     $this->actualizarCaja($total_efectivo,$id_usuario);

            //     $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
            //     $this->actualizarCajaContado($total_efectivo,$id_usuario);

            //     //agregado

            //     $this->descontarCaja($total_deposito_aux,$id_usuario);
            //     $this->actualizarCaja($total_deposito,$id_usuario);

            //     $this->descontarCajaContadoDeposito($total_deposito_aux,$id_usuario);
            //     $this->actualizarCajaContadoDeposito($total_deposito,$id_usuario);
            //     //fin
            //     $affected = DB::table('pago_compra')
            //     ->where('id_compra', $request->id)
            //     ->update(['monto' => $total_efectivo,'saldo'=>$total_efectivo]);
            // }
            elseif($request->formaPago == 'Cuenta por Cobrar'){

                $affected = DB::table('pago_compra')
                ->where('id_compra', $request->id)
                ->update(['monto' => $total,'saldo'=>$total]);

                $affected = DB::table('c_x_pagar')
                ->where('id_pago', $request->id)
                ->update(['monto_total' => $total,'saldo'=>$total]);
            }else
            {
                //
            }




            //MODIFICAR FORMA DE PAGO

            if($request->formaPagoAux=='Efectivo'){
                if($request->formaPago == 'Transferencia' || $request->formaPago == 'Pago por QR' || $request->formaPago == 'Depósito'){
                    //dd($total,$total_d);
                       // $this->descontarCaja($total_d,$id_usuario);
                        $this->descontarCajaContado($total_d,$id_usuario);
                    //nuevo
                       // $this->actualizarCaja($total,$id_usuario);
                       // $this->actualizarCajaContadoDeposito($total,$id_usuario);
                    //fin

                    $affected = DB::table('compra')
                    ->where('id', $request->id)
                    ->update(['total_efectivo' => 0,'total_deposito' => $total]);
                }else {

                }
            }
            if($request->formaPagoAux == 'Transferencia' || $request->formaPagoAux == 'Pago por QR' || $request->formaPagoAux == 'Depósito'){
                
                if($request->formaPago=='Efectivo'){
                    
                    //nuevo
                        $this->descontarCaja($total_d,$id_usuario);
                        $this->descontarCajaContadoDeposito($total_d,$id_usuario);
                    //fin
                    //$this->actualizarCaja($total_d,$id_usuario);
                    //$this->actualizarCajaContado($total_d,$id_usuario);

                    $affected = DB::table('compra')
                    ->where('id', $request->id)
                    ->update(['total_efectivo' => $total,'total_deposito' => 0]);
                }else
                {
                    if($request->formaPago=='Transferencia' || $request->formaPago=='Pago por QR' || $request->formaPago=='Depósito'){
                        //dd($request->formaPago);
                        // $this->descontarCaja($total_d,$id_usuario);
                        // $this->descontarCajaContadoDeposito($total_d,$id_usuario);
                        // //nuevo
                        // $this->actualizarCaja($total,$id_usuario);
                        // $this->actualizarCajaContadoDeposito($total,$id_usuario);
                        //fin
                     }
                }
                if($request->formaPago == 'Mixta')
                {
                    // $this->descontarCajaContadoDeposito($total_deposito_aux,$id_usuario);
                    // $this->descontarCaja($total_deposito_aux,$id_usuario);

                    // $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
                    // $this->descontarCaja($total_efectivo_aux,$id_usuario);


                    // $this->actualizarCajaContado($total_efectivo,$id_usuario);
                    // $this->actualizarCaja($total_efectivo,$id_usuario);
                    
                    // $this->actualizarCajaContadoDeposito($total_deposito,$id_usuario);
                    // $this->actualizarCaja($total_deposito,$id_usuario);

                }
            }

            if($request->formaPagoAux=='Mixta'){
                if($request->formaPago == 'Mixta')
                {
                    // $this->descontarCajaContadoDeposito($total_deposito_aux,$id_usuario);
                    // $this->descontarCaja($total_deposito_aux,$id_usuario);

                    // $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
                    // $this->descontarCaja($total_efectivo_aux,$id_usuario);


                    // $this->actualizarCajaContado($total_efectivo,$id_usuario);
                    // $this->actualizarCaja($total_efectivo,$id_usuario);
                    
                    // $this->actualizarCajaContadoDeposito($total_deposito,$id_usuario);
                    // $this->actualizarCaja($total_deposito,$id_usuario);

                }
                if($request->formaPago == 'Efectivo'){
                    // $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
                    // $this->actualizarCajaContado($total_d,$id_usuario);
                    // $this->descontarCaja($total_efectivo_aux,$id_usuario);
                    // //nuevo 
                    // $this->descontarCajaContadoDeposito($total_deposito_aux,$id_usuario);
                    // $this->descontarCaja($total_deposito_aux,$id_usuario);
                    // //
                    // $this->actualizarCaja($total_d,$id_usuario);
                    $affected = DB::table('compra')
                    ->where('id', $request->id)
                    ->update(['total_efectivo' => $total,'total_deposito' => 0]);

                }elseif($request->formaPago == 'Transferencia' || $request->formaPago == 'Pago por QR' || $request->formaPago == 'Depósito'){
                    // $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
                    // $this->descontarCaja($total_efectivo_aux,$id_usuario);
                    // //nuevo
                    // $this->descontarCajaContadoDeposito($total_deposito_aux,$id_usuario);
                    // $this->descontarCaja($total_deposito_aux,$id_usuario);

                    // $this->actualizarCaja($total,$id_usuario);
                    // $this->actualizarCajaContadoDeposito($total,$id_usuario);
                    //fin

                    $affected = DB::table('compra')
                    ->where('id', $request->id)
                    ->update(['total_efectivo' => 0,'total_deposito' => $total]);
                }
            }




            if($request->id_tipo_pago_anterior == 2){
                if($request->id_tipo_pago == 1){

                    $total_monto_credito= 0;
                    $registro_venta_pago = DB::select("SELECT id, id_compra FROM pago_compra WHERE id_compra= $request->id");

                    $id_pago = $registro_venta_pago[0]->id;

                    $registro_venta_credito = DB::select("SELECT id, id_pago, amortizacion FROM c_x_pagar WHERE c_x_pagar.id_pago = $id_pago");
                    foreach($registro_venta_credito as $credito){
                        $total_monto_credito = $total_monto_credito + floatval($credito->amortizacion);
                    }

                    $obj6 = CXPagar::where('c_x_pagar.id_pago','=',$id_pago);
                    $obj6->delete();

                    // $this->descontarCaja($total_monto_credito,$id_usuario);
                    // $this->descontarCajaCredito($total_monto_credito,$id_usuario);

                    $affected = DB::table('pago_compra')
                    ->where('id', $id_pago)
                    ->update(['id_tipo_pago' => $request->id_tipo_pago]);

                    if($request->formaPago=='Efectivo'){

                            // $this->actualizarCaja($total,$id_usuario);
                            // $this->actualizarCajaContado($total,$id_usuario);

                            $affected = DB::table('compra')
                            ->where('id', $request->id)
                            ->update(['total_efectivo' => $total,'total_deposito' => 0]);

                    }
                    if($request->formaPago == 'Transferencia' || $request->formaPago == 'Pago por QR' || $request->formaPago == 'Depósito'){
                            //dd($total);

                        //    $this->descontarCaja($total_d,$id_usuario);
                        //     $this->descontarCajaContadoDeposito($total_d,$id_usuario);
                            // //nuevo
                            // $this->actualizarCaja($total,$id_usuario);
                            // $this->actualizarCajaContadoDeposito($total,$id_usuario);
                            //fin
                         
                            $affected = DB::table('compra')
                            ->where('id', $request->id)
                            ->update(['total_efectivo' => 0,'total_deposito' => $total]);

                    }

                    if($request->formaPago == 'Mixta'){
                        //dd($request->formaPago);
                            // $this->actualizarCajaContado($total_efectivo,$id_usuario);
                            // $this->actualizarCajaContadoDeposito($total_deposito,$id_usuario);
                            // $this->actualizarCaja($total_efectivo,$id_usuario);
                            // $this->actualizarCaja($total_deposito,$id_usuario);

                            $affected = DB::table('compra')
                            ->where('id', $request->id)
                            ->update(['total_efectivo' => $total_efectivo,'total_deposito' => $total_deposito]);
                    }
                }
            }elseif($request->id_tipo_pago_anterior == 1){
                if($request->id_tipo_pago == 2){

                    $registro_venta_pago = DB::select("SELECT id, id_compra FROM pago_compra WHERE id_compra= $request->id");
                    $id_pago = $registro_venta_pago[0]->id;

                    $affected = DB::table('pago_compra')
                    ->where('id', $id_pago)
                    ->update(['id_tipo_pago' => $request->id_tipo_pago]);


                    $cxcobrar = new CXPagar();
                    $cxcobrar->fecha = $request->fecha;
                    //$cxcobrar->anticipo = $request->anticipo;
                    $cxcobrar->monto_total = $request->total;
                    $cxcobrar->descripcion = "";
                    $cxcobrar->saldo = $request->total;
                    $cxcobrar->id_pago = $id_pago;
                    $cxcobrar->save();


                    if($request->formaPagoAux=='Efectivo'){
                            // $this->descontarCaja($total_d,$id_usuario);
                            // $this->descontarCajaContado($total_d,$id_usuario);

                            $affected = DB::table('compra')
                            ->where('id', $request->id)
                            ->update(['total_efectivo' => 0,'total_deposito' => 0]);

                    }
                    if($request->formaPagoAux == 'Transferencia' || $request->formaPagoAux == 'Pago por QR' || $request->formaPagoAux == 'Depósito'){
                        if($request->formaPagoAux=='Transferencia' || $request->formaPagoAux=='Pago por QR' || $request->formaPagoAux=='Depósito'){
                            // $this->descontarCaja($total_d,$id_usuario);
                            // $this->descontarCajaContadoDeposito($total_d,$id_usuario);
                            ////nuevo
                         }
                        if($request->formaPago=='Efectivo'){
                            // $this->actualizarCaja($total_d,$id_usuario);
                            // $this->actualizarCajaContado($total_d,$id_usuario);

                            $affected = DB::table('compra')
                            ->where('id', $request->id)
                            ->update(['total_efectivo' => $total,'total_deposito' => 0]);
                        }else
                        {
                            //
                        }
                    }

                    if($request->formaPagoAux=='Mixta'){
                            // $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
                            // $this->descontarCajaContadoDeposito($total_deposito_aux,$id_usuario);

                            // $this->descontarCaja($total_efectivo_aux,$id_usuario);
                            // $this->descontarCaja($total_deposito_aux,$id_usuario);

                            $affected = DB::table('compra')
                            ->where('id', $request->id)
                            ->update(['total_efectivo' => 0,'total_deposito' => 0]);
                    }
                }
            }






            $affected = DB::table('pago_compra')
            ->where('id_compra', $request->id)
            ->update(['monto' => $total,'saldo'=>$total]);

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function CompraArqueoEfectivo(Request $request){

        $id_usuario=\Auth::user()->id;

        $compra=Compra::select(DB::raw('SUM(compra.total_efectivo) as total_e'))
        ->where('compra.estado','!=','Anulado')
        ->where('compra.id_tipo_pago','=',1)
        ->where('compra.control','=',0)
        ->where('compra.id_usuario','=',$id_usuario)
        ->get();
        $data=(object) $compra;
        return $data;
        
    }
    public function CompraArqueoDeposito(Request $request){

        $id_usuario=\Auth::user()->id;

        $compra=Compra::select(DB::raw('SUM(compra.total_deposito) as total_d'))
        ->where('compra.estado','!=','Anulado')
        ->where('compra.id_tipo_pago','=',1)
        ->where('compra.control','=',0)
        ->where('compra.id_usuario','=',$id_usuario)
        ->get();
        $data=(object) $compra;
        return $data;
        
    }

    // public function modificarCantidad(Request $request){
    //     try{
    //         DB::beginTransaction();
    //         dd($request->id_tipo_pago);

    //         $compra= Compra::findOrFail($request->id);
    //         $compra->fecha=$request->fecha;
    //         $compra->id_proveedor=$request->id_proveedor;
    //         $compra->descripcion=$request->descripcion;
    //         $compra->save();



    //         $total_d = $request->total_aux;
    //         $total = $request->total;
    //         $total_efectivo = $request->total_efectivo;
    //         $total_deposito = $request->total_deposito;
    //         $total_efectivo_aux = $request->total_efectivo_aux;
    //         $objdate = new DateTime();
    //         $fechaactual= $objdate->format('Y-m-d');
    //         $hora= $objdate->format('H:i:s');

    //         //dd($request->formaPago);
    //         $id_usuario=\Auth::user()->id;
    //         if($request->formaPago == 'Efectivo'){
    //             $affected = DB::table('compra')
    //             ->where('id', $request->id)
    //             ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_efectivo' => $request->total]);
    //         }elseif($request->formaPago == 'Transferencia'){
    //             $affected = DB::table('compra')
    //             ->where('id', $request->id)
    //             ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_deposito' =>$request->total]);
    //         }elseif($request->formaPago == 'Pago por QR'){
    //             $affected = DB::table('compra')
    //             ->where('id', $request->id)
    //             ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_deposito' =>$request->total]);
    //         }elseif($request->formaPago == 'Depósito'){
    //             $affected = DB::table('compra')
    //             ->where('id', $request->id)
    //             ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_deposito' =>$request->total]);
    //         }elseif($request->formaPago == 'Mixta'){
    //             $affected = DB::table('compra')
    //             ->where('id', $request->id)
    //             ->update(['total' => $request->total,'sub_total' => $request->sub_total,'total_efectivo' => $request->total_efectivo,'total_deposito' => $request->total_deposito]);
    //         }

    //           $detalles = $request->detalle;
    //           foreach($detalles as $ep=>$det){
    //               $detall = DB::table('detalle_compra')->where('id_compra',$request->id)->where('id_lote',$det['id_lote'])->first();
    //               //dd($det['id_producto']);

    //             if ($detall->cantidad >=$det['cantidad']) {
    //                 //dd($detall->cantidad <$det['cantidad']);
    //                 $affected = DB::table('detalle_compra')
    //                 ->where('id_compra', $request->id)
    //                 ->where('id_producto',$det['id_producto'])
    //                 ->update(['cantidad' => $det['cantidad'],'sub_total'=>$det['cantidad']*$det['costo_compra']]);
    //                 DB::table('lote')->where('lote.id','=',$det['id_lote'])->increment('cantidad', $det['cantidad']-$detall->cantidad);

    //                 $tienda_articulo=DB::select("SELECT ta.stock
    //                 FROM tienda_articulo ta
    //                 WHERE ta.id = '$detall->id_producto'");
    //                 $total_cantidad = $detall->cantidad - $det['cantidad'];
    //                 $ajuste = new Ajuste();
    //                 $ajuste->stock=$det['cantidad'];
    //                 $ajuste->costo_compra=$det['costo_compra'];
    //                 $ajuste->costo_venta=0;
    //                 $ajuste->costo_unitario=0;
    //                 $ajuste->costo_mayorista=0;
    //                 $ajuste->costo_preferencial=0;
    //                 $ajuste->stock_anterior=$det['stock'];
    //                 $ajuste->stock_actual=$det['stock']- $total_cantidad;
    //                 $ajuste->observacion=$request->descripcion;
    //                 $ajuste->id_lote=$det['id_lote'];
    //                 $ajuste->fecha=$fechaactual;
    //                 $ajuste->id_usuario=$id_usuario;
    //                 $ajuste->id_venta=0;
    //                 $ajuste->id_compra=$request->id;
    //                 $ajuste->id_motivo_ajuste=8;
    //                 $ajuste->id_transaccion=$request->id;
    //                 $ajuste->hora=$hora;
    //                 $ajuste->save();

    //             }

    //             else {
    //                 if($detall->cantidad <=$det['cantidad']){
    //                     $affected = DB::table('detalle_compra')
    //                     ->where('id_compra', $request->id)
    //                     ->where('id_producto',$det['id_producto'])
    //                     ->update(['cantidad' => $det['cantidad'],'sub_total'=>$det['cantidad']*$det['costo_compra']]);
    //                     DB::table('lote')->where('lote.id','=',$det['id_lote'])->decrement('cantidad',$detall->cantidad - $det['cantidad']);


    //                     $ajuste = new Ajuste();
    //                     $ajuste->stock=$det['cantidad'];
    //                     $ajuste->costo_compra=$det['costo_compra'];
    //                     $ajuste->costo_venta=0;
    //                     $ajuste->costo_unitario=0;
    //                     $ajuste->costo_mayorista=0;
    //                     $ajuste->costo_preferencial=0;
    //                     $ajuste->stock_anterior=$det['stock'];
    //                     $ajuste->stock_actual=$det['stock']- ($detall->cantidad - $det['cantidad']);
    //                     $ajuste->observacion=$request->descripcion;
    //                     $ajuste->id_lote=$det['id_lote'];
    //                     $ajuste->fecha=$fechaactual;
    //                     $ajuste->id_usuario=$id_usuario;
    //                     $ajuste->id_venta=0;
    //                     $ajuste->id_compra=$request->id;
    //                     $ajuste->id_motivo_ajuste=8;
    //                     $ajuste->id_transaccion=$request->id;
    //                     $ajuste->hora=$hora;
    //                     $ajuste->save();
    //                 }else
    //                 {
    //                     //
    //                 }
    //                 }

    //         }
    //         if($request->formaPago == 'Efectivo'){

    //             $this->descontarCaja($total_d,$id_usuario);
    //             $this->actualizarCaja($total,$id_usuario);

    //             $this->descontarCajaContado($total_d,$id_usuario);
    //             $this->actualizarCajaContado($total,$id_usuario);

    //         }
    //         elseif($request->formaPago == 'Mixta'){

    //             $this->descontarCaja($total_efectivo_aux,$id_usuario);
    //             $this->actualizarCaja($total_efectivo,$id_usuario);

    //             $this->descontarCajaContado($total_efectivo_aux,$id_usuario);
    //             $this->actualizarCajaContado($total_efectivo,$id_usuario);

    //             $affected = DB::table('pago_compra')
    //             ->where('id_compra', $request->id)
    //             ->update(['monto' => $total_efectivo,'saldo'=>$total_efectivo]);
    //         }
    //         elseif($request->formaPago == 'Cuenta por Cobrar'){

    //             $affected = DB::table('pago_compra')
    //             ->where('id_compra', $request->id)
    //             ->update(['monto' => $total,'saldo'=>$total]);

    //             $affected = DB::table('c_x_pagar')
    //             ->where('id_pago', $request->id)
    //             ->update(['monto_total' => $total,'saldo'=>$total]);
    //         }else
    //         {
    //             //
    //         }
    //         $affected = DB::table('pago_compra')
    //         ->where('id_compra', $request->id)
    //         ->update(['monto' => $total,'saldo'=>$total]);

    //         DB::commit();
    //     } catch (Exception $e){
    //         DB::rollBack();
    //     }
    // }

}

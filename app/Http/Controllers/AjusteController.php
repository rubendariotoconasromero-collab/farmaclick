<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ajuste;
use App\Models\Lote;
use App\Models\MotivoAjuste;
use App\Models\TiendaArticulo;
use App\Http\Requests\AjusteRequest;
use DB;
use DateTime;

class AjusteController extends BitacoraController
{
    public function guardarAjuste($datos=[])
    {
        
        $obj = new Ajuste();
        $obj->stock = $datos['stock'];
        $obj->costo_compra = $datos['costo_compra'];
        $obj->costo_venta = $datos['costo_venta'];
        $obj->observacion = $datos['observacion'];
        $obj->id_articulo = $datos['id_articulo'];
        $obj->id_motivo_ajuste = $datos['id_motivo_ajuste'];
        $obj->save();

    }

    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Ajuste::join('lote','ajuste.id_lote','=','lote.id')
            ->join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
            ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            
            ->join('motivo_ajuste','ajuste.id_motivo_ajuste','=','motivo_ajuste.id')
            ->join('categoria','categoria.id','=','articulo.id_categoria')
            ->select('ajuste.id','ajuste.id_lote','ajuste.id_motivo_ajuste','motivo_ajuste.nombre as motivo_ajuste','ajuste.stock','ajuste.costo_compra','ajuste.costo_venta',
            'ajuste.observacion','articulo.nombre_comercial as producto','ajuste.costo_unitario','ajuste.costo_mayorista','ajuste.costo_preferencial',
            'articulo.id_categoria','categoria.nombre as categoria','ajuste.stock_anterior','ajuste.stock_actual','lote.cantidad','lote.fecha_vecimiento','lote.lote')
            ->orderBy('ajuste.id','desc')->paginate(15);    
        }
        else{
            $obj= Ajuste::join('lote','ajuste.id_lote','=','lote.id')
            ->join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
            ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('motivo_ajuste','ajuste.id_motivo_ajuste','=','motivo_ajuste.id')
            ->join('categoria','categoria.id','=','articulo.id_categoria')
            ->select('ajuste.id','ajuste.id_lote','ajuste.id_motivo_ajuste','motivo_ajuste.nombre as motivo_ajuste','ajuste.stock','ajuste.costo_compra','ajuste.costo_venta',
            'ajuste.observacion','articulo.nombre_comercial as producto','ajuste.costo_unitario','ajuste.costo_mayorista','ajuste.costo_preferencial',
            'articulo.id_categoria','categoria.nombre as categoria','ajuste.stock_anterior','ajuste.stock_actual','lote.cantidad','lote.fecha_vecimiento','lote.lote')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('ajuste.id','desc')->paginate(15);            
        }
        return $obj;
    }

    public function indexProducto(Request $request){
        $id_proveedor = $request->id_proveedor; 
        $fecha_inicio = $request->fecha_inicio;
        $fecha_final = $request->fecha_final;
        $buscar = $request->buscarProducto;
        $criterio = $request->criterio;

        $query = Ajuste::join('lote', 'ajuste.id_lote', '=', 'lote.id')
            ->join('tienda_articulo', 'lote.id_producto', '=', 'tienda_articulo.id')
            ->join('users', 'ajuste.id_usuario', '=', 'users.id')
            ->join('articulo', 'tienda_articulo.id_articulo', '=', 'articulo.id')
            ->join('motivo_ajuste', 'ajuste.id_motivo_ajuste', '=', 'motivo_ajuste.id')
            ->join('proveedor', 'articulo.id_proveedor', '=', 'proveedor.id')
            ->join('categoria', 'categoria.id', '=', 'articulo.id_categoria')
            ->leftJoin('venta', function($join) {
                $join->on('ajuste.id_transaccion', '=', 'venta.id')
                     ->where('ajuste.id_motivo_ajuste', '=', 7);
            })
            ->leftJoin('cliente', 'venta.id_cliente', '=', 'cliente.id')
            ->leftJoin('compra', function($join) {
                $join->on('ajuste.id_transaccion', '=', 'compra.id')
                     ->where('ajuste.id_motivo_ajuste', '=', 6);
            })
            ->leftJoin('proveedor as prov_compra', 'compra.id_proveedor', '=', 'prov_compra.id')
            ->select(
                'ajuste.id',
                'ajuste.id_lote',
                'ajuste.id_motivo_ajuste',
                'motivo_ajuste.nombre as motivo_ajuste',
                'ajuste.stock',
                'ajuste.costo_compra',
                'ajuste.costo_venta',
                'ajuste.observacion',
                'articulo.nombre_comercial as producto',
                'ajuste.costo_unitario',
                'ajuste.costo_mayorista',
                'ajuste.costo_preferencial',
                'articulo.id_categoria',
                'categoria.nombre as categoria',
                'ajuste.stock_anterior',
                'ajuste.stock_actual',
                'lote.cantidad',
                'lote.fecha_vecimiento',
                'lote.lote',
                'ajuste.fecha',
                'ajuste.id_transaccion',
                'users.name as usuario',
                'ajuste.hora',
                'articulo.id as id_articulo',
                'ajuste.descuento',
                'ajuste.stock_general',
                'ajuste.stock_general_anterior',
                'proveedor.nombre as laboratorio',
                DB::raw("CASE WHEN ajuste.id_motivo_ajuste = 7 THEN cliente.nombre WHEN ajuste.id_motivo_ajuste = 6 THEN prov_compra.nombre ELSE NULL END as cliente_proveedor")
            )
            ->whereDate('ajuste.fecha', '>=', $fecha_inicio)
            ->whereDate('ajuste.fecha', '<=', $fecha_final);

        if ($id_proveedor != 0) {
            $query->where('articulo.id_proveedor', '=', $id_proveedor);
        }

        if ($buscar != '') {
            $query->where($criterio, 'like', '%' . $buscar . '%');
        }

        $obj = $query->groupBy('ajuste.id')
            ->orderBy('ajuste.id', 'desc')
            ->paginate(50);

        return $obj;
    }
    // public function indexProducto(Request $request){
    //     $fecha_inicio = $request->fecha_inicio;
    //     $fecha_final = $request->fecha_final;
    //     //dd($fecha_inicio,$fecha_final);
    //     $buscar = $request->buscarProducto;
    //     //dd($buscar);
    //     $criterio = $request->criterio;
    //     if($buscar==''){
    //         $obj=[]; 
    //     }
    //     else{
    //         $obj= Ajuste::join('lote','ajuste.id_lote','=','lote.id')
    //         ->join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
    //         ->join('users','ajuste.id_usuario','=','users.id')
    //         ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
    //         ->join('motivo_ajuste','ajuste.id_motivo_ajuste','=','motivo_ajuste.id')
    //         ->join('categoria','categoria.id','=','articulo.id_categoria')
    //         ->select('ajuste.id','ajuste.id_lote','ajuste.id_motivo_ajuste','motivo_ajuste.nombre as motivo_ajuste','ajuste.stock','ajuste.costo_compra','ajuste.costo_venta',
    //         'ajuste.observacion','articulo.nombre_comercial as producto','ajuste.costo_unitario','ajuste.costo_mayorista','ajuste.costo_preferencial',
    //         'articulo.id_categoria','categoria.nombre as categoria','ajuste.stock_anterior','ajuste.stock_actual','lote.cantidad','lote.fecha_vecimiento','lote.lote'
    //         ,'ajuste.fecha','ajuste.id_transaccion','users.name as usuario','ajuste.hora','articulo.id as id_articulo','ajuste.descuento',DB::raw('SUM(lote.cantidad) loteActual'))
    //         ->whereDate('ajuste.fecha', ">=", $fecha_inicio)
    //         ->whereDate('ajuste.fecha', "<=", $fecha_final)
    //        // ->whereDate('lote.cantidad', "!=", 0)
    //         ->where($criterio, 'like', '%'.$buscar.'%')
    //         ->groupBy('ajuste.id')
    //         ->orderBy('ajuste.id','desc')->get();       
    //     }
    //     //dd($obj);     
    //     return $obj;
    // }

    public function guardar(Request $request){
        try{
            DB::beginTransaction();
            $id_usuario=\Auth::user()->id;
            $objdate = new DateTime();
            $fechaactual= $objdate->format('Y-m-d');
            $hora= $objdate->format('H:i:s');
            $detalles = $request->detalle;
            //dd($request->detalle);
            foreach($detalles as $ep=>$det){
                $id_tienda_articulo = $det['id_tienda_articulo'];
                $tienda_articulo=DB::select("SELECT ta.stock
                FROM tienda_articulo ta
                WHERE ta.id = ?", [$id_tienda_articulo]);

                if($request->id_motivo_ajuste == 1 && !empty($det['id_lote'])){
                    DB::rollBack();
                    return response()->json([
                        'message' => 'Este producto ya tiene stock registrado. Usa "Ingreso" o "Egreso" para modificarlo, "Inventario Inicial" es solo para productos sin stock previo.',
                    ], 422);
                }

                if($request->id_motivo_ajuste == 1 && empty($det['id_lote'])){
                    $lote = new Lote();
                    $lote->id_producto = $det['id_tienda_articulo'];
                    $lote->cantidad = $det['stock'];
                    $lote->fecha_vecimiento = $det['fecha_vencimiento'];
                    $lote->lote = $det['lote'];
                    $lote->estado = 1;
                    $lote->save();
                    $det['id_lote'] = $lote->id;
                }

                $obj = new Ajuste();
                $obj->stock=$det['stock'];
                $obj->costo_compra=$det['costo_compra'];
                $obj->costo_venta=$det['costo_venta'] ? $det['costo_venta'] : 0;
                $obj->observacion=$det['observacion'];
                $obj->id_lote=$det['id_lote'];
                $obj->stock_anterior=$det['saldoStock'];
                $obj->stock_general_anterior=$tienda_articulo[0]->stock;
                if($request->id_motivo_ajuste == 1) {
                    $obj->stock_actual=$det['stock'];
                }else if($request->id_motivo_ajuste == 2){
                    $obj->stock_actual=$det['stock'] + $det['saldoStock'];
                    $obj->stock_general=$tienda_articulo[0]->stock + $det['stock'];
                }else if($request->id_motivo_ajuste == 3){
                    $obj->stock_actual=$det['saldoStock'] - $det['stock']; 
                    $obj->stock_general=$tienda_articulo[0]->stock - $det['stock'];
                }else{
                    $obj->stock_actual=$det['saldoStock'];
                }
                $obj->id_motivo_ajuste=$request->id_motivo_ajuste;
                $obj->costo_unitario=$det['costo_unitario'];
                $obj->costo_mayorista=$det['costo_mayorista'];
                $obj->costo_preferencial=$det['costo_preferencial'];
                $obj->fecha=$fechaactual;
                $obj->id_usuario=$id_usuario;
                $obj->id_venta=0;
                $obj->id_compra=0;
                $obj->id_transaccion=$obj->id;
                $obj->hora=$hora;
                $obj->save();

                $affected = DB::table('ajuste')
                ->where('id', $obj->id)
                ->update(['id_transaccion' => $obj->id]);

                if( $obj->id_motivo_ajuste == 1){
                    $affected = DB::table('lote')
                    ->where('id', $obj->id_lote)
                    ->update(['cantidad' => $det['stock']]); 

                    $affected = DB::table('lote')
                    ->where('id', $obj->id_lote)
                    ->update(['fecha_vecimiento' => $det['fecha_vencimiento']]); 

                    TiendaArticulo::recalcularStock($det['id_tienda_articulo']);

                }else {
                    if( $obj->id_motivo_ajuste == 2){
                        // DB::table('tienda_articulo')->where('tienda_articulo.id_tienda',1)->where('tienda_articulo.id_articulo','=',$det['id_articulo'])->decrement('stock',- $det['stock']);
                        DB::table('lote')->where('lote.id','=',$det['id_lote'])->decrement('cantidad',- $det['stock']);
                        TiendaArticulo::recalcularStock($det['id_tienda_articulo']);
                    }else
                    {
                    if( $obj->id_motivo_ajuste == 3){
                        // DB::table('tienda_articulo')->where('tienda_articulo.id_tienda',1)->where('tienda_articulo.id_articulo','=',$det['id_articulo'])->increment('stock',- $obj->stock);
                        DB::table('lote')->where('lote.id','=',$det['id_lote'])->increment('cantidad',- $obj->stock);
                        TiendaArticulo::recalcularStock($det['id_tienda_articulo']);
                    }else 
                    {
                        if( $obj->id_motivo_ajuste == 4){
                            $affected = DB::table('articulo')
                            ->where('id', $det['id_articulo'])
                            ->update(['costo_compra' => $det['costo_compra']]); 
                            //DB::table('tienda_articulo')->where('tienda_articulo.id_articulo','=',$det['id_articulo'])->decrement('stock', 'tienda_articulo.stock'-$det['stock']);
                        }else 
                        {
                            if( $obj->id_motivo_ajuste == 5){
                                $affected = DB::table('articulo')
                                ->where('id', $det['id_articulo'])
                                ->update(['costo_unitario' => $det['costo_unitario'],'costo_mayorista' => $det['costo_mayorista'],'costo_preferencial' => $det['costo_preferencial']]); 
                            }else 
                            {
                                //
                                //
                            }
                        }
                    }
                }
            }
        }
            $datos = [
                'tabla' => 'ajuste',
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

    public function selectMotivo(){  
        $obj = MotivoAjuste::select('id', 'nombre')->whereIn('id',[1,2,3])->orderBy('motivo_ajuste.id','asc')->get();
        return $obj;
    }

    public function cantidadRegistros(){
        $cantidad = DB::table('ajuste')->count();

        $data=['nro' =>$cantidad];
        return $data;
    }

    public function LoteStock(Request $request){
        $id_articulo = $request->id_articulo;
        $venta=Lote::join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->select(DB::raw('SUM(cantidad) as total'),'articulo.nombre_comercial as producto')
        ->where('lote.id_producto','=',$id_articulo)
        ->where('lote.estado','!=',0)
        ->get();
        $obj_venta = (object) $venta;
        //$venta=venta::sum('venta.total');
        //dd($obj_venta);
        return $obj_venta;
    }
}

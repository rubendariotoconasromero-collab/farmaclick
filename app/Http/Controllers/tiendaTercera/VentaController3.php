<?php

namespace App\Http\Controllers\tiendaTercera;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\OrdenServicio;
use App\Models\Control;
use App\Models\Articulo;
use App\Models\Ajuste;
use App\Models\Pago;
use App\Models\CXCobrar;
use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\ArqueoCaja;
use App\Http\Controllers\BitacoraController;
use DB;
use DateTime;

class VentaController3 extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio')
            ->whereNotBetween('venta.tipo_venta', ['Venta Directa', 'Venta Cotizacion'])
            ->where('venta.id_tienda','=',4)
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);
        }
        else{
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->whereNotBetween('venta.tipo_venta', ['Venta Directa', 'Venta Cotizacion'])
            ->where('venta.id_tienda','=',4)
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);            
        }
        return $obj;
    }

    public function indexServicio(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio')
            ->where('venta.tipo_venta','=','Venta Servicio')
            ->where('venta.id_tienda','=',4)
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);
        }
        else{
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->where('venta.tipo_venta','=','Venta Servicio')
            ->where('venta.id_tienda','=',4)
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);            
        }
        return $obj;
    }
    public function indexCotizacion(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio')
            ->where('venta.tipo_venta','=','Venta Cotizacion')
            ->where('venta.id_tienda','=',4)
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);
        }
        else{
            $obj= Venta::join('users','venta.id_usuario','=','users.id')
            ->join('cliente','venta.id_cliente','=','cliente.id')
            ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
            ->join('tienda','venta.id_tienda','=','tienda.id')
            ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
            'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','venta.id_orden_servicio')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->where('venta.tipo_venta','=','Venta Cotizacion')
            ->where('venta.id_tienda','=',4)
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('venta.id','desc')->paginate(15);            
        }
        return $obj;
    }

    private function actualizarCaja($id_usuario,$monto){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.registro_venta', $monto);
    }

    private function descontarCaja($monto,$id_usuario){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->decrement('arqueo_caja.registro_venta', $monto);
    }

    private function actualizarStock($id_producto,$cantVenta){
        DB::table('tienda_articulo')->where('tienda_articulo.id_articulo','=',$id_producto)
        ->where('tienda_articulo.id_tienda','=',4)
        ->decrement('stock', $cantVenta);
    }

    public function guardar(Request $request){
        try{
                $registro_venta = $request->total;
                $id_usuario=\Auth::user()->id;
                //dd($registro_venta, $id_usuario, $request->id_tipo_pago);

                DB::beginTransaction();
                $venta = new Venta();
                $venta->fecha=$request->fecha;
                $venta->sub_total=$request->sub_total;
                $venta->descuento=$request->descuento;
                $venta->total=$request->total;
                $venta->estado=$request->estado;
                $venta->id_cliente=$request->id_cliente;
                $venta->id_tipo_pago=$request->id_tipo_pago;
                $venta->tipo_venta=$request->tipo_venta;
                $venta->id_orden_servicio=$request->id_servicio ? $request->id_servicio : null;
                $venta->id_tienda=4;
                if($request->id_tipo_pago == 1) {
                    $venta->id_forma_pago=$request->id_forma_pago;
                }else if ($request->id_tipo_pago == 2) {
                    $venta->id_forma_pago=$request->id_forma_pago;
                } else {
                    //
                }
                //$venta->id_pago=$pago->id;
                $venta->id_usuario=\Auth::user()->id;
                $venta->save();  

                //AGREGAR CODIGO DE VENTA

                $correlativo = 0;
                $objdate = new DateTime();
                $fechaactual= $objdate->format('Y-m-d');
                $year = $objdate->format('y');
                $correlativo = $this->correlativoControl();
                $control = new control();
                $control->tabla = $request->tabla = "Venta";
                $control->id_tabla = $venta->id;
                $control->codigo = $request->codigo = 'VP-'.strval($correlativo + 1);
                $control->fecha = $fechaactual;
                $control->save();

                if($request->id_tipo_pago == 1) {
                    $pago = new Pago();
                    $pago->id = $venta->id;
                    $pago->fecha = $request->fecha;
                    $pago->fecha_final = $request->fecha_final;
                    $pago->monto = $request->total;
                    $pago->saldo = $request->total;
                    $pago->descripcion = "";
                    $pago->id_tipo_pago = $request->id_tipo_pago;
                    $pago->id_venta = $venta->id;
                    $pago->save();  

                    $this->actualizarCaja($id_usuario,$registro_venta);
                    
                } else if($request->id_tipo_pago == 2) {
    
                    $pago = new Pago();
                    $pago->id = $venta->id;
                    $pago->fecha = $request->fecha;
                    $pago->fecha_final = $request->fecha_final;
                    $pago->monto = $request->total;
                    $pago->saldo = $request->total;
                    $pago->descripcion = $request->descripcion = "";
                    $pago->id_tipo_pago = $request->id_tipo_pago;
                    $pago->id_venta = $venta->id;
                    $pago->save(); 

                    $cxcobrar = new CXCobrar();
                    $cxcobrar->fecha = $request->fecha;
                    $cxcobrar->monto_total = $request->monto_total;
                    $cxcobrar->descripcion = $request->descripcion_pago;
                    $cxcobrar->saldo = $request->monto_total;
                    $cxcobrar->id_pago = $venta->id;
                    $cxcobrar->save(); 
    
                } else {
                    //
                }
    
                $detalles = $request->detalle;
                $costo_pago = $request->costo_pago;

                foreach($detalles as $ep=>$det){
                    $obj = new DetalleVenta();
                    $obj->id_venta= $venta->id;
                    $obj->id_producto= $det['id_tienda_articulo'];
                    $obj->cantidad= $det['cantidad'];

                    //$obj->costo_venta= $det['costo_venta'];
                    if($request->tipo_venta=='Venta Directa') {
                        if($request->id_costo_pago == 1) {
                            $obj->costo_venta= $det['costo_unitario'];
                        } else if ($request->id_costo_pago == 2) {
                            $obj->costo_venta= $det['costo_mayorista'];
                        } else if ($request->id_costo_pago == 3) {
                            $obj->costo_venta= $det['costo_preferencial'];
                        } else {}
                    } else {
                        $obj->costo_venta= $det['costo_venta'];
                    }

                    //Ajuste por Venta tienda 1
                    if($request->tipo_venta=='Venta Directa'){
                        $ajuste = new Ajuste();
                        $ajuste->stock=$det['cantidad'];
                        $ajuste->costo_compra=$det['costo_compra'];
                        $ajuste->costo_unitario=$det['costo_unitario'];
                        $ajuste->costo_mayorista=$det['costo_mayorista'];
                        $ajuste->costo_preferencial=$det['costo_preferencial'];
                        $ajuste->stock_anterior=$det['stock'];
                        $ajuste->stock_actual=$det['stock']-$det['cantidad'];
                        if($request->tipo_venta=='Venta Directa') {
                            if($request->id_costo_pago == 1) {
                                $ajuste->costo_venta= $det['costo_unitario'];
                            } else if ($request->id_costo_pago == 2) {
                                $ajuste->costo_venta= $det['costo_mayorista'];
                            } else if ($request->id_costo_pago == 3) {
                                $ajuste->costo_venta= $det['costo_preferencial'];
                            } else {}
                        } else {
                            $ajuste->costo_venta= $det['costo_venta'];
                        }
                        $ajuste->observacion=$request->descripcion;
                        $ajuste->id_articulo=$det['id_articulo'];
                        $ajuste->id_motivo_ajuste=9;
                        $ajuste->save();
                    } else {
                        //
                    }

                    
                    $obj->sub_total= $det['sub_total'];
                    $obj->save();
                    if($request->tipo_venta=='Venta Directa'){
                        $this->actualizarStock($det['id_articulo'],$det['cantidad']);
                    } else {
                        //
                    }
                }

                if($request->tipo_venta == 'Venta Servicio') {
                    $affected = DB::table('orden_servicio')
                    ->where('id', $request->id_servicio)
                    ->update([
                        'estado' => 'Entregado',
                    ]); 
                }else{
                    //
                }

    
                $datos = [
                    'tabla' => 'venta',
                    'codigo_tabla' => $venta->id,
                    'transaccion' => 'guardar tienda 3',
                ];
                $this->guardarBitacora($datos);   
                
            
            
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function guardarCotizacion(Request $request){
        try{
                DB::beginTransaction();
                $registro_venta = $request->total;
                $id_usuario=\Auth::user()->id;
                //dd($registro_venta, $id_usuario, $request->id_tipo_pago);



                //Modificar Cotizacion
                $cotizacion = Cotizacion::findOrFail($request->id);
                $cotizacion->fecha=$request->fecha;
                $cotizacion->fecha_venci=$request->fecha_venci;
                $cotizacion->dias_credito=$request->dias_credito;
                $cotizacion->tiempo_entrega=$request->tiempo_entrega;
                $cotizacion->lugar_entrega=$request->lugar_entrega;
                $cotizacion->sub_total=$request->sub_total;
                $cotizacion->descuento=$request->descuento;
                $cotizacion->total=$request->total;
                $cotizacion->estado=$request->estado;
                $cotizacion->id_cliente=$request->id_cliente;
                $cotizacion->id_tipo_pago=$request->id_tipo_pago;
                $cotizacion->tipo_venta=$request->tipo_venta;
                $cotizacion->nota=$request->nota;
                $cotizacion->id_tienda=4;
                if($request->id_tipo_pago == 1) {
                    $cotizacion->id_forma_pago=$request->id_forma_pago;
                }else if ($request->id_tipo_pago == 2) {
                    $cotizacion->id_forma_pago=$request->id_forma_pago;
                } else {
                    //
                }
                //$cotizacion->id_pago=$pago->id;
                $cotizacion->id_usuario=$id_usuario;
                $cotizacion->save();  
                $eliminar = DetalleCotizacion::where('detalle_cotizacion.id_cotizacion','=',$cotizacion->id);
                $eliminar->delete();

                $detalles = $request->detalle;
                $costo_pago = $request->costo_pago;
                foreach($detalles as $ep=>$det){
                    $obj = new DetalleCotizacion();
                    $obj->id_cotizacion= $cotizacion->id;
                    $obj->id_producto= $det['id_tienda_articulo'];
                    $obj->cantidad= $det['cantidad'];
                    $obj->costo_venta= $det['costo_venta']; 
                    $obj->sub_total= $det['sub_total'];
                    $obj->tiempo_entrega= $det['tiempo_entrega'];
                    $obj->save();
                    if($request->tipo_venta=='Venta Cotizacion'){
                        //$this->actualizarStock($det['id_articulo'],$det['cantidad']);
                    } else {
                        //
                    }
    
                    // $articulo = Articulo::findOrFail($det['id_articulo']);
                    // $articulo->costo_venta=$det['costo_venta'];
                    // $articulo->save(); 
                    if($request->tipo_venta=='Venta Cotizacion'){
                        $ajuste = new Ajuste();
                        $ajuste->stock=$det['cantidad'];
                        $ajuste->costo_compra=0;
                        $ajuste->costo_venta=$det['costo_unitario'];
                        $ajuste->observacion='';
                        $ajuste->id_articulo=$det['id_articulo'];
                        $ajuste->id_motivo_ajuste=9;
                        $ajuste->save();
                    }else{
                        //
                    }
                }


                if($request->tipo_venta == 'Venta Servicio') {
                    // $servicio= OrdenServicio::findOrFail($request->id);
                    // $servicio->estado=$request->estado;
                    // $servicio->save();
                    $affected = DB::table('orden_servicio')
                    ->where('id', $request->id_servicio)
                    ->update([
                        'estado' => 'Entregado',
                    ]); 
                }else{
                    //
                }

    
                $datos = [
                    'tabla' => 'cotizacion',
                    'codigo_tabla' => $cotizacion->id,
                    'transaccion' => 'guardar',
                ];
                $this->guardarBitacora($datos);   

                ///Fin de Modificar Cotizacion


                $venta = new Venta();
                $venta->fecha=$request->fecha;
                $venta->sub_total=$request->sub_total;
                $venta->descuento=$request->descuento;
                $venta->total=$request->total;
                $venta->estado=$request->estado;
                $venta->id_cliente=$request->id_cliente;
                $venta->id_tipo_pago=$request->id_tipo_pago;
                $venta->tipo_venta=$request->tipo_venta;
                $venta->id_orden_servicio=$request->id_servicio ? $request->id_servicio : null;
                $venta->id_tienda=4;
                if($request->id_tipo_pago == 1) {
                    $venta->id_forma_pago=$request->id_forma_pago;
                }else if ($request->id_tipo_pago == 2) {
                    $venta->id_forma_pago=$request->id_forma_pago;
                } else {
                    //
                }
                //$venta->id_pago=$pago->id;
                $venta->id_usuario=\Auth::user()->id;
                $venta->save();  

                //AGREGAR CODIGO DE VENTA

                $correlativo = 0;
                $objdate = new DateTime();
                $fechaactual= $objdate->format('Y-m-d');
                $year = $objdate->format('y');
                $correlativo = $this->correlativoControl();
                $control = new control();
                $control->tabla = $request->tabla = "Venta";
                $control->id_tabla = $venta->id;
                $control->codigo = $request->codigo = 'VP-'.strval($correlativo + 1);
                $control->fecha = $fechaactual;
                $control->save();

                if($request->id_tipo_pago == 1) {
                    $pago = new Pago();
                    $pago->id = $venta->id;
                    $pago->fecha = $request->fecha;
                    $pago->fecha_final = $request->fecha_final;
                    $pago->monto = $request->total;
                    $pago->saldo = $request->total;
                    $pago->descripcion = "";
                    $pago->id_tipo_pago = $request->id_tipo_pago;
                    $pago->id_venta = $venta->id;
                    $pago->save();  

                    $this->actualizarCaja($id_usuario,$registro_venta);
                    
                } else if($request->id_tipo_pago == 2) {
    
                    $pago = new Pago();
                    $pago->id = $venta->id;
                    $pago->fecha = $request->fecha;
                    $pago->fecha_final = $request->fecha_final;
                    $pago->monto = $request->total;
                    $pago->saldo = $request->total;
                    $pago->descripcion = $request->descripcion = "";
                    $pago->id_tipo_pago = $request->id_tipo_pago;
                    $pago->id_venta = $venta->id;
                    $pago->save(); 

                    $cxcobrar = new CXCobrar();
                    $cxcobrar->fecha = $request->fecha;
                    $cxcobrar->monto_total = $request->monto_total;
                    $cxcobrar->descripcion = $request->descripcion_pago;
                    $cxcobrar->saldo = $request->monto_total;
                    $cxcobrar->id_pago = $venta->id;
                    $cxcobrar->save(); 
    
                } else {
                    //
                }
    
                $detalles = $request->detalle;
                $costo_pago = $request->costo_pago;

                foreach($detalles as $ep=>$det){
                    $obj = new DetalleVenta();
                    $obj->id_venta= $venta->id;
                    $obj->id_producto= $det['id_tienda_articulo'];
                    $obj->cantidad= $det['cantidad'];
                    $obj->costo_venta= $det['costo_venta'];

                     //Ajuste por Venta tienda 1
                    if($request->tipo_venta=='Venta Directa'){
                        $ajuste = new Ajuste();
                        $ajuste->stock=$det['cantidad'];
                        $ajuste->costo_compra=$det['costo_compra'];
                        $ajuste->costo_unitario=$det['costo_unitario'];
                        $ajuste->costo_mayorista=$det['costo_mayorista'];
                        $ajuste->costo_preferencial=$det['costo_preferencial'];
                        $ajuste->stock_anterior=$det['stock'];
                        $ajuste->stock_actual=$det['stock']-$det['cantidad'];
                        if($request->tipo_venta=='Venta Directa') {
                            if($request->id_costo_pago == 1) {
                                $ajuste->costo_venta= $det['costo_unitario'];
                            } else if ($request->id_costo_pago == 2) {
                                $ajuste->costo_venta= $det['costo_mayorista'];
                            } else if ($request->id_costo_pago == 3) {
                                $ajuste->costo_venta= $det['costo_preferencial'];
                            } else {}
                        } else {
                            $ajuste->costo_venta= $det['costo_venta'];
                        }
                        $ajuste->observacion=$request->descripcion;
                        $ajuste->id_articulo=$det['id_articulo'];
                        $ajuste->id_motivo_ajuste=8;
                        $ajuste->save();
                    } else {
                        //
                    }

                    
                    $obj->sub_total= $det['sub_total'];
                    $obj->save();
                    if($request->tipo_venta=='Venta Cotizacion'){
                        $this->actualizarStock($det['id_articulo'],$det['cantidad']);
                    } else {
                        //
                    }
                }
                
                if($request->tipo_venta == 'Venta Cotizacion') {
                    $affected = DB::table('cotizacion')
                    ->where('id', $request->id)
                    ->update([
                        'estado' => 'Entregado',
                    ]); 
                }else{
                    //
                }
                //dd($request->id);
                
                if($request->tipo_venta == 'Venta Servicio') {
                    $affected = DB::table('orden_servicio')
                    ->where('id', $request->id_servicio)
                    ->update([
                        'estado' => 'Entregado',
                    ]); 
                }else{
                    //
                }

    
                $datos = [
                    'tabla' => 'venta',
                    'codigo_tabla' => $venta->id,
                    'transaccion' => 'guardar tienda 3',
                ];
                $this->guardarBitacora($datos);  
    
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function obtenerCabecera(Request $request){
        
        $id=$request->id; 
        $obj= Venta::join('proveedor','compra.id_proveedor','=','proveedor.id')
        ->select('compra.id','compra.fecha','compra.descripcion','compra.total','proveedor.nombre as proveedor')
        ->where('compra.id','=',$id)
        ->get();

        return $obj;
    }

    public function detalleVenta(Request $request){
        
        $id=$request->id; 
        $obj= detalleVenta::join('tienda_articulo','detalle_venta.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->join('categoria','articulo.id_categoria','=','categoria.id')
        ->select('detalle_venta.id_venta','detalle_venta.costo_venta','articulo.costo_unitario',
        'articulo.costo_mayorista','articulo.costo_preferencial','articulo.nombre as articulo',
        'detalle_venta.cantidad','detalle_venta.sub_total','tienda.nombre as tienda','tienda.id as id_tienda',
        'articulo.marca','articulo.id_categoria','categoria.nombre as categoria')
        ->where('detalle_venta.id_venta','=',$id)
        ->orderBy('categoria.nombre','asc')
        ->orderBy('articulo.nombre','asc')
        ->get();
        return $obj;
    }
    
    public function anular(Request $request){
        $registro_venta = DB::select("SELECT id, total, id_tipo_pago FROM venta WHERE venta.id = $request->id");
        $total = $registro_venta[0]->total;
        $tipo_pago = $registro_venta[0]->id_tipo_pago;
        $id_usuario=\Auth::user()->id;

        if($tipo_pago == "1"){
            $this->descontarCaja($total,$id_usuario);
        }else if ($tipo_pago == "2"){
            $total_monto_credito= 0;
            $registro_venta_pago = DB::select("SELECT id, id_venta FROM pago WHERE id_venta = $request->id");
            $id_pago = $registro_venta_pago[0]->id;
            $registro_venta_credito = DB::select("SELECT id, id_pago, amortizacion FROM c_x_cobrar WHERE c_x_cobrar.id_pago = $id_pago");
            foreach($registro_venta_credito as $credito){
                $total_monto_credito = $total_monto_credito + floatval($credito->amortizacion);
            }
            $this->descontarCaja($total_monto_credito,$id_usuario);
        }

        $obj = Venta::findOrFail($request->id);
        $obj->estado = 'Anulado';
        $obj->save();

        if(isset($request->id_orden_servicio)){
            $obj = OrdenServicio::findOrFail($request->id_orden_servicio);
            $obj->estado = 'Anulado';
            $obj->save();
        }
        

        $pago = Pago::findOrFail($request->id);
        $pago->estado = 0;
        $pago->save();


        $detalles = DetalleVenta::select('id_venta','id_producto','cantidad')
        ->where('detalle_venta.id_venta',$request->id)->get();
        foreach($detalles as $ep=>$det){
            DB::table('tienda_articulo')->where('tienda_articulo.id','=',$det->id_producto)
            ->increment('stock', $det->cantidad);
        }
        

        $datos = [
            'tabla' => 'venta',
            'codigo_tabla' => $obj->id,
            'transaccion' => 'Anular Venta Tienda 1',
        ];
        $this->guardarBitacora($datos); 


    }

    public function cantidadRegistros(){
        $mayor = DB::table('control')->count();
        return $mayor;
    }

    private function correlativoControl(){
       $mayor = DB::table('control')->where('control.tabla','=','Venta')->count();
        return $mayor;
    }
    

    public function pdfVentas(Request $request){

        $id = $request->id;
        $foto = $request->foto;
        
        $venta= Venta::join('users','venta.id_usuario','=','users.id')
        ->join('cliente','venta.id_cliente','=','cliente.id')
        ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
        ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
        ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento',
        'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP')
        ->where('venta.id',$id)
        ->orderBy('venta.id','desc')->get();


        $detalleVenta= detalleVenta::join('tienda_articulo','detalle_venta.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_venta.id_venta','detalle_venta.costo_venta','articulo.costo_unitario as costo_unitario','articulo.costo_mayorista as costo_mayorista','articulo.costo_preferencial as costo_preferencial','articulo.nombre as articulo','detalle_venta.cantidad','detalle_venta.sub_total','tienda.nombre as tienda')
        ->where('detalle_venta.id_venta','=',$id)
        ->get();

        $cliente=$venta[0]->cliente;
        $fecha=$venta[0]->fecha;
        $tipo_pago=$venta[0]->tipoP;
        $forma_pago=$venta[0]->formaP;
        $id_descuento=$venta[0]->id_descuento;
        $detalles=$detalleVenta;
        $total=$venta[0]->total;
        $descuento=$venta[0]->descuento;
        $sub_total=$venta[0]->sub_total;
        if($venta[0]->id_descuento == 1) {
            $descuento_pago= 'Precio Unitario';
        } else if ($venta[0]->id_descuento == 2) {
            $descuento_pago= 'Precio Mayorista';
        } else if ($venta[0]->id_descuento == 3) {
            $descuento_pago=  'Precio Preferencial';
        } else {}

        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.venta.venta', [
            'ventas'=>$venta,
            'fecha'=>$fecha,
            'cliente'=>$cliente,
            'tipo_pago'=>$tipo_pago,
            'forma_pago'=>$forma_pago,
            'descuento_pago'=>$descuento_pago,
            'id_descuento' =>$id_descuento,
            'detalles'=>$detalles,
            'total'=>$total,
            'descuento'=>$descuento,
            'sub_total'=>$sub_total,
            'foto'=>$foto
        ]);
        return $pdf->stream('Ventas.pdf');
    }

    public function pdfVentasGeneral(Request $request){

        $id = $request->id;
        $foto = $request->foto;
        $empresa_nombre = $request->empresa_nombre;
        
        $venta= Venta::join('users','venta.id_usuario','=','users.id')
        ->join('cliente','venta.id_cliente','=','cliente.id')
        ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
        ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
        ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento',
        'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP')
        ->where('venta.id',$id)
        ->orderBy('venta.id','desc')->get();


        $detalleVenta= detalleVenta::join('tienda_articulo','detalle_venta.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_venta.id_venta','detalle_venta.costo_venta','articulo.costo_unitario as costo_unitario','articulo.costo_mayorista as costo_mayorista','articulo.costo_preferencial as costo_preferencial','articulo.nombre as articulo','detalle_venta.cantidad','detalle_venta.sub_total','tienda.nombre as tienda')
        ->where('detalle_venta.id_venta','=',$id)
        ->get();

        $usuario = \Auth::user()->name;
        $objdate = new DateTime();
        $fecha_impresion=$objdate->format('d/m/Y');
        $cliente=$venta[0]->cliente;
        $fecha=$venta[0]->fecha;
        $tipo_pago=$venta[0]->tipoP;
        $forma_pago=$venta[0]->formaP;
        $id_descuento=$venta[0]->id_descuento;
        $detalles=$detalleVenta;
        $total=$venta[0]->total;
        $descuento=$venta[0]->descuento;
        $sub_total=$venta[0]->sub_total;
        if($venta[0]->id_descuento == 1) {
            $descuento_pago= 'Precio Unitario';
        } else if ($venta[0]->id_descuento == 2) {
            $descuento_pago= 'Precio Mayorista';
        } else if ($venta[0]->id_descuento == 3) {
            $descuento_pago=  'Precio Preferencial';
        } else {}

        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.venta.venta-general', [
            'ventas'=>$venta,
            'fecha'=>$fecha,
            'cliente'=>$cliente,
            'tipo_pago'=>$tipo_pago,
            'forma_pago'=>$forma_pago,
            'descuento_pago'=>$descuento_pago,
            'id_descuento' =>$id_descuento,
            'detalles'=>$detalles,
            'total'=>$total,
            'descuento'=>$descuento,
            'sub_total'=>$sub_total,
            'foto'=>$foto,
            'fecha_impresion'=>$fecha_impresion,
            'usuario'=>$usuario,
            'empresa_nombre'=>$empresa_nombre
        ]);
        return $pdf->stream('Ventas.pdf');
    }
}

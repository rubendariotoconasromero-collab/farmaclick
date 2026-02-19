<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetalleVentaPaqueteController extends Controller
{
    public function guardar(Request $request){
        try{
                DB::beginTransaction();
                $registro_venta = $request->total;
                $id_usuario=\Auth::user()->id;
                //dd($registro_venta, $id_usuario, $request->id_tipo_pago);

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
                $venta->id_tienda=1;
                if($request->id_tipo_pago == 1) {
                    $venta->id_forma_pago=$request->id_forma_pago;
                }else if ($request->id_tipo_pago == 2) {
                    $venta->id_forma_pago=$request->id_forma_pago;
                } else {
                    //
                }
                //$venta->id_pago=$pago->id;
                $venta->id_usuario=$id_usuario;
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
                    if($det->producto_venta=='Venta Producto'){
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
                            $ajuste->costo_compra=0;
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
                            $ajuste->id_motivo_ajuste=7;
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

                    }else{
                        //modelo
                        $obj = new DetalleVentaPaquete();
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
                            $ajuste->costo_compra=0;
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
                            $ajuste->id_motivo_ajuste=7;
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
                    'transaccion' => 'guardar tienda 1',
                ];
                $this->guardarBitacora($datos);   
                
            
            
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
}

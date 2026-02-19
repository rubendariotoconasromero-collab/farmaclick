<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ControlVacuna;
use App\Models\MiEmpresa;
use App\Models\DetalleControlVacuna;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Ajuste;
use App\Models\Control;
use App\Models\ArqueoCaja;
use App\Models\Pago;
use App\Models\CXCobrar;
use DB;
use DateTime;


class ControlVacunaController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= ControlVacuna::join('paciente','control_vacuna.id_paciente','=','paciente.id')
            ->join('cliente','paciente.id_cliente','=','cliente.id')
            ->join('detalle_control_vacuna','control_vacuna.id','=','detalle_control_vacuna.id_control_vacuna')
            ->join('animal','paciente.id_animal','=','animal.id')
            ->select('control_vacuna.id','control_vacuna.id_paciente','control_vacuna.fecha','control_vacuna.prox_fecha','detalle_control_vacuna.edad',
            'cliente.nombre as cliente','animal.nombre as animal','paciente.nombre as paciente','paciente.id_animal','paciente.id_cliente','control_vacuna.sub_total','control_vacuna.total')
            //->where('e.estado', 1)
            ->GroupBy('control_vacuna.id')
            ->orderBy('detalle_control_vacuna.prox_fecha','desc')->paginate(15);
        }
        else{
            $obj= ControlVacuna::join('paciente','control_vacuna.id_paciente','=','paciente.id')
            ->join('cliente','paciente.id_cliente','=','cliente.id')
            ->join('detalle_control_vacuna','control_vacuna.id','=','detalle_control_vacuna.id_control_vacuna')
            ->join('animal','paciente.id_animal','=','animal.id')
            ->select('control_vacuna.id','control_vacuna.id_paciente','control_vacuna.fecha','control_vacuna.prox_fecha','detalle_control_vacuna.edad',
            'cliente.nombre as cliente','animal.nombre as animal','paciente.nombre as paciente','paciente.id_animal','paciente.id_cliente','control_vacuna.sub_total','control_vacuna.total')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('control_vacuna.id','desc')->paginate(15);            
        }
        return $obj;
    }
    
    public function guardar(Request $request){
        try{
                DB::beginTransaction();

                // REGISTRO DE CONTROL VACUNA
                $control = new ControlVacuna();
                $control->fecha=$request->fecha;
                $control->prox_fecha=$request->prox_fecha;
                $control->sub_total=$request->sub_total;
                $control->descuento=$request->descuento;
                $control->total=$request->total;
                $control->estado='CONCLUIDO';
                $control->id_paciente=$request->id_paciente;
                $control->save();

                $detalles = $request->detalle;
                $costo_pago = $request->costo_pago;

                foreach($detalles as $ep=>$det){
                        $obj = new DetalleControlVacuna();
                        $obj->id_control_vacuna= $control->id;
                        $obj->id_producto= $det['id_tienda_articulo'];
                        $obj->cantidad= $det['cantidad'];
                        $obj->fecha=$request->fecha;
                        $obj->prox_fecha=$request->prox_fecha;
                        $obj->edad=$request->edad;
                        $obj->costo_venta= $det['costo_unitario'];
                        $obj->sub_total= $det['sub_total'];
                        $obj->estado= $det['estado'];
                        $obj->save();

                        // if($request->tipo_venta=='Venta Directa'){
                        //     $this->actualizarStock($det['id_articulo'],$det['cantidad']);
                        // } else {
                        //     //
                        // }
                         
                }
                //dd($request->edad);
                $affected = DB::table('paciente')
                ->where('id', $request->id_paciente)
                ->update(['edad' => $request->edad]);  


                $datos = [
                    'tabla' => 'control_vacuna',
                    'codigo_tabla' => $control->id,
                    'transaccion' => 'guardar',
                ];
                $this->guardarBitacora($datos);  
                // FIN DE CONTROL VACUNA
                $this->VentaControlVacuna($request);
                      


                
            
            
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function VentaControlVacuna($request)
    {
                // REGISTRO DE VENTA
                // dd($request);
                $registro_venta = $request->total;  
                $id_usuario=\Auth::user()->id;

                
                
                $venta = new Venta();
                $venta->fecha=$request->fecha;
                $venta->sub_total=$request->sub_total;
                $venta->descuento=$request->descuento;
                $venta->total=$request->total;
                $venta->estado='Concluido';
                $venta->id_cliente=$request->id_cliente;
                $venta->id_tipo_pago=$request->id_tipo_pago;
                $venta->tipo_venta='Venta Control Vacuna';
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
                
                //dd($venta);
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

                // if($request->id_tipo_pago == 1) {
                //     $pago = new Pago();
                //     $pago->id = $venta->id;
                //     $pago->fecha = $request->fecha;
                //     $pago->fecha_final = $request->fecha_final;
                //     $pago->monto = $request->total;
                //     $pago->saldo = $request->total;
                //     $pago->descripcion = "";
                //     $pago->id_tipo_pago = $request->id_tipo_pago;
                //     $pago->id_venta = $venta->id;
                //     $pago->save();  

                //     $this->actualizarCaja($id_usuario,$registro_venta);
                    
                // } else if($request->id_tipo_pago == 2) {
    
                //     $pago = new Pago();
                //     $pago->id = $venta->id;
                //     $pago->fecha = $request->fecha;
                //     $pago->fecha_final = $request->fecha_final;
                //     $pago->monto = $request->total;
                //     $pago->saldo = $request->total;
                //     $pago->descripcion = $request->descripcion = "";
                //     $pago->id_tipo_pago = $request->id_tipo_pago;
                //     $pago->id_venta = $venta->id;
                //     $pago->save(); 

                //     $cxcobrar = new CXCobrar();
                //     $cxcobrar->fecha = $request->fecha;
                //     $cxcobrar->monto_total = $request->monto_total;
                //     $cxcobrar->descripcion = $request->descripcion_pago;
                //     $cxcobrar->saldo = $request->monto_total;
                //     $cxcobrar->id_pago = $venta->id;
                //     $cxcobrar->save(); 
    
                // } else {
                //     //
                // }
    
                $detalles = $request->detalle;
                $costo_pago = $request->costo_pago;

                //dd($request->detalle);

                foreach($detalles as $ep=>$det){
                        if($det['estado'] ==0){
                        $obj = new DetalleVenta();
                        $obj->id_venta= $venta->id;
                        $obj->id_producto= $det['id_tienda_articulo'];
                        $obj->cantidad= $det['cantidad'];

                        $obj->costo_venta= $det['costo_venta'];
                        // if($venta->tipo_venta=='Venta Control Vacuna') {
                        //     if($request->id_costo_pago == 1) {
                        //         $obj->costo_venta= $det['costo_unitario'];
                        //     } else if ($request->id_costo_pago == 2) {
                        //         $obj->costo_venta= $det['costo_mayorista'];
                        //     } else if ($request->id_costo_pago == 3) {
                        //         $obj->costo_venta= $det['costo_preferencial'];
                        //     } else {}
                        // } else {
                        //     $obj->costo_venta= $det['costo_venta'];
                        // }

                        //Ajuste por Venta tienda 1
                        if($venta->tipo_venta=='Venta Control Vacuna'){
                            $ajuste = new Ajuste();
                            $ajuste->stock=$det['cantidad'];
                            $ajuste->costo_compra=0;
                            $ajuste->costo_unitario=$det['costo_unitario'];
                            $ajuste->costo_mayorista=$det['costo_mayorista'];
                            $ajuste->costo_preferencial=$det['costo_preferencial'];
                            $ajuste->stock_anterior=$det['stock'];
                            $ajuste->stock_actual=$det['stock']-$det['cantidad'];
                            if($venta->tipo_venta=='Venta Control Vacuna') {
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
                        

                        }else
                        {
                            //
                        }
                        // if($request->tipo_venta=='Venta Directa'){
                        //     $this->actualizarStock($det['id_articulo'],$det['cantidad']);
                        // } else {
                        //     //
                        // }

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
                
    // FIN REGISTRO DE VENTA
    }
    private function correlativoControl(){
        $mayor = DB::table('control')->where('control.tabla','=','Venta')->count();
         return $mayor;
     }
    
     private function actualizarCaja($id_usuario,$monto){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.registro_venta', $monto);
    }
    public function detalleControlVacuna(Request $request){
        
        //dd($request->id);
        $id=$request->id; 
        $obj= DetalleControlVacuna::join('tienda_articulo','detalle_control_vacuna.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('categoria','articulo.id_categoria','=','categoria.id')
        ->select('detalle_control_vacuna.id_control_vacuna','articulo.nombre_comercial as articulo','detalle_control_vacuna.id_producto as id_tienda_articulo'
        ,'detalle_control_vacuna.id_producto as id_articulo','categoria.nombre as categoria','detalle_control_vacuna.costo_venta','articulo.costo_unitario',
        'articulo.costo_mayorista','articulo.costo_preferencial','articulo.nombre_comercial as articulo','articulo.id_categoria'
        ,'detalle_control_vacuna.cantidad','detalle_control_vacuna.sub_total','tienda_articulo.stock','detalle_control_vacuna.estado')
        ->where('detalle_control_vacuna.id_control_vacuna','=',$id)
        ->orderBy('articulo.nombre_comercial','asc')
        ->get();
        return $obj;
    }
    public function ActEstado(Request $request){
        $id=$request->id;  
        //dd($id);
        $affected = DB::table('detalle_control_vacuna')
        ->where('id_control_vacuna', $id)
        ->update(['estado' => 1]); 
       
    }
    public function modificar(Request $request){
        try{
            DB::beginTransaction();
            $id= $request->id;
            //dd($id);
            $affected = DB::table('control_vacuna')
              ->where('id', $request->id)
              ->update(['total' => $request->total]); 
            //dd($affected);

            $detalles = $request->detalle;
            //dd($request->total);
            foreach($detalles as $ep=>$det){             
                if($det['estado'] ==0){
                $detall = DB::table('detalle_control_vacuna')->where('id_control_vacuna',$request->id)->where('id_producto',$det['id_tienda_articulo'])->first();
                //dd($request->id);
                // if ($detall == null) {
                    $obj = new detalleControlVacuna();
                    $idDetalle = DB::table('detalle_control_vacuna')->max('id_control_vacuna');
                    $obj->id_control_vacuna= $request->id;
                    $obj->id_producto= $det['id_tienda_articulo'];
                    $obj->fecha=$request->fecha;
                    $obj->edad=$request->edad;
                    $obj->prox_fecha=$request->prox_fecha;
                    $obj->cantidad= $det['cantidad'];
                    $obj->costo_venta= $det['costo_unitario'];
                    $obj->sub_total= $det['sub_total'];
                    $obj->estado= $det['estado'];
                    $obj->save();
                    
                    if( $obj->estado == 0){
                        $this->VentaControlVacuna($request);
                    }
                }else
                {
                    //
                }
            }
            $control= ControlVacuna::findOrFail($request->id);
            $control->fecha=$request->fecha;
            $control->prox_fecha=$request->prox_fecha;
            $control->save();

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function pdfVacuna(Request $request){

        $id = $request->id;

        $x=DB::select("SELECT detalle_control_vacuna.fecha,detalle_control_vacuna.edad,articulo.nombre_comercial as vacuna ,detalle_control_vacuna.prox_fecha
        FROM control_vacuna,detalle_control_vacuna ,tienda_articulo ,articulo	
        WHERE detalle_control_vacuna.id_control_vacuna=control_vacuna.id and detalle_control_vacuna.id_producto=tienda_articulo.id and tienda_articulo.id_articulo=articulo.id and control_vacuna.id='$id'");

        $obj = json_decode(json_encode($x), true);
        
        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.logo_login')
        ->get();
        //dd($mi_empresa);
        $title='CONTROL DE VACUNAS';
        $nombre_empresa=$mi_empresa[0]->nombre;
        //$cod_empresa=$mi_empresa[1]->cod_tienda;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        //$cod_almacen=$mi_empresa[1]->cod_almacen;
        $foto_empresa=$mi_empresa[0]->logo_login;

        $detalles=$obj;
        
        $cont=ControlVacuna::count();
        $pdf = \PDF::loadView('pdf.reportes.vacunas.vacunas', [
            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,

            'detalles'=>$detalles,
            
        ]);
        return $pdf->stream('Vacunas.pdf');
    }
    

     
}

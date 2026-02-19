<?php

namespace App\Http\Controllers\tiendaPrimera;

use Illuminate\Http\Request;
use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\OrdenServicio;
use App\Models\Control;
use App\Models\Articulo;
use App\Models\Ajuste;
use App\Models\Pago;
use App\Models\CXCobrar;
use App\Models\ArqueoCaja;
use App\Models\tienda;
use App\Http\Controllers\BitacoraController;
use DB;
use DateTime;

class CotizacionController1 extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Cotizacion::join('users','cotizacion.id_usuario','=','users.id')
            ->join('cliente','cotizacion.id_cliente','=','cliente.id')
            ->join('tipo_pago','cotizacion.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','cotizacion.id_forma_pago','=','forma_pago.id')
            ->join('tienda','cotizacion.id_tienda','=','tienda.id')
            ->select('cotizacion.id','cotizacion.fecha','cotizacion.sub_total','cotizacion.descuento','cotizacion.tipo_venta',
            'cotizacion.total','cotizacion.estado','cotizacion.nota','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','cotizacion.fecha_venci','cotizacion.tiempo_entrega','cotizacion.lugar_entrega'
            ,'tipo_pago.nombre as tipo','cotizacion.dias_credito','cotizacion.id_tipo_pago','cotizacion.id_forma_pago','cotizacion.id_cliente')
            ->where('cotizacion.tipo_venta','=','Venta Cotizacion')
            ->where('cotizacion.id_tienda','=',2)
            ->where('cotizacion.estado','=','Registrado')
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('cotizacion.id','desc')->paginate(15);
        }
        else{
            $obj= Cotizacion::join('users','cotizacion.id_usuario','=','users.id')
            ->join('cliente','cotizacion.id_cliente','=','cliente.id')
            ->join('tipo_pago','cotizacion.id_tipo_pago','=','tipo_pago.id')
            ->join('forma_pago','cotizacion.id_forma_pago','=','forma_pago.id')
            ->join('tienda','cotizacion.id_tienda','=','tienda.id')
            ->select('cotizacion.id','cotizacion.fecha','cotizacion.sub_total','cotizacion.descuento','cotizacion.tipo_venta',
            'cotizacion.total','cotizacion.estado','users.name','cotizacion.nota','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
            'tienda.id as tienda_id','tienda.nombre as tienda','cotizacion.fecha_venci','cotizacion.tiempo_entrega','cotizacion.lugar_entrega'
            ,'tipo_pago.nombre as tipo','cotizacion.dias_credito','cotizacion.id_tipo_pago','cotizacion.id_forma_pago','cotizacion.id_cliente')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->where('cotizacion.tipo_venta','=','Venta Cotizacion')
            ->where('cotizacion.id_tienda','=',2)
            ->where('cotizacion.estado','=','Registrado')
            ->where('users.id','=',\Auth::user()->id)
            ->orderBy('cotizacion.id','desc')->paginate(15);            
        }
        return $obj;
    }
    public function guardar(Request $request){
        try{
                DB::beginTransaction();
                $registro_venta = $request->total;
                $id_usuario=\Auth::user()->id;
                $correlativo = 0;
                $objdate = new DateTime();
                $fechaactual= $objdate->format('Y-m-d');
                $year = $objdate->format('y');
                $cotizacion = new Cotizacion();
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
                $cotizacion->id_tienda=2;
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

                $correlativo = $this->correlativoControl();
    
                $control = new control();
                $control->tabla = $request->tabla = "Producto";
                $control->codigo = $request->codigo = 'VP'.strval($correlativo + 1).$year;
                $control->fecha = $fechaactual;
                $control->save();
    
                $detalles = $request->detalle;
                $costo_pago = $request->costo_pago;
                foreach($detalles as $ep=>$det){
                    $obj = new DetalleCotizacion();
                    $obj->id_cotizacion= $cotizacion->id;
                    $obj->id_producto= $det['id_tienda_articulo'];
                    $obj->cantidad= $det['cantidad'];
                    if($request->id_costo_pago == 1) {
                        $obj->costo_venta= $det['costo_unitario'];
                    } else if ($request->id_costo_pago == 2) {
                        $obj->costo_venta= $det['costo_mayorista'];
                    } else if ($request->id_costo_pago == 3) {
                        $obj->costo_venta= $det['costo_preferencial'];
                    } else {}
                    
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
                }
                if($request->tipo_venta=='Venta Cotizacion'){
                    $ajuste = new Ajuste();
                    $ajuste->stock=$det['cantidad'];
                    $ajuste->costo_compra=0;
                    $ajuste->costo_venta=$det['costo_unitario'];
                    $ajuste->observacion=$request->descripcion;
                    $ajuste->id_articulo=$det['id_articulo'];
                    $ajuste->id_motivo_ajuste=6;
                    $ajuste->save();
                }else{
                    //
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
                
            
            
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function modificar(Request $request){
        try{
                DB::beginTransaction();
                $registro_venta = $request->total;
                $id_usuario=\Auth::user()->id;
                $correlativo = 0;
                $objdate = new DateTime();
                $fechaactual= $objdate->format('Y-m-d');
                $year = $objdate->format('y');

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
                $cotizacion->id_tienda=2;
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

                $correlativo = $this->correlativoControl();
    
                $control = new control();
                $control->tabla = $request->tabla = "Producto";
                $control->codigo = $request->codigo = 'VP'.strval($correlativo + 1).$year;
                $control->fecha = $fechaactual;
                $control->save();
    
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
                    $obj->save();
                    if($request->tipo_venta=='Venta Cotizacion'){
                        //$this->actualizarStock($det['id_articulo'],$det['cantidad']);
                    } else {
                        //
                    }
    
                    // $articulo = Articulo::findOrFail($det['id_articulo']);
                    // $articulo->costo_venta=$det['costo_venta'];
                    // $articulo->save(); 
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

    public function detalleCotizacion(Request $request){
        
        $id=$request->id; 
        $obj= DetalleCotizacion::join('tienda_articulo','detalle_cotizacion.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('categoria','articulo.id_categoria','=','categoria.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_cotizacion.id_cotizacion','detalle_cotizacion.costo_venta','articulo.costo_unitario','articulo.marca',
        'articulo.costo_mayorista','articulo.costo_preferencial','articulo.nombre as articulo',
        'categoria.nombre as categoria','detalle_cotizacion.cantidad','detalle_cotizacion.sub_total','detalle_cotizacion.tiempo_entrega','tienda.nombre as tienda','tienda.id as id_tienda','tienda_articulo.id as id_tienda_articulo','tienda_articulo.id_articulo')
        ->where('detalle_cotizacion.id_cotizacion','=',$id)
        ->get();
        return $obj;
    }
    
    private function correlativoControl(){
        $mayor = DB::table('control')->where('control.tabla','=','Producto')->count();
         return $mayor;
     }



    public function anular(Request $request){
        $obj = Cotizacion::findOrFail($request->id);
        $obj->estado = 'Anulado';
        $obj->save();

    }
    
    public function pdfCotizacionSimple(Request $request){

        $id = $request->id;
        $id_tienda = 2;

        $cotizacion= Cotizacion::join('users','cotizacion.id_usuario','=','users.id')
        ->join('cliente','cotizacion.id_cliente','=','cliente.id')
        ->join('tipo_pago','cotizacion.id_tipo_pago','=','tipo_pago.id')
        ->select('cotizacion.id','cotizacion.fecha','cotizacion.fecha_venci','cotizacion.con_factura','cotizacion.dias_credito'
        ,'tipo_pago.id as id_tipo_pago','tipo_pago.nombre as tipo_pago','cotizacion.tiempo_entrega','cotizacion.lugar_entrega','cotizacion.nota','cotizacion.sub_total','cotizacion.descuento'
        ,'cotizacion.total','cotizacion.estado','users.name as usuario','cliente.nombre as cliente','cliente.descuento as id_descuento'
        ,'cliente.direccion as direccion','cliente.telefono as telefono','cliente.direccion as ciudad','cliente.matricula as nit'
        ,'users.id as id_usuario','cliente.id as id_cliente')
        ->where('cotizacion.id',$id)
        ->orderBy('cotizacion.id','desc')->get();

        $detalle_cotizacion= DetalleCotizacion::join('tienda_articulo','detalle_cotizacion.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('categoria','articulo.id_categoria','=','categoria.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_cotizacion.id_cotizacion','detalle_cotizacion.costo_venta','articulo.costo_unitario','articulo.marca',
        'articulo.costo_mayorista','articulo.costo_preferencial','articulo.nombre as producto',
        'categoria.nombre as categoria','detalle_cotizacion.cantidad','detalle_cotizacion.sub_total',
        'detalle_cotizacion.tiempo_entrega','tienda.nombre as tienda','tienda.id as id_tienda'
        ,'tienda_articulo.id as id_tienda_articulo','tienda_articulo.id_articulo','articulo.costo_unitario as costo_unitario'
        ,'articulo.costo_mayorista as costo_mayorista','articulo.costo_preferencial as costo_preferencial','tienda.cod_tienda','tienda.direccion','tienda.telefono','tienda.cod_almacen'
        ,'tienda.foto')
        ->where('detalle_cotizacion.id_cotizacion','=',$id)
        ->get();

        $mi_empresa= tienda::select('tienda.nombre','tienda.cod_tienda','tienda.direccion','tienda.telefono','tienda.cod_almacen'
        ,'tienda.foto')->where('id','=',$id_tienda)
        ->get();

        // dd($mi_empresa);

        $nombre_empresa=$mi_empresa[0]->nombre;
        $nit_empresa=$mi_empresa[0]->nit;
        $representante_empresa=$mi_empresa[0]->representante;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $localidad_empresa=$mi_empresa[0]->localidad;
        $correo_empresa=$mi_empresa[0]->Correo;
        $sitio_web_empresa=$mi_empresa[0]->sitio_web;
        $foto_empresa=$mi_empresa[0]->foto;

        $personal=$cotizacion[0]->personal;
        $cliente=$cotizacion[0]->cliente;
        $contacto=$cotizacion[0]->contacto;
        $direccion=$cotizacion[0]->direccion;
        $telefono=$cotizacion[0]->telefono;
        $correo=$cotizacion[0]->correo;
        $ciudad=$cotizacion[0]->ciudad;
        $nit=$cotizacion[0]->nit;
        $fecha=$cotizacion[0]->fecha;
        $fecha_venci=$cotizacion[0]->fecha_venci;
        $dias_credito=$cotizacion[0]->dias_credito;
        $tiempo_entrega=$cotizacion[0]->tiempo_entrega;
        $tipo_pago=$cotizacion[0]->tipo_pago;
        $lugar_entrega=$cotizacion[0]->lugar_entrega;
        $nota=$cotizacion[0]->nota;
        $detalles=$detalle_cotizacion;
        $total=$cotizacion[0]->total;
        $descuento=$cotizacion[0]->descuento;
        $sub_total=$cotizacion[0]->sub_total;
        $id_descuento=$cotizacion[0]->id_descuento;
        if($cotizacion[0]->id_descuento == 1) {
            $descuento_pago= 'Precio Unitario';
        } else if ($cotizacion[0]->id_descuento == 2) {
            $descuento_pago= 'Precio Mayorista';
        } else if ($cotizacion[0]->id_descuento == 3) {
            $descuento_pago=  'Precio Preferencial';
        } else {}

        $cont=Cotizacion::count();
        $pdf = \PDF::loadView('pdf.cotizacion.cotizacion_simple', [

            'nombre_empresa'=>$nombre_empresa,
            'nit_empresa'=>$nit_empresa,
            'representante_empresa'=>$representante_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'localidad_empresa'=>$localidad_empresa,
            'correo_empresa'=>$correo_empresa,
            'sitio_web_empresa'=>$sitio_web_empresa,
            'foto_empresa'=>$foto_empresa,

            'personal'=>$personal,
            'cotizacion'=>$cotizacion,
            'contacto'=>$contacto,
            'direccion'=>$direccion,
            'id_descuento' =>$id_descuento,
            'telefono'=>$telefono,
            'correo'=>$correo,
            'ciudad'=>$ciudad,
            'nit'=>$nit,
            'cliente'=>$cliente,
            'fecha'=>$fecha,
            'fecha_venci'=>$fecha_venci,
            'dias_credito'=>$dias_credito,
            'tiempo_entrega'=>$tiempo_entrega,
            'tipo_pago' =>$tipo_pago,
            'lugar_entrega' =>$lugar_entrega,
            'nota' =>$nota,
            'detalles'=>$detalles,
            'total'=>$total,
            'descuento'=>$descuento,
            'sub_total'=>$sub_total
        ]);
        return $pdf->stream('Ventas.pdf');
    }

    public function pdfCotizacionReporte(Request $request){

        $id = $request->id;
        $id_tienda = 2;

        $var2=DB::select("SELECT  MAX(id) as venta from cotizacion");
        $id=$var2[0]->venta;
        $foto = $request->foto;
        $empresa_nombre = $request->empresa_nombre;

        $cotizacion= Cotizacion::join('users','cotizacion.id_usuario','=','users.id')
        ->join('cliente','cotizacion.id_cliente','=','cliente.id')
        ->join('tipo_pago','cotizacion.id_tipo_pago','=','tipo_pago.id')
        ->select('cotizacion.id','cotizacion.fecha','cotizacion.fecha_venci','cotizacion.con_factura','cotizacion.dias_credito'
        ,'tipo_pago.id as id_tipo_pago','tipo_pago.nombre as tipo_pago','cotizacion.tiempo_entrega','cotizacion.lugar_entrega','cotizacion.nota','cotizacion.sub_total','cotizacion.descuento'
        ,'cotizacion.total','cotizacion.estado','users.name as usuario','cliente.nombre as cliente','cliente.descuento as id_descuento'
        ,'cliente.direccion as direccion','cliente.telefono as telefono','cliente.direccion as ciudad','cliente.matricula as nit'
        ,'users.id as id_usuario','cliente.id as id_cliente')
        ->where('cotizacion.id',$id)
        ->orderBy('cotizacion.id','desc')->get();

        $detalle_cotizacion= DetalleCotizacion::join('tienda_articulo','detalle_cotizacion.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('categoria','articulo.id_categoria','=','categoria.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_cotizacion.id_cotizacion','detalle_cotizacion.costo_venta','articulo.costo_unitario','articulo.marca',
        'articulo.costo_mayorista','articulo.costo_preferencial','articulo.nombre as producto',
        'categoria.nombre as categoria','detalle_cotizacion.cantidad','detalle_cotizacion.sub_total',
        'detalle_cotizacion.tiempo_entrega','tienda.nombre as tienda','tienda.id as id_tienda'
        ,'tienda_articulo.id as id_tienda_articulo','tienda_articulo.id_articulo','articulo.costo_unitario as costo_unitario'
        ,'articulo.costo_mayorista as costo_mayorista','articulo.costo_preferencial as costo_preferencial','tienda.cod_tienda','tienda.direccion','tienda.telefono','tienda.cod_almacen'
        ,'tienda.foto')
        ->where('detalle_cotizacion.id_cotizacion','=',$id)
        ->get();

        $mi_empresa= tienda::select('tienda.nombre','tienda.cod_tienda','tienda.direccion','tienda.telefono','tienda.cod_almacen'
        ,'tienda.foto')->where('id','=',$id_tienda)
        ->get();

        // dd($mi_empresa);

        $nombre_empresa=$mi_empresa[0]->nombre;
        $nit_empresa=$mi_empresa[0]->nit;
        $representante_empresa=$mi_empresa[0]->representante;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $localidad_empresa=$mi_empresa[0]->localidad;
        $correo_empresa=$mi_empresa[0]->Correo;
        $sitio_web_empresa=$mi_empresa[0]->sitio_web;
        $foto_empresa=$mi_empresa[0]->foto;

        $personal=$cotizacion[0]->personal;
        $cliente=$cotizacion[0]->cliente;
        $contacto=$cotizacion[0]->contacto;
        $direccion=$cotizacion[0]->direccion;
        $telefono=$cotizacion[0]->telefono;
        $correo=$cotizacion[0]->correo;
        $ciudad=$cotizacion[0]->ciudad;
        $nit=$cotizacion[0]->nit;
        $fecha=$cotizacion[0]->fecha;
        $fecha_venci=$cotizacion[0]->fecha_venci;
        $dias_credito=$cotizacion[0]->dias_credito;
        $tiempo_entrega=$cotizacion[0]->tiempo_entrega;
        $tipo_pago=$cotizacion[0]->tipo_pago;
        $lugar_entrega=$cotizacion[0]->lugar_entrega;
        $nota=$cotizacion[0]->nota;
        $detalles=$detalle_cotizacion;
        $total=$cotizacion[0]->total;
        $descuento=$cotizacion[0]->descuento;
        $sub_total=$cotizacion[0]->sub_total;
        $id_descuento=$cotizacion[0]->id_descuento;
        if($cotizacion[0]->id_descuento == 1) {
            $descuento_pago= 'Precio Unitario';
        } else if ($cotizacion[0]->id_descuento == 2) {
            $descuento_pago= 'Precio Mayorista';
        } else if ($cotizacion[0]->id_descuento == 3) {
            $descuento_pago=  'Precio Preferencial';
        } else {}

        $cont=Cotizacion::count();
        $pdf = \PDF::loadView('pdf.cotizacion.cotizacion_simple', [

            'nombre_empresa'=>$nombre_empresa,
            'nit_empresa'=>$nit_empresa,
            'representante_empresa'=>$representante_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'localidad_empresa'=>$localidad_empresa,
            'correo_empresa'=>$correo_empresa,
            'sitio_web_empresa'=>$sitio_web_empresa,
            'foto_empresa'=>$foto_empresa,

            'personal'=>$personal,
            'cotizacion'=>$cotizacion,
            'contacto'=>$contacto,
            'direccion'=>$direccion,
            'id_descuento' =>$id_descuento,
            'telefono'=>$telefono,
            'correo'=>$correo,
            'ciudad'=>$ciudad,
            'nit'=>$nit,
            'cliente'=>$cliente,
            'fecha'=>$fecha,
            'fecha_venci'=>$fecha_venci,
            'dias_credito'=>$dias_credito,
            'tiempo_entrega'=>$tiempo_entrega,
            'tipo_pago' =>$tipo_pago,
            'lugar_entrega' =>$lugar_entrega,
            'nota' =>$nota,
            'detalles'=>$detalles,
            'total'=>$total,
            'descuento'=>$descuento,
            'sub_total'=>$sub_total
        ]);
        return $pdf->stream('Ventas.pdf');
    }
      
 
}

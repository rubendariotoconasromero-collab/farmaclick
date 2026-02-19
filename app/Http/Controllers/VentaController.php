<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\DetalleVentaPaquete;
use App\Models\OrdenServicio;
use App\Models\Control;
use App\Models\Articulo;
use App\Models\Ajuste;
use App\Models\Pago;
use App\Models\CXCobrar;
use App\Models\ArqueoCaja;
use App\Models\MiEmpresa;
use DB;
use DateTime;

class VentaController extends BitacoraController
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
            'tienda.id as id_tienda','tienda.nombre as tienda')
            ->where('venta.tipo_venta','=','Venta Directa')
            //->orWhere('venta.tipo_venta','=','Venta Cotizacion')
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
            'tienda.id as id_tienda','tienda.nombre as tienda')
            ->where('venta.tipo_venta','=','Venta Directa')
            //->orWhere('venta.tipo_venta','=','Venta Cotizacion')
            ->where($criterio, 'like', '%'.$buscar.'%')
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
            'tienda.id as id_tienda','tienda.nombre as tienda')
            ->where('venta.tipo_venta','=','Venta Servicio')
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
            'tienda.id as id_tienda','tienda.nombre as tienda')
            ->where('venta.tipo_venta','=','Venta Servicio')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('venta.id','desc')->paginate(15);            
        }
        return $obj;
    }

public function indexPago(Request $request){
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
            'tienda.id as id_tienda','tienda.nombre as tienda')
            ->where('venta.tipo_venta','=','Venta Directa')
            ->where('venta.id_tipo_pago','=',2)
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
            'tienda.id as id_tienda','tienda.nombre as tienda')
            ->where('venta.id_tipo_pago','=',2)
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('venta.id','desc')->paginate(15);            
        }
        return $obj;
    }

    public function indexServicioPago(Request $request){
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
            'tienda.id as id_tienda','tienda.nombre as tienda')
            ->where('venta.tipo_venta','=','Venta Servicio')
            ->where('venta.id_tipo_pago','=',2)
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
            'tienda.id as id_tienda','tienda.nombre as tienda')
            ->where('venta.tipo_venta','=','Venta Servicio')
            ->where('venta.id_tipo_pago','=',2)
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('venta.id','desc')->paginate(15);            
        }
        return $obj;
    }

    private function actualizarStock($id_producto,$cantVenta){
        DB::table('tienda_articulo')->where('tienda_articulo.id_articulo','=',$id_producto)
        ->where('tienda_articulo.id_tienda','=',3)
        ->decrement('stock', $cantVenta);
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

    public function guardar(Request $request){
        try{
                DB::beginTransaction();
                $registro_venta = $request->total;
                $id_usuario=\Auth::user()->id;
                $correlativo = 0;
                $objdate = new DateTime();
                $fechaactual= $objdate->format('Y-m-d');
                $year = $objdate->format('y');
                $venta = new Venta();
                $venta->fecha=$request->fecha;
                $venta->sub_total=$request->sub_total;
                $venta->descuento=$request->descuento;
                $venta->total=$request->total;
                $venta->estado=$request->estado;
                $venta->id_cliente=$request->id_cliente;
                $venta->id_tipo_pago=$request->id_tipo_pago;
                $venta->tipo_venta=$request->tipo_venta;
                $venta->id_tienda=3;
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

                if($request->id_tipo_pago == 1) {
                    $pago = new Pago();
                    $pago->id = $venta->id;
                    $pago->fecha = $request->fecha;
                    $pago->fecha_final = $request->fecha_final;
                    $pago->monto = $request->total;
                    $pago->descripcion = "";
                    $pago->id_tipo_pago = $request->id_tipo_pago;
                    $pago->id_venta = $venta->id;
                    $pago->save();  

                    $this->actualizarCaja($registro_venta,$id_usuario);
                    
                } else if($request->id_tipo_pago == 2) {
    
                    $pago = new Pago();
                    $pago->id = $venta->id;
                    $pago->fecha = $request->fecha;
                    $pago->fecha_final = $request->fecha_final;
                    $pago->monto = $request->total;
                    $pago->descripcion = $request->descripcion = "";
                    $pago->id_tipo_pago = $request->id_tipo_pago;
                    $pago->id_venta = $venta->id;
                    $pago->save(); 

                    $cxcobrar = new CXCobrar();
                    $cxcobrar->fecha = $request->fecha;
                    //$cxcobrar->anticipo = $request->anticipo;
                    $cxcobrar->monto_total = $request->monto_total;
                    $cxcobrar->descripcion = $request->descripcion_pago;
                    $cxcobrar->saldo = $request->monto_total;
                    $cxcobrar->id_pago = $venta->id;
                    $cxcobrar->save(); 
    
                } else {
                    //
                }

                $correlativo = $this->correlativoControl();
    
                $control = new control();
                $control->tabla = $request->tabla = "Producto";
                $control->codigo = $request->codigo = 'VP'.strval($correlativo + 1).$year;
                $control->fecha = $fechaactual;
                $control->save();
    
                $detalles = $request->detalle;
                $costo_pago = $request->costo_pago;
                foreach($detalles as $ep=>$det){
                    $obj = new DetalleVenta();
                    $obj->id_venta= $venta->id;
                    // if($request->tipo_venta=="Venta Directa"){
                    //     $obj->id_producto= $det['id_tienda_articulo'];
                    // }else{
                    //     $obj->id_producto=0;
                    // }
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
                    $obj->save();
                    if($request->tipo_venta=='Venta Directa'){
                        $this->actualizarStock($det['id_articulo'],$det['cantidad']);
                    } else {
                        //
                    }
    
                    // $articulo = Articulo::findOrFail($det['id_articulo']);
                    // $articulo->costo_venta=$det['costo_venta'];
                    // $articulo->save(); 
                }
                if($request->tipo_venta=='Venta Directa'){
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
                    'tabla' => 'venta',
                    'codigo_tabla' => $venta->id,
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

    public function detalleVenta(Request $request){
        
        $id=$request->id; 
        $obj= detalleVenta::join('tienda_articulo','detalle_venta.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->join('categoria','articulo.id_categoria','=','categoria.id')
        ->select('detalle_venta.id_venta','detalle_venta.costo_venta','articulo.costo_unitario',
        'articulo.costo_mayorista','articulo.costo_preferencial','articulo.nombre_comercial as articulo',
        'detalle_venta.cantidad','detalle_venta.sub_total','tienda.nombre as tienda','tienda.id as id_tienda'
        ,'categoria.nombre as categoria')
        ->where('detalle_venta.id_venta','=',$id)
        ->get();
        return $obj;
    }
    
    public function anular(Request $request){
        $obj = Venta::findOrFail($request->id);
        $obj->estado = 'Anulado';
        $obj->save();

        $pago = Pago::findOrFail($request->id);
        $pago->estado = 0;
        $pago->save();

        if($obj->tipo_venta=='Venta Directa'){
            $detalles = DetalleVenta::select('id_venta','id_producto','cantidad')
            ->where('detalle_venta.id_venta',$request->id)->get();
            foreach($detalles as $ep=>$det){
                DB::table('tienda_articulo')->where('tienda_articulo.id','=',$det->id_producto)
                ->increment('stock', $det->cantidad);
            }
        } else {
            //
        }
        


    }

    public function cantidadRegistros(){
        $mayor = DB::table('control')->count();
        return $mayor;
    }

    private function correlativoControl(){
       $mayor = DB::table('control')->where('control.tabla','=','Producto')->count();
        return $mayor;
    }

    public function pdfVentas(Request $request){

        $id = $request->id;
        $foto = $request->foto;
        
        //dd($venta);
        $venta= Venta::join('users','venta.id_usuario','=','users.id')
        ->join('cliente','venta.id_cliente','=','cliente.id')
        ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
        ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
        ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento',
        'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','venta.total_efectivo','venta.total_deposito')
        ->where('venta.id',$id)
        ->orderBy('venta.id','desc')->get();


        $detalleVenta= detalleVenta::join('tienda_articulo','detalle_venta.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_venta.id_venta','detalle_venta.costo_venta','articulo.costo_unitario as costo_unitario','articulo.costo_mayorista as costo_mayorista','articulo.costo_preferencial as costo_preferencial','articulo.nombre as articulo','detalle_venta.cantidad','detalle_venta.sub_total','tienda.nombre as tienda')
        ->where('detalle_venta.id_venta','=',$id)
        ->get();

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
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
        $total_efectivo=$venta[0]->total_efectivo;
        $total_deposito=$venta[0]->total_deposito;
        $foto_empresa=$mi_empresa[0]->foto;
        if($venta[0]->id_descuento == 1) {
            $descuento_pago= 'Precio Unitario';
        } else if ($venta[0]->id_descuento == 2) {
            $descuento_pago= 'Precio Mayorista';
        } else if ($venta[0]->id_descuento == 3) {
            $descuento_pago=  'Precio Preferencial';
        } else {}

        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.venta.venta', [
            'foto_empresa'=>$foto_empresa,
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
            'total_efectivo'=>$total_efectivo,
            'total_deposito'=>$total_deposito,
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
        ->join('tienda','venta.id_tienda','=','tienda.id')
        ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento',
        'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP',
        'tienda.cod_tienda','tienda.nombre as tienda','tienda.direccion as tienda_direccion','tienda.cod_almacen','venta.total_efectivo','venta.total_deposito')
        ->where('venta.id','=',$id)
        ->orderBy('venta.id','desc')->get();
 // dd($venta);
        $detalleVenta= detalleVenta::join('tienda_articulo','detalle_venta.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->join('categoria','articulo.id_categoria','=','categoria.id')
        ->select('detalle_venta.id_venta','detalle_venta.costo_venta','articulo.costo_unitario as costo_unitario','articulo.costo_mayorista as costo_mayorista','categoria.nombre as categoria',
        'articulo.costo_preferencial as costo_preferencial','articulo.nombre_comercial as articulo','detalle_venta.cantidad','detalle_venta.sub_total','tienda.nombre as tienda')
        ->where('detalle_venta.id_venta','=',$id)
        ->orderBy('categoria.nombre','asc')
        ->orderBy('articulo.nombre_comercial','asc')
        ->get();
        
        $detallePaquete= DetalleVentaPaquete::join('venta','detalle_venta_paquete.id_venta','=','venta.id')
        ->join('paquetes','detalle_venta_paquete.id_paquete','=','paquetes.id')
        ->select('detalle_venta_paquete.id_paquete','detalle_venta_paquete.cantidad','detalle_venta_paquete.costo_venta','detalle_venta_paquete.id_paquete',
        'detalle_venta_paquete.costo_venta','detalle_venta_paquete.sub_total','paquetes.nombre as articulo')
        ->where('detalle_venta_paquete.id_venta','=',$id)
        ->orderBy('paquetes.nombre','asc')
        ->get();

        
        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
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
        $total_efectivo=$venta[0]->total_efectivo;
        $total_deposito=$venta[0]->total_deposito;
        $foto_empresa=$mi_empresa[0]->foto;
        if($venta[0]->id_descuento == 1) {
            $descuento_pago= 'Precio Unitario';
        } else if ($venta[0]->id_descuento == 2) {
            $descuento_pago= 'Precio Mayorista';
        } else if ($venta[0]->id_descuento == 3) {
            $descuento_pago=  'Precio Preferencial';
        } else {}

        $tienda=$venta[0]->tienda;
        $cod_tienda=$venta[0]->cod_tienda;
        $tienda_direccion=$venta[0]->tienda_direccion;
        $cod_almacen=$venta[0]->cod_almacen;
        $detallesPaquete=$detallePaquete;
        //dd($venta);

        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.venta.venta-general', [
            'foto_empresa'=>$foto_empresa,
            'ventas'=>$venta,
            'fecha'=>$fecha,
            'cliente'=>$cliente,
            'tipo_pago'=>$tipo_pago,
            'forma_pago'=>$forma_pago,
            'descuento_pago'=>$descuento_pago,
            'id_descuento' =>$id_descuento,
            'detalles'=>$detalles,
            'total'=>$total,
            'id'=>$id,
            'descuento'=>$descuento,
            'sub_total'=>$sub_total,
            'foto'=>$foto,
            'fecha_impresion'=>$fecha_impresion,
            'usuario'=>$usuario,
            'empresa_nombre'=>$empresa_nombre,
            'detallesPaquete'=>$detallesPaquete,
            'tienda'=>$tienda,
            'cod_tienda'=>$cod_tienda,
            'tienda_direccion'=>$tienda_direccion,
            'cod_almacen'=>$cod_almacen,
            'total_efectivo'=>$total_efectivo,
            'total_deposito'=>$total_deposito,
        ]);
        return $pdf->setPaper('letter', 'portrait')->stream('Ventas.pdf');
    }
    
    public function pdfVentasGeneral2(Request $request){

        $var2=DB::select("SELECT  MAX(id) as venta from venta");
        $id=$var2[0]->venta;
        $foto = $request->foto;
        $empresa_nombre = $request->empresa_nombre;

        //dd($id);
        
        
        $venta= Venta::join('users','venta.id_usuario','=','users.id')
        ->join('cliente','venta.id_cliente','=','cliente.id')
        ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
        ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
        ->join('tienda','venta.id_tienda','=','tienda.id')
        ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento',
        'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP',
        'tienda.cod_tienda','tienda.nombre as tienda','tienda.direccion as tienda_direccion','tienda.cod_almacen','venta.total_efectivo','venta.total_deposito')
        ->where('venta.id','=',$id)
        ->orderBy('venta.id','desc')->get();
  
        $detalleVenta= detalleVenta::join('tienda_articulo','detalle_venta.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->join('categoria','articulo.id_categoria','=','categoria.id')
        ->select('detalle_venta.id_venta','detalle_venta.costo_venta','articulo.costo_unitario as costo_unitario','articulo.costo_mayorista as costo_mayorista','categoria.nombre as categoria',
        'articulo.costo_preferencial as costo_preferencial','articulo.nombre_comercial as articulo','detalle_venta.cantidad','detalle_venta.sub_total','tienda.nombre as tienda')
        ->where('detalle_venta.id_venta','=',$id)
        ->orderBy('categoria.nombre','asc')
        ->orderBy('articulo.nombre_comercial','asc')
        ->get();

        $detallePaquete= DetalleVentaPaquete::join('venta','detalle_venta_paquete.id_venta','=','venta.id')
        ->join('paquetes','detalle_venta_paquete.id_paquete','=','paquetes.id')
        ->select('detalle_venta_paquete.id_paquete','detalle_venta_paquete.cantidad','detalle_venta_paquete.costo_venta','detalle_venta_paquete.id_paquete',
        'detalle_venta_paquete.costo_venta','detalle_venta_paquete.sub_total','paquetes.nombre as articulo')
        ->where('detalle_venta_paquete.id_venta','=',$id)
        ->orderBy('paquetes.nombre','asc')
        ->get();
        //dd($detallePaquete);

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
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
        $detallesPaquete=$detallePaquete;
        $total=$venta[0]->total;
        $descuento=$venta[0]->descuento;
        $sub_total=$venta[0]->sub_total;
        $total_efectivo=$venta[0]->total_efectivo;
        $total_deposito=$venta[0]->total_deposito;
        $foto_empresa=$mi_empresa[0]->foto;
        if($venta[0]->id_descuento == 1) {
            $descuento_pago= 'Precio Unitario';
        } else if ($venta[0]->id_descuento == 2) {
            $descuento_pago= 'Precio Mayorista';
        } else if ($venta[0]->id_descuento == 3) {
            $descuento_pago=  'Precio Preferencial';
        } else {}

        $tienda=$venta[0]->tienda;
        $cod_tienda=$venta[0]->cod_tienda;
        $tienda_direccion=$venta[0]->tienda_direccion;
        $cod_almacen=$venta[0]->cod_almacen;
        //dd($venta);

        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.venta.venta-general', [
            'foto_empresa'=>$foto_empresa,
            'ventas'=>$venta,
            'fecha'=>$fecha,
            'cliente'=>$cliente,
            'id'=>$id,
            'tipo_pago'=>$tipo_pago,
            'forma_pago'=>$forma_pago,
            'descuento_pago'=>$descuento_pago,
            'id_descuento' =>$id_descuento,
            'detalles'=>$detalles,
            'detallesPaquete'=>$detallesPaquete,
            'total'=>$total,
            'descuento'=>$descuento,
            'sub_total'=>$sub_total,
            'foto'=>$foto,
            'fecha_impresion'=>$fecha_impresion,
            'usuario'=>$usuario,
            'empresa_nombre'=>$empresa_nombre,

            'tienda'=>$tienda,
            'cod_tienda'=>$cod_tienda,
            'tienda_direccion'=>$tienda_direccion,
            'cod_almacen'=>$cod_almacen,
            'total_efectivo'=>$total_efectivo,
            'total_deposito'=>$total_deposito,
        ]);
        //dd($detallesPaquete);
        return $pdf->setPaper('letter', 'portrait')->stream('Ventas.pdf');
    }
    public function montoT(){
        $venta=venta::select(DB::raw('SUM(venta.total) as total'))->where('venta.estado','!=','Anulado')->get();
        //$venta=venta::sum('venta.total');
        return $venta;
    }
    public function montoD(){
        $venta=Venta::select(DB::raw('SUM(venta.total) as total'))->where('venta.tipo_venta','Venta Directa')->get();
        //dd($venta);
        return $venta;
    }
    public function montoS(){
        $venta=Venta::select(DB::raw('SUM(venta.total) as total'))->where('venta.tipo_venta','Venta Servicio')->get();
        //dd($venta);
        return $venta;
    }
}

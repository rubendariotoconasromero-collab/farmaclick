<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenServicio;
use App\Models\DetalleOrdenServicio;
use App\Models\DetalleVenta;
use App\Models\Control;
use App\Models\Articulo;
use App\Models\Ajuste;
use DB;
use DateTime;

class OrderServicioController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= OrdenServicio::join('users','orden_servicio.id_usuario','=','users.id')
            ->join('cliente','orden_servicio.id_cliente','=','cliente.id')
            ->join('personal','orden_servicio.id_personal','=','personal.id')
            ->join('tienda','orden_servicio.id_tienda','=','tienda.id')
            ->select('orden_servicio.id','orden_servicio.id_cliente','orden_servicio.id_personal','orden_servicio.fecha','orden_servicio.sub_total','orden_servicio.descuento',
            'orden_servicio.total','orden_servicio.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','personal.nombre as personal',
            'tienda.id as id_tienda','tienda.nombre as tienda')
            ->orderBy('orden_servicio.id','desc')->paginate(15);
        }
        else{
            $obj= OrdenServicio::join('users','orden_servicio.id_usuario','=','users.id')
            ->join('cliente','orden_servicio.id_cliente','=','cliente.id')
            ->join('personal','orden_servicio.id_personal','=','personal.id')
            ->join('tienda','orden_servicio.id_tienda','=','tienda.id')
            ->select('orden_servicio.id','orden_servicio.id_cliente','orden_servicio.id_personal','orden_servicio.fecha','orden_servicio.sub_total','orden_servicio.descuento',
            'orden_servicio.total','orden_servicio.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','personal.nombre as personal',
            'tienda.id as id_tienda','tienda.nombre as tienda')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('orden_servicio.id','desc')->paginate(15);            
        }
        return $obj;
    }

    private function actualizarStock($id_producto,$cantVenta){
        DB::table('tienda_articulo')->where('tienda_articulo.id_articulo','=',$id_producto)
        ->where('tienda_articulo.id_tienda','=',3)
        ->decrement('stock', $cantVenta);
    }

    public function guardar(Request $request){
        try{
               
                DB::beginTransaction();

                $servicio = new OrdenServicio();
                $servicio->fecha=$request->fecha;
                $servicio->sub_total=$request->sub_total;
                $servicio->descuento=$request->descuento;
                $servicio->descripcion=$request->descripcion;
                $servicio->total=$request->total;
                $servicio->estado=$request->estado;
                $servicio->id_cliente=$request->id_cliente;
                $servicio->id_personal=$request->id_personal;
                $servicio->id_usuario=\Auth::user()->id;
                $servicio->id_tienda=3;
                $servicio->save();  
    
                $objdate = new DateTime();
                $fechaactual= $objdate->format('Y-m-d');
                $year = $objdate->format('y');
                $correlativo = $this->correlativoControl();
    
                $control = new control();
                $control->tabla = $request->tabla = "Servicio";
                $control->codigo = $request->codigo = 'VS'.strval($correlativo + 1).$year;
                $control->fecha = $fechaactual;
                $control->save();
    
                $detalles = $request->detalle;
                $costo_pago = $request->costo_pago;
                foreach($detalles as $ep=>$det){
                    $obj = new DetalleOrdenServicio();
                    $obj->id_orden_servicio= $servicio->id;
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
                    if( $det['tipo_producto'] == 'Producto Venta') {
                        $this->actualizarStock($det['id_articulo'],$det['cantidad']);
                    } else
                    {
                        //
                    }

    
                    // $articulo = Articulo::findOrFail($det['id_articulo']);
                    // $articulo->costo_venta=$det['costo_venta'];
                    // $articulo->save(); 
                }

                // if($request->estado == "Concluido"){
                //     $correlativo = 0;
                //     $objdate = new DateTime();
                //     $fechaactual= $objdate->format('Y-m-d');
                //     $year = $objdate->format('y');
                //     $venta = new Venta();
                //     $venta->fecha=$request->fecha;
                //     $venta->sub_total=$request->sub_total;
                //     $venta->descuento=$request->descuento;
                //     $venta->total=$request->total;
                //     $venta->estado=$request->estado;
                //     $venta->id_cliente=$request->id_cliente;
                //     $venta->id_tipo_pago=1;
                //     $venta->id_forma_pago=1;
                //     //$venta->id_pago=$pago->id;
                //     $venta->id_usuario=\Auth::user()->id;
                //     $venta->save();  

                // }
                $ajuste = new Ajuste();
                $ajuste->stock=$det['cantidad'];
                $ajuste->costo_compra=0;
                $ajuste->costo_venta=$det['costo_unitario'];
                $ajuste->observacion=$request->descripcion;
                $ajuste->id_articulo=$det['id_articulo'];
                $ajuste->id_motivo_ajuste=6;
                $ajuste->save();
    
                $datos = [
                    'tabla' => 'servicio',
                    'codigo_tabla' => $servicio->id,
                    'transaccion' => 'guardar',
                ];
                $this->guardarBitacora($datos);   
             
                // if ($request->tipo_producto == 'Producto Servicio')
            //  {
            //     DB::beginTransaction();

            //     $correlativo = 1;
            //     $objdate = new DateTime();
            //     $fechaactual= $objdate->format('Y-m-d');

            //     $servicio = new OrdenServicio();
            //     $servicio->fecha=$request->fecha;
            //     $servicio->sub_total=$request->sub_total;
            //     $servicio->descuento=$request->descuento;
            //     $servicio->descripcion=$request->descripcion;
            //     $servicio->total=$request->total;
            //     $servicio->estado=$request->estado;
            //     $servicio->id_cliente=$request->id_cliente;
            //     $servicio->id_personal=$request->id_personal;
            //     $servicio->id_usuario=\Auth::user()->id;
            //     $servicio->save();  
    
            //     $control = new control();
            //     $control->tabla = $request->tabla = "Servicio";
            //     $control->codigo = $request->codigo = $correlativo + 1;
            //     $control->fecha = $fechaactual;
            //     $control->save();
    
            //     $detalles = $request->detalle;
            //     $costo_pago = $request->costo_pago;
            //     foreach($detalles as $ep=>$det){
            //         $obj = new DetalleOrdenServicio();
            //         $obj->id_orden_servicio= $servicio->id;
            //         $obj->id_producto= $det['id_tienda_articulo'];
            //         $obj->cantidad= $det['cantidad'];
            //         if($request->id_costo_pago == 1) {
            //             $obj->costo_venta= $det['costo_unitario'];
            //         } else if ($request->id_costo_pago == 2) {
            //             $obj->costo_venta= $det['costo_mayorista'];
            //         } else if ($request->id_costo_pago == 3) {
            //             $obj->costo_venta= $det['costo_preferencial'];
            //         } else {}
                    
            //         $obj->sub_total= $det['sub_total'];
            //         $obj->save();
            //         // $this->actualizarStock($det['id_articulo'],$det['cantidad']);
    
            //         // $articulo = Articulo::findOrFail($det['id_articulo']);
            //         // $articulo->costo_venta=$det['costo_venta'];
            //         // $articulo->save(); 
            //     }
            //     $ajuste = new Ajuste();
            //     $ajuste->stock=$det['cantidad'];
            //     $ajuste->costo_compra=0;
            //     $ajuste->costo_venta=$det['costo_unitario'];
            //     $ajuste->observacion=$request->descripcion;
            //     $ajuste->id_articulo=$det['id_articulo'];
            //     $ajuste->id_motivo_ajuste=6;
            //     $ajuste->save();
    
                $datos = [
                    'tabla' => 'servicio',
                    'codigo_tabla' => $servicio->id,
                    'transaccion' => 'guardar',
                ];
                $this->guardarBitacora($datos);   
             
            // }
            // else
            //  {
                 
            //  }

            
            
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function modificar(Request $request){
        // if($request->estado == "Concluido"){

            $servicio= OrdenServicio::findOrFail($request->id);
            $servicio->estado=$request->estado;
            $servicio->save();

            // $correlativo = 0;
            // $objdate = new DateTime();
            // $fechaactual= $objdate->format('Y-m-d');
            // $year = $objdate->format('y');
            // $venta = new Venta();
            // $venta->fecha=$request->fecha;
            // $venta->sub_total=$request->sub_total;
            // $venta->descuento=$request->descuento;
            // $venta->total=$request->total;
            // $venta->estado=$request->estado;
            // $venta->id_cliente=$request->id_cliente;
            // $venta->id_tipo_pago=1;
            // $venta->id_forma_pago=1;
            // //$venta->id_pago=$pago->id;
            // $venta->id_usuario=\Auth::user()->id;
            // $venta->save();  

            // $pago = new Pago();
            // $pago->fecha = $request->fecha;
            // $pago->fecha_final = $request->fecha;
            // $pago->monto = $request->total;
            // $pago->descripcion = "";
            // $pago->id_tipo_pago = 1;
            // $pago->id_venta = $venta->id;
            // $pago->save();  


        // } else {
        //     $servicio= OrdenServicio::findOrFail($request->id);
        //     $servicio->fecha=$request->fecha;
        //     $servicio->sub_total=$request->sub_total;
        //     $servicio->descuento=$request->descuento;
        //     $servicio->descripcion=$request->descripcion;
        //     $servicio->total=$request->total;
        //     $servicio->estado=$request->estado;
        //     $servicio->id_cliente=$request->id_cliente;
        //     $servicio->id_personal=$request->id_personal;
        //     $servicio->id_usuario=\Auth::user()->id;
        //     $servicio->save();
        // }

        // $detalles = $request->detalle;
        // $costo_pago = $request->costo_pago;
        // foreach($detalles as $ep=>$det){
        //     $obj = new DetalleOrdenServicio();
        //     $obj->id_orden_servicio= $servicio->id;
        //     $obj->id_producto= $det['id_tienda_articulo'];
        //     $obj->cantidad= $det['cantidad'];
        //     if($request->id_costo_pago == 1) {
        //         $obj->costo_venta= $det['costo_unitario'];
        //     } else if ($request->id_costo_pago == 2) {
        //         $obj->costo_venta= $det['costo_mayorista'];
        //     } else if ($request->id_costo_pago == 3) {
        //         $obj->costo_venta= $det['costo_preferencial'];
        //     } else {}
            
        //     $obj->sub_total= $det['sub_total'];
        //     $obj->save();
        //     if( $det['tipo_producto'] == 'Producto Venta') {
        //         $this->actualizarStock($det['id_articulo'],$det['cantidad']);
        //     } else
        //     {
        //         //
        //     }
        // }

        $datos = [
            'tabla' => 'orden_servicio',
            'codigo_tabla' => $servicio->id,
            'transaccion' => 'modificar',
        ];
        $this->guardarBitacora($datos);
    }    

    public function obtenerCabecera(Request $request){
        
        $id=$request->id; 
        $obj= Venta::join('proveedor','compra.id_proveedor','=','proveedor.id')
        ->select('compra.id','compra.fecha','compra.descripcion','compra.total','proveedor.nombre as proveedor')
        ->where('compra.id','=',$id)
        ->get();

        return $obj;
    }
    

    public function detalleOrdenServicio(Request $request){


        $id=$request->id; 
        $obj= detalleOrdenServicio::join('tienda_articulo','detalle_orden_servicio.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->leftjoin('categoria', function($join){
            $join->orOn('articulo.id_categoria','=','categoria.id');
        })
        ->select('detalle_orden_servicio.id_orden_servicio','detalle_orden_servicio.costo_venta','detalle_orden_servicio.id_producto as id_tienda_articulo','tienda_articulo.stock',
        'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.nombre_comercial as articulo','articulo.costo_compra','articulo.id as id_articulo',
        'detalle_orden_servicio.cantidad','detalle_orden_servicio.sub_total','tienda.nombre as tienda','tienda.id as id_tienda','categoria.nombre as categoria','articulo.tipo_producto')
        ->where('detalle_orden_servicio.id_orden_servicio','=',$id)
        ->get();
        return $obj;
        
    }
    
    public function anular(Request $request){
        $obj = OrdenServicio::findOrFail($request->id);
        $obj->estado = 'Anulado';
        $obj->save();

        $detalles = DetalleOrdenServicio::select('id_orden_servicio','id_producto','cantidad')
        ->where('detalle_orden_servicio.id_orden_servicio',$request->id)->get();
        foreach($detalles as $ep=>$det){
            DB::table('tienda_articulo')->where('tienda_articulo.id','=',$det->id_producto)
            ->increment('stock', $det->cantidad);
        }

    }

    private function correlativoControl(){
        $mayor = DB::table('control')->where('control.tabla','=','Servicio')->count();
         return $mayor;
    }

    public function listarOrdenSinPaginate(Request $request){
        $buscar = $request->buscar;     
        if ($buscar==''){

            $orden_servicio = OrdenServicio::join('users','orden_servicio.id_usuario','=','users.id')
            ->join('cliente','orden_servicio.id_cliente','=','cliente.id')
            ->join('personal','orden_servicio.id_personal','=','personal.id')
            ->select('orden_servicio.id','orden_servicio.id_cliente','orden_servicio.id_personal','personal.nombre as personal','orden_servicio.id_usuario','orden_servicio.fecha',
            'orden_servicio.sub_total','orden_servicio.descuento','orden_servicio.total','orden_servicio.estado',
            'users.id as id_users','users.name as usuario','cliente.nombre as cliente','cliente.nombre as cliente','cliente.descuento as id_descuento')
            ->where('orden_servicio.estado','=','Concluido')
            ->orderBy('orden_servicio.id', 'desc')->get();
        }
        else{
            $orden_servicio = OrdenServicio::join('users','orden_servicio.id_usuario','=','users.id')
            ->join('cliente','orden_servicio.id_cliente','=','cliente.id')
            ->join('personal','orden_servicio.id_personal','=','personal.id')
            ->select('orden_servicio.id','orden_servicio.id_cliente','orden_servicio.id_personal','personal.nombre as personal','orden_servicio.id_usuario','orden_servicio.fecha',
            'orden_servicio.sub_total','orden_servicio.descuento','orden_servicio.total','orden_servicio.estado',
            'users.id as id_users','users.name as usuario','cliente.nombre as cliente','cliente.nombre as cliente','cliente.descuento as id_descuento')
            ->orderBy('orden_servicio.id', 'desc')
            ->where('orden_servicio.estado','=','Concluido')
            ->where('orden_servicio.id' , 'like', '%'.$buscar.'%')->get();
        }
        return $orden_servicio;
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

    public function pdfOrdenServiciosGeneral(Request $request){

        $id = $request->id;
        $foto = $request->foto;
        
        $orden_servicio= OrdenServicio::join('users','orden_servicio.id_usuario','=','users.id')
        ->join('cliente','orden_servicio.id_cliente','=','cliente.id')
        ->join('tienda','orden_servicio.id_tienda','=','tienda.id')
        ->select('orden_servicio.id','orden_servicio.fecha','orden_servicio.sub_total','orden_servicio.descuento',
        'orden_servicio.total','orden_servicio.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento',
        'tienda.cod_tienda','tienda.nombre as tienda','tienda.direccion as tienda_direccion','tienda.cod_almacen')
        ->where('orden_servicio.id',$id)
        ->orderBy('orden_servicio.id','desc')->get();

        // $venta= Venta::join('users','venta.id_usuario','=','users.id')
        // ->join('cliente','venta.id_cliente','=','cliente.id')
        // ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
        // ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
        // ->join('tienda','venta.id_tienda','=','tienda.id')
        // ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento',
        // 'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP',
        // 'tienda.cod_tienda','tienda.nombre as tienda','tienda.direccion as tienda_direccion','tienda.cod_almacen')
        // ->where('venta.id','=',$id)
        // ->orderBy('venta.id','desc')->get();

        $detalleOrdenServicio= detalleOrdenServicio::join('tienda_articulo','detalle_orden_servicio.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->leftjoin('categoria', function($join){
            $join->orOn('articulo.id_categoria','=','categoria.id');
        })
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_orden_servicio.id_orden_servicio','detalle_orden_servicio.costo_venta','articulo.costo_unitario as costo_unitario','articulo.costo_mayorista as costo_mayorista',
        'articulo.costo_preferencial as costo_preferencial','articulo.nombre_comercial as articulo','detalle_orden_servicio.cantidad','detalle_orden_servicio.sub_total','tienda.nombre as tienda','categoria.nombre as categoria')
        ->where('detalle_orden_servicio.id_orden_servicio','=',$id)
        ->orderBy('categoria.nombre','asc')
        ->orderBy('articulo.nombre_comercial','asc')
        ->get();

        $usuario = \Auth::user()->name;
        $objdate = new DateTime();
        $fecha_impresion=$objdate->format('d/m/Y');
        $cliente=$orden_servicio[0]->cliente;
        $fecha=$orden_servicio[0]->fecha;
        $id_descuento=$orden_servicio[0]->id_descuento;
        $detalles=$detalleOrdenServicio;
        $total=$orden_servicio[0]->total;
        $descuento=$orden_servicio[0]->descuento;
        $sub_total=$orden_servicio[0]->sub_total;
        if($orden_servicio[0]->id_descuento == 1) {
            $descuento_pago= 'Precio Unitario';
        } else if ($orden_servicio[0]->id_descuento == 2) {
            $descuento_pago= 'Precio Mayorista';
        } else if ($orden_servicio[0]->id_descuento == 3) {
            $descuento_pago=  'Precio Preferencial';
        } else {}

        $tienda=$orden_servicio[0]->tienda;
        $cod_tienda=$orden_servicio[0]->cod_tienda;
        $tienda_direccion=$orden_servicio[0]->tienda_direccion;
        $cod_almacen=$orden_servicio[0]->cod_almacen;

        $cont=OrdenServicio::count();
        $pdf = \PDF::loadView('pdf.orden_servicio.orden-servicio-general', [
            'id'=>$id,
            'orden_servicios'=>$orden_servicio,
            'fecha'=>$fecha,
            'cliente'=>$cliente,
            'descuento_pago'=>$descuento_pago,
            'id_descuento' =>$id_descuento,
            'detalles'=>$detalles,
            'total'=>$total,
            'descuento'=>$descuento,
            'sub_total'=>$sub_total,
            'foto'=>$foto,
            'fecha_impresion'=>$fecha_impresion,
            'usuario'=>$usuario,

            'tienda'=>$tienda,
            'cod_tienda'=>$cod_tienda,
            'tienda_direccion'=>$tienda_direccion,
            'cod_almacen'=>$cod_almacen
        ]);
        return $pdf->setPaper('letter', 'portrait')->stream('OrdenServicio.pdf');
    }
    public function pdfOrdenServiciosGeneral2(Request $request){

        $var2=DB::select("SELECT  MAX(id) as orden_servicio from orden_servicio");
        $id=$var2[0]->orden_servicio;
        //dd($id);
        $foto = $request->foto;
        
        $orden_servicio= OrdenServicio::join('users','orden_servicio.id_usuario','=','users.id')
        ->join('cliente','orden_servicio.id_cliente','=','cliente.id')
        ->join('tienda','orden_servicio.id_tienda','=','tienda.id')
        ->select('orden_servicio.id','orden_servicio.fecha','orden_servicio.sub_total','orden_servicio.descuento',
        'orden_servicio.total','orden_servicio.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento',
        'tienda.cod_tienda','tienda.nombre as tienda','tienda.direccion as tienda_direccion','tienda.cod_almacen')
        ->where('orden_servicio.id',$id)
        ->orderBy('orden_servicio.id','desc')->get();

        // $venta= Venta::join('users','venta.id_usuario','=','users.id')
        // ->join('cliente','venta.id_cliente','=','cliente.id')
        // ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
        // ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
        // ->join('tienda','venta.id_tienda','=','tienda.id')
        // ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento',
        // 'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP',
        // 'tienda.cod_tienda','tienda.nombre as tienda','tienda.direccion as tienda_direccion','tienda.cod_almacen')
        // ->where('venta.id','=',$id)
        // ->orderBy('venta.id','desc')->get();

        $detalleOrdenServicio= detalleOrdenServicio::join('tienda_articulo','detalle_orden_servicio.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->leftjoin('categoria', function($join){
            $join->orOn('articulo.id_categoria','=','categoria.id');
        })
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_orden_servicio.id_orden_servicio','detalle_orden_servicio.costo_venta','articulo.costo_unitario as costo_unitario','articulo.costo_mayorista as costo_mayorista',
        'articulo.costo_preferencial as costo_preferencial','articulo.nombre_comercial as articulo','detalle_orden_servicio.cantidad','detalle_orden_servicio.sub_total','tienda.nombre as tienda','categoria.nombre as categoria')
        ->where('detalle_orden_servicio.id_orden_servicio','=',$id)
        ->orderBy('categoria.nombre','asc')
        ->orderBy('articulo.nombre_comercial','asc')
        ->get();

        $usuario = \Auth::user()->name;
        $objdate = new DateTime();
        $fecha_impresion=$objdate->format('d/m/Y');
        $cliente=$orden_servicio[0]->cliente;
        $fecha=$orden_servicio[0]->fecha;
        $id_descuento=$orden_servicio[0]->id_descuento;
        $detalles=$detalleOrdenServicio;
        $total=$orden_servicio[0]->total;
        $descuento=$orden_servicio[0]->descuento;
        $sub_total=$orden_servicio[0]->sub_total;
        if($orden_servicio[0]->id_descuento == 1) {
            $descuento_pago= 'Precio Unitario';
        } else if ($orden_servicio[0]->id_descuento == 2) {
            $descuento_pago= 'Precio Mayorista';
        } else if ($orden_servicio[0]->id_descuento == 3) {
            $descuento_pago=  'Precio Preferencial';
        } else {}

        $tienda=$orden_servicio[0]->tienda;
        $cod_tienda=$orden_servicio[0]->cod_tienda;
        $tienda_direccion=$orden_servicio[0]->tienda_direccion;
        $cod_almacen=$orden_servicio[0]->cod_almacen;

        $cont=OrdenServicio::count();
        $pdf = \PDF::loadView('pdf.orden_servicio.orden-servicio-general', [
            'id'=>$id,
            'orden_servicios'=>$orden_servicio,
            'fecha'=>$fecha,
            'cliente'=>$cliente,
            'descuento_pago'=>$descuento_pago,
            'id_descuento' =>$id_descuento,
            'detalles'=>$detalles,
            'total'=>$total,
            'descuento'=>$descuento,
            'sub_total'=>$sub_total,
            'foto'=>$foto,
            'fecha_impresion'=>$fecha_impresion,
            'usuario'=>$usuario,

            'tienda'=>$tienda,
            'cod_tienda'=>$cod_tienda,
            'tienda_direccion'=>$tienda_direccion,
            'cod_almacen'=>$cod_almacen
        ]);
        return $pdf->setPaper('letter', 'portrait')->stream('OrdenServicio.pdf');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tienda;
use App\Models\TiendaArticulo;
use App\Models\Lote;
use App\Models\Proveedor;
use DB;

class TiendaController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;       
        if ($buscar==''){
            $mi_empresa = Tienda::orderBy('tienda.id', 'desc')
            ->where('tienda.id','!=',1)->paginate(15);
        }
        else{
            $mi_empresa = Tienda::where('tienda.'.$criterio, 'like', '%'.$buscar.'%')
            ->where('tienda.id','!=',1)
            ->orderBy('tienda.id', 'desc')->paginate(15);
        }
        return $mi_empresa;
    }

    public function modificar(Request $request){
        $id_tienda = $request->id;
        $nombre_tienda = $request->nombre;
        $obj= Tienda::findOrFail($id_tienda);
        $obj->cod_tienda=$request->cod_tienda;
        $obj->cod_almacen=$request->cod_almacen;
        $obj->direccion=$request->direccion;
        $obj->estado=$request->estado;
        $obj->nombre=$nombre_tienda;

        if($request->foto==null){
            $obj->foto ='logo.png';
        }
        else{
            if($request->imagenActual==$request->foto){
                $obj->foto =$request->imagenActual;
            }
            else{
                $explode=explode(',',$request->foto);
                $decoded=\base64_decode($explode[1]);
                if(str_contains($explode[0],'jpeg')){
                    $extension='jpg';
                }
                else{
                    $extension='png';
                }
                $fileName = \str_random().'.'.$extension;
                $path= 'img/mi_empresa'.'/'.$fileName;
                \file_put_contents($path,$decoded);
                $obj->foto=$fileName;   
            }
        }
        $obj->save();

        $id_motivo_ajuste=0;
        if($id_tienda == 2) {
            $id_motivo_ajuste = 7;
        }else if($id_tienda == 3){
            $id_motivo_ajuste = 8;
        }else if($id_tienda == 4){
            $id_motivo_ajuste = 9;
        }else{
            //
        }

        $affected = DB::table('motivo_ajuste')
                ->where('id',$id_motivo_ajuste)
                ->update(['nombre' => 'Venta '.$nombre_tienda]); 

        $datos = [
            'tabla' => 'tienda',
            'codigo_tabla' => $obj->id,
            'transaccion' => 'modificar',
        ];
        $this->guardarBitacora($datos);
    }

    public function selectTienda(Request $request){
        $tienda = Tienda::select('id', 'nombre')->get();        
        return $tienda;
    }

    public function selectTienda2(Request $request){
        $tienda1 = $request->id_tienda1;

        $tienda=DB::select("SELECT id, nombre FROM tienda WHERE tienda.id != 1 and tienda.id != $tienda1");
        return $tienda;
    }

    private function actualizarStock($id_producto,$cantVenta){
        DB::table('tienda_articulo')->where('tienda_articulo.id_articulo','=',$id_producto)
        ->where('tienda_articulo.id_tienda','=',1)
        ->decrement('stock', $cantVenta);
    }

    public function guardarArticulo(Request $request){
        try{
            DB::beginTransaction();
            $detalles = $request->detalle;
            $id_tienda = $request->id_tienda;


            foreach($detalles as $ep=>$det){
                $id_articulo = $det['id_articulo'];
                

                $tienda_articulo=DB::select("SELECT id, id_articulo, id_tienda,stock
                FROM tienda_articulo
                where id_articulo = $id_articulo and id_tienda = $id_tienda");

                //dd($tienda_articulo);
                if($tienda_articulo != []){
                    $tienda_articulo_stock = $tienda_articulo[0]->stock ;
                } else {
                    //
                }

                if($tienda_articulo != []) {
                    $tienda_articulo = TiendaArticulo::findOrFail($tienda_articulo[0]->id);
                    $tienda_articulo->stock = $tienda_articulo_stock + $det['stock'];
                    $tienda_articulo->save();

                    $datos = [
                        'tabla' => 'tienda_articulo',
                        'codigo_tabla' => $tienda_articulo->id,
                        'transaccion' => 'modificar',
                    ];
                    $this->guardarBitacora($datos);


                }else{
                    $obj = new TiendaArticulo();
                    $obj->id_articulo= $id_articulo;
                    $obj->id_tienda= $id_tienda;
                    $obj->stock= $det['stock'];
                    $obj->save();

                    $datos = [
                        'tabla' => 'tienda_articulo',
                        'codigo_tabla' => $obj->id,
                        'transaccion' => 'guardar',
                    ];
                    $this->guardarBitacora($datos);

                }
                $this->actualizarStock($det['id_articulo'],$det['stock']);


            }

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function detalleArticuloTienda(Request $request){
        $obj= TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->select('articulo.cod_producto','articulo.cod_proveedor','articulo.nombre','articulo.costo_compra',
        'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','tienda_articulo.stock',
        'tienda_articulo.id_articulo','tienda_articulo.id_tienda','articulo.tipo_producto')
        ->where('tienda_articulo.id_tienda','=',$request->id)
        ->get();
        return $obj;
    }

    public function detalleArticuloTiendaProducto(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar==''){
            $obj= TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->select('articulo.id','articulo.cod_producto','articulo.cod_proveedor','articulo.nombre','articulo.costo_compra',
            'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','tienda_articulo.stock','articulo.marca',
            'tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','articulo.tipo_producto','categoria.nombre as categoria')
            ->where('tienda_articulo.id_tienda','=',$request->id)
            ->where('articulo.tipo_producto','=','Producto Venta')
            ->orderBy('tienda_articulo.id', 'desc')
            ->get();
        } else {
            $obj= TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->select('articulo.id','articulo.cod_producto','articulo.cod_proveedor','articulo.nombre','articulo.costo_compra',
            'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','tienda_articulo.stock','articulo.marca',
            'tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','articulo.tipo_producto','categoria.nombre as categoria')
            ->where('tienda_articulo.id_tienda','=',$request->id)
            ->where('articulo.tipo_producto','=','Producto Venta')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('tienda_articulo.id', 'desc')
            ->get();

        }
        return $obj;
    }

    public function detalleArticuloTiendaServicio(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar==''){
            $obj= TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->select('articulo.id','articulo.cod_producto','articulo.cod_proveedor','articulo.nombre','articulo.costo_compra',
            'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','tienda_articulo.stock',
            'tienda_articulo.id_articulo','tienda_articulo.id_tienda','articulo.tipo_producto','categoria.nombre as categoria')
            ->where('tienda_articulo.id_tienda','=',$request->id)
            ->where('articulo.tipo_producto','=','Producto Servicio')
            ->orderBy('tienda_articulo.id', 'desc')
            ->get();
        } else {
            $obj= TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->select('articulo.id','articulo.cod_producto','articulo.cod_proveedor','articulo.nombre','articulo.costo_compra',
            'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','tienda_articulo.stock',
            'tienda_articulo.id_articulo','tienda_articulo.id_tienda','articulo.tipo_producto','categoria.nombre as categoria')
            ->where('tienda_articulo.id_tienda','=',$request->id)
            ->where('articulo.tipo_producto','=','Producto Servicio')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('tienda_articulo.id', 'desc')
            ->get();

        }
        return $obj;


    }


    public function inventario(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;     
        if ($buscar==''){
            $obj= TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->select('tienda_articulo.id as id_tienda_articulo', 'articulo.id','articulo.cod_sistema','articulo.cod_proveedor','articulo.nombre_comercial','articulo.costo_compra'
            ,'articulo.costo_unitario','tienda_articulo.stock','categoria.nombre as categoria','articulo.nombre_generico'
            ,'proveedor.nombre as laboratorio','articulo.venta_presentacion','articulo.cantidad_blister','articulo.cantidad_caja',
            'articulo.precio_caja','articulo.precio_blister')
            ->where('tienda_articulo.id_tienda',1)
            ->where('articulo.estado','=',1)
            ->orderBy('tienda_articulo.id', 'desc')->paginate(30);
        }
        else{
            if($criterio=="proveedor.nombre"){
                
                $obj= TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
                ->select('tienda_articulo.id as id_tienda_articulo', 'articulo.id','articulo.cod_sistema','articulo.cod_proveedor','articulo.nombre_comercial','articulo.costo_compra'
                ,'articulo.costo_unitario','tienda_articulo.stock','categoria.nombre as categoria','articulo.nombre_generico'
                ,'proveedor.nombre as laboratorio','articulo.venta_presentacion','articulo.cantidad_blister','articulo.cantidad_caja',
                'articulo.precio_caja','articulo.precio_blister')
                ->where('tienda_articulo.id_tienda',1)
                ->where('articulo.estado','=',1)
                ->where($criterio,$buscar)
                ->orderBy('tienda_articulo.id', 'desc')->paginate(30);
            }else{

                $obj= TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
                ->select('tienda_articulo.id as id_tienda_articulo', 'articulo.id','articulo.cod_sistema','articulo.cod_proveedor','articulo.nombre_comercial','articulo.costo_compra'
                ,'articulo.costo_unitario','tienda_articulo.stock','categoria.nombre as categoria','articulo.nombre_generico'
                ,'proveedor.nombre as laboratorio','articulo.venta_presentacion','articulo.cantidad_blister','articulo.cantidad_caja',
                'articulo.precio_caja','articulo.precio_blister')
                ->where('tienda_articulo.id_tienda',1)
                ->where('articulo.estado','=',1)
                ->where($criterio, 'like', '%'.$buscar.'%')
                ->orderBy('tienda_articulo.id', 'desc')->paginate(30);
            }
        }
        return $obj;
    }

    public function listarSinPaginate(Request $request){
        $buscar = $request->buscar; 
        $criterio = $request->criterio;    
        $id_proveedor = $request->id_proveedor;  
        //dd($id_proveedor);  
        if ($buscar==''){

            $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock',
            'articulo.nombre_comercial as articulo','tienda.nombre as tienda',
            'articulo.costo_compra','articulo.costo_unitario','articulo.precio_blister',
            'articulo.precio_caja','articulo.id_categoria','categoria.nombre as categoria','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion')
            ->where('tienda_articulo.id_tienda',1)
            ->where('articulo.id_proveedor','=',$id_proveedor)
            ->where('articulo.estado','=',1)
            ->orderBy('tienda_articulo.id', 'desc')
            ->paginate(100);
            //->get();
        }
        else{
            $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock',
            'articulo.nombre_comercial as articulo','tienda.nombre as tienda',
            'articulo.costo_compra','articulo.costo_unitario','articulo.precio_blister',
            'articulo.precio_caja','articulo.id_categoria','categoria.nombre as categoria','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion')
            ->where('tienda_articulo.id_tienda',1)
            ->where('articulo.id_proveedor','=',$id_proveedor)
            ->where('articulo.estado','=',1)
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('tienda_articulo.id', 'desc')
            ->paginate(100);
            //->get();
        }
        return $tienda_articulo;
    }
    // public function listarSinPaginateAjuste(Request $request){
    //     $buscar = $request->buscar; 
    //     $criterio = $request->criterio;    
    //     //dd($id_proveedor);  
    //     if ($buscar==''){

    //         $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
    //         ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
    //         ->join('categoria','articulo.id_categoria','=','categoria.id')
    //         ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock',
    //         'articulo.nombre_comercial as articulo','tienda.nombre as tienda',
    //         'articulo.costo_compra','articulo.tipo_producto','articulo.costo_unitario','articulo.costo_mayorista',
    //         'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria')
    //         ->where('tienda_articulo.id_tienda',1)
    //         ->where('articulo.tipo_producto', 'Producto Venta')
    //         ->orderBy('tienda_articulo.id', 'desc')->get();
    //     }
    //     else{
    //         $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
    //         ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
    //         ->join('categoria','articulo.id_categoria','=','categoria.id')
    //         ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','tienda.nombre as tienda',
    //         'articulo.costo_compra','articulo.tipo_producto','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria')
    //         ->where('tienda_articulo.id_tienda',1)
    //         ->where('articulo.tipo_producto', 'Producto Venta')
    //         ->where($criterio, 'like', '%'.$buscar.'%')
    //         ->orderBy('tienda_articulo.id', 'desc')->get();
    //     }
    //     return $tienda_articulo;
    // }
    public function listarSinPaginateLote(Request $request){
        $buscar = $request->buscar; 
        $criterio = $request->criterio;    
        //dd($id_proveedor);  
        if ($buscar==''){

            $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock',
            'articulo.nombre_comercial as articulo','tienda.nombre as tienda',
            'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista',
            'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion')
            ->where('articulo.estado', '=',1)
            ->orderBy('tienda_articulo.id', 'desc')
            ->paginate(100);
            //->get();
        }
        else{
            $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock',
            'articulo.nombre_comercial as articulo','tienda.nombre as tienda',
            'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista',
            'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->where('articulo.estado', '=',1)
            ->orderBy('tienda_articulo.id', 'desc')
            ->paginate(100);
            //->get();
        }
        return $tienda_articulo;
    }

    public function listarSinPaginateAjuste(Request $request){
        $buscar = $request->buscar; 
        $criterio = $request->criterio;    
        //dd($id_proveedor);  
        if ($buscar==''){

            $tienda_articulo = TiendaArticulo::join('lote','tienda_articulo.id','=','lote.id_producto')
            ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->select('lote.id as id_lote','tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock',
            'articulo.nombre_comercial as articulo','tienda.nombre as tienda',
            'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista',
            'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','lote.cantidad','lote.fecha_vecimiento','lote.lote','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion')
            ->where('lote.estado', '!=',0)
            ->orderBy('tienda_articulo.id', 'desc')
            ->paginate(100);
            //->get();
        }
        else{
            $tienda_articulo = TiendaArticulo::join('lote','tienda_articulo.id','=','lote.id_producto')
            ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->select('lote.id as id_lote','tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock',
            'articulo.nombre_comercial as articulo','tienda.nombre as tienda',
            'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista',
            'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','lote.cantidad','lote.fecha_vecimiento','lote.lote','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion')
            ->where('lote.estado', '!=',0)
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('tienda_articulo.id', 'desc')
            ->paginate(100);
            //->get();
        }
        return $tienda_articulo;
    }
  
    /*public function listarSinPaginateVenta(Request $request){
        $buscar = $request->buscar; 
        $criterio = $request->criterio;    
        $id_proveedor = $request->id_proveedor; 
        if($id_proveedor <= 0){   
        //dd($id_proveedor);  
        if ($buscar==''){
            // v_fecha
            $tienda_articulo = Lote::join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
            ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->select('lote.id','tienda_articulo.id as id_articulo','tienda_articulo.id_tienda',
            'articulo.nombre_comercial as articulo','articulo.nombre_generico',
            'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.precio_blister','articulo.precio_caja',
            'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','lote.cantidad as stock','lote.fecha_vecimiento',
            'articulo.descripcion','articulo.cod_proveedor','articulo.cantidad_blister','articulo.cantidad_caja','articulo.venta_presentacion',
            'articulo.ubicacion','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion','lote.lote') 
            ->where('lote.cantidad', '!=', 0)
            ->where('lote.estado', '!=', 0)
            ->orderBy('lote.fecha_vecimiento', 'asc')
            ->paginate(100);
            //->get();
        }
        else{
            $tienda_articulo = Lote::join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
            ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->select('lote.id','tienda_articulo.id as id_articulo','tienda_articulo.id_tienda',
            'articulo.nombre_comercial as articulo','articulo.nombre_generico',
            'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.precio_blister','articulo.precio_caja',
            'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','lote.cantidad as stock','lote.fecha_vecimiento',
            'articulo.descripcion','articulo.cod_proveedor','articulo.cantidad_blister','articulo.cantidad_caja','articulo.venta_presentacion',
            'articulo.ubicacion','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion','lote.lote')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->where('lote.cantidad', '!=', 0)
            ->where('lote.estado', '!=', 0)
            ->orderBy('lote.fecha_vecimiento', 'asc')
            ->paginate(100);
            //->get();
        }
    }else
    {
        $tienda_articulo = Lote::join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
            ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->select('lote.id','tienda_articulo.id as id_articulo','tienda_articulo.id_tienda',
            'articulo.nombre_comercial as articulo','articulo.nombre_generico',
            'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.precio_blister','articulo.precio_caja',
            'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','lote.cantidad as stock','lote.fecha_vecimiento',
            'articulo.descripcion','articulo.cod_proveedor','articulo.cantidad_blister','articulo.cantidad_caja','articulo.venta_presentacion',
            'articulo.ubicacion','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion','lote.lote')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->where('lote.cantidad', '!=', 0)
            ->where('lote.estado', '!=', 0)
            ->where('articulo.id_proveedor', '=', $id_proveedor)
            ->orderBy('lote.fecha_vecimiento', 'asc')
            ->paginate(100);
            //->get();
    }

        return $tienda_articulo;
    }*/

    public function listarSinPaginateVenta(Request $request)
    {
        // 1. Recibir parámetros
        $buscar = $request->buscar; 
        $criterio = $request->criterio;    
        $id_proveedor = $request->id_proveedor; 

        // 2. Construir la consulta base (Joins y Selects comunes)
        // Iniciamos la consulta sin ejecutarla aún (no ponemos ->get() ni ->paginate())
        $query = Lote::join('tienda_articulo', 'lote.id_producto', '=', 'tienda_articulo.id')
            ->join('articulo', 'tienda_articulo.id_articulo', '=', 'articulo.id')
            ->join('categoria', 'articulo.id_categoria', '=', 'categoria.id')
            ->join('proveedor', 'articulo.id_proveedor', '=', 'proveedor.id')
            ->join('unidad_medida', 'articulo.id_unidad', '=', 'unidad_medida.id')
            ->select(
                'lote.id',
                'tienda_articulo.id as id_articulo',
                'tienda_articulo.id_tienda',
                'articulo.nombre_comercial as articulo',
                'articulo.nombre_generico',
                'articulo.costo_compra',
                'articulo.costo_unitario',
                'articulo.costo_mayorista',
                'articulo.precio_blister',
                'articulo.precio_caja',
                'articulo.costo_preferencial',
                'articulo.id_categoria',
                'categoria.nombre as categoria',
                'lote.cantidad as stock',
                'lote.fecha_vecimiento',
                'articulo.descripcion',
                'articulo.cod_proveedor',
                'articulo.cantidad_blister',
                'articulo.cantidad_caja',
                'articulo.venta_presentacion',
                'articulo.ubicacion',
                'proveedor.nombre as laboratorio',
                'unidad_medida.nombre as presentacion',
                'lote.lote'
            )
            // Estas condiciones siempre se cumplen en todos tus casos originales
            ->where('lote.cantidad', '!=', 0)
            ->where('lote.estado', '!=', 0);

        // 3. Aplicar filtros dinámicos
        
        // Si se seleccionó un proveedor específico (id > 0)
        if ($id_proveedor > 0) {
            $query->where('articulo.id_proveedor', '=', $id_proveedor);
        }

        // Si hay texto en el buscador
        if (!empty($buscar)) {
            // Asumimos que $criterio es un campo válido (ej: 'articulo.nombre_comercial')
            $query->where($criterio, 'like', '%' . $buscar . '%');
        }

        // 4. Ordenar y devolver resultados paginados
        return $query->orderBy('lote.fecha_vecimiento', 'asc')
                    ->paginate(100);
    }

    public function listarSinPaginateInventario(Request $request){
        $buscar = $request->buscar; 
        $criterio = $request->criterio;    
        if ($buscar==''){

            $tienda_articulo = Lote::join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
            ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->select('lote.id','tienda_articulo.id as id_articulo','tienda_articulo.id_tienda',
            'articulo.nombre_comercial as articulo','articulo.nombre_generico',
            'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.precio_blister','articulo.precio_caja',
            'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','lote.cantidad as stock','lote.fecha_vecimiento',
            'articulo.descripcion','articulo.cod_proveedor','articulo.cantidad_blister','articulo.cantidad_caja','articulo.venta_presentacion',
            'articulo.ubicacion','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion','lote.lote') 
            //->where('lote.cantidad', '!=', 0)
            ->where('lote.estado', '!=', 0)
            ->orderBy('lote.fecha_vecimiento', 'asc')
            ->paginate(30);
            //->get();
        }
        else{
            if($criterio=="proveedor.nombre"){
                $tienda_articulo = Lote::join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
                ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
                ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
                ->select('lote.id','tienda_articulo.id as id_articulo','tienda_articulo.id_tienda',
                'articulo.nombre_comercial as articulo','articulo.nombre_generico',
                'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.precio_blister','articulo.precio_caja',
                'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','lote.cantidad as stock','lote.fecha_vecimiento',
                'articulo.descripcion','articulo.cod_proveedor','articulo.cantidad_blister','articulo.cantidad_caja','articulo.venta_presentacion',
                'articulo.ubicacion','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion','lote.lote')
                ->where($criterio,$buscar)
                //->where('lote.cantidad', '!=', 0)
                ->where('lote.estado', '!=', 0)
                ->orderBy('lote.fecha_vecimiento', 'asc')
                ->paginate(30);
                //->get();
            }else{
                $tienda_articulo = Lote::join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
                ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
                ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
                ->select('lote.id','tienda_articulo.id as id_articulo','tienda_articulo.id_tienda',
                'articulo.nombre_comercial as articulo','articulo.nombre_generico',
                'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.precio_blister','articulo.precio_caja',
                'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','lote.cantidad as stock','lote.fecha_vecimiento',
                'articulo.descripcion','articulo.cod_proveedor','articulo.cantidad_blister','articulo.cantidad_caja','articulo.venta_presentacion',
                'articulo.ubicacion','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion','lote.lote')
                ->where($criterio, 'like', '%'.$buscar.'%')
                //->where('lote.cantidad', '!=', 0)
                ->where('lote.estado', '!=', 0)
                ->orderBy('lote.fecha_vecimiento', 'asc')
                ->paginate(30);
                //->get();
            }
          
        }

        return $tienda_articulo;
    }

    public function selectProveedorB(Request $request){  
       // $id_articulo = $request->id_articulo;
        //dd($id);
        $obj = Proveedor::select('id','nombre as proveedor')
        //->where('articulo.id','=',$id_articulo)
        ->orderBy('proveedor.id','desc')->get(); 
        return $obj;
    }


    public function listarSinPaginateP(Request $request){
        $buscar = $request->buscar;     
        if ($buscar==''){

            $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.tipo_producto')
            ->where('tienda_articulo.id_tienda',3)
            ->orderBy('tienda_articulo.id', 'desc')->get();
        }
        else{
            $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.tipo_producto')
            ->where('tienda_articulo.id_tienda',3)
            ->where('articulo.nombre' , 'like', '%'.$buscar.'%')
            ->orderBy('tienda_articulo.id', 'desc')->get();
        }
        return $tienda_articulo;
    }

    //Listar de orden servicio

    public function listarOrdenProducto(Request $request){
        $buscar = $request->buscar;     
        if ($buscar==''){

            $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
            ->where('tienda_articulo.id_tienda',3)
            ->where('articulo.tipo_producto','Producto Venta')
            ->orderBy('tienda_articulo.id', 'desc')->get();
        }
        else{
            $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
            ->where('tienda_articulo.id_tienda',3)
            ->where('articulo.tipo_producto','Producto Venta')
            ->where('articulo.nombre' , 'like', '%'.$buscar.'%')
            ->orderBy('tienda_articulo.id', 'desc')->get();
        }
        return $tienda_articulo;
    }

    public function listarSinPaginate2(Request $request){
        $buscar = $request->buscar;     
        if ($buscar==''){

            $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
            ->where('tienda_articulo.id_tienda',3)
            ->orderBy('tienda_articulo.id', 'desc')->get();
        }
        else{
            $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
            ->where('tienda_articulo.id_tienda',3)
            ->where('articulo.nombre' , 'like', '%'.$buscar.'%')
            ->orderBy('tienda_articulo.id', 'desc')->get();
        }
        return $tienda_articulo;
    }

    public function listarOrdenServicio(Request $request){
        $buscar = $request->buscar;     
        if ($buscar==''){

            $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
            ->where('tienda_articulo.id_tienda',3)
            ->where('articulo.tipo_producto','Producto Servicio')
            ->orderBy('tienda_articulo.id', 'desc')->get();
        }
        else{
            $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
            ->where('tienda_articulo.id_tienda',3)
            ->where('articulo.tipo_producto','Producto Servicio')
            ->where('articulo.nombre' , 'like', '%'.$buscar.'%')
            ->orderBy('tienda_articulo.id', 'desc')->get();
        }
        return $tienda_articulo;
    }







    //TIENDAS

        //TIENDA 1  -- id_tienda = 2

        public function listarSinPaginate2tienda1(Request $request){
            $buscar = $request->buscar;     
            $criterio = $request->criterio;     
            if ($buscar==''){
    
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','tienda.nombre as tienda',
                'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
                ->where('tienda_articulo.id_tienda',1)
                ->where('articulo.tipo_producto','Producto Venta')
                // ->where('articulo.tipo','=','Venta')
                ->whereNotBetween('categoria.id', ['1','2'])
                ->where('articulo.estado', 1)
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            else{
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','tienda.nombre as tienda',
                'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
                ->where('articulo.tipo_producto','Producto Venta')
                ->where('tienda_articulo.id_tienda',1)
                ->whereNotBetween('categoria.id', ['1','2'])
                ->where('articulo.estado', 1)

                ->where($criterio , 'like', '%'.$buscar.'%')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            return $tienda_articulo;
        }

        public function listarSinPaginateVacuna(Request $request){
            $buscar = $request->buscar;     
            $criterio = $request->criterio;     
            if ($buscar==''){
    
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','tienda.nombre as tienda',
                'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
                ->where('tienda_articulo.id_tienda',1)
                ->where('articulo.tipo_producto','Producto Venta')
                ->where('articulo.estado', 1)
                ->where('categoria.nombre','=','Vacunas')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            else{
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','tienda.nombre as tienda',
                'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
                ->where('articulo.tipo_producto','Producto Venta')
                ->where('tienda_articulo.id_tienda',1)
                ->where('articulo.estado', 1)
                ->where('categoria.nombre','=','Vacunas')
                ->where($criterio , 'like', '%'.$buscar.'%')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            return $tienda_articulo;
        }
        public function listarSinPaginateVacuna2(Request $request){
            $buscar = $request->buscar;     
            $criterio = $request->criterio;     
            if ($buscar==''){
    
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','tienda.nombre as tienda',
                'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
                ->where('tienda_articulo.id_tienda',1)
                ->where('articulo.tipo_producto','Producto Venta')
                ->where('articulo.estado', 1)
                ->where('categoria.nombre','=','Antiparasitario')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            else{
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','tienda.nombre as tienda',
                'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
                ->where('articulo.tipo_producto','Producto Venta')
                ->where('tienda_articulo.id_tienda',1)
                ->where('articulo.estado', 1)
                ->where('categoria.nombre','=','Antiparasitario')
                ->where($criterio , 'like', '%'.$buscar.'%')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            return $tienda_articulo;
        }

        public function listarOrdenProductoTienda1(Request $request){
            $buscar = $request->buscar;  
            $criterio = $request->criterio;       
            if ($buscar==''){
    
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','categoria.nombre as categoria',
                'articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
                ->where('tienda_articulo.id_tienda',1)
                ->where('articulo.estado', 1)
                ->where('articulo.tipo_producto','Producto Venta')
                ->whereNotBetween('categoria.id', ['1','2'])
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            else{
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','categoria.nombre as categoria',
                'articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
                ->where('tienda_articulo.id_tienda',1)
                ->where('articulo.estado', 1)
                ->where('articulo.tipo_producto','Producto Venta')
                ->whereNotBetween('categoria.id', ['1','2'])
                ->where($criterio , 'like', '%'.$buscar.'%')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            return $tienda_articulo;
        }

        public function listarOrdenServicioTienda1(Request $request){
        $buscar = $request->buscar;     
        $criterio = $request->criterio;     
            $buscar = $request->buscar;     
            if ($buscar==''){
    
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','categoria.nombre as categoria',
                'articulo.nombre_comercial as articulo','articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
                ->where('tienda_articulo.id_tienda',1)
                ->where('articulo.tipo_producto','Producto Servicio')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            else{
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','categoria.nombre as categoria',
                'articulo.nombre_comercial as articulo','articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
                ->where('tienda_articulo.id_tienda',1)
                ->where('articulo.tipo_producto','Producto Servicio')
                ->where($criterio , 'like', '%'.$buscar.'%')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            return $tienda_articulo;
        }


        //TIENDA 2  -- id_tienda = 3

        public function listarSinPaginate2tienda2(Request $request){
            $buscar = $request->buscar;     
            if ($buscar==''){
    
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','articulo.marca','tienda.nombre as tienda',
                'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
                ->where('tienda_articulo.id_tienda',3)
                ->where('articulo.tipo_producto','Producto Venta')
                ->where('articulo.estado', 1)
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            else{
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','articulo.marca','tienda.nombre as tienda',
                'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
                ->where('articulo.tipo_producto','Producto Venta')
                ->where('tienda_articulo.id_tienda',3)
                ->where('articulo.estado', 1)
                ->where('articulo.nombre' , 'like', '%'.$buscar.'%')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            return $tienda_articulo;
        }

        public function listarOrdenProductoTienda2(Request $request){
            $buscar = $request->buscar;     
            if ($buscar==''){
    
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','categoria.nombre as categoria',
                'articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.marca')
                ->where('tienda_articulo.id_tienda',3)
                ->where('articulo.estado', 1)
                ->where('articulo.tipo_producto','Producto Venta')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            else{
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','categoria.nombre as categoria',
                'articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.marca')
                ->where('tienda_articulo.id_tienda',3)
                ->where('articulo.estado', 1)
                ->where('articulo.tipo_producto','Producto Venta')
                ->where('articulo.nombre' , 'like', '%'.$buscar.'%')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            return $tienda_articulo;
        }

        public function listarOrdenServicioTienda2(Request $request){
            $buscar = $request->buscar;     
        $buscar = $request->buscar;     
            $buscar = $request->buscar;     
            if ($buscar==''){
    
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','categoria.nombre as categoria',
                'articulo.nombre as articulo','articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
                ->where('tienda_articulo.id_tienda',3)
                ->where('articulo.tipo_producto','Producto Servicio')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            else{
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','categoria.nombre as categoria',
                'articulo.nombre as articulo','articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
                ->where('tienda_articulo.id_tienda',3)
                ->where('articulo.tipo_producto','Producto Servicio')
                ->where('articulo.nombre' , 'like', '%'.$buscar.'%')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            return $tienda_articulo;
        }


        //TIENDA 3  -- id_tienda = 4

        public function listarSinPaginate2tienda3(Request $request){
            $buscar = $request->buscar;     
            if ($buscar==''){
    
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','articulo.marca','tienda.nombre as tienda',
                'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
                ->where('tienda_articulo.id_tienda',4)
                ->where('articulo.tipo_producto','Producto Venta')
                ->where('articulo.estado', 1)
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            else{
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','articulo.marca','tienda.nombre as tienda',
                'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
                ->where('articulo.tipo_producto','Producto Venta')
                ->where('tienda_articulo.id_tienda',4)
                ->where('articulo.estado', 1)
                ->where('articulo.nombre' , 'like', '%'.$buscar.'%')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            return $tienda_articulo;
        }

        public function listarOrdenProductoTienda3(Request $request){
            $buscar = $request->buscar;     
            if ($buscar==''){
    
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','categoria.nombre as categoria',
                'articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.marca')
                ->where('tienda_articulo.id_tienda',4)
                ->where('articulo.estado', 1)
                ->where('articulo.tipo_producto','Producto Venta')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            else{
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre as articulo','categoria.nombre as categoria',
                'articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.marca')
                ->where('tienda_articulo.id_tienda',4)
                ->where('articulo.estado', 1)
                ->where('articulo.tipo_producto','Producto Venta')
                ->where('articulo.nombre' , 'like', '%'.$buscar.'%')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            return $tienda_articulo;
        }

        public function listarOrdenServicioTienda3(Request $request){
            $buscar = $request->buscar;     
        $buscar = $request->buscar;     
            $buscar = $request->buscar;     
            if ($buscar==''){
    
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','categoria.nombre as categoria',
                'articulo.nombre as articulo','articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
                ->where('tienda_articulo.id_tienda',4)
                ->where('articulo.tipo_producto','Producto Servicio')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            else{
                $tienda_articulo = TiendaArticulo::join('articulo','tienda_articulo.id_articulo','=','articulo.id')
                ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
                ->join('categoria','articulo.id_categoria','=','categoria.id')
                ->select('tienda_articulo.id','tienda_articulo.id_articulo','tienda_articulo.id_tienda','tienda_articulo.stock','categoria.nombre as categoria',
                'articulo.nombre as articulo','articulo.tipo_producto','tienda.nombre as tienda','articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial')
                ->where('tienda_articulo.id_tienda',4)
                ->where('articulo.tipo_producto','Producto Servicio')
                ->where('articulo.nombre' , 'like', '%'.$buscar.'%')
                ->orderBy('tienda_articulo.id', 'desc')->get();
            }
            return $tienda_articulo;
        }
}

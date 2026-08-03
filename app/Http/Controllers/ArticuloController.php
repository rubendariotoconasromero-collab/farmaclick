<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use App\Models\Lote;
use App\Models\TiendaArticulo;
use App\Http\Requests\ArticuloRequest;
use App\Models\Control;
use DB;
use DateTime;

class ArticuloController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->select('articulo.id','articulo.cod_proveedor','articulo.cod_barra','articulo.nombre_comercial','articulo.nombre_generico','articulo.costo_unitario','articulo.costo_compra'
            ,'articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.ubicacion','articulo.descripcion','articulo.composicion','articulo.id_categoria','articulo.id_unidad','articulo.id_proveedor',
            'categoria.nombre as categoria','unidad_medida.nombre as unidad','proveedor.nombre as proveedor','articulo.estado','articulo.precio_blister','articulo.precio_caja','articulo.cod_sistema','articulo.cantidad_caja','articulo.costo_compra_caja','articulo.venta_presentacion','articulo.cantidad_blister','articulo.id_marca','articulo.psicotropico','articulo.refrigerado','articulo.cantidad_blister','cantidad_caja')
            ->where('categoria.estado', 1)
            ->orderBy('articulo.id','desc')->paginate(15);
        }
        else{
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->select('articulo.id','articulo.cod_proveedor','articulo.cod_barra','articulo.nombre_comercial','articulo.nombre_generico','articulo.costo_unitario','articulo.costo_compra'
            ,'articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.ubicacion','articulo.descripcion','articulo.composicion','articulo.id_categoria','articulo.id_unidad','articulo.id_proveedor',
            'categoria.nombre as categoria','unidad_medida.nombre as unidad','proveedor.nombre as proveedor','articulo.estado','articulo.precio_blister','articulo.precio_caja','articulo.cod_sistema','articulo.cantidad_caja','articulo.costo_compra_caja','articulo.venta_presentacion','articulo.cantidad_blister','articulo.id_marca','articulo.psicotropico','articulo.refrigerado','articulo.cantidad_blister','cantidad_caja')
            ->where('categoria.estado', 1)
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('articulo.id','desc')->paginate(15);            
        }
        return $obj;
    }
    public function indexFarmacia(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->select('articulo.id','articulo.cod_proveedor','articulo.cod_barra','articulo.nombre_comercial','articulo.nombre_generico','articulo.costo_unitario','articulo.costo_compra'
            ,'articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.ubicacion','articulo.descripcion','articulo.composicion','articulo.id_categoria','articulo.id_unidad','articulo.id_proveedor',
            'categoria.nombre as categoria','unidad_medida.nombre as unidad','proveedor.nombre as proveedor','articulo.estado','articulo.precio_blister','articulo.precio_caja','articulo.cod_sistema','articulo.cantidad_caja','articulo.costo_compra_caja','articulo.venta_presentacion','articulo.cantidad_blister','articulo.id_marca','articulo.psicotropico','articulo.refrigerado')
            ->where('categoria.estado', 1)
            ->orderBy('articulo.id','desc')->paginate(15);
        }
        else{
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->select('articulo.id','articulo.cod_proveedor','articulo.cod_barra','articulo.nombre_comercial','articulo.nombre_generico','articulo.costo_unitario','articulo.costo_compra'
            ,'articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.ubicacion','articulo.descripcion','articulo.composicion','articulo.id_categoria','articulo.id_unidad','articulo.id_proveedor',
            'categoria.nombre as categoria','unidad_medida.nombre as unidad','proveedor.nombre as proveedor','articulo.estado','articulo.precio_blister','articulo.precio_caja','articulo.cod_sistema','articulo.cantidad_caja','articulo.costo_compra_caja','articulo.venta_presentacion','articulo.cantidad_blister','articulo.id_marca','articulo.psicotropico','articulo.refrigerado')
            ->where('categoria.estado', 1)
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('articulo.id','desc')->paginate(15);            
        }
        return $obj;
    }
    public function listarSinPaginate(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
             ->join('tienda_articulo','articulo.id','=','tienda_articulo.id')
            ->select('articulo.id','articulo.cod_producto','articulo.cod_proveedor','articulo.cod_ean','articulo.nombre_comercial','articulo.marca','articulo.costo_compra'
            ,'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.tipo_producto','articulo.descripcion','articulo.id_categoria',
            'categoria.nombre as categoria','articulo.estado','tienda_articulo.id_articulo')
            ->orderBy('articulo.id','desc')->get();
        }
        else{
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('tienda_articulo','articulo.id','=','tienda_articulo.id')
            ->select('articulo.id','articulo.cod_producto','articulo.cod_proveedor','articulo.cod_ean','articulo.nombre_comercial','articulo.marca','articulo.costo_compra'
            ,'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.tipo_producto','articulo.descripcion','articulo.id_categoria',
            'categoria.nombre as categoria','articulo.estado','tienda_articulo.id_articulo')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('articulo.id','desc')->get();            
        }
        return $obj;
    }

    public function index2(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
            ->select('articulo.id','articulo.cod_producto','articulo.cod_proveedor','articulo.nombre_comercial','articulo.nombre_generico','articulo.costo_unitario'
            ,'articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.tipo_producto','articulo.descripcion','articulo.id_categoria',
            'categoria.nombre as categoria','articulo.estado','categoria.nombre as categoria')
            ->where('categoria.estado', 1)
            ->orderBy('articulo.id','desc')->paginate(15);
        }
        else{
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
            ->select('articulo.id','articulo.cod_producto','articulo.cod_proveedor','articulo.nombre_comercial','articulo.nombre_generico','articulo.costo_unitario'
            ,'articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.tipo_producto','articulo.descripcion','articulo.id_categoria',
            'categoria.nombre as categoria','articulo.estado','categoria.nombre as categoria')
            ->where('categoria.estado', 1)
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('articulo.id','desc')->paginate(15);            
        }
        return $obj;
    }

    // public function listarSinPaginate2(Request $request){
    //     $buscar = $request->buscar;
    //     $criterio = $request->criterio;
    //     if($buscar==''){
    //         $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
    //          ->join('tienda_articulo','articulo.id','=','tienda_articulo.id')
    //         ->select('articulo.id','articulo.cod_producto','articulo.cod_proveedor','articulo.cod_ean','articulo.nombre','articulo.marca','articulo.costo_compra'
    //         ,'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.tipo_producto','articulo.descripcion','articulo.id_categoria',
    //         'categoria.nombre as categoria','articulo.estado','tienda_articulo.id_articulo','tienda_articulo.stock')
    //         ->orderBy('articulo.id','desc')->get();
    //     }
    //     else{
    //         $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
    //         ->join('tienda_articulo','articulo.id','=','tienda_articulo.id')
    //         ->select('articulo.id','articulo.cod_producto','articulo.cod_proveedor','articulo.cod_ean','articulo.nombre','articulo.marca','articulo.costo_compra'
    //         ,'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.tipo_producto','articulo.descripcion','articulo.id_categoria',
    //         'categoria.nombre as categoria','articulo.estado','tienda_articulo.id_articulo','tienda_articulo.stock')
    //         ->where($criterio, 'like', '%'.$buscar.'%')
    //         ->orderBy('articulo.id','desc')->get();            
    //     }
    //     return $obj;
    // }

    public function listarSinPaginate2(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
             ->join('tienda_articulo','articulo.id','=','tienda_articulo.id_articulo')
            ->select('articulo.id as id_articulo','articulo.cod_producto','articulo.cod_proveedor','articulo.cod_ean','articulo.nombre_comercial as articulo','articulo.marca','articulo.costo_compra'
            ,'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.tipo_producto','articulo.descripcion','articulo.id_categoria',
            'categoria.nombre as categoria','articulo.estado','tienda_articulo.id_articulo','tienda_articulo.stock','tienda_articulo.id as id_tienda_articulo')
            ->where('tienda_articulo.id_tienda','=',1 )
            ->orderBy('articulo.id','desc')->get();
        }
        else{
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('tienda_articulo','articulo.id','=','tienda_articulo.id_articulo')
            ->select('articulo.id as id_articulo','articulo.cod_producto','articulo.cod_proveedor','articulo.cod_ean','articulo.nombre_comercial as articulo','articulo.marca','articulo.costo_compra'
            ,'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.tipo_producto','articulo.descripcion','articulo.id_categoria',
            'categoria.nombre as categoria','articulo.estado','tienda_articulo.id_articulo','tienda_articulo.stock','tienda_articulo.id as id_tienda_articulo')
            ->where('tienda_articulo.id_tienda','!=',1)
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('articulo.id','desc')->get();            
        }
        return $obj;
    }

    public function listarSinPaginate2Producto(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
             ->join('tienda_articulo','articulo.id','=','tienda_articulo.id_articulo')
            ->select('articulo.id as id_articulo','articulo.cod_producto','articulo.cod_proveedor','articulo.cod_ean','articulo.nombre','articulo.marca','articulo.costo_compra'
            ,'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.tipo_producto','articulo.descripcion','articulo.id_categoria',
            'categoria.nombre as categoria','articulo.estado','tienda_articulo.id_articulo','tienda_articulo.stock','tienda_articulo.id as id_tienda_articulo')
            ->where('tienda_articulo.id_tienda','=',1 )
            ->where('articulo.tipo_producto','=','Producto Venta')
            ->orderBy('articulo.id','desc')->get();
        }
        else{
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('tienda_articulo','articulo.id','=','tienda_articulo.id_articulo')
            ->select('articulo.id as id_articulo','articulo.cod_producto','articulo.cod_proveedor','articulo.cod_ean','articulo.nombre','articulo.marca','articulo.costo_compra'
            ,'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.tipo_producto','articulo.descripcion','articulo.id_categoria',
            'categoria.nombre as categoria','articulo.estado','tienda_articulo.id_articulo','tienda_articulo.stock','tienda_articulo.id as id_tienda_articulo')
            ->where('tienda_articulo.id_tienda','=',1)
            ->where('articulo.tipo_producto','=','Producto Venta')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('articulo.id','desc')->get();            
        }
        return $obj;
    }

    public function listarSinPaginate2Servicio(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
             ->join('tienda_articulo','articulo.id','=','tienda_articulo.id_articulo')
            ->select('articulo.id as id_articulo','articulo.cod_producto','articulo.cod_proveedor','articulo.cod_ean','articulo.nombre','articulo.marca','articulo.costo_compra'
            ,'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.tipo_producto','articulo.descripcion','articulo.id_categoria',
            'categoria.nombre as categoria','articulo.estado','tienda_articulo.id_articulo','tienda_articulo.stock','tienda_articulo.id as id_tienda_articulo')
            ->where('tienda_articulo.id_tienda','=',1 )
            ->where('articulo.tipo_producto','=','Producto Servicio')
            ->orderBy('articulo.id','desc')->get();
        }
        else{
            $obj= Articulo::join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('tienda_articulo','articulo.id','=','tienda_articulo.id_articulo')
            ->select('articulo.id as id_articulo','articulo.cod_producto','articulo.cod_proveedor','articulo.cod_ean','articulo.nombre','articulo.marca','articulo.costo_compra'
            ,'articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.stock_minimo','articulo.tipo_producto','articulo.descripcion','articulo.id_categoria',
            'categoria.nombre as categoria','articulo.estado','tienda_articulo.id_articulo','tienda_articulo.stock','tienda_articulo.id as id_tienda_articulo')
            ->where('tienda_articulo.id_tienda','=',1)
            ->where('articulo.tipo_producto','=','Producto Servicio')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('articulo.id','desc')->get();            
        }
        return $obj;
    }
    public function ultimo_id_articulo(){
       
        $id_historia = DB::select('SELECT max(id) as id FROM  articulo');
        return $id_historia;
    }

    public function guardar(ArticuloRequest $request){
        try{
            if (Articulo::where('nombre_comercial', $request->nombre_comercial)
                ->where('id_categoria', $request->id_categoria)->first()){
                return ['error'=>0];
            }
            else{
                $correlativo = DB::table('articulo')->select()->max('id');

                DB::beginTransaction();
                $obj = new Articulo();
                $obj->cod_sistema=$correlativo+1;
                $obj->cod_proveedor=$request->cod_proveedor;
                // $obj->cod_veterinaria= $request->cod_veterinaria = 'CV-'.strval($correlativo +1);
                $obj->cod_barra=$request->cod_barra;
                $obj->nombre_comercial=$request->nombre_comercial;
                $obj->nombre_generico=$request->nombre_generico;
                $obj->costo_compra=$request->costo_compra;
                $obj->costo_compra_caja=$request->costo_compra_caja;
                $obj->costo_unitario=$request->costo_unitario;
                $obj->costo_mayorista=0;
                $obj->costo_preferencial=0;
                $obj->stock_minimo=$request->stock_minimo;
                $obj->ubicacion=$request->ubicacion;
                $obj->descripcion=$request->descripcion;
                $obj->composicion=$request->composicion;
                $obj->venta_presentacion=$request->venta_presentacion;
                $obj->cantidad_caja=$request->cantidad_caja;
                $obj->cantidad_blister=$request->cantidad_blister;
                $obj->precio_caja=$request->precio_caja;
                $obj->precio_blister=$request->precio_blister;
                $obj->psicotropico=$request->psicotropico;
                $obj->refrigerado=$request->refrigerado;
                $obj->estado=$request->estado;
                //$obj->id_categoria=$request->id_categoria;
                $obj->id_categoria=$request->id_categoria ? $request->id_categoria : 1;
                $obj->id_marca=$request->id_marca ? $request->id_marca : 1;

                //$obj->id_marca=$request->id_marca;
                $obj->id_unidad=$request->id_unidad;
                $obj->id_proveedor=$request->id_proveedor;
                //DD($obj);
                $obj->save();

                //AGREGAR CODIGO DE VENTA

                if($request->tipo_producto == 'Producto Venta') {
                    $correlativo = 0;
                    $objdate = new DateTime();
                    $fechaactual= $objdate->format('Y-m-d');
                    $year = $objdate->format('y');
                    $correlativo = $this->correlativoControlProducto();
                    $control = new control();
                    $control->tabla = "articulo producto";
                    $control->id_tabla = $obj->id;
                    $control->codigo = $request->codigo = 'P-'.strval($correlativo + 1);
                    $control->fecha = $fechaactual;
                    $control->save();
                } else {
                    $correlativo = 0;
                    $objdate = new DateTime();
                    $fechaactual= $objdate->format('Y-m-d');
                    $year = $objdate->format('y');
                    $correlativo = $this->correlativoControlServicio();
                    $control = new control();
                    $control->tabla = $request->tabla = "articulo servicio";
                    $control->id_tabla = $obj->id;
                    $control->codigo = $request->codigo = 'S-'.strval($correlativo + 1);
                    $control->fecha = $fechaactual;
                    $control->save();
                }

           }
            $Almacen = new TiendaArticulo();
            $Almacen->id_articulo=$obj->id;
            $Almacen->id_tienda=1;
            $Almacen->stock=0;
            $Almacen->save();

            //dd($Almacen->id);
            // $lote = new Lote();
            // $lote->id_producto=$Almacen->id;
            // $lote->fecha_vecimiento=$request->fecha_vencimiento;
            // $lote->cantidad=0;
            // $lote->estado=1;
            // $lote->save();  

            if($request->tipo_producto == 'Producto Venta') {
                
                $datos = [
                    'tabla' => 'articulo',
                    'codigo_tabla' => $obj->id,
                    'transaccion' => 'guardar venta',
                ];
                $this->guardarBitacora($datos);
    
                $datos = [
                    'tabla' => 'almacen',
                    'codigo_tabla' => $Almacen->id,
                    'transaccion' => 'guardar venta',
                ];
                $this->guardarBitacora($datos);

            } else {

                $datos = [
                    'tabla' => 'articulo',
                    'codigo_tabla' => $obj->id,
                    'transaccion' => 'guardar servicio',
                ];
                $this->guardarBitacora($datos);
    
                $datos = [
                    'tabla' => 'almacen',
                    'codigo_tabla' => $Almacen->id,
                    'transaccion' => 'guardar servicio',
                ];
                $this->guardarBitacora($datos);

            }

            

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function guardarServicio(Request $request){
        try{
            if (Articulo::where('nombre_comercial', $request->nombre_comercial)
                ->where('id_categoria', $request->id_categoria)->first()){
                return ['error'=>0];
            }
            else{
                $correlativo = DB::table('articulo')->select()->max('id');

                DB::beginTransaction();
                $obj = new Articulo();
                $obj->cod_producto=$correlativo+1;
                $obj->nombre_comercial=$request->nombre_comercial;
                $obj->nombre_generico=$request->nombre_comercial;
                $obj->costo_compra=$request->costo_compra;
                $obj->costo_unitario=$request->costo_unitario;
                $obj->costo_mayorista=0;
                $obj->costo_preferencial=0;
                $obj->stock_minimo=$request->stock_minimo;
                $obj->descripcion=$request->descripcion;
                $obj->tipo=$request->tipo;
                $obj->tipo_producto=$request->tipo_producto;
                $obj->fecha_vencimiento=$request->fecha_vencimiento;
                $obj->estado=$request->estado;
                $obj->id_categoria=$request->id_categoria;
                //DD($obj);
                $obj->save();

                //AGREGAR CODIGO DE VENTA

                if($request->tipo_producto == 'Producto Venta') {
                    $correlativo = 0;
                    $objdate = new DateTime();
                    $fechaactual= $objdate->format('Y-m-d');
                    $year = $objdate->format('y');
                    $correlativo = $this->correlativoControlProducto();
                    $control = new control();
                    $control->tabla = "articulo producto";
                    $control->id_tabla = $obj->id;
                    $control->codigo = $request->codigo = 'P-'.strval($correlativo + 1);
                    $control->fecha = $fechaactual;
                    $control->save();
                } else {
                    $correlativo = 0;
                    $objdate = new DateTime();
                    $fechaactual= $objdate->format('Y-m-d');
                    $year = $objdate->format('y');
                    $correlativo = $this->correlativoControlServicio();
                    $control = new control();
                    $control->tabla = $request->tabla = "articulo servicio";
                    $control->id_tabla = $obj->id;
                    $control->codigo = $request->codigo = 'S-'.strval($correlativo + 1);
                    $control->fecha = $fechaactual;
                    $control->save();
                }

            }
            $Almacen = new TiendaArticulo();
            $Almacen->id_articulo=$obj->id;
            $Almacen->id_tienda=1;
            $Almacen->stock=0;
            $Almacen->save();

            if($request->tipo_producto == 'Producto Venta') {
                
                $datos = [
                    'tabla' => 'articulo',
                    'codigo_tabla' => $obj->id,
                    'transaccion' => 'guardar venta',
                ];
                $this->guardarBitacora($datos);
    
                $datos = [
                    'tabla' => 'almacen',
                    'codigo_tabla' => $Almacen->id,
                    'transaccion' => 'guardar venta',
                ];
                $this->guardarBitacora($datos);

            } else {

                $datos = [
                    'tabla' => 'articulo',
                    'codigo_tabla' => $obj->id,
                    'transaccion' => 'guardar servicio',
                ];
                $this->guardarBitacora($datos);
    
                $datos = [
                    'tabla' => 'almacen',
                    'codigo_tabla' => $Almacen->id,
                    'transaccion' => 'guardar servicio',
                ];
                $this->guardarBitacora($datos);

            }

            

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function modificar(ArticuloRequest $request){
        // if (Articulo::where('nombre_comercial', $request->nombre_comercial)
        //     ->where('id_categoria', $request->id_categoria)
        //     ->where('id','!=', $request->id)
        //     ->first()){
        //     return ['error'=>0];
        // } else {
            $obj= Articulo::findOrFail($request->id);
            $obj->cod_proveedor=$request->cod_proveedor;
            // $obj->cod_veterinaria= $request->cod_veterinaria = 'CV-'.strval($correlativo +1);
            $obj->cod_barra=$request->cod_barra;
            $obj->nombre_comercial=$request->nombre_comercial;
            $obj->nombre_generico=$request->nombre_generico;
            $obj->costo_compra=$request->costo_compra;
            $obj->costo_compra_caja=$request->costo_compra_caja;
            $obj->costo_unitario=$request->costo_unitario;
            $obj->costo_mayorista=0;
            $obj->costo_preferencial=0;
            $obj->stock_minimo=$request->stock_minimo;
            $obj->ubicacion=$request->ubicacion;
            $obj->descripcion=$request->descripcion;
            $obj->composicion=$request->composicion;
            $obj->venta_presentacion=$request->venta_presentacion;
            $obj->cantidad_caja=$request->cantidad_caja;
            $obj->cantidad_blister=$request->cantidad_blister;
            $obj->precio_caja=$request->precio_caja;
            $obj->precio_blister=$request->precio_blister;
            $obj->psicotropico=$request->psicotropico;
            $obj->refrigerado=$request->refrigerado;
            $obj->estado=$request->estado;
            $obj->id_categoria=$request->id_categoria;
            $obj->id_marca=$request->id_marca;
            $obj->id_unidad=$request->id_unidad;
            $obj->id_proveedor=$request->id_proveedor;
            $obj->save();

            if($request->tipo_producto == 'Producto Venta') {
                    
                $datos = [
                    'tabla' => 'articulo',
                    'codigo_tabla' => $obj->id,
                    'transaccion' => 'modificar venta',
                ];
                $this->guardarBitacora($datos);

            } else {

                $datos = [
                    'tabla' => 'articulo',
                    'codigo_tabla' => $obj->id,
                    'transaccion' => 'modificar servicio',
                ];
                $this->guardarBitacora($datos);

            }
        // }
    }
    public function modificarServicio(ArticuloRequest $request){
        if (Articulo::where('nombre_comercial', $request->nombre_comercial)
            ->where('id_categoria', $request->id_categoria)
            ->where('id','!=', $request->id)
            ->first()){
            return ['error'=>0];
        } else {
            $obj= Articulo::findOrFail($request->id);
            $obj->nombre_comercial=$request->nombre_comercial;
            $obj->nombre_generico=$request->nombre_generico;
            $obj->costo_compra=$request->costo_compra;
            $obj->costo_unitario=$request->costo_unitario;
            $obj->costo_mayorista=0;
            $obj->costo_preferencial=0;
            $obj->stock_minimo=$request->stock_minimo;
            $obj->descripcion=$request->descripcion;
            $obj->tipo=$request->tipo;
            $obj->estado=$request->estado;
            $obj->id_categoria=$request->id_categoria;
            $obj->save();

            if($request->tipo_producto == 'Producto Venta') {
                    
                $datos = [
                    'tabla' => 'articulo',
                    'codigo_tabla' => $obj->id,
                    'transaccion' => 'modificar venta',
                ];
                $this->guardarBitacora($datos);

            } else {

                $datos = [
                    'tabla' => 'articulo',
                    'codigo_tabla' => $obj->id,
                    'transaccion' => 'modificar servicio',
                ];
                $this->guardarBitacora($datos);

            }
        }
    }
    public function desactivar(Request $request){
        $obj = Articulo::findOrFail($request->id);
        $obj->estado = '0';
        $obj->save();

        $id = $request->id;
        //dd($id);
        $affected = DB::table('lote')
        ->where('id_producto', $id)
        ->update(['estado' => 0]);  

        $datos = [
            'tabla' => 'articulo',
            'codigo_tabla' => $obj->id,
            'transaccion' => 'desactivar',
        ];
        $this->guardarBitacora($datos);
    }

    public function activar(Request $request){
        $obj = Articulo::findOrFail($request->id);
        $obj->estado = '1';
        $obj->save();

        $datos = [
            'tabla' => 'articulo',
            'codigo_tabla' => $obj->id,
            'transaccion' => 'activar',
        ];
        $this->guardarBitacora($datos);
    }
    public function cantidadRegistros(){
        $cantidad = DB::table('articulo')->count();
        $data=['nro' =>$cantidad];
        return $data;
    }

    public function cantidadRegistrosProductos(){
        $cantidad = DB::table('articulo')
        ->count();
        $data=['nro' =>$cantidad];
        return $data;
    }

    public function cantidadRegistrosServicios(){
        $cantidad = DB::table('articulo')
        ->where('articulo.tipo_producto','=','Producto Servicio')
        ->count();
        $data=['nro' =>$cantidad];
        return $data;
    }

    private function correlativoControlProducto(){
        $mayor = DB::table('control')->where('control.tabla','=','articulo producto')->count();
         return $mayor;
    }

    private function correlativoControlServicio(){
        $mayor = DB::table('control')->where('control.tabla','=','articulo servicio')->count();
         return $mayor;
    }

    public function show(){
        $hoy = date('Y-m-d');   
        //$sql = "UPDATE paquetes SET estado = IF(fecha_final<'$hoy','0','1')";
        $affected = DB::table('paquetes')
        ->where('fecha_final','<', $hoy)
        ->update(['estado' => '0']);
        //return $sql;
    }

    public function detalleLote(Request $request){
        $datos = $request->validate([
            'buscar' => 'required|integer|exists:tienda_articulo,id',
        ]);

        return Lote::select('id', 'cantidad', 'fecha_vecimiento', 'id_producto', 'lote')
            ->where('id_producto', $datos['buscar'])
            ->where('estado', '!=', 0)
            ->orderBy('fecha_vecimiento')
            ->get();
    }
    public function detalleLotePrincipal(Request $request){

        //dd($buscar);
        $obj=DB::select("SELECT l.id,l.cantidad, l.fecha_vecimiento,l.id_producto
        FROM lote l 
        WHERE l.estado!=0" );

        return $obj;
    }
    public function contador(Request $request){
        $id = $request->id;

     $consulta = DB::select('CALL stock(?)', [$id]);    

     }
     public function selectUnitario(Request $request){  
        $id_articulo = $request->id_articulo;
        //dd($id);
        $obj = Articulo::select('articulo.id','articulo.costo_unitario','articulo.venta_presentacion')
        ->where('articulo.id','=',$id_articulo)
        ->orderBy('articulo.id','desc')->get(); 
        return $obj;
    }
    public function selectArticulo(){  
        $obj = Articulo::select('id', 'nombre_comercial')->where('articulo.estado','=',1)->get(); 
        return $obj;
    }
    // public function selectPaciente2(Request $request){  
    //     $id_cliente = $request->id_cliente;
    //     //dd($id_cliente);
    //     $obj = Paciente::join('animal','paciente.id_animal','=','animal.id')
    //     ->select('paciente.id', 'paciente.nombre', 'paciente.id_animal','paciente.edad','animal.nombre as animal','paciente.id_cliente','paciente.peso','paciente.especie','paciente.color','paciente.sexo','paciente.raza')
    //     ->where('paciente.id_cliente','=',$id_cliente)
    //     ->orderBy('paciente.id','desc')->get(); 
    //     return $obj;
    // }
}

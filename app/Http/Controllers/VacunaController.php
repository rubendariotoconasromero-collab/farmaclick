<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetalleVacuna;
use DB;

class VacunaController extends BitacoraController
{
    public function guardar(Request $request){
        try{
                DB::beginTransaction();
                
                $eliminar = DetalleVacuna::where('detalle_vacuna.id_animal','=',$request->id_animal);
                $eliminar->delete(); 
               
                $detalles = $request->detalle;
                $costo_pago = $request->costo_pago;

                foreach($detalles as $ep=>$det){

                        $obj = new DetalleVacuna();
                        $obj->id_animal= $request->id_animal;
                        $obj->id_producto= $det['id_tienda_articulo'];
                        $obj->tipo_vacuna= $det['categoria'];
                        $obj->save();


                        $datos = [
                            'tabla' => 'detalle_vacuna',
                            'codigo_tabla' => $obj->id,
                            'transaccion' => 'guardar detalle_vacuna',
                        ];
                        $this->guardarBitacora($datos);          
  
                }

 
                
            
            
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function detalleVacuna(Request $request){
        
        //dd($request->id);
        $id=$request->id; 
        $obj= detalleVacuna::join('tienda_articulo','detalle_vacuna.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('categoria','articulo.id_categoria','=','categoria.id')
        ->select('detalle_vacuna.id_producto as id','detalle_vacuna.id_animal','articulo.nombre_comercial as articulo','detalle_vacuna.id_producto as id_tienda_articulo'
        ,'detalle_vacuna.id_producto as id_articulo','categoria.nombre as categoria')
        ->where('detalle_vacuna.id_animal','=',$id)
        ->orderBy('articulo.nombre_comercial','asc')
        ->get();
        return $obj;
    }
    public function listarSinPaginateControlVacuna(Request $request){
        $buscar = $request->buscar;     
        $id_animal = $request->id_animal;     
        $criterio = $request->criterio;     
        if ($buscar==''){

            $detalle_vacuna = DetalleVacuna::join('tienda_articulo','detalle_vacuna.id_producto','=','tienda_articulo.id')
            ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->select('detalle_vacuna.id_producto as id','tienda_articulo.id_articulo','tienda_articulo.id as id','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','tienda.nombre as tienda',
            'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
            ->where('detalle_vacuna.tipo_vacuna','Vacunas')
            ->where('detalle_vacuna.id_animal','=',$id_animal)
            ->orderBy('tienda_articulo.id', 'desc')->get();
        }
        else{
            $detalle_vacuna = DetalleVacuna::join('tienda_articulo','detalle_vacuna.id_producto','=','tienda_articulo.id')
            ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->select('detalle_vacuna.id_producto as id','tienda_articulo.id_articulo','tienda_articulo.id as id','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','tienda.nombre as tienda',
            'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
            ->where('detalle_vacuna.tipo_vacuna','Vacunas')
            ->where('detalle_vacuna.id_animal','=',$id_animal)
            ->where($criterio , 'like', '%'.$buscar.'%')
            ->orderBy('tienda_articulo.id', 'desc')->get();
        }
        return $detalle_vacuna;
    }
    public function listarSinPaginateAntiparasitario(Request $request){
        $buscar = $request->buscar;     
        $id_animal = $request->id_animal;    
        //dd($id_animal); 
        $criterio = $request->criterio;     
        if ($buscar==''){

            $detalle_vacuna = DetalleVacuna::join('tienda_articulo','detalle_vacuna.id_producto','=','tienda_articulo.id')
            ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->select('detalle_vacuna.id_producto as id','tienda_articulo.id_articulo','tienda_articulo.id as id','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','tienda.nombre as tienda',
            'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
            ->where('detalle_vacuna.tipo_vacuna','Antiparasitario')
            ->where('detalle_vacuna.id_animal','=',$id_animal)
            ->orderBy('tienda_articulo.id', 'desc')->get();
        }
        else{
            $detalle_vacuna = DetalleVacuna::join('tienda_articulo','detalle_vacuna.id_producto','=','tienda_articulo.id')
            ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->select('detalle_vacuna.id_producto as id','tienda_articulo.id_articulo','tienda_articulo.id as id','tienda_articulo.id_tienda','tienda_articulo.stock','articulo.nombre_comercial as articulo','tienda.nombre as tienda',
            'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.costo_preferencial','articulo.estado','articulo.id_categoria','categoria.nombre as categoria')
            ->where('detalle_vacuna.tipo_vacuna','Antiparasitario')
            ->where('detalle_vacuna.id_animal','=',$id_animal)
            ->where($criterio , 'like', '%'.$buscar.'%')
            ->orderBy('tienda_articulo.id', 'desc')->get();
        }
        return $detalle_vacuna;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnidadMedida;
use App\Http\Requests\UnidadMedidaRequest;
use DB;

class UnidadMedidaController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;       
        if ($buscar==''){
            $unidad_medida = UnidadMedida::orderBy('unidad_medida.id', 'desc')->paginate(15);
        }
        else{
            $unidad_medida = UnidadMedida::where('unidad_medida.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('unidad_medida.id', 'desc')->paginate(15);
        }
        return $unidad_medida;
    }

    public function guardar(UnidadMedidaRequest $request){
        if (UnidadMedida::where('nombre', $request->nombre)->first()){
            return ['error'=>0];
        }
        else{
        $unidad= new UnidadMedida();
        $unidad->nombre=$request->nombre;
        $unidad->abreviatura=$request->abreviatura;
        $unidad->estado=$request->estado;
        $unidad->save();
        }
        $datos = [
            'tabla' => 'unidad_medida',
            'codigo_tabla' => $unidad->id,
            'transaccion' => 'guardar',
        ];
        $this->guardarBitacora($datos);
    }

    public function modificar(Request $request){
        $unidad_medida= UnidadMedida::findOrFail($request->id);
        $unidad_medida->nombre=$request->nombre;
        $unidad_medida->abreviatura=$request->abreviatura;
        $unidad_medida->estado=$request->estado;
        $unidad_medida->save();

        $datos = [
            'tabla' => 'unidad_medida',
            'codigo_tabla' => $unidad_medida->id,
            'transaccion' => 'modificar',
        ];
        $this->guardarBitacora($datos);
    }

    public function desactivar(Request $request){
        $unidad_medida = UnidadMedida::findOrFail($request->id);
        $unidad_medida->estado = '0';
        $unidad_medida->save();
    }

    public function activar(Request $request){
        $unidad_medida = UnidadMedida::findOrFail($request->id);
        $unidad_medida->estado = '1';
        $unidad_medida->save();
    }
    public function selectUnidad(){  
        $obj = UnidadMedida::select('id', 'nombre')->where('estado',1)->orderBy('unidad_medida.id','asc')->get(); 
        return $obj;
    }

    public function cantidadRegistros(){
        $cantidad = DB::table('unidad_medida')->count();
        $data=['nro' =>$cantidad];
        return $data;
    }
}

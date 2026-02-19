<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MotivoGasto;
use App\Http\Requests\MotivoGastoRequest;
use DB;

class MotivoGastoController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;       
        if ($buscar==''){
            $motivo_gasto = MotivoGasto::orderBy('motivo_gasto.id', 'desc')->paginate(15);
        }
        else{
            $motivo_gasto = MotivoGasto::where('motivo_gasto.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('motivo_gasto.id', 'desc')->paginate(15);
        }
        return $motivo_gasto;
    }

    public function guardar(MotivoGastoRequest $request){
        if (MotivoGasto::where('nombre', $request->nombre)->first()){
            return ['error'=>0];
        }
        else{
        $motivo_gasto= new MotivoGasto();
        $motivo_gasto->nombre=($request->nombre);
        $motivo_gasto->descripcion=$request->descripcion;
        $motivo_gasto->save();
        }
        $datos = [
            'tabla' => 'motivo_gasto',
            'codigo_tabla' => $motivo_gasto->id,
            'transaccion' => 'guardar',
        ];
        $this->guardarBitacora($datos);
        

    }

    public function modificar(MotivoGastoRequest $request){
        // if($request->nombre == ''){ 
        $motivo_gasto= MotivoGasto::findOrFail($request->id);
        $motivo_gasto->nombre=($request->nombre);
        $motivo_gasto->descripcion=$request->descripcion;
        $motivo_gasto->save();
        // }else if (MotivoGasto::where('nombre', $request->nombre )->first()){
            
        //     if (MotivoGasto::where('id', $request->id ) ->first()){
        //         return ['error'=>0];
                
        //     }else
        //     {
        //         $motivo_gasto= MotivoGasto::findOrFail($request->id);
        //         $motivo_gasto->nombre=($request->nombre);
        //         $motivo_gasto->descripcion=$request->descripcion;
        //         $motivo_gasto->save();
        //     }
        // }
        // else{
        //     $motivo_gasto= MotivoGasto::findOrFail($request->id);
        //     $motivo_gasto->nombre=($request->nombre);
        //     $motivo_gasto->descripcion=$request->descripcion;
        //     $motivo_gasto->save();
        // }
        $datos = [
            'tabla' => 'motivo_gasto',
            'codigo_tabla' => $motivo_gasto->id,
            'transaccion' => 'guardar',
        ];
        $this->guardarBitacora($datos);
    }

    public function cantidadRegistros(){
        $cantidad = DB::table('motivo_gasto')->count();

        $data=['nro' =>$cantidad];
        return $data;
    }
    
    public function selectMotivoGasto(){  
        $obj = MotivoGasto::select('id', 'nombre')->orderBy('motivo_gasto.id','desc')->get(); 
        return $obj;
    }

    
}

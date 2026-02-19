<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cargo;
use App\Http\Requests\CargoRequest;
use DB;

class CargoController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;       
        if ($buscar==''){
            $cargo = Cargo::orderBy('cargo.id', 'desc')->paginate(15);
        }
        else{
            $cargo = Cargo::where('cargo.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('cargo.id', 'desc')->paginate(15);
        }
        return $cargo;
    }

    public function guardar(CargoRequest $request){
        if (Cargo::where('nombre', $request->nombre)->first()){
            return ['error'=>0];
        }
        else{
            $cargo= new Cargo();
            $cargo->nombre=$request->nombre;
            $cargo->descripcion=$request->descripcion;
            $cargo->estado=$request->estado;
            $cargo->save();
        }

        $datos = [
            'tabla' => 'cargo',
            'codigo_tabla' => $cargo->id,
            'transaccion' => 'guardar',
        ];
        $this->guardarBitacora($datos);
    }

    public function modificar(CargoRequest $request){
        if (Cargo::where('nombre', $request->nombre)->where('id','!=',$request->id)->first()){
            return ['error'=>0];
        }
        else{
            $cargo= Cargo::findOrFail($request->id);
            $cargo->nombre=$request->nombre;
            $cargo->descripcion=$request->descripcion;
            $cargo->estado=$request->estado;
            $cargo->save();
        }

        $datos = [
            'tabla' => 'cargo',
            'codigo_tabla' => $cargo->id,
            'transaccion' => 'modificar',
        ];
        $this->guardarBitacora($datos);

    }
    
    public function desactivar(Request $request){
        $cargo = Cargo::findOrFail($request->id);
        $cargo->estado = '0';
        $cargo->save();
    }

    public function activar(Request $request){
        $cargo = Cargo::findOrFail($request->id);
        $cargo->estado = '1';
        $cargo->save();
    }

    public function selectCargo(){  
        $obj = Cargo::select('id', 'nombre')->where('cargo.estado', 1)->orderBy('cargo.id','desc')->get(); 
        return $obj;
    }

    public function cantidadRegistros(){
        $cantidad = DB::table('cargo')->count();
        $data=['nro' =>$cantidad];
        return $data;
    }
}

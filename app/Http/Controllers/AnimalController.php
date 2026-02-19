<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Http\Requests\AnimalRequest;
use DB;

class AnimalController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;       
        if ($buscar==''){
            $animal = Animal::orderBy('animal.id', 'desc')->paginate(15);
        }
        else{
            $animal = Animal::where('animal.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('animal.id', 'desc')->paginate(15);
        }
        return $animal;
    }
    public function guardar(AnimalRequest $request){
        if (Animal::where('nombre', $request->nombre)->first()){
            return ['error'=>0];
        }
        else{
            $animal= new Animal();
            $animal->nombre=$request->nombre;
            $animal->descripcion=$request->descripcion;
            $animal->estado=$request->estado;
            $animal->save();
        }

        $datos = [
            'tabla' => 'animal',
            'codigo_tabla' => $animal->id,
            'transaccion' => 'guardar',
        ];
        $this->guardarBitacora($datos);
    }
    public function modificar(AnimalRequest $request){
        if (Animal::where('nombre', $request->nombre)->where('id','!=',$request->id)->first()){
            return ['error'=>0];
        }
        else{
            $animal= Animal::findOrFail($request->id);
            $animal->nombre=$request->nombre;
            $animal->descripcion=$request->descripcion;
            $animal->estado=$request->estado;
            $animal->save();
        }

        $datos = [
            'tabla' => 'animal',
            'codigo_tabla' => $animal->id,
            'transaccion' => 'modificar',
        ];
        $this->guardarBitacora($datos);

    }
    public function desactivar(Request $request){
        $animal = Animal::findOrFail($request->id);
        $animal->estado = '0';
        $animal->save();
    }
    public function activar(Request $request){
        $animal = Animal::findOrFail($request->id);
        $animal->estado = '1';
        $animal->save();
    }
    public function selectAnimal(){  
        $obj = Animal::select('id', 'nombre')->where('animal.estado', 1)->orderBy('animal.id','desc')->get(); 
        return $obj;
    }
    public function cantidadRegistros(){
        $cantidad = DB::table('animal')->count();
        $data=['nro' =>$cantidad];
        return $data;
    }
}

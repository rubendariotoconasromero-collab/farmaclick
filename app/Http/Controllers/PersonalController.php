<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Personal;
use App\Http\Requests\PersonalRequest;
use DB;

class PersonalController extends BitacoraController
{
    public function index(Request $request){
        if(!$request->ajax()) return redirect('/');
        $nroPag = $request->pag;
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if($buscar==''){
            $obj= Personal::join('cargo','personal.id_cargo','=','cargo.id')
            ->select('personal.id','personal.nombre','personal.telefono','personal.direccion','personal.descripcion','personal.estado','personal.id_cargo',
            'cargo.nombre as cargo')
            ->where('personal.id', '>',1)
            ->orderBy('personal.id','desc')->paginate($nroPag);
        }
        else{
            $obj= Personal::join('cargo','personal.id_cargo','=','cargo.id')
            ->select('personal.id','personal.nombre','personal.telefono','personal.direccion','personal.descripcion','personal.estado','personal.id_cargo',
            'cargo.nombre as cargo')
            ->where('personal.id', '>',1)
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->orderBy('personal.id','desc')->paginate($nroPag);            
        }
        return $obj;
    }

    public function guardar(PersonalRequest $request){
        try{
            DB::beginTransaction();
            $obj = new Personal();
            $obj->nombre=$request->nombre;
            $obj->telefono=$request->telefono;
            $obj->direccion=$request->direccion;
            $obj->descripcion=$request->descripcion;
            $obj->estado=$request->estado;
            $obj->id_cargo=$request->id_cargo;
            $obj->save();


            $datos = [
                'tabla' => 'personal',
                'codigo_tabla' => $obj->id,
                'transaccion' => 'guardar',
            ];
            $this->guardarBitacora($datos);
        
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function modificar(PersonalRequest $request){
        $obj= Personal::findOrFail($request->id);
        $obj->nombre=$request->nombre;
        $obj->telefono=$request->telefono;
        $obj->direccion=$request->direccion;
        $obj->descripcion=$request->descripcion;
        $obj->estado=$request->estado;
        $obj->id_cargo=$request->id_cargo;
        $obj->save();

        $datos = [
            'tabla' => 'personal',
            'codigo_tabla' => $obj->id,
            'transaccion' => 'modificar',
        ];
        $this->guardarBitacora($datos);
    }

    public function desactivar(Request $request){
        $personal = Personal::findOrFail($request->id);
        $personal->estado = '0';
        $personal->save();
    }

    public function activar(Request $request){
        $personal = Personal::findOrFail($request->id);
        $personal->estado = '1';
        $personal->save();
    }
    
    public function cantidadRegistros(){
        $cantidad = DB::table('personal')->count();

        $data=['nro' =>$cantidad-1];
        return $data;
    }

    public function listarSinPaginate(Request $request){
        $buscar = $request->buscar;     
        if ($buscar==''){

            $personal = Personal::join('cargo','personal.id_cargo','=','cargo.id')
            ->select('personal.id','personal.nombre','personal.telefono','personal.direccion','cargo.nombre as cargo')
            //->where('personal.id_cargo',2)
            ->where('personal.id','!=',1)
            ->where('personal.estado',1)
            ->orderBy('personal.id', 'desc')->get();
        }
        else{
            $personal = Personal::join('cargo','personal.id_cargo','=','cargo.id')
            ->select('personal.id','personal.nombre','personal.telefono','personal.direccion','cargo.nombre as cargo')
            //->where('personal.id_cargo',2)
            ->where('personal.estado',1)
            ->where('personal.nombre', 'like', '%'.$buscar.'%')
            ->orderBy('personal.id', 'desc')->get();
        }
        return $personal;
    }

    public function selectPersonal(){  
        $obj = Personal::select('id', 'nombre')
        ->where('personal.estado', 1)
        ->where('personal.id','!=',1)
        ->orderBy('personal.id','desc')->get(); 
        return $obj;
    }
    public function selectPersonalDoctor(){  
        $obj = Personal::select('id', 'nombre')
        ->where('personal.estado', 1)
        ->where('personal.id_cargo','=',2)
        ->orderBy('personal.id','desc')->get(); 
        return $obj;
    }
}

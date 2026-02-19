<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permiso;
use App\Http\Requests\CargoRequest;

class PermisoController extends Controller
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;       
        if ($buscar==''){
            $permiso = Permiso::orderBy('permiso.id', 'desc')->paginate(15);
        }
        else{
            $permiso = Permiso::where('permiso.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('permiso.id', 'desc')->paginate(15);
        }
        return $permiso;
    }

    public function listarSinPaginate(Request $request){
        $buscar = $request->buscar;     
        if ($buscar==''){
            $permiso = Permiso::orderBy('permiso.id', 'desc')->get();
        }
        else{
            $permiso = Permiso::where('permiso.nombre', 'like', '%'.$buscar.'%')
            ->orderBy('permiso.id', 'desc')->get();
        }
        return $permiso;
    }

    public function guardar(CargoRequest $request){
        $permiso= new Permiso();
        $permiso->nombre=strtoupper($request->nombre);
        $permiso->tipo_permiso=$request->tipo_permiso;
        $permiso->save();
    }

    public function modificar(CargoRequest $request){
        $permiso= Permiso::findOrFail($request->id);
        $permiso->nombre=strtoupper($request->nombre);
        $permiso->tipo_permiso=$request->tipo_permiso;
        $permiso->save();
    }

    public function eliminar(Request $request){
        $permiso= Permiso::findOrFail($request->id);
        $permiso->delete();
    }
}

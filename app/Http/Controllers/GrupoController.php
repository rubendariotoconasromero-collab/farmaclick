<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use DB;

class GrupoController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;       
        if ($buscar==''){
            $grupo = Grupo::orderBy('grupo.id', 'desc')
            ->where('grupo.id','!=',1)
            ->paginate(15);
        }
        else{
            $grupo = Grupo::where('grupo.'.$criterio, 'like', '%'.$buscar.'%')
            ->where('grupo.id','!=',1)
            ->orderBy('grupo.id', 'desc')->paginate(15);
        }
        return $grupo;
    }

    public function listar(Request $request){
        $buscar = $request->buscar;

        if ($buscar==''){
            $obj = DB::table('grupo')
            ->leftJoin('users', 'grupo.id', '=', 'users.id_grupo')
            ->select('grupo.id','grupo.nombre',DB::raw('count(users.id) as nroUsuarios'))
            ->where('grupo.estado',1)
            ->where('grupo.is_super_admin', false)
            ->groupBy('grupo.id','grupo.nombre')
            ->get();
        }
        else{
            $obj = DB::table('grupo')
            ->leftJoin('users', 'grupo.id', '=', 'users.id_grupo')
            ->select('grupo.id','grupo.nombre',DB::raw('count(users.id) as nroUsuarios'))
            ->where('grupo.estado',1)
            ->where('grupo.is_super_admin', false)
            ->where('grupo.nombre', 'like', '%'.$buscar.'%')
            ->groupBy('grupo.id','grupo.nombre')
            ->get();
        }
        return $obj;
    }

    public function selectGrupo(){  
        $obj = Grupo::select('id', 'nombre')
            ->where('grupo.estado', 1)
            ->where('grupo.is_super_admin', false)
            ->orderBy('grupo.id','desc')->get();
        return $obj;
    }
   
}

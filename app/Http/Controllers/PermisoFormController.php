<?php

namespace App\Http\Controllers;

use App\Models\PermisoForm;
use Illuminate\Http\Request;
use DB;

class PermisoFormController extends Controller
{
    public function listarPermisos(Request $request){
        $id_grupo = $request->id;
        $obj = DB::table('grupo as g')
            ->join('PermisoForm as pf', 'g.id', '=', 'pf.id_grupo')
            ->join('DetalleForm as df', 'pf.id', '=', 'df.id_permiso_form')
            ->join('formulario as f', 'f.id', '=', 'df.id_formulario')
            ->select('f.id','f.nombre')
            ->where('g.id', '=', $id_grupo)
            ->get();
        return $obj;
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\GrupoPermiso;
use App\Models\DetalleForm;
use App\Http\Requests\GrupoRequest;
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
            ->groupBy('grupo.id','grupo.nombre')
            ->get();
        }
        else{
            $obj = DB::table('grupo')
            ->leftJoin('users', 'grupo.id', '=', 'users.id_grupo')
            ->select('grupo.id','grupo.nombre',DB::raw('count(users.id) as nroUsuarios'))
            ->where('grupo.estado',1)
            ->where('grupo.nombre', 'like', '%'.$buscar.'%')
            ->groupBy('grupo.id','grupo.nombre')
            ->get();
        }
        return $obj;
    }

    public function guardar(GrupoRequest $request){
        try{
            DB::beginTransaction();
            $grupo = new Grupo();
            $grupo->nombre=$request->nombre;
            $grupo->descripcion=$request->descripcion;
            $grupo->estado=$request->estado;
            $grupo->save();  

            /* $detalles = $request->detalle;
            foreach($detalles as $ep=>$det){
                $obj = new GrupoPermiso();
                $obj->id_grupo= 1;
                $obj->id_permiso= 1;
                $obj->save();
            } */
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    // private function eliminarDetalle($id_grupo){
    //     $obj = GrupoPermiso::where('grupo_permiso.id_grupo','=',$id_grupo);
    //     $obj->delete();
    // }

    // public function modificar(Request $request){
    //     try{
    //         DB::beginTransaction();
    //         $grupo = Grupo::findOrFail($request->id);
    //         $grupo->nombre=$request->nombre;
    //         $grupo->descripcion=$request->descripcion;
    //         $grupo->estado=$request->estado;
    //         $grupo->save();  

    //         $this->eliminarDetalle($request->id);
    //         $detalles = $request->detalle;
    //         foreach($detalles as $ep=>$det){
    //             $obj = new GrupoPermiso();
    //             $obj->id_grupo= $request->id;
    //             $obj->id_permiso= $det['id_permiso'];
    //             $obj->save();
    //         }
    //         DB::commit();
    //     } catch (Exception $e){
    //         DB::rollBack();
    //     }
    // }

    public function desactivar(Request $request){
        $Grupo = Grupo::findOrFail($request->id);
        $Grupo->estado = '0';
        $Grupo->save();

        $datos = [
            'tabla' => 'grupo',
            'codigo_tabla' => $request->id,
            'transaccion' => 'desactivar',
        ];
    }

    public function activar(Request $request){
        $Grupo = Grupo::findOrFail($request->id);
        $Grupo->estado = '1';
        $Grupo->save();

        $datos = [
            'tabla' => 'grupo',
            'codigo_tabla' => $request->id,
            'transaccion' => 'activar',
        ];
    }

    // public function detallePermiso(Request $request){
    //     $grupo = GrupoPermiso::join('permiso','grupo_permiso.id_permiso','=','permiso.id')
    //     ->select('grupo_permiso.id_permiso','permiso.nombre','permiso.tipo_permiso')
    //     ->where('grupo_permiso.id_grupo', '=', $request->id)
    //     ->get();
        
    //     return $grupo;
    // }

    public function detallePermiso(Request $request){
        $grupo = DetalleForm::join('formularios','detalle_forms.id_formulario','=','formularios.id')
        ->join('permiso_forms','detalle_forms.id_permiso_form','=','permiso_forms.id')
        ->select('detalle_forms.id_formulario','formularios.nombre','permiso_forms.id','permiso_forms.id_grupo')
        ->where('permiso_forms.id_grupo', '=', $request->id)
        ->get();
        
        return $grupo;
    }

    public function selectGrupo(){  
        $obj = Grupo::select('id', 'nombre')->where('grupo.estado', 1)->orderBy('grupo.id','desc')->get(); 
        return $obj;
    }
   
}

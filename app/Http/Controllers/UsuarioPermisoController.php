<?php

namespace App\Http\Controllers;

use App\Models\UsuarioPermiso;
use Illuminate\Http\Request;
use DB;

class UsuarioPermisoController extends BitacoraController
{
    public function obtenerDetalles(Request $request){
        $id_usuario = $request->id;
        $obj=DB::table('users as u')
            ->join('usuario_permisos as up','u.id','=','up.id_usuario')
            ->join('detalle_forms as df','up.id_permiso','=','df.id')
            ->join('formularios as f','df.id_formulario','=','f.id')
            ->select('df.id as id_permiso','f.nombre')
            ->where('u.id', '=', $id_usuario)
            ->orderBy('df.id','desc')
            ->get();
 
        return $obj;
    }

    public function modificarPermisos(Request $request){
        try{
            DB::beginTransaction();
            $datos = $request->id;

            $detalle_permiso = $request->detalle;
            foreach($detalle_permiso as $ep=>$det){
                $obj = new UsuarioPermiso();
                $obj->id_usuario = $datos; 
                $obj->id_permiso = $det['id_permiso'];
                $obj->save();
            } 

            $datos = [
                'tabla' => 'detalle_permiso',
                'codigo_tabla' => $datos,
                'transaccion' => 'modificar',
            ];

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function verPermisos(Request $request){
        $idUsuario = $request->id;
        $obj=DB::table('usuario as u')
            ->join('usuario_permiso as up','u.id','=','up.id_usuario')
            ->join('detalle_form as df','up.id_permiso','=','df.id')
            ->join('formulario as f','df.id_formulario','=','f.id')
            ->select('f.id','f.nombre')
            ->where('u.id', '=', $idUsuario)
            ->orderBy('df.id','desc')
            ->get();
 
        return $obj;
    }

    public function eliminarPermisoUsuario(Request $request){
        $id=$request->id;
        $obj = UsuarioPermiso::where('usuario_permisos.id_usuario','=',$id);
        $obj->delete();
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\DetalleForm;
use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\PermisoForm;
use App\Http\Requests\GrupoRequest;
use App\Models\User;
use App\Models\UsuarioPermiso;
use DB;

class DetalleFormController extends BitacoraController
{

public function listar(Request $request){
        $id_grupo = $request->id_grupo;
        $buscar = $request->buscar;
        if($buscar == '')
            $obj=DB::table('detalle_forms as df')
            ->join('permiso_forms as pf','df.id_permiso_form','=','pf.id')
            ->join('grupo as g','pf.id_grupo','=','g.id')
            ->join('formularios as f','df.id_formulario','=','f.id')
            ->select('df.id','f.nombre','f.id', 'g.nombre as grupo_nombre')
            ->where('g.id', '=', $id_grupo)
            ->whereNotIn('f.id', [4,13])
            ->orderBy('df.id','asc')
            ->get();   
        else {
            $obj=DB::table('detalle_forms as df')
            ->join('permiso_forms as pf','df.id_permiso_form','=','pf.id')
            ->join('grupo as g','pf.id_grupo','=','g.id')
            ->join('formularios as f','df.id_formulario','=','f.id')
            ->select('df.id','f.nombre','f.id', 'g.nombre as grupo_nombre')
            ->where('g.id', '=', $id_grupo)
            ->where('f.nombre', 'like', '%'.$buscar.'%')
            ->whereNotIn('f.id', [4,13])
            ->orderBy('df.id','asc')
            ->get();  
        }
        return $obj;
    }

    // public function listar(Request $request){
    //     $id_grupo = $request->id_grupo;
    //     $buscar = $request->buscar;
    //     if($buscar = '')
    //         $obj=DB::select("SELECT df.id, f.nombre FROM detalle_forms as df, permiso_forms as pf, grupo as g, formularios as f WHERE df.id_permiso_form = pf.id and pf.id_grupo = g.id and df.id_formulario = f.id and g.id = $id_grupo ORDER BY df.id = 'asc'");  
    //     else {
    //         $obj=DB::select("SELECT df.id, f.nombre FROM detalle_forms as df, permiso_forms as pf, grupo as g, formularios as f WHERE df.id_permiso_form = pf.id and pf.id_grupo = g.id and df.id_formulario = f.id and g.id = $id_grupo and f.nombre like '%$request->id_usuario%' ORDER BY df.id = 'asc'");
    //     }
    //     return $obj;
    // }

    public function obtenerDetalles(Request $request){
        $id_grupo = $request->id;
        $obj=DB::table('detalle_form as df')
            ->join('permiso_form as pf','df.id_permiso_form','=','pf.id')
            ->join('grupo as g','pf.id_grupo','=','g.id')
            ->join('formulario as f','df.id_formulario','=','f.id')
            ->select('f.id as id_formulario','f.nombre')
            ->where('g.id', '=', $id_grupo)
            ->get();
 
        return $obj;
    }

    public function ultimoIdPermisoForm(){
        $obj = DB::table('permiso_forms as pf')
            ->select(DB::raw('max(pf.id) as id_ultm'))
            ->get();
        return $obj[0]->id_ultm;
    }

    public function guardar(GrupoRequest $request){
        try{
            DB::beginTransaction();
            $datos = $request->datos;
            $grup = new Grupo();
            $grup->nombre=$request->nombre;
            $grup->descripcion=$request->descripcion;
            $grup->estado=$request->estado;
            $grup->save();

            $detalleform = $request->detalle;
            foreach($detalleform as $ep=>$det){
                $obj = new PermisoForm();
                $obj->id_grupo = $grup->id; 
                $obj->save();

                $obj1 = new DetalleForm();
                $obj1->id_permiso_form = $this->ultimoIdPermisoForm();
                $obj1->id_formulario = $det['id_formulario']; 
                $obj1->save();                
            }

            $datos = [
                'tabla' => 'grupo',
                'codigo_tabla' => $grup->id,
                'transaccion' => 'guardar',
            ];
            $this->guardarBitacora($datos);

            DB::commit();
            return[
                'id'=>$grup->id
            ];
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function eliminarDetalleForm(Request $request){
        $id_grupo=$request->id;
        $obj = DetalleForm::join('Permiso_Form','detalle_form.id_permiso_form','=','permiso_form.id')
        ->where('permiso_form.id_grupo','=',$id_grupo);
        $obj->delete();

        $obj2 = PermisoForm::where('permiso_form.id_grupo','=',$id_grupo);
        $obj2->delete();
    }

    public function eliminarDetalleGrupo(Request $request){
        $id_grupo=$request->id;
        $obj = DetalleForm::join('permiso_form','detalle_form.id_permiso_form','=','permiso_form.id')
        ->where('permiso_form.id_grupo','=',$id_grupo);
        $obj->delete();

        $obj2 = PermisoForm::where('permiso_forms.id_grupo','=',$id_grupo);
        $obj2->delete();

        $obj3 = Grupo::where('grupo.id','=',$id_grupo);
        $obj3->delete();
    }

    public function modificar2(Request $request){
        try{
            DB::beginTransaction();
            $datos = $request->datos;
            $idGrupo = $request->id;
            //dd($idGrupo);

            $grup = Grupo::findOrFail($idGrupo);
            $grup->nombre=$request->nombre;
            $grup->descripcion=$request->descripcion;
            $grup->estado=$request->estado;
            $grup->save();

            $usuarios=User::join('grupo', 'users.id_grupo', '=', 'grupo.id')
            ->select('users.name', 'users.id')
            ->where('grupo.id', '=', $idGrupo)->get();

            $formularios=DetalleForm::join('permiso_forms', 'detalle_forms.id_permiso_form', '=', 'permiso_forms.id')
            ->join('formularios', 'detalle_forms.id_formulario', '=', 'formularios.id')
            ->join('grupo', 'permiso_forms.id_grupo', '=', 'grupo.id')
            ->select('formularios.nombre', 'formularios.id')
            ->where('grupo.id', $idGrupo)->get();
   
           
            $obj = DetalleForm::join('permiso_forms','detalle_forms.id_permiso_form','=','permiso_forms.id')
            ->where('permiso_forms.id_grupo','=',$idGrupo);
            $obj->delete();

            $obj2 = PermisoForm::where('permiso_forms.id_grupo','=',$idGrupo);
            $obj2->delete();



            $detalleform = $request->detalle;
            foreach($detalleform as $ep=>$det){
                $objp = new PermisoForm();
                $objp->id_grupo = $idGrupo; 
                $objp->save();

                $obj1 = new DetalleForm();
                $obj1->id_permiso_form = $this->ultimoIdPermisoForm();
                $obj1->id_formulario = $det['id_formulario']; 
                $obj1->save();   
                             
            }

            foreach($usuarios as $ep=>$user){
                foreach($formularios as $ep1=>$formulario){
                     UsuarioPermiso::select('id_usuario', 'id_permiso')
                    ->where('id_usuario', $user['id'])
                    ->delete();
                } 
            }

            foreach($usuarios as $ep=>$user){
                foreach($formularios as $ep1=>$formulario){
                    $usuario_permiso = new UsuarioPermiso();
                    $usuario_permiso->id_usuario=$user['id'];
                    $usuario_permiso->id_permiso=$formulario['id'];
                    $usuario_permiso->save();
                } 
            }
            

            $datos = [
                'tabla' => 'grupo',
                'codigo_tabla' => $idGrupo,
                'transaccion' => 'modificar permisos',
            ];
            $this->guardarBitacora($datos);
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
}

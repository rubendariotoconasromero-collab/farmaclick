<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UsuarioRequest;
use DB;

class UsuarioController extends BitacoraController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $nroPag = min(max((int) ($request->pag ?: 15), 1), 100);
        $buscar = $request->buscar;
        $criterios = ['users.name', 'grupo.nombre', 'personal.nombre'];
        $criterio = in_array($request->criterio, $criterios, true) ? $request->criterio : 'users.name';
        if ($buscar==''){
            $usuario = User::join('personal','users.id_personal','=','personal.id')
            ->join('grupo','users.id_grupo','=','grupo.id')
            ->select('users.id','users.name','users.matricula','users.email','users.id_personal','users.id_grupo','users.estado',
            'personal.nombre as personal','grupo.nombre as grupo')
            ->where('grupo.is_super_admin', false)
            ->where('grupo.estado','=',1)
            ->orderBy('users.id', 'desc')->paginate($nroPag);
        }
        else{
            $usuario = User::join('personal','users.id_personal','=','personal.id')
            ->join('grupo','users.id_grupo','=','grupo.id')
            ->select('users.id','users.name','users.matricula','users.email','users.id_personal','users.id_grupo','users.estado',
            'personal.nombre as personal','grupo.nombre as grupo')
            ->where($criterio, 'like', '%'.$buscar.'%')
            ->where('grupo.is_super_admin', false)
            ->where('grupo.estado','=',1)
            ->orderBy('users.id', 'desc')->paginate($nroPag);
        }
        return $usuario;
    }

    public function listarGrupoUsuario(Request $request){
        $id = $request->validate(['id' => ['required', 'integer', 'exists:grupo,id']])['id'];
        $group = \App\Models\Grupo::findOrFail($id);
        abort_if($group->is_super_admin, 403, 'El rol superadministrador es reservado.');
        $obj = User::select('users.id','users.name as nombre','users.estado','users.id_grupo')
        ->where('users.id_grupo', '=', $id)
        ->orderBy('users.id', 'desc')->get();
        return $obj;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function guardar(UsuarioRequest $request){
        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nombre,
                'matricula' => $request->matricula,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'id_grupo' => $request->id_grupo,
                'id_personal' => $request->id_personal,
                'estado' => $request->estado,
            ]);

            $this->guardarBitacora([
                'tabla' => 'users',
                'codigo_tabla' => $user->id,
                'transaccion' => 'guardar',
            ]);

            return $user;
        });

        return response()->json(['message' => 'Usuario creado correctamente.', 'user' => $user], 201);
    }

    public function modificar(UsuarioRequest $request){
        $user = User::with('group')->findOrFail($request->id);
        abort_if($user->isSuperAdmin(), 422, 'La cuenta superadministradora no puede modificarse desde este módulo.');

        DB::transaction(function () use ($request, $user) {
            $user->fill([
                'name' => $request->nombre,
                'matricula' => $request->matricula,
                'email' => $request->email,
                'id_grupo' => $request->id_grupo,
                'id_personal' => $request->id_personal,
                'estado' => $request->estado,
            ]);
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }
            $user->save();

            $this->guardarBitacora([
                'tabla' => 'users',
                'codigo_tabla' => $user->id,
                'transaccion' => 'modificar',
            ]);
        });

        return response()->json(['message' => 'Usuario actualizado correctamente.', 'user' => $user->fresh()]);
    }

    public function desactivar(Request $request){
        $request->validate(['id' => ['required', 'integer', 'exists:users,id']]);
        $obj = User::with('group')->findOrFail($request->id);
        abort_if($obj->isSuperAdmin(), 422, 'La cuenta superadministradora no puede desactivarse.');
        $obj->estado = '0';
        $obj->save();

        $datos = [
            'tabla' => 'usuario',
            'codigo_tabla' => $request->id,
            'transaccion' => 'desactivar',
        ];
        $this->guardarBitacora($datos);
    }

    public function activar(Request $request){
        $request->validate(['id' => ['required', 'integer', 'exists:users,id']]);
        $obj = User::with('group')->findOrFail($request->id);
        abort_if($obj->isSuperAdmin(), 422, 'La cuenta superadministradora se administra de forma reservada.');
        $obj->estado = '1';
        $obj->save();

        $datos = [
            'tabla' => 'usuario',
            'codigo_tabla' => $request->id,
            'transaccion' => 'activar',
        ];
        $this->guardarBitacora($datos);
    }

    public function maximoId(){
        $maximoID = \DB::table('users')->where('id', \DB::raw("(select max(`id`) from users)"))->get();
        return $maximoID;

    }

    public function usuarioId(){
        $id = \Auth::user()->id;
        $objeto=DB::select("SELECT u.id, u.name, p.nombre FROM users as u , personal as p WHERE u.id_personal = p.id and u.id = $id");

        $objeto1=(object) $objeto[0];
        return $objeto1;
    }

    public function listar(Request $request){
        $nroPag = $request->pag;
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        if ($buscar==''){
            $obj = User::join('grupo','users.id_grupo','=','grupo.id')
            ->join('cargo','users.id_cargo','=','cargo.id')
            ->select('users.id','users.name','users.password','users.email','users.id_grupo','users.estado','users.id_cargo','grupo.nombre as grupo')
            ->orderBy('users.id', 'desc')->paginate($nroPag);
        }
        else{
            $obj = User::join('grupo','users.id_grupo','=','grupo.id')
            ->join('cargo','users.id_cargo','=','cargo.id')
            ->select('users.id','users.name','users.password','users.email','users.id_grupo','users.estado','users.id_cargo','grupo.nombre as grupo')
            ->where('users.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('users.id', 'desc')->paginate($nroPag);
        }
        return $obj;
    }
    public function montoUsuario(){
        $venta=User::select(DB::raw('COUNT(users.id) as usuario'))->where('users.id_grupo','=','1')->where('users.estado','=','1')->where('users.id_personal','>','1')->get();
        //dd($venta);
        return $venta;
    }
    public function montoCajeros(){
        $venta=User::select(DB::raw('COUNT(users.id) as usuario'))->where('users.id_grupo','=','2')->where('users.estado','=','1')->get();
        //dd($venta);
        return $venta;
    }

    public function usuario(){
        $id = \Auth::user()->id;
        $objeto=DB::table('users')->select('users.name','users.id','users.id_personal','users.id_grupo','users.estado')->where('users.id','=',$id)->get();

        $objeto1=(object) $objeto[0];
        return $objeto1;

        //return $objeto;
    }
    public function selectUsuario(){  
        // $obj = User::select('id', 'name as nombre')->where('estado',1)->orderBy('users.id','asc')->get(); 
        $obj = User::select('id', 'name as nombre')->where('estado',1)->where('id','!=',1)->orderBy('users.id','asc')->get(); 
        return $obj;
    }

    

}

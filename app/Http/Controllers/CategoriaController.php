<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Http\Requests\CategoriaRequest;
use DB;

class CategoriaController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;       
        if ($buscar==''){
            $categoria = Categoria::orderBy('categoria.id', 'desc')->paginate(15);
        }
        else{
            $categoria = Categoria::where('categoria.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('categoria.id', 'desc')->paginate(15);
        }
        return $categoria;
    }

    public function guardar(CategoriaRequest $request){
        if (Categoria::where('nombre', $request->nombre)->first()){
            return ['error'=>0];
        }
        else{
        $categoria= new Categoria();
        $categoria->nombre=$request->nombre;
        $categoria->descripcion=$request->descripcion;
        $categoria->estado=$request->estado;
        $categoria->save();
        }
        $datos = [
            'tabla' => 'categoria',
            'codigo_tabla' => $categoria->id,
            'transaccion' => 'guardar',
        ];
        $this->guardarBitacora($datos);
    }

    public function modificar(Request $request){
        $categoria= Categoria::findOrFail($request->id);
        $categoria->nombre=$request->nombre;
        $categoria->descripcion=$request->descripcion;
        $categoria->estado=$request->estado;
        $categoria->save();

        $datos = [
            'tabla' => 'categoria',
            'codigo_tabla' => $categoria->id,
            'transaccion' => 'modificar',
        ];
        $this->guardarBitacora($datos);
    }

    public function desactivar(Request $request){
        $categoria = Categoria::findOrFail($request->id);
        $categoria->estado = '0';
        $categoria->save();
    }

    public function activar(Request $request){
        $categoria = Categoria::findOrFail($request->id);
        $categoria->estado = '1';
        $categoria->save();
    }
    public function selectCategoria(){  
        $obj = Categoria::select('id', 'nombre')->where('estado',1)->orderBy('categoria.id','asc')->get(); 
        return $obj;
    }

    public function cantidadRegistros(){
        $cantidad = DB::table('categoria')->count();
        $data=['nro' =>$cantidad];
        return $data;
    }
}

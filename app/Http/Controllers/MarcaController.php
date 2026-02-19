<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use App\Http\Requests\MarcaRequest;
use DB;
class MarcaController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;       
        if ($buscar==''){
            $marca = Marca::orderBy('marca.id', 'desc')->paginate(15);
        }
        else{
            $marca = Marca::where('marca.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('marca.id', 'desc')->paginate(15);
        }
        return $marca;
    }

    public function guardar(MarcaRequest $request){
        if (Marca::where('nombre', $request->nombre)->first()){
            return ['error'=>0];
        }
        else{
        $marca= new Marca();
        $marca->nombre=$request->nombre;
        $marca->descripcion=$request->descripcion;
        $marca->estado=$request->estado;
        $marca->save();
        }
        $datos = [
            'tabla' => 'marca',
            'codigo_tabla' => $marca->id,
            'transaccion' => 'guardar',
        ];
        $this->guardarBitacora($datos);
    }

    public function modificar(Request $request){
        $marca= Marca::findOrFail($request->id);
        $marca->nombre=$request->nombre;
        $marca->descripcion=$request->descripcion;
        $marca->estado=$request->estado;
        $marca->save();

        $datos = [
            'tabla' => 'marca',
            'codigo_tabla' => $marca->id,
            'transaccion' => 'modificar',
        ];
        $this->guardarBitacora($datos);
    }

    public function desactivar(Request $request){
        $categoria = Marca::findOrFail($request->id);
        $categoria->estado = '0';
        $categoria->save();
    }

    public function activar(Request $request){
        $categoria = Marca::findOrFail($request->id);
        $categoria->estado = '1';
        $categoria->save();
    }
    public function selectMarca(){  
        $obj = Marca::select('id', 'nombre')->where('estado',1)->orderBy('marca.id','asc')->get(); 
        return $obj;
    }

    public function cantidadRegistros(){
        $cantidad = DB::table('marca')->count();
        $data=['nro' =>$cantidad];
        return $data;
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Http\Requests\ProveedorRequest;
use DB;

class ProveedorController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;       
        if ($buscar==''){
            $proveedor = Proveedor::orderBy('proveedor.id', 'desc')->paginate(15);
        }
        else{
            $proveedor = Proveedor::where('proveedor.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('proveedor.id', 'desc')->paginate();
        }
        return $proveedor;
    }
    public function index_pagoP(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $id_usuario = \Auth::user()->id;
        if($buscar==''){
            $obj=DB::select("SELECT c.id,c.nombre as cliente, c.telefono,SUM(g.monto) as monto, SUM(g.saldo) as saldo_cliente
            FROM proveedor c, pago_compra g, compra v, users u
            where v.id_proveedor=c.id and g.id_compra=v.id AND v.estado!='Anulado' AND g.id_tipo_pago=2   AND v.id_usuario=u.id AND u.id = '$id_usuario' GROUP by c.nombre,c.id,c.telefono");   
        }
        else{
            $obj=DB::select("SELECT c.id,c.nombre as cliente, c.telefono,SUM(g.monto) as monto, SUM(g.saldo) as saldo_cliente
            FROM proveedor c, pago_compra g, compra v, users u
            where v.id_proveedor=c.id and g.id_compra=v.id AND v.estado!='Anulado' AND g.id_tipo_pago=2   AND v.id_usuario=u.id AND u.id = '$id_usuario' AND v.id_usuario=u.id AND u.id = '$id_usuario' and c.nombre LIKE '%$buscar%'
            GROUP by c.nombre,c.id,c.telefono");
        }
        return $obj;
    }
    
    public function guardar(ProveedorRequest $request){
        $proveedor= new Proveedor();
        $proveedor->nombre=$request->nombre;
        $proveedor->nit=$request->nit;
        $proveedor->contacto=$request->contacto;
        $proveedor->direccion=$request->direccion;
        $proveedor->telefono=$request->telefono;
        $proveedor->descripcion=$request->descripcion;
        $proveedor->estado=$request->estado;
        $proveedor->save();

        $datos = [
            'tabla' => 'proveedor',
            'codigo_tabla' => $proveedor->id,
            'transaccion' => 'guardar',
        ];
        $this->guardarBitacora($datos);
    }

    public function modificar(ProveedorRequest $request){
        $proveedor= Proveedor::findOrFail($request->id);
        $proveedor->nombre=$request->nombre;
        $proveedor->nit=$request->nit;
        $proveedor->contacto=$request->contacto;
        $proveedor->direccion=$request->direccion;
        $proveedor->telefono=$request->telefono;
        $proveedor->descripcion=$request->descripcion;
        $proveedor->estado=$request->estado;
        $proveedor->save();

        $datos = [
            'tabla' => 'proveedor',
            'codigo_tabla' => $proveedor->id,
            'transaccion' => 'modificar',
        ];
        $this->guardarBitacora($datos);
     }

    public function desactivar(Request $request){
        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->estado = '0';
        $proveedor->save();
    }

    public function activar(Request $request){
        $proveedor = Proveedor::findOrFail($request->id);
        $proveedor->estado = '1';
        $proveedor->save();
    }

    public function selectProveedor(){  
        $obj = Proveedor::select('id', 'nombre')->orderBy('proveedor.id','desc')->get(); 
        return $obj;
    }

    
    public function cantidadRegistros(){
        $cantidad = DB::table('proveedor')->count();

        $data=['nro' =>$cantidad];
        return $data;
    }
}

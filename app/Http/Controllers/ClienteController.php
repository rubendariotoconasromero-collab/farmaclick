<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Http\Requests\ClienteRequest;
use DB;

class ClienteController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;       
        if ($buscar==''){
            $cliente = Cliente::orderBy('cliente.id', 'desc')
            ->where('cliente.id','!=',1)
            ->paginate(15);
        }
        else{
            $cliente = Cliente::where('cliente.'.$criterio, 'like', '%'.$buscar.'%')
            ->where('cliente.id','!=',1)
            ->orderBy('cliente.id', 'desc')->paginate(15);
        }
        return $cliente;
    }

    public function index_pago(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $id_tienda = $request->id_tienda;
        $tipo_producto = $request->tipo_producto;
        //$id_usuario = \Auth::user()->id;
        if($buscar==''){
            $obj=DB::select("SELECT c.id,c.nombre as cliente, c.matricula, c.telefono,SUM(g.monto) as monto, SUM(g.saldo) as saldo_cliente, v.id_tienda, v.tipo_venta,u.name FROM cliente c, pago g, venta v, users u
            where v.id_cliente=c.id and g.id_venta=v.id AND v.estado!='Anulado' AND v.estado!='Cancelado' AND g.id_tipo_pago=2 AND v.id_tienda = '$id_tienda' AND v.tipo_venta BETWEEN 'Venta Cotizacion'  AND '$tipo_producto' AND v.id_usuario=u.id GROUP by c.nombre,c.id,c.telefono");   
        }
        else{
            $obj=DB::select("SELECT c.id,c.nombre as cliente, c.matricula, c.telefono,SUM(g.monto) as monto, SUM(g.saldo) as saldo_cliente, v.id_tienda, v.tipo_venta FROM cliente c, pago g, venta v, users u
            where v.id_cliente=c.id and g.id_venta=v.id AND v.estado!='Anulado' AND v.estado!='Cancelado' AND g.id_tipo_pago=2 AND v.id_tienda = '$id_tienda' AND v.tipo_venta BETWEEN 'Venta Cotizacion'  AND '$tipo_producto' AND v.id_usuario=u.id and c.nombre LIKE '%$buscar%'
            GROUP by c.nombre,c.id,c.telefono");
        }
        return $obj;
    }

    public function guardar(Request $request){
        if (Cliente::where('nombre', $request->nombre)->first()){
            return ['error'=>0];
        }
        else{
            $cliente= new Cliente();
            $cliente->nombre=$request->nombre;
            //$cliente->matricula=$request->matricula;
            $cliente->matricula=$request->matricula != '' ? $request->matricula : 0;
            $cliente->telefono=$request->telefono;
            $cliente->direccion=$request->direccion;
            $cliente->descripcion=$request->descripcion;
            $cliente->descuento=$request->id_descuento;
            $cliente->estado=$request->estado;
            $cliente->save();
        }  
            $datos = [
                'tabla' => 'cliente',
                'codigo_tabla' => $cliente->id,
                'transaccion' => 'guardar',
            ];
            $this->guardarBitacora($datos);
              
    }

    public function modificar(ClienteRequest $request){
        $cliente= Cliente::findOrFail($request->id);
        $cliente->nombre=$request->nombre;
        $cliente->matricula=$request->matricula;
        $cliente->telefono=$request->telefono;
        $cliente->direccion=$request->direccion;
        $cliente->descripcion=$request->descripcion;
        $cliente->descuento=$request->id_descuento;
        $cliente->estado=$request->estado;
        $cliente->save();

        $datos = [
            'tabla' => 'cliente',
            'codigo_tabla' => $cliente->id,
            'transaccion' => 'modificar',
        ];
        $this->guardarBitacora($datos);
    }

    public function desactivar(Request $request){
        $cliente = Cliente::findOrFail($request->id);
        $cliente->estado = '0';
        $cliente->save();
    }

    public function activar(Request $request){
        $cliente = Cliente::findOrFail($request->id);
        $cliente->estado = '1';
        $cliente->save();
    }
    //seleccionar cliente por id
    public function selectCliente(Request $request){  
    // $obj = Cliente::select('id', 'nombre', 'descuento','direccion','telefono')->where('cliente.id','!=',1)->where('cliente.estado','=',1)->orderBy('cliente.id','desc')->get(); 
    //     return $obj;
        // $clientes = Cliente::select('id', 'nombre', 'descuento', 'direccion', 'telefono')
        //     ->where('id', '!=', 1)
        //     ->where('estado', '=', 1)
        //     ->orderBy('id', 'desc')
        //     ->paginate(20); // Trae 20 registros por página

        // return $clientes;

        $filtro = $request->filtro; // <--- Importante: Recibir el filtro

        $query = Cliente::select('id', 'nombre', 'descuento','direccion','telefono')
                ->where('id','!=',1)
                ->where('estado','=',1);

        if(!empty($filtro)){
            $query->where(function($q) use ($filtro){
                $q->where('nombre', 'like', '%'.$filtro.'%')
                ->orWhere('telefono', 'like', '%'.$filtro.'%'); // Opcional: buscar por teléfono también
            });
        }

        return $query->orderBy('id','desc')->paginate(20);
    }
    public function listarSinPaginate(Request $request){
        $buscar = $request->buscar;     
        if ($buscar==''){

            $cliente = Cliente::orderBy('cliente.id', 'desc')
            ->where('cliente.estado',1)->get();
        }
        else{
            $cliente = Cliente::orderBy('cliente.id', 'desc')
            ->where('cliente.estado',1)
            ->where('cliente.nombre' , 'like', '%'.$buscar.'%')->get();
        }
        return $cliente;
    }

    public function listarClienteId(Request $request){
        $id = $request->id_cliente;     
        $cliente = Cliente::orderBy('cliente.id', 'desc')->where('cliente.id' , 'like', '%'.$id.'%')->get();
        return $cliente;
    }

    public function cantidadRegistros(){
        $cantidad = DB::table('cliente')->where('cliente.id','!=',1)->count();

        $data=['nro' =>$cantidad];
        return $data;
    }

    public function pdfListarClientes(){
        $cliente= cliente::select('cliente.id','cliente.nombre','cliente.matricula','cliente.telefono',
            'cliente.descripcion','cliente.direccion')
            ->orderBy('cliente.nombre','desc')->get();
        $cont=cliente::count();
        $pdf = \PDF::loadView('pdf.venta.venta', ['clientes'=>$cliente, 'cont'=>$cont]);
        return $pdf->stream('clientes.pdf');
    }
    public function direccion(Request $request){
        $buscar = $request->buscar;
        $direccion=DB::select("SELECT direccion FROM cliente WHERE id=$buscar");
        return $direccion;
    }
    public function cliente_id(){
        $cliente_id = Cliente::orderBy('cliente.id', 'desc')->first();
        return ['id_cliente'=>$cliente_id];
    }

    public function buscarClientes(Request $request)
    {
        try {
            $criterio = $request->input('criterio', '');
            
            // Si no hay criterio, devolver todos los clientes activos
            if (empty($criterio)) {
                $clientes = Cliente::where('estado', 1)
                    ->orderBy('nombre', 'asc')
                    ->limit(50) // Limitar resultados iniciales
                    ->get(['id', 'nombre', 'matricula', 'telefono', 'direccion', 'descuento']);
                    
                return response()->json($clientes);
            }
            
            // Buscar por nombre o matrícula
            $clientes = Cliente::where('estado', 1)
                ->where(function($query) use ($criterio) {
                    $query->where('nombre', 'LIKE', '%' . $criterio . '%')
                          ->orWhere('matricula', 'LIKE', '%' . $criterio . '%');
                })
                ->orderBy('nombre', 'asc')
                ->limit(100) // Limitar resultados de búsqueda
                ->get(['id', 'nombre', 'matricula', 'telefono', 'direccion', 'descuento']);
            
            return response()->json($clientes);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al buscar clientes',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    

    public function obtenerCliente($id)
    {
        try {
            $cliente = Cliente::where('id', $id)
                ->where('estado', 1)
                ->first(['id', 'nombre', 'matricula', 'telefono', 'direccion', 'descuento']);
            
            if (!$cliente) {
                return response()->json([
                    'error' => 'Cliente no encontrado'
                ], 404);
            }
            
            return response()->json($cliente);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener cliente',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    

    public function listarClientes()
    {
        try {
            $clientes = Cliente::where('estado', 1)
                ->orderBy('nombre', 'asc')
                ->get(['id', 'nombre', 'matricula', 'telefono', 'direccion', 'descuento']);
            
            return response()->json($clientes);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al listar clientes',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

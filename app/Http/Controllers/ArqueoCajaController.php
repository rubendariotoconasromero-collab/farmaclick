<?php

namespace App\Http\Controllers;

use App\Models\MiEmpresa;
use App\Models\ArqueoCaja;
use Illuminate\Http\Request;
use DB;
use DateTime;

class ArqueoCajaController extends BitacoraController
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;   
        $fecha_inicio = $request->fecha_inicio;   
        $fecha_final = $request->fecha_final;   
        //dd($fecha_inicio,$fecha_final);
        // $objdate = new DateTime();
        // $fechaactual= $objdate->format('Y-m-d');
        // $hora= $objdate->format('H:i:s');

        // $mes = $objdate->format('m');  
  
        if ($buscar==''){
            $arqueo = DB::table('arqueo_caja as a')
            ->join('users as u', 'a.id_usuario', '=', 'u.id')
            ->select('a.id','a.fecha_apertura','a.fecha_cierre','a.apertura','a.registro_venta as ingreso','a.estado','a.gastos','a.total','a.registro_compra','a.saldo_sistema'
            ,'a.saldo_efectivo','a.diferencia','a.id_usuario',DB::raw('a.registro_compra + a.gastos + a.gastos_deposito as egreso')
            ,DB::raw('a.apertura + a.registro_venta - a.registro_compra- a.gastos as total_efectivo'),'a.doscientos','a.cien','a.cincuenta','a.veinte','a.diez','a.cinco','a.dos',
            'a.uno','a.cerocinco','a.ceroveinte','a.ceroveinte','a.cien_dolar','u.name','a.total_contado','a.total_credito','a.total_contado_compra','a.total_credito_compra'
            ,'a.total_contado_deposito','a.total_credito_deposito','a.total_contado_deposito_compra','a.total_credito_deposito_compra','a.gastos_deposito'
            ,DB::raw('a.total_contado + a.total_contado_deposito + a.total_credito + a.total_credito_deposito as total_ingreso_general')
            ,DB::raw('a.total_contado + a.total_credito as total_ingreso_efectivo')
            ,DB::raw('a.total_contado_compra + a.total_contado_deposito_compra + a.total_credito_compra + a.total_credito_deposito_compra + a.gastos + gastos_deposito  as total_egreso_general')
            ,DB::raw('a.total_contado_compra + a.total_credito_compra + gastos as total_egreso_efectivo')
            ,DB::raw('a.total_contado + a.total_credito + a.apertura as total_ingreso_efectivo1')
            )
            ->whereDate('a.fecha_apertura', ">=", $fecha_inicio)
            ->whereDate('a.fecha_apertura', "<=", $fecha_final)
            ->orderBy('a.id', 'desc')->paginate(100);

        }
        else{
            $arqueo = DB::table('arqueo_caja as a')
            ->join('users as u', 'a.id_usuario', '=', 'u.id')
            ->select('a.id','a.fecha_apertura','a.fecha_cierre','a.apertura','a.registro_venta as ingreso','a.estado','a.gastos','a.total','a.registro_compra','a.saldo_sistema'
            ,'a.saldo_efectivo','a.diferencia','a.id_usuario',DB::raw('a.registro_compra + a.gastos + a.gastos_deposito as egreso')
            ,DB::raw('a.apertura + a.registro_venta - a.registro_compra- a.gastos as total_efectivo'),'a.doscientos','a.cien','a.cincuenta','a.veinte','a.diez','a.cinco','a.dos'
            ,'a.uno','a.cerocinco','a.ceroveinte','a.ceroveinte','a.cien_dolar','u.name','a.total_contado','a.total_credito','a.total_contado_compra','a.total_credito_compra'
            ,'a.total_contado_deposito','a.total_credito_deposito','a.total_contado_deposito_compra','a.total_credito_deposito_compra','a.gastos_deposito'
            ,DB::raw('a.total_contado + a.total_contado_deposito + a.total_credito + a.total_credito_deposito as total_ingreso_general')
            ,DB::raw('a.total_contado + a.total_credito as total_ingreso_efectivo')
            ,DB::raw('a.total_contado_compra + a.total_contado_deposito_compra + a.total_credito_compra + a.total_credito_deposito_compra + a.gastos + gastos_deposito  as total_egreso_general')
            ,DB::raw('a.total_contado_compra + a.total_credito_compra + gastos as total_egreso_efectivo')
            ,DB::raw('a.total_contado + a.total_credito + a.apertura as total_ingreso_efectivo1')
            )
            ->where(''.$criterio, 'like', '%'.$buscar.'%')
            ->whereDate('a.fecha_apertura', ">=", $fecha_inicio)
            ->whereDate('a.fecha_apertura', "<=", $fecha_final)
            ->orderBy('a.id', 'desc')->paginate(100);
        }
        return $arqueo;
    }

    public function indexArqueo(Request $request){
        $id_usuario = \Auth::user()->id;

            $caja=DB::select("SELECT a.id as id, a.fecha_apertura, a.fecha_cierre, a.apertura,
            (a.registro_venta)as ingreso, (a.registro_compra)+(a.gastos)+(a.gastos_deposito)  as egreso
            ,((a.apertura)+(a.registro_venta))-((a.registro_compra)+(a.gastos)+(a.gastos_deposito)) as total_efectivo,
            u.name as name, a.estado , g.id as grupo
            from arqueo_caja a, users u, grupo g
            where a.id_usuario=u.id and u.id=$id_usuario and u.id_grupo=g.id and a.estado='Abierta'");

            // $caja=DB::select("SELECT a.id as id, a.fecha_apertura, a.fecha_cierre, a.apertura,
            // (a.registro_venta)as ingreso, (a.registro_compra)+(a.gastos)  as egreso
            // ,((a.apertura)+(a.registro_venta))-((a.registro_compra)+(a.gastos)) as total_efectivo,
            // u.name as name, a.estado , u.id as id_usuario
            // from arqueo_caja a, users u
            // where a.id_usuario=u.id and u.id=$id_usuario and a.estado='Abierta'");
        return $caja;
    }

    public function index2(Request $request){
        $id_usuario = \Auth::user()->id;
            $caja=DB::select("SELECT a.id as id,a.apertura as apertura,a.registro_venta as registro_venta,a.gastos as gastos,a.registro_compra as registro_compra,a.total_contado,
            a.total_credito,a.total_contado_compra,a.total_credito_compra,a.total_credito_deposito,a.total_contado_deposito,a.gastos_deposito,a.total_credito_deposito_compra,a.total_contado_deposito_compra
            from arqueo_caja a, users u
            where a.id_usuario=u.id and u.id=$id_usuario and a.estado='Abierta'");
        return $caja;
    }

    public function guardar(Request $request){
        
        $caja= new ArqueoCaja();
        
        $objdate = new DateTime();
        $fecha_apertura=$objdate;

        $caja->fecha_apertura=$fecha_apertura;
        $caja->apertura=$request->apertura;
        $caja->total_contado=0;
        $caja->total_contado_deposito=0;
        $caja->total_credito=0;
        $caja->total_credito_deposito=0;
        $caja->total_contado_compra=0;
        $caja->total_credito_compra=0;
        $caja->total_contado_deposito_compra=0;
        $caja->total_credito_deposito_compra=0;
        $caja->gastos_deposito=0;
        $caja->estado="Abierta";
        $caja->id_usuario=\Auth::user()->id;
        $caja->save();

        
        $datos = [
            'tabla' => 'caja',
            'codigo_tabla' => $caja->id,
            'transaccion' => 'apertura',
        ];
        $this->guardarBitacora($datos);
    }

    public function modificar(Request $request){
        $objdate = new DateTime();
        $fecha_cierre=$objdate;
        //dd($request->total_contado);
        $caja= ArqueoCaja::findOrFail($request->id);
        $caja->fecha_cierre=$fecha_cierre;
        $caja->doscientos=$request->doscientos;
        $caja->cien=$request->cien;
        $caja->cincuenta=$request->cincuenta;
        $caja->veinte=$request->veinte;
        $caja->diez=$request->diez;
        $caja->cinco=$request->cinco;
        $caja->dos=$request->dos;
        $caja->uno=$request->uno;
        $caja->cerocinco=$request->cerocinco;
        $caja->ceroveinte=$request->ceroveinte;
        $caja->cien_dolar=$request->cien_dolar;
        

        $caja->registro_venta=$request->total_contado + $request->total_contado_deposito;
        $caja->total=$request->registro_venta;
        $caja->gastos=$request->gastos;
        $caja->gastos_deposito =$request->gastos_deposito;
        $caja->registro_compra= $request->total_contado_compra + $request->total_contado_deposito_compra;
        $caja->saldo_sistema=$request->total_egreso_efectivo;
        $caja->saldo_efectivo=$request->saldo_total;
        $caja->diferencia =$request->diferencia;
        //venta
        $caja->total_contado =$request->total_contado;
        $caja->total_contado_deposito=$request->total_contado_deposito;
        $caja->total_credito = $request->total_credito;
        $caja->total_credito_deposito = $request->total_credito_deposito;

        //compra
        $caja->total_contado_compra =$request->total_contado_compra;
        $caja->total_contado_deposito_compra =$request->total_contado_deposito_compra;
        $caja->total_credito_compra = $request->total_credito_compra;
        $caja->total_credito_deposito_compra = $request->total_credito_deposito_compra;


        //$caja->efectivo_total =$request->efectivo_total;







        $caja->estado='Cerrada';
        $caja->id_usuario=\Auth::user()->id;
        $caja->save();

        $datos = [
            'tabla' => 'caja',
            'codigo_tabla' => $caja->id,
            'transaccion' => 'arqueo de caja',
        ];
        $this->guardarBitacora($datos);
    }

    public function cantidadRegistros(){
        $cantidad = DB::table('mi_empresa')->count();

        $data=['nro' =>$cantidad];
        return $data;
    }

    public function estadoArqueoCaja(){
        $id_usuario=\Auth::user()->id;
        $estado_caja = DB::table('arqueo_caja')->select('estado')->where('id_usuario','=',$id_usuario)->where('estado','=','Abierta')->get();
        if($estado_caja->isEmpty()){
            //$obj=['estado_caja' =>'Sin Caja'];
            $data=(object) ['estado'=>'Cerrada'];
        } else {
            //$obj=['estado_caja' =>$estado_caja];
            $data=(object) $estado_caja[0];
        }
        return $data;
    }

    public function estadoCaja(Request $request){
        $id_usuario = \Auth::user()->id;
            $caja=DB::select("SELECT a.estado
            from arqueo_caja a, users u
            where a.id_usuario=u.id and u.id=$id_usuario and a.estado='Abierta'");

        return $caja;
    }
    public function actualizarVentasAlCerrarCaja(Request $request){
        DB::beginTransaction();
        $id_usuario=\Auth::user()->id;
        try {
            
            DB::table('venta')
            ->join('users', 'venta.id_usuario', '=', 'users.id')
            ->where('venta.control', 0)
            ->where('venta.id_usuario','=', $id_usuario)
            ->update(['venta.control' => $request->id_caja]);

            $affected = DB::table('gasto')
            ->where('control', 0)
            ->where('id_usuario','=', $id_usuario)
            ->update(['control' => $request->id_caja]); 

            $affected = DB::table('compra')
            ->where('control', 0)
            ->where('id_usuario','=', $id_usuario)
            ->update(['control' => $request->id_caja]); 
            
            $affected = DB::table('c_x_cobrar')
            ->where('control', 0)
            ->where('id_usuario','=', $id_usuario)
            ->update(['control' => $request->id_caja]);
            
            $affected = DB::table('c_x_pagar')
            ->where('control', 0)
            ->where('id_usuario','=', $id_usuario)
            ->update(['control' => $request->id_caja]); 


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return ['error'=>$e->getMessage()];
        }
    }
}

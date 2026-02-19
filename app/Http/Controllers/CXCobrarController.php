<?php

namespace App\Http\Controllers;
use App\Models\CXCobrar;
use App\Models\Pago;
use App\Models\ArqueoCaja;
use DB;

use Illuminate\Http\Request;

class CXCobrarController extends Controller
{
    public function index(Request $request){
        $buscar = $request->buscar;      
            $c_x_cobrar = CXCobrar::where('c_x_cobrar.id_pago', '=', $buscar)
            ->orderBy('c_x_cobrar.id_pago', 'desc')->get();
        return $c_x_cobrar;
    }

    public function detallePagoCredito(Request $request){
        $id_pago = $request->id_pago;  
        $detalle_credito=DB::select("SELECT c_x_cobrar.id, c_x_cobrar.id_pago, c_x_cobrar.fecha, c_x_cobrar.monto_total, c_x_cobrar.amortizacion, c_x_cobrar.saldo, c_x_cobrar.descripcion ,forma_pago.nombre as forma 
        FROM c_x_cobrar LEFT JOIN forma_pago 
        ON c_x_cobrar.id_forma_pago=forma_pago.id
        where c_x_cobrar.id_pago = $id_pago");
        return $detalle_credito;
    }

    private function actualizarCaja($id_usuario,$monto){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.registro_venta', $monto);
    }
    private function actualizarCajaCredito($id_usuario,$monto){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.total_credito', $monto);
    }

    private function actualizarCajaCreditoDeposito($id_usuario,$monto){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.total_credito_deposito', $monto);
    }

    public function guardar(Request $request){
        try{
            DB::beginTransaction();
                $registro_venta = $request->amortizacion;
                $id_usuario=\Auth::user()->id;
                $c_x_cobrar = new CXCobrar();
                $c_x_cobrar->fecha = $request->fecha;
                $c_x_cobrar->monto_total = $request->monto_total;
                $c_x_cobrar->amortizacion = $request->amortizacion;
                $c_x_cobrar->descripcion = "";
                $c_x_cobrar->id_pago = $request->id_pago;
                $c_x_cobrar->saldo = $request->saldo -$request->amortizacion;
                $c_x_cobrar->id_usuario = $id_usuario;
                $c_x_cobrar->id_forma_pago = $request->id_forma_pago;
                $c_x_cobrar->save(); 

                $obj= Pago::findOrFail($request->id_pago);
                $obj->saldo=$request->saldo -$request->amortizacion;
                $obj->save(); 
                //DD($request->id_pago);
                if($c_x_cobrar->saldo == 0) {
                    DB::table('venta')
                    ->where('id',$request->id_pago)
                    ->update(['estado' => 'Cancelado']); 
                }
                // if($request->id_forma_pago == 2){
                //     $this->actualizarCaja($id_usuario,$registro_venta);
                //     $this->actualizarCajaCredito($id_usuario,$registro_venta);
                // }
                // if($request->id_forma_pago == 3 || $request->id_forma_pago == 4 || $request->id_forma_pago == 5){
                //     $this->actualizarCaja($id_usuario,$registro_venta);
                //     $this->actualizarCajaCreditoDeposito($id_usuario,$registro_venta);
                // }
            
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
    public function VentaCobrarArqueoEfectivo(Request $request){

        $id_usuario=\Auth::user()->id;

        $compra=CXCobrar::join('pago','c_x_cobrar.id_pago','=','pago.id')
        ->join('venta','pago.id_venta','=','venta.id')
        ->select(DB::raw('SUM(c_x_cobrar.amortizacion) as total_e'))
        ->where('c_x_cobrar.id_forma_pago','=',2)
        ->where('c_x_cobrar.control','=',0)
        ->where('venta.estado','!=','Anulado')
        ->where('c_x_cobrar.id_usuario','=',$id_usuario)
        ->get();
        $data=(object) $compra;
        return $data;
        
    }
    public function VentaCobrarArqueoDeposito(Request $request){
        
        $id_usuario=\Auth::user()->id;
        
        $compra=CXCobrar::join('pago','c_x_cobrar.id_pago','=','pago.id')
        ->join('venta','pago.id_venta','=','venta.id')
        ->select(DB::raw('SUM(c_x_cobrar.amortizacion) as total_d'))
        ->whereIn('c_x_cobrar.id_forma_pago',[3,4,5])
        ->where('c_x_cobrar.control','=',0)
        ->where('venta.estado','!=','Anulado')
        ->where('c_x_cobrar.id_usuario','=',$id_usuario)
        ->get();
        $data=(object) $compra;
        return $data;
        
    }
}

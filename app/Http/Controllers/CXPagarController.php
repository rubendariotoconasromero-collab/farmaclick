<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CXPagar;
use App\Models\PagoCompra;
use App\Models\ArqueoCaja;
use DB;


class CXPagarController extends Controller
{
    public function index(Request $request){
        $buscar = $request->buscar;      
            $c_x_pagar = CXPagar::where('c_x_pagar.id_pago', '=', $buscar)
            ->orderBy('c_x_pagar.id_pago', 'desc')->get();
        return $c_x_pagar;
    }

    public function detallePagoCreditoC(Request $request){
        $id_pago = $request->id_pago;  
        $detalle_credito=DB::select("SELECT c_x_pagar.id, c_x_pagar.id_pago, c_x_pagar.fecha, c_x_pagar.monto_total, c_x_pagar.amortizacion, c_x_pagar.saldo, c_x_pagar.descripcion,forma_pago.nombre as formaP 
        FROM c_x_pagar LEFT JOIN forma_pago 
        ON c_x_pagar.id_forma_pago=forma_pago.id 
        where c_x_pagar.id_pago = $id_pago");
        return $detalle_credito;
    }

    private function actualizarCaja($id_usuario,$monto){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.registro_compra', $monto);
    }
    private function actualizarCajaCredito($id_usuario,$monto){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.total_credito_compra', $monto);
    }
    private function actualizarCajaCreditoDeposito($id_usuario,$monto){
        DB::table('arqueo_caja')->where('arqueo_caja.id_usuario','=',$id_usuario)
        ->where('arqueo_caja.estado','=','Abierta')
        ->increment('arqueo_caja.total_credito_deposito_compra', $monto);
    }

    public function guardar(Request $request){
        try{
            DB::beginTransaction();
                $registro_venta = $request->amortizacion;
                $id_usuario=\Auth::user()->id;
                //dd($request->id_pago);
                $c_x_cobrar = new CXPagar();
                $c_x_cobrar->fecha = $request->fecha;
                $c_x_cobrar->monto_total = $request->monto_total;
                $c_x_cobrar->amortizacion = $request->amortizacion;
                $c_x_cobrar->descripcion = "";
                $c_x_cobrar->id_pago = $request->id_pago;
                $c_x_cobrar->saldo = $request->saldo -$request->amortizacion;
                $c_x_cobrar->id_forma_pago = $request->id_forma_pago;
                $c_x_cobrar->id_usuario = $id_usuario;
                $c_x_cobrar->save(); 

                $obj= PagoCompra::findOrFail($request->id_pago);
                $obj->saldo=$request->saldo -$request->amortizacion;
                $obj->save(); 

                if($c_x_cobrar->saldo == 0) {
                    DB::table('compra')
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
    public function CompraCobrarArqueoEfectivo(Request $request){

        $id_usuario=\Auth::user()->id;

        $compra=CXPagar::join('pago_compra','c_x_pagar.id_pago','=','pago_compra.id')
        ->join('compra','pago_compra.id_compra','=','compra.id')
        ->select(DB::raw('SUM(c_x_pagar.amortizacion) as total_e'))
        ->where('c_x_pagar.id_forma_pago','=',2)
        ->where('c_x_pagar.control','=',0)
        ->where('compra.estado','!=','Anulado')
        ->where('c_x_pagar.id_usuario','=',$id_usuario)
        ->get();
        $data=(object) $compra;
        return $data;
        
    }
    public function CompraCobrarArqueoDeposito(Request $request){
        
        $id_usuario=\Auth::user()->id;
        
        $compra=CXPagar::join('pago_compra','c_x_pagar.id_pago','=','pago_compra.id')
        ->join('compra','pago_compra.id_compra','=','compra.id')
        ->select(DB::raw('SUM(c_x_pagar.amortizacion) as total_d'))
        ->whereIn('c_x_pagar.id_forma_pago',[3,4,5])
        ->where('c_x_pagar.control','=',0)
        ->where('compra.estado','!=','Anulado')
        ->where('c_x_pagar.id_usuario','=',$id_usuario)
        ->get();
        $data=(object) $compra;
        return $data;
        
    }
}

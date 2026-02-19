<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PagoCompra;
use DB;

class PagoCompraController extends Controller
{
    public function index(Request $request){
        $buscar = $request->buscar;  
            $pago = PagoCompra::where('pago_compra.id', '=', $buscar)
            ->orderBy('pago_compra.id', 'desc')->get();
        return $pago;
    }

    public function guardar(Request $request){
        try{
            DB::beginTransaction();

                $pago = new PagoCompra();
                $pago->fecha = $request->fecha;
                $pago->monto = $request->total;
                $pago->descripcion = "";
                $pago->id_tipo_pago = $request->id_tipo_pago;
                //$pago->id_c_x_cobrar = 1;
                $pago->save();  
            
            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function index2C(Request $request){
        $buscar = $request->buscar;
            $cuota_venta=DB::select("SELECT p.id, p.fecha,p.fecha_final,p.monto,p.id_tipo_pago,p.id_compra,p.descripcion 
            FROM c_x_pagar u, compra v, pago_compra p 
            where v.id=p.id_compra and u.id_pago=p.id and v.id=$buscar");
        return $cuota_venta;
    }

    public function pagos_proveedor(Request $request){
        $buscar = $request->buscar;
        $cuota=DB::select("SELECT v.id,g.fecha as fecha,c.telefono,(g.monto) as monto,(g.monto)-(SUM(u.amortizacion)) as saldo 
        FROM proveedor c, pago_compra g, compra v, c_x_pagar u 
        where v.id_proveedor=c.id and u.id_pago=g.id and g.id_compra=v.id AND v.estado!='Anulado' AND g.id_tipo_pago=2 /* AND g.saldo > 0 */ AND c.nombre='$buscar'
        GROUP By v.id,g.fecha,c.telefono,g.monto");
        return $cuota;
    }


    public function indexPagoArticulo(Request $request){
        $buscar = $request->buscar;  
        $pago=DB::select("SELECT p.id, p.fecha, p.fecha_final, p.monto, p.estado, p.saldo, p.id_tipo_pago, p.id_venta, c.nombre as cliente, t.nombre tienda, tp.nombre as tipo_pago, fp.nombre as forma_pago, v.tipo_venta FROM pago as p, venta as v, cliente as c, tienda as t, tipo_pago as tp, forma_pago as fp where p.id_venta = v.id and v.id_cliente = c.id and v.id_tienda = t.id and v.id_tipo_pago = tp.id and v.id_forma_pago = fp.id and tp.nombre = 'Credito'");
        return $pago;
    }

    public function indexPagoServicio(Request $request){
        $buscar = $request->buscar;  
        $pago=DB::select("SELECT p.id, p.fecha, p.fecha_final, p.monto, p.estado, p.saldo, p.id_tipo_pago, p.id_venta, c.nombre as cliente, t.nombre tienda, tp.nombre as tipo_pago, fp.nombre as forma_pago, v.FROM pago as p, venta as v, cliente as c, tienda as t, tipo_pago as tp, forma_pago as fp where p.id_venta = v.id and v.id_cliente = c.id and v.id_tienda = t.id and v.id_tipo_pago = tp.id and v.id_forma_pago = fp.id and tp.nombre = 'Credito'");
        return $pago;
    }
}

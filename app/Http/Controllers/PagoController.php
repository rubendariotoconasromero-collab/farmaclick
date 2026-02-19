<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use DB;

class PagoController extends Controller
{

    public function index(Request $request){
        $buscar = $request->buscar;  
            $pago = Pago::where('pago.id', '=', $buscar)
            ->orderBy('pago.id', 'desc')->get();
        return $pago;
    }

    public function guardar(Request $request){
        try{
            DB::beginTransaction();

                $pago = new Pago();
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

    public function index2(Request $request){
        $buscar = $request->buscar;
            $cuota_venta=DB::select("SELECT p.id, p.fecha,p.fecha_final,p.monto,p.id_tipo_pago,p.id_venta,p.descripcion 
            FROM c_x_cobrar u, venta v, pago p 
            where v.id=p.id_venta and u.id_pago=p.id and v.id=$buscar");
        return $cuota_venta;
    }

    public function pagos_cliente(Request $request){
        $buscar = $request->buscar;
        $id_tienda = $request->id_tienda;
        //$id_usuario = \Auth::user()->id;
        //dd($id_usuario);
        $tipo_producto = $request->tipo_producto;
            $cuota=DB::select("SELECT v.id,g.fecha as fecha,c.telefono,c.matricula,(g.monto) as monto,g.saldo ,v.id_usuario
            FROM cliente c, pago g, venta v, c_x_cobrar u 
            where v.id_cliente=c.id and u.id_pago=g.id and g.id_venta=v.id AND v.estado!='Anulado' AND g.id_tipo_pago=2 
            AND c.nombre='$buscar' AND v.id_tienda='$id_tienda'
            AND v.tipo_venta BETWEEN 'Venta Cotizacion' 
            AND '$tipo_producto' 
            
            GROUP By v.id,g.fecha,c.telefono,g.monto,c.matricula,v.id_usuario");
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

    // public function indexPagoTiendaArticulo1(Request $request){
    //     $id_usuario = \Auth::user()->id;
        
    //     $buscar = $request->buscar;
    //     $criterio = $request->criterio;

        // if($buscar==''){
        //     $pago= Pago::join('venta','pago.id_venta','=','venta.id')
        //     ->join('users','venta.id_usuario','=','users.id')
        //     ->join('cliente','venta.id_cliente','=','cliente.id')
        //     ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
        //     ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
        //     ->join('tienda','venta.id_tienda','=','tienda.id')

        //     ->select('pago.id','pago.fecha','pago.fecha_final','pago.monto','pago.estado','pago.saldo',
        //     'pago.id_tipo_pago','pago.id_venta','venta.tipo_venta','venta.total','venta.descuento','venta.sub_total','venta.estado','venta.fecha','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
        //     'tienda.id as id_tienda','tienda.nombre as tienda')
        //     ->where('venta.tipo_venta','=','Venta Directa')
        //     ->where('venta.id_tipo_pago','=',2)
        //     ->orderBy('venta.id','desc')->paginate(15);
        // }
        // else{
        //     $obj= Venta::join('users','venta.id_usuario','=','users.id')
        //     ->join('cliente','venta.id_cliente','=','cliente.id')
        //     ->join('tipo_pago','venta.id_tipo_pago','=','tipo_pago.id')
        //     ->join('forma_pago','venta.id_forma_pago','=','forma_pago.id')
        //     ->join('tienda','venta.id_tienda','=','tienda.id')
        //     ->select('venta.id','venta.fecha','venta.sub_total','venta.descuento','venta.tipo_venta',
        //     'venta.total','venta.estado','users.name','cliente.nombre as cliente','cliente.descuento as id_descuento','tipo_pago.nombre as tipoP','forma_pago.nombre as formaP','forma_pago.nombre as formaP',
        //     'tienda.id as id_tienda','tienda.nombre as tienda')
        //     ->where('venta.id_tipo_pago','=',2)
        //     ->where($criterio, 'like', '%'.$buscar.'%')
        //     ->orderBy('venta.id','desc')->paginate(15);            
        // }

        // public function indexPagoTiendaArticulo1(Request $request){
        //     $id_usuario = \Auth::user()->id;
        //     $page = $request->page;
        //     $buscar = $request->buscar;
        //     $criterio = $request->criterio;

        //     if ($buscar==''){
        //         $pago=DB::select("SELECT p.id, p.fecha, p.fecha_final, p.monto, p.estado, p.saldo, p.id_tipo_pago, p.id_venta, t.nombre tienda, tp.nombre as tipo_pago, fp.nombre as forma_pago,t.id as tienda_id, u.id as id_user, u.name, c.descuento as id_descuento, v.tipo_venta, v.total, v.descuento, v.sub_total, v.estado, v.fecha as venta_fecha, v.id_cliente as id_cliente, c.nombre as cliente FROM pago as p, venta as v, cliente as c, tienda as t, tipo_pago as tp, forma_pago as fp, users as u where p.id_venta = v.id and v.id_cliente = c.id and v.id_tienda = t.id and v.id_tipo_pago = tp.id and v.id_forma_pago = fp.id and v.id_usuario=u.id and tp.nombre = 'Credito' and v.tipo_venta='Venta Directa' and p.saldo != 0 and t.id = 2 and u.id = $id_usuario GROUP BY c.nombre ORDER BY p.id  desc");
        //     }else{
        //         $pago=DB::select("SELECT p.id, p.fecha, p.fecha_final, p.monto, p.estado, p.saldo, p.id_tipo_pago, p.id_venta, t.nombre tienda, tp.nombre as tipo_pago, fp.nombre as forma_pago,t.id as tienda_id, u.id as id_user, u.name, c.descuento as id_descuento, v.tipo_venta, v.total, v.descuento, v.sub_total, v.estado, v.fecha as venta_fecha, v.id_cliente as id_cliente, c.nombre as cliente FROM pago as p, venta as v, cliente as c, tienda as t, tipo_pago as tp, forma_pago as fp, users as u where p.id_venta = v.id and v.id_cliente = c.id and v.id_tienda = t.id and v.id_tipo_pago = tp.id and v.id_forma_pago = fp.id and v.id_usuario=u.id and tp.nombre = 'Credito' and v.tipo_venta='Venta Directa' and p.saldo != 0 and t.id = 2 and u.id = $id_usuario and $criterio = '$buscar' GROUP BY c.cliente  ORDER BY p.id  desc");
        //     }
            
        //     return $pago;
        // }

        // public function indexPagoTiendaArticuloDetalle1(Request $request){
        //     $id_usuario = \Auth::user()->id;
        //     $page = $request->page;
        //     $buscar = $request->buscar;

        //         $pago=DB::select("SELECT p.id, p.fecha, p.fecha_final, p.monto, p.estado, p.saldo, p.id_tipo_pago, p.id_venta, t.nombre tienda, tp.nombre as tipo_pago, fp.nombre as forma_pago,t.id as tienda_id, u.id as id_user, u.name, c.descuento as id_descuento, v.tipo_venta, v.total, v.descuento, v.sub_total, v.estado, v.fecha as venta_fecha, v.id_cliente as id_cliente, c.nombre as cliente FROM pago as p, venta as v, cliente as c, tienda as t, tipo_pago as tp, forma_pago as fp, users as u where p.id_venta = v.id and v.id_cliente = c.id and v.id_tienda = t.id and v.id_tipo_pago = tp.id and v.id_forma_pago = fp.id and v.id_usuario=u.id and tp.nombre = 'Credito' and v.tipo_venta='Venta Directa' and p.saldo != 0 and t.id = 2 and u.id = $id_usuario and v.id_cliente = '$buscar'  ORDER BY p.id  desc");
            
        //     return $pago;
        // }




    //     if ($buscar==''){
    //         $pago=DB::select("SELECT p.id, p.fecha, p.fecha_final, p.monto, p.estado, p.saldo, p.id_tipo_pago, p.id_venta, c.nombre as cliente, t.nombre tienda, tp.nombre as tipo_pago, fp.nombre as forma_pago,t.id as tienda_id, u.id as id_cliente, u.name, c.descuento as id_descuento, v.tipo_venta, v.total, v.descuento, v.sub_total, v.estado, v.fecha as venta_fecha, v.id_cliente as id_cliente FROM pago as p, venta as v, cliente as c, tienda as t, tipo_pago as tp, forma_pago as fp, users as u where p.id_venta = v.id and v.id_cliente = c.id and v.id_tienda = t.id and v.id_tipo_pago = tp.id and v.id_forma_pago = fp.id and v.id_usuario=u.id and tp.nombre = 'Credito' and v.tipo_venta='Venta Directa' and p.saldo != 0 and t.id = 2 and u.id = $id_usuario  ORDER BY p.id  desc");
    //     }else{
    //         $pago=DB::select("SELECT p.id, p.fecha, p.fecha_final, p.monto, p.estado, p.saldo, p.id_tipo_pago, p.id_venta, c.nombre as cliente, t.nombre tienda, tp.nombre as tipo_pago, fp.nombre as forma_pago,t.id as tienda_id, u.id as id_cliente, u.name, c.descuento as id_descuento, v.tipo_venta, v.total, v.descuento, v.sub_total, v.estado, v.fecha as venta_fecha, v.id_cliente as id_cliente FROM pago as p, venta as v, cliente as c, tienda as t, tipo_pago as tp, forma_pago as fp, users as u where p.id_venta = v.id and v.id_cliente = c.id and v.id_tienda = t.id and v.id_tipo_pago = tp.id and v.id_forma_pago = fp.id and v.id_usuario=u.id and tp.nombre = 'Credito' and v.tipo_venta='Venta Directa' and p.saldo != 0 and t.id = 2 and u.id = $id_usuario and c.nombre = $buscar  ORDER BY p.id  desc");
    //     }
        
    //     return $pago;
    // }

    public function indexPagoTiendaArticulo1(Request $request){
        $id_usuario = \Auth::user()->id;
        $buscar = $request->buscar;  
        $pago=DB::select("SELECT p.id, p.fecha, p.fecha_final, p.monto, p.estado, p.saldo, p.id_tipo_pago, p.id_venta, c.nombre as cliente, t.nombre tienda, tp.nombre as tipo_pago, fp.nombre as forma_pago,t.id as tienda_id, u.id as id_cliente, u.name, c.descuento as id_descuento, v.tipo_venta, v.total, v.descuento, v.sub_total, v.estado, v.fecha as venta_fecha FROM pago as p, venta as v, cliente as c, tienda as t, tipo_pago as tp, forma_pago as fp, users as u where p.id_venta = v.id and v.id_cliente = c.id and v.id_tienda = t.id and v.id_tipo_pago = tp.id and v.id_forma_pago = fp.id and v.id_usuario=u.id and tp.nombre = 'Credito' and v.tipo_venta='Venta Directa' and v.estado != 'Anulado' and p.saldo != 0 and t.id = 2 and u.id = $id_usuario  ORDER BY p.id  desc");
        return $pago;
    }



    public function indexPagoTiendaArticulo2(Request $request){
        $id_usuario = \Auth::user()->id;
        $buscar = $request->buscar;  
        $pago=DB::select("SELECT p.id, p.fecha, p.fecha_final, p.monto, p.estado, p.saldo, p.id_tipo_pago, p.id_venta, c.nombre as cliente, t.nombre tienda, tp.nombre as tipo_pago, fp.nombre as forma_pago,t.id as tienda_id, u.id as id_cliente, u.name, c.descuento as id_descuento, v.tipo_venta, v.total, v.descuento, v.sub_total, v.estado, v.fecha as venta_fecha FROM pago as p, venta as v, cliente as c, tienda as t, tipo_pago as tp, forma_pago as fp, users as u where p.id_venta = v.id and v.id_cliente = c.id and v.id_tienda = t.id and v.id_tipo_pago = tp.id and v.id_forma_pago = fp.id and v.id_usuario=u.id and tp.nombre = 'Credito' and v.tipo_venta='Venta Directa' and v.estado != 'Anulado' and p.saldo != 0 and t.id = 3 and u.id = $id_usuario  ORDER BY p.id  desc");
        return $pago;
    }


    public function indexPagoTiendaArticulo3(Request $request){
        $id_usuario = \Auth::user()->id;
        $buscar = $request->buscar;  
        $pago=DB::select("SELECT p.id, p.fecha, p.fecha_final, p.monto, p.estado, p.saldo, p.id_tipo_pago, p.id_venta, c.nombre as cliente, t.nombre tienda, tp.nombre as tipo_pago, fp.nombre as forma_pago,t.id as tienda_id, u.id as id_cliente, u.name, c.descuento as id_descuento, v.tipo_venta, v.total, v.descuento, v.sub_total, v.estado, v.fecha as venta_fecha FROM pago as p, venta as v, cliente as c, tienda as t, tipo_pago as tp, forma_pago as fp, users as u where p.id_venta = v.id and v.id_cliente = c.id and v.id_tienda = t.id and v.id_tipo_pago = tp.id and v.id_forma_pago = fp.id and v.id_usuario=u.id and tp.nombre = 'Credito' and v.tipo_venta='Venta Directa' and v.estado != 'Anulado' and p.saldo != 0 and t.id = 4 and u.id = $id_usuario  ORDER BY p.id  desc");
        return $pago;
    }

    public function indexPagoTiendaServicio1(Request $request){
        $id_usuario = \Auth::user()->id;
        
        $buscar = $request->buscar;
        $criterio = $request->criterio;

       $pago=DB::select("SELECT p.id, p.fecha, p.fecha_final, p.monto, p.estado, p.saldo, p.id_tipo_pago, p.id_venta, c.nombre as cliente, t.nombre tienda, tp.nombre as tipo_pago, fp.nombre as forma_pago,t.id as tienda_id, u.id as id_cliente, u.name, c.descuento as id_descuento, v.tipo_venta, v.total, v.descuento, v.sub_total, v.estado, v.fecha as venta_fecha FROM pago as p, venta as v, cliente as c, tienda as t, tipo_pago as tp, forma_pago as fp, users as u where p.id_venta = v.id and v.id_cliente = c.id and v.id_tienda = t.id and v.id_tipo_pago = tp.id and v.id_forma_pago = fp.id and v.id_usuario=u.id and tp.nombre = 'Credito' and v.tipo_venta='Venta Servicio' and v.estado != 'Anulado' and p.saldo != 0 and t.id = 2 and u.id = $id_usuario  ORDER BY p.id  desc");
        return $pago;
    }


    public function indexPagoTiendaServicio2(Request $request){
        $id_usuario = \Auth::user()->id;
        $buscar = $request->buscar;  
        $pago=DB::select("SELECT p.id, p.fecha, p.fecha_final, p.monto, p.estado, p.saldo, p.id_tipo_pago, p.id_venta, c.nombre as cliente, t.nombre tienda, tp.nombre as tipo_pago, fp.nombre as forma_pago,t.id as tienda_id, u.id as id_cliente, u.name, c.descuento as id_descuento, v.tipo_venta, v.total, v.descuento, v.sub_total, v.estado, v.fecha as venta_fecha FROM pago as p, venta as v, cliente as c, tienda as t, tipo_pago as tp, forma_pago as fp, users as u where p.id_venta = v.id and v.id_cliente = c.id and v.id_tienda = t.id and v.id_tipo_pago = tp.id and v.id_forma_pago = fp.id and v.id_usuario=u.id and tp.nombre = 'Credito' and v.tipo_venta='Venta Servicio' and v.estado != 'Anulado' and p.saldo != 0 and t.id = 3 and u.id = $id_usuario  ORDER BY p.id  desc");
        return $pago;
    }


    public function indexPagoTiendaServicio3(Request $request){
        $id_usuario = \Auth::user()->id;
        $buscar = $request->buscar;  
        $pago=DB::select("SELECT p.id, p.fecha, p.fecha_final, p.monto, p.estado, p.saldo, p.id_tipo_pago, p.id_venta, c.nombre as cliente, t.nombre tienda, tp.nombre as tipo_pago, fp.nombre as forma_pago,t.id as tienda_id, u.id as id_cliente, u.name, c.descuento as id_descuento, v.tipo_venta, v.total, v.descuento, v.sub_total, v.estado, v.fecha as venta_fecha FROM pago as p, venta as v, cliente as c, tienda as t, tipo_pago as tp, forma_pago as fp, users as u where p.id_venta = v.id and v.id_cliente = c.id and v.id_tienda = t.id and v.id_tipo_pago = tp.id and v.id_forma_pago = fp.id and v.id_usuario=u.id and tp.nombre = 'Credito' and v.tipo_venta='Venta Servicio' and v.estado != 'Anulado' and p.saldo != 0 and t.id = 4 and u.id = $id_usuario  ORDER BY p.id  desc");
        return $pago;
    }
}

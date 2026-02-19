<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormaPago;

class FormaPagoController extends Controller
{
    public function index(Request $request){
        $buscar = $request->buscar;
        $criterio = $request->criterio;       
        if ($buscar==''){
            $forma_pago = FormaPago::orderBy('forma_pago.id', 'desc')->paginate(15);
        }
        else{
            $forma_pago = FormaPago::where('forma_pago.'.$criterio, 'like', '%'.$buscar.'%')
            ->orderBy('forma_pago.id', 'desc')->paginate(15);
        }
        return $forma_pago;
    }

    /* public function index(Request $request){
        $buscar = $request->buscar;
            $cuota=DB::select("SELECT u.id, u.id_pago,u.fecha,u.monto_total,u.amortizacion,u.saldo,u.descripcion 
            FROM cuota u, compra c, pago p 
            where c.id=p.id_compra and u.id_pago=p.id and c.id=$buscar");
        return $cuota;
    } */

    public function selectFormaP(){  
        $obj = FormaPago::select('id', 'nombre')->orderBy('forma_pago.id','asc')->get(); 
        return $obj;
    }

    public function selectFormaPago(){  
        $obj = FormaPago::select('id', 'nombre')->whereIn('id',[2,3,4,5,6])->orderBy('forma_pago.id','asc')->get(); 
        return $obj;
    }
    public function selectFormaPago2(){  
        $obj = FormaPago::select('id', 'nombre')->whereIn('id',[2,3,4,5])->orderBy('forma_pago.id','asc')->get(); 
        return $obj;
    }
}

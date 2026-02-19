<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoPago;

class TipoPagoController extends Controller
{
    public function selectTipoP(){  
        $obj = TipoPago::select('id', 'nombre')->orderBy('tipo_pago.id','asc')->get(); 
        return $obj;
    }
}

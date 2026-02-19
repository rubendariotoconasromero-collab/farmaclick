<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bitacora;
use Carbon\Carbon;

class BitacoraController extends Controller
{
    public function guardarBitacora($datos=[]){
        $myDate= Carbon::now();
        $mytime= Carbon::now();
        //$mytime=$mytime->addHours(5);
        
        $obj = new Bitacora();
        $obj->fecha = $myDate->toDateString();
        $obj->hora = $mytime->toTimeString();
        $obj->tabla = $datos['tabla'];
        $obj->codigo_tabla = $datos['codigo_tabla'];
        $obj->transaccion = $datos['transaccion'];
        $obj->id_usuario =\Auth::user()->id;
        $obj->save();
    }
}

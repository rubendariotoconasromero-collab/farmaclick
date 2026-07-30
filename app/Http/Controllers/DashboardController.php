<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Lote;


class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $año=date('Y');
        $compra=DB::table('compra as nc')
        ->select(DB::raw('MONTH(nc.fecha) as mes'),
        DB::raw('YEAR(nc.fecha) as año'),
        DB::raw('SUM(nc.total) as total'))
        ->whereYear('nc.fecha', $año)
        ->where('nc.estado','!=','Anulado')
        ->groupBy(DB::raw('MONTH(nc.fecha)'),DB::raw('YEAR(nc.fecha)'))
        //->orderBy('SUM(nc.total)', 'limit 5')
        ->get();

        $venta=DB::table('venta as nv')
        ->join('tienda as tn','nv.id_tienda','=','tn.id')
        ->select(DB::raw('YEAR(nv.fecha) as año'),
        DB::raw('SUM(nv.total) as total'),
        'nv.id_tienda','tn.nombre as tienda')
        ->whereYear('nv.fecha', $año)
        ->where('nv.estado','!=','Anulado')
        ->groupBy(DB::raw('YEAR(nv.fecha)'),'nv.id_tienda')
        ->get();

        $venta_directa=DB::table('venta as nv')
        ->join('tienda as tn','nv.id_tienda','=','tn.id')
        ->select(DB::raw('YEAR(nv.fecha) as año'),
        DB::raw('SUM(nv.total) as total'),
        'nv.id_tienda','tn.nombre as tienda')
        ->whereYear('nv.fecha', $año)
        ->where('nv.tipo_venta','=','Venta Directa')
        ->where('nv.estado','!=','Anulado')
        ->groupBy(DB::raw('YEAR(nv.fecha)'),'nv.id_tienda')
        ->get();

        $venta_servicio=DB::table('venta as nv')
        ->join('tienda as tn','nv.id_tienda','=','tn.id')
        ->select(DB::raw('YEAR(nv.fecha) as año'),
        DB::raw('SUM(nv.total) as total'),
        'nv.id_tienda','tn.nombre as tienda')
        ->whereYear('nv.fecha', $año)
        ->where('nv.tipo_venta','=','Venta Servicio')
        ->where('nv.estado','!=','Anulado')
        ->groupBy(DB::raw('YEAR(nv.fecha)'),'nv.id_tienda')
        ->get();

        return ['compra'=>$compra,'venta'=>$venta, 'venta_directa'=>$venta_directa, 'venta_servicio'=>$venta_servicio,'año'=>$año];
    }

    public function listarProductoMesDashboad(Request $request){
        $fecha1 = now()->toDateString();
        $fecha2 = now()->addDays(365)->toDateString();

        $tienda_articulo = Lote::join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
        ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
        ->select(
            'lote.id',
            'articulo.nombre_comercial as articulo',
            'lote.fecha_vecimiento',
            'proveedor.nombre as laboratorio',
            'unidad_medida.nombre as presentacion'
        )
        ->where('lote.cantidad', '!=', 0)
        ->where('lote.estado', '!=', 0)
        ->whereBetween('lote.fecha_vecimiento', [$fecha1, $fecha2])
        ->orderBy('lote.fecha_vecimiento', 'asc')
        ->get();

    return $tienda_articulo;
    }
    public function listarProductoMesesDashboad(Request $request){
        $fecha1 = now()->toDateString();
        $fecha2 = now()->addDays(90)->toDateString();

        $tienda_articulo = Lote::join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
        ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
        ->select(
            'lote.id',
            'articulo.nombre_comercial as articulo',
            'lote.fecha_vecimiento',
            'lote.cantidad as stock',
            'proveedor.nombre as laboratorio',
            'unidad_medida.nombre as presentacion'
        )
        ->where('lote.cantidad', '!=', 0)
        ->where('lote.estado', '!=', 0)
        ->whereBetween('lote.fecha_vecimiento', [$fecha1, $fecha2])
        ->orderBy('lote.fecha_vecimiento', 'asc')
        ->get();

    return $tienda_articulo;
    }
    // public function __invoke2(Request $request)
    // {


    //     $venta=DB::select("SELECT c.nombre_cliente cliente, SUM(v.total) total, '2022' año FROM cliente C, venta v 
    //     WHERE v.id_cliente=c.id AND c.estado!=0 AND v.estado!='Anulado'  GROUP BY c.nombre_cliente  ORDER BY SUM(v.total) DESC LIMIT 5");

    //     $compra=DB::select("SELECT p.nombre as proveedor, SUM(c.total) total FROM proveedor p, compra c
    //     WHERE c.id_proveedor=p.id AND p.estado!=0 AND c.estado!='Anulado' GROUP BY p.nombre ORDER BY SUM(c.total) DESC  LIMIT 5 ");

    //     //dd($compra,$venta);

    //     return ['compra'=>$compra,'venta'=>$venta,'año'=>2022];
    // }

    // public function __invoke3(Request $request)  
    // {
    //     $venta=DB::table('venta as v')
    //     ->join('cliente as c','v.id_cliente','=','c.id')
    //     ->select('c.nombre_cliente as cliente',
    //     DB::raw('SUM(v.total) as total'),'"2022" as año')
    //     ->where('c.estado','!=',0)
    //     ->where('v.estado','!=','Anulado')
    //     ->groupBy('c.nombre_cliente')
    //     ->orderBy('SUM(V.total)')
    //     ->get();

    //     $compra=DB::table('proveedor as p')
    //     ->join('compra as c','c.id_proveedor','=','p.id')
    //     ->select('p.nombre as proveedor',
    //     DB::raw('SUM(c.total) as total'))
    //     ->where('p.estado','!=',0)
    //     ->where('c.estado','!=','Anulado')
    //     ->groupBy('p.nombre')
    //     ->orderBy('SUM(c.total)')
    //     ->get();

    //     return ['compra'=>$compra,'venta'=>$venta,'año'=>2022];
    // }
}

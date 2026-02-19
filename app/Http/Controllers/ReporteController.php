<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Personal;
use App\Models\User;
use App\Models\Proveedor;
use App\Models\Paciente;
use App\Models\Producto;
use App\Models\Articulo;
use App\Models\Ajuste;
use App\Models\MiEmpresa;
use App\Models\Browser;
use DB;
use App\Models\Orden;
use App\Models\DetalleOrden;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proforma;
use App\Models\DetalleProforma;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\ArqueoCaja;
use App\Models\Gasto;
use App\Models\Pago;
use App\Models\PagoCompra;
use App\Models\traspaso;
use App\Models\DetalleTraspaso;

use App\Models\Tienda;
use App\Models\Lote;
use Mpdf\Mpdf;

class ReporteController extends BitacoraController
{

    public function pdfPersonal(Request $request){

        $obj= Personal::join('cargo','personal.id_cargo','=','cargo.id')
        ->select('personal.nombre','personal.direccion','personal.telefono','cargo.nombre as cargo')
        ->where('personal.estado','!=','0')
        ->get();

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto', 'logo_sistema')
        ->get();

        $title='LISTA DE PERSONAL';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;
        

        $detalles=$obj;
        
        $cont=Personal::count();
        $pdf = \PDF::loadView('pdf.reportes.personal.personal', [
            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,
            'detalles'=>$detalles,
            
        ]);
        //return $pdf->stream('Personal.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Personal.pdf');

        
    }
    public function pdfUsuario(Request $request){

        $title='LISTA DE USUARIOS';
        $obj= User::join('grupo','users.id_grupo','=','grupo.id')
        ->join('personal','users.id_personal','=','personal.id')
        ->leftjoin('cargo', function($join){
            $join->orOn('personal.id_cargo','=','cargo.id');
        })
        ->select('users.name as nombre','users.matricula','users.email'
        ,'grupo.nombre as grupo','personal.nombre as personal','cargo.nombre as cargo')
        ->where('users.estado','!=','0')
        ->get();

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto', 'logo_sistema')
        ->get();

        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;

        $detalles=$obj;
        
        $cont=User::count();
        $pdf = \PDF::loadView('pdf.reportes.usuario.usuario', [
            'title'=>$title,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,
            'detalles'=>$detalles,
            
        ]);
        //return $pdf->stream('Usuarios.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Usuarios.pdf');

    }
    public function pdfCaja(Request $request){

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        
        $x = DB::table('arqueo_caja as a')
        ->join('users as u', 'a.id_usuario', '=', 'u.id')
        ->select('a.id','a.fecha_apertura','a.fecha_cierre','a.apertura','a.registro_venta as ingreso','a.estado','a.gastos','a.total',
        'a.registro_compra','a.saldo_sistema','a.saldo_efectivo','a.diferencia','a.id_usuario',DB::raw('a.registro_compra + a.gastos as egreso'),
        DB::raw('a.apertura + a.registro_venta - a.registro_compra - a.gastos as total_efectivo'),'a.doscientos','a.cien','a.cincuenta','a.veinte',
        'a.diez','a.cinco','a.dos','a.uno','a.cerocinco','a.ceroveinte','a.ceroveinte','a.cien_dolar','u.name')
        ->whereDate('a.fecha_apertura', ">=", $fecha_inicio)
        ->whereDate('a.fecha_apertura', "<=", $fecha_fin)
        ->orderBy('a.id', 'asc')->get();

        $obj = json_decode(json_encode($x), true);

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto', 'logo_sistema')
        ->get();

        $title='ARQUEO POR USUARIO';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;

        $detalles=$obj;

        //dd($detalles);
        
        $cont=ArqueoCaja::count();
        $pdf = \PDF::loadView('pdf.reportes.arqueo.arqueo', [
            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,

            'fecha_inicio'=>$fecha_inicio,
            'fecha_fin'=>$fecha_fin,
            'detalles'=>$detalles,
            
        ]);
        //return $pdf->stream('Arqueo.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Arqueo.pdf');

    }
    public function pdfProducto(Request $request){

        $x=DB::select("SELECT l.id , l.fecha_vecimiento , l.cantidad as stock, p.nombre_comercial,p.nombre_generico, p.ubicacion, c.nombre as categoria,
        p.costo_unitario, p.precio_blister,p.precio_caja, p.stock_minimo, p.costo_compra, ta.id_tienda, ta.id_articulo, t.nombre as tienda,pr.nombre as proveedor
        FROM lote as l, tienda_articulo ta, articulo p, categoria c, tienda t , proveedor as pr
        WHERE l.id_producto=ta.id and p.estado!=0 and ta.id_tienda=t.id and ta.id_articulo=p.id and c.id=p.id_categoria 
        and p.id_proveedor=pr.id  ORDER BY l.fecha_vecimiento, pr.nombre");

        $obj = json_decode(json_encode($x), true);

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTA DE PRODUCTOS';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;

        $detalles=$obj;
        
        $cont=Articulo::count();
        $pdf = \PDF::loadView('pdf.reportes.producto.producto', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,

            'detalles'=>$detalles,
            
        ]);
        //return $pdf->stream('Producto.pdf');
        return $pdf->setPaper('letter', 'landscape')->stream('Producto.pdf');
    }
    public function pdfGasto(Request $request){

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        $x=DB::select("SELECT g.fecha,g.monto,g.descripcion,m.nombre motivo_gasto,g.id_forma_pago,g.efectivo,g.deposito,u.name as usuario,f.nombre as forma
        FROM gasto g, motivo_gasto m,forma_pago f,users u
        WHERE g.id_motivo_gasto=m.id and g.id_forma_pago=f.id and g.id_usuario=u.id
        AND g.fecha>='$fecha_inicio' AND g.fecha<='$fecha_fin'");
        $obj = json_decode(json_encode($x), true);

        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTA DE GASTOS';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;
       // $id_forma_pago=$x[0]->id_forma_pago;
        $detalles=$obj;
        $total_general = 0;
        $total_deposito = 0;
        $total = 0;
        foreach($detalles as $det)
        {
            $total_general=$total_general+$det['efectivo'];
            $total_deposito=$total_deposito+$det['deposito'];
            $total=$total_deposito+$total_general;
            //$resul = $resul+$total_general;
        

        }
        //dd($total_general,$total_deposito,$total);
        //dd($detalles);
        
        $cont=Gasto::count();
        $pdf = \PDF::loadView('pdf.reportes.gasto.gasto', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,

            'fecha_inicio'=>$fecha_inicio,
            'fecha_fin'=>$fecha_fin,
            //'id_forma_pago'=>$id_forma_pago,
            'detalles'=>$detalles,
            'total_general'=>$total_general,
            'total_deposito'=>$total_deposito,
            'total'=>$total,
            
        ]);
        //return $pdf->stream('Gasto.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Gasto.pdf');

    }
    public function pdfGastoCliente(Request $request){

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        $x=DB::select("SELECT g.fecha,g.monto,g.descripcion,m.nombre motivo_gasto,g.id_forma_pago,g.efectivo,g.deposito,u.name as usuario,f.nombre as forma
        FROM gasto g, motivo_gasto m,forma_pago f,users u
        WHERE g.id_motivo_gasto=m.id and g.id_forma_pago=f.id and g.id_usuario=u.id and g.id_forma_pago !=3 and g.id_forma_pago !=4 and g.id_forma_pago !=5
        AND g.fecha>='$fecha_inicio' AND g.fecha<='$fecha_fin'");
        $obj = json_decode(json_encode($x), true);

        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTA DE GASTOS TOTAL EFECTIVO';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;
       // $id_forma_pago=$x[0]->id_forma_pago;
        $detalles=$obj;
        $total_general = 0;
        foreach($detalles as $det)
        {
            $total_general=$total_general+$det['efectivo'];
            //$resul = $resul+$total_general;
        

        }
        //dd($total_general);
        //dd($detalles);
        
        $cont=Gasto::count();
        $pdf = \PDF::loadView('pdf.reportes.gasto.gasto_efectivo', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,

            'fecha_inicio'=>$fecha_inicio,
            'fecha_fin'=>$fecha_fin,
            //'id_forma_pago'=>$id_forma_pago,
            'detalles'=>$detalles,
            'total_general'=>$total_general,
            
        ]);
        //return $pdf->stream('Gasto.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Gasto.pdf');

    }
    public function pdfProveedor(Request $request){

        $obj= Proveedor::select('proveedor.id','proveedor.nombre','proveedor.nit','proveedor.contacto','proveedor.direccion','proveedor.telefono')
        ->where('proveedor.estado','!=','0')
        ->get();

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto', 'logo_sistema')
        ->get();

        $title='LISTA DE LABORATORIO';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;

        $detalles=$obj;

        $cont=Proveedor::count();
        $pdf = \PDF::loadView('pdf.reportes.proveedor.proveedor', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,
            'detalles'=>$detalles,
            
        ]);
        //return $pdf->stream('Proveedor.pdf');
        return $pdf->setPaper('letter')->stream('Proveedor.pdf');
    }
    public function pdfCompraGeneral(Request $request){

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin; 

        $x=DB::select("SELECT c.fecha,p.nombre proveedor,u.name usuario,c.sub_total,c.descuento,c.total, t.nombre as tipo,f.nombre as forma,c.total_efectivo,c.total_deposito
        FROM compra c, proveedor p, users u,tipo_pago t,forma_pago f
        WHERE c.estado!='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id AND c.id_tipo_pago=t.id AND c.id_forma_pago=f.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin'");
        $obj = json_decode(json_encode($x), true);

        $a=DB::select("SELECT SUM(c.total) as totalC
        FROM compra c, proveedor p, users u
        WHERE c.estado!='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin' ");
        $obj2 = json_decode(json_encode($a), true);

        $b=DB::select("SELECT SUM(c.total) as totalCo
        FROM compra c, proveedor p, users u
        WHERE c.estado!='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin' AND c.id_tipo_pago=1");
        $obj3 = json_decode(json_encode($b), true);

        $c=DB::select("SELECT SUM(c.total) as totalCr
        FROM compra c, proveedor p, users u
        WHERE c.estado!='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin' AND c.id_tipo_pago=2");
        $obj4 = json_decode(json_encode($c), true);

        $d=DB::select("SELECT SUM(c.total_efectivo) as totalEf
        FROM compra c, proveedor p, users u
        WHERE c.estado!='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin'");
        $obj5 = json_decode(json_encode($d), true);

        $e=DB::select("SELECT SUM(c.total_deposito) as totalDep
        FROM compra c, proveedor p, users u
        WHERE c.estado!='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin'");
        $obj6 = json_decode(json_encode($e), true);


        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTADO DE COMPRAS';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;

        $detalles=$obj;
        $detalles2=$obj2;
        $detalles3=$obj3;
        $detalles4=$obj4;
        $detalles5=$obj5;
        $detalles6=$obj6;
        
        //dd($detalles2,$detalles3,$detalles4,$detalles5,$detalles6);
        
        $cont=Compra::count();
        $pdf = \PDF::loadView('pdf.reportes.compra.compra_general', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,

            'fecha_inicio'=>$fecha_inicio,
            'fecha_fin'=>$fecha_fin,
            'detalles'=>$detalles,

            'detalles2'=>$detalles2,
            'detalles3'=>$detalles3,
            'detalles4'=>$detalles4,
            'detalles5'=>$detalles5,
            'detalles6'=>$detalles6,

            
        ]);
        //return $pdf->stream('Compra.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Compra.pdf');

    }
    public function pdfCompraDetallada(Request $request){

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin; 

        $x=DB::select("SELECT c.id,c.fecha,p.nombre proveedor,u.name usuario,c.sub_total,c.descuento,c.total,t.nombre as tipo,f.nombre as forma,c.total_efectivo,c.total_deposito
        FROM compra c, proveedor p, users u,tipo_pago t,forma_pago f
        WHERE c.estado!='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id AND c.id_tipo_pago=t.id AND c.id_forma_pago=f.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin'");
        $obj = json_decode(json_encode($x), true);
        $y= detalleCompra::join('tienda_articulo','detalle_compra.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->leftjoin('categoria', function($join){
            $join->orOn('articulo.id_categoria','=','categoria.id');
        })
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_compra.id_compra','detalle_compra.costo_compra as pu','detalle_compra.id_compra','detalle_compra.cantidad',
        'detalle_compra.sub_total','articulo.nombre_comercial as producto','tienda.nombre as tienda','categoria.nombre as categoria','detalle_compra.descuento')
        ->where('detalle_compra.eliminado','=',0)
        ->get();
        $obj2 = json_decode(json_encode($y), true);

        $a=DB::select("SELECT SUM(c.total) as totalC
        FROM compra c, proveedor p, users u
        WHERE c.estado!='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin' ");
        $obj3 = json_decode(json_encode($a), true);

        $b=DB::select("SELECT SUM(c.total) as totalCo
        FROM compra c, proveedor p, users u
        WHERE c.estado!='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin' AND c.id_tipo_pago=1");
        $obj4 = json_decode(json_encode($b), true);

        $c=DB::select("SELECT SUM(c.total) as totalCr
        FROM compra c, proveedor p, users u
        WHERE c.estado!='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin' AND c.id_tipo_pago=2");
        $obj5 = json_decode(json_encode($c), true);

        $d=DB::select("SELECT SUM(c.total_efectivo) as totalEf
        FROM compra c, proveedor p, users u
        WHERE c.estado!='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin'");
        $obj6 = json_decode(json_encode($d), true);

        $e=DB::select("SELECT SUM(c.total_deposito) as totalDep
        FROM compra c, proveedor p, users u
        WHERE c.estado!='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin'");
        $obj7 = json_decode(json_encode($e), true);

        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTADO DE COMPRAS DETALLADA';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;

        $compra=$obj;
        $detalles=$obj2;

        $detalles2=$obj3;
        $detalles3=$obj4;
        $detalles4=$obj5;
        $detalles5=$obj6;
        $detalles6=$obj7;
        
        //dd($compra,$detalles);
        
        $cont=Compra::count();
        $pdf = \PDF::loadView('pdf.reportes.compra.compra_detallada', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,

            'fecha_inicio'=>$fecha_inicio,
            'fecha_fin'=>$fecha_fin,
            'compra'=>$compra,
            'detalles'=>$detalles,

            'detalles2'=>$detalles2,
            'detalles3'=>$detalles3,
            'detalles4'=>$detalles4,
            'detalles5'=>$detalles5,
            'detalles6'=>$detalles6,


            
        ]);
        //return $pdf->stream('Compra.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Compra.pdf');

    }
    public function pdfCompraDetalladaAnular(Request $request){

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin; 

        $x=DB::select("SELECT c.id,c.fecha,p.nombre proveedor,u.name usuario,c.sub_total,c.descuento,c.total,t.nombre as tipo,f.nombre as forma,c.total_efectivo,c.total_deposito
        FROM compra c, proveedor p, users u,tipo_pago t,forma_pago f
        WHERE c.estado='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id AND c.id_tipo_pago=t.id AND c.id_forma_pago=f.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin'");
        $obj = json_decode(json_encode($x), true);
       
        $y= detalleCompra::join('tienda_articulo','detalle_compra.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->leftjoin('categoria', function($join){
            $join->orOn('articulo.id_categoria','=','categoria.id');
        })
        ->join('tienda','tienda_articulo.id_tienda','=','tienda.id')
        ->select('detalle_compra.id_compra','detalle_compra.costo_compra as pu','detalle_compra.id_compra','detalle_compra.cantidad',
        'detalle_compra.sub_total','articulo.nombre_comercial as producto','tienda.nombre as tienda','categoria.nombre as categoria')
        ->get();
        $obj2 = json_decode(json_encode($y), true);

        $a=DB::select("SELECT SUM(c.total) as totalC
        FROM compra c, proveedor p, users u
        WHERE c.estado='Anulado' AND c.id_proveedor=p.id AND c.id_usuario=u.id
        AND c.fecha>='$fecha_inicio' AND c.fecha<='$fecha_fin' ");
        $obj3 = json_decode(json_encode($a), true);

        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTADO DE COMPRAS DETALLADA ANULADAS';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;

        $compra=$obj;
        $detalles=$obj2;

        $detalles2=$obj3;

        
        //dd($compra,$detalles);
        
        $cont=Compra::count();
        $pdf = \PDF::loadView('pdf.reportes.compra.compra_detallada_anular', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,

            'fecha_inicio'=>$fecha_inicio,
            'fecha_fin'=>$fecha_fin,
            'compra'=>$compra,
            'detalles'=>$detalles,

            'detalles2'=>$detalles2,

            
        ]);
        //return $pdf->stream('Compra.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Compra.pdf');

    }
    
    public function pdfVentaGeneral(Request $request)
    {
        ini_set('memory_limit', '20048M');
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;

            // Obtener los totales resumidos
            $resumen = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->selectRaw('SUM(v.total) as totalV')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('td.id', $id_tienda)
                ->first();

            $totalContado = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->selectRaw('SUM(v.total) as totalC')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('td.id', $id_tienda)
                ->where('v.id_tipo_pago', 1) // Contado
                ->first();

            $totalCredito = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->selectRaw('SUM(v.total) as totalCr')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('td.id', $id_tienda)
                ->where('v.id_tipo_pago', 2) // Crédito
                ->first();

            $totalEfectivo = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->selectRaw('SUM(v.total_efectivo) as totalEf')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('td.id', $id_tienda)
                ->first();

            $totalDeposito = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->selectRaw('SUM(v.total_deposito) as totalDep')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('td.id', $id_tienda)
                ->first();

            // === 2. DATOS DE LA EMPRESA ===
            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            if (!$empresa) {
                return response()->json(['error' => 'Datos de la empresa no configurados'], 500);
            }

            // === 3. CONFIGURACIÓN DE mPDF ===
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'Letter',
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
            ]);

            // === 4. ESTILOS CSS ===
            $css = "
                @page {
                    font-size: 13px;
                }
                body {
                    position: relative;
                    color: black;
                    background: #FFFFFF;
                    font-family: Arial, sans-serif;
                    font-size: 13px;
                }
                .table {
                    display: table;
                    width: 100%;
                    max-width: 100%;
                    background-color: transparent;
                    border-collapse: collapse;
                }
                .table th {
                    padding: 0.5rem;
                    vertical-align: top;
                }
                .table td {
                    padding: 0.5rem;
                    vertical-align: top;
                }
                .table-head {
                    width: 100%;
                    max-width: 100%;
                    border-collapse: collapse;
                }
                .table-head th {
                    vertical-align: center;
                }
                .table-body {
                    display: table;
                    width: 100%;
                    max-width: 100%;
                    background-color: transparent;
                    border-collapse: collapse;
                }
                .table-body th {
                    vertical-align: top;
                }
                .table-body td {
                    vertical-align: top;
                    padding-top: 5px;
                    padding-bottom: 2px;
                }
                .table-footer{
                    border-top: 1px solid #001843;
                    font-size: 10px;
                }
                .table-saldo{
                    text-align: right;
                    vertical-align: top;
                }
                .footer-centro{
                    position: absolute;
                    bottom: 50%;
                    left: 0;
                    right: 0;
                }
                .footer-inferior{
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                }
                .A{
                    float: left;
                    width: 20%;
                    height: 100px;
                    text-align:center;
                }
                .AA{
                    float: left;
                    text-align:center;
                }
                .BB{
                    float: left;
                    text-align:center;
                }
                .CC{
                    float: left;
                    text-align:center;
                }
                .DD{
                    float: left;
                    text-align:center;
                }
                .EE{
                    float: left;
                    text-align:center;
                }

                .container{
                    height: 100px;
                }
                .container2{
                    height: 40px;
                }
                #lateral {
                    width: 80px;
                }
                #lateral {
                    height: 100px;
                }

                .mostrar {
                    display: block;
                }
                .nomostrar {
                    display: none;
                }
                .colocar_pie {
                    page-break-before: always;
                }
                footer {
                    position: fixed;
                    bottom: 0cm;
                    left: 0cm;
                    right: 0cm;
                    height: 1.5cm;
                }
            ";
            $mpdf->WriteHTML('<style>' . $css . '</style>', \Mpdf\HTMLParserMode::HEADER_CSS);

            // === 5. ENCABEZADO DEL PDF ===
            $title = 'LISTADO DE VENTAS';
            $logoHtml = $empresa->logo_sistema
                ? '<img src="img/logo/' . $empresa->logo_sistema . '" 
                        style="height: 60px; width: auto; display: block; margin: 0 auto;" 
                        alt="Logo de la Empresa">'
                : '<div style="width: 60px; height: 60px; background-color: #f0f0f0; border: 1px solid #ccc; margin: 0 auto;"></div>';

            $header = "
                <table style='width: 100%; border-collapse: collapse; margin-bottom: 0px; table-layout: fixed;'>
                    <tr>
                        <td style='width: 80px; text-align: center; vertical-align: middle; padding: 10px;'>
                            {$logoHtml}
                        </td>
                        <td style='text-align: center; vertical-align: middle; padding: 10px;'>
                            <div style='font-size: 20px; font-weight: bold; color: #001843; line-height: 1.3; margin: 0; padding: 0;'>
                                " . strtoupper($title) . "
                            </div>
                            <small style='text-align:center'>DESDE: {$fecha_inicio} HASTA: {$fecha_fin}</small>
                        </td>
                        <td style='width: 170px; text-align: center; vertical-align: middle; padding: 10px;'>
                            
                        </td>
                    </tr>
                </table>


                <table class='table' style='' >
                    <thead>
                        <tr>
                            <th style='width:50%; text-align:left;color:#fff;font-size: 12px; background-color:#001843'>
                                Total Venta: " . ($resumen->totalV ?? 0) . "
                            </th>
                   
                            <th style='width:50%'></th>
                        </tr>
                        <tr>
                            <td height='5px'></td>
                        </tr>
                        <tr>
                            <th style='width:50%; text-align:left;color:#fff;font-size: 12px;background-color:#5386a5'>
                                Total Venta Contado: " . ($totalContado->totalC ?? 0) . "
                            </th>
                  
                            <th style='width:50%; text-align:left;color:#fff;font-size: 12px;background-color:#5386a5'>
                                Total Venta Credito: " . ($totalCredito->totalCr ?? 0) . "
                            </th>
                       
                        </tr>

                        <tr>
                            <td height='5px'></td>
                        </tr>
                     
                        <tr>
                            <th style='width:50%; text-align:left;font-size: 12px;background-color:#a0d2f3'>
                                Total Efectivo: " . ($totalEfectivo->totalEf ?? 0) . "
                            </th>
                            <th style='width:50%; text-align:left;font-size: 12px;background-color:#a0d2f3'>
                                Total Deposito: " . ($totalDeposito->totalDep ?? 0) . "
                            </th>
                        </tr>

                        <tr>
                            <td height='5px'></td>
                        </tr>

                    </thead>
                </table>
            ";

            $mpdf->WriteHTML($header, \Mpdf\HTMLParserMode::HTML_BODY);

            // === 6. TABLA DE DETALLES DE VENTAS CON PAGINACIÓN ===
            $query = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->select(
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'c.nombre as cliente',
                    't.nombre as tipo_pago',
                    'f.nombre as forma_pago',
                    'u.name as usuario',
                    'td.nombre as tienda'
                )
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('td.id', $id_tienda)
                ->orderBy('v.fecha') // Agrega un orden para consistencia en la paginación
                ->orderBy('v.id'); // Orden secundario para evitar duplicados si hay fechas iguales

            $ventasCount = $query->count();

            if ($ventasCount > 0) {
                $mpdf->WriteHTML("
                    <table class='table' style='border: 1px solid black;'>
                        <thead style='border: 1px solid black; background-color:#001843'>
                            <tr>
                                <th style='background-color:#001843; vertical-align: middle; border: 2px solid black ;color:#FFFFFF; text-align: center;' width='10%'>#</th>
                                <th style='background-color:#001843; vertical-align: middle; border: 2px solid black ;color:#FFFFFF; text-align: start;' width='35%'>Cliente</th>
                                <th style='background-color:#001843; vertical-align: middle; border: 2px solid black ;color:#FFFFFF; text-align: center;' width='15%'>Tipo P.</th>
                                <th style='background-color:#001843; vertical-align: middle; border: 2px solid black ;color:#FFFFFF; text-align: center;' width='15%'>Forma P.</th>
                                <th style='background-color:#001843; vertical-align: middle; border: 2px solid black ;color:#FFFFFF; text-align: center;' width='20%'>Descuento</th>
                                <th style='background-color:#001843; vertical-align: middle; border: 2px solid black ;color:#FFFFFF; text-align: center;' width='20%'>Sub Total</th>
                                <th style='background-color:#001843; vertical-align: middle; border: 2px solid black ;color:#FFFFFF; text-align: center;' width='20%'>Total</th>
                                <th style='background-color:#001843; vertical-align: middle; border: 2px solid black ;color:#FFFFFF; text-align: start;' width='20%'>Usuario</th>
                            </tr>
                        </thead>
                        <tbody>", \Mpdf\HTMLParserMode::HTML_BODY);

                $contador = 1;
                // chunk() procesa los resultados en bloques para no sobrecargar la memoria
                $query->chunk(500, function ($ventasChunk) use ($mpdf, &$contador) {
                    foreach ($ventasChunk as $venta) {
                        $fila = "
                            <tr style='border-top: 1px solid black'>
                                <td style='text-align: center; border: 1px solid black;'>{$contador}</td>
                                <td style='text-align: start; border: 1px solid black;'>" . e($venta->cliente ?? '—') . "</td>
                                <td style='text-align: center; border: 1px solid black;'>" . e($venta->tipo_pago ?? '—') . "</td>
                                <td style='text-align: center; border: 1px solid black;'>" . e($venta->forma_pago ?? '—') . "</td>
                                <td style='text-align: center; border: 1px solid black;'>" . e($venta->descuento ?? '0') . "</td>
                                <td style='text-align: center; border: 1px solid black;'>" . e($venta->sub_total ?? '0') . "</td>
                                <td style='text-align: center; border: 1px solid black;'>" . e($venta->total ?? '0') . "</td>
                                <td style='text-align: center; border: 1px solid black;'>" . e($venta->usuario ?? '—') . "</td>
                            </tr>";
                        $mpdf->WriteHTML($fila, \Mpdf\HTMLParserMode::HTML_BODY);
                        $contador++;
                    }
                });

                $mpdf->WriteHTML("</tbody></table>", \Mpdf\HTMLParserMode::HTML_BODY);
            } else {
                $mpdf->WriteHTML("
                    <table class='data-table' style='border: 1px solid black;'>
                        <tr>
                            <td style='text-align: center; border: 1px solid black; padding: 20px; font-style: italic;'>
                                No se encuentran registros entre estas fechas
                            </td>
                        </tr>
                    </table>", \Mpdf\HTMLParserMode::HTML_BODY);
            }

            // === 7. PIE DE PÁGINA (Opcional) ===
            $mpdf->WriteHTML("
                <div class='footer-note' style='font-size: 9px; color: #777; margin-top: 10px; text-align: center;'>
                    Reporte generado automáticamente.
                </div>", \Mpdf\HTMLParserMode::HTML_BODY);

            // === 8. SALIDA DEL PDF ===
            return response($mpdf->Output('Venta_General.pdf', 'I'))
                ->header('Content-Type', 'application/pdf');

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaGeneral: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de ventas'], 500);
        }
    }

    public function pdfVentaDetallada(Request $request)
    {
        ini_set('memory_limit', '200048M');
        try {
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin'     => 'required|date|after_or_equal:fecha_inicio',
                'tipo_venta'    => 'required|string',
                'id_tienda'     => 'required|exists:tienda,id',
            ]);

            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin    = $request->fecha_fin;
            $tipo_venta   = $request->tipo_venta;
            $id_tienda    = $request->id_tienda;

            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            if (!$empresa) {
                return response()->json(['error' => 'Datos de la empresa no configurados'], 500);
            }

            $totales = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->selectRaw('
                    COUNT(v.id) as totalV,
                    SUM(CASE WHEN v.id_tipo_pago = 1 THEN v.total ELSE 0 END) as totalC,
                    SUM(CASE WHEN v.id_tipo_pago = 2 THEN v.total ELSE 0 END) as totalCr,
                    SUM(v.total_efectivo) as totalEf,
                    SUM(v.total_deposito) as totalDep
                ')
                ->first();

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'Letter',
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
            ]);

            $css = "
                @page {
                    font-size: 12px;
                }
                body {
                    position: relative;
                    color: black;
                    background: #FFFFFF;
                    font-family: Arial, sans-serif;
                    font-size: 11px;
                }
                .table {
                    display: table;
                    width: 100%;
                    max-width: 100%;
                    background-color: transparent;
                    border-collapse: collapse;
                }
                .table th {
                    padding: 0.5rem;
                    vertical-align: top;
                }
                .table td {
                    padding: 0.5rem;
                    vertical-align: top;
                }
                .table-head {
                    width: 100%;
                    max-width: 100%;
                    border-collapse: collapse;
                }
                .table-head th {
                    vertical-align: center;
                }
                .container{
                    height: auto;
                }
                footer {
                    position: fixed;
                    bottom: 0cm;
                    left: 0cm;
                    right: 0cm;
                    height: 1.5cm;
                }
            ";
            $mpdf->WriteHTML('<style>' . $css . '</style>', \Mpdf\HTMLParserMode::HEADER_CSS);

            // === 5. HEADER ===
            $title = 'LISTADO DE VENTAS DETALLADO';
            $logoHtml = $empresa->logo_sistema
                ? '<img src="img/logo/' . $empresa->logo_sistema . '" 
                        style="height: 60px; width: auto; display: block; margin: 0 auto;" 
                        alt="Logo de la Empresa">'
                : '<div style="width: 60px; height: 60px; background-color: #f0f0f0; border: 1px solid #ccc; margin: 0 auto;"></div>';

            $header = "
                <table style='width: 100%; border-collapse: collapse; margin-bottom: 0px; table-layout: fixed;'>
                    <tr>
                        <td style='width: 80px; text-align: center; vertical-align: middle; padding: 10px;'>
                            {$logoHtml}
                        </td>
                        <td style='text-align: center; vertical-align: middle; padding: 10px;'>
                            <div style='font-size: 20px; font-weight: bold; color: #001843; line-height: 1.3; margin: 0; padding: 0;'>
                                " . strtoupper($title) . "
                            </div>
                            <small style='text-align:center; font-size:14px;'>DESDE: {$fecha_inicio} HASTA: {$fecha_fin}</small>
                        </td>
                        <td style='width: 170px; text-align: center; vertical-align: middle; padding: 10px;'>
                            
                        </td>
                    </tr>
                </table>

                <table class='table' style='margin-top: 10px;'>
                    <thead>
                        <tr>
                            <th style='width:50%; text-align:left; color:#fff; font-size: 12px; background-color:#001843; border: 1px solid black; padding: 8px;'>
                                Total Ventas: " . number_format($totales->totalV ?? 0, 0) . "
                            </th>
                            <th style='width:50%'>

                            </th>
                        </tr>
                        <tr>
                            <td height='5px' style='border: none;'></td>
                        </tr>

                        <tr>
                            <th style='width:50%; text-align:left; color:#fff; font-size: 12px; background-color:#5386a5; border: 1px solid black; padding: 8px;'>
                                Total Contado: Bs. " . number_format($totales->totalC ?? 0, 2) . "
                            </th>
                            <th style='width:50%; text-align:left; color:#fff; font-size: 12px; background-color:#5386a5; border: 1px solid black; padding: 8px;'>
                                Total Crédito: Bs. " . number_format($totales->totalCr ?? 0, 2) . "
                            </th>
                        </tr>
                        <tr>
                            <td height='5px' style='border: none;'></td>
                        </tr>
                        <tr>
                            <th style='width:50%; text-align:left; font-size: 12px; background-color:#a0d2f3; border: 1px solid black; padding: 8px;'>
                                Total Efectivo: Bs. " . number_format($totales->totalEf ?? 0, 2) . "
                            </th>
                            <th style='width:50%; text-align:left; font-size: 12px; background-color:#a0d2f3; border: 1px solid black; padding: 8px;'>
                                Total Depósito: Bs. " . number_format($totales->totalDep ?? 0, 2) . "
                            </th>
                        </tr>
                        <tr>
                            <td height='10px' style='border: none;'></td>
                        </tr>
                    </thead>
                </table>
            ";

            $mpdf->WriteHTML($header, \Mpdf\HTMLParserMode::HTML_BODY);

            // === 6. OBTENER VENTAS CON CHUNK PARA EVITAR SOBRECARGA ===
            $queryVentas = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('td.id', $id_tienda)
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_efectivo',
                    'v.total_deposito',
                    'c.nombre as cliente',
                    't.nombre as tipo_pago',
                    'f.nombre as forma_pago',
                    'u.name as usuario',
                    'td.nombre as tienda'
                )
                ->orderBy('v.fecha', 'desc')
                ->orderBy('v.id');

            $ventasCount = $queryVentas->count();

            if ($ventasCount > 0) {
                // === 7. PROCESAR VENTAS CON CHUNK ===
                $queryVentas->chunk(100, function ($ventasChunk) use ($mpdf, $fecha_inicio, $fecha_fin) {
                    foreach ($ventasChunk as $venta) {
                        // Obtener detalles de productos para esta venta
                        $detallesProductos = DB::table('detalle_venta as d')
                            ->join('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                            ->join('articulo as p', 'ta.id_articulo', '=', 'p.id')
                            ->where('d.id_venta', $venta->id)
                            ->where('d.estado', '!=', '1')
                            ->select('d.cantidad', 'p.nombre_comercial as producto', 'd.costo_venta', 'd.sub_total')
                            ->get();

                        // Obtener detalles de paquetes para esta venta
                        $detallesPaquetes = DB::table('detalle_venta_paquete as dvp')
                            ->join('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                            ->where('dvp.id_venta', $venta->id)
                            ->select('dvp.cantidad', 'pqt.nombre as producto', 'dvp.costo_venta', 'dvp.sub_total')
                            ->get();

                        $tieneDetalles = $detallesProductos->isNotEmpty() || $detallesPaquetes->isNotEmpty();

                        // Calcular total mostrar
                        $totalMostrar = $venta->forma_pago == 'Efectivo'
                            ? ($venta->total_efectivo ?? 0)
                            : ($venta->total_deposito ?? $venta->total ?? 0);

                        // === TABLA DE LA VENTA ===
                        $tablaVenta = "
                            <table class='table' style='margin-bottom: 15px; border: 1px solid black; page-break-inside: avoid;'>
                                <thead>
                                    <tr>
                                        <th style='background-color: #001843; color: #FFF; font-size: 10px; width: 25%; border: 1px solid black; padding: 6px; text-align: left;'>
                                            Cliente: " . e($venta->cliente ?? 'N/A') . "
                                        </th>
                                        <th style='background-color: #001843; color: #FFF; font-size: 10px; width: 15%; border: 1px solid black; padding: 6px;'>
                                            Tipo P.: " . e($venta->tipo_pago ?? 'N/A') . "
                                        </th>
                                        <th style='background-color: #001843; color: #FFF; font-size: 10px; width: 15%; border: 1px solid black; padding: 6px;'>
                                            Forma P.: " . e($venta->forma_pago ?? 'N/A') . "
                                        </th>
                                        <th style='background-color: #001843; color: #FFF; font-size: 10px; width: 15%; border: 1px solid black; padding: 6px;'>
                                            Desc.: " . number_format($venta->descuento ?? 0, 2) . "
                                        </th>
                                        <th style='background-color: #001843; color: #FFF; font-size: 10px; width: 15%; border: 1px solid black; padding: 6px;'>
                                            Total: " . number_format($venta->total, 2) . " Bs.
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style='background-color: #FF0107; color: #FFF; font-size: 10px; border: 1px solid black; padding: 6px; text-align: left;'>Producto / Paquete</th>
                                        <th style='background-color: #FF0107; color: #FFF; font-size: 10px; border: 1px solid black; padding: 6px; text-align: center;'>Cantidad</th>
                                        <th style='background-color: #FF0107; color: #FFF; font-size: 10px; border: 1px solid black; padding: 6px; text-align: right;'>P.U.</th>
                                        <th style='background-color: #FF0107; color: #FFF; font-size: 10px; border: 1px solid black; padding: 6px; text-align: right;'>Sub Total</th>
                                        <th style='background-color: #FF0107; color: #FFF; font-size: 10px; border: 1px solid black; padding: 6px; text-align: center;'>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody style='border: 1px solid black;'>
                        ";

                        $ventaSubTotal = 0;

                        // === DETALLES DE PRODUCTOS ===
                        if ($detallesProductos->isNotEmpty()) {
                            foreach ($detallesProductos as $det) {
                                $ventaSubTotal += $det->sub_total ?? 0;
                                $tablaVenta .= "
                                    <tr style='border-top: 1px solid black;'>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: left;'>" . e($det->producto ?? 'Producto no disponible') . "</td>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: center;'>" . e($det->cantidad ?? 0) . "</td>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: right;'>" . number_format($det->costo_venta ?? 0, 2) . "</td>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: right;'>" . number_format($det->sub_total ?? 0, 2) . "</td>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: center;'>" . \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') . "</td>
                                    </tr>
                                ";
                            }
                        }

                        // === DETALLES DE PAQUETES ===
                        if ($detallesPaquetes->isNotEmpty()) {
                            foreach ($detallesPaquetes as $det) {
                                $ventaSubTotal += $det->sub_total ?? 0;
                                $tablaVenta .= "
                                    <tr style='border-top: 1px solid black;'>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: left;'>" . e($det->producto ?? 'Paquete no disponible') . "</td>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: center;'>" . e($det->cantidad ?? 0) . "</td>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: right;'>" . number_format($det->costo_venta ?? 0, 2) . "</td>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: right;'>" . number_format($det->sub_total ?? 0, 2) . "</td>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: center;'>" . \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') . "</td>
                                    </tr>
                                ";
                            }
                        }

                        // === SI NO HAY DETALLES ===
                        if (!$tieneDetalles) {
                            $tablaVenta .= "
                                <tr>
                                    <td colspan='5' style='text-align: center; font-style: italic; color: #666; padding: 15px; border: 1px solid black;'>
                                        No se encontraron detalles para esta venta
                                    </td>
                                </tr>
                            ";
                        } else {
                            // === FILA DE TOTAL ===
                            $tablaVenta .= "
                                <tr style='background-color: #f8f8f8; border-top: 2px solid #001843;'>
                                    <td colspan='3' style='text-align: right; font-weight: bold; font-size: 10px; padding: 8px; border: 1px solid black;'>
                                        TOTAL:
                                    </td>
                                    <td style='text-align: right; font-weight: bold; font-size: 10px; padding: 8px; border: 1px solid black;'>
                                        " . number_format($ventaSubTotal, 2) . " Bs.
                                    </td>
                                    <td style='border: 1px solid black;'></td>
                                </tr>
                            ";
                        }

                        $tablaVenta .= "</tbody></table>";

                        $mpdf->WriteHTML($tablaVenta, \Mpdf\HTMLParserMode::HTML_BODY);
                    }
                });
            } else {
                $mpdf->WriteHTML("
                    <table class='table' style='border: 1px solid black;'>
                        <tr>
                            <td style='text-align: center; border: 1px solid black; padding: 20px; font-style: italic;'>
                                No se encontraron registros entre las fechas {$fecha_inicio} y {$fecha_fin}
                            </td>
                        </tr>
                    </table>
                ", \Mpdf\HTMLParserMode::HTML_BODY);
            }

            // === 8. FOOTER ===
            $mpdf->WriteHTML("
                <div style='font-size: 9px; color: #777; margin-top: 10px; text-align: center;'>
                    Reporte generado automáticamente — " . now()->format('d/m/Y H:i') . "
                </div>
            ", \Mpdf\HTMLParserMode::HTML_BODY);

            // === 9. SALIDA DEL PDF ===
            return response($mpdf->Output("Ventas_Detalladas_{$tipo_venta}.pdf", 'I'))
                ->header('Content-Type', 'application/pdf');

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaDetallada: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte: ' . $e->getMessage()], 500);
        }
    }

    public function pdfProductoLaboratorio(Request $request){

 
        $id_proveedor = $request->id_proveedor;
        //dd($id_proveedor);
        $proveedor_listado= Proveedor::select('nombre')
        ->where('id','=',$id_proveedor)
        ->get();

        $x=DB::select("SELECT articulo.id,articulo.id as id_producto,articulo.nombre_comercial as producto, tienda_articulo.stock,unidad_medida.nombre as presentacion
        FROM articulo INNER JOIN tienda_articulo
        ON tienda_articulo.id_articulo=articulo.id
        INNER JOIN unidad_medida
        ON articulo.id_unidad = unidad_medida.id
        WHERE tienda_articulo.stock>0 AND articulo.id_proveedor= '$id_proveedor' AND articulo.estado=1 ORDER BY articulo.nombre_comercial");
        $obj = json_decode(json_encode($x), true);

       //dd($obj);
        $y=DB::select("SELECT articulo.id as id_producto,lote.lote,lote.cantidad,lote.fecha_vecimiento
        FROM lote INNER JOIN tienda_articulo
        ON lote.id_producto=tienda_articulo.id
        INNER JOIN articulo
        ON tienda_articulo.id_articulo=articulo.id
        WHERE  lote.estado!=0 and lote.cantidad> 0");
        $obj2 = json_decode(json_encode($y), true);
       // dd($obj2);
   
        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();




        $title='LISTADO DE PRODUCTO DETALLADO';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;
        $proveedor=$proveedor_listado[0]->nombre;
        //$totalC=$obj5[0]->totalC;

        //dd($totalC);

        $venta=$obj;
        //dd($venta);
        $detalles=$obj2;

        
        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.reportes.producto.producto_detallada', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,
            'proveedor'=>$proveedor,

            'venta'=>$venta,
            'detalles'=>$detalles,

        ]);
        //return $pdf->stream('Ventas.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Venta.pdf');

    }
    public function pdfVentaDetalladaDevolucion(Request $request){

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin; 
        $tipo_venta = $request->tipo_venta;
        $id_tienda = $request->id_tienda;


        $x=DB::select("SELECT v.id,v.fecha,v.sub_total,v.descuento,v.total,v.total_efectivo,v.total_deposito,c.nombre as cliente,t.nombre as tipo_pago,f.nombre as forma_pago, u.name as usuario, td.nombre as tienda
        FROM venta v, cliente c, tipo_pago t, forma_pago f, users u, tienda td
        WHERE v.estado='Devolucion' AND v.id_cliente=c.id AND v.id_tipo_pago=t.id AND v.id_forma_pago=f.id AND v.id_usuario=u.id AND v.id_tienda=td.id AND v.tipo_venta='$tipo_venta'
        AND v.fecha>='$fecha_inicio' AND v.fecha<='$fecha_fin' AND td.id='$id_tienda' ");
        $obj = json_decode(json_encode($x), true);

        $z=DB::select("SELECT SUM(v.total) as totalV
        FROM venta v, cliente c, tipo_pago t, forma_pago f, users u, tienda td
        WHERE v.estado='Devolucion' AND v.id_cliente=c.id AND v.id_tipo_pago=t.id AND v.id_forma_pago=f.id AND v.id_usuario=u.id AND v.id_tienda=td.id AND v.tipo_venta='$tipo_venta'
        AND v.fecha>='$fecha_inicio' AND v.fecha<='$fecha_fin' AND td.id='$id_tienda'");
        $obj3 = json_decode(json_encode($z), true);


        $y=DB::select("SELECT d.id_venta,d.cantidad,p.nombre_comercial producto, d.costo_venta, d.sub_total,u.name as usuario
        FROM detalle_venta d, tienda_articulo ta, articulo p, users u
        WHERE d.id_producto=ta.id AND ta.id_articulo=p.id AND d.id_eliminado=u.id AND d.estado='1'");
        $obj2 = json_decode(json_encode($y), true);

        $a=DB::select("SELECT detalle_venta_paquete.id_venta,detalle_venta_paquete.cantidad,paquetes.nombre producto, detalle_venta_paquete.costo_venta, detalle_venta_paquete.sub_total
        FROM detalle_venta_paquete, paquetes
        WHERE detalle_venta_paquete.id_paquete=paquetes.id");
        $obj4 = json_decode(json_encode($a), true);

        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();




        $title='LISTADO DE VENTAS DETALLADO DEVOLUCION';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;

        //dd($nombre_empresa);

        $venta=$obj;
        //dd($venta);
        $detalles=$obj2;
        $detalles2=$obj3;
        $detallesPaquete=$obj4;
        
        //dd($venta,$detalles);
        
        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_devolucion', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,

            'tipo_venta'=>$tipo_venta,
            'fecha_inicio'=>$fecha_inicio,
            'fecha_fin'=>$fecha_fin,
            'venta'=>$venta,
            'detalles'=>$detalles,
            'detalles2'=>$detalles2,
            'detallesPaquete'=>$detallesPaquete,
            
        ]);
        //return $pdf->stream('Ventas.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Venta.pdf');

    }
    
    public function pdfVentaDetalladaAnulada(Request $request)
    {
        try {
            // Validar entrada
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin'     => 'required|date|after_or_equal:fecha_inicio',
                'tipo_venta'    => 'required|string',
                'id_tienda'     => 'required|exists:tienda,id',
            ]);

            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin    = $request->fecha_fin;
            $tipo_venta   = $request->tipo_venta;
            $id_tienda    = $request->id_tienda;

            // === 1. Datos de la empresa ===
            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto']);
            if (!$empresa) {
                return response()->json(['error' => 'Datos de la empresa no configurados'], 500);
            }

            // === 2. Ventas ANULADAS en el rango ===
            $ventas = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '=', 'Anulado')  // Solo anuladas
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('td.id', $id_tienda)
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_efectivo',
                    'v.total_deposito',
                    'c.nombre as cliente',
                    't.nombre as tipo_pago',
                    'f.nombre as forma_pago',
                    'u.name as usuario',
                    'td.nombre as tienda'
                )
                ->orderBy('v.fecha', 'desc')
                ->get();

            if ($ventas->isEmpty()) {
                return response()->json(['info' => 'No hay ventas anuladas en el rango seleccionado'], 404);
            }

            // === 3. Totales (solo anuladas) ===
            $totales = DB::table('venta as v')
                ->where('v.estado', '=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->select(
                    DB::raw('SUM(v.total) as totalV'),
                    DB::raw('SUM(CASE WHEN v.id_tipo_pago = 1 THEN v.total ELSE 0 END) as totalC'),
                    DB::raw('SUM(CASE WHEN v.id_tipo_pago = 2 THEN v.total ELSE 0 END) as totalCr'),
                    DB::raw('SUM(v.total_efectivo) as totalEf'),
                    DB::raw('SUM(v.total_deposito) as totalDep')
                )
                ->first();

            // === 4. Detalles de productos y paquetes ===
            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = [];
            $detallesPaquete = [];

            if (!empty($ventasIds)) {
                // Productos
                $detalles = DB::table('detalle_venta as d')
                    ->join('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->join('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->select('d.id_venta', 'd.cantidad', 'p.nombre_comercial as producto', 'd.costo_venta', 'd.sub_total')
                    ->get()
                    ->groupBy('id_venta');

                // Paquetes
                $detallesPaquete = DB::table('detalle_venta_paquete as dvp')
                    ->join('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                    ->whereIn('dvp.id_venta', $ventasIds)
                    ->select('dvp.id_venta', 'dvp.cantidad', 'pqt.nombre as producto', 'dvp.costo_venta', 'dvp.sub_total')
                    ->get()
                    ->groupBy('id_venta');
            }

            // === 5. Generar PDF con DomPDF (o mPDF si es necesario) ===
            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_anular', [
                'title'               => 'LISTADO DE VENTAS DETALLADO ANULADAS',
                'nombre_empresa'      => $empresa->nombre,
                'direccion_empresa'   => $empresa->direccion,
                'telefono_empresa'    => $empresa->telefono,
                'foto_empresa'        => $empresa->foto,

                'fecha_inicio'        => $fecha_inicio,
                'fecha_fin'           => $fecha_fin,
                'tipo_venta'          => $tipo_venta,

                'ventas'              => $ventas,
                'totales'             => $totales,
                'detalles'            => $detalles,
                'detallesPaquete'     => $detallesPaquete,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream("Ventas_Anuladas_Detalladas_{$tipo_venta}.pdf");

        } catch (\Exception $e) {
            Log::error('Error en pdfVentaDetalladaAnulada: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    
    public function pdfVentaDetalladaEfectivo(Request $request)
    {
        ini_set('memory_limit', '200048M');
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;

            if (!$fecha_inicio || !$fecha_fin || !$tipo_venta || !$id_tienda) {
                return response()->json(['error' => 'Parámetros requeridos faltantes'], 400);
            }

            // === 1. DATOS DE LA EMPRESA ===
            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'logo_sistema']);
            if (!$empresa) {
                return response()->json(['error' => 'Datos de la empresa no configurados'], 500);
            }

            // === 2. TOTAL EN EFECTIVO ===
            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_forma_pago', 2) // Efectivo
                ->sum('v.total_efectivo');

            // === 3. CONFIGURAR MPDF ===
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'Letter',
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
            ]);

            // === 4. CSS ===
            $css = "
                @page { font-size: 12px; }
                body { font-family: Arial, sans-serif; font-size: 11px; color: #000; }
                .table { width: 100%; border:none;}
                .table th, .table td { vertical-align: top; border:1px solid #000}
            ";
            $mpdf->WriteHTML('<style>' . $css . '</style>', \Mpdf\HTMLParserMode::HEADER_CSS);

            // === 5. HEADER ===
            $title = 'LISTADO DE VENTAS EFECTIVO DETALLADO';
            $logoHtml = $empresa->logo_sistema
                ? '<img src="img/logo/' . $empresa->logo_sistema . '" style="height: 60px; width: auto; display: block; margin: 0 auto;">'
                : '<div style="width: 60px; height: 60px; background-color: #f0f0f0; border: 1px solid #ccc; margin: 0 auto;"></div>';

            $header = "
                <table style='width: 100%; border-collapse: collapse; margin-bottom: 10px;'>
                    <tr>
                        <td style='width: 80px; text-align: center; padding: 10px;'>{$logoHtml}</td>
                        <td style='text-align: center; padding: 10px;'>
                            <div style='font-size: 20px; font-weight: bold; color: #001843;'>" . strtoupper($title) . "</div>
                            <small style='font-size:12px;'>DESDE: {$fecha_inicio} HASTA: {$fecha_fin}</small>
                        </td>
                        <td style='width: 165px;'></td>
                    </tr>
                </table>
                <table style='width: 100%; margin-bottom: 15px;'>
                    <tr>
                        <th style='background-color: #001843; color: #fff; padding: 10px; text-align: left; border: 1px solid black;'>
                            TOTAL VENTAS EFECTIVO: Bs. " . number_format($totalVentas, 2) . "
                        </th>
                    </tr>
                </table>
            ";
            $mpdf->WriteHTML($header, \Mpdf\HTMLParserMode::HTML_BODY);

            // === 6. PROCESAR VENTAS CON CHUNK ===
            $queryVentas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_forma_pago', 2)
                ->select(
                    'v.id', 'v.fecha', 'v.descuento', 'v.total_efectivo',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Contado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Efectivo") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario')
                )
                ->orderBy('v.id', 'asc');

            $ventasCount = $queryVentas->count();

            if ($ventasCount > 0) {
                $queryVentas->chunk(100, function ($ventasChunk) use ($mpdf) {
                    foreach ($ventasChunk as $venta) {
                        $detalles = DB::table('detalle_venta as d')
                            ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                            ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                            ->where('d.id_venta', $venta->id)
                            ->where('d.estado', '!=', '1')
                            ->select('d.cantidad', DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'), 'd.costo_venta', 'd.sub_total')
                            ->get();

                        $tabla = "
                            <table style='width: 100%; margin-bottom: 15px; border-collapse: collapse;'>
                                <thead>
                                    <tr>
                                        <th style='margin:0px; background-color: #001843; color: #fff; font-size: 10px; border: 1px solid black; padding: 6px; text-align: left;'>Cliente: " . e($venta->cliente) . "</th>
                                        <th style='margin:0px; background-color: #001843; color: #fff; font-size: 10px; border: 1px solid black; padding: 6px;'>Tipo P.: " . e($venta->tipo_pago) . "</th>
                                        <th style='margin:0px; background-color: #001843; color: #fff; font-size: 10px; border: 1px solid black; padding: 6px;'>Forma P.: " . e($venta->forma_pago) . "</th>
                                        <th style='margin:0px; background-color: #001843; color: #fff; font-size: 10px; border: 1px solid black; padding: 6px;'>Desc.: " . number_format($venta->descuento, 2) . "</th>
                                        <th style='margin:0px; background-color: #001843; color: #fff; font-size: 10px; border: 1px solid black; padding: 6px;'>Total: " . number_format($venta->total_efectivo, 2) . " Bs.</th>
                                    </tr>
                                    <tr>
                                        <th style='margin:0px; background-color: #FF0107; color: #fff; font-size: 10px; border: 1px solid black; padding: 3px;'>Producto</th>
                                        <th style='margin:0px; background-color: #FF0107; color: #fff; font-size: 10px; border: 1px solid black; padding: 3px;'>Cantidad</th>
                                        <th style='margin:0px; background-color: #FF0107; color: #fff; font-size: 10px; border: 1px solid black; padding: 3px;'>P.U.</th>
                                        <th style='margin:0px; background-color: #FF0107; color: #fff; font-size: 10px; border: 1px solid black; padding: 3px;'>Sub Total</th>
                                        <th style='margin:0px; background-color: #FF0107; color: #fff; font-size: 10px; border: 1px solid black; padding: 3px;'>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                        ";

                        $subtotal = 0;
                        if ($detalles->isNotEmpty()) {
                            foreach ($detalles as $det) {
                                $subtotal += $det->sub_total;
                                $tabla .= "
                                    <tr>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px;'>" . e($det->producto) . "</td>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: center;'>" . e($det->cantidad) . "</td>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: right;'>" . number_format($det->costo_venta, 2) . "</td>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: right;'>" . number_format($det->sub_total, 2) . "</td>
                                        <td style='font-size: 10px; border: 1px solid black; padding: 4px; text-align: center;'>" . \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') . "</td>
                                    </tr>
                                ";
                            }
                            $tabla .= "
                                <tr style='background-color: #f8f8f8;'>
                                    <td colspan='3' style='text-align: right; font-weight: bold; border: 1px solid black; padding: 8px;'>TOTAL:</td>
                                    <td style='text-align: right; font-weight: bold; border: 1px solid black; padding: 8px;'>" . number_format($subtotal, 2) . " Bs.</td>
                                    <td style='border: 1px solid black;'></td>
                                </tr>
                            ";
                        } else {
                            $tabla .= "<tr><td colspan='5' style='text-align: center; padding: 15px; border: 1px solid black;'>No se encontraron detalles</td></tr>";
                        }

                        $tabla .= "</tbody></table>";
                        $mpdf->WriteHTML($tabla, \Mpdf\HTMLParserMode::HTML_BODY);
                    }
                });
            } else {
                $mpdf->WriteHTML("<table style='border: 1px solid black;'><tr><td style='text-align: center; padding: 20px;'>No se encontraron registros</td></tr></table>", \Mpdf\HTMLParserMode::HTML_BODY);
            }

            return response($mpdf->Output('Venta_Efectivo.pdf', 'I'))->header('Content-Type', 'application/pdf');

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaDetalladaEfectivo: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte', 'message' => $e->getMessage()], 500);
        }
    }

    public function pdfVentaDetalladaTransfencia(Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;
            $id_caja = $request->id_caja;

            if (!$fecha_inicio || !$fecha_fin || !$tipo_venta || !$id_tienda) {
                return response()->json(['error' => 'Parámetros requeridos faltantes'], 400);
            }

            $ventas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->leftJoin('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_tipo_pago', 1)
                ->where('v.id_forma_pago', 3) // Transferencia
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_efectivo',
                    'v.total_deposito',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Tipo no encontrado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Forma no encontrada") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario'),
                    DB::raw('COALESCE(td.nombre, "Tienda no encontrada") as tienda')
                )
                ->orderBy('v.id', 'asc')
                ->get();


            // Total en depósito (transferencia)
            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_tipo_pago', 1)
                ->where('v.id_forma_pago', 3)
                ->sum('v.total_deposito');

            // Detalles de venta
            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = collect();

            if (!empty($ventasIds)) {
                $detalles = DB::table('detalle_venta as d')
                    ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1')
                    ->select(
                        'd.id_venta',
                        'd.cantidad',
                        DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'),
                        'd.costo_venta',
                        'd.sub_total'
                    )
                    ->get();
            }

            // Empresa
            $mi_empresa = MiEmpresa::select(
                'nombre',
                'direccion',
                'telefono',
                'logo_sistema',
                'logo_login as foto'
            )->first();

            if (!$mi_empresa) {
                $mi_empresa = (object) [
                    'nombre' => 'Mi Empresa',
                    'direccion' => 'Dirección no disponible',
                    'telefono' => 'Teléfono no disponible',
                    'foto' => null,
                    'logo_sistema' => null,
                ];
            }

            $title = 'LISTADO DE VENTAS TRANSFERENCIA DETALLADO';
            $nombre_empresa = $mi_empresa->nombre ?? 'Mi Empresa';
            $direccion_empresa = $mi_empresa->direccion ?? 'Dirección no disponible';
            $telefono_empresa = $mi_empresa->telefono ?? 'Teléfono no disponible';
            $foto_empresa = $mi_empresa->foto ?? null;
            $logo_sistema = $mi_empresa->logo_sistema ?? null;

            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_forma', [
                'title' => $title,
                'nombre_empresa' => $nombre_empresa,
                'direccion_empresa' => $direccion_empresa,
                'telefono_empresa' => $telefono_empresa,
                'foto_empresa' => $foto_empresa,
                'logo_sistema' => $logo_sistema,
                'tipo_venta' => $tipo_venta,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'venta' => $ventas,
                'detalles' => $detalles,
                'totalVentas' => $totalVentas ?? 0,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream('Venta_Transferencia.pdf');

        } catch (\Exception $e) {
            \Log::error('Error generando reporte de ventas por transferencia: ' . $e->getMessage(), [
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'tipo_venta' => $request->tipo_venta,
                'id_tienda' => $request->id_tienda,
            ]);

            return response()->json([
                'error' => 'Error al generar el reporte',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function pdfVentaDetalladaQr(Request $request) {
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;
            $id_caja = $request->id_caja;
         
            if (!$fecha_inicio || !$fecha_fin || !$tipo_venta || !$id_tienda) {
                return response()->json(['error' => 'Parámetros requeridos faltantes'], 400);
            }
     
            $ventas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->leftJoin('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_forma_pago', 4) // Forma de pago QR
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_deposito',
                    'v.total_efectivo',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Tipo no encontrado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Forma no encontrada") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario'),
                    DB::raw('COALESCE(td.nombre, "Tienda no encontrada") as tienda')
                )
                // ->orderBy('v.fecha', 'desc')
                ->orderBy('v.id', 'asc')
                ->get();

            
            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_forma_pago', 4)
                ->sum('v.total_deposito');

            $ventasIds = $ventas->pluck('id')->toArray();
            
            $detalles = collect(); // Inicializar como colección vacía
            
            if (!empty($ventasIds)) {
                $detalles = DB::table('detalle_venta as d')
                    ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1') 
                    ->select(
                        'd.id_venta',
                        'd.cantidad',
                        DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'),
                        'd.costo_venta',
                        'd.sub_total'
                    )
                    ->get();
            }

           
            $mi_empresa = MiEmpresa::select(
                'nombre',
                'nit',
                'representante',
                'direccion',
                'telefono',
                'localidad',
                'Correo',
                'sitio_web',
                'foto',
                'logo_sistema',
            )->first();

            
            if (!$mi_empresa) {
                $mi_empresa = (object) [
                    'nombre' => 'Mi Empresa',
                    'direccion' => 'Dirección no disponible',
                    'telefono' => 'Teléfono no disponible',
                    'foto' => null,
                    'logo_sistema' => null,
                ];
            }

            // Preparar datos para la vista
            $title = 'LISTADO DE VENTAS DETALLADO QR';
            $nombre_empresa = $mi_empresa->nombre ?? 'Mi Empresa';
            $direccion_empresa = $mi_empresa->direccion ?? 'Dirección no disponible';
            $telefono_empresa = $mi_empresa->telefono ?? 'Teléfono no disponible';
            $foto_empresa = $mi_empresa->foto ?? null;
            $logo_sistema = $mi_empresa->logo_sistema ?? null;

            // Generar el PDF con manejo de errores
            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_forma_qr_general', [
                'title' => $title,
                'nombre_empresa' => $nombre_empresa,
                'direccion_empresa' => $direccion_empresa,
                'telefono_empresa' => $telefono_empresa,
                'foto_empresa' => $foto_empresa,
                'logo_sistema' => $logo_sistema,
                'tipo_venta' => $tipo_venta,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'venta' => $ventas,
                'totalVentas' => $totalVentas ?? 0,
                'detalles' => $detalles,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream('Venta_QR.pdf');

        } catch (\Exception $e) {
        
            \Log::error('Error generando reporte de ventas QR: ' . $e->getMessage(), [
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'tipo_venta' => $request->tipo_venta,
                'id_tienda' => $request->id_tienda,
                'stack_trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Error al generar el reporte',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function pdfVentaDetalladaDeposito(Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;
            $id_caja = $request->id_caja;

            if (!$fecha_inicio || !$fecha_fin || !$tipo_venta || !$id_tienda) {
                return response()->json(['error' => 'Parámetros requeridos faltantes'], 400);
            }

            $ventas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->leftJoin('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_forma_pago', 5) // Depósito
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_deposito',
                    'v.total_efectivo',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Tipo no encontrado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Forma no encontrada") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario'),
                    DB::raw('COALESCE(td.nombre, "Tienda no encontrada") as tienda')
                )
                ->orderBy('v.id', 'asc')
                ->get();

            // Total en depósito
            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_forma_pago', 5)
                ->sum('v.total_deposito');

            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = collect();
            $detallesPaquete = collect();

            if (!empty($ventasIds)) {
                // Detalles de productos
                $detalles = DB::table('detalle_venta as d')
                    ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1')
                    ->select(
                        'd.id_venta',
                        'd.cantidad',
                        DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'),
                        'd.costo_venta',
                        'd.sub_total'
                    )
                    ->get();

                // Detalles de paquetes
                $detallesPaquete = DB::table('detalle_venta_paquete as dvp')
                    ->leftJoin('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                    ->whereIn('dvp.id_venta', $ventasIds)
                    ->select(
                        'dvp.id_venta',
                        'dvp.cantidad',
                        DB::raw('COALESCE(pqt.nombre, "Paquete no encontrado") as producto'),
                        'dvp.costo_venta',
                        'dvp.sub_total'
                    )
                    ->get();
            }

            // Empresa
            $mi_empresa = MiEmpresa::select(
                'nombre',
                'direccion',
                'telefono',
                'logo_sistema',
                'foto as logo_login'
            )->first();

            if (!$mi_empresa) {
                $mi_empresa = (object) [
                    'nombre' => 'Mi Empresa',
                    'direccion' => 'Dirección no disponible',
                    'telefono' => 'Teléfono no disponible',
                    'logo_login' => null,
                    'logo_sistema' => null,
                ];
            }

            $title = 'LISTADO DE VENTAS DETALLADO DEPOSITO';
            $nombre_empresa = $mi_empresa->nombre ?? 'Mi Empresa';
            $direccion_empresa = $mi_empresa->direccion ?? 'Dirección no disponible';
            $telefono_empresa = $mi_empresa->telefono ?? 'Teléfono no disponible';
            $foto_empresa = $mi_empresa->logo_login ?? null;
            $logo_sistema = $mi_empresa->logo_sistema ?? null;

            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_forma', [
                'title' => $title,
                'nombre_empresa' => $nombre_empresa,
                'direccion_empresa' => $direccion_empresa,
                'telefono_empresa' => $telefono_empresa,
                'foto_empresa' => $foto_empresa,
                'logo_sistema' => $logo_sistema,
                'tipo_venta' => $tipo_venta,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'venta' => $ventas,
                'detalles' => $detalles,
                'detallesPaquete' => $detallesPaquete,
                'totalVentas' => $totalVentas ?? 0,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream('Venta_Deposito.pdf');

        } catch (\Exception $e) {
            \Log::error('Error generando reporte de ventas por depósito: ' . $e->getMessage(), [
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'tipo_venta' => $request->tipo_venta,
                'id_tienda' => $request->id_tienda,
            ]);

            return response()->json([
                'error' => 'Error al generar el reporte',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function pdfVentaDetalladaMixta(Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;
            $id_caja = $request->id_caja;

            if (!$fecha_inicio || !$fecha_fin || !$tipo_venta || !$id_tienda) {
                return response()->json(['error' => 'Parámetros requeridos faltantes'], 400);
            }

            $ventas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->leftJoin('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_forma_pago', 6) // Mixta
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_efectivo',
                    'v.total_deposito',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Tipo no encontrado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Forma no encontrada") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario'),
                    DB::raw('COALESCE(td.nombre, "Tienda no encontrada") as tienda')
                )
                ->orderBy('v.id', 'asc')
                ->get();


            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_forma_pago', 6)
                ->sum('v.total'); // o sum('v.total_efectivo') + sum('v.total_deposito')


            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = collect();
            $detallesPaquete = collect();

            if (!empty($ventasIds)) {
                // Productos
                $detalles = DB::table('detalle_venta as d')
                    ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1')
                    ->select(
                        'd.id_venta',
                        'd.cantidad',
                        DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'),
                        'd.costo_venta',
                        'd.sub_total'
                    )
                    ->get();

                // Paquetes
                $detallesPaquete = DB::table('detalle_venta_paquete as dvp')
                    ->leftJoin('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                    ->whereIn('dvp.id_venta', $ventasIds)
                    ->select(
                        'dvp.id_venta',
                        'dvp.cantidad',
                        DB::raw('COALESCE(pqt.nombre, "Paquete no encontrado") as producto'),
                        'dvp.costo_venta',
                        'dvp.sub_total'
                    )
                    ->get();
            }
            // Empresa
            $mi_empresa = MiEmpresa::select(
                'nombre',
                'direccion',
                'telefono',
                'logo_sistema',
                'foto as logo_login'
            )->first();

            if (!$mi_empresa) {
                $mi_empresa = (object) [
                    'nombre' => 'Mi Empresa',
                    'direccion' => 'Dirección no disponible',
                    'telefono' => 'Teléfono no disponible',
                    'logo_login' => null,
                    'logo_sistema' => null,
                ];
            }

            $title = 'LISTADO DE VENTAS DETALLADO MIXTA';
            $nombre_empresa = $mi_empresa->nombre ?? 'Mi Empresa';
            $direccion_empresa = $mi_empresa->direccion ?? 'Dirección no disponible';
            $telefono_empresa = $mi_empresa->telefono ?? 'Teléfono no disponible';
            $foto_empresa = $mi_empresa->logo_login ?? null;
            $logo_sistema = $mi_empresa->logo_sistema ?? null;

            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_mixta', [
                'title' => $title,
                'nombre_empresa' => $nombre_empresa,
                'direccion_empresa' => $direccion_empresa,
                'telefono_empresa' => $telefono_empresa,
                'foto_empresa' => $foto_empresa,
                'logo_sistema' => $logo_sistema,
                'tipo_venta' => $tipo_venta,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'venta' => $ventas,
                'detalles' => $detalles,
                'detallesPaquete' => $detallesPaquete,
                'totalVentas' => $totalVentas ?? 0,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream('Venta_Mixta.pdf');

        } catch (\Exception $e) {
            \Log::error('Error generando reporte de ventas mixtas: ' . $e->getMessage(), [
                'fecha_inicio' => $request->fecha_inicio,
                'fecha_fin' => $request->fecha_fin,
                'tipo_venta' => $request->tipo_venta,
                'id_tienda' => $request->id_tienda,
            ]);

            return response()->json([
                'error' => 'Error al generar el reporte',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function pdfPagoVenta(Request $request){

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $tipo_venta = $request->tipo_venta;
        $id_tienda = $request->id_tienda;

        $y=DB::select("SELECT p.fecha, p.fecha_final,p.monto,p.saldo,p.id, c.nombre as cliente, t.nombre as tienda, p.id_tipo_pago, p.estado
        FROM pago p, venta v, cliente c, tienda t
        WHERE p.id_venta=v.id AND v.id_cliente=c.id AND v.id_tienda=t.id AND p.id_tipo_pago=2
        AND p.fecha>='$fecha_inicio' AND p.fecha<='$fecha_fin' AND p.estado=1 AND v.tipo_venta='$tipo_venta'");
        $obj2 = json_decode(json_encode($y), true);

        $x=DB::select("SELECT i.nombre cliente,c.fecha, c.amortizacion,c.saldo,id_pago,c.descripcion
        FROM c_x_cobrar c, pago p, venta v, cliente i
        WHERE c.id_pago=p.id AND p.id_venta=v.id AND v.id_cliente=i.id");
        $obj = json_decode(json_encode($x), true);
        

        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();


        $title='LISTADO DE PAGOS AL CRÉDITO';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;

        $detalles=$obj;
        $venta_pago=$obj2;
        
        $cont=Pago::count();
        $pdf = \PDF::loadView('pdf.reportes.venta.venta_pago', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,

            'tipo_venta'=>$tipo_venta,
            'fecha_inicio'=>$fecha_inicio,
            'fecha_fin'=>$fecha_fin,
            'detalles'=>$detalles,
            'venta_pago'=>$venta_pago
            
        ]);
        //return $pdf->stream('Pagos.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Pagos.pdf');

    }
    
    public function pdfCliente()
    {
        try {
            // === 1. DATOS DE LA EMPRESA ===
            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            if (!$empresa) {
                return response()->json(['error' => 'Datos de la empresa no configurados'], 500);
            }

            $totalCount = Cliente::where('estado', '!=', 0)
                ->where('id', '!=', 1)
                ->count();

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'Letter',
                'margin_top' => 10,
                'margin_bottom' => 15,
                'margin_left' => 15,
                'margin_right' => 15,
            ]);

            $css = "
                @page {
                    margin-top: 1cm;
                    margin-bottom: 1cm;
                    margin-left: 1.5cm;
                    margin-right: 1.5cm;
                    font-size: 12px;
                    font-family: Arial;
                }
                body {
                    position: relative;
                    color: black;
                    background: #FFFFFF;
                    font-family: Arial, sans-serif;
                    font-size: 10px;
                }
                .table {
                    display: table;
                    width: 100%;
                    max-width: 100%;
                    background-color: transparent;
                    border-collapse: collapse;
                }
                .table th {
                    padding: 0.5rem;
                    vertical-align: top;
                }
                .table td {
                    padding: 0.5rem;
                    vertical-align: top;
                }
                .table-pago {
                    display: table;
                    width: 80%;
                    max-width: 80%;
                    background-color: transparent;
                    border-collapse: collapse;
                }
                .table-pago th {
                    padding: 1rem;
                    vertical-align: top;
                }
                .table-pago td {
                    padding: 0.5rem;
                    vertical-align: top;
                }
                .table-description{
                    display: table;
                    width: 95%;
                    max-width: 95%;
                    background-color: transparent;
                    border-collapse: collapse;
                }
                .table-description th {
                    padding: 0.5rem;
                    vertical-align: top;
                }
                .table-description td {
                    padding: 0.5rem;
                    vertical-align: top;
                }
                .table-head {
                    width: 100%;
                    max-width: 100%;
                    border-collapse: collapse;
                }
                .table-head th {
                    vertical-align: center;
                }
                .table-body {
                    display: table;
                    width: 100%;
                    max-width: 100%;
                    background-color: transparent;
                    border-collapse: collapse;
                }
                .table-body th {
                    vertical-align: top;
                }
                .table-body td {
                    vertical-align: top;
                    padding-top: 5px;
                    padding-bottom: 2px;
                }
                .table-footer{
                    border-top: 1px solid #001843;
                    font-size: 10px; 
                }
                .table-saldo{
                    text-align: right;
                    vertical-align: top;
                }
                .footer-centro{
                    position: absolute;
                    bottom: 50%;
                    left: 0;
                    right: 0;
                }
                .footer-inferior{
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                }
                .A{
                    float: left;
                    width: 20%; 
                    height: 100px; 
                    text-align:center;
                }
                .AA{
                    float: left;
                    text-align:center;
                }
                .BB{
                    float: left;
                    text-align:center;
                }
                .CC{
                    float: left;
                    text-align:center;
                }
                .DD{
                    float: left;
                    text-align:center;
                }
                .EE{
                    float: left;
                    text-align:center;
                }
                
                .container{
                    height: 100px; 
                }
                .container2{
                    height: 40px; 
                }
                #lateral { 
                    width: 80px; 
                }
                #lateral { 
                    height: 100px; 
                } 
                
                .mostrar {
                    display: block;
                }
                .nomostrar {
                    display: none;
                }
                .colocar_pie {
                    page-break-before: always;

                }

                footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 1.5cm;
            }
            ";
            $mpdf->WriteHTML('<style>' . $css . '</style>', \Mpdf\HTMLParserMode::HEADER_CSS);

            $title='Lista de clientes';

            $logoHtml = $empresa->logo_sistema
                ? '<img src="img/logo/' . $empresa->logo_sistema . '" 
                        style="height: 60px; width: auto; display: block; margin: 0 auto;" 
                        alt="Logo de la Empresa">'
                : '<div style="width: 60px; height: 60px; background-color: #f0f0f0; border: 1px solid #ccc; margin: 0 auto;"></div>';

            $header = "
                <table style='width: 100%; border-collapse: collapse; margin-bottom: 0px; table-layout: fixed;'>
                    <tr>
                        <td style='width: 80px; text-align: center; vertical-align: middle; padding: 10px;'>
                            {$logoHtml}
                        </td>
                        <td style='text-align: center; vertical-align: middle; padding: 0px;'>
                            <div style='font-size: 20px; font-weight: bold; color: #001843; line-height: 1.3; margin: 0; padding: 0;'>
                                " . strtoupper($title) . "
                            </div>
                        </td>
                        <td style='width: 170px; text-align: center; vertical-align: middle; padding: 10px;'>
                            
                        </td>
                    </tr>
                </table>
            ";
            
            $mpdf->WriteHTML($header, \Mpdf\HTMLParserMode::HTML_BODY);

            // === 6. TABLA DE CLIENTES ===
            if ($totalCount > 0) {
                $mpdf->WriteHTML("
                    <table class='table'>
                        <thead>
                            <tr>
                                <th style='background-color:#001843; vertical-align: middle; border: 2px solid #001843  ;color:#FFFFFF'>N°</th>
                                <th style='background-color:#001843; vertical-align: middle; border: 2px solid #001843  ;color:#FFFFFF; text-align:start'>Nombre del Cliente</th>
                                <th style='background-color:#001843; vertical-align: middle; border: 2px solid #001843  ;color:#FFFFFF'>CI</th>
                                <th style='background-color:#001843; vertical-align: middle; border: 2px solid #001843  ;color:#FFFFFF'>Teléfono</th>
                                <th style='background-color:#001843; vertical-align: middle; border: 2px solid #001843  ;color:#FFFFFF'>Dirección</th>
                            </tr>
                        </thead>
                        <tbody>", \Mpdf\HTMLParserMode::HTML_BODY);

                $contador = 1;
                Cliente::select('nombre as cliente', 'direccion', 'telefono', 'matricula')
                    ->where('estado', '!=', 0)
                    ->where('id', '!=', 1)
                    ->orderBy('nombre')
                    ->chunk(500, function ($clientes) use ($mpdf, &$contador) {
                        foreach ($clientes as $c) {
                            $fila = "
                                <tr>
                                    <td style='text-align: center; border: 1px solid black;' class='text-center'>{$contador}</td>
                                    <td style='text-align: center; border: 1px solid black; text-align:start'>" . e($c->cliente ?? '—') . "</td>
                                    <td style='text-align: center; border: 1px solid black;'>" . e($c->matricula ?? '—') . "</td>
                                    <td style='text-align: center; border: 1px solid black;'>" . e($c->telefono ?? '—') . "</td>
                                    <td style='text-align: center; border: 1px solid black;'>" . e($c->direccion ?? '—') . "</td>
                                </tr>";
                            $mpdf->WriteHTML($fila, \Mpdf\HTMLParserMode::HTML_BODY);
                            $contador++;
                        }
                    });

                $mpdf->WriteHTML("</tbody></table>", \Mpdf\HTMLParserMode::HTML_BODY);
            } else {
                $mpdf->WriteHTML("
                    <table class='data-table'>
                        <tr>
                            <td class='text-center bg-light' style='padding: 20px; font-style: italic;'>
                                No hay clientes registrados.
                            </td>
                        </tr>
                    </table>", \Mpdf\HTMLParserMode::HTML_BODY);
            }

            // === 7. PIE DE PÁGINA ===
            $mpdf->WriteHTML("
                <div class='footer-note'>
                    Reporte generado automáticamente. Solo incluye clientes activos.
                </div>", \Mpdf\HTMLParserMode::HTML_BODY);

            // === 8. SALIDA DEL PDF ===
            return response($mpdf->Output('Lista_Clientes.pdf', 'I'))
                ->header('Content-Type', 'application/pdf');

        } catch (\Exception $e) {
            \Log::error('Error en pdfCliente: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de clientes'], 500);
        }
    }

    

    public function pdfPaciente(Request $request){

        $obj= Paciente::join('cliente','paciente.id_cliente','=','paciente.id')
        ->join('animal','paciente.id_animal','=','animal.id')
        ->select('paciente.id','paciente.nombre as mascota','paciente.especie','paciente.color','paciente.raza','paciente.sexo','animal.nombre as animal','cliente.nombre as cliente')
        ->where('paciente.estado','!=','0')
        ->get();

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTA DE MASCOTAS';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;

        $detalles=$obj;
        //dd($detalles);
        
        $cont=Paciente::count();
        $pdf = \PDF::loadView('pdf.reportes.veterinaria.mascota', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'detalles'=>$detalles,
            
        ]);
        //return $pdf->stream('Proveedor.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Proveedor.pdf');

    }
    public function pdfProductoStock(Request $request){


        $x=DB::select("SELECT articulo.nombre_comercial as producto ,SUM(tienda_articulo.stock) as stock , articulo.costo_compra as compra , articulo.costo_unitario as venta 
        FROM tienda_articulo,articulo,tienda
        WHERE tienda_articulo.id_articulo=articulo.id and tienda_articulo.id_tienda=tienda.id and articulo.estado!=0
        GROUP by articulo.nombre_comercial,articulo.costo_compra,articulo.costo_unitario");
        $obj = json_decode(json_encode($x), true);

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTA DE PRODUCTOS';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;

        $detalles=$obj;
        $total = 0;
        $desc = 0;
        $total1 = 0;
        $desc2 = 0;
        $total1 = 0;
        foreach($detalles as $det)
        {
            $desc=$det['stock']*$det['compra'];
            $total=$total+$desc;

            $desc2= ($det['stock']*$det['venta']);
            $total1 = $total1+$desc2;

            $totalResultado = $total1 - $total;

        }
        $cont=Articulo::count();
        $pdf = \PDF::loadView('pdf.reportes.producto.producto_stock', [

            'title'=>$title,
            'foto_empresa'=>$foto_empresa,
            'detalles'=>$detalles,
            'total'=>$total,
            'total1'=>$total1,
            'totalResultado'=>$totalResultado,
            
        ]);
        //return $pdf->stream('Producto.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Producto.pdf');

        
    }
    public function pdfPagoCompra(Request $request){

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;

        $y=DB::select("SELECT p.fecha, p.fecha_final,p.monto,p.saldo,p.id, pr.nombre as proveedor, p.id_tipo_pago, p.estado
        FROM pago_compra p, compra c, proveedor pr
        WHERE p.id_compra=c.id AND c.id_proveedor=pr.id  AND p.id_tipo_pago=2
        AND p.fecha>='$fecha_inicio' AND p.fecha<='$fecha_fin' AND c.estado='Registrado'");
        $obj2 = json_decode(json_encode($y), true);

        $x=DB::select("SELECT i.nombre cliente,c.fecha, c.amortizacion,c.saldo,id_pago,c.descripcion
        FROM c_x_pagar c, pago_compra p, compra v, proveedor i
        WHERE c.id_pago=p.id AND p.id_compra=v.id AND v.id_proveedor=i.id");
        $obj = json_decode(json_encode($x), true);


        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();
        $title='LISTADO DE PAGOS AL CRÉDITO';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;


        $detalles=$obj;
        $venta_pago=$obj2;

        //dd($detalles);
        
        $cont=PagoCompra::count();
        $pdf = \PDF::loadView('pdf.reportes.compra.compra_pago', [
            
            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,

            'fecha_inicio'=>$fecha_inicio,
            'fecha_fin'=>$fecha_fin,
            'detalles'=>$detalles,
            'venta_pago'=>$venta_pago
            
        ]);
        return $pdf->stream('Pagos.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Pagos.pdf');

    }
    public function pdfProductoMinimo(Request $request){

        $x=DB::select("SELECT p.nombre_comercial,p.nombre_generico , p.cod_proveedor, c.nombre as categoria, p.costo_unitario, p.precio_blister, p.precio_caja, p.stock_minimo, p.costo_compra, ta.stock, ta.id_tienda, ta.id_articulo, t.nombre as tienda
        FROM tienda_articulo ta, articulo p, categoria c, tienda t
        WHERE p.estado!=0 and ta.id_tienda=t.id and ta.id_articulo=p.id and c.id=p.id_categoria and p.stock_minimo>=ta.stock");


        $obj = json_decode(json_encode($x), true);

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTA DE PRODUCTOS STOCK MINIMO';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;

        $detalles=$obj;
        // $total = 0;
        // $desc = 0;
        // $total1 = 0;
        // $desc2 = 0;
        // $total1 = 0;
        // foreach($detalles as $det)
        // {
        //     $desc=$det['stock_minimo']>=$det['stock'];
        //     $total=$desc;

        //     $desc2= ($det['stock']*$det['venta']);
        //     $total1 = $total1+$desc2;

        //     $totalResultado = $total1 - $total;

        // }
        
        $cont=Articulo::count();
        $pdf = \PDF::loadView('pdf.reportes.producto.producto_stock_minimo', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,

            'detalles'=>$detalles,
            
        ]);
        //return $pdf->stream('Producto.pdf');
        return $pdf->setPaper('letter', 'landscape')->stream('Producto.pdf');
    }

    
    /*public function pdfVentaGeneralUsuario(Request $request){
        ini_set('memory_limit', '20048M');
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin; 
        $tipo_venta = $request->tipo_venta;
        $id_tienda = $request->id_tienda;
        $id_usuario = $request->id_usuario;

        //dd($fecha_inicio,$fecha_fin,$tipo_venta,$id_tienda,$id_usuario);


        $x=DB::select("SELECT v.sub_total,v.descuento ,v.total,c.nombre as cliente,t.nombre as tipo_pago,f.nombre as forma_pago, u.name as usuario, td.nombre as tienda
        FROM venta v, cliente c, tipo_pago t, forma_pago f, users u, tienda td
        WHERE v.estado!='Anulado' AND v.id_cliente=c.id AND v.id_tipo_pago=t.id AND v.id_forma_pago=f.id AND v.id_usuario=u.id AND v.id_tienda=td.id AND v.tipo_venta='$tipo_venta'
        AND v.fecha>='$fecha_inicio' AND v.fecha<='$fecha_fin' AND td.id='$id_tienda' and v.id_usuario=$id_usuario");
        $obj = json_decode(json_encode($x), true);

        //dd($id_usuario);

        $y=DB::select("SELECT SUM(v.total) as totalV
        FROM venta v, cliente c, tipo_pago t, forma_pago f, users u, tienda td
        WHERE v.estado!='Anulado' AND v.id_cliente=c.id AND v.id_tipo_pago=t.id AND v.id_forma_pago=f.id AND v.id_usuario=u.id AND v.id_tienda=td.id AND v.tipo_venta='$tipo_venta'
        AND v.fecha>='$fecha_inicio' AND v.fecha<='$fecha_fin' AND td.id='$id_tienda' and v.id_usuario=$id_usuario");
        $obj1 = json_decode(json_encode($y), true);

        $a=DB::select("SELECT SUM(v.total) as totalC
        FROM venta v, cliente c, tipo_pago t, forma_pago f, users u, tienda td
        WHERE v.estado!='Anulado' AND v.id_cliente=c.id AND v.id_tipo_pago=t.id AND v.id_forma_pago=f.id AND v.id_usuario=u.id AND v.id_tienda=td.id AND v.tipo_venta='$tipo_venta'
        AND v.fecha>='$fecha_inicio' AND v.fecha<='$fecha_fin' AND td.id='$id_tienda' AND v.id_tipo_pago=1 and v.id_usuario=$id_usuario");
        $obj5 = json_decode(json_encode($a), true);

        $b=DB::select("SELECT SUM(v.total) as totalCr
        FROM venta v, cliente c, tipo_pago t, forma_pago f, users u, tienda td
        WHERE v.estado!='Anulado' AND v.id_cliente=c.id AND v.id_tipo_pago=t.id AND v.id_forma_pago=f.id AND v.id_usuario=u.id AND v.id_tienda=td.id AND v.tipo_venta='$tipo_venta'
        AND v.fecha>='$fecha_inicio' AND v.fecha<='$fecha_fin' AND td.id='$id_tienda' AND v.id_tipo_pago=2 and v.id_usuario=$id_usuario");
        $obj6 = json_decode(json_encode($b), true);

        $c=DB::select("SELECT SUM(v.total_efectivo) as totalEf
        FROM venta v, cliente c, tipo_pago t, forma_pago f, users u, tienda td
        WHERE v.estado!='Anulado' AND v.id_cliente=c.id AND v.id_tipo_pago=t.id AND v.id_forma_pago=f.id AND v.id_usuario=u.id AND v.id_tienda=td.id AND v.tipo_venta='$tipo_venta'
        AND v.fecha>='$fecha_inicio' AND v.fecha<='$fecha_fin' AND td.id='$id_tienda' and v.id_usuario=$id_usuario");
        $obj7 = json_decode(json_encode($c), true);

        $d=DB::select("SELECT SUM(v.total_deposito) as totalDep
        FROM venta v, cliente c, tipo_pago t, forma_pago f, users u, tienda td
        WHERE v.estado!='Anulado' AND v.id_cliente=c.id AND v.id_tipo_pago=t.id AND v.id_forma_pago=f.id AND v.id_usuario=u.id AND v.id_tienda=td.id AND v.tipo_venta='$tipo_venta'
        AND v.fecha>='$fecha_inicio' AND v.fecha<='$fecha_fin' AND td.id='$id_tienda' and v.id_usuario=$id_usuario");
        $obj8 = json_decode(json_encode($d), true);
        //dd($obj);

        $usuario= User::select('id','name')
        ->where('id','=',$id_usuario)
        ->get();

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTADO DE VENTAS';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;

        $usuarioActual=$usuario[0]->name;

        $detalles=$obj;
        $detalles2=$obj1;
        //dd($detalles);
        $detalles5=$obj5;
        //dd($detalles5);
        $detalles6=$obj6;
        $detalles7=$obj7;
        $detalles8=$obj8;

        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.reportes.venta.venta_general_usuario', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,

            'tipo_venta'=>$tipo_venta,
            'fecha_inicio'=>$fecha_inicio,
            'fecha_fin'=>$fecha_fin,
            'detalles'=>$detalles,
            'detalles2'=>$detalles2,
            'usuarioActual'=>$usuarioActual,

            'detalles5'=>$detalles5,
            'detalles6'=>$detalles6,
            'detalles7'=>$detalles7,
            'detalles8'=>$detalles8,
            
        ]);
        //return $pdf->stream('Venta.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Venta.pdf');

    }*/

    public function pdfVentaGeneralUsuario(Request $request)
    {
        ini_set('memory_limit', '20048M');
        // Validar los datos de entrada
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo_venta' => 'required|string',
            'id_tienda' => 'required|integer|exists:tienda,id',
            'id_usuario' => 'required|integer|exists:users,id',
        ]);

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $tipo_venta = $request->tipo_venta;
        $id_tienda = (int) $request->id_tienda;
        $id_usuario = (int) $request->id_usuario;

        // Consulta principal para los detalles de las ventas
        $ventas = DB::table('venta as v')
            ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
            ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
            ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
            ->join('users as u', 'v.id_usuario', '=', 'u.id')
            ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
            ->select(
                'v.sub_total',
                'v.descuento',
                'v.total',
                'c.nombre as cliente',
                't.nombre as tipo_pago',
                'f.nombre as forma_pago',
                'u.name as usuario',
                'td.nombre as tienda'
            )
            ->where('v.estado', '!=', 'Anulado')
            ->where('v.tipo_venta', $tipo_venta)
            ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
            ->where('v.id_tienda', $id_tienda)
            ->where('v.id_usuario', $id_usuario)
            ->get();

        // Consultas para totales resumidos
        $totalesObj = DB::table('venta as v')
            ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
            ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
            ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
            ->join('users as u', 'v.id_usuario', '=', 'u.id')
            ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
            ->where('v.estado', '!=', 'Anulado')
            ->where('v.tipo_venta', $tipo_venta)
            ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
            ->where('v.id_tienda', $id_tienda)
            ->where('v.id_usuario', $id_usuario)
            ->select(
                DB::raw('COALESCE(SUM(v.total), 0) as totalV'),
                DB::raw("COALESCE(SUM(CASE WHEN v.id_tipo_pago = 1 THEN v.total ELSE 0 END), 0) as totalC"),
                DB::raw("COALESCE(SUM(CASE WHEN v.id_tipo_pago = 2 THEN v.total ELSE 0 END), 0) as totalCr"),
                DB::raw('COALESCE(SUM(v.total_efectivo), 0) as totalEf'),
                DB::raw('COALESCE(SUM(v.total_deposito), 0) as totalDep')
            )
            ->first();

        // Obtener datos adicionales
        $usuario = User::select('id', 'name')->where('id', $id_usuario)->firstOrFail();
        $mi_empresa = MiEmpresa::select(
                'nombre',
                'nit',
                'representante',
                'direccion',
                'telefono',
                'localidad',
                'Correo',
                'sitio_web',
                'logo_sistema',
                'foto'
            )->firstOrFail();

        // Datos para la vista del PDF
        $data = [
            'title' => 'LISTADO DE VENTAS',
            'nombre_empresa' => $mi_empresa->nombre,
            'direccion_empresa' => $mi_empresa->direccion,
            'telefono_empresa' => $mi_empresa->telefono,
            'foto_empresa' => $mi_empresa->foto,
            'logo_sistema' => $mi_empresa->logo_sistema,
            'tipo_venta' => $tipo_venta,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'detalles' => $ventas,
            'totalV' => $totalesObj->totalV ?? 0,
            'totalC' => $totalesObj->totalC ?? 0,
            'totalCr' => $totalesObj->totalCr ?? 0,
            'totalEf' => $totalesObj->totalEf ?? 0,
            'totalDep' => $totalesObj->totalDep ?? 0,
            'usuarioActual' => $usuario->name,
        ];

        // Generar el PDF
        $pdf = \PDF::loadView('pdf.reportes.venta.venta_general_usuario', $data);
        return $pdf->setPaper('letter', 'portrait')->stream('Venta.pdf');
    }

    
    public function pdfVentaDetalladaUsuario(Request $request)
    {

        // Obtener parámetros
        ini_set('memory_limit', '20048M');
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin; 
        $tipo_venta = $request->tipo_venta;
        $id_tienda = $request->id_tienda;
        $id_usuario = $request->id_usuario;

        // Consulta de ventas detalladas
        $ventas = DB::table('venta as v')
        ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
        ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
        ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
        ->join('users as u', 'v.id_usuario', '=', 'u.id')
        ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
        ->where('v.estado', '!=', 'Anulado')
        ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
        ->where('v.id_tienda', $id_tienda)
        ->where('v.id_usuario', $id_usuario)
        ->select(
            'v.id', 'v.fecha', 'v.sub_total', 'v.descuento', 'v.total', 
            'v.total_efectivo', 'v.total_deposito', 'c.nombre as cliente', 
            't.nombre as tipo_pago', 'f.nombre as forma_pago', 
            'u.name as usuario', 'td.nombre as tienda'
        )
        ->get();


        // Cálculo de totales
        $totalVentas = Venta::where('estado', '!=', 'Anulado')
            ->where('tipo_venta', $tipo_venta)
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->where('id_tienda', $id_tienda)
            ->where('id_usuario', $id_usuario)
            ->sum('total');

        $totalContado = Venta::where('estado', '!=', 'Anulado')
            ->where('tipo_venta', $tipo_venta)
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->where('id_tienda', $id_tienda)
            ->where('id_tipo_pago', 1) // Contado
            ->where('id_usuario', $id_usuario)
            ->sum('total');

        $totalCredito = Venta::where('estado', '!=', 'Anulado')
            ->where('tipo_venta', $tipo_venta)
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->where('id_tienda', $id_tienda)
            ->where('id_tipo_pago', 2) // Crédito
            ->where('id_usuario', $id_usuario)
            ->sum('total');

        $totalEfectivo = Venta::where('estado', '!=', 'Anulado')
            ->where('tipo_venta', $tipo_venta)
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->where('id_tienda', $id_tienda)
            ->where('id_usuario', $id_usuario)
            ->sum('total_efectivo');

        $totalDeposito = Venta::where('estado', '!=', 'Anulado')
            ->where('tipo_venta', $tipo_venta)
            ->whereBetween('fecha', [$fecha_inicio, $fecha_fin])
            ->where('id_tienda', $id_tienda)
            ->where('id_usuario', $id_usuario)
            ->sum('total_deposito');

        // Detalle de ventas
        $detalles = DB::table('detalle_venta as dv')
        ->join('venta as v', 'dv.id_venta', '=', 'v.id')
        ->join('tienda_articulo as ta', 'dv.id_producto', '=', 'ta.id')
        ->join('articulo as a', 'ta.id_articulo', '=', 'a.id')
        ->where('v.estado', '!=', 'Anulado')
        ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
        ->where('v.id_tienda', $id_tienda)
        ->where('v.id_usuario', $id_usuario)
        ->select('dv.*', 'a.nombre_comercial as producto')
        ->get();


        // Detalle de ventas de paquetes
        $detallesPaquete = DB::table('detalle_venta_paquete as dvp')
        ->join('venta as v', 'dvp.id_venta', '=', 'v.id')
        ->join('paquetes as p', 'dvp.id_paquete', '=', 'p.id')
        ->where('v.estado', '!=', 'Anulado')
        ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
        ->where('v.id_tienda', $id_tienda)
        ->where('v.id_usuario', $id_usuario)
        ->select('dvp.*', 'p.nombre as producto')
        ->get();


        // Información de la empresa
        $mi_empresa = MiEmpresa::select('logo_sistema','nombre', 'nit', 'representante', 'direccion', 'telefono', 'localidad', 'Correo', 'sitio_web', 'foto')
            ->first();

        // Usuario actual
        $usuarioActual = User::find($id_usuario)->name;

        // Título y detalles
        $title = 'LISTADO DE VENTAS DETALLADO';
        $nombre_empresa = $mi_empresa->nombre;
        $direccion_empresa = $mi_empresa->direccion;
        $telefono_empresa = $mi_empresa->telefono;
        $foto_empresa = $mi_empresa->foto;
        $logo_sistema = $mi_empresa->logo_sistema;
 
        // Generar PDF
        $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_usuario', [
            'title' => $title,
            'nombre_empresa' => $nombre_empresa,
            'direccion_empresa' => $direccion_empresa,
            'telefono_empresa' => $telefono_empresa,
            'foto_empresa' => $foto_empresa,
            'logo_sistema' => $logo_sistema,
            'tipo_venta' => $tipo_venta,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'venta' => $ventas,
            'detalles' => $detalles,
            'detalles2' => [['totalV' => $totalVentas]],
            'detallesPaquete' => $detallesPaquete,
            'detalles5' => [['totalC' => $totalContado]],
            'detalles6' => [['totalCr' => $totalCredito]],
            'detalles7' => [['totalEf' => $totalEfectivo]],
            'detalles8' => [['totalDep' => $totalDeposito]],
            'usuarioActual' => $usuarioActual,
        ]);

        // Devolver el PDF generado
        return $pdf->setPaper('letter', 'portrait')->stream('Venta.pdf');
    }


    public function pdfVentaDetalladaEfectivoUsuario(Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;
            $id_usuario = $request->id_usuario;

            if (!$fecha_inicio || !$fecha_fin || !$tipo_venta || !$id_tienda || !$id_usuario) {
                return response()->json(['error' => 'Parámetros requeridos faltantes'], 400);
            }

            $ventas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->leftJoin('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_usuario', $id_usuario)
                ->where('v.id_forma_pago', 2) // Efectivo
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_efectivo',
                    'v.total_deposito',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Tipo no encontrado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Forma no encontrada") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario'),
                    DB::raw('COALESCE(td.nombre, "Tienda no encontrada") as tienda')
                )
                ->orderBy('v.id', 'asc')
                ->get();

            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_usuario', $id_usuario)
                ->where('v.id_forma_pago', 2)
                ->sum('v.total_efectivo');

            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = collect();
            $detallesPaquete = collect();

            if (!empty($ventasIds)) {
                $detalles = DB::table('detalle_venta as d')
                    ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1')
                    ->select(
                        'd.id_venta',
                        'd.cantidad',
                        DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'),
                        'd.costo_venta',
                        'd.sub_total'
                    )
                    ->get();

                $detallesPaquete = DB::table('detalle_venta_paquete as dvp')
                    ->leftJoin('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                    ->whereIn('dvp.id_venta', $ventasIds)
                    ->select(
                        'dvp.id_venta',
                        'dvp.cantidad',
                        DB::raw('COALESCE(pqt.nombre, "Paquete no encontrado") as producto'),
                        'dvp.costo_venta',
                        'dvp.sub_total'
                    )
                    ->get();
            }

            $mi_empresa = MiEmpresa::select(
                'nombre',
                'direccion',
                'telefono',
                'foto as logo_login',
                'logo_sistema',
            )->first();

            $usuarioActual=DB::table('users')->where('id', $id_usuario)->first()->name;

            if (!$mi_empresa) {
                $mi_empresa = (object) [
                    'nombre' => 'Mi Empresa',
                    'direccion' => 'Dirección no disponible',
                    'telefono' => 'Teléfono no disponible',
                    'logo_login' => null
                ];
            }

            $title = 'LISTADO DE VENTAS DETALLADO EFECTIVO';
            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_forma', [
                'title' => $title,
                'nombre_empresa' => $mi_empresa->nombre ?? 'Mi Empresa',
                'direccion_empresa' => $mi_empresa->direccion ?? 'Dirección no disponible',
                'telefono_empresa' => $mi_empresa->telefono ?? 'Teléfono no disponible',
                'foto_empresa' => $mi_empresa->logo_login ?? null,
                'logo_sistema' => $mi_empresa->logo_sistema ?? null,
                'usuarioActual' => $usuarioActual ?? null,
                'tipo_venta' => $tipo_venta,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'venta' => $ventas,
                'detalles' => $detalles,
                'detallesPaquete' => $detallesPaquete,
                'totalVentas' => $totalVentas ?? 0,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream('Venta_Efectivo_Usuario.pdf');

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaDetalladaEfectivoUsuario: ' . $e->getMessage(), $request->all());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    public function pdfVentaDetalladaTransfenciaUsuario(Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;
            $id_usuario = $request->id_usuario;

            if (!$fecha_inicio || !$fecha_fin || !$tipo_venta || !$id_tienda || !$id_usuario) {
                return response()->json(['error' => 'Parámetros requeridos faltantes'], 400);
            }

            $ventas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->leftJoin('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_usuario', $id_usuario)
                ->where('v.id_tipo_pago', 1)
                ->where('v.id_forma_pago', 3) // Transferencia
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_efectivo',
                    'v.total_deposito',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Tipo no encontrado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Forma no encontrada") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario'),
                    DB::raw('COALESCE(td.nombre, "Tienda no encontrada") as tienda')
                )
                ->orderBy('v.id', 'asc')
                ->get();

            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_usuario', $id_usuario)
                ->where('v.id_tipo_pago', 1)
                ->where('v.id_forma_pago', 3)
                ->sum('v.total_deposito');

            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = collect();
            $detallesPaquete = collect();

            if (!empty($ventasIds)) {
                $detalles = DB::table('detalle_venta as d')
                    ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1')
                    ->select(
                        'd.id_venta',
                        'd.cantidad',
                        DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'),
                        'd.costo_venta',
                        'd.sub_total'
                    )
                    ->get();

                $detallesPaquete = DB::table('detalle_venta_paquete as dvp')
                    ->leftJoin('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                    ->whereIn('dvp.id_venta', $ventasIds)
                    ->select(
                        'dvp.id_venta',
                        'dvp.cantidad',
                        DB::raw('COALESCE(pqt.nombre, "Paquete no encontrado") as producto'),
                        'dvp.costo_venta',
                        'dvp.sub_total'
                    )
                    ->get();
            }

            $mi_empresa = MiEmpresa::select(
                'nombre',
                'direccion',
                'telefono',
                'logo_sistema',
                'foto as logo_login'
            )->first();

            $usuarioActual=DB::table('users')->where('id', $id_usuario)->first()->name;


            $title = 'LISTADO DE VENTAS DETALLADO TRANSFERENCIA';
            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_forma', [
                'title' => $title,
                'nombre_empresa' => $mi_empresa->nombre ?? 'Mi Empresa',
                'direccion_empresa' => $mi_empresa->direccion ?? 'Dirección no disponible',
                'telefono_empresa' => $mi_empresa->telefono ?? 'Teléfono no disponible',
                'foto_empresa' => $mi_empresa->logo_login ?? null,
                'logo_sistema' => $mi_empresa->logo_sistema ?? null,
                'tipo_venta' => $tipo_venta,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'venta' => $ventas,
                'detalles' => $detalles,
                'usuarioActual'=>$usuarioActual,
                'detallesPaquete' => $detallesPaquete,
                'totalVentas' => $totalVentas ?? 0,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream('Venta_Transferencia_Usuario.pdf');

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaDetalladaTransfenciaUsuario: ' . $e->getMessage(), $request->all());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    public function pdfVentaDetalladaDepositoUsuario(Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;
            $id_usuario = $request->id_usuario;

            if (!$fecha_inicio || !$fecha_fin || !$tipo_venta || !$id_tienda || !$id_usuario) {
                return response()->json(['error' => 'Parámetros requeridos faltantes'], 400);
            }

            $ventas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->leftJoin('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_usuario', $id_usuario)
                ->where('v.id_forma_pago', 5) // Depósito
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_efectivo',
                    'v.total_deposito',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Tipo no encontrado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Forma no encontrada") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario'),
                    DB::raw('COALESCE(td.nombre, "Tienda no encontrada") as tienda')
                )
                ->orderBy('v.id', 'asc')
                ->get();

            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_usuario', $id_usuario)
                ->where('v.id_forma_pago', 5)
                ->sum('v.total_deposito');

            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = collect();
            $detallesPaquete = collect();

            if (!empty($ventasIds)) {
                $detalles = DB::table('detalle_venta as d')
                    ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1')
                    ->select(
                        'd.id_venta',
                        'd.cantidad',
                        DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'),
                        'd.costo_venta',
                        'd.sub_total'
                    )
                    ->get();

                $detallesPaquete = DB::table('detalle_venta_paquete as dvp')
                    ->leftJoin('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                    ->whereIn('dvp.id_venta', $ventasIds)
                    ->select(
                        'dvp.id_venta',
                        'dvp.cantidad',
                        DB::raw('COALESCE(pqt.nombre, "Paquete no encontrado") as producto'),
                        'dvp.costo_venta',
                        'dvp.sub_total'
                    )
                    ->get();
            }

            $mi_empresa = MiEmpresa::select(
                'nombre',
                'direccion',
                'telefono',
                'logo_sistema',
                'foto as logo_login'
            )->first();

            $usuarioActual=DB::table('users')->where('id', $id_usuario)->first()->name;

            $title = 'LISTADO DE VENTAS DETALLADO DEPOSITO';
            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_forma', [
                'title' => $title,
                'nombre_empresa' => $mi_empresa->nombre ?? 'Mi Empresa',
                'direccion_empresa' => $mi_empresa->direccion ?? 'Dirección no disponible',
                'telefono_empresa' => $mi_empresa->telefono ?? 'Teléfono no disponible',
                'foto_empresa' => $mi_empresa->logo_login ?? null,
                'logo_sistema' => $mi_empresa->logo_sistema ?? null,
                'usuarioActual' => $usuarioActual ?? null,
                'tipo_venta' => $tipo_venta,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'venta' => $ventas,
                'detalles' => $detalles,
                'detallesPaquete' => $detallesPaquete,
                'totalVentas' => $totalVentas ?? 0,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream('Venta_Deposito_Usuario.pdf');

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaDetalladaDepositoUsuario: ' . $e->getMessage(), $request->all());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    public function pdfVentaDetalladaMixtaUsuario(Request $request)
    {
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;
            $id_usuario = $request->id_usuario;

            if (!$fecha_inicio || !$fecha_fin || !$tipo_venta || !$id_tienda || !$id_usuario) {
                return response()->json(['error' => 'Parámetros requeridos faltantes'], 400);
            }

            $ventas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->leftJoin('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_usuario', $id_usuario)
                ->where('v.id_forma_pago', 6) // Mixta
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_efectivo',
                    'v.total_deposito',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Tipo no encontrado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Forma no encontrada") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario'),
                    DB::raw('COALESCE(td.nombre, "Tienda no encontrada") as tienda')
                )
                ->orderBy('v.id', 'asc')
                ->get();

            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_usuario', $id_usuario)
                ->where('v.id_forma_pago', 6)
                ->sum('v.total');

            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = collect();
            $detallesPaquete = collect();

            if (!empty($ventasIds)) {
                $detalles = DB::table('detalle_venta as d')
                    ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1')
                    ->select(
                        'd.id_venta',
                        'd.cantidad',
                        DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'),
                        'd.costo_venta',
                        'd.sub_total'
                    )
                    ->get();

                $detallesPaquete = DB::table('detalle_venta_paquete as dvp')
                    ->leftJoin('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                    ->whereIn('dvp.id_venta', $ventasIds)
                    ->select(
                        'dvp.id_venta',
                        'dvp.cantidad',
                        DB::raw('COALESCE(pqt.nombre, "Paquete no encontrado") as producto'),
                        'dvp.costo_venta',
                        'dvp.sub_total'
                    )
                    ->get();
            }

            $mi_empresa = MiEmpresa::select(
                'nombre',
                'direccion',
                'telefono',
                'logo_sistema',
                'foto as logo_login'
            )->first();

            $usuarioActual=DB::table('users')->where('id', $id_usuario)->first()->name;


            $title = 'LISTADO DE VENTAS DETALLADO MIXTA';
            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_mixta', [
                'title' => $title,
                'nombre_empresa' => $mi_empresa->nombre ?? 'Mi Empresa',
                'direccion_empresa' => $mi_empresa->direccion ?? 'Dirección no disponible',
                'telefono_empresa' => $mi_empresa->telefono ?? 'Teléfono no disponible',
                'foto_empresa' => $mi_empresa->logo_login ?? null,
                'logo_sistema' => $mi_empresa->logo_sistema ?? null,
                'usuarioActual' => $usuarioActual ?? null,
                'tipo_venta' => $tipo_venta,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'venta' => $ventas,
                'detalles' => $detalles,
                'detallesPaquete' => $detallesPaquete,
                'totalVentas' => $totalVentas ?? 0,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream('Venta_Mixta_Usuario.pdf');

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaDetalladaMixtaUsuario: ' . $e->getMessage(), $request->all());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }
   

    public function pdfVentaDetalladaQrUsuario(Request $request)
    {
        // Obtener parámetros
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin; 
        $tipo_venta = $request->tipo_venta;
        $id_tienda = $request->id_tienda;
        $id_usuario = $request->id_usuario;

        // Consulta de ventas detalladas para la forma de pago 4 (Pago por QR)
        $ventas = DB::table('venta as v')
            ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
            ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
            ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
            ->join('users as u', 'v.id_usuario', '=', 'u.id')
            ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
            ->where('v.estado', '!=', 'Anulado')
            ->where('v.id_forma_pago', 4) // Forma de pago 4 (QR)
            ->where('v.tipo_venta', $tipo_venta)
            ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
            ->where('v.id_tienda', $id_tienda)
            ->where('v.id_usuario', $id_usuario)
            ->select(
                'v.id', 'v.fecha', 'v.sub_total', 'v.descuento', 'v.total', 
                'v.total_deposito', 'c.nombre as cliente', 't.nombre as tipo_pago', 
                'f.nombre as forma_pago', 'u.name as usuario', 'td.nombre as tienda'
            )
            ->get();

        // Sumar total del depósito para la forma de pago 4 (Pago por QR)
        $totalVentasDeposito = DB::table('venta as v')
            ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
            ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
            ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
            ->join('users as u', 'v.id_usuario', '=', 'u.id')
            ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
            ->where('v.estado', '!=', 'Anulado')
            ->where('v.id_forma_pago', 4) // Forma de pago 4 (QR)
            ->where('v.tipo_venta', $tipo_venta)
            ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
            ->where('v.id_tienda', $id_tienda)
            ->where('v.id_usuario', $id_usuario)
            ->sum('v.total_deposito');

        // Detalle de ventas
        $detallesVenta = DB::table('detalle_venta as dv')
            ->join('venta as v', 'dv.id_venta', '=', 'v.id')
            ->join('tienda_articulo as ta', 'dv.id_producto', '=', 'ta.id')
            ->join('articulo as a', 'ta.id_articulo', '=', 'a.id')
            ->where('dv.estado', '!=', '1') // No anulado
            ->whereIn('v.id_forma_pago', [4]) // Solo forma de pago QR
            ->select('dv.id_venta', 'dv.cantidad', 'a.nombre_comercial as producto', 'dv.costo_venta', 'dv.sub_total')
            ->get();


        // Información de la empresa
        $mi_empresa = MiEmpresa::select(
            'nombre', 'nit', 'representante', 'direccion', 'telefono', 
            'localidad', 'Correo', 'sitio_web', 'foto', 'logo_sistema'
        )->first();

        $usuarioActual = User::find($id_usuario)->name;

        // Título y detalles
        $title = 'LISTADO DE VENTAS DETALLADO QR';
        $nombre_empresa = $mi_empresa->nombre;
        $direccion_empresa = $mi_empresa->direccion;
        $telefono_empresa = $mi_empresa->telefono;
        $foto_empresa = $mi_empresa->foto;
        $logo_sistema = $mi_empresa->logo_sistema;

        // Preparar datos para el PDF
        $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_forma_qr', [
            'title' => $title,
            'nombre_empresa' => $nombre_empresa,
            'direccion_empresa' => $direccion_empresa,
            'telefono_empresa' => $telefono_empresa,
            'foto_empresa' => $foto_empresa,
            'logo_sistema' => $logo_sistema,
            'tipo_venta' => $tipo_venta,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
            'venta' => $ventas,
            'detalles' => $detallesVenta,
            'totalVentasDeposito' => $totalVentasDeposito,
            'usuarioActual'=> $usuarioActual
        ]);

        // Devolver el PDF generado
        return $pdf->setPaper('letter', 'portrait')->stream('Venta.pdf');
    }

    
    //Dashboard
    /*public function listarProductoMes(Request $request){
            $anio = $request->anio;
            $fecha1 = now()->toDateString();
        $fecha2 = now()->addDays(360)->toDateString();
            //dd($anio);
            $tienda_articulo = Lote::join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
            ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
            ->join('categoria','articulo.id_categoria','=','categoria.id')
            ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
            ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
            ->select('lote.id','tienda_articulo.id as id_articulo','tienda_articulo.id_tienda',
            'articulo.nombre_comercial','articulo.nombre_generico',
            'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.precio_blister','articulo.precio_caja',
            'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','lote.cantidad as stock','lote.fecha_vecimiento',
            'articulo.descripcion','articulo.cod_proveedor','articulo.cantidad_blister','articulo.cantidad_caja','articulo.venta_presentacion',
            'articulo.ubicacion','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion') 
            ->where('lote.cantidad', '!=', 0)
            ->where('lote.estado', '!=', 0)
            // ->whereYear('lote.fecha_vecimiento',$anio)
            ->whereBetween('lote.fecha_vecimiento', [$fecha1, $fecha2])
            ->orderBy('lote.fecha_vecimiento', 'asc')
            ->get();
            //->get();

            $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
            ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
            ->get();
    
            $title='LISTA DE PRODUCTOS';
            $nombre_empresa=$mi_empresa[0]->nombre;
            $direccion_empresa=$mi_empresa[0]->direccion;
            $telefono_empresa=$mi_empresa[0]->telefono;
            $foto_empresa=$mi_empresa[0]->foto;
    
            $detalles=$tienda_articulo;
            
            $cont=Articulo::count();
            $pdf = \PDF::loadView('pdf.reportes.producto.producto_mes', [
    
                'title'=>$title,
                'nombre_empresa'=>$nombre_empresa,
                'direccion_empresa'=>$direccion_empresa,
                'telefono_empresa'=>$telefono_empresa,
                'foto_empresa'=>$foto_empresa,
    
                'detalles'=>$detalles,
                
            ]);
            //return $pdf->stream('Producto.pdf');
            return $pdf->setPaper('letter', 'landscape')->stream('Producto.pdf');
    }*/

    public function listarProductoMes(Request $request)
    {
        ini_set('memory_limit', '200048M');
        $fecha1 = now()->toDateString();
        $fecha2 = now()->addDays(360)->toDateString();
        $detalles = Lote::join('tienda_articulo', 'lote.id_producto', '=', 'tienda_articulo.id')
            ->join('articulo', 'tienda_articulo.id_articulo', '=', 'articulo.id')
            ->join('categoria', 'articulo.id_categoria', '=', 'categoria.id')
            ->join('proveedor', 'articulo.id_proveedor', '=', 'proveedor.id')
            ->join('unidad_medida', 'articulo.id_unidad', '=', 'unidad_medida.id')
            ->select(
                'lote.id',
                'tienda_articulo.id as id_articulo',
                'articulo.nombre_comercial',
                'articulo.nombre_generico',
                'articulo.costo_unitario',
                'articulo.precio_blister',
                'articulo.precio_caja',
                'lote.cantidad as stock',
                'lote.fecha_vecimiento',
                'articulo.ubicacion',
                'proveedor.nombre as laboratorio'
            )
            ->where('lote.cantidad', '!=', 0)
            ->where('lote.estado', '!=', 0)
            ->whereBetween('lote.fecha_vecimiento', [$fecha1, $fecha2])
            ->orderBy('lote.fecha_vecimiento', 'asc')
            ->get();
        $empresa = MiEmpresa::select('nombre', 'direccion', 'telefono', 'foto')->first();
        $pdf = \PDF::loadView('pdf.reportes.producto.producto_mes', compact('detalles', 'empresa'))
            ->setPaper('letter', 'landscape');

        return $pdf->stream('Producto.pdf');
    }

    public function listarProductoMes1(Request $request){
        $anio = $request->anio;
        $id_proveedor = $request->id_proveedor;
        $id_tienda = $request->id_tienda;
        $tienda_articulo = Lote::join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('categoria','articulo.id_categoria','=','categoria.id')
        ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
        ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
        ->select('lote.id','tienda_articulo.id as id_articulo','tienda_articulo.id_tienda',
        'articulo.nombre_comercial','articulo.nombre_generico',
        'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.precio_blister','articulo.precio_caja',
        'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','lote.cantidad as stock','lote.fecha_vecimiento',
        'articulo.descripcion','articulo.cod_proveedor','articulo.cantidad_blister','articulo.cantidad_caja','articulo.venta_presentacion',
        'articulo.ubicacion','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion') 
        ->where('lote.cantidad', '!=', 0)
        ->where('lote.estado', '!=', 0)
        ->where('proveedor.id',$id_proveedor)
        ->where('tienda_articulo.id_tienda',$id_tienda)
        ->whereYear('lote.fecha_vecimiento',$anio)
        ->orderBy('lote.fecha_vecimiento', 'asc')
        ->get();


        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTA DE PRODUCTOS';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;

        $detalles=$tienda_articulo;
        
        $cont=Articulo::count();
        $pdf = \PDF::loadView('pdf.reportes.producto.producto_mes', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,

            'detalles'=>$detalles,
            
        ]);
        return $pdf->setPaper('letter', 'landscape')->stream('Producto.pdf');
    }

    /*public function listarProductoMeses(Request $request){
        $anio = $request->anio;
        $fecha1 = now()->toDateString();
        $fecha2 = now()->addDays(90)->toDateString();
        //dd($anio);
        $tienda_articulo = Lote::join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('categoria','articulo.id_categoria','=','categoria.id')
        ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
        ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
        ->select('lote.id','tienda_articulo.id as id_articulo','tienda_articulo.id_tienda',
        'articulo.nombre_comercial','articulo.nombre_generico',
        'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.precio_blister','articulo.precio_caja',
        'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','lote.cantidad as stock','lote.fecha_vecimiento',
        'articulo.descripcion','articulo.cod_proveedor','articulo.cantidad_blister','articulo.cantidad_caja','articulo.venta_presentacion',
        'articulo.ubicacion','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion') 
        ->where('lote.cantidad', '!=', 0)
        ->where('lote.estado', '!=', 0)
        ->whereBetween('lote.fecha_vecimiento', [$fecha1, $fecha2])
        // ->whereYear('lote.fecha_vecimiento',$anio)
        ->orderBy('lote.fecha_vecimiento', 'asc')
        ->get();
        //->get();

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTA DE PRODUCTOS';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;

        $detalles=$tienda_articulo;
        
        $cont=Articulo::count();
        $pdf = \PDF::loadView('pdf.reportes.producto.producto_mes', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,

            'detalles'=>$detalles,
            
        ]);
        //return $pdf->stream('Producto.pdf');
        return $pdf->setPaper('letter', 'landscape')->stream('Producto.pdf');
    }*/

    public function listarProductoMeses(Request $request)
    {
        ini_set('memory_limit', '200048M');
        $fecha1 = now()->toDateString();
        $fecha2 = now()->addDays(90)->toDateString();
        $detalles = Lote::join('tienda_articulo', 'lote.id_producto', '=', 'tienda_articulo.id')
            ->join('articulo', 'tienda_articulo.id_articulo', '=', 'articulo.id')
            ->join('categoria', 'articulo.id_categoria', '=', 'categoria.id')
            ->join('proveedor', 'articulo.id_proveedor', '=', 'proveedor.id')
            ->join('unidad_medida', 'articulo.id_unidad', '=', 'unidad_medida.id')
            ->select(
                'lote.id',
                'tienda_articulo.id as id_articulo',
                'articulo.nombre_comercial',
                'articulo.nombre_generico',
                'articulo.costo_unitario',
                'articulo.precio_blister',
                'articulo.precio_caja',
                'articulo.ubicacion',
                'lote.cantidad as stock',
                'lote.fecha_vecimiento',
                'proveedor.nombre as laboratorio'
            )
            ->where('lote.cantidad', '!=', 0)
            ->where('lote.estado', '!=', 0)
            ->whereBetween('lote.fecha_vecimiento', [$fecha1, $fecha2])
            ->orderBy('lote.fecha_vecimiento', 'asc')
            ->get();

        $empresa = MiEmpresa::select('nombre', 'direccion', 'telefono', 'foto')->first();
        
        $title = 'LISTA DE PRODUCTOS';
        $pdf = \PDF::loadView('pdf.reportes.producto.producto_mes', compact('detalles', 'empresa', 'title'))
            ->setPaper('letter', 'landscape');

        return $pdf->stream('Producto.pdf');
    }

    public function listarProductoMeses1(Request $request){
        $anio = $request->anio;
        $fecha1 = now()->toDateString();
        $fecha2 = now()->addDays(90)->toDateString();
        $id_proveedor = $request->id_proveedor;
        $id_tienda = $request->id_tienda;
        //dd($anio);
        $tienda_articulo = Lote::join('tienda_articulo','lote.id_producto','=','tienda_articulo.id')
        ->join('articulo','tienda_articulo.id_articulo','=','articulo.id')
        ->join('categoria','articulo.id_categoria','=','categoria.id')
        ->join('proveedor','articulo.id_proveedor','=','proveedor.id')
        ->join('unidad_medida','articulo.id_unidad','=','unidad_medida.id')
        ->select('lote.id','tienda_articulo.id as id_articulo','tienda_articulo.id_tienda',
        'articulo.nombre_comercial','articulo.nombre_generico',
        'articulo.costo_compra','articulo.costo_unitario','articulo.costo_mayorista','articulo.precio_blister','articulo.precio_caja',
        'articulo.costo_preferencial','articulo.id_categoria','categoria.nombre as categoria','lote.cantidad as stock','lote.fecha_vecimiento',
        'articulo.descripcion','articulo.cod_proveedor','articulo.cantidad_blister','articulo.cantidad_caja','articulo.venta_presentacion',
        'articulo.ubicacion','proveedor.nombre as laboratorio','unidad_medida.nombre as presentacion') 
        ->where('lote.cantidad', '!=', 0)
        ->where('lote.estado', '!=', 0)
        ->where('proveedor.id',$id_proveedor)
        ->where('tienda_articulo.id_tienda',$id_tienda)
        ->whereBetween('lote.fecha_vecimiento', [$fecha1, $fecha2])
        ->whereYear('lote.fecha_vecimiento',$anio)
        ->orderBy('lote.fecha_vecimiento', 'asc')
        ->get();
        // dd($tienda_articulo);
        //->get();

        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTA DE PRODUCTOS';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;

        $detalles=$tienda_articulo;
        
        $cont=Articulo::count();
        $pdf = \PDF::loadView('pdf.reportes.producto.producto_mes', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,

            'detalles'=>$detalles,
            
        ]);
        //return $pdf->stream('Producto.pdf');
        return $pdf->setPaper('letter', 'landscape')->stream('Producto.pdf');
    }
    
    public function pdfVentaDetalladaCliente(Request $request)
    {
        try {
            // Validar entrada
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin'     => 'required|date|after_or_equal:fecha_inicio',
                'tipo_venta'    => 'required|string',
                'id_tienda'     => 'required|exists:tienda,id',
                'id_cliente'    => 'required|exists:cliente,id',
            ]);

            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin    = $request->fecha_fin;
            $tipo_venta   = $request->tipo_venta;
            $id_tienda    = $request->id_tienda;
            $id_cliente   = $request->id_cliente;

            // === 1. Datos de la empresa ===
            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            if (!$empresa) {
                return response()->json(['error' => 'Datos de la empresa no configurados'], 500);
            }

            // === 2. Cliente ===
            $cliente = DB::table('cliente')
                ->where('id', $id_cliente)
                ->first(['nombre']);

            if (!$cliente) {
                return response()->json(['error' => 'Cliente no encontrado'], 404);
            }

            // === 3. Ventas del cliente en el rango ===
            $ventas = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('td.id', $id_tienda)
                ->where('v.id_cliente', $id_cliente)
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_efectivo',
                    'v.total_deposito',
                    'c.nombre as cliente',
                    't.nombre as tipo_pago',
                    'f.nombre as forma_pago',
                    'u.name as usuario',
                    'td.nombre as tienda'
                )
                ->orderBy('v.fecha', 'desc')
                ->get();

       

            // === 4. Totales del cliente ===
            $totales = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin])
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_cliente', $id_cliente)
                ->select(
                    DB::raw('SUM(v.total) as totalV'),
                    DB::raw('SUM(CASE WHEN v.id_tipo_pago = 1 THEN v.total ELSE 0 END) as totalC'),
                    DB::raw('SUM(CASE WHEN v.id_tipo_pago = 2 THEN v.total ELSE 0 END) as totalCr'),
                    DB::raw('SUM(v.total_efectivo) as totalEf'),
                    DB::raw('SUM(v.total_deposito) as totalDep')
                )
                ->first();

            // === 5. Detalles de productos y paquetes ===
            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = [];
            $detallesPaquete = [];

            if (!empty($ventasIds)) {
                // Productos
                $detalles = DB::table('detalle_venta as d')
                    ->join('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->join('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->select('d.id_venta', 'd.cantidad', 'p.nombre_comercial as producto', 'd.costo_venta', 'd.sub_total')
                    ->get()
                    ->groupBy('id_venta');

                // Paquetes
                $detallesPaquete = DB::table('detalle_venta_paquete as dvp')
                    ->join('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                    ->whereIn('dvp.id_venta', $ventasIds)
                    ->select('dvp.id_venta', 'dvp.cantidad', 'pqt.nombre as producto', 'dvp.costo_venta', 'dvp.sub_total')
                    ->get()
                    ->groupBy('id_venta');
            }

            // === 6. Generar PDF con DomPDF ===
            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_cliente', [
                'title'               => 'LISTADO DE VENTAS DETALLADO POR CLIENTE',
                'nombre_empresa'      => $empresa->nombre,
                'direccion_empresa'   => $empresa->direccion,
                'telefono_empresa'    => $empresa->telefono,
                'foto_empresa'        => $empresa->foto,
                'logo_sistema'        => $empresa->logo_sistema,

                'fecha_inicio'        => $fecha_inicio,
                'fecha_fin'           => $fecha_fin,
                'tipo_venta'          => $tipo_venta,
                'nombre_cliente'      => $cliente->nombre,

                'ventas'              => $ventas,
                'totales'             => $totales,
                'detalles'            => $detalles,
                'detallesPaquete'     => $detallesPaquete,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream("Ventas_Cliente_{$cliente->nombre}.pdf");

        } catch (\Exception $e) {
            Log::error('Error en pdfVentaDetalladaCliente: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    public function pdfVentaClienteCredito(Request $request){

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin; 
        //dd($id_cliente);


        $x=DB::select("SELECT pago.id,c_x_cobrar.fecha,cliente.nombre as cliente,c_x_cobrar.amortizacion as total,users.name as usuario,forma_pago.nombre as forma
        FROM venta  INNER JOIN pago
        ON pago.id_venta=venta.id 
        INNER JOIN cliente 
        ON venta.id_cliente=cliente.id 
        INNER JOIN c_x_cobrar 
        ON c_x_cobrar.id_pago=pago.id 
        LEFT JOIN users
        ON c_x_cobrar.id_usuario=users.id 
        LEFT JOIN forma_pago
        ON c_x_cobrar.id_forma_pago=forma_pago.id 
        WHERE c_x_cobrar.amortizacion>0 
        and c_x_cobrar.fecha>='$fecha_inicio' AND c_x_cobrar.fecha<='$fecha_fin'
        ORDER BY c_x_cobrar.id");
        $obj = json_decode(json_encode($x), true);

        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTADO DE PAGO AL CREDITO';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;

        //dd($nombre_empresa);

        $detalles=$obj;
        //dd($detalles);
        $total_general = 0;
        foreach($detalles as $det)
        {
            $total_general=$total_general+$det['total'];
        }

        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.reportes.venta.venta_credito', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,
            'fecha_inicio'=>$fecha_inicio,
            'fecha_fin'=>$fecha_fin,
            'detalles'=>$detalles,
            'total_general'=>$total_general,
            
        ]);
        //return $pdf->stream('Ventas.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Venta.pdf');

    }

    public function pdfVentaClienteCreditoUsuario(Request $request){

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin; 
        $id_usuario = $request->id_usuario; 
        $x=DB::select("SELECT pago.id,c_x_cobrar.fecha,cliente.nombre as cliente, c_x_cobrar.amortizacion as total,users.name as usuario,forma_pago.nombre as forma
        FROM venta  INNER JOIN pago
        ON pago.id_venta=venta.id 
        INNER JOIN cliente 
        ON venta.id_cliente=cliente.id 
        INNER JOIN c_x_cobrar 
        ON c_x_cobrar.id_pago=pago.id 
        LEFT JOIN users
        ON c_x_cobrar.id_usuario=users.id 
        LEFT JOIN forma_pago
        ON c_x_cobrar.id_forma_pago=forma_pago.id 
        WHERE c_x_cobrar.amortizacion>0 
        and c_x_cobrar.fecha>='$fecha_inicio' AND c_x_cobrar.fecha<='$fecha_fin' and c_x_cobrar.id_usuario = '$id_usuario'
        ORDER BY c_x_cobrar.id");
        $obj = json_decode(json_encode($x), true);

        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTADO DE PAGO AL CREDITO';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;

        $detalles=$obj;
        //dd($detalles);
        $total_general = 0;
        foreach($detalles as $det)
        {
            $total_general=$total_general+$det['total'];
        }

        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.reportes.venta.venta_credito', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,

            'fecha_inicio'=>$fecha_inicio,
            'fecha_fin'=>$fecha_fin,
            'detalles'=>$detalles,
            'total_general'=>$total_general,
            
        ]);
        return $pdf->setPaper('letter', 'portrait')->stream('Venta.pdf');

    }
    public function pdfCompraProveedorCredito(Request $request){

        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin; 

        $x=DB::select("SELECT pago_compra.id,c_x_pagar.fecha,proveedor.nombre as proveedor,c_x_pagar.amortizacion as total,forma_pago.nombre as forma
        FROM compra INNER JOIN pago_compra
        ON pago_compra.id_compra=compra.id  INNER JOIN proveedor
        ON compra.id_proveedor=proveedor.id INNER JOIN c_x_pagar
        ON c_x_pagar.id_pago=pago_compra.id LEFT JOIN forma_pago
        ON c_x_pagar.id_forma_pago=forma_pago.id
        WHERE c_x_pagar.amortizacion>0 
        and c_x_pagar.fecha>='$fecha_inicio' AND c_x_pagar.fecha<='$fecha_fin'
        ORDER BY c_x_pagar.id");
        $obj = json_decode(json_encode($x), true);

        $mi_empresa= MiEmpresa::select('logo_sistema','mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTADO DE PAGO AL CREDITO COMPRA';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;
        $logo_sistema=$mi_empresa[0]->logo_sistema;

        $detalles=$obj;

        $total_general = 0;
        foreach($detalles as $det)
        {
            $total_general=$total_general+$det['total'];

        }

        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.reportes.compra.compra_credito', [

            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,

            'fecha_inicio'=>$fecha_inicio,
            'fecha_fin'=>$fecha_fin,
            'detalles'=>$detalles,
            'total_general'=>$total_general,
            
        ]);
        //return $pdf->stream('Ventas.pdf');
        return $pdf->setPaper('letter', 'portrait')->stream('Venta.pdf');

    }
    //Cantidad Venta
    public function pdfHistorialProductoUsuario(Request $request){

        $id_lote = $request->id_lote;
        $fecha_producto = $request->fecha_producto; 
        $fecha_fin = $request->fecha_fin; 

        $x=DB::select("SELECT detalle_venta.cantidad, articulo.nombre_comercial, detalle_venta.id_venta, detalle_venta.id_lote, 
        venta.fecha,proveedor.nombre as laboratorio, users.name as usuario
        FROM detalle_venta, articulo, venta ,proveedor,users
        WHERE  detalle_venta.id_producto = articulo.id and detalle_venta.id_venta = venta.id and articulo.id_proveedor=proveedor.id
         and venta.id_usuario=users.id and venta.estado!='Anulado'
         and venta.fecha>='$fecha_producto' AND venta.fecha<='$fecha_fin' and detalle_venta.id_lote='$id_lote'");
        $obj = json_decode(json_encode($x), true);

        $y=DB::select("SELECT SUM(detalle_venta.cantidad) as cantidadT
        FROM detalle_venta, articulo, venta ,proveedor,users
        WHERE  detalle_venta.id_producto = articulo.id and detalle_venta.id_venta = venta.id and articulo.id_proveedor=proveedor.id
         and venta.id_usuario=users.id and venta.estado!='Anulado'
         and venta.fecha>='$fecha_producto' AND venta.fecha<='$fecha_fin' and detalle_venta.id_lote='$id_lote'");
        $obj1 = json_decode(json_encode($y), true);

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();

        $title='LISTADO DE HISTORIAL PRODUCTO POR USUARIOS';
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;

        $detalles=$obj;
        $detalles1=$obj1;

        $cont=Venta::count();
        $pdf = \PDF::loadView('pdf.reportes.producto.historial_producto', [
            'title'=>$title,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
            'foto_empresa'=>$foto_empresa,
            'fecha_producto'=>$fecha_producto,
            'detalles'=>$detalles,
            'detalles1'=>$detalles1,
        ]);
        return $pdf->setPaper('letter', 'portrait')->stream('Venta.pdf');

    }

    public function listadoArqueos(Request $request)
    {
        $query = DB::table('arqueo_caja as a')
            ->join('users as u', 'a.id_usuario', '=', 'u.id')
            ->select(
                'a.id',
                'a.fecha_apertura',
                'a.fecha_cierre',
                'a.saldo_efectivo',
                'a.saldo_sistema',
                'a.diferencia',
                'a.estado',
                'u.name as usuario'
            );

        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
            $query->whereBetween('a.fecha_apertura', [
                $request->fecha_inicio . ' 00:00:00',
                $request->fecha_fin . ' 23:59:59'
            ]);
        }

        if ($request->filled('id_usuario')) {
            $query->where('a.id_usuario', $request->id_usuario);
        }

        return $query->orderBy('a.fecha_apertura', 'desc')->get();
    }


    // EFECTIVO POR ARQUEO
    public function pdfVentaDetalladaEfectivoPorArqueo(Request $request)
    {
        try {
            $id_caja = $request->id_caja;
            if (!$id_caja) {
                return response()->json(['error' => 'ID de arqueo requerido'], 400);
            }

            $ventas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->leftJoin('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.control', $id_caja)
                ->where('v.id_forma_pago', 2) // Efectivo
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_efectivo',
                    'v.total_deposito',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Tipo no encontrado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Forma no encontrada") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario'),
                    DB::raw('COALESCE(td.nombre, "Tienda no encontrada") as tienda')
                )
                ->orderBy('v.id', 'asc')
                ->get();

            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.control', $id_caja)
                ->where('v.id_forma_pago', 2)
                ->sum('v.total_efectivo');

            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = collect();
            if (!empty($ventasIds)) {
                $detalles = DB::table('detalle_venta as d')
                    ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1')
                    ->select(
                        'd.id_venta',
                        'd.cantidad',
                        DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'),
                        'd.costo_venta',
                        'd.sub_total'
                    )
                    ->get();
            }

            $mi_empresa = $this->obtenerEmpresa();
            $title = 'VENTAS EN EFECTIVO - ARQUEO #' . $id_caja;

            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_forma', [
                'title' => $title,
                'nombre_empresa' => $mi_empresa->nombre,
                'direccion_empresa' => $mi_empresa->direccion,
                'telefono_empresa' => $mi_empresa->telefono,
                'foto_empresa' => $mi_empresa->foto,
                'logo_sistema' => $mi_empresa->logo_sistema,
                'tipo_venta' => 'Venta',
                'fecha_inicio' => '',
                'fecha_fin' => '',
                'venta' => $ventas,
                'detalles' => $detalles,
                'totalVentas' => $totalVentas ?? 0,
                // Nuevos datos opcionales:
                'usuario_arqueo' => $request->usuario_arqueo ?? null,
                'fecha_inicio_arqueo' => $request->fecha_inicio_arqueo ?? null,
                'fecha_fin_arqueo' => $request->fecha_fin_arqueo ?? null,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream("Venta_Efectivo_Arqueo_{$id_caja}.pdf");

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaDetalladaEfectivoPorArqueo: ' . $e->getMessage(), $request->all());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    // TRANSFERENCIA POR ARQUEO
    public function pdfVentaDetalladaTransfenciaPorArqueo(Request $request)
    {
        try {
            $id_caja = $request->id_caja;
            if (!$id_caja) {
                return response()->json(['error' => 'ID de arqueo requerido'], 400);
            }

            $ventas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->leftJoin('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.control', $id_caja)
                ->where('v.id_tipo_pago', 1)
                ->where('v.id_forma_pago', 3) // Transferencia
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_efectivo',
                    'v.total_deposito',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Tipo no encontrado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Forma no encontrada") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario'),
                    DB::raw('COALESCE(td.nombre, "Tienda no encontrada") as tienda')
                )
                ->orderBy('v.id', 'asc')
                ->get();

            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.control', $id_caja)
                ->where('v.id_tipo_pago', 1)
                ->where('v.id_forma_pago', 3)
                ->sum('v.total_deposito');

            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = collect();
            if (!empty($ventasIds)) {
                $detalles = DB::table('detalle_venta as d')
                    ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1')
                    ->select(
                        'd.id_venta',
                        'd.cantidad',
                        DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'),
                        'd.costo_venta',
                        'd.sub_total'
                    )
                    ->get();
            }

            $mi_empresa = $this->obtenerEmpresa();
            $title = 'VENTAS POR TRANSFERENCIA - ARQUEO #' . $id_caja;

            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_forma', [
                'title' => $title,
                'nombre_empresa' => $mi_empresa->nombre,
                'direccion_empresa' => $mi_empresa->direccion,
                'telefono_empresa' => $mi_empresa->telefono,
                'foto_empresa' => $mi_empresa->foto,
                'logo_sistema' => $mi_empresa->logo_sistema,
                'tipo_venta' => 'Venta',
                'fecha_inicio' => '',
                'fecha_fin' => '',
                'venta' => $ventas,
                'detalles' => $detalles,
                'totalVentas' => $totalVentas ?? 0,
                // Nuevos datos opcionales:
                'usuario_arqueo' => $request->usuario_arqueo ?? null,
                'fecha_inicio_arqueo' => $request->fecha_inicio_arqueo ?? null,
                'fecha_fin_arqueo' => $request->fecha_fin_arqueo ?? null,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream("Venta_Transferencia_Arqueo_{$id_caja}.pdf");

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaDetalladaTransfenciaPorArqueo: ' . $e->getMessage(), $request->all());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    // QR POR ARQUEO
    public function pdfVentaDetalladaQrPorArqueo(Request $request)
    {
        try {
            $id_caja = $request->id_caja;
            if (!$id_caja) {
                return response()->json(['error' => 'ID de arqueo requerido'], 400);
            }

            $ventas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->leftJoin('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.control', $id_caja)
                ->where('v.id_forma_pago', 4) // QR
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_deposito',
                    'v.total_efectivo',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Tipo no encontrado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Forma no encontrada") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario'),
                    DB::raw('COALESCE(td.nombre, "Tienda no encontrada") as tienda')
                )
                ->orderBy('v.id', 'asc')
                ->get();

            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.control', $id_caja)
                ->where('v.id_forma_pago', 4)
                ->sum('v.total_deposito');

            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = collect();
            if (!empty($ventasIds)) {
                $detalles = DB::table('detalle_venta as d')
                    ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1')
                    ->select(
                        'd.id_venta',
                        'd.cantidad',
                        DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'),
                        'd.costo_venta',
                        'd.sub_total'
                    )
                    ->get();
            }

            $mi_empresa = $this->obtenerEmpresa();
            $title = 'VENTAS POR QR - ARQUEO #' . $id_caja;

            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_forma_qr_general', [
                'title' => $title,
                'nombre_empresa' => $mi_empresa->nombre,
                'direccion_empresa' => $mi_empresa->direccion,
                'telefono_empresa' => $mi_empresa->telefono,
                'foto_empresa' => $mi_empresa->foto,
                'logo_sistema' => $mi_empresa->logo_sistema,
                'tipo_venta' => 'Venta',
                'fecha_inicio' => '',
                'fecha_fin' => '',
                'venta' => $ventas,
                'totalVentas' => $totalVentas ?? 0,
                'detalles' => $detalles,
                // Nuevos datos opcionales:
                'usuario_arqueo' => $request->usuario_arqueo ?? null,
                'fecha_inicio_arqueo' => $request->fecha_inicio_arqueo ?? null,
                'fecha_fin_arqueo' => $request->fecha_fin_arqueo ?? null,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream("Venta_QR_Arqueo_{$id_caja}.pdf");

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaDetalladaQrPorArqueo: ' . $e->getMessage(), $request->all());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    // DEPÓSITO POR ARQUEO
    public function pdfVentaDetalladaDepositoPorArqueo(Request $request)
    {
        try {
            $id_caja = $request->id_caja;
            if (!$id_caja) {
                return response()->json(['error' => 'ID de arqueo requerido'], 400);
            }

            $ventas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->leftJoin('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.control', $id_caja)
                ->where('v.id_forma_pago', 5) // Depósito
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_deposito',
                    'v.total_efectivo',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Tipo no encontrado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Forma no encontrada") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario'),
                    DB::raw('COALESCE(td.nombre, "Tienda no encontrada") as tienda')
                )
                ->orderBy('v.id', 'asc')
                ->get();

            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.control', $id_caja)
                ->where('v.id_forma_pago', 5)
                ->sum('v.total_deposito');

            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = collect();
            $detallesPaquete = collect();
            if (!empty($ventasIds)) {
                $detalles = DB::table('detalle_venta as d')
                    ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1')
                    ->select(
                        'd.id_venta',
                        'd.cantidad',
                        DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'),
                        'd.costo_venta',
                        'd.sub_total'
                    )
                    ->get();

                $detallesPaquete = DB::table('detalle_venta_paquete as dvp')
                    ->leftJoin('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                    ->whereIn('dvp.id_venta', $ventasIds)
                    ->select(
                        'dvp.id_venta',
                        'dvp.cantidad',
                        DB::raw('COALESCE(pqt.nombre, "Paquete no encontrado") as producto'),
                        'dvp.costo_venta',
                        'dvp.sub_total'
                    )
                    ->get();
            }

            $mi_empresa = $this->obtenerEmpresa();
            $title = 'VENTAS POR DEPÓSITO - ARQUEO #' . $id_caja;

            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_forma', [
                'title' => $title,
                'nombre_empresa' => $mi_empresa->nombre,
                'direccion_empresa' => $mi_empresa->direccion,
                'telefono_empresa' => $mi_empresa->telefono,
                'foto_empresa' => $mi_empresa->foto,
                'logo_sistema' => $mi_empresa->logo_sistema,
                'tipo_venta' => 'Venta',
                'fecha_inicio' => '',
                'fecha_fin' => '',
                'venta' => $ventas,
                'detalles' => $detalles,
                'detallesPaquete' => $detallesPaquete,
                'totalVentas' => $totalVentas ?? 0,
                // Nuevos datos opcionales:
                'usuario_arqueo' => $request->usuario_arqueo ?? null,
                'fecha_inicio_arqueo' => $request->fecha_inicio_arqueo ?? null,
                'fecha_fin_arqueo' => $request->fecha_fin_arqueo ?? null,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream("Venta_Deposito_Arqueo_{$id_caja}.pdf");

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaDetalladaDepositoPorArqueo: ' . $e->getMessage(), $request->all());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    // MIXTA POR ARQUEO
    public function pdfVentaDetalladaMixtaPorArqueo(Request $request)
    {
        try {
            $id_caja = $request->id_caja;
            if (!$id_caja) {
                return response()->json(['error' => 'ID de arqueo requerido'], 400);
            }

            $ventas = DB::table('venta as v')
                ->leftJoin('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->leftJoin('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->leftJoin('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->leftJoin('users as u', 'v.id_usuario', '=', 'u.id')
                ->leftJoin('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.control', $id_caja)
                ->where('v.id_forma_pago', 6) // Mixta
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_efectivo',
                    'v.total_deposito',
                    DB::raw('COALESCE(c.nombre, "Cliente no encontrado") as cliente'),
                    DB::raw('COALESCE(t.nombre, "Tipo no encontrado") as tipo_pago'),
                    DB::raw('COALESCE(f.nombre, "Forma no encontrada") as forma_pago'),
                    DB::raw('COALESCE(u.name, "Usuario no encontrado") as usuario'),
                    DB::raw('COALESCE(td.nombre, "Tienda no encontrada") as tienda')
                )
                ->orderBy('v.id', 'asc')
                ->get();

            $totalVentas = DB::table('venta as v')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.control', $id_caja)
                ->where('v.id_forma_pago', 6)
                ->sum('v.total');

            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = collect();
            $detallesPaquete = collect();
            if (!empty($ventasIds)) {
                $detalles = DB::table('detalle_venta as d')
                    ->leftJoin('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->leftJoin('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1')
                    ->select(
                        'd.id_venta',
                        'd.cantidad',
                        DB::raw('COALESCE(p.nombre_comercial, "Producto no encontrado") as producto'),
                        'd.costo_venta',
                        'd.sub_total'
                    )
                    ->get();

                $detallesPaquete = DB::table('detalle_venta_paquete as dvp')
                    ->leftJoin('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                    ->whereIn('dvp.id_venta', $ventasIds)
                    ->select(
                        'dvp.id_venta',
                        'dvp.cantidad',
                        DB::raw('COALESCE(pqt.nombre, "Paquete no encontrado") as producto'),
                        'dvp.costo_venta',
                        'dvp.sub_total'
                    )
                    ->get();
            }

            $mi_empresa = $this->obtenerEmpresa();
         
            $title = 'VENTAS MIXTAS - ARQUEO #' . $id_caja;

            $pdf = \PDF::loadView('pdf.reportes.venta.venta_detallada_mixta', [
                'title' => $title,
                'nombre_empresa' => $mi_empresa->nombre,
                'direccion_empresa' => $mi_empresa->direccion,
                'telefono_empresa' => $mi_empresa->telefono,
                'foto_empresa' => $mi_empresa->foto,
                'logo_sistema' => $mi_empresa->logo_sistema,
                'tipo_venta' => 'Venta',
                'fecha_inicio' => '',
                'fecha_fin' => '',
                'venta' => $ventas,
                'detalles' => $detalles,
                'detallesPaquete' => $detallesPaquete,
                'totalVentas' => $totalVentas ?? 0,
                // Nuevos datos opcionales:
                'usuario_arqueo' => $request->usuario_arqueo ?? null,
                'fecha_inicio_arqueo' => $request->fecha_inicio_arqueo ?? null,
                'fecha_fin_arqueo' => $request->fecha_fin_arqueo ?? null,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream("Venta_Mixta_Arqueo_{$id_caja}.pdf");

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaDetalladaMixtaPorArqueo: ' . $e->getMessage(), $request->all());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    private function obtenerEmpresa()
    {
        $mi_empresa = MiEmpresa::select(
            'nombre',
            'direccion',
            'telefono',
            'foto',
            'logo_sistema',
        )->first();

        if (!$mi_empresa) {
            $mi_empresa = (object) [
                'nombre' => 'Mi Empresa',
                'direccion' => 'Dirección no disponible',
                'telefono' => 'Teléfono no disponible',
                'foto' => null,
                'logo_sistema' => null,
            ];
        }
        return $mi_empresa;
    }

    public function reportePorArqueo($arqueoId, $tipo, Request $request)
    {
        $tiposValidos = ['efectivo', 'transferencia', 'deposito', 'qr', 'mixta', 'general', 'general_detallada'];
        if (!in_array($tipo, $tiposValidos)) {
            abort(400, 'Tipo de reporte no válido');
        }

        $arqueo = DB::table('arqueo_caja')->where('id', $arqueoId)->first();
        $user = DB::table('users')->where('id', $arqueo->id_usuario)->first();

        $simulatedRequest = new \Illuminate\Http\Request();
        $simulatedRequest->replace([
            'id_caja' => $arqueoId,
            'usuario_arqueo' => $user->name ? $user->name : 'Usuario no asignado',
            'fecha_inicio_arqueo' => $arqueo->fecha_apertura ? \Carbon\Carbon::parse($arqueo->fecha_apertura)->format('d/m/Y H:i') : null,
            'fecha_fin_arqueo' => $arqueo->fecha_cierre ? \Carbon\Carbon::parse($arqueo->fecha_cierre)->format('d/m/Y H:i') : 'Caja aún abierta',
        ]);

        switch ($tipo) {
            case 'efectivo':
                return $this->pdfVentaDetalladaEfectivoPorArqueo($simulatedRequest);
            case 'transferencia':
                return $this->pdfVentaDetalladaTransfenciaPorArqueo($simulatedRequest);
            case 'deposito':
                return $this->pdfVentaDetalladaDepositoPorArqueo($simulatedRequest);
            case 'qr':
                return $this->pdfVentaDetalladaQrPorArqueo($simulatedRequest);
            case 'mixta':
                return $this->pdfVentaDetalladaMixtaPorArqueo($simulatedRequest);
            case 'general':
                return $this->pdfVentaGeneralPorArqueo($simulatedRequest);
            case 'general_detallada':
                return $this->pdfVentaDetalladaPorArqueo($simulatedRequest);
            default:
                abort(400);
        }
    }

    public function pdfVentaGeneralPorArqueo(Request $request)
    {
        try {
            $id_caja = $request->id_caja;
            if (!$id_caja) {
                return response()->json(['error' => 'ID de arqueo requerido'], 400);
            }

            // Ventas del arqueo
            $ventas = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.control', $id_caja)
                ->select(
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'c.nombre as cliente',
                    't.nombre as tipo_pago',
                    'f.nombre as forma_pago',
                    'u.name as usuario',
                    'td.nombre as tienda'
                )
                ->get();

            // Totales
            $totalV = DB::table('venta')->where('control', $id_caja)->where('estado', '!=', 'Anulado')->sum('total');
            $totalC = DB::table('venta')->where('control', $id_caja)->where('estado', '!=', 'Anulado')->where('id_tipo_pago', 1)->sum('total');
            $totalCr = DB::table('venta')->where('control', $id_caja)->where('estado', '!=', 'Anulado')->where('id_tipo_pago', 2)->sum('total');
            $totalEf = DB::table('venta')->where('control', $id_caja)->where('estado', '!=', 'Anulado')->sum('total_efectivo');
            $totalDep = DB::table('venta')->where('control', $id_caja)->where('estado', '!=', 'Anulado')->sum('total_deposito');

            $mi_empresa = $this->obtenerEmpresa();
            $title = 'VENTAS GENERALES - ARQUEO #' . $id_caja;

            $pdf = \PDF::loadView('pdf.reportes.venta.venta_general_arqueo', [
                'title' => $title,
                'nombre_empresa' => $mi_empresa->nombre,
                'direccion_empresa' => $mi_empresa->direccion,
                'telefono_empresa' => $mi_empresa->telefono,
                'foto_empresa' => $mi_empresa->foto,
                'logo_sistema' => $mi_empresa->logo_sistema,
                'tipo_venta' => 'Venta',
                'fecha_inicio' => '',
                'fecha_fin' => '',
                'detalles' => $ventas,
                'totalV' => $totalV,
                'totalC' => $totalC,
                'totalCr' => $totalCr,
                'totalEf' => $totalEf,
                'totalDep' => $totalDep,
                'usuario_arqueo' => $request->usuario_arqueo ?? null,
                'fecha_inicio_arqueo' => $request->fecha_inicio_arqueo ?? null,
                'fecha_fin_arqueo' => $request->fecha_fin_arqueo ?? null,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream("Venta_General_Arqueo_{$id_caja}.pdf");

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaGeneralPorArqueo: ' . $e->getMessage(), $request->all());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    public function pdfVentaDetalladaPorArqueo(Request $request)
    {
        try {
            $id_caja = $request->id_caja;
            if (!$id_caja) {
                return response()->json(['error' => 'ID de arqueo requerido'], 400);
            }

            // Ventas
            $ventas = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->join('tienda as td', 'v.id_tienda', '=', 'td.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.control', $id_caja)
                ->select(
                    'v.id',
                    'v.fecha',
                    'v.sub_total',
                    'v.descuento',
                    'v.total',
                    'v.total_efectivo',
                    'v.total_deposito',
                    'c.nombre as cliente',
                    't.nombre as tipo_pago',
                    'f.nombre as forma_pago',
                    'u.name as usuario',
                    'td.nombre as tienda'
                )
                ->get();

            // Totales
            $totalV = DB::table('venta')->where('control', $id_caja)->where('estado', '!=', 'Anulado')->sum('total');
            $totalC = DB::table('venta')->where('control', $id_caja)->where('estado', '!=', 'Anulado')->where('id_tipo_pago', 1)->sum('total');
            $totalCr = DB::table('venta')->where('control', $id_caja)->where('estado', '!=', 'Anulado')->where('id_tipo_pago', 2)->sum('total');
            $totalEf = DB::table('venta')->where('control', $id_caja)->where('estado', '!=', 'Anulado')->sum('total_efectivo');
            $totalDep = DB::table('venta')->where('control', $id_caja)->where('estado', '!=', 'Anulado')->sum('total_deposito');

            // Detalles
            $ventasIds = $ventas->pluck('id')->toArray();
            $detalles = collect();
            $detallesPaquete = collect();

            if (!empty($ventasIds)) {
                $detalles = DB::table('detalle_venta as d')
                    ->join('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                    ->join('articulo as p', 'ta.id_articulo', '=', 'p.id')
                    ->whereIn('d.id_venta', $ventasIds)
                    ->where('d.estado', '!=', '1')
                    ->select('d.id_venta', 'd.cantidad', 'p.nombre_comercial as producto', 'd.costo_venta', 'd.sub_total')
                    ->get();

                $detallesPaquete = DB::table('detalle_venta_paquete as dvp')
                    ->join('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                    ->whereIn('dvp.id_venta', $ventasIds)
                    ->select('dvp.id_venta', 'dvp.cantidad', 'pqt.nombre as producto', 'dvp.costo_venta', 'dvp.sub_total')
                    ->get();
            }

            $mi_empresa = $this->obtenerEmpresa();
            $title = 'VENTAS DETALLADAS - ARQUEO #' . $id_caja;

            $pdf = \PDF::loadView('pdf.reportes.venta.venta_general_detallada_arqueo', [
                'title' => $title,
                'nombre_empresa' => $mi_empresa->nombre,
                'direccion_empresa' => $mi_empresa->direccion,
                'telefono_empresa' => $mi_empresa->telefono,
                'foto_empresa' => $mi_empresa->foto,
                'logo_sistema' => $mi_empresa->logo_sistema,
                'tipo_venta' => 'Venta',
                'fecha_inicio' => '',
                'fecha_fin' => '',
                'venta' => $ventas,
                'detalles' => $detalles,
                'detallesPaquete' => $detallesPaquete,
                'totalV' => $totalV,
                'totalC' => $totalC,
                'totalCr' => $totalCr,
                'totalEf' => $totalEf,
                'totalDep' => $totalDep,
                'usuario_arqueo' => $request->usuario_arqueo ?? null,
                'fecha_inicio_arqueo' => $request->fecha_inicio_arqueo ?? null,
                'fecha_fin_arqueo' => $request->fecha_fin_arqueo ?? null,
            ]);

            return $pdf->setPaper('letter', 'portrait')->stream("Venta_Detallada_Arqueo_{$id_caja}.pdf");

        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaDetalladaPorArqueo: ' . $e->getMessage(), $request->all());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    public function pdfProductosPorVencer()
    {
        try {
            $fechaLimite = now()->addMonths(3);

            $productosPorVencer = DB::table('lote')
                ->join('tienda_articulo', 'lote.id_producto', '=', 'tienda_articulo.id')
                ->join('articulo', 'tienda_articulo.id_articulo', '=', 'articulo.id')
                ->join('categoria', 'articulo.id_categoria', '=', 'categoria.id')
                ->join('proveedor', 'articulo.id_proveedor', '=', 'proveedor.id')
                ->join('unidad_medida', 'articulo.id_unidad', '=', 'unidad_medida.id')
                ->select(
                    'lote.id',
                    'tienda_articulo.id as id_articulo',
                    'tienda_articulo.id_tienda',
                    'articulo.nombre_comercial as articulo',
                    'articulo.nombre_generico',
                    'articulo.costo_compra',
                    'articulo.costo_unitario',
                    'articulo.costo_mayorista',
                    'articulo.precio_blister',
                    'articulo.precio_caja',
                    'articulo.costo_preferencial',
                    'categoria.nombre as categoria',
                    'lote.cantidad as stock',
                    'lote.fecha_vecimiento',
                    'articulo.descripcion',
                    'articulo.cod_proveedor',
                    'articulo.cantidad_blister',
                    'articulo.cantidad_caja',
                    'articulo.venta_presentacion',
                    'articulo.ubicacion',
                    'proveedor.nombre as laboratorio',
                    'unidad_medida.nombre as presentacion',
                    'lote.lote'
                )
                ->where('lote.cantidad', '!=', 0)
                ->where('lote.estado', '!=', 0)
                ->whereDate('lote.fecha_vecimiento', '<', $fechaLimite)
                ->orderBy('lote.fecha_vecimiento', 'asc')
                ->get();

            $mi_empresa = $this->obtenerEmpresa();
            $title = 'PRODUCTOS POR VENCERSE';
            $subtitle = '(Menos de 3 meses)';

            $pdf = \PDF::loadView('pdf.reportes.inventario.productos_por_vencer', [
                'title' => $title,
                'subtitle' => $subtitle,
                'nombre_empresa' => $mi_empresa->nombre,
                'direccion_empresa' => $mi_empresa->direccion,
                'telefono_empresa' => $mi_empresa->telefono,
                'logo_sistema' => $mi_empresa->logo_sistema,
                'productos' => $productosPorVencer,
                'fecha_reporte' => now()->format('d/m/Y'),
            ]);

           
            $options = [
                'isRemoteEnabled' => true, 
                'isPhpEnabled' => true,  
            ];

            return $pdf->setPaper('letter', 'portrait')->stream("Productos_Por_Vencer_" . now()->format('Y-m-d') . ".pdf");

        } catch (\Exception $e) {
            \Log::error('Error en pdfProductosPorVencer: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

}

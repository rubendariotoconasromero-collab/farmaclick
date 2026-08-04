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
    /**
     * mPDF mantiene todo el documento renderizado en memoria hasta el Output() final,
     * sin importar cómo se lean los datos de la BD (chunk() solo acota la consulta).
     * En reportes muy extensos esto agota el límite por defecto de PHP; se sube a un
     * valor generoso pero razonable (no los ini_set de cientos de GB que había antes)
     * y se amplía el tiempo de ejecución para evitar timeouts en reportes lentos.
     */
    private function prepararEntornoReporte(): void
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(180);
    }

    private function aplicarTemaReporte(Mpdf $mpdf): void
    {
        $theme = view('pdf.reportes.partials.system-theme')->render();
        // mPDF's HEADER_CSS parser only applies CSS rules to every page (headers/footers
        // included) when the stylesheet keeps its <style> wrapper; stripping it breaks
        // repeating footers after page 1.
        $mpdf->WriteHTML(trim($theme), \Mpdf\HTMLParserMode::HEADER_CSS);
    }


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
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;

        $detalles=$obj;
        
        $cont=User::count();
        $pdf = \PDF::loadView('pdf.reportes.usuario.usuario', [
            'title'=>$title,
            'foto_empresa'=>$foto_empresa,
            'logo_sistema'=>$logo_sistema,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'telefono_empresa'=>$telefono_empresa,
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
        return $this->buildGastoReport($request, false);
    }

    public function pdfGastoCliente(Request $request){
        return $this->buildGastoReport($request, true);
    }

    private function buildGastoReport(Request $request, bool $soloEfectivo)
    {
        $this->prepararEntornoReporte();
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;

            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

            $base = DB::table('gasto as g')
                ->join('motivo_gasto as m', 'g.id_motivo_gasto', '=', 'm.id')
                ->join('forma_pago as f', 'g.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'g.id_usuario', '=', 'u.id')
                ->whereDate('g.fecha', '>=', $fecha_inicio)
                ->whereDate('g.fecha', '<=', $fecha_fin);
            if ($soloEfectivo) {
                $base->whereNotIn('g.id_forma_pago', [3, 4, 5]);
            }

            $totales = (clone $base)->selectRaw('SUM(g.efectivo) as totalEfectivo, SUM(g.deposito) as totalDeposito')->first();
            $totalEfectivo = (float) ($totales->totalEfectivo ?? 0);
            $totalDeposito = (float) ($totales->totalDeposito ?? 0);
            $totalCount = (clone $base)->count();

            $title = $soloEfectivo ? 'LISTA DE GASTOS TOTAL EFECTIVO' : 'LISTA DE GASTOS';

            $mpdf = new Mpdf([
                'mode' => 'utf-8', 'format' => 'Letter',
                'margin_top' => 10, 'margin_bottom' => 16, 'margin_left' => 10, 'margin_right' => 10,
            ]);

            $theme = trim(view('pdf.reportes.partials.corporate-letter-theme')->render());

            $viewData = [
                'title' => $title,
                'nombre_empresa' => $empresa->nombre,
                'direccion_empresa' => $empresa->direccion,
                'telefono_empresa' => $empresa->telefono,
                'logo_sistema' => $empresa->logo_sistema,
                'eyebrow' => 'Movimientos de gasto',
                'documentLabel' => $soloEfectivo ? 'Gastos en efectivo' : 'Reporte de gastos',
                'sectionTitle' => $soloEfectivo ? 'Gastos pagados en efectivo' : 'Gastos registrados',
                'description' => $soloEfectivo
                    ? 'Gastos pagados en efectivo durante el período seleccionado.'
                    : 'Detalle de los gastos registrados en el período seleccionado.',
                'recordCount' => $totalCount,
                'recordLabel' => 'Gastos',
                'periodLabel' => 'Del ' . \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') . ' al ' . \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y'),
                'footerLabel' => $soloEfectivo ? 'Gastos en efectivo' : 'Listado de gastos',
            ];

            $mpdf->WriteHTML($theme, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-letter-header', $viewData)->render(), \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLFooter(view('pdf.reportes.partials.corporate-mpdf-footer', $viewData)->render(), '', true);

            $summaryItems = $soloEfectivo
                ? [['label' => 'Total efectivo', 'value' => 'Bs ' . number_format($totalEfectivo, 2, ',', '.')]]
                : [
                    ['label' => 'Total efectivo', 'value' => 'Bs ' . number_format($totalEfectivo, 2, ',', '.')],
                    ['label' => 'Total depósito', 'value' => 'Bs ' . number_format($totalDeposito, 2, ',', '.')],
                    ['label' => 'Total general', 'value' => 'Bs ' . number_format($totalEfectivo + $totalDeposito, 2, ',', '.')],
                ];
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-summary-cards', ['items' => $summaryItems])->render(), \Mpdf\HTMLParserMode::HTML_BODY);

            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:10%">Fecha</th><th style="width:22%">Descripción</th><th style="width:15%">Motivo</th>'
                . '<th style="width:13%">Forma pago</th><th style="width:13%">Efectivo</th><th style="width:13%">Depósito</th><th style="width:14%">Usuario</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            (clone $base)
                ->select('g.id', 'g.fecha', 'g.descripcion', 'm.nombre as motivo_gasto', 'f.nombre as forma', 'g.efectivo', 'g.deposito', 'u.name as usuario')
                ->orderBy('g.id')
                ->chunk(300, function ($rows) use ($mpdf) {
                    $html = '';
                    foreach ($rows as $row) {
                        $html .= '<tr>'
                            . '<td>' . e($row->fecha) . '</td>'
                            . '<td>' . e($row->descripcion ?: '—') . '</td>'
                            . '<td>' . e($row->motivo_gasto) . '</td>'
                            . '<td>' . e($row->forma) . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->efectivo, 2, ',', '.') . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->deposito, 2, ',', '.') . '</td>'
                            . '<td>' . e($row->usuario) . '</td>'
                            . '</tr>';
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });

            if ($totalCount === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="7">No existen gastos registrados para el período seleccionado.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $filename = $soloEfectivo ? 'Gastos_Efectivo.pdf' : 'Gastos.pdf';
            $content = $mpdf->Output($filename, 'S');

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en reporte de gastos: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de gastos'], 500);
        }
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
        return $pdf->setPaper('letter', 'portrait')->stream('Proveedor.pdf');
    }
    public function pdfCompraGeneral(Request $request){
        $this->prepararEntornoReporte();
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;

            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

            $base = DB::table('compra as c')
                ->join('proveedor as p', 'c.id_proveedor', '=', 'p.id')
                ->join('users as u', 'c.id_usuario', '=', 'u.id')
                ->join('tipo_pago as t', 'c.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'c.id_forma_pago', '=', 'f.id')
                ->where('c.estado', '!=', 'Anulado')
                ->whereDate('c.fecha', '>=', $fecha_inicio)
                ->whereDate('c.fecha', '<=', $fecha_fin);

            $totales = (clone $base)->selectRaw('
                SUM(c.total) as totalC,
                SUM(CASE WHEN c.id_tipo_pago = 1 THEN c.total ELSE 0 END) as totalCo,
                SUM(CASE WHEN c.id_tipo_pago = 2 THEN c.total ELSE 0 END) as totalCr,
                SUM(c.total_efectivo) as totalEf,
                SUM(c.total_deposito) as totalDep
            ')->first();

            $totalCount = (clone $base)->count();
            $title = 'LISTADO DE COMPRAS';

            $mpdf = new Mpdf([
                'mode' => 'utf-8', 'format' => 'Letter',
                'margin_top' => 10, 'margin_bottom' => 16, 'margin_left' => 10, 'margin_right' => 10,
            ]);

            $theme = trim(view('pdf.reportes.partials.corporate-letter-theme')->render());

            $viewData = [
                'title' => $title,
                'nombre_empresa' => $empresa->nombre,
                'direccion_empresa' => $empresa->direccion,
                'telefono_empresa' => $empresa->telefono,
                'logo_sistema' => $empresa->logo_sistema,
                'eyebrow' => 'Movimientos de compra',
                'documentLabel' => 'Reporte de compras',
                'sectionTitle' => 'Compras registradas',
                'description' => 'Listado general de las compras registradas en el período seleccionado.',
                'recordCount' => $totalCount,
                'recordLabel' => 'Compras',
                'periodLabel' => 'Del ' . \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') . ' al ' . \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y'),
                'footerLabel' => 'Listado de compras',
            ];

            $mpdf->WriteHTML($theme, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-letter-header', $viewData)->render(), \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLFooter(view('pdf.reportes.partials.corporate-mpdf-footer', $viewData)->render(), '', true);

            $summaryItems = [
                ['label' => 'Total compra', 'value' => 'Bs ' . number_format((float) ($totales->totalC ?? 0), 2, ',', '.')],
                ['label' => 'Contado', 'value' => 'Bs ' . number_format((float) ($totales->totalCo ?? 0), 2, ',', '.')],
                ['label' => 'Crédito', 'value' => 'Bs ' . number_format((float) ($totales->totalCr ?? 0), 2, ',', '.')],
                ['label' => 'Efectivo', 'value' => 'Bs ' . number_format((float) ($totales->totalEf ?? 0), 2, ',', '.')],
                ['label' => 'Depósito', 'value' => 'Bs ' . number_format((float) ($totales->totalDep ?? 0), 2, ',', '.')],
            ];
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-summary-cards', ['items' => $summaryItems])->render(), \Mpdf\HTMLParserMode::HTML_BODY);

            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:5%">N.º</th><th style="width:10%">Fecha</th><th style="width:18%">Proveedor</th>'
                . '<th style="width:10%">Tipo P.</th><th style="width:12%">Forma P.</th><th style="width:11%">Descuento</th>'
                . '<th style="width:11%">Subtotal</th><th style="width:11%">Total</th><th style="width:12%">Usuario</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            $index = 1;
            (clone $base)
                ->select('c.id', 'c.fecha', 'p.nombre as proveedor', 'u.name as usuario', 'c.sub_total', 'c.descuento', 'c.total', 't.nombre as tipo', 'f.nombre as forma')
                ->orderBy('c.id')
                ->chunk(300, function ($rows) use ($mpdf, &$index) {
                    $html = '';
                    foreach ($rows as $row) {
                        $forma = $row->forma === 'Cuenta por Cobrar' ? 'Cuenta por Pagar' : $row->forma;
                        $html .= '<tr>'
                            . '<td class="is-center">' . $index++ . '</td>'
                            . '<td>' . e($row->fecha) . '</td>'
                            . '<td class="is-strong">' . e($row->proveedor) . '</td>'
                            . '<td>' . e($row->tipo) . '</td>'
                            . '<td>' . e($forma) . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->descuento, 2, ',', '.') . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->sub_total, 2, ',', '.') . '</td>'
                            . '<td class="is-right is-strong">Bs ' . number_format((float) $row->total, 2, ',', '.') . '</td>'
                            . '<td>' . e($row->usuario) . '</td>'
                            . '</tr>';
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });

            if ($totalCount === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="9">No existen compras registradas para el período seleccionado.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $content = $mpdf->Output('Listado_Compras.pdf', 'S');

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Listado_Compras.pdf"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en pdfCompraGeneral: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de compras'], 500);
        }
    }
    public function pdfCompraDetallada(Request $request){
        return $this->buildCompraDetalladaReport($request, false);
    }

    private function buildCompraDetalladaReport(Request $request, bool $soloAnuladas)
    {
        $this->prepararEntornoReporte();
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;

            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

            $base = DB::table('compra as c')
                ->join('proveedor as p', 'c.id_proveedor', '=', 'p.id')
                ->join('users as u', 'c.id_usuario', '=', 'u.id')
                ->join('tipo_pago as t', 'c.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'c.id_forma_pago', '=', 'f.id')
                ->where('c.estado', $soloAnuladas ? '=' : '!=', 'Anulado')
                ->whereDate('c.fecha', '>=', $fecha_inicio)
                ->whereDate('c.fecha', '<=', $fecha_fin);

            $totales = (clone $base)->selectRaw('
                SUM(c.total) as totalC,
                SUM(CASE WHEN c.id_tipo_pago = 1 THEN c.total ELSE 0 END) as totalCo,
                SUM(CASE WHEN c.id_tipo_pago = 2 THEN c.total ELSE 0 END) as totalCr,
                SUM(c.total_efectivo) as totalEf,
                SUM(c.total_deposito) as totalDep
            ')->first();

            $totalCount = (clone $base)->count();

            if ($totalCount > 4000) {
                return response()->json([
                    'error' => 'El período seleccionado es demasiado amplio (' . number_format($totalCount) . ' compras). Reduzca el rango de fechas e intente nuevamente.',
                ], 422);
            }

            $title = $soloAnuladas ? 'LISTADO DE COMPRAS DETALLADA ANULADAS' : 'LISTADO DE COMPRAS DETALLADA';

            $mpdf = new Mpdf([
                'mode' => 'utf-8', 'format' => 'Letter',
                'margin_top' => 10, 'margin_bottom' => 16, 'margin_left' => 10, 'margin_right' => 10,
            ]);

            $theme = trim(view('pdf.reportes.partials.corporate-letter-theme')->render());

            $viewData = [
                'title' => $title,
                'nombre_empresa' => $empresa->nombre,
                'direccion_empresa' => $empresa->direccion,
                'telefono_empresa' => $empresa->telefono,
                'logo_sistema' => $empresa->logo_sistema,
                'eyebrow' => 'Movimientos de compra',
                'documentLabel' => $soloAnuladas ? 'Compras anuladas' : 'Reporte de compras',
                'sectionTitle' => $soloAnuladas ? 'Compras anuladas' : 'Compras y detalle de productos',
                'description' => $soloAnuladas
                    ? 'Compras anuladas en el período seleccionado, con el detalle de productos incluidos.'
                    : 'Compras registradas en el período seleccionado, con el detalle de productos de cada una.',
                'recordCount' => $totalCount,
                'recordLabel' => 'Compras',
                'periodLabel' => 'Del ' . \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') . ' al ' . \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y'),
                'footerLabel' => $soloAnuladas ? 'Compras anuladas' : 'Compras detalladas',
            ];

            $mpdf->WriteHTML($theme, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-letter-header', $viewData)->render(), \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLFooter(view('pdf.reportes.partials.corporate-mpdf-footer', $viewData)->render(), '', true);

            $summaryItems = $soloAnuladas
                ? [['label' => 'Total anulado', 'value' => 'Bs ' . number_format((float) ($totales->totalC ?? 0), 2, ',', '.')]]
                : [
                    ['label' => 'Total compra', 'value' => 'Bs ' . number_format((float) ($totales->totalC ?? 0), 2, ',', '.')],
                    ['label' => 'Contado', 'value' => 'Bs ' . number_format((float) ($totales->totalCo ?? 0), 2, ',', '.')],
                    ['label' => 'Crédito', 'value' => 'Bs ' . number_format((float) ($totales->totalCr ?? 0), 2, ',', '.')],
                    ['label' => 'Efectivo', 'value' => 'Bs ' . number_format((float) ($totales->totalEf ?? 0), 2, ',', '.')],
                    ['label' => 'Depósito', 'value' => 'Bs ' . number_format((float) ($totales->totalDep ?? 0), 2, ',', '.')],
                ];
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-summary-cards', ['items' => $summaryItems])->render(), \Mpdf\HTMLParserMode::HTML_BODY);

            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:46%">Compra / Producto</th><th style="width:18%">Costo unit.</th>'
                . '<th style="width:14%">Cantidad</th><th style="width:22%">Subtotal</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            (clone $base)
                ->select('c.id', 'c.fecha', 'p.nombre as proveedor', 'u.name as usuario', 'c.total', 't.nombre as tipo', 'f.nombre as forma')
                ->orderBy('c.id')
                ->chunk(100, function ($compras) use ($mpdf) {
                    $ids = $compras->pluck('id')->all();
                    $lineasPorCompra = DB::table('detalle_compra as dc')
                        ->join('tienda_articulo as ta', 'dc.id_producto', '=', 'ta.id')
                        ->join('articulo as a', 'ta.id_articulo', '=', 'a.id')
                        ->leftJoin('categoria as cat', 'a.id_categoria', '=', 'cat.id')
                        ->join('tienda as ti', 'ta.id_tienda', '=', 'ti.id')
                        ->where('dc.eliminado', 0)
                        ->whereIn('dc.id_compra', $ids)
                        ->select('dc.id_compra', 'dc.costo_compra as pu', 'dc.cantidad', 'dc.sub_total', 'a.nombre_comercial as producto', 'ti.nombre as tienda', 'cat.nombre as categoria')
                        ->orderBy('dc.id_compra')
                        ->get()
                        ->groupBy('id_compra');

                    $html = '';
                    foreach ($compras as $compra) {
                        $forma = $compra->forma === 'Cuenta por Cobrar' ? 'Cuenta por Pagar' : $compra->forma;
                        $html .= '<tr class="fc-group-row">'
                            . '<td colspan="3">' . e($compra->fecha) . ' &middot; ' . e($compra->proveedor)
                            . '<div class="is-muted">' . e($compra->tipo) . ' / ' . e($forma) . ' &middot; ' . e($compra->usuario) . '</div></td>'
                            . '<td class="is-right">Total: Bs ' . number_format((float) $compra->total, 2, ',', '.') . '</td>'
                            . '</tr>';

                        $lineas = $lineasPorCompra->get($compra->id, collect());
                        if ($lineas->isEmpty()) {
                            $html .= '<tr class="fc-subrow"><td colspan="4" class="is-muted">Sin líneas de producto registradas.</td></tr>';
                            continue;
                        }
                        foreach ($lineas as $linea) {
                            $html .= '<tr class="fc-subrow">'
                                . '<td>' . e($linea->producto) . '<div class="is-muted">' . e($linea->categoria ?: '—') . ' · ' . e($linea->tienda) . '</div></td>'
                                . '<td class="is-right">Bs ' . number_format((float) $linea->pu, 2, ',', '.') . '</td>'
                                . '<td class="is-center">' . (float) $linea->cantidad . '</td>'
                                . '<td class="is-right">Bs ' . number_format((float) $linea->sub_total, 2, ',', '.') . '</td>'
                                . '</tr>';
                        }
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });

            if ($totalCount === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="4">No existen compras para el período seleccionado.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $filename = $soloAnuladas ? 'Compras_Anuladas.pdf' : 'Compras_Detalladas.pdf';
            $content = $mpdf->Output($filename, 'S');

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en reporte de compras detallado: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de compras detallado'], 500);
        }
    }
    public function pdfCompraDetalladaAnular(Request $request){
        return $this->buildCompraDetalladaReport($request, true);
    }
    
    public function pdfVentaGeneral(Request $request)
    {
        return $this->buildVentaGeneralReport($request);
    }

    public function pdfVentaGeneralUsuario(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'tipo_venta' => 'required|string',
            'id_tienda' => 'required|integer|exists:tienda,id',
            'id_usuario' => 'required|integer|exists:users,id',
        ]);

        return $this->buildVentaGeneralReport($request, (int) $request->id_usuario);
    }

    private function buildVentaGeneralReport(Request $request, ?int $idUsuario = null)
    {
        $this->prepararEntornoReporte();
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;

            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

            $base = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->where('v.id_tienda', $id_tienda)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin]);

            if ($idUsuario !== null) {
                $base->where('v.id_usuario', $idUsuario);
            }

            $totales = (clone $base)->selectRaw('
                SUM(v.total) as totalV,
                SUM(CASE WHEN v.id_tipo_pago = 1 THEN v.total ELSE 0 END) as totalCo,
                SUM(CASE WHEN v.id_tipo_pago = 2 THEN v.total ELSE 0 END) as totalCr,
                SUM(v.total_efectivo) as totalEf,
                SUM(v.total_deposito) as totalDep
            ')->first();

            $totalCount = (clone $base)->count();
            $title = 'LISTADO DE VENTAS';

            $usuarioNombre = $idUsuario !== null
                ? optional(DB::table('users')->find($idUsuario))->name
                : null;

            $mpdf = new Mpdf([
                'mode' => 'utf-8', 'format' => 'Letter',
                'margin_top' => 10, 'margin_bottom' => 16, 'margin_left' => 10, 'margin_right' => 10,
            ]);

            $theme = trim(view('pdf.reportes.partials.corporate-letter-theme')->render());

            $viewData = [
                'title' => $title,
                'nombre_empresa' => $empresa->nombre,
                'direccion_empresa' => $empresa->direccion,
                'telefono_empresa' => $empresa->telefono,
                'logo_sistema' => $empresa->logo_sistema,
                'eyebrow' => 'Movimientos de venta',
                'documentLabel' => $usuarioNombre ? 'Usuario: ' . $usuarioNombre : 'Reporte de ventas',
                'sectionTitle' => 'Ventas registradas',
                'description' => $usuarioNombre
                    ? 'Listado de ventas registradas por ' . $usuarioNombre . ' en el período seleccionado.'
                    : 'Listado general de las ventas registradas en el período seleccionado.',
                'recordCount' => $totalCount,
                'recordLabel' => 'Ventas',
                'periodLabel' => 'Del ' . \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') . ' al ' . \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y'),
                'footerLabel' => 'Listado de ventas',
            ];

            $mpdf->WriteHTML($theme, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-letter-header', $viewData)->render(), \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLFooter(view('pdf.reportes.partials.corporate-mpdf-footer', $viewData)->render(), '', true);

            $summaryItems = [
                ['label' => 'Total venta', 'value' => 'Bs ' . number_format((float) ($totales->totalV ?? 0), 2, ',', '.')],
                ['label' => 'Contado', 'value' => 'Bs ' . number_format((float) ($totales->totalCo ?? 0), 2, ',', '.')],
                ['label' => 'Crédito', 'value' => 'Bs ' . number_format((float) ($totales->totalCr ?? 0), 2, ',', '.')],
                ['label' => 'Efectivo', 'value' => 'Bs ' . number_format((float) ($totales->totalEf ?? 0), 2, ',', '.')],
                ['label' => 'Depósito', 'value' => 'Bs ' . number_format((float) ($totales->totalDep ?? 0), 2, ',', '.')],
            ];
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-summary-cards', ['items' => $summaryItems])->render(), \Mpdf\HTMLParserMode::HTML_BODY);

            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:5%">N.º</th><th style="width:10%">Fecha</th><th style="width:18%">Cliente</th>'
                . '<th style="width:10%">Tipo P.</th><th style="width:12%">Forma P.</th><th style="width:11%">Descuento</th>'
                . '<th style="width:11%">Subtotal</th><th style="width:11%">Total</th><th style="width:12%">Usuario</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            $index = 1;
            (clone $base)
                ->select('v.fecha', 'c.nombre as cliente', 'u.name as usuario', 'v.sub_total', 'v.descuento', 'v.total', 't.nombre as tipo_pago', 'f.nombre as forma_pago')
                ->orderBy('v.fecha')
                ->orderBy('v.id')
                ->chunk(500, function ($rows) use ($mpdf, &$index) {
                    $html = '';
                    foreach ($rows as $row) {
                        $html .= '<tr>'
                            . '<td class="is-center">' . $index++ . '</td>'
                            . '<td>' . e(\Carbon\Carbon::parse($row->fecha)->format('d/m/Y')) . '</td>'
                            . '<td class="is-strong">' . e($row->cliente ?? '—') . '</td>'
                            . '<td>' . e($row->tipo_pago ?? '—') . '</td>'
                            . '<td>' . e($row->forma_pago ?? '—') . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->descuento, 2, ',', '.') . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->sub_total, 2, ',', '.') . '</td>'
                            . '<td class="is-right is-strong">Bs ' . number_format((float) $row->total, 2, ',', '.') . '</td>'
                            . '<td>' . e($row->usuario ?? '—') . '</td>'
                            . '</tr>';
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });

            if ($totalCount === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="9">No existen ventas registradas para el período seleccionado.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $content = $mpdf->Output('Listado_Ventas.pdf', 'S');

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Listado_Ventas.pdf"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaGeneral: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de ventas'], 500);
        }
    }

    public function pdfVentaDetallada(Request $request)
    {
        return $this->buildVentaDetalladaReport($request, 'detallada');
    }

    public function pdfVentaDetalladaUsuario(Request $request)
    {
        $request->validate(['id_usuario' => 'required|integer|exists:users,id']);
        return $this->buildVentaDetalladaReport($request, 'detallada', (int) $request->id_usuario);
    }

    /**
     * Construye los reportes de venta con detalle de productos/paquetes por venta
     * (Detallada, Anuladas y Devolución comparten exactamente la misma estructura,
     * solo cambia el filtro de estado y los textos). $idUsuario, cuando se indica,
     * acota el listado a las ventas registradas por ese usuario (reportes "por usuario").
     */
    private function buildVentaDetalladaReport(Request $request, string $variant, ?int $idUsuario = null, ?int $idCliente = null)
    {
        $this->prepararEntornoReporte();
        try {
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'tipo_venta' => 'required|string',
                'id_tienda' => 'required|exists:tienda,id',
            ]);

            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;

            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

            $usuarioNombre = $idUsuario !== null
                ? optional(DB::table('users')->find($idUsuario))->name
                : null;

            $clienteNombre = $idCliente !== null
                ? optional(DB::table('cliente')->find($idCliente))->nombre
                : null;

            $base = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->where('v.tipo_venta', $tipo_venta)
                ->where('v.id_tienda', $id_tienda)
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin]);

            if ($idUsuario !== null) {
                $base->where('v.id_usuario', $idUsuario);
            }

            if ($idCliente !== null) {
                $base->where('v.id_cliente', $idCliente);
            }

            if ($variant === 'anuladas') {
                $base->where('v.estado', 'Anulado');
            } elseif ($variant === 'devolucion') {
                $base->where('v.estado', 'Devolucion');
            } else {
                $base->where('v.estado', '!=', 'Anulado');
            }

            $totales = (clone $base)->selectRaw('
                SUM(v.total) as totalV,
                SUM(CASE WHEN v.id_tipo_pago = 1 THEN v.total ELSE 0 END) as totalCo,
                SUM(CASE WHEN v.id_tipo_pago = 2 THEN v.total ELSE 0 END) as totalCr,
                SUM(v.total_efectivo) as totalEf,
                SUM(v.total_deposito) as totalDep
            ')->first();

            $totalCount = (clone $base)->count();

            if ($totalCount > 4000) {
                return response()->json([
                    'error' => 'El período seleccionado es demasiado amplio (' . number_format($totalCount) . ' ventas). Reduzca el rango de fechas e intente nuevamente.',
                ], 422);
            }

            $textos = [
                'detallada' => [
                    'title' => 'LISTADO DE VENTAS DETALLADO',
                    'documentLabel' => 'Reporte de ventas',
                    'sectionTitle' => 'Ventas y detalle de productos',
                    'description' => 'Ventas registradas en el período seleccionado, con el detalle de productos y paquetes de cada una.',
                    'footerLabel' => 'Ventas detalladas',
                    'filename' => 'Ventas_Detalladas.pdf',
                    'empty' => 'No existen ventas para el período seleccionado.',
                ],
                'anuladas' => [
                    'title' => 'LISTADO DE VENTAS ANULADAS',
                    'documentLabel' => 'Ventas anuladas',
                    'sectionTitle' => 'Ventas anuladas',
                    'description' => 'Ventas anuladas en el período seleccionado, con el detalle de productos incluidos.',
                    'footerLabel' => 'Ventas anuladas',
                    'filename' => 'Ventas_Anuladas.pdf',
                    'empty' => 'No existen ventas anuladas para el período seleccionado.',
                ],
                'devolucion' => [
                    'title' => 'LISTADO DE VENTAS - DEVOLUCIÓN',
                    'documentLabel' => 'Devoluciones',
                    'sectionTitle' => 'Ventas con devolución',
                    'description' => 'Ventas marcadas como devolución en el período seleccionado, con el detalle de productos incluidos.',
                    'footerLabel' => 'Devoluciones',
                    'filename' => 'Ventas_Devolucion.pdf',
                    'empty' => 'No existen devoluciones para el período seleccionado.',
                ],
            ][$variant];

            $mpdf = new Mpdf([
                'mode' => 'utf-8', 'format' => 'Letter',
                'margin_top' => 10, 'margin_bottom' => 16, 'margin_left' => 10, 'margin_right' => 10,
            ]);

            $theme = trim(view('pdf.reportes.partials.corporate-letter-theme')->render());

            $viewData = [
                'title' => $textos['title'],
                'nombre_empresa' => $empresa->nombre,
                'direccion_empresa' => $empresa->direccion,
                'telefono_empresa' => $empresa->telefono,
                'logo_sistema' => $empresa->logo_sistema,
                'eyebrow' => 'Movimientos de venta',
                'documentLabel' => $usuarioNombre
                    ? 'Usuario: ' . $usuarioNombre
                    : ($clienteNombre ? 'Cliente: ' . $clienteNombre : $textos['documentLabel']),
                'sectionTitle' => $textos['sectionTitle'],
                'description' => $usuarioNombre
                    ? $textos['description'] . ' Filtrado por el usuario ' . $usuarioNombre . '.'
                    : ($clienteNombre ? $textos['description'] . ' Filtrado por el cliente ' . $clienteNombre . '.' : $textos['description']),
                'recordCount' => $totalCount,
                'recordLabel' => 'Ventas',
                'periodLabel' => 'Del ' . \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') . ' al ' . \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y'),
                'footerLabel' => $textos['footerLabel'],
            ];

            $mpdf->WriteHTML($theme, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-letter-header', $viewData)->render(), \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLFooter(view('pdf.reportes.partials.corporate-mpdf-footer', $viewData)->render(), '', true);

            $summaryItems = $variant === 'detallada'
                ? [
                    ['label' => 'Total venta', 'value' => 'Bs ' . number_format((float) ($totales->totalV ?? 0), 2, ',', '.')],
                    ['label' => 'Contado', 'value' => 'Bs ' . number_format((float) ($totales->totalCo ?? 0), 2, ',', '.')],
                    ['label' => 'Crédito', 'value' => 'Bs ' . number_format((float) ($totales->totalCr ?? 0), 2, ',', '.')],
                    ['label' => 'Efectivo', 'value' => 'Bs ' . number_format((float) ($totales->totalEf ?? 0), 2, ',', '.')],
                    ['label' => 'Depósito', 'value' => 'Bs ' . number_format((float) ($totales->totalDep ?? 0), 2, ',', '.')],
                ]
                : [['label' => $variant === 'anuladas' ? 'Total anulado' : 'Total devuelto', 'value' => 'Bs ' . number_format((float) ($totales->totalV ?? 0), 2, ',', '.')]];
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-summary-cards', ['items' => $summaryItems])->render(), \Mpdf\HTMLParserMode::HTML_BODY);

            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:46%">Venta / Producto</th><th style="width:18%">Costo unit.</th>'
                . '<th style="width:14%">Cantidad</th><th style="width:22%">Subtotal</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            (clone $base)
                ->select('v.id', 'v.fecha', 'c.nombre as cliente', 'u.name as usuario', 'v.total', 't.nombre as tipo_pago', 'f.nombre as forma_pago')
                ->orderBy('v.fecha')
                ->orderBy('v.id')
                ->chunk(100, function ($ventas) use ($mpdf) {
                    $ids = $ventas->pluck('id')->all();

                    $productos = DB::table('detalle_venta as d')
                        ->join('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                        ->join('articulo as p', 'ta.id_articulo', '=', 'p.id')
                        ->where('d.estado', '!=', '1')
                        ->whereIn('d.id_venta', $ids)
                        ->select('d.id_venta', 'd.cantidad', 'p.nombre_comercial as producto', 'd.costo_venta', 'd.sub_total')
                        ->get();

                    $paquetes = DB::table('detalle_venta_paquete as dvp')
                        ->join('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                        ->whereIn('dvp.id_venta', $ids)
                        ->select('dvp.id_venta', 'dvp.cantidad', 'pqt.nombre as producto', 'dvp.costo_venta', 'dvp.sub_total')
                        ->get();

                    $lineasPorVenta = $productos->concat($paquetes)->groupBy('id_venta');

                    $html = '';
                    foreach ($ventas as $venta) {
                        $html .= '<tr class="fc-group-row">'
                            . '<td colspan="3">' . e(\Carbon\Carbon::parse($venta->fecha)->format('d/m/Y')) . ' &middot; ' . e($venta->cliente)
                            . '<div class="is-muted">' . e($venta->tipo_pago) . ' / ' . e($venta->forma_pago) . ' &middot; ' . e($venta->usuario) . '</div></td>'
                            . '<td class="is-right">Total: Bs ' . number_format((float) $venta->total, 2, ',', '.') . '</td>'
                            . '</tr>';

                        $lineas = $lineasPorVenta->get($venta->id, collect());
                        if ($lineas->isEmpty()) {
                            $html .= '<tr class="fc-subrow"><td colspan="4" class="is-muted">Sin líneas de producto registradas.</td></tr>';
                            continue;
                        }
                        foreach ($lineas as $linea) {
                            $html .= '<tr class="fc-subrow">'
                                . '<td>' . e($linea->producto) . '</td>'
                                . '<td class="is-right">Bs ' . number_format((float) $linea->costo_venta, 2, ',', '.') . '</td>'
                                . '<td class="is-center">' . (float) $linea->cantidad . '</td>'
                                . '<td class="is-right">Bs ' . number_format((float) $linea->sub_total, 2, ',', '.') . '</td>'
                                . '</tr>';
                        }
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });

            if ($totalCount === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="4">' . e($textos['empty']) . '</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $content = $mpdf->Output($textos['filename'], 'S');

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $textos['filename'] . '"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en reporte de ventas detallado (' . $variant . '): ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de ventas'], 500);
        }
    }

    public function pdfProductoLaboratorio(Request $request)
    {
        $this->prepararEntornoReporte();
        try {
            $request->validate(['id_proveedor' => 'required|integer|exists:proveedor,id']);
            $idProveedor = (int) $request->id_proveedor;

            $proveedor = DB::table('proveedor')->where('id', $idProveedor)->first(['nombre']);
            abort_if(!$proveedor, 404, 'Laboratorio no encontrado.');

            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

            $base = DB::table('articulo as a')
                ->join('tienda_articulo as ta', 'ta.id_articulo', '=', 'a.id')
                ->join('unidad_medida as um', 'a.id_unidad', '=', 'um.id')
                ->where('ta.stock', '>', 0)
                ->where('a.id_proveedor', $idProveedor)
                ->where('a.estado', 1);

            $totalCount = (clone $base)->count();

            if ($totalCount > 4000) {
                return response()->json([
                    'error' => 'El laboratorio seleccionado tiene demasiados productos (' . number_format($totalCount) . '). Reduzca el catálogo o contacte al administrador.',
                ], 422);
            }

            $title = 'LISTADO DE PRODUCTO DETALLADO';

            $mpdf = new Mpdf([
                'mode' => 'utf-8', 'format' => 'Letter',
                'margin_top' => 10, 'margin_bottom' => 16, 'margin_left' => 10, 'margin_right' => 10,
            ]);

            $theme = trim(view('pdf.reportes.partials.corporate-letter-theme')->render());

            $viewData = [
                'title' => $title,
                'nombre_empresa' => $empresa->nombre,
                'direccion_empresa' => $empresa->direccion,
                'telefono_empresa' => $empresa->telefono,
                'logo_sistema' => $empresa->logo_sistema,
                'eyebrow' => 'Inventario',
                'documentLabel' => 'Laboratorio: ' . $proveedor->nombre,
                'sectionTitle' => 'Productos y lotes',
                'description' => 'Productos con stock disponible del laboratorio ' . $proveedor->nombre . ', con el detalle de lotes y fechas de vencimiento.',
                'recordCount' => $totalCount,
                'recordLabel' => 'Productos',
                'periodLabel' => null,
                'footerLabel' => 'Inventario por laboratorio',
            ];

            $mpdf->WriteHTML($theme, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-letter-header', $viewData)->render(), \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLFooter(view('pdf.reportes.partials.corporate-mpdf-footer', $viewData)->render(), '', true);

            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:46%">Producto</th><th style="width:20%">Presentación</th>'
                . '<th style="width:16%">Stock</th><th style="width:18%">Lotes</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            (clone $base)
                ->select('a.id', 'a.nombre_comercial as producto', 'ta.stock', 'um.nombre as presentacion')
                ->orderBy('a.nombre_comercial')
                ->chunk(300, function ($productos) use ($mpdf) {
                    $ids = $productos->pluck('id')->all();

                    $lotesPorProducto = DB::table('lote as l')
                        ->join('tienda_articulo as ta', 'l.id_producto', '=', 'ta.id')
                        ->whereIn('ta.id_articulo', $ids)
                        ->where('l.estado', '!=', 0)
                        ->where('l.cantidad', '>', 0)
                        ->select('ta.id_articulo', 'l.lote', 'l.cantidad', 'l.fecha_vecimiento')
                        ->orderBy('l.fecha_vecimiento')
                        ->get()
                        ->groupBy('id_articulo');

                    $html = '';
                    foreach ($productos as $producto) {
                        $html .= '<tr class="fc-group-row">'
                            . '<td colspan="2">' . e($producto->producto) . '</td>'
                            . '<td>' . e($producto->presentacion) . '</td>'
                            . '<td class="is-right">Stock: ' . (float) $producto->stock . '</td>'
                            . '</tr>';

                        $lotes = $lotesPorProducto->get($producto->id, collect());
                        if ($lotes->isEmpty()) {
                            $html .= '<tr class="fc-subrow"><td colspan="4" class="is-muted">Sin lotes registrados.</td></tr>';
                            continue;
                        }
                        foreach ($lotes as $lote) {
                            $html .= '<tr class="fc-subrow">'
                                . '<td colspan="2">Lote: ' . e($lote->lote) . '</td>'
                                . '<td>Vence: ' . e($lote->fecha_vecimiento) . '</td>'
                                . '<td class="is-right">' . (float) $lote->cantidad . '</td>'
                                . '</tr>';
                        }
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });

            if ($totalCount === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="4">No existen productos con stock disponible para este laboratorio.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $content = $mpdf->Output('Producto_Laboratorio.pdf', 'S');

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Producto_Laboratorio.pdf"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en pdfProductoLaboratorio: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de productos por laboratorio'], 500);
        }
    }
    public function pdfVentaDetalladaDevolucion(Request $request){
        return $this->buildVentaDetalladaReport($request, 'devolucion');
    }
    
    public function pdfVentaDetalladaAnulada(Request $request)
    {
        return $this->buildVentaDetalladaReport($request, 'anuladas');
    }

    
    public function pdfVentaDetalladaEfectivo(Request $request)
    {
        return $this->buildVentaFormaPagoReport($request, 'efectivo');
    }

    public function pdfVentaDetalladaEfectivoUsuario(Request $request)
    {
        $request->validate(['id_usuario' => 'required|integer|exists:users,id']);
        return $this->buildVentaFormaPagoReport($request, 'efectivo', (int) $request->id_usuario);
    }

    public function pdfVentaDetalladaTransfenciaUsuario(Request $request)
    {
        $request->validate(['id_usuario' => 'required|integer|exists:users,id']);
        return $this->buildVentaFormaPagoReport($request, 'transferencia', (int) $request->id_usuario);
    }

    public function pdfVentaDetalladaQrUsuario(Request $request)
    {
        $request->validate(['id_usuario' => 'required|integer|exists:users,id']);
        return $this->buildVentaFormaPagoReport($request, 'qr', (int) $request->id_usuario);
    }

    public function pdfVentaDetalladaDepositoUsuario(Request $request)
    {
        $request->validate(['id_usuario' => 'required|integer|exists:users,id']);
        return $this->buildVentaFormaPagoReport($request, 'deposito', (int) $request->id_usuario);
    }

    public function pdfVentaDetalladaMixtaUsuario(Request $request)
    {
        $request->validate(['id_usuario' => 'required|integer|exists:users,id']);
        return $this->buildVentaFormaPagoReport($request, 'mixta', (int) $request->id_usuario);
    }

    /**
     * Reportes de venta segmentados por forma de pago (Efectivo, Transferencia, QR,
     * Depósito, Mixta). Comparten la misma estructura de venta + detalle de productos
     * y paquetes agrupados; solo cambia el filtro de id_forma_pago y los textos.
     * $idUsuario, cuando se indica, acota el listado a las ventas de ese usuario
     * (reportes "por usuario").
     */
    private function buildVentaFormaPagoReport(Request $request, string $variant, ?int $idUsuario = null)
    {
        $this->prepararEntornoReporte();
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;

            if (!$fecha_inicio || !$fecha_fin || !$tipo_venta || !$id_tienda) {
                return response()->json(['error' => 'Parámetros requeridos faltantes'], 400);
            }

            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

            $usuarioNombre = $idUsuario !== null
                ? optional(DB::table('users')->find($idUsuario))->name
                : null;

            $config = [
                'efectivo' => [
                    'id_forma_pago' => 2, 'sumField' => 'total_efectivo', 'label' => 'Efectivo',
                    'title' => 'LISTADO DE VENTAS EFECTIVO DETALLADO', 'filename' => 'Ventas_Efectivo.pdf',
                ],
                'transferencia' => [
                    'id_forma_pago' => 3, 'sumField' => 'total_deposito', 'label' => 'Transferencia',
                    'title' => 'LISTADO DE VENTAS TRANSFERENCIA DETALLADO', 'filename' => 'Ventas_Transferencia.pdf',
                    'extraTipoPago' => 1,
                ],
                'qr' => [
                    'id_forma_pago' => 4, 'sumField' => 'total_deposito', 'label' => 'QR',
                    'title' => 'LISTADO DE VENTAS QR DETALLADO', 'filename' => 'Ventas_QR.pdf',
                ],
                'deposito' => [
                    'id_forma_pago' => 5, 'sumField' => 'total_deposito', 'label' => 'Depósito',
                    'title' => 'LISTADO DE VENTAS DEPÓSITO DETALLADO', 'filename' => 'Ventas_Deposito.pdf',
                ],
                'mixta' => [
                    'id_forma_pago' => 6, 'sumField' => 'total', 'label' => 'Mixta',
                    'title' => 'LISTADO DE VENTAS MIXTA DETALLADO', 'filename' => 'Ventas_Mixta.pdf',
                ],
            ][$variant];

            $base = DB::table('venta as v')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->join('tipo_pago as t', 'v.id_tipo_pago', '=', 't.id')
                ->join('forma_pago as f', 'v.id_forma_pago', '=', 'f.id')
                ->join('users as u', 'v.id_usuario', '=', 'u.id')
                ->where('v.estado', '!=', 'Anulado')
                ->where('v.tipo_venta', $tipo_venta)
                ->where('v.id_tienda', $id_tienda)
                ->where('v.id_forma_pago', $config['id_forma_pago'])
                ->whereBetween('v.fecha', [$fecha_inicio, $fecha_fin]);

            if (!empty($config['extraTipoPago'])) {
                $base->where('v.id_tipo_pago', $config['extraTipoPago']);
            }

            if ($idUsuario !== null) {
                $base->where('v.id_usuario', $idUsuario);
            }

            $totalMonto = (float) (clone $base)->sum('v.' . $config['sumField']);
            $totalCount = (clone $base)->count();

            if ($totalCount > 4000) {
                return response()->json([
                    'error' => 'El período seleccionado es demasiado amplio (' . number_format($totalCount) . ' ventas). Reduzca el rango de fechas e intente nuevamente.',
                ], 422);
            }

            $mpdf = new Mpdf([
                'mode' => 'utf-8', 'format' => 'Letter',
                'margin_top' => 10, 'margin_bottom' => 16, 'margin_left' => 10, 'margin_right' => 10,
            ]);

            $theme = trim(view('pdf.reportes.partials.corporate-letter-theme')->render());

            $viewData = [
                'title' => $config['title'],
                'nombre_empresa' => $empresa->nombre,
                'direccion_empresa' => $empresa->direccion,
                'telefono_empresa' => $empresa->telefono,
                'logo_sistema' => $empresa->logo_sistema,
                'eyebrow' => 'Movimientos de venta',
                'documentLabel' => $usuarioNombre ? 'Usuario: ' . $usuarioNombre : 'Forma de pago: ' . $config['label'],
                'sectionTitle' => 'Ventas por ' . $config['label'],
                'description' => 'Ventas registradas con forma de pago ' . $config['label'] . ' en el período seleccionado.'
                    . ($usuarioNombre ? ' Filtrado por el usuario ' . $usuarioNombre . '.' : ''),
                'recordCount' => $totalCount,
                'recordLabel' => 'Ventas',
                'periodLabel' => 'Del ' . \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') . ' al ' . \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y'),
                'footerLabel' => 'Ventas · ' . $config['label'],
            ];

            $mpdf->WriteHTML($theme, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-letter-header', $viewData)->render(), \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLFooter(view('pdf.reportes.partials.corporate-mpdf-footer', $viewData)->render(), '', true);

            $summaryItems = [
                ['label' => 'Total ' . $config['label'], 'value' => 'Bs ' . number_format($totalMonto, 2, ',', '.')],
            ];
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-summary-cards', ['items' => $summaryItems])->render(), \Mpdf\HTMLParserMode::HTML_BODY);

            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:46%">Venta / Producto</th><th style="width:18%">Costo unit.</th>'
                . '<th style="width:14%">Cantidad</th><th style="width:22%">Subtotal</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            (clone $base)
                ->select('v.id', 'v.fecha', 'c.nombre as cliente', 'u.name as usuario', 'v.total', 't.nombre as tipo_pago', 'f.nombre as forma_pago')
                ->orderBy('v.fecha')
                ->orderBy('v.id')
                ->chunk(100, function ($ventas) use ($mpdf) {
                    $ids = $ventas->pluck('id')->all();

                    $productos = DB::table('detalle_venta as d')
                        ->join('tienda_articulo as ta', 'd.id_producto', '=', 'ta.id')
                        ->join('articulo as p', 'ta.id_articulo', '=', 'p.id')
                        ->where('d.estado', '!=', '1')
                        ->whereIn('d.id_venta', $ids)
                        ->select('d.id_venta', 'd.cantidad', 'p.nombre_comercial as producto', 'd.costo_venta', 'd.sub_total')
                        ->get();

                    $paquetes = DB::table('detalle_venta_paquete as dvp')
                        ->join('paquetes as pqt', 'dvp.id_paquete', '=', 'pqt.id')
                        ->whereIn('dvp.id_venta', $ids)
                        ->select('dvp.id_venta', 'dvp.cantidad', 'pqt.nombre as producto', 'dvp.costo_venta', 'dvp.sub_total')
                        ->get();

                    $lineasPorVenta = $productos->concat($paquetes)->groupBy('id_venta');

                    $html = '';
                    foreach ($ventas as $venta) {
                        $html .= '<tr class="fc-group-row">'
                            . '<td colspan="3">' . e(\Carbon\Carbon::parse($venta->fecha)->format('d/m/Y')) . ' &middot; ' . e($venta->cliente)
                            . '<div class="is-muted">' . e($venta->tipo_pago) . ' / ' . e($venta->forma_pago) . ' &middot; ' . e($venta->usuario) . '</div></td>'
                            . '<td class="is-right">Total: Bs ' . number_format((float) $venta->total, 2, ',', '.') . '</td>'
                            . '</tr>';

                        $lineas = $lineasPorVenta->get($venta->id, collect());
                        if ($lineas->isEmpty()) {
                            $html .= '<tr class="fc-subrow"><td colspan="4" class="is-muted">Sin líneas de producto registradas.</td></tr>';
                            continue;
                        }
                        foreach ($lineas as $linea) {
                            $html .= '<tr class="fc-subrow">'
                                . '<td>' . e($linea->producto) . '</td>'
                                . '<td class="is-right">Bs ' . number_format((float) $linea->costo_venta, 2, ',', '.') . '</td>'
                                . '<td class="is-center">' . (float) $linea->cantidad . '</td>'
                                . '<td class="is-right">Bs ' . number_format((float) $linea->sub_total, 2, ',', '.') . '</td>'
                                . '</tr>';
                        }
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });

            if ($totalCount === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="4">No existen ventas con forma de pago ' . e($config['label']) . ' para el período seleccionado.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $content = $mpdf->Output($config['filename'], 'S');

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $config['filename'] . '"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en reporte de ventas por forma de pago (' . $variant . '): ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte'], 500);
        }
    }

    public function pdfVentaDetalladaTransfencia(Request $request)
    {
        return $this->buildVentaFormaPagoReport($request, 'transferencia');
    }

    public function pdfVentaDetalladaQr(Request $request) {
        return $this->buildVentaFormaPagoReport($request, 'qr');
    }


    public function pdfVentaDetalladaDeposito(Request $request)
    {
        return $this->buildVentaFormaPagoReport($request, 'deposito');
    }

    public function pdfVentaDetalladaMixta(Request $request)
    {
        return $this->buildVentaFormaPagoReport($request, 'mixta');
    }
    
    public function pdfPagoVenta(Request $request){
        $this->prepararEntornoReporte();
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;
            $tipo_venta = $request->tipo_venta;
            $id_tienda = $request->id_tienda;

            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

            $baseCuotas = DB::table('pago as p')
                ->join('venta as v', 'p.id_venta', '=', 'v.id')
                ->join('cliente as c', 'v.id_cliente', '=', 'c.id')
                ->where('p.id_tipo_pago', 2)
                ->where('p.estado', 1)
                ->where('v.tipo_venta', $tipo_venta)
                ->where('v.id_tienda', $id_tienda)
                ->whereDate('p.fecha', '>=', $fecha_inicio)
                ->whereDate('p.fecha', '<=', $fecha_fin);
            $totalCuotas = (clone $baseCuotas)->count();

            $baseAmortizaciones = DB::table('c_x_cobrar as c')
                ->join('pago as p', 'c.id_pago', '=', 'p.id')
                ->join('venta as v', 'p.id_venta', '=', 'v.id')
                ->join('cliente as i', 'v.id_cliente', '=', 'i.id')
                ->whereDate('c.fecha', '>=', $fecha_inicio)
                ->whereDate('c.fecha', '<=', $fecha_fin);
            $totalAmortizaciones = (clone $baseAmortizaciones)->count();

            $title = 'LISTADO DE PAGOS AL CRÉDITO';

            $mpdf = new Mpdf([
                'mode' => 'utf-8', 'format' => 'Letter',
                'margin_top' => 10, 'margin_bottom' => 16, 'margin_left' => 10, 'margin_right' => 10,
            ]);

            $theme = trim(view('pdf.reportes.partials.corporate-letter-theme')->render());

            $viewData = [
                'title' => $title,
                'nombre_empresa' => $empresa->nombre,
                'direccion_empresa' => $empresa->direccion,
                'telefono_empresa' => $empresa->telefono,
                'logo_sistema' => $empresa->logo_sistema,
                'eyebrow' => 'Cuentas por cobrar',
                'documentLabel' => 'Cuotas de venta',
                'sectionTitle' => 'Cuotas al crédito y amortizaciones',
                'description' => 'Cuotas de venta pendientes y amortizaciones registradas en el período seleccionado.',
                'recordCount' => $totalCuotas + $totalAmortizaciones,
                'recordLabel' => 'Registros',
                'periodLabel' => 'Del ' . \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') . ' al ' . \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y'),
                'footerLabel' => 'Cuotas de venta',
            ];

            $mpdf->WriteHTML($theme, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-letter-header', $viewData)->render(), \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLFooter(view('pdf.reportes.partials.corporate-mpdf-footer', $viewData)->render(), '', true);

            $mpdf->WriteHTML('<h3 style="margin:0 0 6px;color:#173f32;font-size:10.5px;">Cuotas pendientes en el período</h3>', \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:14%">Fecha</th><th style="width:14%">Vencimiento</th><th style="width:26%">Cliente</th>'
                . '<th style="width:16%">Monto</th><th style="width:16%">Saldo</th><th style="width:14%">Estado</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            (clone $baseCuotas)
                ->select('p.fecha', 'p.fecha_final', 'c.nombre as cliente', 'p.monto', 'p.saldo', 'p.estado')
                ->orderBy('p.id')
                ->chunk(300, function ($rows) use ($mpdf) {
                    $html = '';
                    foreach ($rows as $row) {
                        $html .= '<tr>'
                            . '<td>' . e($row->fecha) . '</td>'
                            . '<td>' . e($row->fecha_final) . '</td>'
                            . '<td class="is-strong">' . e($row->cliente) . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->monto, 2, ',', '.') . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->saldo, 2, ',', '.') . '</td>'
                            . '<td><span class="fc-status ' . ((int) $row->estado === 0 ? 'fc-status--closed' : '') . '">' . ((int) $row->estado === 1 ? 'Pendiente' : 'Cancelado') . '</span></td>'
                            . '</tr>';
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });
            if ($totalCuotas === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="6">No existen cuotas pendientes para el período seleccionado.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $mpdf->WriteHTML('<h3 style="margin:14px 0 6px;color:#173f32;font-size:10.5px;">Historial de amortizaciones</h3>', \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:14%">Fecha</th><th style="width:26%">Cliente</th><th style="width:16%">Amortizado</th>'
                . '<th style="width:16%">Saldo</th><th style="width:28%">Descripción</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            (clone $baseAmortizaciones)
                ->select('c.fecha', 'i.nombre as cliente', 'c.amortizacion', 'c.saldo', 'c.descripcion')
                ->orderBy('c.id')
                ->chunk(300, function ($rows) use ($mpdf) {
                    $html = '';
                    foreach ($rows as $row) {
                        $html .= '<tr>'
                            . '<td>' . e($row->fecha) . '</td>'
                            . '<td class="is-strong">' . e($row->cliente) . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->amortizacion, 2, ',', '.') . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->saldo, 2, ',', '.') . '</td>'
                            . '<td class="is-muted">' . e($row->descripcion ?: '—') . '</td>'
                            . '</tr>';
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });
            if ($totalAmortizaciones === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="5">No existen amortizaciones registradas.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $content = $mpdf->Output('Cuotas_Venta.pdf', 'S');

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Cuotas_Venta.pdf"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en pdfPagoVenta: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de cuotas de venta'], 500);
        }
    }
    
    private function pdfClienteBlade()
    {
        $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
        abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

        $clientes = Cliente::select('nombre as cliente', 'direccion', 'telefono', 'matricula')
            ->where('estado', '!=', 0)
            ->where('id', '!=', 1)
            ->orderBy('nombre')
            ->get();

        $pdf = \PDF::loadView('pdf.reportes.cliente.cliente', [
            'title' => 'LISTA DE CLIENTES',
            'nombre_empresa' => $empresa->nombre,
            'direccion_empresa' => $empresa->direccion,
            'telefono_empresa' => $empresa->telefono,
            'foto_empresa' => $empresa->foto,
            'logo_sistema' => $empresa->logo_sistema,
            'clientes' => $clientes,
            'total_clientes' => $clientes->count(),
        ]);

        return $pdf->setPaper('letter', 'portrait')->stream('Lista_Clientes.pdf');
    }

    public function pdfCliente()
    {
        $this->prepararEntornoReporte();
        try {
            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

            $query = Cliente::select('nombre as cliente', 'direccion', 'telefono', 'matricula')
                ->where('estado', '!=', 0)
                ->where('id', '!=', 1)
                ->orderBy('nombre');
            $totalCount = (clone $query)->count();
            $title = 'LISTA DE CLIENTES';

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'Letter',
                'margin_top' => 10,
                'margin_bottom' => 16,
                'margin_left' => 10,
                'margin_right' => 10,
            ]);

            // mPDF's HEADER_CSS parser only applies CSS rules to every page (headers/footers
            // included) when the stylesheet keeps its <style> wrapper; stripping it breaks
            // repeating footers after page 1.
            $theme = trim(view('pdf.reportes.partials.corporate-letter-theme')->render());

            $viewData = [
                'title' => $title,
                'nombre_empresa' => $empresa->nombre,
                'direccion_empresa' => $empresa->direccion,
                'telefono_empresa' => $empresa->telefono,
                'logo_sistema' => $empresa->logo_sistema,
                'eyebrow' => 'Directorio comercial',
                'documentLabel' => 'Reporte de clientes',
                'sectionTitle' => 'Clientes activos',
                'description' => 'Directorio de clientes habilitados para las operaciones comerciales del sistema.',
                'recordCount' => $totalCount,
                'recordLabel' => 'Clientes',
                'footerLabel' => 'Directorio de clientes',
            ];
            $mpdf->WriteHTML($theme, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-letter-header', $viewData)->render(), \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLFooter(view('pdf.reportes.partials.corporate-mpdf-footer', $viewData)->render(), '', true);

            $mpdf->WriteHTML('<table class="fc-table"><thead><tr><th style="width:6%">N.º</th><th style="width:27%">Cliente</th><th style="width:16%">Documento</th><th style="width:17%">Teléfono</th><th style="width:34%">Dirección</th></tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            $index = 1;
            $query->chunk(500, function ($clientes) use ($mpdf, &$index) {
                $rows = '';
                foreach ($clientes as $cliente) {
                    $rows .= '<tr>'
                        . '<td class="is-center">' . $index++ . '</td>'
                        . '<td class="is-strong">' . e($cliente->cliente ?: '—') . '</td>'
                        . '<td>' . e($cliente->matricula ?: '—') . '</td>'
                        . '<td>' . e($cliente->telefono ?: '—') . '</td>'
                        . '<td>' . e($cliente->direccion ?: '—') . '</td>'
                        . '</tr>';
                }
                $mpdf->WriteHTML($rows, \Mpdf\HTMLParserMode::HTML_BODY);
            });
            if ($totalCount === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="5">No existen clientes activos registrados.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $content = $mpdf->Output('Lista_Clientes.pdf', 'S');

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Lista_Clientes.pdf"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en pdfCliente: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de clientes'], 500);
        }
    }

    private function pdfClienteLegacy()
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
            $this->aplicarTemaReporte($mpdf);

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
        $this->prepararEntornoReporte();
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;

            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

            $baseCuotas = DB::table('pago_compra as p')
                ->join('compra as c', 'p.id_compra', '=', 'c.id')
                ->join('proveedor as pr', 'c.id_proveedor', '=', 'pr.id')
                ->where('p.id_tipo_pago', 2)
                ->where('c.estado', 'Registrado')
                ->whereDate('p.fecha', '>=', $fecha_inicio)
                ->whereDate('p.fecha', '<=', $fecha_fin);
            $totalCuotas = (clone $baseCuotas)->count();

            $baseAmortizaciones = DB::table('c_x_pagar as c')
                ->join('pago_compra as p', 'c.id_pago', '=', 'p.id')
                ->join('compra as v', 'p.id_compra', '=', 'v.id')
                ->join('proveedor as i', 'v.id_proveedor', '=', 'i.id')
                ->whereDate('c.fecha', '>=', $fecha_inicio)
                ->whereDate('c.fecha', '<=', $fecha_fin);
            $totalAmortizaciones = (clone $baseAmortizaciones)->count();

            $title = 'LISTADO DE PAGOS AL CRÉDITO';

            $mpdf = new Mpdf([
                'mode' => 'utf-8', 'format' => 'Letter',
                'margin_top' => 10, 'margin_bottom' => 16, 'margin_left' => 10, 'margin_right' => 10,
            ]);

            $theme = trim(view('pdf.reportes.partials.corporate-letter-theme')->render());

            $viewData = [
                'title' => $title,
                'nombre_empresa' => $empresa->nombre,
                'direccion_empresa' => $empresa->direccion,
                'telefono_empresa' => $empresa->telefono,
                'logo_sistema' => $empresa->logo_sistema,
                'eyebrow' => 'Cuentas por pagar',
                'documentLabel' => 'Cuotas de compra',
                'sectionTitle' => 'Cuotas al crédito y amortizaciones',
                'description' => 'Cuotas de compra pendientes y amortizaciones registradas en el período seleccionado.',
                'recordCount' => $totalCuotas + $totalAmortizaciones,
                'recordLabel' => 'Registros',
                'periodLabel' => 'Del ' . \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') . ' al ' . \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y'),
                'footerLabel' => 'Cuotas de compra',
            ];

            $mpdf->WriteHTML($theme, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-letter-header', $viewData)->render(), \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLFooter(view('pdf.reportes.partials.corporate-mpdf-footer', $viewData)->render(), '', true);

            $mpdf->WriteHTML('<h3 style="margin:0 0 6px;color:#173f32;font-size:10.5px;">Cuotas pendientes en el período</h3>', \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:14%">Fecha</th><th style="width:14%">Vencimiento</th><th style="width:26%">Proveedor</th>'
                . '<th style="width:16%">Monto</th><th style="width:16%">Saldo</th><th style="width:14%">Estado</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            (clone $baseCuotas)
                ->select('p.fecha', 'p.fecha_final', 'pr.nombre as proveedor', 'p.monto', 'p.saldo', 'p.estado')
                ->orderBy('p.id')
                ->chunk(300, function ($rows) use ($mpdf) {
                    $html = '';
                    foreach ($rows as $row) {
                        $html .= '<tr>'
                            . '<td>' . e($row->fecha) . '</td>'
                            . '<td>' . e($row->fecha_final) . '</td>'
                            . '<td class="is-strong">' . e($row->proveedor) . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->monto, 2, ',', '.') . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->saldo, 2, ',', '.') . '</td>'
                            . '<td><span class="fc-status ' . ($row->estado === 'Cancelado' ? 'fc-status--closed' : '') . '">' . e($row->estado ?: '—') . '</span></td>'
                            . '</tr>';
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });
            if ($totalCuotas === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="6">No existen cuotas pendientes para el período seleccionado.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $mpdf->WriteHTML('<h3 style="margin:14px 0 6px;color:#173f32;font-size:10.5px;">Historial de amortizaciones</h3>', \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:14%">Fecha</th><th style="width:26%">Proveedor</th><th style="width:16%">Amortizado</th>'
                . '<th style="width:16%">Saldo</th><th style="width:28%">Descripción</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            (clone $baseAmortizaciones)
                ->select('c.fecha', 'i.nombre as proveedor', 'c.amortizacion', 'c.saldo', 'c.descripcion')
                ->orderBy('c.id')
                ->chunk(300, function ($rows) use ($mpdf) {
                    $html = '';
                    foreach ($rows as $row) {
                        $html .= '<tr>'
                            . '<td>' . e($row->fecha) . '</td>'
                            . '<td class="is-strong">' . e($row->proveedor) . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->amortizacion, 2, ',', '.') . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->saldo, 2, ',', '.') . '</td>'
                            . '<td class="is-muted">' . e($row->descripcion ?: '—') . '</td>'
                            . '</tr>';
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });
            if ($totalAmortizaciones === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="5">No existen amortizaciones registradas.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $content = $mpdf->Output('Cuotas_Compra.pdf', 'S');

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Cuotas_Compra.pdf"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en pdfPagoCompra: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de cuotas de compra'], 500);
        }
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
        return $this->buildProductoVencimientoReport($request, false);
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
        return $this->buildProductoVencimientoReport($request, true);
    }

    private function buildProductoVencimientoReport(Request $request, bool $limitarRango90Dias)
    {
        $this->prepararEntornoReporte();
        try {
            $request->validate([
                'anio' => 'required|integer',
                'id_proveedor' => 'required|integer|exists:proveedor,id',
                'id_tienda' => 'required|exists:tienda,id',
            ]);

            $anio = $request->anio;
            $idProveedor = (int) $request->id_proveedor;
            $idTienda = $request->id_tienda;

            $proveedor = DB::table('proveedor')->where('id', $idProveedor)->first(['nombre']);
            abort_if(!$proveedor, 404, 'Laboratorio no encontrado.');

            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

            $base = DB::table('lote as l')
                ->join('tienda_articulo as ta', 'l.id_producto', '=', 'ta.id')
                ->join('articulo as a', 'ta.id_articulo', '=', 'a.id')
                ->join('unidad_medida as um', 'a.id_unidad', '=', 'um.id')
                ->where('l.cantidad', '!=', 0)
                ->where('l.estado', '!=', 0)
                ->where('a.id_proveedor', $idProveedor)
                ->where('ta.id_tienda', $idTienda)
                ->whereYear('l.fecha_vecimiento', $anio);

            if ($limitarRango90Dias) {
                $fecha1 = now()->toDateString();
                $fecha2 = now()->addDays(90)->toDateString();
                $base->whereBetween('l.fecha_vecimiento', [$fecha1, $fecha2]);
            }

            $totalCount = (clone $base)->count();

            if ($totalCount > 4000) {
                return response()->json([
                    'error' => 'El laboratorio seleccionado tiene demasiados lotes por vencer (' . number_format($totalCount) . '). Contacte al administrador.',
                ], 422);
            }

            $title = 'LISTA DE PRODUCTOS';

            $mpdf = new Mpdf([
                'mode' => 'utf-8', 'format' => 'Letter-L',
                'margin_top' => 10, 'margin_bottom' => 16, 'margin_left' => 10, 'margin_right' => 10,
            ]);

            $theme = trim(view('pdf.reportes.partials.corporate-letter-theme')->render());

            $viewData = [
                'title' => $title,
                'nombre_empresa' => $empresa->nombre,
                'direccion_empresa' => $empresa->direccion,
                'telefono_empresa' => $empresa->telefono,
                'logo_sistema' => $empresa->logo_sistema,
                'eyebrow' => 'Vencimientos',
                'documentLabel' => 'Laboratorio: ' . $proveedor->nombre,
                'sectionTitle' => $limitarRango90Dias ? 'Productos por vencer en los próximos 3 meses' : 'Productos por vencer en el año',
                'description' => 'Lotes con stock disponible del laboratorio ' . $proveedor->nombre . ' cuya fecha de vencimiento cae dentro del período seleccionado.',
                'recordCount' => $totalCount,
                'recordLabel' => 'Lotes',
                'periodLabel' => 'Año ' . $anio,
                'footerLabel' => 'Vencimiento de productos',
            ];

            $mpdf->WriteHTML($theme, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-letter-header', $viewData)->render(), \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLFooter(view('pdf.reportes.partials.corporate-mpdf-footer', $viewData)->render(), '', true);

            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:5%">N.º</th><th style="width:24%">Producto</th><th style="width:24%">Nombre genérico</th>'
                . '<th style="width:9%">F. Venc.</th><th style="width:9%">Ubic.</th><th style="width:8%">P. Unidad</th>'
                . '<th style="width:8%">P. Blister</th><th style="width:8%">P. Caja</th><th style="width:5%">Stock</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            $numero = 0;
            (clone $base)
                ->select(
                    'a.nombre_comercial', 'a.nombre_generico', 'l.fecha_vecimiento', 'a.ubicacion',
                    'a.costo_unitario', 'a.precio_blister', 'a.precio_caja', 'l.cantidad as stock'
                )
                ->orderBy('l.fecha_vecimiento')
                ->chunk(300, function ($rows) use ($mpdf, &$numero) {
                    $html = '';
                    foreach ($rows as $row) {
                        $numero++;
                        $html .= '<tr>'
                            . '<td class="is-center">' . $numero . '</td>'
                            . '<td>' . e($row->nombre_comercial) . '</td>'
                            . '<td>' . e($row->nombre_generico) . '</td>'
                            . '<td class="is-center">' . e($row->fecha_vecimiento) . '</td>'
                            . '<td class="is-center">' . e($row->ubicacion) . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->costo_unitario, 2, ',', '.') . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->precio_blister, 2, ',', '.') . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->precio_caja, 2, ',', '.') . '</td>'
                            . '<td class="is-center">' . (float) $row->stock . '</td>'
                            . '</tr>';
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });

            if ($totalCount === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="9">No existen productos por vencer para los filtros seleccionados.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $content = $mpdf->Output('Producto_Vencimiento.pdf', 'S');

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Producto_Vencimiento.pdf"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en reporte de productos por vencer: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de productos por vencer'], 500);
        }
    }

    public function pdfVentaDetalladaCliente(Request $request)
    {
        $request->validate(['id_cliente' => 'required|integer|exists:cliente,id']);
        return $this->buildVentaDetalladaReport($request, 'detallada', null, (int) $request->id_cliente);
    }

    public function pdfVentaClienteCredito(Request $request){
        return $this->buildVentaClienteCreditoReport($request);
    }

    public function pdfVentaClienteCreditoUsuario(Request $request){
        $request->validate(['id_usuario' => 'required|integer|exists:users,id']);
        return $this->buildVentaClienteCreditoReport($request, (int) $request->id_usuario);
    }

    private function buildVentaClienteCreditoReport(Request $request, ?int $idUsuario = null)
    {
        $this->prepararEntornoReporte();
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;

            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

            $usuarioNombre = $idUsuario !== null
                ? optional(DB::table('users')->find($idUsuario))->name
                : null;

            $base = DB::table('venta')
                ->join('pago', 'pago.id_venta', '=', 'venta.id')
                ->join('cliente', 'venta.id_cliente', '=', 'cliente.id')
                ->join('c_x_cobrar', 'c_x_cobrar.id_pago', '=', 'pago.id')
                ->leftJoin('forma_pago', 'c_x_cobrar.id_forma_pago', '=', 'forma_pago.id')
                ->where('c_x_cobrar.amortizacion', '>', 0)
                ->whereDate('c_x_cobrar.fecha', '>=', $fecha_inicio)
                ->whereDate('c_x_cobrar.fecha', '<=', $fecha_fin);

            if ($idUsuario !== null) {
                $base->where('c_x_cobrar.id_usuario', $idUsuario);
            }

            $totales = (clone $base)->selectRaw('SUM(c_x_cobrar.amortizacion) as totalGeneral')->first();
            $totalGeneral = (float) ($totales->totalGeneral ?? 0);
            $totalCount = (clone $base)->count();

            $title = 'LISTADO DE PAGO AL CRÉDITO';

            $mpdf = new Mpdf([
                'mode' => 'utf-8', 'format' => 'Letter',
                'margin_top' => 10, 'margin_bottom' => 16, 'margin_left' => 10, 'margin_right' => 10,
            ]);

            $theme = trim(view('pdf.reportes.partials.corporate-letter-theme')->render());

            $viewData = [
                'title' => $title,
                'nombre_empresa' => $empresa->nombre,
                'direccion_empresa' => $empresa->direccion,
                'telefono_empresa' => $empresa->telefono,
                'logo_sistema' => $empresa->logo_sistema,
                'eyebrow' => 'Cuentas por cobrar',
                'documentLabel' => $usuarioNombre ? 'Usuario: ' . $usuarioNombre : 'Pago a crédito',
                'sectionTitle' => 'Amortizaciones de clientes',
                'description' => 'Pagos al crédito realizados por clientes en el período seleccionado.'
                    . ($usuarioNombre ? ' Registrados por el usuario ' . $usuarioNombre . '.' : ''),
                'recordCount' => $totalCount,
                'recordLabel' => 'Pagos',
                'periodLabel' => 'Del ' . \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') . ' al ' . \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y'),
                'footerLabel' => 'Pago a crédito de ventas',
            ];

            $mpdf->WriteHTML($theme, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-letter-header', $viewData)->render(), \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLFooter(view('pdf.reportes.partials.corporate-mpdf-footer', $viewData)->render(), '', true);

            $summaryItems = [
                ['label' => 'Total general', 'value' => 'Bs ' . number_format($totalGeneral, 2, ',', '.')],
            ];
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-summary-cards', ['items' => $summaryItems])->render(), \Mpdf\HTMLParserMode::HTML_BODY);

            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:18%">Fecha</th><th style="width:36%">Cliente</th>'
                . '<th style="width:24%">Forma de pago</th><th style="width:22%">Monto</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            (clone $base)
                ->select('c_x_cobrar.fecha', 'cliente.nombre as cliente', 'c_x_cobrar.amortizacion as total', 'forma_pago.nombre as forma')
                ->orderBy('c_x_cobrar.id')
                ->chunk(300, function ($rows) use ($mpdf) {
                    $html = '';
                    foreach ($rows as $row) {
                        $html .= '<tr>'
                            . '<td>' . e($row->fecha) . '</td>'
                            . '<td class="is-strong">' . e($row->cliente) . '</td>'
                            . '<td>' . e($row->forma ?: '—') . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->total, 2, ',', '.') . '</td>'
                            . '</tr>';
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });

            if ($totalCount === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="4">No existen pagos al crédito para el período seleccionado.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $content = $mpdf->Output('Pago_Credito_Venta.pdf', 'S');

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Pago_Credito_Venta.pdf"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en pdfVentaClienteCredito: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de pago a crédito'], 500);
        }
    }

    public function pdfCompraProveedorCredito(Request $request){
        $this->prepararEntornoReporte();
        try {
            $fecha_inicio = $request->fecha_inicio;
            $fecha_fin = $request->fecha_fin;

            $empresa = MiEmpresa::first(['nombre', 'direccion', 'telefono', 'foto', 'logo_sistema']);
            abort_if(!$empresa, 422, 'Configure los datos de la empresa antes de generar reportes.');

            $base = DB::table('compra')
                ->join('pago_compra', 'pago_compra.id_compra', '=', 'compra.id')
                ->join('proveedor', 'compra.id_proveedor', '=', 'proveedor.id')
                ->join('c_x_pagar', 'c_x_pagar.id_pago', '=', 'pago_compra.id')
                ->leftJoin('forma_pago', 'c_x_pagar.id_forma_pago', '=', 'forma_pago.id')
                ->where('c_x_pagar.amortizacion', '>', 0)
                ->whereDate('c_x_pagar.fecha', '>=', $fecha_inicio)
                ->whereDate('c_x_pagar.fecha', '<=', $fecha_fin);

            $totales = (clone $base)->selectRaw('SUM(c_x_pagar.amortizacion) as totalGeneral')->first();
            $totalGeneral = (float) ($totales->totalGeneral ?? 0);
            $totalCount = (clone $base)->count();

            $title = 'LISTADO DE PAGO AL CRÉDITO COMPRA';

            $mpdf = new Mpdf([
                'mode' => 'utf-8', 'format' => 'Letter',
                'margin_top' => 10, 'margin_bottom' => 16, 'margin_left' => 10, 'margin_right' => 10,
            ]);

            $theme = trim(view('pdf.reportes.partials.corporate-letter-theme')->render());

            $viewData = [
                'title' => $title,
                'nombre_empresa' => $empresa->nombre,
                'direccion_empresa' => $empresa->direccion,
                'telefono_empresa' => $empresa->telefono,
                'logo_sistema' => $empresa->logo_sistema,
                'eyebrow' => 'Cuentas por pagar',
                'documentLabel' => 'Pago a crédito',
                'sectionTitle' => 'Amortizaciones a proveedores',
                'description' => 'Pagos al crédito realizados a proveedores en el período seleccionado.',
                'recordCount' => $totalCount,
                'recordLabel' => 'Pagos',
                'periodLabel' => 'Del ' . \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') . ' al ' . \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y'),
                'footerLabel' => 'Pago a crédito de compras',
            ];

            $mpdf->WriteHTML($theme, \Mpdf\HTMLParserMode::HEADER_CSS);
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-letter-header', $viewData)->render(), \Mpdf\HTMLParserMode::HTML_BODY);
            $mpdf->SetHTMLFooter(view('pdf.reportes.partials.corporate-mpdf-footer', $viewData)->render(), '', true);

            $summaryItems = [
                ['label' => 'Total general', 'value' => 'Bs ' . number_format($totalGeneral, 2, ',', '.')],
            ];
            $mpdf->WriteHTML(view('pdf.reportes.partials.corporate-summary-cards', ['items' => $summaryItems])->render(), \Mpdf\HTMLParserMode::HTML_BODY);

            $mpdf->WriteHTML('<table class="fc-table"><thead><tr>'
                . '<th style="width:18%">Fecha</th><th style="width:36%">Proveedor</th>'
                . '<th style="width:24%">Forma de pago</th><th style="width:22%">Monto</th>'
                . '</tr></thead><tbody>', \Mpdf\HTMLParserMode::HTML_BODY);

            (clone $base)
                ->select('c_x_pagar.fecha', 'proveedor.nombre as proveedor', 'c_x_pagar.amortizacion as total', 'forma_pago.nombre as forma')
                ->orderBy('c_x_pagar.id')
                ->chunk(300, function ($rows) use ($mpdf) {
                    $html = '';
                    foreach ($rows as $row) {
                        $html .= '<tr>'
                            . '<td>' . e($row->fecha) . '</td>'
                            . '<td class="is-strong">' . e($row->proveedor) . '</td>'
                            . '<td>' . e($row->forma ?: '—') . '</td>'
                            . '<td class="is-right">Bs ' . number_format((float) $row->total, 2, ',', '.') . '</td>'
                            . '</tr>';
                    }
                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
                });

            if ($totalCount === 0) {
                $mpdf->WriteHTML('<tr><td class="fc-empty" colspan="4">No existen pagos al crédito para el período seleccionado.</td></tr>', \Mpdf\HTMLParserMode::HTML_BODY);
            }
            $mpdf->WriteHTML('</tbody></table>', \Mpdf\HTMLParserMode::HTML_BODY);

            $content = $mpdf->Output('Pago_Credito_Compra.pdf', 'S');

            return response($content, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Pago_Credito_Compra.pdf"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error en pdfCompraProveedorCredito: ' . $e->getMessage());
            return response()->json(['error' => 'Error al generar el reporte de pago a crédito'], 500);
        }
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

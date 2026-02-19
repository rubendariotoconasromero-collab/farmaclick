<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\Tienda;
use App\Models\MiEmpresa;
use App\Models\Articulo;
use DateTime;
use DB;

class ProductoInventarioExport implements FromView, ShouldAutoSize, WithTitle, WithColumnFormatting
{
    private $consulta;
    public function __construct(Request $consulta)
    {
        $this->consulta = $consulta;  
    }
    // Modelo de la vista 
    public function view(): View
    {
        $x=DB::select("SELECT l.id , l.fecha_vecimiento , l.cantidad as stock, p.nombre_comercial,p.nombre_generico, p.ubicacion, c.nombre as categoria,
        p.costo_unitario, p.precio_blister,p.precio_caja, p.stock_minimo, p.costo_compra, ta.id_tienda, ta.id_articulo, t.nombre as tienda,pr.nombre as proveedor
        FROM lote as l, tienda_articulo ta, articulo p, categoria c, tienda t , proveedor as pr
        WHERE l.id_producto=ta.id and l.estado!=0 and p.estado!=0 and ta.id_tienda=t.id and ta.id_articulo=p.id and c.id=p.id_categoria 
        and p.id_proveedor=pr.id  ORDER BY l.fecha_vecimiento, pr.nombre");

        $obj = json_decode(json_encode($x), true);

        $mi_empresa= MiEmpresa::select('mi_empresa.nombre','mi_empresa.nit','mi_empresa.representante','mi_empresa.direccion','mi_empresa.telefono'
        ,'mi_empresa.localidad','mi_empresa.Correo','mi_empresa.sitio_web','mi_empresa.foto')
        ->get();
        $titulo = $this->title();
        $objdate = new DateTime();
        $fecha_impresion=$objdate->format('d/m/Y');
        $nombre_empresa=$mi_empresa[0]->nombre;
        $direccion_empresa=$mi_empresa[0]->direccion;
        $telefono_empresa=$mi_empresa[0]->telefono;
        $foto_empresa=$mi_empresa[0]->foto;

        $detalles=$obj;
        
        $cont=Articulo::count();

        return view('excel.reportes.producto.producto_inventario',[
            'titulo'=>$titulo,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'foto_empresa'=>$foto_empresa,
            'detalles'=>$detalles,
        ]);
    }

    // Titulo de la pestaña
    public function title(): string
    {
        return 'LISTA DE PRODUCTOS';
    }  

    // Formato de celdas específicas
    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, 
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,    
            // 'K' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }

}

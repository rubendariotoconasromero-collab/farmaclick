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

class ProductoExport implements FromView, ShouldAutoSize, WithTitle, WithColumnFormatting
{
    private $consulta;
    public function __construct(Request $consulta)
    {
        $this->consulta = $consulta;  
    }
    // Modelo de la vista 
    public function view(): View
    {
        $x=DB::select("SELECT articulo.nombre_comercial as producto ,tienda_articulo.stock, articulo.costo_compra as compra , articulo.costo_unitario as venta 
        FROM tienda_articulo,articulo,tienda
        WHERE tienda_articulo.id_articulo=articulo.id and tienda_articulo.id_tienda=tienda.id and articulo.estado!=0");

        $obj = json_decode(json_encode($x), true);
        //dd($obj);
    
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

        return view('excel.reportes.producto.producto',[
            'titulo'=>$titulo,
            'nombre_empresa'=>$nombre_empresa,
            'direccion_empresa'=>$direccion_empresa,
            'foto_empresa'=>$foto_empresa,
            'detalles'=>$detalles,
            'total'=>$total,
            'total1'=>$total1,
            'totalResultado'=>$totalResultado,
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

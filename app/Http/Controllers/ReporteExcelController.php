<?php

namespace App\Http\Controllers;
use App\Exports\ProductoExport;
use App\Exports\ProductoInventarioExport;

use App\Exports\ProductoStockExport;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;

class ReporteExcelController extends Controller
{
    public function ExcelProducto(Request $request)
    {
        return Excel::download(new ProductoExport($request), 'Producto.xlsx');
    }
    public function ExcelProductoMinimo(Request $request)
    {
        return Excel::download(new ProductoStockExport($request), 'Producto.xlsx');
    }
    public function ExcelProductoInventario(Request $request)
    {
        return Excel::download(new ProductoInventarioExport($request), 'Producto.xlsx');
    }
}

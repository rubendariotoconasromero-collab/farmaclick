<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        /* Establece márgenes de página */
        @page {
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
            size: letter; /* Define explícitamente el tamaño carta */
        }

        /* Estilos para el cuerpo del documento */
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 0;
            /* Evita saltos de página innecesarios */
            page-break-inside: avoid;
        }

        /* Tabla de encabezado */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .header-table th, .header-table td {
            border: none;
            vertical-align: top;
        }
        .logo-placeholder {
            width: 70px;
            height: 70px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #001843;
            margin-bottom: 4px;
        }
        .report-subtitle {
            font-size: 11px;
            color: #555;
        }

        /* Tabla de productos */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 9px;
            /* Evita que se corte la tabla entre páginas */
            page-break-inside: auto;
        }
        .table th, .table td {
            padding: 5px;
            border: 1px solid #000;
            text-align: left;
        }
        .table th {
            background-color: #001843;
            color: white;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bg-light { background-color: #f9f9f9; }
        .footer-note {
            font-size: 9px;
            color: #777;
            margin-top: 10px;
            text-align: center;
        }

        /* Asegura que las filas no se rompan */
        .table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

    </style>
</head>
<body>

<table style="width: 100%; border-collapse: collapse; margin-bottom: 15px; table-layout: fixed;">
    <tr>
        <td style="width: 80px; text-align: center; vertical-align: middle; padding: 10px;">
            @if($logo_sistema)
                <img src="{{ 'img/logo/' . $logo_sistema }}" 
                    style="height: 60px; width: auto; display: block; margin: 0 auto;" 
                    alt="Logo de la Empresa">
            @else
                <div style="width: 60px; height: 60px; background-color: #f0f0f0; border: 1px solid #ccc; margin: 0 auto;"></div>
            @endif
        </td>
        <td style="text-align: center; vertical-align: middle; padding: 10px;">
            <div style="font-size: 20px; font-weight: bold; color: #001843; line-height: 1.3; margin: 0; padding: 0;">
                {{ strtoupper($title) }}
            </div>
            <small>{{ strtoupper($subtitle)}}</small>
        </td>
        <td style="width: 80px; padding: 10px;">
        </td>
    </tr>
</table>

<!-- Tabla de productos -->
@if($productos->isNotEmpty())
    <table class="table">
        <thead>
            <tr>
                <th>Lote</th>
                <th>Artículo Comercial</th>
                <th>Genérico</th>
                <th>Laboratorio</th>
                <th>Presentación</th>
                <th>Stock</th>
                <th>Vencimiento</th>
                {{-- <th>Ubicación</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $p)
                <tr>
                    <td class="text-center">{{ $p->lote ?? 'N/A' }}</td>
                    <td>{{ $p->articulo ?? 'N/A' }}</td>
                    <td>{{ $p->nombre_generico ?? '—' }}</td>
                    <td>{{ $p->laboratorio ?? 'N/A' }}</td>
                    <td>{{ $p->presentacion ?? 'N/A' }}</td>
                    <td class="text-center">{{ $p->stock ?? 0 }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($p->fecha_vecimiento)->format('d/m/Y') }}</td>
                    {{-- <td>{{ $p->ubicacion ?? '—' }}</td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <table class="table">
        <tr>
            <td class="text-center bg-light" style="padding: 20px; font-style: italic;">
                No se encontraron productos con vencimiento en menos de 3 meses.
            </td>
        </tr>
    </table>
@endif

</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
    @php
        $color1 = "#001843"; /* Azul oscuro institucional */
        $color2 = "#E46C0A"; /* Naranja corporativo */
        $color3 = "#dedbb6";
        $color4 = "#a2d972";
        $color5 = "#000000";
        $color6 = "#5386a5"; /* Azul claro para totales */
    @endphp
    <style>
        @page {
            margin: 1cm;
            font-size: 12px;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
            background-color: #fff;
        }
        /* Encabezado */
        header {
            border-bottom: 2px solid {{ $color1 }};
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: middle;
        }
        .header-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: {{ $color1 }};
            text-transform: uppercase;
        }
        /* Tablas generales */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 6px;
            border: 1px solid #000;
            text-align: center;
        }
        th {
            background-color: {{ $color1 }};
            color: white;
            font-size: 11px;
        }
        td {
            font-size: 10px;
        }
        /* Totales */
        .totales-table {
            margin-bottom: 15px;
        }
        .totales-table th {
            background-color: {{ $color6 }};
            color: #fff;
            font-weight: bold;
            text-align: center;
            padding: 8px;
            border: 1px solid #000;
        }
        .totales-table td {
            text-align: center;
            border: 1px solid #000;
            padding: 6px;
        }
        /* Bloques de totales */
        .resumen-section {
            margin-top: 10px;
            margin-bottom: 15px;
        }
        /* Footer */
        footer {
            border-top: 2px solid {{ $color1 }};
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #555;
            padding-top: 5px;
        }
        .no-border {
            border: none !important;
        }
    </style>
</head>
<body>
    <header>
        <table class="header-table">
            <tr>
                <td style="width: 80px; text-align: center; padding: 10px;">
                    @if($foto_empresa)
                        <img src="{{ public_path('img/logo/' . $foto_empresa) }}" alt="Logo" style="height: 60px; width: auto; display: block; margin: 0 auto;">
                    @else
                        <div style="width:60px; height:60px; border:1px solid #ccc; margin: 0 auto; background-color: #f0f0f0;"></div>
                    @endif
                </td>
                <td class="header-title" style="text-align: center; padding: 10px;">
                    {{ strtoupper($title) }}
                </td>
                <td style="width: 150px; text-align: right; font-size: 10px; padding: 10px;">
                    <strong>Desde:</strong> {{ $fecha_inicio }}<br>
                    <strong>Hasta:</strong> {{ $fecha_fin }}
                </td>
            </tr>
        </table>
    </header>

    <!-- Totales generales -->
    <section class="resumen-section">
        <table class="totales-table">
            @foreach($totales as $det)
            <tr>
                <th style="background-color:{{ $color1 }};">Total Ventas: {{ number_format($det->totalV, 0) }}</th>
            </tr>
            <tr>
                <th style="background-color:{{ $color6 }};">Total Contado: Bs. {{ number_format($det->totalC, 2) }}</th>
                <th style="background-color:{{ $color6 }};">Total Crédito: Bs. {{ number_format($det->totalCr, 2) }}</th>
            </tr>
            <tr>
                <th style="background-color:#a0d2f3;">Total Efectivo: Bs. {{ number_format($det->totalEf, 2) }}</th>
                <th style="background-color:#a0d2f3;">Total Depósito: Bs. {{ number_format($det->totalDep, 2) }}</th>
            </tr>
            @endforeach
        </table>
    </section>

    <!-- Tabla principal -->
    <main>
        <table>
            <thead>
                <tr>
                    <th style="width:5%;">N°</th>
                    <th style="width:25%;">Cliente</th>
                    <th style="width:10%;">Tipo P.</th>
                    <th style="width:10%;">Forma P.</th>
                    <th style="width:10%;">Descuento</th>
                    <th style="width:15%;">Sub Total</th>
                    <th style="width:15%;">Total</th>
                    <th style="width:10%;">Usuario</th>
                </tr>
            </thead>
            <tbody>
                @php $numero = 1; @endphp
                @foreach($detalles as $det)
                    <tr>
                        <td>{{ $numero++ }}</td>
                        <td style="text-align: left;">{{ $det->cliente }}</td>
                        <td>{{ $det->tipo_pago }}</td>
                        <td>{{ $det->forma_pago }}</td>
                        <td>Bs. {{ number_format($det->descuento, 2) }}</td>
                        <td>Bs. {{ number_format($det->sub_total, 2) }}</td>
                        <td>Bs. {{ number_format($det->total, 2) }}</td>
                        <td>{{ $det->usuario }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; font-style: italic; padding: 20px; background-color: #f9f9f9;">
                            No se encontraron registros entre estas fechas.
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </main>

    <footer>
        <span>Reporte generado automáticamente — {{ now()->format('d/m/Y H:i') }}</span>
    </footer>
</body>
</html>
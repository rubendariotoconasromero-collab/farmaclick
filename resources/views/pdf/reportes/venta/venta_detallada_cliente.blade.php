@include('pdf.reportes.partials.system-theme')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Reporte de Ventas por Cliente' }}</title>
</head>
@php
    $colors = [
        'color1' => '#001843',
        'color2' => '#FF0107',
        'color3' => '#dedbb6',
        'color4' => '#a2d972',
        'color5' => '#000000',
        'color6' => '#E46C0A'
    ];
@endphp
<style>
    @page {
        margin: 0.7cm;
        font-family: Arial, sans-serif;
        font-size: 12px;
    }
    body {
        background: #FFFFFF;
        font-size: 12px;
        margin: 0;
        padding: 0;
        color: #000;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
    }
    .table th, .table td {
        padding: 0.5rem;
        vertical-align: top;
        border: 1px solid #dedede;
    }
    .no-border {
        border: none !important;
    }
    .text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }
    .text-left {
        text-align: left;
    }
    .venta-table th, .venta-table td {
        border: 1px solid #000;
    }
</style>

<body>
    <!-- Encabezado del reporte -->
    <table class="table-head" style="width: 100%; margin-bottom: 20px;">
        <tr>
            <!-- Logo de la empresa -->
            <th rowspan="3" style="width: 10%; text-align: center; vertical-align: middle; padding: 5px;" class="no-border">
                @if($logo_sistema)
                    <img src="{{ 'img/logo/' . $logo_sistema }}" height="60" alt="Company Logo">
                @else
                    <div style="width: 60px; height: 60px; background-color: #f0f0f0; border: 1px solid #ccc;"></div>
                @endif
            </th>
    
            <!-- Título del reporte -->
            <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 5px; padding-right:80px" class="no-border">
                <div style="font-size: 18px; font-weight: bold; color: {{ $colors['color1'] }};">
                    {{$title}}
                </div>

                <!-- Nombre del cliente -->
                <div style="font-size: 14px; color: #555555; margin-top: 8px;">
                    Cliente: {{ $nombre_cliente ?? 'N/A' }}
                </div>

                @if(!empty($fecha_inicio) & !empty($fecha_fin))
                    <!-- Rango de fechas -->
                    <div style="font-size: 12px; color: #555555; margin-top: 10px;">
                        DESDE: {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') }} HASTA: {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') }}
                    </div>
                @endif
            </th>

            <th style='width: 100px;'>
                            
            </th>
        </tr>
    </table>

    <!-- Resumen de totales -->
    <table class="table" style="width: 100%; margin-bottom: 20px;">
        <thead>
            <tr>
                <th style="background-color: {{ $colors['color1'] }}; color: #FFF; padding: 10px; text-align: left;">
                    TOTAL VENTAS: {{ number_format($totales->totalV ?? 0, 2) }} Bs.
                </th>
            </tr>
            <tr>
                <th style="background-color: {{ $colors['color2'] }}; color: #FFF; padding: 8px; text-align: left;">
                    TOTAL CONTADO: {{ number_format($totales->totalC ?? 0, 2) }} Bs.
                </th>
                <th style="background-color: {{ $colors['color2'] }}; color: #FFF; padding: 8px; text-align: left;">
                    TOTAL CRÉDITO: {{ number_format($totales->totalCr ?? 0, 2) }} Bs.
                </th>
            </tr>
            <tr>
                <th style="background-color: #a0d2f3; padding: 8px; text-align: left;">
                    TOTAL EFECTIVO: {{ number_format($totales->totalEf ?? 0, 2) }} Bs.
                </th>
                <th style="background-color: #a0d2f3; padding: 8px; text-align: left;">
                    TOTAL DEPÓSITO: {{ number_format($totales->totalDep ?? 0, 2) }} Bs.
                </th>
            </tr>
        </thead>
    </table>

    <!-- Listado de ventas -->
    @if(isset($ventas) && $ventas->count() > 0)
        @foreach($ventas as $venta)
            @php
                $detallesVenta = $detalles->get($venta->id, collect());
                $paquetesVenta = $detallesPaquete->get($venta->id, collect());
                $tieneDetalles = $detallesVenta->isNotEmpty() || $paquetesVenta->isNotEmpty();
            @endphp

            <table class="table venta-table" style="margin-bottom: 15px; page-break-inside: avoid;">
                <thead>
                    <tr>
                        <th style="background-color: {{ $colors['color1'] }}; color: #FFF; font-size: 10px; width: 25%;">
                            Cliente: {{ $venta->cliente ?? 'N/A' }}
                        </th>
                        <th style="background-color: {{ $colors['color1'] }}; color: #FFF; font-size: 10px; width: 15%;">
                            Tipo P.: {{ $venta->tipo_pago ?? 'N/A' }}
                        </th>
                        <th style="background-color: {{ $colors['color1'] }}; color: #FFF; font-size: 10px; width: 15%;">
                            Forma P.: {{ $venta->forma_pago ?? 'N/A' }}
                        </th>
                        <th style="background-color: {{ $colors['color1'] }}; color: #FFF; font-size: 10px; width: 15%;">
                            Desc.: {{ number_format($venta->descuento ?? 0, 2) }}
                        </th>
                        <th style="background-color: {{ $colors['color1'] }}; color: #FFF; font-size: 10px; width: 15%;">
                            Total: 
                            @php
                                $totalMostrar = $venta->forma_pago == 'Efectivo'
                                    ? ($venta->total_efectivo ?? 0)
                                    : ($venta->total_deposito ?? $venta->total ?? 0);
                            @endphp
                            {{ number_format($totalMostrar, 2) }} Bs.
                        </th>
                    </tr>
                    <tr>
                        <th style="background-color: {{ $colors['color2'] }}; color: #FFF; font-size: 10px;">Producto / Paquete</th>
                        <th style="background-color: {{ $colors['color2'] }}; color: #FFF; font-size: 10px; text-align: center;">Cantidad</th>
                        <th style="background-color: {{ $colors['color2'] }}; color: #FFF; font-size: 10px; text-align: right;">P.U.</th>
                        <th style="background-color: {{ $colors['color2'] }}; color: #FFF; font-size: 10px; text-align: right;">Sub Total</th>
                        <th style="background-color: {{ $colors['color2'] }}; color: #FFF; font-size: 10px; text-align: center;">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ventaSubTotal = 0;
                    @endphp

                    <!-- Productos -->
                    @if($detallesVenta->isNotEmpty())
                        @foreach($detallesVenta as $det)
                            @php
                                $ventaSubTotal += $det->sub_total ?? 0;
                            @endphp
                            <tr>
                                <td style="font-size: 10px; word-break: break-word;">{{ $det->producto ?? 'Producto no disponible' }}</td>
                                <td style="font-size: 10px; text-align: center;">{{ $det->cantidad ?? 0 }}</td>
                                <td style="font-size: 10px; text-align: right;">{{ number_format($det->costo_venta ?? 0, 2) }}</td>
                                <td style="font-size: 10px; text-align: right;">{{ number_format($det->sub_total ?? 0, 2) }}</td>
                                <td style="font-size: 10px; text-align: center;">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    @endif

                    <!-- Paquetes -->
                    @if($paquetesVenta->isNotEmpty())
                        @foreach($paquetesVenta as $det)
                            @php
                                $ventaSubTotal += $det->sub_total ?? 0;
                            @endphp
                            <tr>
                                <td style="font-size: 10px; word-break: break-word;">{{ $det->producto ?? 'Paquete no disponible' }}</td>
                                <td style="font-size: 10px; text-align: center;">{{ $det->cantidad ?? 0 }}</td>
                                <td style="font-size: 10px; text-align: right;">{{ number_format($det->costo_venta ?? 0, 2) }}</td>
                                <td style="font-size: 10px; text-align: right;">{{ number_format($det->sub_total ?? 0, 2) }}</td>
                                <td style="font-size: 10px; text-align: center;">{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    @endif

                    @if(!$tieneDetalles)
                        <tr>
                            <td colspan="5" style="text-align: center; font-style: italic; color: #666; padding: 15px;">
                                No se encontraron detalles para esta venta
                            </td>
                        </tr>
                    @else
                        <tr style="background-color: #f8f8f8; border-top: 2px solid {{ $colors['color1'] }};">
                            <td colspan="3" style="text-align: right; font-weight: bold; font-size: 10px; padding: 8px;">
                                TOTAL:
                            </td>
                            <td style="text-align: right; font-weight: bold; font-size: 10px; padding: 8px;">
                                {{ number_format($ventaSubTotal, 2) }} Bs.
                            </td>
                            <td></td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach
    @else
        <table class="table">
            <thead>
                <tr>
                    <th style="text-align: center; padding: 25px; background-color: #f8f8f8; color: #666; font-weight: normal;">
                        No se encontraron ventas para {{ $nombre_cliente ?? 'el cliente' }} entre las fechas {{ \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') ?? 'N/A' }} y {{ \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y') ?? 'N/A' }}
                    </th>
                </tr>
            </thead>
        </table>
    @endif
</body>
</html>

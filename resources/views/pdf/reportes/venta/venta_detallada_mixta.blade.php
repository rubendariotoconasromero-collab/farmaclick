<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Reporte de Ventas Mixtas' }}</title>
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
                <div style="font-size: 18px; font-weight: bold; color: #001843;">
                    {{$title}}
                </div>

                @if(isset($fecha_inicio_arqueo) || isset($fecha_fin_arqueo))
                    <div style="font-size: 12px; color: #555555; margin-top: 8px;">
                        @if($fecha_inicio_arqueo)
                            Apertura: {{ $fecha_inicio_arqueo }}
                        @endif
                        @if($fecha_fin_arqueo)
                            &nbsp;|&nbsp; Cierre: {{ $fecha_fin_arqueo }}
                        @endif
                    </div>
                @endif

                @if(isset($usuario_arqueo))
                    <div style="font-size: 11px; color: #777; margin-top: 6px;">
                        Caja gestionada por: {{ $usuario_arqueo }}
                    </div>
                @endif

                
                @if(isset($usuarioActual))
                <div style="font-size: 14px; color: #333333;">
                    Usuario: <strong>{{ $usuarioActual }}</strong>
                </div>
                @endif
                @if(!empty($fecha_inicio) && !empty($fecha_fin))
                    <!-- Rango de fechas -->
                    <div style="font-size: 12px; color: #555555; margin-top: 10px;">
                        DESDE: {{ $fecha_inicio ?? 'N/A' }} HASTA: {{ $fecha_fin ?? 'N/A' }}
                    </div>
                @endif

            </th>

            <th style='width: 80px; text-align: center; vertical-align: middle; padding: 10px;'>             
            </th>
        </tr>
    </table>

    <!-- Total general -->
    <table class="table" style="width: 100%; margin-bottom: 20px;">
        <thead>
            <tr>
                <th style="background-color: {{ $colors['color1'] }}; color: #FFF; padding: 10px; text-align: left;">
                    TOTAL VENTAS: {{ number_format($totalVentas ?? 0, 2) }} Bs.
                </th>
            </tr>
        </thead>
    </table>

    <!-- Listado de ventas -->
    @if(isset($venta) && $venta->count() > 0)
        @foreach($venta as $comp)
            <table class="table" style="margin-bottom: 15px; page-break-inside: avoid;">
                <thead>
                    <tr>
                        <th style="background-color: {{ $colors['color1'] }}; color: #FFF; font-size: 10px; width: 20%;">
                            Cliente: {{ $comp->cliente ?? 'N/A' }}
                        </th>
                        <th style="background-color: {{ $colors['color1'] }}; color: #FFF; font-size: 10px; width: 12%;">
                            Desc.: {{ number_format($comp->descuento ?? 0, 2) }}
                        </th>
                        <th style="background-color: {{ $colors['color1'] }}; color: #FFF; font-size: 10px; width: 12%;">
                            Total: {{ number_format($comp->total ?? 0, 2) }} Bs.
                        </th>
                        <th style="background-color: {{ $colors['color1'] }}; color: #FFF; font-size: 10px; width: 12%;">
                            Efectivo: {{ number_format($comp->total_efectivo ?? 0, 2) }} Bs.
                        </th>
                        <th style="background-color: {{ $colors['color1'] }}; color: #FFF; font-size: 10px; width: 12%;">
                            Depósito: {{ number_format($comp->total_deposito ?? 0, 2) }} Bs.
                        </th>
                        <th style="background-color: {{ $colors['color1'] }}; color: #FFF; font-size: 10px; width: 12%;">
                            Fecha: {{ \Carbon\Carbon::parse($comp->fecha)->format('d/m/Y') }}
                        </th>
                    </tr>
                    <tr>
                        <th style="background-color: {{ $colors['color2'] }}; color: #FFF; font-size: 10px;">Producto / Paquete</th>
                        <th style="background-color: {{ $colors['color2'] }}; color: #FFF; font-size: 10px; text-align: center;">Cantidad</th>
                        <th style="background-color: {{ $colors['color2'] }}; color: #FFF; font-size: 10px; text-align: right;">P.U.</th>
                        <th style="background-color: {{ $colors['color2'] }}; color: #FFF; font-size: 10px; text-align: right;">Sub Total</th>
                        <th style="background-color: {{ $colors['color2'] }}; color: #FFF; font-size: 10px; text-align: center;" colspan="2">Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $hasDetails = false;
                        $ventaSubTotal = 0;
                    @endphp

                    <!-- Productos -->
                    @if(isset($detalles) && $detalles->isNotEmpty())
                        @foreach($detalles as $det)
                            @if($comp->id == $det->id_venta)
                                @php
                                    $hasDetails = true;
                                    $ventaSubTotal += $det->sub_total ?? 0;
                                @endphp
                                <tr>
                                    <td style="font-size: 10px;">{{ $det->producto ?? 'Producto no disponible' }}</td>
                                    <td style="font-size: 10px; text-align: center;">{{ $det->cantidad ?? 0 }}</td>
                                    <td style="font-size: 10px; text-align: right;">{{ number_format($det->costo_venta ?? 0, 2) }}</td>
                                    <td style="font-size: 10px; text-align: right;">{{ number_format($det->sub_total ?? 0, 2) }}</td>
                                    <td style="font-size: 10px; text-align: center;" colspan="2">Producto</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif

                    <!-- Paquetes -->
                    @if(isset($detallesPaquete) && $detallesPaquete->isNotEmpty())
                        @foreach($detallesPaquete as $det)
                            @if($comp->id == $det->id_venta)
                                @php
                                    $hasDetails = true;
                                    $ventaSubTotal += $det->sub_total ?? 0;
                                @endphp
                                <tr>
                                    <td style="font-size: 10px;">{{ $det->producto ?? 'Paquete no disponible' }}</td>
                                    <td style="font-size: 10px; text-align: center;">{{ $det->cantidad ?? 0 }}</td>
                                    <td style="font-size: 10px; text-align: right;">{{ number_format($det->costo_venta ?? 0, 2) }}</td>
                                    <td style="font-size: 10px; text-align: right;">{{ number_format($det->sub_total ?? 0, 2) }}</td>
                                    <td style="font-size: 10px; text-align: center;" colspan="2">Paquete</td>
                                </tr>
                            @endif
                        @endforeach
                    @endif

                    @if(!$hasDetails)
                        <tr>
                            <td colspan="6" style="text-align: center; font-style: italic; color: #666; padding: 15px;">
                                No se encontraron detalles para esta venta
                            </td>
                        </tr>
                    @else
                        <tr style="background-color: #f8f8f8; border-top: 2px solid {{ $colors['color1'] }};">
                            <td colspan="3" style="text-align: right; font-weight: bold; font-size: 10px; padding: 8px;">
                                TOTAL DETALLES:
                            </td>
                            <td style="text-align: right; font-weight: bold; font-size: 10px; padding: 8px;">
                                {{ number_format($ventaSubTotal, 2) }} Bs.
                            </td>
                            <td colspan="2"></td>
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
                        No se encontraron registros entre las fechas {{ $fecha_inicio ?? 'N/A' }} y {{ $fecha_fin ?? 'N/A' }}
                    </th>
                </tr>
            </thead>
        </table>
    @endif
</body>
</html>
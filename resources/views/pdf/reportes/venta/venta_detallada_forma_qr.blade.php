<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
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
        font-size: 10px;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th, .table td {
        padding: 0.5rem;
        vertical-align: top;
    }
    .table-head th, .table-body td {
        text-align: left;
    }
    .table-footer {
        border-top: 1px solid {{ $colors['color1'] }};
        font-size: 10px;
    }
    .footer-inferior {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 1.5cm;
    }
</style>

<body>
    <table class="table-head" style="width: 100%; margin-bottom: 20px;">
        <tr>
            <!-- Logo de la empresa -->
            <th rowspan="3" style="width: 10%; text-align: center; vertical-align: middle; padding: 5px;">
                <img src="{{ $foto_empresa ? 'img/logo/' . $foto_empresa : '' }}" height="60" alt="Company Logo">
            </th>
    
            <!-- Título del reporte -->
            <th rowspan="3" style="text-align: center; vertical-align: middle; padding: 5px; padding-right:80px">
                <div style="font-size: 18px; font-weight: bold; color: #001843;">
                    LISTADO DE VENTAS QR DETALLADO
                </div>
                <!-- Nombre del usuario actual -->
                <div style="font-size: 14px; color: #333333;">
                    Usuario: <strong>{{ $usuarioActual }}</strong>
                </div>
                <!-- Rango de fechas -->
                <div style="font-size: 12px; color: #555555;">
                    DESDE: {{ $fecha_inicio }} HASTA: {{ $fecha_fin }}
                </div>
            </th>
        </tr>
    </table>
    
    

    <!-- Totales de la venta -->
    <table class="table" style="width: 100%; margin-bottom: 20px; border-collapse: collapse;">
        <thead>
            <tr>
                <th colspan="2" style="background-color: {{ $colors['color1'] }}; color: #FFF; padding: 10px; text-align: left; border: 1px solid #dedede;">
                    Total Ventas: {{ number_format($totalVentasDeposito, 2)}}
                </th>
            </tr>
            {{-- <tr>
                <th style="background-color: #5386a5; color: #FFF; padding: 10px; text-align: left; border: 1px solid #dedede;">
                    Total Venta Contado: {{ number_format($detalles5[0]['totalC'], 2) }}
                </th>
                <th style="background-color: #5386a5; color: #FFF; padding: 10px; text-align: left; border: 1px solid #dedede;">
                    Total Venta Crédito: {{ number_format($detalles6[0]['totalCr'], 2) }}
                </th>
            </tr>
            <tr>
                <th colspan="2" style="background-color: #a0d2f3; padding: 10px; text-align: left; border: 1px solid #dedede;">
                    Total Efectivo: {{ number_format($detalles7[0]['totalEf'], 2) }}
                </th>
            </tr>
            <tr>
                <th colspan="2" style="background-color: #a0d2f3; padding: 10px; text-align: left; border: 1px solid #dedede;">
                    Total Depósito: {{ number_format($detalles8[0]['totalDep'], 2) }}
                </th>
            </tr> --}}
        </thead>
    </table>
    

    <!-- Tabla con las ventas detalladas -->
    @foreach($venta as $comp)
        <table class="table" style="margin-bottom:10px;">
            <thead style="border: 1px solid black;">
                <tr>
                    <th style="background-color: {{ $colors['color1'] }}; color: #FFF;">Cliente: {{ $comp->cliente }}</th>
                    <th style="background-color: {{ $colors['color1'] }}; color: #FFF;">Tipo P.: {{ $comp->tipo_pago }}</th>
                    <th style="background-color: {{ $colors['color1'] }}; color: #FFF;">Forma P.: {{ $comp->forma_pago }}</th>
                    <th style="background-color: {{ $colors['color1'] }}; color: #FFF;">Desc.: {{ $comp->descuento }}</th>
                    <th style="background-color: {{ $colors['color1'] }}; color: #FFF;">
                        Total: 
                        @if($comp->forma_pago == 'Efectivo') {{ $comp->total_efectivo }}
                        @elseif(in_array($comp->forma_pago, ['Transferencia', 'Pago por QR', 'Depósito'])) {{ $comp->total_deposito }}
                        @else {{ $comp->total }}
                        @endif
                    </th>
                    <th style="background-color: {{ $colors['color2'] }}; color: #FFF;">{{__('Cantidad')}}</th>
                    <th style="background-color: {{ $colors['color2'] }}; color: #FFF;">{{__('PU')}}</th>
                    <th style="background-color: {{ $colors['color2'] }}; color: #FFF;"><strong>{{__('Sub Total:')}}</strong> {{$comp->sub_total}}</th>
                </tr>
            </thead>
            <tbody style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">
                @foreach($detalles as $det)
                    @if($comp->id == $det->id_venta)
                        <tr style="border-top: 1px solid black;">
                            <td colspan="5">{{ $det->producto }}</td>
                            <td>{{ $det->cantidad }}</td>
                            <td>{{ $det->costo_venta }}</td>
                            <td style="text-align:right">{{ $det->sub_total }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endforeach

    <!-- Mostrar si no hay ventas en el periodo -->
    @if($venta->isEmpty())
        <table class="table">
            <thead style="border: 1px solid black;">
                <tr>
                    <th colspan="6" style="text-align: left; border-right: 1px solid black;">No se encuentran registros entre estas fechas</th>
                </tr>
            </thead>
        </table>
    @endif

</body>
</html>

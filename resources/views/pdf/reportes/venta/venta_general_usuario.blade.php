<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
</head>
@php
    $color1 = "#001843";
    $color2 = "#E46C0A";
    $color3 = "#dedbb6";
    $color4 = "#a2d972";
    $color5 = "#000000";
    $color6 = "#E46C0A";
@endphp
<style>
    @page {
        margin-top: 1cm;
        margin-bottom: 1cm;
        margin-left: 1.5cm;
        margin-right: 1.5cm;
        font-size: 12px;
        font-family: 'Times New Roman', Times, serif;
    }
    body {
        position: relative;
        color: black;
        background: #FFFFFF;
        font-family: Arial, sans-serif;
        font-size: 10px;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table th, .table td {
        padding: 0.3rem;
        vertical-align: top;
        border: 1px solid #ddd;
    }
    .table th {
        background-color: {{ $color1 }};
        color: white;
    }
    .no-records {
        text-align: center;
        padding: 1rem;
        font-style: italic;
    }
</style>
<body>
    @php
        $no_encontrada = "https://i.ibb.co/DVT0mnx/noencontrada.png";
    @endphp

    <!-- Encabezado del reporte -->
    <table class="table-head" style="width: 100%; margin-bottom: 10px;">
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
                <small style='text-align:center; font-size: 14px;'>DESDE: {{$fecha_inicio}} HASTA: {{$fecha_fin}}</small>
                <br>
                <small style='text-align:center; font-size: 14px;text-transform:uppercase'>{{$usuarioActual}}</small>
            </th>
            <th style='width: 120px;'>
                            
            </th>
        </tr>
    </table>
    
    <!-- Resumen de Totales -->
    <table class="table" style="margin-bottom: 15px;">
        <tr>
            <th style="width:50%; text-align: left; font-size: 12px; background-color: {{ $color1 }};">
                Total Venta: {{ number_format($totalV, 2) }}
            </th>
            <td style="width:50%; font-size: 12px; background-color: #ffffff; text-align: left;">
                
            </td>
        </tr>
        <tr>
            <td style="width:50%; font-size: 12px; background-color: #5386a5; color: white; text-align: left;">
                Total Venta Contado: {{ number_format($totalC, 2) }}
            </td>
            <td style="width:50%; font-size: 12px; background-color: #5386a5; color: white; text-align: left;">
                Total Venta Crédito: {{ number_format($totalCr, 2) }}
            </td>
            
        </tr>
       
        <tr>
            <td style="width:50%; font-size: 12px; background-color: #a0d2f3; text-align: left;">
                Total Efectivo: {{ number_format($totalEf, 2) }}
            </td>
            <td style="width:50%; font-size: 12px; background-color: #a0d2f3; text-align: left;">
                Total Depósito: {{ number_format($totalDep, 2) }}
            </td>
          
        </tr>
    </table>

    <!-- Tabla de Detalles -->
    <table class="table">
        <thead>
            <tr>
                <th width="5%">Nro</th>
                <th width="35%">Cliente</th>
                <th width="15%">Tipo P.</th>
                <th width="15%">Forma P.</th>
                <th width="10%">Descuento</th>
                <th width="10%">Sub Total</th>
                <th width="10%">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $numero = 1;
            @endphp
            @forelse($detalles as $det)
            <tr>
                <td style="text-align: center;">{{ $numero++ }}</td>
                <td>{{ $det->cliente }}</td>
                <td>{{ $det->tipo_pago }}</td>
                <td>{{ $det->forma_pago }}</td>
                <td style="text-align: right;">{{ number_format($det->descuento, 2) }}</td>
                <td style="text-align: right;">{{ number_format($det->sub_total, 2) }}</td>
                <td style="text-align: right;">{{ number_format($det->total, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="no-records">
                    No se encuentran registros entre estas fechas
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
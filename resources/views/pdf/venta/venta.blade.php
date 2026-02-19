<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    <title>Venta</title>
</head>
@php
    $color1 = "#5797b5";
    $color2 = "#e1f1b8";
    $color3 = "#dedbb6";
    $color4 = "#a2d972";
@endphp
<style>
    @page {
        margin: 0.7cm 0.7cm 0.7cm 0.7cm;
        font-size: 12px;
        font-family: Arial;
    }
    body {
        position: relative;
        color: #555555;
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
        padding: 0.5rem;
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
    /* .table tbody tr:nth-child(even) {
        background: #eff2d5;
    } */
    .table-head {
        width: 100%;
        max-width: 100%;
        border-collapse: separate;
        border-spacing: 5px;
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
        border-top: 1px solid <?php echo $color1; ?>;
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

</style>
<header>
    <div style="font-size: 26px; text-align: center; padding-bottom: 10px" >            
        <strong>Registro Venta</strong>
    </div>
    <table class="table-head table-borderless" style="padding-bottom: 7px" width="100%">
        <tr style="">
            <th width="50%" style="padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: left; border: 2px solid <?php echo $color1; ?>; border-radius: 5px, 5px">
                <div style="font-weight: normal" >
                    <strong style="color: <?php echo $color1; ?>"> {{__('Cliente')}}: </strong> {{ $cliente }}
                </div>
            </th>
            <th width="25%" style="padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: left; border: 2px solid <?php echo $color1; ?>; border-radius: 5px, 5px"">
                <div style="font-weight: normal" >
                    <strong style="color: <?php echo $color1; ?>"> {{__('Fecha')}}: </strong> {{ $fecha }}
                </div>
            </th>
            <th colspan="3" width="20%" style="padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: left">
            </th>
            <th rowspan="4" width="20%" style="vertical-align: middle">
                <div>
                    <img src=<?php echo $foto != null ? "img/mi_empresa/".$foto : '""' ?> height="100">
                </div>
            </th>
        </tr>
        <tr style="">
            <th colspan="5" width="30%" style="padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: left; border: 2px solid <?php echo $color1; ?>; border-radius: 5px, 5px">
                <div style="font-weight: normal" >
                    <strong style="color: <?php echo $color1; ?>">{{__('Tipo de Pago')}}: </strong> {{ $tipo_pago }}
                </div>
            </th>
        </tr>
        <tr style="">
            <th colspan="5" width="40%" style="padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: left; border: 2px solid <?php echo $color1; ?>; border-radius: 5px, 5px">
                <div style="font-weight: normal" >
                    <strong style="color: <?php echo $color1; ?>"> {{__('Forma de Pago')}}: </strong> {{ $forma_pago }}
                </div>
            </th>
        </tr>
        <tr style="">
            <th colspan="5" width="40%" style="padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: left; border: 2px solid <?php echo $color1; ?>; border-radius: 5px, 5px">
                <div style="font-weight: normal" >
                    <strong style="color: <?php echo $color1; ?>"> {{__('Pago')}}: </strong> {{ $descuento_pago }}
                </div>
            </th>
        </tr>
    </table>
</header>
<body>
    @php
        $numero = 1;
        $totalCaja = 0;
        $totalBanco = 0;
        $totalTarjeta = 0;
        $origenVM = "";
        $color5 = "#eff2d5";
        $color6 = "white";
        $color7 = "";
        $saldo_anterior = 0;
        $pago = 0;
        $saldo_por_pagar= 0;
    @endphp
    @php
        $saldo_por_pagar = $total+$saldo_anterior-$pago
    @endphp
    <div class="container">
        <div class="col-lg-7 col-md-7">
            <table class="table" style="">
                <thead style="border: 2px solid black; background-color:<?php echo $color2; ?>">
                    <tr> 
                        <th style="vertical-align: middle; border-right: 2px solid black" width="5%" style="">{{__('Numero')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="20%" style="">{{__('Nombre')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="10%" style="">{{__('Tienda')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="7%" style="">{{__('Precio')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="7%" style="">{{__('Cantidad')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="7%" style="">{{__('Sub Total')}}</th>
                    </tr>
                </thead>
                <tbody style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">
                    @foreach($detalles as $det)
                    <tr>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $numero }}</td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['articulo'] }}</td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['tienda'] }}</td>
                        @if($id_descuento == 1)
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['costo_unitario']}}</td>
                        @endif
                        @if($id_descuento == 2)
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['costo_mayorista']}}</td>
                        @endif
                        @if($id_descuento == 3)
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['costo_preferencial']}}</td>
                        @endif
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['cantidad']}}</td>
                        <td style="text-align: left; border-right: 1px solid black; text-align: center; background-color:<?php echo $color6; ?>">{{ $det['sub_total'] }}</td>
                    </tr>
                    @php
                        $color7 = $color5;
                        $color5 = $origenVM == $det['origen']  ? $color5 : $color6;
                        $color6 = $origenVM == $det['origen']  ? $color6 : $color7;
                        $totalCaja += $origenVM == $det['origen'] ? 0 : $det['caja'];
                        $totalBanco += $origenVM == $det['origen'] ? 0 : $det['banco'];
                        $totalTarjeta += $origenVM == $det['origen'] ? 0 : $det['tarjeta'];
                        $origenVM = $det['origen'];
                        $numero++;
                    @endphp
                    @endforeach
                </tbody>
                <tfoot style="">
                    <tr>
                        <td colspan="5" height="1px" style="font-weight: bold; text-align: right">Sub Total:</td>
                        <td height="10px" style="font-weight: bold; text-align: center; border: 2px solid black; background-color:<?php echo $color4; ?>">{{ number_format($sub_total,2)  }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" height="1px" style="font-weight: bold; text-align: right">Descuento:</td>
                        <td height="10px" style="font-weight: bold; text-align: center; border: 2px solid black; background-color:<?php echo $color4; ?>">{{ number_format($descuento,2)  }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" height="1px" style="font-weight: bold; text-align: right">Total:</td>
                        <td height="10px" style="font-weight: bold; text-align: center; border: 2px solid black; background-color:<?php echo $color4; ?>">{{ number_format($total,2)  }}</td>
                    </tr>
                    
                </tfoot>
            </table>
        </div>
    </div>
</body>
</html>

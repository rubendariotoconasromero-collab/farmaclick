<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    <title>Proformas</title>
</head>
@php
    $color1 = "#FCD5B4";
    $color2 = "#E46C0A";
    $color3 = "#dedbb6";
    $color4 = "#a2d972";
    $color5 = "#000000";
    $color6 = "#E46C0A";
@endphp
<style>
    @page {
        margin: 0.7cm 0.7cm 0.3cm 0.7cm;
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
    .A{
        float: left;
        width: 20%; 
        height: 100px; 
        text-align:center;
    }
    .AA{
        float: left;
        text-align:center;
    }
    .BB{
        float: left;
        text-align:center;
    }
    .CC{
        float: left;
        text-align:center;
    }
    .DD{
        float: left;
        text-align:center;
    }
    .container{
        height: 100px; 
    }
     #lateral { 
        width: 80px; 
    }
    #lateral { 
        height: 100px; 
    } 
    .page_break {
        page-break-before: always;
        @php $tamaño=0 @endphp
    }
    .mostrar {
        display: block;
    }
    .nomostrar {
        display: none;
    }
    .colocar_pie {
        page-break-before: always;

    }

    footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: .8cm;
            }

</style>
<header>
    <table class="table-head table-borderless" style="padding-bottom: 7px" width="100%">
        <tr style="">
            <th rowspan="1"  width="30%">
                                <!-- <img src=<?php echo $foto_empresa != null ? "img/mi_empresa/".$foto_empresa : '""' ?> height="100" width="200"> -->
                                <img src=<?php echo $foto_empresa != null ? "../public_html/img/mi_empresa/".$foto_empresa : '""' ?> height="100" width="200">
            </th>
            <th width="40%" style="padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: center; border: 2px solid <?php echo $color1; ?>; border-radius: 5px, 5px">
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: black" SIZE=3>{{__('Web Site: ')}}</FONT>
                <u><FONT FACE="times new roman" style="color: blue" SIZE=3>{{ $sitio_web_empresa }}</FONT></u>
                </div>    
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('NIT: ')}}</FONT>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ $nit_empresa }}</FONT>
                </div>
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('TEL: ')}}</FONT>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ $telefono_empresa }}</FONT>
                </div>
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ $direccion_empresa }}</FONT>
                </div>
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: black" SIZE=3>{{__('Correo: ')}}</FONT>
                <u><FONT FACE="times new roman" style="color:blue" SIZE=3>{{ $correo_empresa }}</FONT></u>
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
        $defecto = "defecto.png";
        $color6 = "white";
        $color7 = "";
        $saldo_anterior = 0;
        $pago = 0;
        $saldo_por_pagar= 0;
        $tamaño = 1;
        $tamaño2 = 1;
        $x1 = 0;
        $xx=0;
        $x5=6;
        
    @endphp

    <div class="container">
        <div class="col-lg-7 col-md-7" style=" color: black">
        <table width="100%">
            <tr width="100%">
                <td width="100%" style="text-align: center" colspan="6">
                    <strong><u><FONT FACE="Cambria" style="color: black" SIZE=5>LISTA DE PROFORMAS</FONT></u></strong>
                </td>
            </tr>
            <tr width="100%" >
                <td width="25%" style="text-align: center"></td>
                <td width="10%" style="text-align: center"><h2>{{__('Desde')}}</h2></td>
                <td width="15%" style="text-align: left"><h2>{{ $fecha_inicio }}</h2></td>
                <td width="10%" style="text-align: center"><h2>{{__('Hasta')}}</h2></td>
                <td width="15%" style="text-align: left"><h2>{{ $fecha_fin }}</h2></td>
                <td width="25%" style="text-align: center"></td>
            </tr>
        </table>
            <table class="table" style="">
                <thead style="border: 1px solid black; background-color:<?php echo $color1; ?>">
                    <tr> 
                        <th style="vertical-align: middle; border-right: 2px solid black" width="5%" style="">{{__('Código')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="10%" style="">{{__('Fecha')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="15%" style="">{{__('Cliente')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="10%" style="">{{__('Usuario')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="10%" style="">{{__('Fecha Vencimiento')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="5%" style="">{{__('Días Crédito')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="15%" style="">{{__('Lugar Entrega')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="10%" style="">{{__('Sub Total')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="10%" style="">{{__('Descuento')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="10%" style="">{{__('Total')}}</th>
                    </tr>
                </thead>
                
                @foreach($detalles as $det)
                    
                    
                <tbody  style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">
                    
                    <tr style="border-top: 1px solid black">
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['codigo_proforma'] }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['fecha'] }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['cliente']}}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['usuario']}}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['fecha_venci']}}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['dias_credito']}}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['lugar_entrega']}}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['sub_total']}}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['descuento']}}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['total']}}</td>
                    </tr>
                    
                    @endforeach
                </tbody>
            </table>
                
            </div>
        </div>

    </body>
        <footer>
            <!-- <img style="margin: 0px 10px" src="img/mi_empresa/k1.jpg" width="17%" height="100%"/>
            <img style="margin: 0px 10px" src="img/mi_empresa/k2.jpg" width="17%" height="100%"/>
            <img style="margin: 0px 10px" src="img/mi_empresa/k3.jpg" width="17%" height="100%"/>
            <img style="margin: 0px 10px" src="img/mi_empresa/k4.jpg" width="17%" height="100%"/>
            <img src="img/mi_empresa/k5.jpg" width="17%" height="100%"/> -->
            <img style="margin: 0px 10px" src="../public_html/img/mi_empresa/k1.jpg" width="17%" height="100%"/>
            <img style="margin: 0px 10px" src="../public_html/img/mi_empresa/k2.jpg" width="17%" height="100%"/>
            <img style="margin: 0px 10px" src="../public_html/img/mi_empresa/k3.jpg" width="17%" height="100%"/>
            <img style="margin: 0px 10px" src="../public_html/img/mi_empresa/k4.jpg" width="17%" height="100%"/>
            <img src="../public_html/img/mi_empresa/k5.jpg" width="17%" height="100%"/>
        </footer>

</html>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    <title>{{$title}}</title>
</head>
@php
    $color1 = "#FDD7AF";
    $color2 = "#DEE4EF";
    $color3 = "#dedbb6";
    $color4 = "#a2d972";
    $color5 = "#000000";
    $color6 = "#E46C0A";
@endphp
<style>
    @page {
        margin: 0.7cm 0.7cm 0.7cm 0.7cm;
        font-size: 12px;
        font-family: Arial;
    }
    body {
        position: relative;
        color: black;
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
    .table-head {
        width: 100%;
        max-width: 100%;
        border-collapse: collapse;
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
    .EE{
        float: left;
        text-align:center;
    }
    
    .container{
        height: 100px; 
    }
    .container2{
        height: 40px; 
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
        height: 1.5cm;
    }

</style>
    <table class="table-head table-borderless;" style="padding-bottom: 7px;" width="100%">
        <tr style="">
            <th rowspan="7" colspan="2" width="30%" style="vertical-align:middle;">
                    <img src=<?php echo ( !is_array(@getimagesize("../public_html/img/sitnorte-centralcell.png")) ? "../public_html/img/sitnorte-centralcell.png" : str_replace(" ","%20","../public_html/img/sitnorte-centralcell.png")) ?> height="100">
                    {{-- <img src=<?php //echo ( !is_array(@getimagesize("img/sitnorte-centralcell.png")) ? "img/sitnorte-centralcell.png" : str_replace(" ","%20","img/sitnorte-centralcell.png")) ?> height="100"> --}}
            </th>
            <th rowspan="7" width="40%" style="padding-left: 10px; text-align: center; vertical-align: middle;">
                <div style="font-weight: normal" >
                <strong ><FONT FACE="times new roman" style="color: black" SIZE=4>{{$title}}</FONT></strong >
                </div>
                <div style="font-weight: normal; padding-top: 10px" >
                <strong ><FONT FACE="times new roman" style="color: black" SIZE=4>{{$tipo_venta}}</FONT></strong >
                </div>
            </th>
            <th width="30%" colspan="2" style="text-align:center; vertical-align: middle; text-transform: uppercase;">
            </th>
        </tr>
        <tr>
            <th width="35%" colspan="2" style="text-align:center; vertical-align: middle; text-transform: uppercase; border-top: 1px solid orange; border-left: 1px solid orange; border-right: 1px solid orange">
                <div style="">
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ $nombre_empresa }}</FONT>
                </div>
            </th>
        </tr>
        <tr style="">
            <th width="18%" style="text-align:right; vertical-align: middle; text-transform: uppercase; border-left: 1px solid orange">
                <div style="font-weight: normal" >
                <strong ><FONT  FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=2>{{__('Cod Tienda: ')}}</FONT></strong>
                </div>
            </th>
            <th width="17%" style="text-align:left; vertical-align: middle;; border-right: 1px solid orange">  
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=2>{{ $cod_empresa }}</FONT>
                </div>
            </th>
        </tr>
        <tr style="">
            <th style="text-align:right; vertical-align: middle; text-transform: uppercase; border-left: 1px solid orange">
                <div style="font-weight: normal" >
                <strong><FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=2>{{__('Cod. Almacén: ')}}</FONT></strong>
                </div>
            </th>
            <th style="text-align:left; vertical-align: middle;; border-right: 1px solid orange">  
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=2>{{ $cod_almacen }}</FONT>
                </div>
            </th>
        </tr>
        <tr style="">
            <th colspan="2" style="text-align:center; vertical-align: middle; border-left: 1px solid orange; border-right: 1px solid orange">  
                <div style="" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=2>{{__('DIRECCIÓN ')}}</FONT>
                </div>
            </th>
        </tr>
        <tr style="">
            <th colspan="2" style="text-align:center; vertical-align: middle; border-left: 1px solid orange; border-right: 1px solid orange; border-bottom: 1px solid orange">  
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=2>{{ $direccion_empresa }}</FONT>
                </div>
            </th>
        </tr>
        <tr style="">
            <th colspan="2" style="text-align:right; vertical-align: middle; text-transform: uppercase;">
            </th>
        </tr>
        <tr>
            <th colspan='4' style="padding-left: 60px; font-size: 15px;">
            DESDE: {{$fecha_inicio}}  
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            HASTA: {{$fecha_fin}}</th>
            <th style="font-size: 15px;"></th>
        </tr>
        <tr>
            <th colspan='4' style="padding-left: 60px; font-size: 15px;"></th>
            <th style="font-size: 15px;"></th>
        </tr>
    </table>
<body>
    @php
        $numero = 1;
    @endphp
    <div class="container">
        <div class="col-lg-7 col-md-7" style=" color: black">   
            @foreach($venta as $comp)     
            <table class="table" style="">
                <thead style="border: 1px solid black">
                    <tr> 
                        <th style="vertical-align: middle; border-right: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="30%"><strong>{{__('Cliente:')}}</strong> {{$comp['cliente']}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Descuento:')}}</strong> {{$comp['descuento']}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total:')}}</strong> {{$comp['total']}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="15%">{{__('Cantidad')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="15%">{{__('PU')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="15%"><strong>{{__('Sub Total:')}}</strong> {{$comp['sub_total']}}</th>
                    </tr>
                </thead>
                
                <tbody  style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">
                    @foreach($detalles as $det)
                    @if($comp['id']==$det['id_venta'])
                    <tr style="border-top: 1px solid black">
                        <td colspan="3" style="text-align: left; border-right: 1px solid black;">{{ $det['producto']}}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['cantidad']}}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['costo_venta'] }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['sub_total'] }}</td>
                    </tr>
                    @endif
                    @php
                        $numero++;
                    @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6"></td>
                    </tr>
                </tfoot>
            </table>
            @endforeach
            @if($venta === [])
            <table class="table" style="">
                <thead style="border: 1px solid black">
                    <tr> 
                        <th style="vertical-align: middle; border-right: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="30%"><strong>{{__('Cliente:')}}</strong></th>
                        <th style="vertical-align: middle; border-right: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Descuento:')}}</strong></th>
                        <th style="vertical-align: middle; border-right: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Sub Total:')}}</strong></th>
                        <th style="vertical-align: middle; border-right: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="15%">{{__('Cantidad')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="15%">{{__('PU')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="15%">{{__('Sub Total')}}</th>
                    </tr>
                </thead>
                <tbody  style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">
                    <tr style="border-top: 1px solid black">
                        <td colspan="6" style="text-align: left; border-right: 1px solid black;">{{__('No se encuentran registro entre estas fechas')}}</td>
                    </tr>
                </tbody>
            </table>
            @endif
            <br><br><br><br><br><br>
            </div>
        </div>

    </body>

</html>


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
    $color2 = "#E46C0A";
    $color3 = "#dedbb6";
    $color4 = "#a2d972";
    $color5 = "#000000";
    $color6 = "#E46C0A";
@endphp
<style>
    @page {
        margin: 0.7cm 0.7cm 0.7cm 0.7cm;
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
<header>
    @php
        $no_encontrada = "https://i.ibb.co/DVT0mnx/noencontrada.png";
    @endphp
<header>
    @php
        $no_encontrada = "https://i.ibb.co/DVT0mnx/noencontrada.png";
    @endphp
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
            <th colspan='4' style="padding-left: 60px; font-size: 15px;">DESDE: {{$fecha_inicio}} HASTA: {{$fecha_fin}}</th>
            <th style="font-size: 15px;"></th>
        </tr>
        <tr>
            <th colspan='4' style="padding-left: 60px; font-size: 15px;"></th>
            <th style="font-size: 15px;"></th>
        </tr>
    </table>
</header>
<body>
    @php
        $numero = 1;
    @endphp
    <div class="container">
        <div class="col-lg-7 col-md-7" style=" color: black">        
            <table class="table" style="">
                <thead style="border: 1px solid black; background-color:<?php echo $color1; ?>">
                    <tr> 
                        <th style="vertical-align: middle; border-right: 2px solid black" width="5%" style="">{{__('Nro')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="15%" style="">{{__('Fecha')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="20%" style="">{{__('Tiena Origen')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="20%" style="">{{__('Tienda Destino')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="20%" style="">{{__('Glosa')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="20%" style="">{{__('Usuario')}}</th>
                    </tr>
                </thead>
                
                @foreach($detalles as $det)
                <tbody  style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">
                    <tr style="border-top: 1px solid black">
                        <td style="text-align: center; border-right: 1px solid black;">{{ $numero }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['fecha'] }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['tienda1']}}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['tienda2']}}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['glosa'] }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['name']}}</td>
                    </tr>
                    @php
                        $numero++;
                    @endphp
                    @endforeach
                </tbody>
            </table>
                <br><br><br><br><br><br>
            </div>
        </div>

    </body>

</html>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    <title>LISTA DE PRODUCTOS</title>
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
<header>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 0px; table-layout: fixed;">
        <tr>
            <td style="width: 80px; text-align: center; vertical-align: middle; padding: 10px;">
                @if($empresa && $empresa->foto)
                    <img src="{{ 'img/logo/' . $empresa->foto }}" 
                        style="height: 60px; width: auto; display: block; margin: 0 auto;" 
                        alt="Logo de la Empresa">
                @else
                    <div style="width: 60px; height: 60px; background-color: #f0f0f0; border: 1px solid #ccc; margin: 0 auto;"></div>
                @endif
            </td>
            <td style="text-align: center; vertical-align: middle; padding: 0px;">
                <div style="font-size: 20px; font-weight: bold; color: #001843; line-height: 1.3; margin: 0; padding: 0;">
                    LISTA DE PRODUCTOS
                </div>
            </td>
            <td style="width: 80px; padding: 10px;">
                </td>
        </tr>
    </table>
    
    <table class="table-head table-borderless;" style="padding-bottom: 7px; " width="100%">
        <tr><th colspan="2"></th></tr>
    </table>
</header>

<body>
    <div class="container">
        <div class="col-lg-7 col-md-7" style="color: black">        
            <table class="table" style="">
                <thead style="border: 1px solid black; background-color:<?php echo $color1; ?>">
                    <tr> 
                        <th style="vertical-align: middle; border-right: 2px solid black; color:#FFFFFF" width="5%">{{__('Nro')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; color:#FFFFFF" width="35%">{{__('Producto')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; color:#FFFFFF" width="35%">{{__('Nombre Generico')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; color:#FFFFFF" width="10%">{{__('F. Venc')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; color:#FFFFFF" width="10%">{{__('Ubic.')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; color:#FFFFFF" width="10%">{{__('P. Unidad')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; color:#FFFFFF" width="10%">{{__('P. Blister')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; color:#FFFFFF" width="10%">{{__('P. Caja')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; color:#FFFFFF" width="10%">{{__('Stock')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black; color:#FFFFFF" width="15%">{{__('Lab.')}}</th>
                    </tr>
                </thead>
                
                <tbody style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">
                    @foreach($detalles as $det)
                    <tr style="border-top: 1px solid black">
                        <td style="text-align: center; border-right: 1px solid black;">{{ $loop->iteration }}</td>
                        <td style="text-align: left; border-right: 1px solid black; font-size: 8px; padding-left:3px;">{{ $det->nombre_comercial }}</td>
                        <td style="text-align: left; border-right: 1px solid black; font-size: 8px; padding-left:3px;">{{ $det->nombre_generico }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det->fecha_vecimiento }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det->ubicacion }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det->costo_unitario }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det->precio_blister }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det->precio_caja }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det->stock }}</td>
                        <td style="text-align: center; border-right: 1px solid black; font-size: 8px;">{{ $det->laboratorio }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br><br><br>
        </div>
    </div>
</body>
</html>
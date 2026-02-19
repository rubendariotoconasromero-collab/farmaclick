<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    <title>{{$title}}</title>
</head>
@php
    $color1 = "#001843";
    $color2 = "#FF0107";
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
<table style="width: 100%; border-collapse: collapse; margin-bottom: 0px; table-layout: fixed;">
    <tr>
        <td style="width: 80px; text-align: center; vertical-align: middle; padding: 10px;">
            @if($logo_sistema)
                <img src="{{ 'img/logo/' . $logo_sistema }}" 
                    style="height: 60px; width: auto; display: block; margin: 0 auto;" 
                    alt="Logo de la Empresa">
            @else
                <div style="width: 60px; height: 60px; background-color: #f0f0f0; border: 1px solid #ccc; margin: 0 auto;"></div>
            @endif
        </td>
        <td style="text-align: center; vertical-align: middle; padding: 0px;">
            <div style="font-size: 20px; font-weight: bold; color: #001843; line-height: 1.3; margin: 0; padding: 0;">
                {{ strtoupper($title) }}
            </div>
        
        </td>
        <td style="width: 80px; padding: 10px;">
        </td>
    </tr>
</table>
<table class="table-head table-borderless;" style="padding-bottom: 7px; " width="100%">
    <tr>
    {{-- <th colspan="2" style="text-align:center; vertical-align: middle; text-transform: uppercase; border-top: 1px solid #5A007F; border-left: 1px solid #5A007F; border-right: 1px solid #5A007F">
            <div style="">
            <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ $nombre_empresa }}</FONT>
            </div>
        </th> --}}
    </tr>
    <tr style="">
    {{-- <th width="20%" style="text-align:right; vertical-align: middle; text-transform: uppercase; border-left: 1px solid #5A007F">
            <div style="" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=2>{{__('DIRECCIÓN: ')}}</FONT>
            </div>
        </th>
    <th width="17%" style="text-align:left; vertical-align: middle;; border-right: 1px solid #5A007F">  
            <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=2>{{ $direccion_empresa }}</FONT>
                </div>
        </th> --}}
    </tr>
    <tr style="">
        {{-- <th width="20%" style="text-align:center; vertical-align: middle; border-left: 1px solid #5A007F">

        </th>
        <th style="text-align:left; vertical-align: middle;; border-right: 1px solid #5A007F">  
        </th> --}}
    </tr>
    <tr style="">
        <th colspan="2" style="">  

        </th>
    </tr>
    <tr style="">
        <th colspan="2" style="">  
            {{-- <div style="font-weight: normal" >
            <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=2>{{ $direccion_empresa }}</FONT>
            </div> --}}
        </th>
    </tr>
    <tr style="">
        <th colspan="2" style="text-align:right; vertical-align: middle; text-transform: uppercase;">
        </th>
    </tr>
    {{-- <tr>
        <th colspan='4' style="padding-left: 60px; font-size: 15px;">DESDE: {{$fecha_inicio}} HASTA: {{$fecha_fin}}</th>
        <th style="font-size: 15px;"></th>
    </tr> --}}
    <tr>
        <th colspan='4' style="padding-left: 60px; font-size: 15px;"></th>
        <th style="font-size: 15px;"></th>
    </tr>
</table>
{{-- <table class="table" style="" >
    <thead>
        <tr>
            <th  colspan=4 style="padding-left: 60px; font-size: 15px">
            DESDE: {{$fecha_inicio}}  
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            HASTA: {{$fecha_fin}}</th>
        </tr>
        <tr >
            @foreach($detalles2 as $det1)
            <th style="text-align:left;color:#ffff;font-size: 12px; background-color:<?php echo $color1; ?>">
            Total Venta.: {{$det1['totalV']}}  </th>
            <th style="font-size: 15px;"></th>
            <th style="font-size: 15px;"></th>
            <th></th>


            @endforeach
        </tr>
        <tr>
            <td heigth="5px">
        </tr>
        <tr>
            @foreach($detalles5 as $det3)
            <th colspan="1" style=" text-align:left;color:#ffff;font-size: 12px;background-color:#5386a5">
                Total Venta Contado: {{$det3['totalC']}}  
            @endforeach    
            <th heigth="5px"></th>
            @foreach($detalles6 as $det4)
            <th colspan="1" style="text-align:left;color:#ffff;font-size: 12px;background-color:#5386a5">
                Total Venta Credito: {{$det4['totalCr']}}  
            @endforeach   
                <th></th>
        </tr>
        <tr>
            <td heigth="5px">
        </tr>
        <tr >
            @foreach($detalles7 as $det4)
            <th style="text-align:left;font-size: 12px;background-color:#a0d2f3">
            Total Efectivo.: {{$det4['totalEf']}}  
            <th style="font-size: 15px;"></th>
            <th style="font-size: 15px;"></th>
            <th style="font-size: 15px;"></th>
            @endforeach
        </tr>
        <tr>
            <td heigth="5px">
        </tr>
        <tr >
            @foreach($detalles8 as $det5)
            <th style="text-align:left;font-size: 12px;background-color:#a0d2f3">
            Total Deposito.: {{$det5['totalDep']}}  
            <th style="font-size: 15px;"></th>
            <th style="font-size: 15px;"></th>
            <th style="font-size: 15px;"></th>

            @endforeach
        </tr>
        <tr>
            <td heigth="5px">
        </tr>
    </thead>
</table> --}}
<body>
    @php
        $numero = 1;
        $id=0;
    @endphp
    <div class="container">
        <div class="col-lg-7 col-md-7" style=" color: black">   
            @foreach($venta as $comp)   
            <table class="table" style="">
                <thead style="border: 1px solid black">
                    <tr> 
                        <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="70%">{{$comp['producto']}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Presentacion :')}}</strong>{{$comp['presentacion']}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color2; ?>" width="15%">{{__('Stock:')}}</strong> {{$comp['stock']}}</th>
                    </tr>
                </thead>
                
                @foreach($detalles as $det)
                @if($comp['id']==$det['id_producto'])
                <tbody  style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">
                    <tr style="border-top: 1px solid black">
                        <td colspan="2" style="text-align: center; border-right: 1px solid black;"><strong>{{__('Lote:')}}</strong> {{ $det['lote']}}
                        | <strong>{{__('F. Vencimiento:')}}</strong> {{ $det['fecha_vecimiento']}}
                        </td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['cantidad']}}</td>
                    </tr>
                </tbody>
                    @endif
                    @php
                        $numero++;
                       
                    @endphp
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>

            </table>
  
            @endforeach

            <br><br><br><br><br><br>
            </div>
        </div>

    </body>

</html>


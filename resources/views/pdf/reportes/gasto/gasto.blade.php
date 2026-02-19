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
    @php
        $no_encontrada = "https://i.ibb.co/DVT0mnx/noencontrada.png";
    @endphp
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
            </th>
            <th style='width: 120px;'>             
            </th>
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
     
        <tr>
            <th colspan='4' style="padding-left: 60px; font-size: 15px;"></th>
            <th style="font-size: 15px;"></th>
        </tr>
    </table>
    <table class="table" style="" >
        <thead>
            <tr >
                {{-- @foreach($detalles2 as $det1) --}}
                <th style="text-align:left;color:#ffff;font-size: 12px; background-color:<?php echo $color1; ?>">
                Total Gasto.: {{$total}}  </th>
                <th style="font-size: 15px;"></th>
                <th style="font-size: 15px;"></th>
                <th></th>


                {{-- @endforeach --}}
            </tr>
            <tr>
                <td heigth="5px">
            </tr>
            <tr>

                <th colspan="1" style=" text-align:left;color:#ffff;font-size: 12px;background-color:#5386a5">
                    Total Gasto Efectivo: {{$total_general}}   
                <th heigth="5px"></th>
                <th colspan="1" style="text-align:left;color:#ffff;font-size: 12px;background-color:#5386a5">
                    Total Gasto  Depositvo: {{$total_deposito}}  
                    <th></th>
            </tr>
            <tr>
                <td heigth="5px">
            </tr>
        </thead>
    </table>
</header>
<body>
    @php
        $numero = 1;
    @endphp
    <div class="container">
        <div class="col-lg-7 col-md-7" style=" color: black">        
            @foreach($detalles as $det)
            <table class="table" style="">
                {{-- @foreach($detalles as $det) --}}
                <thead style="border: 1px solid black; background-color:<?php echo $color1; ?>">
                    <tr> 
                        {{-- <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="5%" style="">{{__('Nro')}}</th> --}}
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="15%" style="">{{__('Fecha')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="15%" style="">{{__('Motivo')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="20%" style="">{{__('Forma Pago')}}</th>
                        @if($det['id_forma_pago'] !=6)
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="30%" style="">{{__('Monto')}}</th>
                        @endif
                        @if($det['id_forma_pago'] ==6)
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="10%" style="">{{__('Monto')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF"  width="10%" style="">{{__('T. Efectivo')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF"  width="10%" style="">{{__('T. Deposito')}}</th>
                        @endif
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="20%" style="">{{__('Usuario')}}</th>
                    </tr>


                </thead>
                
                <tbody  style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">
                    <tr style="border-top: 1px solid black">
                        {{-- <td style="text-align: center; border-right: 1px solid black;">{{ $numero }}</td> --}}
                        <td style="text-align: center; border-right: 1px solid black;" width="15%">{{ $det['fecha'] }}</td>
                        <td style="text-align: center; border-right: 1px solid black;" width="15%">{{ $det['descripcion']}}</td>
                        <td style="text-align: center; border-right: 1px solid black;" width="20%">{{ $det['forma']}}</td>
                        @if($det['id_forma_pago'] !=6)
                        <td style="text-align: center; border-right: 1px solid black;" width="30%">{{ $det['monto']}}</td>
                        @endif
                        @if($det['id_forma_pago'] ==6)
                        <td style="text-align: center; border-right: 1px solid black;" width="10%">{{ $det['monto']}}</td>
                             <td style="text-align: center; border-right: 1px solid black;" width="10%">{{ $det['efectivo']}}</td>
                             <td style="text-align: center; border-right: 1px solid black;" width="10%">{{ $det['deposito']}}</td>
                        @endif
                        <td style="text-align: center; border-right: 1px solid black;" width="20%">{{ $det['usuario']}}</td>
                    </tr>
                    {{-- @php
                        $numero++;
                    @endphp --}}
                </tbody>
            </table>
            <br>
            @endforeach
            </div>
        </div>

    </body>

</html>


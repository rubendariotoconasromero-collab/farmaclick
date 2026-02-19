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
<table class="table-head table-borderless;" style="padding-bottom: 7px; " width="100%">
    <tr style="">
        <th rowspan="7" colspan="2" width="10%" style="vertical-align:middle;">
                {{-- <img src=<?php echo ( !is_array(@getimagesize("../public_html/img/sitnorte-centralcell.png")) ? "../public_html/img/sitnorte-centralcell.png" : str_replace(" ","%20","../public_html/img/sitnorte-centralcell.png")) ?> height="100"> --}}
                {{-- <img src=<?php //echo ( !is_array(@getimagesize("img/sitnorte-centralcell.png")) ? "img/sitnorte-centralcell.png" : str_replace(" ","%20","img/sitnorte-centralcell.png")) ?> height="100"> --}}
                {{-- <img src=<?php echo $foto_empresa != null ? "../public_html/img/logo/".$foto_empresa : '""' ?> height="70" width="180"> --}}
                <img src=<?php echo $foto_empresa != null ? "img/logo/".$foto_empresa : '""' ?> height="70" width="180">

        </th>
        <th rowspan="7" width="60%" style="padding-left: 10px; text-align: center; vertical-align: middle;">
            <div style="font-weight: normal" >
            <strong ><FONT FACE="times new roman" style="color: black" SIZE=4>{{$title}}</FONT></strong >
            </div>
            <div style="font-weight: normal" >
                <strong ><FONT FACE="times new roman" style="color: black" SIZE=4>{{$usuarioActual}}</FONT></strong >
            </div>
            <div style="font-weight: normal; padding-top: 15px" >
                <strong ><FONT FACE="times new roman" style="color: black" SIZE=3>DESDE: <span style="font-weight: normal">{{$fecha_inicio}}</span>   HASTA: <span style="font-weight: normal">{{$fecha_fin}}</span></FONT></strong >
            </div>
        </th>
        <th width="30%" colspan="2" style="text-align:center; vertical-align: middle; text-transform: uppercase;">
        </th>
    </tr>
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
<table class="table" style="" >
    <thead>
        <tr >
            @foreach($detalles2 as $det1)
            <th style="text-align:left;color:#ffff;font-size: 12px; background-color:<?php echo $color1; ?>">
            Total Venta.: {{$det1['totalV']}}  </th>
            <th style="font-size: 15px;"></th>
            <th style="font-size: 15px;"></th>
            <th></th>


            @endforeach
        </tr>
        <!-- <tr>
            <td heigth="5px">
        </tr> -->
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
</table>
<body>
    @php
        $numero = 1;
        $id=0;
    @endphp
    <div class="container">
        <div class="col-lg-7 col-md-7" style=" color: black">   
            @foreach($venta as $comp)   
            @if($id != $comp['id'])
            
            <table class="table" style="">
                <thead style="border: 1px solid black">
                    <tr> 
                        <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Cliente:')}}</strong> {{$comp['cliente']}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="10%"><strong>{{__('Tipo P.:')}}</strong> {{$comp['tipo_pago']}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Forma P.:')}}</strong> {{$comp['forma_pago']}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="15%"><strong>{{__('Desc.:')}}</strong> {{$comp['descuento']}}</th>
                        @if($comp['forma_pago'] == 'Efectivo')
                         <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total:')}}</strong> {{$comp['total_efectivo']}}</th>
                         <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total E.:')}}</strong> {{$comp['total_efectivo']}}</th>
                        @elseif($comp['forma_pago'] == 'Transferencia')
                         <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total:')}}</strong> {{$comp['total_deposito']}}</th>
                         <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total D.:')}}</strong> {{$comp['total_deposito']}}</th>
                         @elseif($comp['forma_pago'] == 'Pago por QR')
                         <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total:')}}</strong> {{$comp['total_deposito']}}</th>
                         <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total D.:')}}</strong> {{$comp['total_deposito']}}</th>
                         @elseif($comp['forma_pago'] == 'Depósito')
                         <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total:')}}</strong> {{$comp['total_deposito']}}</th>
                         <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total D.:')}}</strong> {{$comp['total_deposito']}}</th>
                         @elseif($comp['forma_pago'] == 'Cuenta por Cobrar')
                         <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total:')}}</strong> {{$comp['total']}}</th>
                         <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total C.:')}}</strong> {{$comp['total']}}</th>
                         @elseif($comp['forma_pago'] == 'Mixta')
                         <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total:')}}</strong> {{$comp['total']}}</th>
                         <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total E.:')}}</strong> {{$comp['total_efectivo']}}</th>
                         <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color1; ?>" width="20%"><strong>{{__('Total D.:')}}</strong> {{$comp['total_deposito']}}</th>
                        @endif

                        <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color2; ?>" width="15%">{{__('Cantidad')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color2; ?>" width="15%">{{__('PU')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black;color:#FFFFFF; font-weight: normal; background-color:<?php echo $color2; ?>" width="20%"><strong>{{__('Sub Total:')}}</strong> {{$comp['sub_total']}}</th>
                    </tr>
                </thead>
                
                @foreach($detalles as $det)
                @if($comp['id']==$det['id_venta'])
                <tbody  style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">
                    <tr style="border-top: 1px solid black">
                        @if($comp['forma_pago'] == 'Mixta')
                        <td colspan="7" style="text-align: left; border-right: 1px solid black;">{{ $det['producto']}}</td>
                        @else
                        <td colspan="6" style="text-align: left; border-right: 1px solid black;">{{ $det['producto']}}</td>
                        @endif
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['cantidad']}}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['costo_venta'] }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['sub_total'] }}</td>
                    </tr>
                </tbody>
                    @endif
                    @php
                        $numero++;
                       
                    @endphp
                @endforeach
                     <!-- @foreach($detallesPaquete as $det2)
                    
                    @if($comp['id'] == $det2['id_venta'])
                    <tbody  style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">
                        
                        <tr style="border-top: 1px solid black">
                            <td colspan="3" style="text-align: left; border-right: 1px solid black;">{{ $det2['producto'] }}</td>
                            <td style="text-align: center; border-right: 1px solid black; background-color:">{{ $det2['cantidad']}}</td>
                            <td style="text-align: center; border-right: 1px solid black;">{{ $det2['costo_venta']}}</td>
                            <td style="text-align: left; border-right: 1px solid black; text-align: center; background-color:">{{ $det2['sub_total'] }}</td>
                        </tr>
                        @php
                            $numero++;
                        @endphp
                        @endif
                    @endforeach  -->
                </tbody>
                @if($comp['forma_pago'] == 'Mixta')
                <tfoot>
                    <tr>
                        <td colspan="10"></td>
                    </tr>
                </tfoot>
                @else
                <tfoot>
                    <tr>
                        <td colspan="9"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
            @php
             $id= $comp['id'];
            @endphp
            @endif

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


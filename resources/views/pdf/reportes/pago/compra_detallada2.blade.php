<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    <title>Proforma</title>
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
        height: 80px; 
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
    <table class="table-head table-borderless" style="padding-bottom: 7px" width="100%">
        <tr style="">
            <th rowspan="1"  width="30%">
                    <img src=<?php echo ( !is_array(@getimagesize("../public_html/img/sitnorte-centralcell.png")) ? "../public_html/img/sitnorte-centralcell.png" : str_replace(" ","%20","../public_html/img/sitnorte-centralcell.png")) ?> height="100">
                    {{-- <img src=<?php //echo ( !is_array(@getimagesize("img/sitnorte-centralcell.png")) ? "img/sitnorte-centralcell.png" : str_replace(" ","%20","img/sitnorte-centralcell.png")) ?> height="100"> --}}
            </th>

            <th width="40%" style="padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: center; border: 2px solid <?php echo $color1; ?>; border-radius: 5px, 5px">
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: black" SIZE=3>{{__('Web Site: ')}}</FONT>
                <u><FONT FACE="times new roman" style="color: blue" SIZE=3>{{ '$sitio_web_empresa' }}</FONT></u>
                </div>    
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('NIT: ')}}</FONT>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ '$nit_empresa' }}</FONT>
                </div>
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('TEL: ')}}</FONT>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ '$telefono_empresa' }}</FONT>
                <!-- <strong style="color: <?php echo $color5; ?>"> {{__('TEL: ')}}: </strong> {{ '$telefono_empresa' }} -->
                </div>
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ '$direccion_empresa' }}</FONT>
                </div>
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: black" SIZE=3>{{__('Correo: ')}}</FONT>
                <u><FONT FACE="times new roman" style="color:blue" SIZE=3>{{ '$correo_empresa' }}</FONT></u>
                <!-- <strong style="color: <?php echo $color5; ?>"> {{ '$correo_empresa' }} </strong>  -->
                </div>
                <!-- <div style="font-weight: normal" >
                <strong style="color: <?php echo $color1; ?>"> {{__('Clientes')}}: </strong> {{ '$cliente' }}
                </div> -->
            </th>
            <th width="30%" style="text-align:right">
                <div style="font-weight: normal">
                <strong ><FONT FACE="times new roman" style=" color: black;background-color: #FCD5B4" SIZE="5">{{__('PROFORMA ')}}</FONT></strong>
                </div>    
                <div style="font-weight: normal">
                <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Número: ')}}</FONT></strong>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ '$codigo_proforma' }}</FONT>
                </div>
                <div style="font-weight: normal" >
                <strong ><FONT  FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('Fecha: ')}}</FONT></strong>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ '$fecha' }}</FONT>
                </div>
                <div style="font-weight: normal" >
                <strong><FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('F. Venc.: ')}}</FONT></strong>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ '$fecha_venci' }}</FONT>
                </div>
                <div style="font-weight: normal" >
               <strong><FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('Días Crédito: ')}}</FONT></strong>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ '$dias_credito' }}</FONT>
                </div>
            </th>
            <th colspan="3" width="20%" style="padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: left">
            </th>
        </tr>
        <tr style="font-weight: normal;text-align:center">
            <td colspan=3>
                <strong ><FONT FACE="times new roman" style=" border: 0.4px solid black; color: black;background-color: #FCD5B4" SIZE="4">{{__('    DATOS DEL CLIENTE ')}}</FONT></strong>
            </td>
        </tr>
        <tr style="">
            <td colspan=3>
                <table style="width:100%;padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: center; border: 2px solid <?php echo $color1; ?>; border-radius: 5px, 5px">
                    <tr style="width:100%" >    
                        <td style="text-align:left;width:10%">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Empresa: ')}}</FONT></strong>
                        </td>
                        <td style="text-align:left;width:50%">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{ '$cliente' }}</FONT>
                        </td>
                        <td style="text-align:right;width:10%">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('NIT/CI.: : ')}}</FONT></strong>
                        </td>
                        <td style="text-align:left;width:30%">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{ '$nit' }}</FONT>
                        </td>
                    </tr> 
                    <tr style="width:100%" >    
                        <td style="text-align:left;width:10%">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Contacto: ')}}</FONT></strong>
                        </td>
                        <td style="text-align:left;width:45%">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{ '$contacto' }}</FONT>
                        </td>
                        <td style="text-align:right;width:20%">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Asesor de Venta: ')}}</FONT></strong>
                        </td>
                        <td style="text-align:left;width:25%">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{ '$personal' }}</FONT>
                        </td>
                    </tr> 
                    <tr style="width:100%" >    
                        <td style="text-align:left;width:10%">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Dirección: ')}}</FONT></strong>
                        </td>
                        <td style="text-align:left;width:50%">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{ '$direccion '}}</FONT>
                        </td>
                        <td style="text-align:right;width:10%">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Ciudad.: ')}}</FONT></strong>
                        </td>
                        <td style="text-align:left;width:30%">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{ '$ciudad' }}</FONT>
                        </td>
                    </tr> 
                    <tr style="width:100%" >    
                        <td style="text-align:left;width:10%">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Teléfono: ')}}</FONT></strong>
                        </td>
                        <td style="text-align:left;width:50%">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{ '$telefono' }}</FONT>
                        </td>
                    </tr> 
                    <tr style="width:100%" >    
                        <td style="text-align:left;width:10%">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Email.: ')}}</FONT></strong>
                        </td>
                        <td style="text-align:left;width:50%">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{ '$correo' }}</FONT>
                        </td>
                    </tr> 
                </table>
            </td>
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

        @foreach($detalles as $det)
        @php    
            $x1 = 0;//$det['descuento'];
            $xx=$xx+$x1;
        @endphp
        @endforeach

    @php
        $saldo_por_pagar = 0;//$total+$saldo_anterior-$pago;
    @endphp
    <div class="container">
        <div class="col-lg-7 col-md-7" style=" color: black">

            <table class="table" style="">
                <thead style="border: 1px solid black; background-color:<?php echo $color1; ?>">
                    <tr> 
                        <th style="vertical-align: middle; border-right: 2px solid black" width="5%" style="">{{__('Items')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="9%" style="">{{__('Código')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="5%" style="">{{__('Cantidad')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="6%" style="">{{__('Unidades')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="50%" style="">{{__('Descripción')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="7%" style="">{{__('Valores Unitarios')}}</th>
                        @if($xx > 0)
                        <th colspan="2" style="vertical-align: middle; border-right: 2px solid black" width="9%" style="">{{__('Descuentos')}}</th>
                        @endif
                        <!-- <th class="<?php echo ($xx===0) ? "nomostrar" : "mostrar"; ?>" colspan="2" style="vertical-align: middle; border-right: 2px solid black" width="9%" style="">{{__('Descuentos')}}</th> -->
                        <th style="vertical-align: middle; border-right: 2px solid black" width="11%" style="">{{__('Valores Totales')}}</th>
                    </tr>
                </thead>

                

                @foreach($detalles as $det)
                <tbody  style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">
                    
                    <!-- class="page_break" -->
                    <!-- <div> -->
                    <!--  -->

                    <tr class="<?php echo ($tamaño===2) ? ("page_break") : ""; ?>" style="border-top: 1px solid black">
                        <td style="text-align: center; border-right: 1px solid black; background-color:#DCE6F1">{{ $numero }}</td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:#DCE6F1">{{ 0/* $det['cod_producto'] */ }}</td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:#DCE6F1">{{ $det['cantidad']}}</td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:#DCE6F1">{{ 0/* $det['medida'] */}}</td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:#DCE6F1"><strong ><FONT FACE="times new roman" style=" color: black" SIZE=2>{{ $det['producto']}}</FONT><strong ></td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['pu'] }}</td>
                        
                        @if($xx > 0)
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ number_format((($det['descuento'])*100)/$det['sub_total'],2 )}}{{__('%')}}</td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['descuento'] }}</td>
                        @endif
                        <td style="text-align: left; border-right: 1px solid black; text-align: center; background-color:<?php echo $color6; ?>">{{ $det['sub_total'] }}</td>
                    </tr>
                    <tr class="<?php echo ($tamaño===2) ? ($tamaño=0) : ""; ?>">
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: left; border-right: 1px solid black; background-color:<?php echo $color6; ?>"><strong >{{__('Modelo: ')}}</strong >{{ 0/* $det['modelo'] */}}</td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        @if($xx > 0)
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        @endif
                        <td style="text-align: left; border-right: 1px solid black; text-align: center; background-color:<?php echo $color6; ?>"></td>
                    </tr>
                    <tr style="height:100px">
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: left; border-right: 1px solid black; background-color:<?php echo $color6; ?>"><strong >{{__('Código: ')}}</strong >{{ 0/* $det['cod_producto'] */}}</td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        @if($xx > 0)
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        @endif
                        <td style="text-align: left; border-right: 1px solid black; text-align: center; background-color:<?php echo $color6; ?>"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: left; border-right: 1px solid black; background-color:<?php echo $color6; ?>"><strong >{{__('Marca: ')}}</strong >{{ 0/* $det['marca'] */}}</td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        @if($xx > 0)
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        @endif
                        <td style="text-align: left; border-right: 1px solid black; text-align: center; background-color:<?php echo $color6; ?>"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: left; border-right: 1px solid black; background-color:<?php echo $color6; ?>"><strong >{{__('Especificaciones: ')}}</strong ></td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        @if($xx > 0)
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        @endif
                        <td style="text-align: left; border-right: 1px solid black; text-align: center; background-color:<?php echo $color6; ?>"></td>
                    </tr>
                    <tr>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        {{-- <td style="text-align: left; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['observacion']}}</td> --}}
                        {{-- <td style="text-align: left; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ str_replace("alex","<br>",$det['observacion']) }}</td> --}}
                        <td style="text-align: left; border-right: 1px solid black; background-color:<?php echo $color6; ?>"><?php echo 0/* echo nl2br(str_replace("-","\n -",$det['observacion'] ))*/ ?></td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        @if($xx > 0)
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        @endif
                        <td style="text-align: left; border-right: 1px solid black; text-align: center; background-color:<?php echo $color6; ?>"></td>
                    </tr>
                    
                    @php
                        $color7 = $color5;
                        $numero++;
                        $tamaño=$tamaño+1;
                        $tamaño2=$tamaño2+1;
                    @endphp
                    
                    @endforeach
<!-- </div> -->
                </tbody>
            </table>
        </div>

    </body>
</html>


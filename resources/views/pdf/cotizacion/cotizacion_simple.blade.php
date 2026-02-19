<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    <title>Cotización</title>
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
                height: 1cm;
            }

</style>
<header>
    <table class="table-head table-borderless" style="padding-bottom: 7px" width="100%">
        <tr style="">
            <th rowspan="1"  width="30%">
                <img src=<?php echo $detalles[0]['foto'] != null ? "../public_html/img/mi_empresa/".$detalles[0]['foto'] : '""' ?> height="100" width="200">
                 {{-- <img src=<?php //echo $detalles[0]['foto'] != null ? "img/mi_empresa/".$detalles[0]['foto'] : '""' ?> height="100" width="200">  --}}
            </th>

            <th width="40%" style="padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: center; border: 2px solid <?php echo $color1; ?>; border-radius: 5px, 5px">
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: black" SIZE=3>{{__('Tienda: ')}}</FONT>
                <FONT FACE="times new roman" style="color: black" SIZE=3>{{ $detalles[0]['tienda'] }}</FONT>
                </div>    
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('Cod. Tienda: ')}}</FONT>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ $detalles[0]['cod_tienda'] }}</FONT>
                </div>
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('TEL: ')}}</FONT>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ $detalles[0]['direccion'] }}</FONT>
                <!-- <strong style="color: <?php echo $color5; ?>"> {{__('TEL: ')}}: </strong> {{ $telefono_empresa }} -->
                </div>
                <div style="font-weight: normal" >
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ $detalles[0]['cod_almacen'] }}</FONT>
                </div>
                <!-- <div style="font-weight: normal" >
                <strong style="color: <?php echo $color1; ?>"> {{__('Clientes')}}: </strong> {{ $cliente }}
                </div> -->
            </th>
            <th width="30%" style="text-align:right">
                <div style="font-weight: normal">
                <strong ><FONT FACE="times new roman" style=" color: black;background-color: #FCD5B4" SIZE="5">{{__('COTIZACION ')}}</FONT></strong>
                </div>    
                {{-- <div style="font-weight: normal">
                <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Número: ')}}</FONT></strong>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ $codigo_proforma }}</FONT>
                </div> --}}
                <div style="font-weight: normal" >
                <strong ><FONT  FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('Fecha: ')}}</FONT></strong>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ $fecha }}</FONT>
                </div>
                <div style="font-weight: normal" >
                <strong><FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('F. Venc.: ')}}</FONT></strong>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ $fecha_venci }}</FONT>
                </div>
                <div style="font-weight: normal" >
               <strong><FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('Días Crédito: ')}}</FONT></strong>
                <FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ $dias_credito }}</FONT>
                </div>
            </th>
            <th colspan="3" width="20%" style="padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: left">
            </th>
            <!-- <th rowspan="3" width="40%" style="vertical-align: middle">
                <div>
                    <img src=<?php //echo $logotipo != null ? "$logotipo" : '""' ?> height="80">
                </div>
            </th> -->
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
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Nombre: ')}}</FONT></strong>
                        </td>
                        <td style="text-align:left;width:50%">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{ $cliente }}</FONT>
                        </td>
                        <td style="text-align:right;width:10%">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('NIT/CI.:')}}</FONT></strong>
                        </td>
                        <td style="text-align:left;width:30%">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{ $nit }}</FONT>
                        </td>
                    </tr> 

                    <tr style="width:100%" >    
                        <td style="text-align:left;width:10%">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Dirección: ')}}</FONT></strong>
                        </td>
                        <td style="text-align:left;width:50%">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{ $direccion }}</FONT>
                        </td>
                        <td style="text-align:left;width:10%">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Teléfono: ')}}</FONT></strong>
                        </td>
                        <td style="text-align:left;width:30%">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{ $telefono }}</FONT>
                        </td>
                    </tr> 
                    <tr style="width:100%" >    
                        <td style="text-align:left;width:10%">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Teléfono: ')}}</FONT></strong>
                        </td>
                        <td style="text-align:left;width:50%">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{ $telefono }}</FONT>
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
        $numero2 = 1;
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
            $x1 = $det['descuento'];
            $xx=$xx+$x1;
        @endphp
        @endforeach

    @php
        $saldo_por_pagar = $total+$saldo_anterior-$pago
    @endphp
    <div class="container">
        <div class="col-lg-7 col-md-7" style=" color: black">
            <!-- <footer> -->
                <!-- <img src="img/mi_empresa/k1.jpg" width="18%" height="60%"/>
                <img src="img/mi_empresa/k2.jpg" width="18%" height="60%"/>
                <img src="img/mi_empresa/k3.jpg" width="18%" height="60%"/>
                <img src="img/mi_empresa/k4.jpg" width="18%" height="60%"/>
                <img src="img/mi_empresa/k5.jpg" width="18%" height="60%"/> -->
                <!-- <img src="../public_html/img/mi_empresa/k1.jpg" width="18%" height="60%"/>
                <img src="../public_html/img/mi_empresa/k2.jpg" width="18%" height="60%"/>
                <img src="../public_html/img/mi_empresa/k3.jpg" width="18%" height="60%"/>
                <img src="../public_html/img/mi_empresa/k4.jpg" width="18%" height="60%"/>
                <img src="../public_html/img/mi_empresa/k5.jpg" width="18%" height="60%"/>
            </footer> -->
            <table class="table" style="">
                <thead style="border: 1px solid black; background-color:<?php echo $color1; ?>">
                    <tr> 
                        <th style="vertical-align: middle; border-right: 2px solid black" width="5%" style="">{{__('Items')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black" width="5%" style="">{{__('Cantidad')}}</th>
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

                    <tr style="border-top: 1px solid black">
                        <td style="text-align: center; border-right: 1px solid black; background-color:#DCE6F1">{{ $numero }}</td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:#DCE6F1">{{ $det['cantidad']}}</td>
                        <td style="text-align: left; border-right: 1px solid black; background-color:#DCE6F1"><strong ><FONT FACE="times new roman" style=" color: black" SIZE=2>{{ $det['producto']}}</FONT><strong ></td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['costo_venta']}}</td>
                            {{-- @if($id_descuento == 1)
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['costo_unitario']}}</td>
                        @endif
                        @if($id_descuento == 2)
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['costo_mayorista']}}</td>
                        @endif
                        @if($id_descuento == 3)
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['costo_preferencial']}}</td>
                        @endif --}}
                        <td style="text-align: left; border-right: 1px solid black; text-align: center; background-color:<?php echo $color6; ?>">{{ $det['sub_total'] }}</td>
                    </tr>
                    {{-- <tr style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;">
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: center; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: left; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{__('Tiempo de Entrega: ')}}{{ $det['tiempo_entrega']}}</td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        @if($xx > 0)
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>"></td>
                        @endif
                        <td style="text-align: left; border-right: 1px solid black; text-align: center; background-color:<?php echo $color6; ?>"></td>
                    </tr> --}}
                    @if($numero2==9)
                    @php
                        $numero2=0;
                    @endphp
                    @endif
                    @php
                        $color7 = $color5;
                        $color5 = $origenVM == $det['origen']  ? $color5 : $color6;
                        $color6 = $origenVM == $det['origen']  ? $color6 : $color7;
                        $totalCaja += $origenVM == $det['origen'] ? 0 : $det['caja'];
                        $totalBanco += $origenVM == $det['origen'] ? 0 : $det['banco'];
                        $totalTarjeta += $origenVM == $det['origen'] ? 0 : $det['tarjeta'];
                        $origenVM = $det['origen'];
                        $numero++;

                        $numero2=$numero2+1;
                        $tamaño=$tamaño+1;
                        $tamaño2=$tamaño2+1;
                        $valor=0;
                    @endphp
                    
                    @endforeach
<!-- </div> -->
                </tbody>
            </table>
            @if($descuento > 0)
                <div class="container" style="width:99.2%; height:16">
                    <div class="AA" style="width:32.4%">
                        &nbsp;
                    </div>
                    <div class="AA" style="width:50.9%;text-align: right; border-bottom: 1px solid black;border-top: 1.1px solid black;border-left: 1px solid black; background-color:<?php echo $color6; ?>">
                    <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{__('SUB-TOTAL BOLIVIANOS: ')}}</FONT></strong>
                    </div>
                    <!-- <td style=""></td> -->
                    <div class="AA" style="width:17.1%;text-align: right; border-right: 1px solid black;border-top: 1.1px solid black; border-bottom: 1px solid black;border-left: 1px solid black;background-color:<?php echo $color6; ?>">
                    
                    <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{ $sub_total }}</FONT></strong>
                    </div>
                </div>
                
                <div class="container" style="width:99.2%; height:16">
                    <div class="BB" style="width:32.4%">
                        &nbsp;
                    </div>
                    <div class="BB" style="width:50.9%;text-align: right; border-bottom: 1px solid black;border-left: 1px solid black; background-color:<?php echo $color6; ?>">
                    <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3> {{__('DESCUENTO BS.: ')}}</FONT></strong>
                    </div>
                    <!-- <td style=""></td> -->
                    <div class="BB" style="width:17.1%;text-align: right; border-right: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black;background-color:<?php echo $color6; ?>">
                    
                    <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{ $descuento }}</FONT></strong>
                    </div>
                </div>
                @endif
                <div class="container" style="width:99.2%; height:16">
                    <div class="CC" style="width:32.4%">
                        &nbsp;
                    </div>
                    <div class="CC" style="width:50.9%;text-align: right; border-bottom: 1px solid black;border-left: 1px solid black; background-color:<?php echo $color6; ?>">
                    <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{__('TOTAL BOLIVIANOS: ')}}</FONT></strong>
                    </div>
                    <!-- <td style=""></td> -->
                    <div class="CC" style="width:17.1%;text-align: right; border-right: 1px solid black;border-bottom: 1px solid black;border-left: 1px solid black;background-color:<?php echo $color6; ?>">
                    <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{ $total }}</FONT></strong>
                    </div>
                </div>
                <br>
                @if($valor == 0)
                        @if($numero2 > 7)
                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                        @php
                        $valor=1;
                        @endphp
                        @endif
                @endif

                <i><strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{__('INCLUYE IMPUESTOS DE LEY')}}</FONT></strong></i>
                <br>
                <div class="container" style="width:99.2%">
                    <div id="lateral" class="DD" style="width:30%; text-align:left" >    
                        <div style="font-weight: normal">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{__('Tiempo de entrega:')}}</FONT></strong>
                        </div>
                        <div style="font-weight: normal">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{__('Forma de Pago:')}}</FONT></strong>
                        </div>
                        <div style="font-weight: normal">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{__('Lugar de Entrega:')}}</FONT></strong>
                        </div>
                        <div style="font-weight: normal">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{__('Nota:')}}</FONT></strong>
                        </div>
                    </div> 
                    <div id="lateral" class="DD" style="width:70%; text-align:left">    
                        <div style="font-weight: normal">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{ $tiempo_entrega }}</FONT></strong>
                        </div>
                        <div style="font-weight: normal">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{ $tipo_pago }}</FONT></strong>
                        </div>
                        <div style="font-weight: normal">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{ $lugar_entrega }}</FONT></strong>
                        </div>
                        <div style="font-weight: normal">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{ $nota }}</FONT></strong>
                        </div>
                    </div> 
                    </div>
                    <br>
                    @if($valor == 0)
                        @if($numero2 > 6)
                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                        @php
                        $valor=1;
                        @endphp
                        @endif
                    @endif
                    <table width="100%" style="font-weight: normal;text-align:center">
                        <tr width="100%">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{ $personal }}</FONT></strong>
                        </tr>
                        {{-- <tr width="100%">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{__('Depto. Comercial KEDEM CONTROL')}}</FONT></strong>
                        </tr> --}}
                    </table>
                    <!-- <div style="font-weight: normal;text-align:center;width: 95%">
                        <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{ $personal }}</FONT></strong>
                    </div>
                    <div style="font-weight: normal;text-align:center;width: 95%">
                        <strong ><FONT FACE="times new roman" style=" color: black" SIZE=3>{{__('Depto. Comercial KEDEM CONTROL')}}</FONT></strong>
                    </div> -->
                    <!-- @if($valor == 0)
                        @if($numero2 > 4)
                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                        @else
                        <br>
                        @endif
                    @else
                        <br>
                    @endif
                    <div  style="width: 45%;padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: left; border: 2px solid <?php echo $color2; ?>; border-radius: 5px, 5px">
                        <div style="font-weight: normal" >
                            <i><strong><FONT FACE="times new roman" style="color: black" SIZE=3>{{__('CUENTA BANCARIA: 4025614902')}}</FONT></strong></i>
                        </div> 
                        <div style="font-weight: normal" >
                            <i><strong><FONT FACE="times new roman" style="color: black" SIZE=3>{{__('BANCO MERCANTIL SANTA CRUZ')}}</FONT></strong></i>
                        </div> 
                        <div style="font-weight: normal" >
                            <i><strong><FONT FACE="times new roman" style="color: black" SIZE=3>{{__('CAJA DE AHORRO')}}</FONT></strong></i>
                        </div> 
                        <div style="font-weight: normal" >
                            <i><strong><FONT FACE="times new roman" style="color: black" SIZE=3>{{__('CUENTA EN: BOLIVIANOS')}}</FONT></strong></i>
                        </div> 
                        <div style="font-weight: normal" >
                            <i><strong><FONT FACE="times new roman" style="color: black" SIZE=3>{{__('NOMBRE: IVAR ALAIN CALLAPA CARVAJAL')}}</FONT></strong></i>
                        </div> 
                        <div style="font-weight: normal" >
                            <i><strong><FONT FACE="times new roman" style="color: black" SIZE=3>{{__('CI: 4669470 SC')}}</FONT></strong></i>
                        </div>    
                        
                    </div> -->
            </div>
        </div>

    </body>
</html>


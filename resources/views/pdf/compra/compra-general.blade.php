<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">  
    <title>Compra</title>
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
        margin: 0.7cm 0.7cm 14.7cm 0.7cm;
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
    <table class="table-head table-borderless" style="padding-bottom: 7px" width="100%">
        <tr style="">
            <th rowspan="1"  width="10%">
                    {{-- <img src=<?php echo ( !is_array(@getimagesize("../public_html/img/sitnorte-centralcell.png")) ? "../public_html/img/sitnorte-centralcell.png" : str_replace(" ","%20","../public_html/img/sitnorte-centralcell.png")) ?> height="100"> --}}
                    {{-- <img src=<?php echo ( !is_array(@getimagesize("img/logo.png")) ? "img/logo.png" : str_replace(" ","%20","img/logo.png")) ?> height="50"> --}}
                    {{-- <img src=<?php echo $foto_empresa != null ? "../public_html/img/mi_empresa/".$foto_empresa : '""' ?> height="50"> --}}
                    {{-- <img src=<?php echo $foto_empresa != null ? "img/mi_empresa/".$foto_empresa : '""' ?> height="50"> --}}
                    <img src=<?php echo $foto_empresa != null ? "img/logo/".$foto_empresa : '""' ?> height="70" width="180">


            </th>
            <th width="40%" style=" text-align: right;">
                <br><br>
                <div style="font-weight: normal ;" >
                <strong ><FONT FACE="times new roman" style="color: black" SIZE=4>{{'NOTA DE COMPRA '}} - {{$id}}</FONT></strong >
                </div>    
                <!-- <div style="font-weight: normal" >
                    <strong ><FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{ '$tipo_proveedor' }}{{__('-')}}{{ '$tipo_pago' }}</FONT></strong >
                </div> -->
            </th>
            <th width="50%" style="text-align:right">
                {{-- <div style="font-weight: normal">
                <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=3>{{__('Nombre: ')}}</FONT></strong>
                </div> --}}
                {{-- <div style="font-weight: normal" >
                <strong ><FONT  FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('Cod Tienda: ')}}</FONT></strong>
                </div> --}}
                {{-- <div style="font-weight: normal" >
                <strong><FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('Dirección: ')}}</FONT></strong>
                </div> --}}
                {{-- <div style="font-weight: normal" >
                <strong><FONT FACE="times new roman" style="color: <?php echo $color5; ?>" SIZE=3>{{__('Cod. Almacén: ')}}</FONT></strong>
                </div> --}}
            </th>
        </tr>
        <!-- <tr style="font-weight: normal;text-align:center;width:100%">
            <td colspan=4>
                <strong style="width:70%;" ><FONT FACE="times new roman" style=" border: 0.4px solid black; color: black;background-color: #FCD5B4" SIZE="4">{{__('    DATOS DE LA COMPRA ')}}</FONT></strong>
            </td>
        </tr> -->
        <tr style="">
            <td colspan=4>
                <div class="container2" style="padding-top: 5px; padding-bottom: 5px; padding-left: 10px; text-align: center; border: 2px solid <?php echo $color1; ?>; border-radius: 5px, 5px">
                    <div id="lateral" class="A" style="width:25%; text-align:left" >    
                        <div style="font-weight: normal">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=2>{{__('PROVEEDOR: ')}}</FONT></strong>
                        </div>
                        <div style="font-weight: normal">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=2>{{__('TIPO DE PAGO: ')}}</FONT></strong>
                        </div>
                    </div> 
                    <div id="lateral" class="A" style="width:25%; text-align:left">    
                        <div style="font-weight: normal">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=2>{{ $proveedor }}</FONT>
                        </div>
                        <div style="font-weight: normal">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=2>{{ $tipo_pago }}</FONT>
                        </div>
                    </div> 
                    <div id="lateral" class="A" style="width:25%; text-align:right" >    
                        <div style="font-weight: normal">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=2>{{__('FECHA: ')}}</FONT></strong>
                        </div>
                        @if( $forma_pago != 'Cuenta por Cobrar')
                        <div style="font-weight: normal">
                            <strong ><FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=2>{{__('FORMA DE PAGO: ')}}</FONT></strong>
                        </div>
                        @endif
                    </div> 
                    <div id="lateral" class="A" style="width:23%; text-align:right">    
                        <div style="font-weight: normal">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=2>{{ $fecha }}</FONT>
                        </div>
                        @if( $forma_pago != 'Cuenta por Cobrar')    
                        <div style="font-weight: normal">
                            <FONT FACE="times new roman" style=" color: <?php echo $color5; ?>" SIZE=2>{{ $forma_pago }}</FONT>
                        </div>
                        @endif
                    </div>
                </div>
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

    @php
        $saldo_por_pagar = $total+$saldo_anterior-$pago
    @endphp
    <div class="container">
        <div class="col-lg-7 col-md-7" style=" color: black">

                
            
                    
            <table class="table" style="">
                <thead style="border: 1px solid black; background-color:<?php echo $color1; ?>">
                    <tr> 
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="5%" style="">{{__('Items')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="15%" style="">{{__('Categoria')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="30%" style="">{{__('Nombre')}}</th>
                        {{-- <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="15%" style="">{{__('Tienda')}}</th> --}}
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="10%" style="">{{__('Precio')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="5%" style="">{{__('Cantidad')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="5%" style="">{{__('Descuento')}}</th>
                        <th style="vertical-align: middle; border-right: 2px solid black ;color:#FFFFFF" width="12%" style="">{{__('Sub Total')}}</th>
                    </tr>
                </thead>
                
                @foreach($detalles as $det)
                    
                    
                <tbody  style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black">
                    
                    <tr style="border-top: 1px solid black">
                        <td style="text-align: center; border-right: 1px solid black;">{{ $numero }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['categoria'] }}</td>
                        <td style="text-align: left; border-right: 1px solid black;">{{ $det['articulo'] }}</td>
                        <td style="text-align: center; border-right: 1px solid black;">{{ $det['costo_compra']}}</td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['cantidad']}}</td>
                        <td style="text-align: right; border-right: 1px solid black; background-color:<?php echo $color6; ?>">{{ $det['descuento']}}</td>
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
                        $tamaño=$tamaño+1;
                        $tamaño2=$tamaño2+1;
                    @endphp
                    
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="2" style="border: 1px solid black; text-align: right">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=1> {{__('SUB-TOTAL ')}} :</FONT></strong>
                        </td>
                        <td  style="border: 1px solid black; text-align: center">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=1>{{number_format($sub_total,2)}} Bs</FONT></strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="2" style="border: 1px solid black; text-align: right">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=1> {{__('DESCUENTO ')}} :</FONT></strong>
                        </td>
                        <td  style="border: 1px solid black; text-align: center">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=1>{{ number_format($descuento,2)  }} Bs</FONT></strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td  colspan="2" style="border: 1px solid black; text-align: right">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=1> {{__('TOTAL ')}}:</FONT></strong>
                        </td>
                        <td   style="border: 1px solid black; text-align: center">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=1>{{ number_format($total,2)  }} Bs</FONT></strong>
                        </td>
                    </tr>
                    @if($forma_pago == 'Mixta')
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="2" style="border: 1px solid black; text-align: right">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=1> {{__('TOTAL EFECT. ')}} :</FONT></strong>
                        </td>
                        <td  style="border: 1px solid black; text-align: center">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=1>{{ number_format($total_efectivo,2)  }} Bs</FONT></strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td  colspan="2" style="border: 1px solid black; text-align: right">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=1> {{__('TOTAL DEP. ')}}:</FONT></strong>
                        </td>
                        <td   style="border: 1px solid black; text-align: center">
                            <strong ><FONT FACE="times new roman" style=" color: black" SIZE=1>{{ number_format($total_deposito,2)  }} Bs</FONT></strong>
                        </td>
                    </tr>
                    @endif
                </tfoot>
            </table>
            </div>
        </div>

    </body>
                    <!-- <footer>
                        <img src="img/mi_empresa/k1.jpg" width="18%" height="60%"/>
                        <img src="img/mi_empresa/k2.jpg" width="18%" height="60%"/>
                        <img src="img/mi_empresa/k3.jpg" width="18%" height="60%"/>
                        <img src="img/mi_empresa/k4.jpg" width="18%" height="60%"/>
                        <img src="img/mi_empresa/k5.jpg" width="18%" height="60%"/>
                    </footer> -->

</html>


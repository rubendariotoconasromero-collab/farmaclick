<table>
    @php
        $logotipo = "https://i.ibb.co/N6hPGQj/logogemgloo2.png";
    @endphp
    @php
        $color1 = "#68D3AB";
        $color2 = "#bfbfbf";
        $color3 = "#dedbb6";
    @endphp
    <thead>
        <tr>
            <th colspan="6"  style="text-align: center; font-size: 20px;"><b>{{ $titulo }}</b></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th style="text-align: right; font-weight: bold">Nombre:</th>
            <th>{{$nombre_empresa}}</th>
        </tr>
        <tr>
            <td style="text-align: left"><b>Fecha Incio:</b> &nbsp; <span style="font-weight: bold">{{$fecha_inicio}}</span></td>
            <th colspan="3"></th>
            <th style="text-align: right; font-weight: bold">Codigo de Tienda:</th>
            <th>{{$cod_empresa}}</th>
        </tr>
        <tr>
            <th style="text-align: left;"><b>Fecha Final:</b> &nbsp; {{$fecha_fin}}</th>
            <th colspan="3"></th>
            <th style="text-align: right; font-weight: bold">Dirección:</th>
            <th>{{$direccion_empresa}}</th>
        </tr>
        
        <tr></tr>
        <tr>
            {{-- <td colspan="2" style="text-align: left; background-color: #68D3AB; font-weight: normal; border: #000 solid 1px; width: 30px;"><b>Total BS.: </b>{{$detalles1[0]['totalV']}}</td> --}}
            {{-- <td colspan="3"></td>
            <td style="text-align: left; background-color: #68D3AB; font-weight: normal; border: #000 solid 1px; width: 30px;"><b>Cantidad: </b>{{$cantidad[0]['cantidad']}}</td> --}}
        </tr>               
    </thead>
    @foreach($venta_pago as $pago)   
    @php $numero = 1; @endphp
    <table class="table" style="">
        <thead style="border: 1px solid black">
            <tr> 
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="120px"><strong>{{__('Cliente')}}</strong></th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="120px"><strong>{{__('Tienda')}}</strong></th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="120px"><strong>{{__('Fecha Inico')}}</strong></th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="120px"><strong>{{__('Fecha Final')}}</strong></th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="120px"><strong>{{__('Monto')}}</strong></th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="120px"><strong>{{__('Saldo')}}</strong></th>
            </tr>
            <tr> 
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; " width="120px">{{$pago['cliente']}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; " width="120px">{{$pago['tienda']}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; " width="120px">{{$pago['fecha']}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; " width="120px">{{$pago['fecha_final']}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; " width="120px">{{$pago['monto']}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; " width="120px">{{$pago['saldo']}}</th>
            </tr>
        </thead>
        
        <tbody  style="border: 1px solid black; border: 1px solid black; border-bottom: 1px solid black">
            @php $numero = 1; @endphp
            @foreach($detalles as $det)
                @if($pago['id']==$det['id_pago'])
                    @if($pago['monto']!=$det['saldo'])
                    <tr style="border: 1px solid black">
                        <td colspan="2" style="text-align: left; border: 1px solid black;">{{ $det['descripcion']}}</td>
                        <td style="text-align: center; border: 1px solid black;">{{__('Pago')}} {{ $numero }}</td>
                        <td style="text-align: center; border: 1px solid black;">{{ $det['fecha']}}</td>
                        <td style="text-align: right; border: 1px solid black;">{{ $det['amortizacion'] }}</td>
                        <td style="text-align: right; border: 1px solid black;">{{ $det['saldo']}}</td>
                    </tr>
                        {{-- @if($det == [])
                            <tr style="border-top: 1px solid black">
                                <td colspan="6" style="text-align: left; border-right: 1px solid black;">{{__('No se ha realizado ningun pago')}}</td>
                            </tr>
                        @endif --}}
                    @php $numero++; @endphp
                    @endif
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr></tr>
        </tfoot>
    </table>
    @endforeach
    @if($venta_pago != [])
    <table>
        <tr>
            <th colspan="5"></th> 
            <th style="text-align: right; font-size: 10px">Consulta: {{$fecha_impresion}}</th>
        </tr>
        <tr></tr>
    </table>
    @endif
    @if($venta_pago === [])
    <table class="table" style="">
        <thead style="border: 1px solid black">
            <tr> 
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="200px"><strong>{{__('Cliente:')}}</strong></th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="120px"><strong>{{__('Descuento:')}}</strong></th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="120px"><strong>{{__('Sub Total:')}}</strong></th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="120px">{{__('Cantidad')}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="120px">{{__('PU')}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="120px">{{__('Sub Total')}}</th>
            </tr>
        </thead>
        <tbody  style="border: 1px solid black;">
            <tr style="border: 1px solid black">
                <td colspan="6" style="text-align: left; border: 1px solid black;">{{__('No se encuentran registro entre estas fechas')}}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr></tr>
            <tr>
                <th colspan="5"></th> 
                <th style="text-align: right; font-size: 10px">Consulta: {{$fecha_impresion}}</th>
            </tr>
            <tr></tr>
        </tfoot>
    </table>
    @endif
</table>
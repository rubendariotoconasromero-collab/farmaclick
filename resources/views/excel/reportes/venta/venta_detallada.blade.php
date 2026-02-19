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
            <td colspan="2" style="text-align: left; background-color: #68D3AB; font-weight: normal; border: #000 solid 1px; width: 30px;"><b>Total BS.: </b>{{$detalles2[0]['totalV']}}</td>
            {{-- <td colspan="3"></td>
            <td style="text-align: left; background-color: #68D3AB; font-weight: normal; border: #000 solid 1px; width: 30px;"><b>Cantidad: </b>{{$cantidad[0]['cantidad']}}</td> --}}
        </tr>
        <tr></tr>           
    </thead>
    @php
        $numero = 1;
    @endphp
    @foreach($venta as $comp)     
    <table class="table" style="">
        <thead style="border: 1px solid black">
            <tr></tr>
            <tr> 
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="200px"><strong>{{__('Cliente:')}}</strong> {{$comp['cliente']}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="150px"><strong>{{__('Descuento:')}}</strong> {{$comp['descuento']}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="150px"><strong>{{__('Total:')}}</strong> {{$comp['total']}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="150px">{{__('Cantidad')}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="150px">{{__('PU')}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="150px"><strong>{{__('Sub Total:')}}</strong> {{$comp['sub_total']}}</th>
            </tr>
        </thead>
        
        <tbody  style="border-left: 1px solid black; border: 1px solid black; border-bottom: 1px solid black">
            @foreach($detalles as $det)
            @if($comp['id']==$det['id_venta'])
            <tr style="border-top: 1px solid black">
                <td colspan="3" style="text-align: left; border: 1px solid black;">{{ $det['producto']}}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $det['cantidad']}}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $det['costo_venta'] }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $det['sub_total'] }}</td>
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
            <tr></tr>
            <tr> 
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="150px"><strong>{{__('Cliente:')}}</strong></th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="150px"><strong>{{__('Descuento:')}}</strong></th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="150px"><strong>{{__('Sub Total:')}}</strong></th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="150px">{{__('Cantidad')}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="150px">{{__('PU')}}</th>
                <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" width="150px">{{__('Sub Total')}}</th>
            </tr>
        </thead>
        <tbody  style="border-left: 1px solid black; border: 1px solid black; border-bottom: 1px solid black">
            <tr style="border-top: 1px solid black">
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
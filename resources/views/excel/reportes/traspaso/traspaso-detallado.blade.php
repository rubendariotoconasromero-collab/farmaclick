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
            <th></th>
            <td colspan="2" style="text-align: left" width="150px"><b>Fecha Incio:</b> &nbsp; <span style="font-weight: bold">{{$fecha_inicio}}</span></td>
            <th></th>
            <th style="text-align: right; font-weight: bold">Codigo de Tienda:</th>
            <th>{{$cod_empresa}}</th>
        </tr>
        <tr>
            <th></th>
            <th colspan="2" style="text-align: left;"><b>Fecha Final:</b> &nbsp; {{$fecha_fin}}</th>
            <th></th>
            <th style="text-align: right; font-weight: bold">Dirección:</th>
            <th>{{$direccion_empresa}}</th>
        </tr>             
    </thead>
</table>
@php
$numero = 1;
@endphp
@foreach($traspaso as $trasp)     
<table class="table" style="border: 1px solid black">
    <thead style="border: 1px solid black">
        <tr> 
            <th colspan="2" style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="100px"><strong>{{__('Fecha:')}}</strong> {{$trasp['fecha']}}</th>
            <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="250px"><strong>{{__('Tienda Origen:')}}</strong> {{$trasp['tienda1']}}</th>
            <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="250px"><strong>{{__('Tienda Destion:')}}</strong> {{$trasp['tienda2']}}</th>
            <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>; text-align: left" width="150px"><strong>{{__('Glosa:')}}</strong> {{$trasp['glosa']}}</th>
            <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color1; ?>" width="150px"><strong>{{__('Usuario:')}}</strong> {{$trasp['name']}}</th>
        </tr>
    </thead>
    <thead style="border: 1px solid black">
        <tr> 
            <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" ><strong>{{__('C. Producto')}}</strong></th>
            <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" ><strong>{{__('C. Proveedor')}}</strong></th>
            <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" ><strong>{{__('Categoria')}}</strong></th>
            <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" ><strong>{{__('Marca')}}</strong></th>
            <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>; text-align: center" ><strong>{{__('Producto')}}</strong></th>
            <th style="vertical-align: middle; border: 2px solid black; font-weight: normal; background-color:<?php echo $color2; ?>" ><strong>{{__('Cantidad')}}</strong></th>
        </tr>
    </thead>
    
    <tbody  style="border-left: 1px solid black; border: 1px solid black; border-bottom: 1px solid black">
        @foreach($detalles as $det)
        @if($trasp['id']==$det['id_traspaso'])
        <tr style="border-top: 1px solid black">
            <td style="text-align: left; border: 1px solid black;">{{ $det['cod_producto']}}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $det['cod_proveedor']}}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $det['categoria'] }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $det['marca']}}</td>
            <td style="text-align: left; border: 1px solid black;">{{ $det['producto'] }}</td>
            <td style="text-align: center; border: 1px solid black;">{{ $det['cantidad'] }}</td>
        </tr>
        @endif
        @php
            $numero++;
        @endphp
        @endforeach
    </tbody>
</table>
@endforeach
<table>
    <tr>
        <th colspan="3"></th> 
        <th colspan="3" style="text-align: right; font-size: 10px">Consulta: {{$fecha_impresion}}</th>
    </tr>
    <tr></tr>
</table>
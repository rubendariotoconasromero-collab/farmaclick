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
            <th colspan="5"  style="text-align: center; font-size: 20px;"><b>{{ $titulo }}</b></th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: right; font-weight: bold">Nombre: {{$nombre_empresa}}</th>
            <th style="text-align: right; font-weight: bold">Codigo de Tienda:</th>
            <th>{{$cod_empresa}}</th>
            <th style="text-align: right; font-weight: bold">Dirección: {{$direccion_empresa}}</th>
        </tr>
        
        <tr></tr>
        <tr>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 30px;"><b>Nº</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Cliente</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Teléfono</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Dirección</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Descuento</b></th>
        </tr>                
    </thead>
    <tbody>                        
        @php
            $numero = 1;
        @endphp
        @foreach($detalles as $detalle)
            <tr>
                <td style="text-align: center;border: #000 solid 1px;">{{$numero}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['cliente']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['telefono']}}</td>  
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['direccion']}}</td>
                @if($detalle['descuento'] == 1)
                <td style="text-align: center; border: 1px solid black;">{{__('Unitario')}}</td>
                @endif
                @if($detalle['descuento'] == 2)
                <td style="text-align: center; border: 1px solid black;">{{__('Mayorista')}}</td>
                @endif
                @if($detalle['descuento'] == 3)
                <td style="text-align: center; border: 1px solid black;">{{__('Preferencial')}}</td>
                @endif
            </tr>
            @php
            $numero++;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr></tr>
        <tr>
            <th colspan="4"></th> 
            <th style="text-align: right; font-size: 10px">Consulta: {{$fecha_impresion}}</th>
        </tr>
        <tr></tr>
    </tfoot>
</table>
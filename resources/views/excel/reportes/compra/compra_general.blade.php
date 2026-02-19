<table>
    @php
        $logotipo = "https://i.ibb.co/N6hPGQj/logogemgloo2.png";
    @endphp
    @php
        $color1 = "#68D3AB";
        $color2 = "#bfbfbf";
        $color3 = "#dedbb6";
        $numero = 1;
    @endphp
    <thead>
        <tr>
            <th colspan="7"  style="text-align: center; font-size: 20px;"><b>{{ $titulo }}</b></th>
        </tr>
        <tr>
            <th colspan="5"></th>
            <th style="text-align: right; font-weight: bold">Nombre:</th>
            <th>{{$nombre_empresa}}</th>
        </tr>
        <tr>
            <th></th>
            <td colspan="3" style="text-align: left"><b>Fecha Incio:</b> &nbsp; <span style="font-weight: bold">{{$fecha_inicio}}</span></td>
            <th></th>
            <th style="text-align: right; font-weight: bold">Codigo de Tienda:</th>
            <th>{{$cod_empresa}}</th>
        </tr>
        <tr>
            <th></th>
            <th colspan="3" style="text-align: left;"><b>Fecha Final:</b> &nbsp; {{$fecha_fin}}</th>
            <th></th>
            <th style="text-align: right; font-weight: bold">Dirección:</th>
            <th>{{$direccion_empresa}}</th>
        </tr>
        
        <tr></tr>
        <tr>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 30px;"><b>Nº</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Fecha</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 250px;"><b>Proveedor</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Usuario</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Sub Total</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 15px; width: 150px;"><b>Descuento</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 15px; width: 150px;"><b>Total</b></th>
        </tr>                
    </thead>
    <tbody>
        @foreach($detalles as $detalle)
            <tr>
                <td style="text-align: center;border: #000 solid 1px;">{{$numero}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['fecha']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['proveedor']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['usuario']}}</td>  
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['sub_total']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{ $detalle['descuento'] }}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{ $detalle['total'] }}</td>
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
            <th colspan="3" style="text-align: right; font-size: 10px">Consulta: {{$fecha_impresion}}</th>
        </tr>
        <tr></tr>
    </tfoot>
</table>
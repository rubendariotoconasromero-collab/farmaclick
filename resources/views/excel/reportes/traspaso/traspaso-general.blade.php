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
            <th colspan="7"  style="text-align: center; font-size: 20px;"><b>{{ $titulo }}</b></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th style="text-align: right; font-weight: bold">Nombre:</th>
            <th>{{$nombre_empresa}}</th>
        </tr>
        <tr>
            <th></th>
            <td colspan="2" style="text-align: left"><b>Fecha Incio:</b> &nbsp; <span style="font-weight: bold">{{$fecha_inicio}}</span></td>
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
        
        <tr></tr>
        <tr>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 30px;"><b>Nº</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Fecha</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 250px;"><b>Tienda Origen</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Tienda Destino</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Glosa</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 15px; width: 150px;"><b>Usuario</b></th>
        </tr>                
    </thead>
    <tbody>                        
        @php
            $numero = 1;
        @endphp
        @foreach($detalles as $detalle)
            <tr>
                <td style="text-align: center;border: #000 solid 1px;">{{$numero}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['fecha']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['tienda1']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['tienda2']}}</td>  
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['glosa']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{ $detalle['usuario'] }}</td>
            </tr>
            @php
            $numero++;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr></tr>
        <tr>
            <th colspan="3"></th> 
            <th colspan="3" style="text-align: right; font-size: 10px">Consulta: {{$fecha_impresion}}</th>
        </tr>
        <tr></tr>
    </tfoot>
</table>
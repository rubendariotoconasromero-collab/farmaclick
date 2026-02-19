<table>
    @php
        $logotipo = "https://i.ibb.co/N6hPGQj/logogemgloo2.png";
    @endphp
    @php
        $color1 = "#68D3AB";
        $color2 = "#bfbfbf";
        $color3 = "#dedbb6";
        $stock = 0;
    @endphp
    <thead>
        <tr>
            <th colspan="12"  style="text-align: center; font-size: 20px;"><b>{{ $titulo }}</b></th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: right; font-weight: bold">Nombre:</th>
            <th colspan="2">{{$nombre_empresa}}</th>
            <th style="text-align: right; font-weight: bold">Dirección:</th>
            <th>{{$direccion_empresa}}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 30px;"><b>Nº</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 50px;"><b>Nombre Comercial</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 50px;"><b>Nombre Generico</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>F. Vencimiento</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Ubicación</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Precio Unidad</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Precio Blister</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Precio Caja</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Stock</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Laboratorio</b></th>
        </tr>                
    </thead>
    <tbody>                        
        @php
            $numero = 1;
        @endphp
        @foreach($detalles as $detalle)
            <tr>
                <td style="text-align: center;border: #000 solid 1px;">{{$numero}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['nombre_comercial']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['nombre_generico']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['fecha_vecimiento']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['ubicacion']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['costo_unitario']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['precio_blister']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['precio_caja']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['stock']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['proveedor']}}</td>

            </tr>
            @php
            $numero++;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr></tr>
        <tr>
            <th colspan="11"></th> 
            {{-- <th colspan="3" style="text-align: right; font-size: 10px">Consulta: {{$fecha_impresion}}</th> --}}
        </tr>
        <tr></tr>
    </tfoot>
</table>
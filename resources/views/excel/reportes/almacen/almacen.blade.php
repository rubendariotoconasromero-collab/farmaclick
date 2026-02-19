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
            <th style="text-align: right; font-weight: bold">Nombre:</th>
            <th colspan="2">{{$nombre_empresa}}</th>
            <th style="text-align: right; font-weight: bold">Codigo de Tienda:</th>
            <th>{{$cod_empresa}}</th>
            <th style="text-align: right; font-weight: bold">Dirección:</th>
            <th>{{$direccion_empresa}}</th>
        </tr>
        
        <tr></tr>
        <tr>
            {{-- <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 30px;"><b>Nº</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 50px;"><b>Cod. Producto</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 50px;"><b>Cod. Proveedor</b></th> --}}
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Categoria</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Nombre</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Marca</b></th>
            {{-- <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Costo de Compra</b></th> --}}
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Precio Unitario</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Precio Mayorista</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Precio Preferencial</b></th>
            {{-- <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Tienda</b></th> --}}
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Stock</b></th>
        </tr>                
    </thead>
    <tbody>                        
        @php
            $numero = 1;
        @endphp
        @foreach($detalles as $detalle)
            <tr>
                {{-- <td style="text-align: center;border: #000 solid 1px;">{{$numero}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['cod_producto']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['cod_proveedor']}}</td> --}}
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['categoria']}}</td>  
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['nombre']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['marca']}}</td>
                {{-- <td style="text-align: center; border: #000 solid 1px;">{{$detalle['costo_compra']}}</td> --}}
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['costo_unitario']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['costo_mayorista']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['costo_preferencial']}}</td>
                {{-- <td style="text-align: center; border: #000 solid 1px;">{{$detalle['tienda']}}</td> --}}
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['stock']}}</td>
            </tr>
            @php
            $numero++;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr></tr>
        <tr>
            <th colspan="6"></th> 
            <th style="text-align: right; font-size: 10px">Consulta: {{$fecha_impresion}}</th>
        </tr>
        <tr></tr>
    </tfoot>
</table>
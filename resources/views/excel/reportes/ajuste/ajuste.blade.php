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
            <th colspan="12"  style="text-align: center; font-size: 20px;"><b>{{ $titulo }}</b></th>
        </tr>
        <tr>
            <th colspan="2" style="text-align: right; font-weight: bold">Nombre:</th>
            <th colspan="2">{{$nombre_empresa}}</th>
            <th colspan="2" style="text-align: right; font-weight: bold">Codigo de Tienda:</th>
            <th>{{$cod_empresa}}</th>
            <th></th>
            <th style="text-align: right; font-weight: bold">Dirección:</th>
            <th>{{$direccion_empresa}}</th>
        </tr>
        
        <tr></tr>
        <tr>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 30px;"><b>Nº</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Categoria</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Producto</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Marca</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 50px;"><b>Stock</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 70px;"><b>Stock Anterior</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 70px;"><b>Stock Actual</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Motivo</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 100px;"><b>Costode Compra</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 100px;"><b>Precio Unitario</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 100px;"><b>Precio Mayorista</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 100px;"><b>Precio Preferencial</b></th>
        </tr>                
    </thead>
    <tbody>                        
        @php
            $numero = 1;
        @endphp
        @foreach($detalles as $detalle)
            <tr>
                <td style="text-align: center;border: #000 solid 1px;">{{$numero}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['categoria']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['producto']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['marca']}}</td>  
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['stock']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['stock_anterior']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['stock_actual']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['motivo_ajuste']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['costo_compra']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['costo_unitario']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['costo_mayorista']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['costo_preferencial']}}</td>
            </tr>
            @php
            $numero++;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr></tr>
        <tr>
            <th colspan="9"></th> 
            <th colspan="3" style="text-align: right; font-size: 10px">Consulta: {{$fecha_impresion}}</th>
        </tr>
        <tr></tr>
    </tfoot>
</table>
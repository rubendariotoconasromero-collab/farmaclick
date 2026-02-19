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
            <th colspan="7"  style="text-align: center; font-size: 20px;"><b>{{ $titulo }} - {{ $id }}</b></th>
        </tr>
        <tr>
            <th colspan="5"></th>
            <th style="text-align: right; font-weight: bold">Nombre:</th>
            <th>{{$tienda}}</th>
        </tr>
        <tr>
            <th></th>
            <td colspan="2" style="text-align: left"><b>Cliente:</b> &nbsp; <span style="font-weight: bold">{{$cliente}}</span></td>
            <th><b><span> fecha:</span></b> &nbsp; {{$fecha}}</th>
            <th></th>
            <th style="text-align: right; font-weight: bold">Codigo de Tienda:</th>
            <th>{{$cod_tienda}}</th>
        </tr>
        <tr>
            <th></th>
            <th colspan="2" style="text-align: left;"><b>Tipo de Pago:</b> &nbsp; {{$tipo_pago}}</th>
            <th style="text-align: left;"><b>Forma de Pago:</b> &nbsp; {{$forma_pago}}</th>
            <th></th>
            <th style="text-align: right; font-weight: bold">Dirección:</th>
            <th>{{$tienda_direccion}}</th>
        </tr>

        <tr></tr>
        <tr>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 30px;"><b>Nº</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Categoria</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 250px;"><b>Producto</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Marca</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Precio</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 15px; width: 150px;"><b>Cantidad</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 15px; width: 150px;"><b>Sub Total</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $totalImporte = "0";
            $totalDescuento = "0";
            $totalCaja = "0";
            $totalBanco = "0";
            $totalTarjeta = "0";
            $numero = 0
        @endphp
        @foreach($detalles as $detalle)
            <tr>
                <td style="text-align: center;border: #000 solid 1px;">{{$numero}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['categoria']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['articulo']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['marca']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['costo_venta']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{ $detalle['cantidad'] }}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{ $detalle['sub_total']}}</td>
            </tr>
            @php
            $totalImporte += $detalle['importe'];
            $totalDescuento += $detalle['descuento'];
            $totalCaja += $detalle['caja'];
            $totalBanco += $detalle['banco'];
            $totalTarjeta += $detalle['tarjeta'];
            $numero++;
            @endphp
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5"></th>
            <th style="text-align: right;"><b>Sub Total:</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; font-weight: bold">@php echo number_format($sub_total, 2) @endphp</th>
        </tr>
        <tr>
            <th colspan="5"></th>
            <th style="text-align: right;"><b>Descuento:</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; font-weight: bold">@php echo number_format($descuento, 2) @endphp</th>
        </tr>
        <tr>
            <th colspan="5"></th>
            <th style="text-align: right;"><b>Total:</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; font-weight: bold">@php echo number_format($total, 2) @endphp</th>
        </tr>
        <tr></tr>
        <tr>
            <th colspan="4"></th>
            <th colspan="3" style="text-align: right; font-size: 10px">Consulta: {{$fecha_impresion}}</th>
        </tr>
        <tr></tr>
    </tfoot>
</table>

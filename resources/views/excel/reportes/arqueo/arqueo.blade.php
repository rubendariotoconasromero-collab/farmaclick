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
            <th colspan="13"  style="text-align: center; font-size: 20px;"><b>{{ $titulo }}</b></th>
        </tr>
        <tr>
            <th colspan="11"></th>
            <th style="text-align: right; font-weight: bold">Nombre:</th>
            <th>{{$nombre_empresa}}</th>
        </tr>
        <tr>
            <th></th>
            <td colspan="9" style="text-align: left"><b>Fecha Incio:</b> &nbsp; <span style="font-weight: bold">{{$fecha_inicio}}</span></td>
            <th></th>
            <th style="text-align: right; font-weight: bold">Codigo de Tienda:</th>
            <th>{{$cod_empresa}}</th>
        </tr>
        <tr>
            <th></th>
            <th colspan="9" style="text-align: left;"><b>Fecha Final:</b> &nbsp; {{$fecha_fin}}</th>
            <th></th>
            <th style="text-align: right; font-weight: bold">Dirección:</th>
            <th>{{$direccion_empresa}}</th>
        </tr>

        <tr></tr>
        <tr>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 30px;"><b>Nº</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Fecha Apertura</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Fecha Cierre</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 90px;"><b>Apertura</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 90px;"><b>Ingreso</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 15px; width: 90px;"><b>Egreso</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 15px; width: 90px;"><b>Total</b></th>

            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 15px; width: 90px;"><b>Efectivo</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 15px; width: 90px;"><b>Deposito</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 15px; width: 90px;"><b>Transferencia</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 15px; width: 90px;"><b>Qr</b></th>

            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 15px; width: 150px;"><b>Usuario</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 15px; width: 70px;"><b>Estado</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $numero = 0;
        @endphp
        @foreach($detalles as $detalle)
            <tr>
                <td style="text-align: center;border: #000 solid 1px;">{{$numero++}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['fecha_apertura']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['fecha_cierre']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['apertura']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['ingreso']}}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{ $detalle['egreso'] }}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{ $detalle['total_efectivo'] }}</td>

                <td style="text-align: center;border: #000 solid 1px;">{{ $detalle['tp_efectivo'] }}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{ $detalle['tp_deposito'] }}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{ $detalle['tp_transferencia'] }}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{ $detalle['tp_qr'] }}</td>

                <td style="text-align: center;border: #000 solid 1px;">{{ $detalle['name'] }}</td>
                <td style="text-align: center;border: #000 solid 1px;">{{ $detalle['estado'] }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr></tr>
        <tr>
            <th colspan="6"></th>
            <th colspan="3" style="text-align: right; font-size: 10px">Consulta: {{$fecha_impresion}}</th>
        </tr>
        <tr></tr>
    </tfoot>
</table>

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
            <td style="text-align: left"><b>Fecha Incio:</b> &nbsp; <span style="font-weight: bold">{{$fecha_inicio}}</span></td>
            <th colspan="2"></th>
            <th style="text-align: right; font-weight: bold">Codigo de Tienda:</th>
            <th>{{$cod_empresa}}</th>
        </tr>
        <tr>
            <th></th>
            <th style="text-align: left;"><b>Fecha Final:</b> &nbsp; {{$fecha_fin}}</th>
            <th colspan="2"></th>
            <th style="text-align: right; font-weight: bold">Dirección:</th>
            <th>{{$direccion_empresa}}</th>
        </tr>

        <tr></tr>
        <tr>
            <td colspan="2" style="text-align: left; background-color: #68D3AB; font-weight: normal; border: #000 solid 1px; width: 30px;"><b>Total BS.: </b>{{$detalles1[0]['totalV']}}</td>
            {{-- <td colspan="3"></td>
            <td style="text-align: left; background-color: #68D3AB; font-weight: normal; border: #000 solid 1px; width: 30px;"><b>Cantidad: </b>{{$cantidad[0]['cantidad']}}</td> --}}
        </tr>
        <tr></tr>
        <tr>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 30px;"><b>Nº</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Cliente</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Descuento</b></th>
            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Sub Total</b></th>

            <th style="text-align: center; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Total</b></th>
            <th style="text-align: center; margin: auto; background-color: #68D3AB; border: #000 solid 1px; width: 150px;"><b>Usuario</b></th>
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
                <td style="text-align: center;border: #000 solid 1px;">{{$detalle['descuento']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['sub_total']}}</td>

                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['total']}}</td>
                <td style="text-align: center; border: #000 solid 1px;">{{$detalle['usuario']}}</td>
            </tr>
            @php
            $numero++;
            @endphp
        @endforeach
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

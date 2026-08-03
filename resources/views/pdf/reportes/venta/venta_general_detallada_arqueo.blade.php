@include('pdf.reportes.partials.system-theme')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 0.7cm;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        body {
            background: #FFFFFF;
            font-size: 11px;
            margin: 0;
            padding: 0;
            color: #000;
            font-family: Arial, sans-serif;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table th, .table td {
            padding: 6px;
            vertical-align: middle;
            border: 1px solid #000;
        }
        .no-border {
            border: none !important;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bg-primary { background-color: #001843; color: #fff; }
        .bg-secondary { background-color: #FF0107; color: #fff; }
        .bg-light { background-color: #f8f8f8; }
    </style>
</head>
<body>

<!-- Encabezado del reporte -->
<table class="table" style="margin-bottom: 15px;">
    <tr>
        <th rowspan="2" style="width: 30%; text-align: center; vertical-align: middle;" class="no-border">
            @if($logo_sistema)
                <img src="{{ public_path('img/logo/' . $logo_sistema) }}" height="70" alt="Logo Empresa">
            @else
                <div style="width: 70px; height: 70px; background-color: #f0f0f0; border: 1px solid #ccc;"></div>
            @endif
        </th>
        <th rowspan="2" style="text-align: center; vertical-align: middle; width: 40%;" class="no-border">
            <div style="font-size: 16px; font-weight: bold; color: #001843;">
                {{ $title }}
            </div>
            @if(isset($fecha_inicio_arqueo) || isset($fecha_fin_arqueo))
                <div style="font-size: 11px; color: #555; margin-top: 6px;">
                    @if($fecha_inicio_arqueo) Apertura: {{ $fecha_inicio_arqueo }} @endif
                    @if($fecha_fin_arqueo) | Cierre: {{ $fecha_fin_arqueo }} @endif
                </div>
            @endif
            @if(isset($usuario_arqueo))
                <div style="font-size: 10px; color: #777; margin-top: 4px;">Caja gestionada por: {{ $usuario_arqueo }}</div>
            @endif
        </th>
        <th style="width: 30%;" class="no-border"></th>
    </tr>
</table>

<table class="table">
    <tr class="bg-primary">
        <th>Total Venta: {{ number_format($totalV ?? 0, 2) }} Bs.</th>
    </tr>
    <tr class="bg-secondary" style="color: white;">
        <th>Total Contado: {{ number_format($totalC ?? 0, 2) }} Bs.</th>
        <th>Total Crédito: {{ number_format($totalCr ?? 0, 2) }} Bs.</th>
    </tr>
    <tr style="background-color: #a0d2f3;">
        <th>Total Efectivo: {{ number_format($totalEf ?? 0, 2) }} Bs.</th>
        <th>Total Depósito: {{ number_format($totalDep ?? 0, 2) }} Bs.</th>
    </tr>
</table>

<!-- Listado de ventas -->
@if(isset($venta) && count($venta) > 0)
    @php
        $ventasAgrupadas = collect($venta)->groupBy('id');
    @endphp

    @foreach($ventasAgrupadas as $id_venta => $grupo)
        @php
            $comp = $grupo->first();
            $detallesVenta = $detalles->where('id_venta', $id_venta);
            $paquetesVenta = $detallesPaquete->where('id_venta', $id_venta);
            $tieneDetalles = $detallesVenta->isNotEmpty() || $paquetesVenta->isNotEmpty();
        @endphp

        <table class="table" style="margin-bottom: 15px; page-break-inside: avoid;">
            <thead>
                <tr class="bg-primary">
                    <th style="width: 20%;">Cliente: {{ $comp->cliente ?? 'N/A' }}</th>
                    <th style="width: 12%;">Tipo P.: {{ $comp->tipo_pago ?? 'N/A' }}</th>
                    <th style="width: 15%;">Forma P.: {{ $comp->forma_pago ?? 'N/A' }}</th>
                    <th style="width: 10%;">Desc.: {{ number_format($comp->descuento ?? 0, 2) }}</th>
                    <th style="width: 13%;">Total: {{ number_format($comp->total ?? 0, 2) }} Bs.</th>
                    <th style="width: 10%;">Efectivo: {{ number_format($comp->total_efectivo ?? 0, 2) }} Bs.</th>
                    <th style="width: 10%;">Depósito: {{ number_format($comp->total_deposito ?? 0, 2) }} Bs.</th>
                    <th style="width: 10%;">Fecha: {{ \Carbon\Carbon::parse($comp->fecha)->format('d/m/Y') }}</th>
                </tr>
                <tr class="bg-secondary">
                    <th>Producto / Paquete</th>
                    <th class="text-center">Cantidad</th>
                    <th class="text-center">P.U.</th>
                    <th class="text-center">Sub Total</th>
                    <th class="text-center" colspan="4">Tipo</th>
                </tr>
            </thead>
            <tbody>
                @if($tieneDetalles)
                    <!-- Productos -->
                    @foreach($detallesVenta as $det)
                        <tr>
                            <td>{{ $det->producto ?? 'Producto no disponible' }}</td>
                            <td class="text-center">{{ $det->cantidad ?? 0 }}</td>
                            <td class="text-right">{{ number_format($det->costo_venta ?? 0, 2) }}</td>
                            <td class="text-right">{{ number_format($det->sub_total ?? 0, 2) }}</td>
                            <td class="text-center" colspan="4">Producto</td>
                        </tr>
                    @endforeach

                    @foreach($paquetesVenta as $det)
                        <tr>
                            <td>{{ $det->producto ?? 'Paquete no disponible' }}</td>
                            <td class="text-center">{{ $det->cantidad ?? 0 }}</td>
                            <td class="text-right">{{ number_format($det->costo_venta ?? 0, 2) }}</td>
                            <td class="text-right">{{ number_format($det->sub_total ?? 0, 2) }}</td>
                            <td class="text-center" colspan="4">Paquete</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 12px; font-style: italic; color: #666;">
                            No se encontraron detalles para esta venta
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endforeach
@else
    <table class="table">
        <tr>
            <td class="text-center" style="padding: 25px; background-color: #f8f8f8; color: #666;">
                No hay ventas registradas en este arqueo.
            </td>
        </tr>
    </table>
@endif

</body>
</html>

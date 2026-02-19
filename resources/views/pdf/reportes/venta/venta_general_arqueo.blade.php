<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        @page { margin: 0.7cm; font-family: Arial, sans-serif; font-size: 12px; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #000; background: #fff; margin: 0; padding: 0; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .table th, .table td { padding: 6px; vertical-align: middle; border: 1px solid #000; }
        .no-border { border: none !important; }
        .text-center { text-align: center; }
        .bg-primary { background-color: #001843; color: #fff; }
        .bg-secondary { background-color: #5386a5; color: #fff; }
        .bg-light { background-color: #a0d2f3; }
    </style>
</head>
<body>

<!-- Encabezado -->
<table class="table" style="margin-bottom: 15px;">
    <tr>
        <th rowspan="2" style="width: 30%; text-align: center; vertical-align: middle;" class="no-border">
            @if($logo_sistema)
                <img src="{{ public_path('img/logo/' . $logo_sistema) }}" height="70" alt="Logo">
            @else
                <div style="width: 70px; height: 70px; background: #f0f0f0; border: 1px solid #ccc;"></div>
            @endif
        </th>
        <th rowspan="2" style="text-align: center; vertical-align: middle; width: 40%;" class="no-border">
            <div style="font-size: 16px; font-weight: bold; color: #001843;">{{ $title }}</div>
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

<!-- Totales -->
<table class="table">
    <tr class="bg-primary">
        <th>Total Venta: {{ number_format($totalV ?? 0, 2) }} Bs.</th>
    </tr>
    <tr class="bg-secondary">
        <th>Total Contado: {{ number_format($totalC ?? 0, 2) }} Bs.</th>
        <th>Total Crédito: {{ number_format($totalCr ?? 0, 2) }} Bs.</th>
    </tr>
    <tr style="background-color: #a0d2f3;">
        <th>Total Efectivo: {{ number_format($totalEf ?? 0, 2) }} Bs.</th>
        <th>Total Depósito: {{ number_format($totalDep ?? 0, 2) }} Bs.</th>
    </tr>
</table>

<!-- Listado de ventas -->
@if($detalles && count($detalles) > 0)
    <table class="table">
        <thead class="bg-primary">
            <tr>
                <th>Nro</th>
                <th>Cliente</th>
                <th>Tipo P.</th>
                <th>Forma P.</th>
                <th>Descuento</th>
                <th>Sub Total</th>
                <th>Total</th>
                <th>Usuario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $index => $det)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $det->cliente ?? 'N/A' }}</td>
                    <td>{{ $det->tipo_pago ?? 'N/A' }}</td>
                    <td>{{ $det->forma_pago ?? 'N/A' }}</td>
                    <td class="text-center">{{ number_format($det->descuento ?? 0, 2) }}</td>
                    <td class="text-center">{{ number_format($det->sub_total ?? 0, 2) }}</td>
                    <td class="text-center">{{ number_format($det->total ?? 0, 2) }}</td>
                    <td>{{ $det->usuario ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <table class="table">
        <tr>
            <td class="text-center" style="padding: 20px; background: #f8f8f8;">
                No hay ventas registradas en este arqueo.
            </td>
        </tr>
    </table>
@endif

</body>
</html>
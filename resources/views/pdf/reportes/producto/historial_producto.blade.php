@php
    $eyebrow = 'Ventas';
    $documentLabel = 'Historial de producto';
    $sectionTitle = 'Historial de producto por usuario';
    $description = 'Detalle de ventas registradas para el lote seleccionado.';
    $recordCount = count($detalles);
    $recordLabel = 'Movimientos';
    $periodLabel = !empty($fecha_producto) ? 'Desde ' . \Carbon\Carbon::parse($fecha_producto)->format('d/m/Y') : null;
    $footerLabel = 'Historial de producto';
    $logo_sistema = $foto_empresa ?? null;
@endphp
@extends('pdf.reportes.layouts.corporate-letter')

@section('content')
@include('pdf.reportes.partials.corporate-summary-cards', ['items' => [
    ['label' => 'Cantidad total vendida', 'value' => number_format((float) ($detalles1[0]['cantidadT'] ?? 0))],
]])
<table class="fc-table">
    <thead>
        <tr>
            <th style="width:5%">N.º</th>
            <th style="width:15%">Fecha</th>
            <th style="width:30%">Producto</th>
            <th style="width:20%">Laboratorio</th>
            <th style="width:10%">Cantidad</th>
            <th style="width:20%">Usuario</th>
        </tr>
    </thead>
    <tbody>
        @forelse($detalles as $index => $det)
            <tr>
                <td class="is-center">{{ $index + 1 }}</td>
                <td>{{ $det['fecha'] }}</td>
                <td class="is-strong">{{ $det['nombre_comercial'] }}</td>
                <td>{{ $det['laboratorio'] }}</td>
                <td class="is-center">{{ $det['cantidad'] }}</td>
                <td>{{ $det['usuario'] }}</td>
            </tr>
        @empty
            <tr><td class="fc-empty" colspan="6">No se encontraron registros entre estas fechas.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

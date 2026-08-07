@php
    $eyebrow = 'Ventas';
    $documentLabel = 'Ventas generales';
    $sectionTitle = 'Ventas generales por arqueo';
    $description = 'Detalle de ventas registradas durante el arqueo de caja.';
    $recordCount = count($detalles);
    $recordLabel = 'Ventas';
    $footerLabel = 'Ventas generales';
@endphp
@extends('pdf.reportes.layouts.corporate-letter')

@section('content')
@include('pdf.reportes.partials.corporate-summary-cards', ['items' => [
    ['label' => 'Total venta', 'value' => 'Bs ' . number_format((float) ($totalV ?? 0), 2)],
    ['label' => 'Contado', 'value' => 'Bs ' . number_format((float) ($totalC ?? 0), 2)],
    ['label' => 'Crédito', 'value' => 'Bs ' . number_format((float) ($totalCr ?? 0), 2)],
    ['label' => 'Efectivo', 'value' => 'Bs ' . number_format((float) ($totalEf ?? 0), 2)],
    ['label' => 'Depósito', 'value' => 'Bs ' . number_format((float) ($totalDep ?? 0), 2)],
]])
<table class="fc-table">
    <thead>
        <tr>
            <th style="width:5%">N.º</th>
            <th style="width:20%">Cliente</th>
            <th style="width:12%">Tipo P.</th>
            <th style="width:14%">Forma P.</th>
            <th style="width:10%">Descuento</th>
            <th style="width:10%">Sub total</th>
            <th style="width:10%">Total</th>
            <th style="width:19%">Usuario</th>
        </tr>
    </thead>
    <tbody>
        @forelse($detalles as $index => $det)
            <tr>
                <td class="is-center">{{ $index + 1 }}</td>
                <td>{{ $det->cliente ?? 'N/A' }}</td>
                <td>{{ $det->tipo_pago ?? 'N/A' }}</td>
                <td>{{ $det->forma_pago ?? 'N/A' }}</td>
                <td class="is-right">Bs {{ number_format((float) ($det->descuento ?? 0), 2) }}</td>
                <td class="is-right">Bs {{ number_format((float) ($det->sub_total ?? 0), 2) }}</td>
                <td class="is-right is-strong">Bs {{ number_format((float) ($det->total ?? 0), 2) }}</td>
                <td>{{ $det->usuario ?? 'N/A' }}</td>
            </tr>
        @empty
            <tr><td class="fc-empty" colspan="8">No hay ventas registradas en este arqueo.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

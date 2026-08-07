@php
    $eyebrow = 'Ventas';
    $documentLabel = 'Ventas QR';
    $sectionTitle = $title ?? 'Ventas detalladas QR';
    $description = 'Detalle de ventas y productos pagados con QR, dentro del arqueo de caja.';
    $recordCount = isset($venta) ? $venta->count() : 0;
    $recordLabel = 'Ventas';
    $footerLabel = 'Ventas QR';
@endphp
@extends('pdf.reportes.layouts.corporate-letter')

@section('content')
@include('pdf.reportes.partials.corporate-summary-cards', ['items' => [
    ['label' => 'Total ventas QR', 'value' => 'Bs ' . number_format((float) ($totalVentas ?? 0), 2)],
]])
<table class="fc-table">
    <thead>
        <tr>
            <th style="width:30%">Producto</th>
            <th style="width:12%">Cantidad</th>
            <th style="width:15%">P.U.</th>
            <th style="width:15%">Sub total</th>
            <th style="width:28%">Fecha</th>
        </tr>
    </thead>
    <tbody>
        @forelse(($venta ?? collect()) as $comp)
            @php
                $detallesVenta = ($detalles ?? collect())->where('id_venta', $comp->id);
            @endphp
            <tr class="fc-group-row">
                <td colspan="5">
                    {{ $comp->cliente ?? 'N/A' }} · {{ $comp->tipo_pago ?? 'N/A' }} / {{ $comp->forma_pago ?? 'N/A' }}
                    <div class="is-muted">Descuento: Bs {{ number_format((float) ($comp->descuento ?? 0), 2) }} · Total: Bs {{ number_format((float) ($comp->total_deposito ?? $comp->total ?? 0), 2) }}</div>
                </td>
            </tr>
            @forelse($detallesVenta as $det)
                <tr class="fc-subrow">
                    <td>{{ $det->producto ?? 'Producto no disponible' }}</td>
                    <td class="is-center">
                        @php $presentacionLabel = [1 => 'Blíster', 2 => 'Caja'][$det->presentacion ?? 0] ?? null; @endphp
                        {{ $det->cantidad ?? 0 }}
                        @if($presentacionLabel)
                            {{ $presentacionLabel }} <small>({{ $det->total_cantidad ?? 0 }} un.)</small>
                        @endif
                    </td>
                    <td class="is-right">Bs {{ number_format((float) ($det->costo_venta ?? 0), 2) }}</td>
                    <td class="is-right">Bs {{ number_format((float) ($det->sub_total ?? 0), 2) }}</td>
                    <td class="is-center">{{ \Carbon\Carbon::parse($comp->fecha)->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr class="fc-subrow"><td colspan="5" class="is-muted is-center">No se encontraron detalles para esta venta.</td></tr>
            @endforelse
        @empty
            <tr><td class="fc-empty" colspan="5">No se encontraron registros entre las fechas seleccionadas.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

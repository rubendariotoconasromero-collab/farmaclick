@php
    $eyebrow = 'Ventas';
    $documentLabel = 'Ventas mixtas';
    $sectionTitle = $title ?? 'Ventas mixtas';
    $description = 'Detalle de productos y paquetes vendidos con forma de pago mixta (efectivo + depósito).';
    $recordCount = isset($venta) ? $venta->count() : 0;
    $recordLabel = 'Ventas';
    $footerLabel = 'Ventas mixtas';
@endphp
@extends('pdf.reportes.layouts.corporate-letter')

@section('content')
@include('pdf.reportes.partials.corporate-summary-cards', ['items' => [
    ['label' => 'Total ventas', 'value' => 'Bs ' . number_format((float) ($totalVentas ?? 0), 2)],
]])
<table class="fc-table">
    <thead>
        <tr>
            <th style="width:28%">Producto / Paquete</th>
            <th style="width:10%">Cantidad</th>
            <th style="width:14%">P.U.</th>
            <th style="width:14%">Sub total</th>
            <th style="width:12%">Tipo</th>
            <th style="width:22%">Fecha</th>
        </tr>
    </thead>
    <tbody>
        @forelse(($venta ?? collect()) as $comp)
            @php
                $detallesVenta = ($detalles ?? collect())->where('id_venta', $comp->id);
                $paquetesVenta = ($detallesPaquete ?? collect())->where('id_venta', $comp->id);
                $tieneDetalles = $detallesVenta->isNotEmpty() || $paquetesVenta->isNotEmpty();
            @endphp
            <tr class="fc-group-row">
                <td colspan="6">
                    {{ $comp->cliente ?? 'N/A' }} · Descuento: Bs {{ number_format((float) ($comp->descuento ?? 0), 2) }}
                    <div class="is-muted">Total: Bs {{ number_format((float) ($comp->total ?? 0), 2) }} · Efectivo: Bs {{ number_format((float) ($comp->total_efectivo ?? 0), 2) }} · Depósito: Bs {{ number_format((float) ($comp->total_deposito ?? 0), 2) }} · {{ \Carbon\Carbon::parse($comp->fecha)->format('d/m/Y') }}</div>
                </td>
            </tr>
            @if($tieneDetalles)
                @foreach($detallesVenta as $det)
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
                        <td class="is-center">Producto</td>
                        <td></td>
                    </tr>
                @endforeach
                @foreach($paquetesVenta as $det)
                    <tr class="fc-subrow">
                        <td>{{ $det->producto ?? 'Paquete no disponible' }}</td>
                        <td class="is-center">{{ $det->cantidad ?? 0 }}</td>
                        <td class="is-right">Bs {{ number_format((float) ($det->costo_venta ?? 0), 2) }}</td>
                        <td class="is-right">Bs {{ number_format((float) ($det->sub_total ?? 0), 2) }}</td>
                        <td class="is-center">Paquete</td>
                        <td></td>
                    </tr>
                @endforeach
            @else
                <tr class="fc-subrow"><td colspan="6" class="is-muted is-center">No se encontraron detalles para esta venta.</td></tr>
            @endif
        @empty
            <tr><td class="fc-empty" colspan="6">No se encontraron registros entre las fechas seleccionadas.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

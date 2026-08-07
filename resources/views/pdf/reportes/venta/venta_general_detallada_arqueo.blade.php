@php
    $eyebrow = 'Ventas';
    $documentLabel = 'Ventas detalladas';
    $sectionTitle = 'Ventas detalladas por arqueo';
    $description = 'Detalle de productos y paquetes vendidos durante el arqueo de caja.';
    $recordCount = collect($venta ?? [])->pluck('id')->unique()->count();
    $recordLabel = 'Ventas';
    $footerLabel = 'Ventas detalladas';
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
            <th style="width:30%">Producto / Paquete</th>
            <th style="width:10%">Cantidad</th>
            <th style="width:15%">P.U.</th>
            <th style="width:15%">Sub total</th>
            <th style="width:15%">Tipo</th>
            <th style="width:15%">Fecha</th>
        </tr>
    </thead>
    <tbody>
        @php $ventasAgrupadas = collect($venta ?? [])->groupBy('id'); @endphp
        @forelse($ventasAgrupadas as $id_venta => $grupo)
            @php
                $comp = $grupo->first();
                $detallesVenta = $detalles->where('id_venta', $id_venta);
                $paquetesVenta = $detallesPaquete->where('id_venta', $id_venta);
                $tieneDetalles = $detallesVenta->isNotEmpty() || $paquetesVenta->isNotEmpty();
            @endphp
            <tr class="fc-group-row">
                <td colspan="6">
                    {{ $comp->cliente ?? 'N/A' }} · {{ $comp->tipo_pago ?? 'N/A' }} / {{ $comp->forma_pago ?? 'N/A' }}
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
            <tr><td class="fc-empty" colspan="6">No hay ventas registradas en este arqueo.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

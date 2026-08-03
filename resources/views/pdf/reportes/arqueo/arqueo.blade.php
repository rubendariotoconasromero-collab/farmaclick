@php
    $eyebrow = 'Control financiero';
    $documentLabel = 'Reporte de caja';
    $sectionTitle = 'Arqueos por usuario';
    $description = 'Resumen de aperturas, movimientos y cierres de caja del período seleccionado.';
    $recordCount = count($detalles);
    $recordLabel = 'Arqueos';
    $periodLabel = 'Del ' . \Carbon\Carbon::parse($fecha_inicio)->format('d/m/Y') . ' al ' . \Carbon\Carbon::parse($fecha_fin)->format('d/m/Y');
    $footerLabel = 'Control de cajas';
@endphp
@extends('pdf.reportes.layouts.corporate-letter')

@section('content')
<table class="fc-table">
    <thead><tr><th style="width:5%">N.º</th><th style="width:20%">Período</th><th style="width:12%">Inicial</th><th style="width:12%">Ingresos</th><th style="width:12%">Egresos</th><th style="width:14%">Efectivo</th><th style="width:15%">Responsable</th><th style="width:10%">Estado</th></tr></thead>
    <tbody>
        @forelse($detalles as $det)
            <tr>
                <td class="is-center">{{ $loop->iteration }}</td>
                <td>Desde: <strong>{{ !empty($det['fecha_apertura']) ? \Carbon\Carbon::parse($det['fecha_apertura'])->format('d/m/Y H:i') : '—' }}</strong><div class="is-muted">Hasta: {{ !empty($det['fecha_cierre']) ? \Carbon\Carbon::parse($det['fecha_cierre'])->format('d/m/Y H:i') : 'Pendiente' }}</div></td>
                <td class="is-right">Bs {{ number_format((float) $det['apertura'], 2, ',', '.') }}</td>
                <td class="is-right">Bs {{ number_format((float) $det['ingreso'], 2, ',', '.') }}</td>
                <td class="is-right">Bs {{ number_format((float) $det['egreso'], 2, ',', '.') }}</td>
                <td class="is-right is-strong">Bs {{ number_format((float) $det['total_efectivo'], 2, ',', '.') }}</td>
                <td class="is-strong">{{ $det['name'] ?: '—' }}</td>
                <td><span class="fc-status {{ $det['estado'] === 'Cerrada' ? 'fc-status--closed' : '' }}">{{ $det['estado'] ?: '—' }}</span></td>
            </tr>
        @empty
            <tr><td class="fc-empty" colspan="8">No existen arqueos para el período seleccionado.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

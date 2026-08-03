@php
    $eyebrow = 'Control de inventario';
    $documentLabel = 'Reporte preventivo';
    $sectionTitle = 'Productos próximos a vencer';
    $description = 'Lotes con vencimiento dentro del horizonte de control configurado.';
    $recordCount = $productos->count();
    $recordLabel = 'Lotes';
    $periodLabel = $subtitle ?? 'Próximos 3 meses';
    $footerLabel = 'Control de vencimientos';
@endphp
@extends('pdf.reportes.layouts.corporate-letter')

@section('content')
<table class="fc-table">
    <thead><tr><th style="width:13%">Lote</th><th style="width:35%">Producto</th><th style="width:24%">Laboratorio</th><th style="width:10%">Stock</th><th style="width:18%">Vencimiento</th></tr></thead>
    <tbody>
        @forelse($productos as $producto)
            <tr>
                <td class="is-center is-strong">{{ $producto->lote ?: 'S/L' }}</td>
                <td class="is-strong">{{ $producto->articulo ?: '—' }}<div class="is-muted">{{ $producto->nombre_generico ?: 'Sin genérico' }} · {{ $producto->categoria ?: 'Sin categoría' }}</div></td>
                <td>{{ $producto->laboratorio ?: '—' }}<div class="is-muted">{{ $producto->presentacion ?: 'Sin presentación' }}</div></td>
                <td class="is-center">{{ number_format((float) $producto->stock, 0, ',', '.') }}</td>
                <td class="is-center is-strong">{{ $producto->fecha_vecimiento ? \Carbon\Carbon::parse($producto->fecha_vecimiento)->format('d/m/Y') : '—' }}</td>
            </tr>
        @empty
            <tr><td class="fc-empty" colspan="5">No existen productos próximos a vencer.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

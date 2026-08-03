@php
    $eyebrow = 'Directorio de abastecimiento';
    $documentLabel = 'Reporte de laboratorios';
    $sectionTitle = 'Laboratorios y proveedores activos';
    $description = 'Directorio comercial utilizado para compras y abastecimiento de productos.';
    $recordCount = $detalles->count();
    $recordLabel = 'Laboratorios';
    $footerLabel = 'Directorio de laboratorios';
@endphp
@extends('pdf.reportes.layouts.corporate-letter')

@section('content')
<table class="fc-table">
    <thead><tr><th style="width:6%">N.º</th><th style="width:10%">Código</th><th style="width:25%">Laboratorio</th><th style="width:23%">Contacto</th><th style="width:36%">Dirección</th></tr></thead>
    <tbody>
        @forelse($detalles as $det)
            <tr>
                <td class="is-center">{{ $loop->iteration }}</td>
                <td class="is-center">{{ $det->id }}</td>
                <td class="is-strong">{{ $det->nombre ?: '—' }}<div class="is-muted">NIT: {{ $det->nit ?: 'Sin registro' }}</div></td>
                <td>{{ $det->contacto ?: '—' }}<div class="is-muted">Tel.: {{ $det->telefono ?: 'Sin registro' }}</div></td>
                <td>{{ $det->direccion ?: '—' }}</td>
            </tr>
        @empty
            <tr><td class="fc-empty" colspan="5">No existen laboratorios activos registrados.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

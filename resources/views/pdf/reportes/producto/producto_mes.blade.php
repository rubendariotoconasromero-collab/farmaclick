@php
    $eyebrow = 'Almacén';
    $documentLabel = 'Reporte de vencimientos';
    $sectionTitle = 'Lista de productos';
    $description = 'Productos con lote activo dentro del rango de vencimiento consultado.';
    $recordCount = count($detalles);
    $recordLabel = 'Productos';
    $footerLabel = 'Lista de productos';
    $title = 'LISTA DE PRODUCTOS';
    $nombre_empresa = $empresa->nombre ?? null;
    $direccion_empresa = $empresa->direccion ?? null;
    $telefono_empresa = $empresa->telefono ?? null;
    $logo_sistema = $empresa->logo_sistema ?? ($empresa->foto ?? null);
@endphp
@extends('pdf.reportes.layouts.corporate-letter')

@section('content')
<table class="fc-table">
    <thead>
        <tr>
            <th style="width:4%">N.º</th>
            <th style="width:21%">Producto</th>
            <th style="width:19%">Nombre genérico</th>
            <th style="width:9%">Vence</th>
            <th style="width:8%">Ubic.</th>
            <th style="width:9%">P. Unidad</th>
            <th style="width:9%">P. Blister</th>
            <th style="width:9%">P. Caja</th>
            <th style="width:5%">Stock</th>
            <th style="width:15%">Laboratorio</th>
        </tr>
    </thead>
    <tbody>
        @forelse($detalles as $index => $det)
            <tr>
                <td class="is-center">{{ $index + 1 }}</td>
                <td class="is-strong">{{ $det->nombre_comercial }}</td>
                <td>{{ $det->nombre_generico }}</td>
                <td class="is-center">{{ !empty($det->fecha_vecimiento) ? \Carbon\Carbon::parse($det->fecha_vecimiento)->format('d/m/Y') : '—' }}</td>
                <td class="is-center">{{ $det->ubicacion }}</td>
                <td class="is-right">Bs {{ number_format((float) $det->costo_unitario, 2) }}</td>
                <td class="is-right">Bs {{ number_format((float) $det->precio_blister, 2) }}</td>
                <td class="is-right">Bs {{ number_format((float) $det->precio_caja, 2) }}</td>
                <td class="is-center">{{ $det->stock }}</td>
                <td>{{ $det->laboratorio }}</td>
            </tr>
        @empty
            <tr><td class="fc-empty" colspan="10">No se encontraron productos en el rango de vencimiento seleccionado.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

@php
    $eyebrow = 'Directorio organizacional';
    $documentLabel = 'Reporte de personal';
    $sectionTitle = 'Personal activo';
    $description = 'Información de contacto y asignación de cargos del personal registrado.';
    $recordCount = $detalles->count();
    $recordLabel = 'Personas';
    $footerLabel = 'Directorio de personal';
@endphp
@extends('pdf.reportes.layouts.corporate-letter')

@section('content')
<table class="fc-table">
    <thead><tr><th style="width:7%">N.º</th><th style="width:27%">Nombre</th><th style="width:17%">Teléfono</th><th style="width:31%">Dirección</th><th style="width:18%">Cargo</th></tr></thead>
    <tbody>
        @forelse($detalles as $det)
            <tr><td class="is-center">{{ $loop->iteration }}</td><td class="is-strong">{{ $det->nombre ?: '—' }}</td><td>{{ $det->telefono ?: '—' }}</td><td>{{ $det->direccion ?: '—' }}</td><td>{{ $det->cargo ?: '—' }}</td></tr>
        @empty
            <tr><td class="fc-empty" colspan="5">No existe personal activo registrado.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

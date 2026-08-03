@php
    $eyebrow = 'Seguridad y acceso';
    $documentLabel = 'Reporte de usuarios';
    $sectionTitle = 'Usuarios activos del sistema';
    $description = 'Relación de cuentas, grupos y personal asociado dentro de FarmaClick.';
    $recordCount = $detalles->count();
    $recordLabel = 'Usuarios';
    $footerLabel = 'Directorio de usuarios';
@endphp
@extends('pdf.reportes.layouts.corporate-letter')

@section('content')
<table class="fc-table">
    <thead><tr><th style="width:6%">N.º</th><th style="width:18%">Usuario</th><th style="width:22%">Correo</th><th style="width:16%">Grupo</th><th style="width:23%">Personal</th><th style="width:15%">Cargo</th></tr></thead>
    <tbody>
        @forelse($detalles as $det)
            <tr><td class="is-center">{{ $loop->iteration }}</td><td class="is-strong">{{ $det->nombre ?: '—' }}</td><td>{{ $det->email ?: '—' }}</td><td>{{ $det->grupo ?: '—' }}</td><td>{{ $det->personal ?: '—' }}</td><td>{{ $det->cargo ?: '—' }}</td></tr>
        @empty
            <tr><td class="fc-empty" colspan="6">No existen usuarios activos registrados.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

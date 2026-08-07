@php
    $eyebrow = 'Veterinaria';
    $documentLabel = 'Control antiparasitario';
    $sectionTitle = 'Control antiparasitario';
    $description = 'Historial de aplicaciones registradas para esta mascota.';
    $recordCount = count($detalles);
    $recordLabel = 'Aplicaciones';
    $footerLabel = 'Control antiparasitario';
    $logo_sistema = $foto_empresa ?? null;
@endphp
@extends('pdf.reportes.layouts.corporate-letter')

@section('content')
<table class="fc-table">
    <thead>
        <tr>
            <th style="width:25%">Fecha</th>
            <th style="width:25%">Peso</th>
            <th style="width:25%">Vacuna</th>
            <th style="width:25%">Próx. fecha</th>
        </tr>
    </thead>
    <tbody>
        @forelse($detalles as $det)
            <tr>
                <td class="is-center">{{ $det['fecha'] }}</td>
                <td class="is-center">{{ $det['peso'] }}</td>
                <td>{{ $det['vacuna'] }}</td>
                <td class="is-center">{{ $det['prox_fecha'] }}</td>
            </tr>
        @empty
            <tr><td class="fc-empty" colspan="4">No se encontraron registros.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection

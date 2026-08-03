@php
    $resolvedLogo = null;
    if (!empty($logo_sistema)) {
        $candidate = public_path('img/logo/' . $logo_sistema);
        $resolvedLogo = is_file($candidate) ? $candidate : null;
    }
@endphp
<table class="fc-header" role="presentation">
    <tr>
        <td class="fc-header__brand">
            @if($resolvedLogo)
                <img class="fc-header__logo" src="{{ $resolvedLogo }}" alt="{{ $nombre_empresa ?? 'FarmaClick' }}">
            @else
                <div class="fc-header__fallback">FarmaClick</div>
            @endif
            <div class="fc-header__company">{{ $nombre_empresa ?? 'FarmaClick' }}</div>
            @if(!empty($direccion_empresa) || !empty($telefono_empresa))
                <div>{{ $direccion_empresa ?? '' }}{{ !empty($direccion_empresa) && !empty($telefono_empresa) ? ' · ' : '' }}{{ $telefono_empresa ?? '' }}</div>
            @endif
        </td>
        <td class="fc-header__document">
            <div class="fc-header__eyebrow">{{ $eyebrow ?? 'Información empresarial' }}</div>
            <div class="fc-header__title">{{ $title }}</div>
            <div class="fc-header__badge">{{ $documentLabel ?? 'Reporte corporativo' }}</div>
        </td>
    </tr>
</table>

<table class="fc-intro" role="presentation">
    <tr>
        <td class="fc-intro__copy">
            <strong>{{ $sectionTitle ?? 'Resumen de información' }}</strong>
            {{ $description ?? 'Documento generado desde el sistema FarmaClick.' }}
        </td>
        <td class="fc-intro__meta">
            @if(!empty($periodLabel)){{ $periodLabel }}<br>@endif
            <strong>{{ $recordLabel ?? 'Registros' }}: {{ number_format((int) ($recordCount ?? 0)) }}</strong>
        </td>
    </tr>
</table>

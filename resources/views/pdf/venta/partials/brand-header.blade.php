<table class="brand-header" role="presentation">
    <tr>
        <td class="brand-header__identity">
            @if($logo)
                <img class="brand-header__logo" src="{{ $logo }}" alt="{{ optional($empresa)->nombre ?: 'FarmaClick' }}">
            @else
                <div class="brand-header__fallback">FarmaClick</div>
            @endif
            @if(!$compact)
                <div class="brand-header__company">{{ optional($empresa)->nombre ?: 'FarmaClick' }}</div>
                @if(optional($empresa)->nit)<div>NIT {{ $empresa->nit }}</div>@endif
            @endif
        </td>
        <td class="brand-header__document">
            <div class="brand-header__eyebrow">Comprobante de salida</div>
            <div class="brand-header__title">NOTA DE VENTA</div>
            <div class="brand-header__number">N. {{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}</div>
        </td>
    </tr>
</table>

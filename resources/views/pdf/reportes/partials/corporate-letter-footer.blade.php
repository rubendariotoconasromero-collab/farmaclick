<div class="fc-footer">
    Generado por FarmaClick el {{ now()->format('d/m/Y H:i') }}
    <span class="fc-footer__right">{{ $footerLabel ?? $title }} · Página @if(!empty($mpdfMode)){PAGENO}@else<span class="fc-footer__page"></span>@endif</span>
</div>

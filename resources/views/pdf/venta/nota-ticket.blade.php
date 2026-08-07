<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket de venta {{ $venta->id }}</title>
    <style>
        @page { margin: 9px 10px 12px; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #111; font-family: DejaVu Sans, sans-serif; font-size: 7.5px; line-height: 1.35; }
        .brand-header { width: 100%; border-collapse: collapse; text-align: center; }
        .brand-header td { display: block; width: 100%; padding: 0; text-align: center; }
        .brand-header__logo { width: 128px; max-height: 45px; object-fit: contain; }
        .brand-header__fallback { font-size: 18px; font-weight: bold; }
        .brand-header__eyebrow { margin-top: 4px; font-size: 6px; font-weight: bold; letter-spacing: .6px; text-transform: uppercase; }
        .brand-header__title { margin-top: 2px; font-size: 12px; font-weight: bold; }
        .brand-header__number { font-size: 8px; font-weight: bold; }
        .company { margin: 7px 0; padding: 6px 0; border-top: 1px dashed #555; border-bottom: 1px dashed #555; text-align: center; }
        .company strong { display: block; font-size: 8px; }
        .ticket-meta { margin-bottom: 7px; }
        .ticket-meta div { clear: both; padding: 1px 0; }
        .ticket-meta span { float: left; color: #444; }
        .ticket-meta strong { display: block; margin-left: 54px; text-align: right; }
        .items { width: 100%; border-collapse: collapse; border-top: 1px dashed #555; }
        .items td { padding: 5px 0; border-bottom: 1px dashed #aaa; vertical-align: top; }
        .item__name { font-size: 7.5px; font-weight: bold; }
        .item__meta { margin-top: 2px; color: #444; font-size: 6.5px; }
        .item__subtotal { float: right; font-weight: bold; }
        .totals { width: 100%; margin-top: 6px; border-collapse: collapse; }
        .totals td { padding: 2px 0; }
        .totals td:last-child { font-weight: bold; text-align: right; }
        .totals__break td { padding-top: 5px; border-top: 1px dashed #555; }
        .totals__grand td { padding: 6px 0 4px; border-top: 1px solid #111; border-bottom: 2px solid #111; font-size: 10px; font-weight: bold; }
        .footer { margin-top: 10px; text-align: center; }
        .footer strong { display: block; margin-bottom: 2px; font-size: 8px; }
        .footer small { color: #444; font-size: 6px; }
    </style>
</head>
<body>
    @include('pdf.venta.partials.brand-header', ['compact' => true])
    <div class="company">
        <strong>{{ optional($empresa)->nombre ?: 'FarmaClick' }}</strong>
        @if(optional($empresa)->nit)<div>NIT {{ $empresa->nit }}</div>@endif
        @if(optional($empresa)->direccion)<div>{{ $empresa->direccion }}</div>@endif
        @if(optional($empresa)->telefono)<div>Tel. {{ $empresa->telefono }}</div>@endif
    </div>
    @include('pdf.venta.partials.sale-meta', ['compact' => true])
    <table class="items"><tbody>
        @forelse($detalles as $detalle)
            <tr><td>
                <div class="item__name">{{ $loop->iteration }}. {{ $detalle->articulo }}</div>
                @if($detalle->lote)<div class="item__meta">Lote: {{ $detalle->lote }}@if($detalle->fecha_vecimiento) | Vence: {{ \Carbon\Carbon::parse($detalle->fecha_vecimiento)->format('d/m/Y') }}@endif</div>@endif
                @php $presentacionLabel = [1 => 'Blíster', 2 => 'Caja'][$detalle->presentacion ?? 0] ?? null; @endphp
                <div>{{ number_format((float) $detalle->cantidad, 0, ',', '.') }}@if($presentacionLabel) {{ $presentacionLabel }} ({{ number_format((float) $detalle->total_cantidad, 0, ',', '.') }} un.)@endif x Bs {{ number_format((float) $detalle->costo_venta, 2, ',', '.') }}<span class="item__subtotal">Bs {{ number_format((float) $detalle->sub_total, 2, ',', '.') }}</span></div>
            </td></tr>
        @empty
            <tr><td>Sin productos registrados.</td></tr>
        @endforelse
    </tbody></table>
    @include('pdf.venta.partials.sale-totals')
    <div class="footer"><strong>¡Gracias por su compra!</strong>Conserve este comprobante<br><small>FarmaClick - {{ $fecha_impresion }}</small></div>
</body>
</html>

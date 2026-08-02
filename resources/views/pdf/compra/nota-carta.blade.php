<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota de compra {{ $compra->id }}</title>
    <style>
        @page { margin: 32px 38px 44px; }
        * { box-sizing: border-box; }
        body { margin: 0; color: #17362b; font-family: DejaVu Sans, sans-serif; font-size: 9px; line-height: 1.45; }
        .brand-header { width: 100%; border-collapse: collapse; border-bottom: 3px solid #1f9254; }
        .brand-header td { padding: 0 0 14px; vertical-align: middle; }
        .brand-header__identity { width: 54%; color: #587067; }
        .brand-header__logo { width: 205px; max-height: 62px; object-fit: contain; }
        .brand-header__fallback { color: #1f9254; font-size: 25px; font-weight: bold; }
        .brand-header__company { margin-top: 5px; color: #17362b; font-size: 10px; font-weight: bold; }
        .brand-header__document { width: 46%; text-align: right; }
        .brand-header__eyebrow { color: #1a9ebe; font-size: 8px; font-weight: bold; letter-spacing: 1px; text-transform: uppercase; }
        .brand-header__title { margin: 3px 0 4px; color: #173f32; font-size: 20px; font-weight: bold; }
        .brand-header__number { display: inline-block; padding: 4px 10px; color: #fff; background: #1f9254; border-radius: 12px; font-size: 9px; font-weight: bold; }
        .document-intro { margin: 17px 0 8px; }
        .document-intro h2 { margin: 0; color: #173f32; font-size: 12px; }
        .document-intro p { margin: 2px 0 0; color: #6c8179; }
        .meta-grid { width: 100%; margin-bottom: 18px; border-spacing: 7px; margin-left: -7px; margin-right: -7px; }
        .meta-grid td { width: 50%; padding: 8px 10px; background: #f2f8f5; border-left: 3px solid #40bfd4; border-radius: 4px; }
        .meta-grid span { display: block; color: #71847d; font-size: 7px; font-weight: bold; letter-spacing: .5px; text-transform: uppercase; }
        .meta-grid strong { display: block; margin-top: 2px; color: #17362b; font-size: 9px; }
        .meta-grid .status { color: #1f9254; }
        .items { width: 100%; border-collapse: collapse; }
        .items thead { display: table-header-group; }
        .items th { padding: 8px 6px; color: #fff; background: #173f32; font-size: 7px; letter-spacing: .35px; text-align: left; text-transform: uppercase; }
        .items th:first-child { border-radius: 5px 0 0 0; }
        .items th:last-child { border-radius: 0 5px 0 0; }
        .items td { padding: 8px 6px; border-bottom: 1px solid #d9e5e0; vertical-align: top; }
        .items tbody tr:nth-child(even) td { background: #f7faf8; }
        .items .index { width: 4%; color: #779087; text-align: center; }
        .items .product { width: 35%; font-weight: bold; }
        .items .product small { display: block; color: #758b82; font-size: 7px; font-weight: normal; }
        .items .qty { width: 8%; text-align: center; }
        .items .money { text-align: right; white-space: nowrap; }
        .items .lot { width: 15%; }
        .items .lot small { display: block; color: #758b82; font-size: 7px; }
        .bottom { width: 100%; margin-top: 15px; border-collapse: collapse; page-break-inside: avoid; }
        .bottom__notes { width: 55%; padding-right: 20px; color: #60766e; vertical-align: top; }
        .bottom__notes strong { display: block; margin-bottom: 4px; color: #173f32; font-size: 8px; text-transform: uppercase; }
        .bottom__notes div { min-height: 42px; padding: 8px; background: #f7faf8; border: 1px solid #deebe5; border-radius: 5px; }
        .bottom__totals { width: 45%; vertical-align: top; }
        .totals { width: 100%; border-collapse: collapse; }
        .totals td { padding: 5px 8px; border-bottom: 1px solid #dfebe6; }
        .totals td:last-child { font-weight: bold; text-align: right; }
        .totals__break td { border-top: 1px dashed #a9bdb5; }
        .totals__grand td { padding: 9px 8px; color: #fff; background: #1f9254; border: 0; font-size: 12px; font-weight: bold; }
        .footer { position: fixed; right: 0; bottom: -28px; left: 0; color: #7a8d86; font-size: 7px; border-top: 1px solid #dbe7e2; padding-top: 7px; }
        .footer__right { float: right; }
        .footer__page:after { content: counter(page); }
    </style>
</head>
<body>
    @include('pdf.compra.partials.brand-header', ['compact' => false])

    <div class="document-intro">
        <h2>Detalle de la operacion</h2>
        <p>Resumen de productos ingresados al inventario y condiciones comerciales de la compra.</p>
    </div>

    @include('pdf.compra.partials.purchase-meta', ['compact' => false])

    <table class="items">
        <thead>
            <tr>
                <th class="index">#</th>
                <th>Producto</th>
                <th>Lote / vencimiento</th>
                <th class="qty">Cant.</th>
                <th class="money">Costo unit.</th>
                <th class="money">Desc.</th>
                <th class="money">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $detalle)
                <tr>
                    <td class="index">{{ $loop->iteration }}</td>
                    <td class="product">{{ $detalle->articulo }}<small>{{ $detalle->categoria ?: 'Sin categoria' }} - {{ $detalle->tienda }}</small></td>
                    <td class="lot">{{ $detalle->lote ?: 'S/L' }}<small>{{ $detalle->fecha_vecimiento ? \Carbon\Carbon::parse($detalle->fecha_vecimiento)->format('d/m/Y') : 'Sin vencimiento' }}</small></td>
                    <td class="qty">{{ number_format((float) $detalle->cantidad, 0, ',', '.') }}</td>
                    <td class="money">Bs {{ number_format((float) $detalle->costo_compra, 2, ',', '.') }}</td>
                    <td class="money">Bs {{ number_format((float) $detalle->descuento, 2, ',', '.') }}</td>
                    <td class="money"><strong>Bs {{ number_format((float) $detalle->sub_total, 2, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="bottom" role="presentation">
        <tr>
            <td class="bottom__notes">
                <strong>Observaciones</strong>
                <div>{{ $compra->descripcion ?: 'Sin observaciones registradas.' }}</div>
            </td>
            <td class="bottom__totals">@include('pdf.compra.partials.purchase-totals')</td>
        </tr>
    </table>

    <div class="footer">
        Generado por FarmaClick el {{ $fecha_impresion }}
        <span class="footer__right">Nota {{ str_pad($compra->id, 6, '0', STR_PAD_LEFT) }} - Pagina <span class="footer__page"></span></span>
    </div>
</body>
</html>

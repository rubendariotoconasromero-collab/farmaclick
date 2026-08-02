<table class="totals" role="presentation">
    <tr><td>Subtotal</td><td>Bs {{ number_format((float) $compra->sub_total, 2, ',', '.') }}</td></tr>
    <tr><td>Descuento</td><td>- Bs {{ number_format((float) $compra->descuento, 2, ',', '.') }}</td></tr>
    @php
        $efectivo = (float) $compra->total_efectivo;
        $deposito = (float) $compra->total_deposito;
        $esPagoMixtoCoherente = $efectivo > 0
            && $deposito > 0
            && abs(($efectivo + $deposito) - (float) $compra->total) < 0.02;
    @endphp
    @if($esPagoMixtoCoherente)
        <tr class="totals__break"><td>Efectivo</td><td>Bs {{ number_format((float) $compra->total_efectivo, 2, ',', '.') }}</td></tr>
        <tr><td>Deposito / banco</td><td>Bs {{ number_format((float) $compra->total_deposito, 2, ',', '.') }}</td></tr>
    @endif
    <tr class="totals__grand"><td>Total</td><td>Bs {{ number_format((float) $compra->total, 2, ',', '.') }}</td></tr>
</table>

<table class="totals" role="presentation">
    <tr><td>Subtotal</td><td>Bs {{ number_format((float) $venta->sub_total, 2, ',', '.') }}</td></tr>
    @if((float) $venta->descuento > 0)
        <tr><td>Descuento</td><td>- Bs {{ number_format((float) $venta->descuento, 2, ',', '.') }}</td></tr>
    @endif
    @if((float) $venta->total_efectivo > 0 && (float) $venta->total_deposito > 0)
        <tr class="totals__break"><td>Efectivo</td><td>Bs {{ number_format((float) $venta->total_efectivo, 2, ',', '.') }}</td></tr>
        <tr><td>Depósito / transferencia</td><td>Bs {{ number_format((float) $venta->total_deposito, 2, ',', '.') }}</td></tr>
    @endif
    <tr class="totals__grand"><td>Total</td><td>Bs {{ number_format((float) $venta->total, 2, ',', '.') }}</td></tr>
    @if((float) $venta->efectivo > 0)
        <tr><td>Recibido</td><td>Bs {{ number_format((float) $venta->efectivo, 2, ',', '.') }}</td></tr>
        <tr><td>Cambio</td><td>Bs {{ number_format((float) $venta->cambio, 2, ',', '.') }}</td></tr>
    @endif
</table>

@if($compact)
    <div class="ticket-meta">
        <div><span>Fecha</span><strong>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</strong></div>
        <div><span>Cliente</span><strong>{{ $venta->cliente }}</strong></div>
        <div><span>Pago</span><strong>{{ $venta->tipo_pago }} / {{ $venta->forma_pago }}</strong></div>
        <div><span>Atendido por</span><strong>{{ $venta->usuario }}</strong></div>
    </div>
@else
    <table class="meta-grid" role="presentation">
        <tr>
            <td><span>Cliente</span><strong>{{ $venta->cliente }}</strong></td>
            <td><span>Fecha de venta</span><strong>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</strong></td>
        </tr>
        <tr>
            <td><span>Condición de pago</span><strong>{{ $venta->tipo_pago }}</strong></td>
            <td><span>Forma de pago</span><strong>{{ $venta->forma_pago }}</strong></td>
        </tr>
        <tr>
            <td><span>Atendido por</span><strong>{{ $venta->usuario }}</strong></td>
            <td><span>Estado</span><strong class="status">{{ $venta->estado }}</strong></td>
        </tr>
    </table>
@endif

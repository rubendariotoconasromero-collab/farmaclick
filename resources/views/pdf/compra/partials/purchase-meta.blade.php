@if($compact)
    <div class="ticket-meta">
        <div><span>Fecha</span><strong>{{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</strong></div>
        <div><span>Proveedor</span><strong>{{ $compra->proveedor }}</strong></div>
        <div><span>Pago</span><strong>{{ $compra->tipo_pago }} / {{ $compra->forma_pago }}</strong></div>
        <div><span>Atendido por</span><strong>{{ $compra->usuario }}</strong></div>
    </div>
@else
    <table class="meta-grid" role="presentation">
        <tr>
            <td><span>Proveedor</span><strong>{{ $compra->proveedor }}</strong></td>
            <td><span>Fecha de compra</span><strong>{{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y') }}</strong></td>
        </tr>
        <tr>
            <td><span>Condicion de pago</span><strong>{{ $compra->tipo_pago }}</strong></td>
            <td><span>Forma de pago</span><strong>{{ $compra->forma_pago }}</strong></td>
        </tr>
        <tr>
            <td><span>Registrado por</span><strong>{{ $compra->usuario }}</strong></td>
            <td><span>Estado</span><strong class="status">{{ $compra->estado }}</strong></td>
        </tr>
    </table>
@endif

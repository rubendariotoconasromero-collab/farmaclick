<template>
    <div class="sale-record">
        <div class="sale-record__metrics">
            <app-metric-card label="Cliente" :value="datos.cliente || 'Sin cliente'" hint="Titular del comprobante" icon="icons/people.svg" tone="green" />
            <app-metric-card label="Fecha" :value="datos.fecha || '—'" hint="Fecha de la venta" icon="icons/calendar.svg" tone="cyan" />
            <app-metric-card label="Total" :value="money(total)" :hint="datos.tipoPago || 'Pago no registrado'" icon="icons/money.svg" tone="blue" />
        </div>

        <div class="sale-record__grid">
            <app-data-panel
                :eyebrow="editable ? 'Operación controlada' : 'Comprobante'"
                :title="editable ? 'Productos a devolver' : 'Productos vendidos'"
                :subtitle="editable ? 'Retire únicamente los productos que serán devueltos y confirme el ajuste.' : 'Detalle económico y cantidades registradas en la venta.'"
                flush
            >
                <app-table
                    :columns="columns"
                    :rows="details"
                    :loading="loading"
                    row-key="id"
                    min-width="720px"
                    empty-title="Sin productos"
                    empty-message="No se encontraron líneas asociadas a esta venta."
                >
                    <template v-if="editable" #cell-remove="{ row }">
                        <button
                            v-if="!row.isPackage"
                            type="button"
                            class="sale-record__remove"
                            :disabled="deleting || deletingIds.includes(row.id) || !canRemoveProduct"
                            title="Devolver producto"
                            @click="$emit('remove', row)"
                        >
                            <img :src="icon('trash.svg')" alt="">
                        </button>
                        <span v-else class="sale-record__package">Paquete</span>
                    </template>
                    <template #cell-producto="{ row }">
                        <strong>{{ row.articulo || row.nombre || 'Producto sin nombre' }}</strong>
                        <small v-if="row.marca">{{ row.marca }}</small>
                    </template>
                    <template #cell-costo_venta="{ value }">{{ money(value) }}</template>
                    <template #cell-sub_total="{ value }"><strong>{{ money(value) }}</strong></template>
                </app-table>
            </app-data-panel>

            <aside class="sale-record__summary">
                <span class="sale-record__summary-label">Resumen del comprobante</span>
                <dl>
                    <div><dt>Tipo de pago</dt><dd>{{ datos.tipoPago || '—' }}</dd></div>
                    <div v-if="datos.formaPago"><dt>Forma de pago</dt><dd>{{ datos.formaPago }}</dd></div>
                    <div><dt>Subtotal</dt><dd>{{ money(subtotal) }}</dd></div>
                    <div><dt>Descuento</dt><dd>{{ money(datos.descuento) }}</dd></div>
                    <div v-if="datos.formaPago === 'Mixta'">
                        <dt>Efectivo</dt>
                        <dd v-if="editable" class="sale-record__cash">
                            <input
                                type="number"
                                min="0"
                                :max="total"
                                step="0.01"
                                :value="datos.total_efectivo"
                                aria-label="Importe en efectivo"
                                @input="$emit('update-cash', $event.target.value)"
                            >
                            <span>Bs</span>
                        </dd>
                        <dd v-else>{{ money(datos.total_efectivo) }}</dd>
                    </div>
                    <div v-if="datos.formaPago === 'Mixta'"><dt>Depósito</dt><dd>{{ money(depositTotal) }}</dd></div>
                    <div class="sale-record__grand"><dt>Total</dt><dd>{{ money(total) }}</dd></div>
                </dl>
                <p v-if="editable" class="sale-record__notice">
                    <template v-if="pendingReturnCount">
                        {{ pendingReturnCount }} {{ pendingReturnCount === 1 ? 'producto preparado' : 'productos preparados' }} para devolución. Los cambios aún no se guardaron.
                    </template>
                    <template v-else>
                        Seleccione los productos que desea devolver. La venta debe conservar al menos un producto.
                    </template>
                </p>
                <app-button
                    v-if="editable"
                    block
                    icon="icons/check.svg"
                    :loading="saving"
                    :disabled="loading || deleting || pendingReturnCount === 0"
                    @click="$emit('save')"
                >
                    Confirmar devolución
                </app-button>
            </aside>
        </div>
    </div>
</template>

<script>
export default {
    name: 'StoreOneSaleRecordPanel',
    props: {
        datos: { type: Object, required: true },
        details: { type: Array, default: () => [] },
        editable: { type: Boolean, default: false },
        loading: { type: Boolean, default: false },
        saving: { type: Boolean, default: false },
        deleting: { type: Boolean, default: false },
        deletingIds: { type: Array, default: () => [] },
        pendingReturnCount: { type: Number, default: 0 },
    },
    computed: {
        columns() {
            const columns = [
                { key: 'producto', label: 'Producto' },
                { key: 'costo_venta', label: 'Precio' },
                { key: 'cantidad', label: 'Cantidad' },
                { key: 'sub_total', label: 'Subtotal' },
            ];
            return this.editable ? [{ key: 'remove', label: '' }, ...columns] : columns;
        },
        subtotal() {
            return this.details.reduce((sum, row) => sum + Number(row.costo_venta || 0) * Number(row.cantidad || 0), 0);
        },
        total() {
            return Math.max(0, this.subtotal - Number(this.datos.descuento || 0));
        },
        depositTotal() {
            return Math.max(0, this.total - Number(this.datos.total_efectivo || 0));
        },
        canRemoveProduct() {
            const regularProducts = this.details.filter(row => !row.isPackage).length;
            const packages = this.details.filter(row => row.isPackage).length;
            return regularProducts > 1 || packages > 0;
        },
    },
    methods: {
        money(value) {
            return `${Number(value || 0).toFixed(2)} Bs`;
        },
        icon(name) {
            const mainIndex = window.location.pathname.indexOf('/main');
            const base = mainIndex >= 0 ? window.location.pathname.substring(0, mainIndex) : '';
            return `${base}/icons/${name}`;
        },
    },
};
</script>

<style scoped>
.sale-record { display: grid; gap: 1rem; }
.sale-record__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .85rem; }
.sale-record__grid { display: grid; grid-template-columns: minmax(0, 1fr) 300px; gap: 1rem; align-items: start; }
.sale-record__summary { position: sticky; top: 1rem; overflow: hidden; padding: 1rem; background: #fff; border: 1px solid var(--system-border-color, #d8e5df); border-top: 4px solid var(--fc-cyan-500, #3ec6e0); border-radius: var(--system-radius-lg, 14px); box-shadow: var(--system-shadow, 0 6px 18px rgba(23,54,43,.07)); }
.sale-record__summary-label { color: var(--fc-blue-600, #0e93b5); font-size: .67rem; font-weight: 900; text-transform: uppercase; letter-spacing: .06em; }
.sale-record__summary dl { margin: .75rem 0 1rem; }
.sale-record__summary dl > div { display: flex; justify-content: space-between; gap: 1rem; padding: .55rem 0; border-bottom: 1px solid #e5ece8; }
.sale-record__summary dt { color: var(--system-text-muted, #6f817a); font-size: .73rem; font-weight: 700; }
.sale-record__summary dd { margin: 0; color: var(--fc-ink, #17362b); font-size: .76rem; font-weight: 900; text-align: right; }
.sale-record__summary .sale-record__grand { margin-top: .25rem; padding-top: .8rem; border-bottom: 0; }
.sale-record__grand dt, .sale-record__grand dd { color: var(--fc-green-700, #1f6b45); font-size: 1rem; }
.sale-record__cash { display: flex; align-items: center; gap: .35rem; }
.sale-record__cash input { width: 92px; min-height: 32px; padding: .3rem .45rem; color: var(--fc-ink, #17362b); font: inherit; font-size: .76rem; font-weight: 800; text-align: right; background: #fff; border: 1px solid #bdd2c9; border-radius: 7px; outline: 0; }
.sale-record__cash input:focus { border-color: var(--fc-blue-600, #0e93b5); box-shadow: 0 0 0 3px rgba(62,198,224,.16); }
.sale-record__notice { margin: 0 0 .8rem; padding: .7rem; color: #7a5d1c; font-size: .7rem; line-height: 1.45; background: #fff8e8; border: 1px solid #ecdba9; border-radius: 8px; }
.sale-record__remove { display: grid; width: 31px; height: 31px; place-items: center; background: #fff3f3; border: 1px solid #f0cece; border-radius: 7px; }
.sale-record__remove:disabled { cursor: not-allowed; opacity: .45; }
.sale-record__remove img { width: 14px; filter: invert(29%) sepia(72%) saturate(1248%) hue-rotate(324deg); }
.sale-record__package { display: inline-flex; padding: .25rem .45rem; color: #17647a; font-size: .62rem; font-weight: 900; background: #e8f8fb; border-radius: 999px; }
.sale-record strong { color: var(--fc-ink, #17362b); }
.sale-record strong + small { display: block; margin-top: .15rem; color: var(--system-text-muted, #6f817a); font-size: .68rem; }
@media (max-width: 1050px) { .sale-record__grid { grid-template-columns: 1fr; } .sale-record__summary { position: static; } }
@media (max-width: 760px) { .sale-record__metrics { grid-template-columns: 1fr; } }
</style>

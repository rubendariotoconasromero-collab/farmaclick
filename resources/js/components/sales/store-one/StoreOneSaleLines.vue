<template>
    <app-data-panel title="Productos y lotes" subtitle="Seleccione presentación y cantidad directamente en la hoja." eyebrow="Paso 2" flush>
        <template #actions>
            <app-button icon="icons/plus.svg" :disabled="disabled" @click="$emit('open-products')">Agregar productos</app-button>
        </template>
        <div class="sale-lines__legend">
            <span><i></i> Campos editables</span>
            <small>El stock se descuenta según la presentación seleccionada.</small>
        </div>
        <app-table :columns="columns" :rows="details" row-key="id_lote" min-width="1080px" empty-title="Sin productos agregados" empty-message="Use “Agregar productos” para seleccionar un lote del inventario.">
            <template #cell-product="{ row }"><strong>{{ row.articulo }}</strong><small>{{ row.laboratorio || row.presentacion }}</small></template>
            <template #cell-lot="{ row }"><strong>{{ row.lote || '—' }}</strong><small>Vence: {{ row.fecha_vecimiento || '—' }}</small></template>
            <template #cell-presentation="{ row }">
                <select :value="row.contador" @change="$emit('update-line', { row, field: 'contador', value: Number($event.target.value) })">
                    <option :value="0">Unitario</option>
                    <option :value="1" :disabled="!Number(row.cantidad_blister)">Blíster</option>
                    <option :value="2" :disabled="!Number(row.cantidad_caja)">Caja</option>
                </select>
            </template>
            <template #cell-quantity="{ row }"><input :value="row.cantidad" type="number" min="1" @input="$emit('update-line', { row, field: 'cantidad', value: $event.target.value })"></template>
            <template #cell-stock="{ row }"><span class="sale-lines__stock" :class="{ 'is-low': Number(row.descuento_stock) > Number(row.stock) }">{{ row.stock }}</span><small>{{ baseUnits(row) }} u. solicitadas</small></template>
            <template #cell-price="{ row }">{{ money(row.costo_venta) }}</template>
            <template #cell-subtotal="{ row }"><strong>{{ money(row.sub_total) }}</strong></template>
            <template #cell-actions="{ row }"><button class="sale-lines__remove" type="button" aria-label="Quitar producto" @click="$emit('remove', details.indexOf(row))">×</button></template>
        </app-table>
    </app-data-panel>
</template>

<script>
export default {
    name: 'StoreOneSaleLines',
    props: {
        details: { type: Array, default: () => [] },
        disabled: { type: Boolean, default: false },
    },
    data() {
        return {
            columns: [
                { key: 'product', label: 'Producto' }, { key: 'lot', label: 'Lote' },
                { key: 'presentation', label: 'Presentación' }, { key: 'quantity', label: 'Cantidad' },
                { key: 'stock', label: 'Stock base' }, { key: 'price', label: 'Precio' },
                { key: 'subtotal', label: 'Subtotal', className: 'text-right' }, { key: 'actions', label: '' },
            ],
        };
    },
    methods: {
        baseUnits(row) {
            return Number(row.descuento_stock || 0);
        },
        money(value) {
            return `${Number(value || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} Bs`;
        },
    },
};
</script>

<style scoped>
.sale-lines__legend { display: flex; justify-content: space-between; gap: .7rem; padding: .6rem 1rem; color: var(--system-text-muted, #5f716a); background: #f8fbf9; border-bottom: 1px solid var(--system-border-color, #d8e5df); }
.sale-lines__legend span { display: flex; align-items: center; gap: .4rem; color: var(--fc-blue-600, #0e93b5); font-size: .68rem; font-weight: 800; }
.sale-lines__legend i { width: 8px; height: 8px; background: var(--fc-cyan-500, #3ec6e0); border-radius: 50%; }
.sale-lines__legend small { font-size: .66rem; }
::v-deep .app-table td strong { display: block; color: var(--fc-ink, #17362b); }
::v-deep .app-table td small { display: block; margin-top: .12rem; color: var(--system-text-muted, #5f716a); font-size: .64rem; }
select, input { width: 100%; min-width: 86px; min-height: 36px; padding: .35rem .45rem; color: var(--fc-ink, #17362b); background: var(--fc-cyan-50, #effbfd); border: 1px solid #c6e7ed; border-radius: 7px; outline: 0; }
select:focus, input:focus { background: #fff; border-color: var(--fc-blue-600, #0e93b5); box-shadow: 0 0 0 2px var(--system-focus-ring, rgba(62,198,224,.24)); }
.sale-lines__stock { display: inline-flex; min-width: 36px; justify-content: center; padding: .24rem .45rem; color: var(--fc-green-700, #1f6b45); font-size: .7rem; font-weight: 900; background: var(--fc-green-50, #effaf4); border-radius: 999px; }
.sale-lines__stock.is-low { color: #a72f36; background: #fde8e9; }
.sale-lines__remove { display: grid; width: 30px; height: 30px; place-items: center; color: #b62e35; font-size: 1.15rem; background: #fff1f1; border: 1px solid #f1c9cb; border-radius: 7px; }
.sale-lines__remove:hover { color: #fff; background: var(--fc-danger, #d63c3c); }
@media (max-width: 650px) { .sale-lines__legend { align-items: flex-start; flex-direction: column; } }
</style>

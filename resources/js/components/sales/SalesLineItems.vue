<template>
    <app-data-panel
        title="Productos de la venta"
        subtitle="Edite precio y cantidad directamente en la hoja."
        eyebrow="Paso 2"
        flush
    >
        <template #actions>
            <app-button
                icon="icons/plus.svg"
                :disabled="disabled || !customerSelected"
                data-bs-toggle="modal"
                data-bs-target="#salesProductModal"
                @click="$emit('search-products')"
            >
                Agregar productos
            </app-button>
        </template>

        <div class="line-sheet__hint">
            <span><i></i>{{ priceLabel }}</span>
            <small>Los campos celestes son editables.</small>
        </div>

        <app-table
            :columns="columns"
            :rows="details"
            min-width="880px"
            row-key="id_articulo"
            empty-title="La venta todavía no tiene productos"
            empty-message="Seleccione un cliente y agregue productos desde el inventario."
        >
            <template #cell-product="{ row }">
                <strong class="line-sheet__product">{{ row.articulo }}</strong>
                <small>{{ row.tienda }}</small>
            </template>
            <template #cell-price="{ row }">
                <div class="line-sheet__money">
                    <span>Bs</span>
                    <input :value="linePrice(row)" type="number" min="0" step="0.01" @input="$emit('update-price', { row, value: $event.target.value })">
                </div>
            </template>
            <template #cell-quantity="{ row }">
                <input class="line-sheet__input" :value="row.cantidad" type="number" min="1" :max="row.stock" step="1" @input="$emit('update-quantity', { row, value: $event.target.value })">
            </template>
            <template #cell-stock="{ row }">
                <span class="line-sheet__stock" :class="{ 'is-low': Number(row.cantidad) > Number(row.stock) }">{{ row.stock }}</span>
            </template>
            <template #cell-subtotal="{ row }"><strong>{{ money(lineSubtotal(row)) }}</strong></template>
            <template #cell-actions="{ row }">
                <button class="line-sheet__remove" type="button" title="Quitar producto" aria-label="Quitar producto" @click="$emit('remove', details.indexOf(row))">×</button>
            </template>
        </app-table>
    </app-data-panel>
</template>

<script>
export default {
    name: 'SalesLineItems',
    props: {
        details: { type: Array, default: () => [] },
        priceField: { type: String, required: true },
        priceLabel: { type: String, required: true },
        customerSelected: { type: Boolean, default: false },
        disabled: { type: Boolean, default: false },
    },
    data() {
        return {
            columns: [
                { key: 'product', label: 'Producto' },
                { key: 'price', label: 'Precio aplicado' },
                { key: 'quantity', label: 'Cantidad' },
                { key: 'stock', label: 'Disponible' },
                { key: 'subtotal', label: 'Subtotal', className: 'text-right' },
                { key: 'actions', label: '' },
            ],
        };
    },
    methods: {
        linePrice(row) {
            return Number(row[this.priceField] || 0);
        },
        lineSubtotal(row) {
            return this.linePrice(row) * Number(row.cantidad || 0);
        },
        money(value) {
            return `${Number(value || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} Bs`;
        },
    },
};
</script>

<style scoped>
.line-sheet__hint { display: flex; align-items: center; justify-content: space-between; gap: .7rem; padding: .65rem 1rem; color: var(--system-text-muted, #5f716a); background: #f8fbf9; border-bottom: 1px solid var(--system-border-color, #d8e5df); }
.line-sheet__hint span { display: flex; align-items: center; gap: .4rem; color: var(--fc-blue-600, #0e93b5); font-size: .7rem; font-weight: 800; }
.line-sheet__hint i { width: 7px; height: 7px; background: var(--fc-cyan-500, #3ec6e0); border-radius: 50%; }
.line-sheet__hint small { font-size: .67rem; }
.line-sheet__product { display: block; max-width: 360px; overflow: hidden; color: var(--fc-ink, #17362b); text-overflow: ellipsis; white-space: nowrap; }
.line-sheet__product + small { display: block; margin-top: .12rem; color: var(--system-text-muted, #5f716a); font-size: .65rem; }
.line-sheet__money { display: flex; min-width: 112px; overflow: hidden; background: var(--fc-cyan-50, #effbfd); border: 1px solid #c6e7ed; border-radius: 7px; }
.line-sheet__money span { display: grid; padding: 0 .45rem; place-items: center; color: var(--fc-blue-600, #0e93b5); font-size: .68rem; font-weight: 800; }
.line-sheet__money input, .line-sheet__input { width: 100%; min-width: 70px; min-height: 36px; padding: .35rem .45rem; color: var(--fc-ink, #17362b); background: var(--fc-cyan-50, #effbfd); border: 1px solid #c6e7ed; border-radius: 7px; outline: 0; }
.line-sheet__money input { border: 0; border-left: 1px solid #c6e7ed; border-radius: 0; text-align: right; }
.line-sheet__money:focus-within, .line-sheet__input:focus { background: #fff; border-color: var(--fc-blue-600, #0e93b5); box-shadow: 0 0 0 2px var(--system-focus-ring, rgba(62,198,224,.24)); }
.line-sheet__stock { display: inline-flex; min-width: 34px; justify-content: center; padding: .25rem .45rem; color: var(--fc-green-700, #1f6b45); font-size: .7rem; font-weight: 900; background: var(--fc-green-50, #effaf4); border-radius: 999px; }
.line-sheet__stock.is-low { color: #a72f36; background: #fde8e9; }
.line-sheet__remove { display: grid; width: 30px; height: 30px; place-items: center; color: #b62e35; font-size: 1.15rem; background: #fff1f1; border: 1px solid #f1c9cb; border-radius: 7px; }
.line-sheet__remove:hover { color: #fff; background: var(--fc-danger, #d63c3c); }
@media (max-width: 650px) { .line-sheet__hint { align-items: flex-start; flex-direction: column; } }
</style>

<template>
    <div id="salesProductModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content picker-modal">
                <div class="modal-header">
                    <div><small>Inventario disponible</small><h5>Agregar productos a la venta</h5></div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="picker-modal__toolbar">
                        <div>
                            <strong>{{ priceLabel }}</strong>
                            <small>La tarifa está definida por el cliente seleccionado.</small>
                        </div>
                        <div class="picker-modal__search">
                            <app-input :value="search" placeholder="Nombre comercial del producto…" @input="$emit('update:search', $event)" @keyup.enter="$emit('search')" />
                            <app-button icon="icons/magnifying-glass.svg" @click="$emit('search')">Buscar</app-button>
                        </div>
                    </div>
                    <app-table :columns="columns" :rows="products" min-width="940px" empty-title="Sin productos disponibles" empty-message="Cambie el término de búsqueda o revise el inventario.">
                        <template #cell-articulo="{ row }"><strong>{{ row.articulo }}</strong><small>{{ row.tienda }}</small></template>
                        <template #cell-costo_unitario="{ value }">{{ money(value) }}</template>
                        <template #cell-costo_mayorista="{ value }">{{ money(value) }}</template>
                        <template #cell-costo_preferencial="{ value }">{{ money(value) }}</template>
                        <template #cell-stock="{ value }"><span class="picker-modal__stock" :class="{ 'is-empty': Number(value) <= 0 }">{{ value }}</span></template>
                        <template #cell-action="{ row }">
                            <app-button variant="secondary" :disabled="Number(row.stock) <= 0 || isSelected(row)" @click="$emit('select', row)">
                                {{ isSelected(row) ? 'Agregado' : 'Agregar' }}
                            </app-button>
                        </template>
                    </app-table>
                </div>
                <div class="modal-footer"><app-button variant="ghost" data-bs-dismiss="modal" @click="$emit('close')">Terminar</app-button></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SalesProductPickerModal',
    props: {
        products: { type: Array, default: () => [] },
        selectedIds: { type: Array, default: () => [] },
        search: { type: String, default: '' },
        priceLabel: { type: String, required: true },
    },
    data() {
        return {
            columns: [
                { key: 'articulo', label: 'Producto' }, { key: 'costo_unitario', label: 'Unitario' },
                { key: 'costo_mayorista', label: 'Mayorista' }, { key: 'costo_preferencial', label: 'Preferencial' },
                { key: 'stock', label: 'Stock' }, { key: 'action', label: '' },
            ],
        };
    },
    methods: {
        isSelected(row) {
            return this.selectedIds.includes(Number(row.id_articulo));
        },
        money(value) {
            return `${Number(value || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} Bs`;
        },
    },
};
</script>

<style scoped>
.picker-modal { border: 0; }
.picker-modal .modal-header { color: #fff; background: linear-gradient(110deg, var(--system-sidebar-bg, #163f32), var(--fc-green-600, #1f8a4c)); border-bottom: 3px solid var(--fc-cyan-500, #3ec6e0); }
.picker-modal .modal-header h5 { margin: .1rem 0 0; font-weight: 800; }
.picker-modal .modal-header small { color: #80dcec; font-size: .65rem; font-weight: 900; text-transform: uppercase; letter-spacing: .07em; }
.picker-modal .modal-body { padding: 1.1rem; background: var(--system-body-bg, #f4f9f7); }
.picker-modal__toolbar { display: grid; grid-template-columns: minmax(220px, .55fr) minmax(360px, 1fr); gap: 1rem; align-items: end; margin-bottom: 1rem; padding: .8rem; background: #fff; border: 1px solid var(--system-border-color, #d8e5df); border-radius: var(--system-radius, 9px); }
.picker-modal__toolbar > div:first-child { display: flex; flex-direction: column; }
.picker-modal__toolbar strong { color: var(--fc-blue-600, #0e93b5); }
.picker-modal__toolbar small { color: var(--system-text-muted, #5f716a); font-size: .67rem; }
.picker-modal__search { display: grid; grid-template-columns: 1fr auto; gap: .55rem; }
.picker-modal ::v-deep .app-table td strong { display: block; color: var(--fc-ink, #17362b); }
.picker-modal ::v-deep .app-table td small { color: var(--system-text-muted, #5f716a); font-size: .65rem; }
.picker-modal__stock { display: inline-flex; min-width: 34px; justify-content: center; padding: .25rem .45rem; color: var(--fc-green-700, #1f6b45); font-size: .7rem; font-weight: 900; background: var(--fc-green-50, #effaf4); border-radius: 999px; }
.picker-modal__stock.is-empty { color: #a72f36; background: #fde8e9; }
@media (max-width: 780px) { .picker-modal__toolbar, .picker-modal__search { grid-template-columns: 1fr; } }
</style>

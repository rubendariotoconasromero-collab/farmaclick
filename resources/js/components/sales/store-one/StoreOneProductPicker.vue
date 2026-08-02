<template>
    <div v-if="open" class="product-picker" role="dialog" aria-modal="true" aria-labelledby="store-product-picker-title">
        <div class="product-picker__dialog">
            <header>
                <div><small>Inventario de Tienda Primera</small><h5 id="store-product-picker-title">Agregar productos por lote</h5></div>
                <button type="button" aria-label="Cerrar" @click="$emit('close')">×</button>
            </header>
            <div class="product-picker__body">
                <div class="product-picker__filters">
                    <select :value="criterion" @change="$emit('update:criterion', $event.target.value)">
                        <option value="articulo.nombre_comercial">Nombre comercial</option>
                        <option value="articulo.nombre_generico">Nombre genérico</option>
                        <option value="unidad_medida.nombre">Presentación</option>
                        <option value="proveedor.nombre">Laboratorio</option>
                        <option value="articulo.descripcion">Acción terapéutica</option>
                        <option value="categoria.cod_proveedor">Código proveedor</option>
                    </select>
                    <app-input :value="search" placeholder="Buscar en el inventario…" @input="$emit('update:search', $event)" @keyup.enter="$emit('search')" />
                    <select :value="supplierId" @change="$emit('update:supplierId', Number($event.target.value))">
                        <option :value="0">Todos los laboratorios</option>
                        <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">{{ supplier.nombre }}</option>
                    </select>
                    <app-button icon="icons/magnifying-glass.svg" @click="$emit('search')">Buscar</app-button>
                </div>
                <app-table :columns="columns" :rows="products" row-key="id" min-width="1180px" :row-class="rowClass" empty-title="Sin productos" empty-message="No se encontraron lotes para los filtros seleccionados.">
                    <template #cell-product="{ row }"><strong>{{ row.articulo }}</strong><small>{{ row.nombre_generico || 'Sin nombre genérico' }}</small></template>
                    <template #cell-presentation="{ row }"><strong>{{ row.presentacion }}</strong><small>{{ row.laboratorio }}</small></template>
                    <template #cell-expiry="{ row }"><strong>{{ row.fecha_vecimiento }}</strong><small>Lote: {{ row.lote }}</small></template>
                    <template #cell-costo_unitario="{ value }">{{ money(value) }}</template>
                    <template #cell-precio_blister="{ value }">{{ money(value) }}</template>
                    <template #cell-precio_caja="{ value }">{{ money(value) }}</template>
                    <template #cell-stock="{ value }"><span class="product-picker__stock" :class="{ 'is-empty': Number(value) <= 0 }">{{ value }}</span></template>
                    <template #cell-action="{ row }"><app-button variant="secondary" :disabled="Number(row.stock) <= 0 || selectedIds.includes(Number(row.id))" @click="$emit('select', row)">{{ selectedIds.includes(Number(row.id)) ? 'Agregado' : 'Agregar' }}</app-button></template>
                </app-table>
                <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
            </div>
            <footer><app-button variant="ghost" @click="$emit('close')">Terminar</app-button></footer>
        </div>
    </div>
</template>

<script>
import moment from 'moment';

export default {
    name: 'StoreOneProductPicker',
    props: {
        open: { type: Boolean, default: false },
        products: { type: Array, default: () => [] },
        suppliers: { type: Array, default: () => [] },
        selectedIds: { type: Array, default: () => [] },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        search: { type: String, default: '' },
        criterion: { type: String, default: 'articulo.nombre_comercial' },
        supplierId: { type: Number, default: 0 },
    },
    data() {
        return {
            columns: [
                { key: 'product', label: 'Producto' }, { key: 'presentation', label: 'Presentación / laboratorio' },
                { key: 'expiry', label: 'Vencimiento / lote' }, { key: 'costo_unitario', label: 'Unidad' },
                { key: 'precio_blister', label: 'Blíster' }, { key: 'precio_caja', label: 'Caja' },
                { key: 'stock', label: 'Stock' }, { key: 'action', label: '' },
            ],
        };
    },
    methods: {
        isExpired(date) {
            return date ? moment(date).endOf('day').isBefore(moment()) : false;
        },
        rowClass(row) {
            return this.isExpired(row.fecha_vecimiento) ? 'is-expired' : '';
        },
        money(value) {
            return `${Number(value || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} Bs`;
        },
    },
};
</script>

<style scoped>
.product-picker { position: fixed; inset: 0; z-index: 1060; display: grid; padding: .75rem; place-items: center; background: rgba(11, 45, 35, .58); backdrop-filter: blur(3px); }
.product-picker__dialog { display: grid; width: min(1500px, 100%); height: min(900px, 96vh); grid-template-rows: auto 1fr auto; overflow: hidden; background: var(--system-body-bg, #f4f9f7); border-radius: var(--system-radius-lg, 14px); box-shadow: var(--system-shadow-lg, 0 24px 70px rgba(10,56,42,.26)); }
.product-picker__dialog > header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.2rem; color: #fff; background: linear-gradient(110deg, var(--system-sidebar-bg, #163f32), var(--fc-green-600, #1f8a4c)); border-bottom: 3px solid var(--fc-cyan-500, #3ec6e0); }
.product-picker__dialog header h5 { margin: .1rem 0 0; font-weight: 800; }
.product-picker__dialog header small { color: #80dcec; font-size: .65rem; font-weight: 900; text-transform: uppercase; letter-spacing: .07em; }
.product-picker__dialog header button { width: 36px; height: 36px; color: #fff; font-size: 1.5rem; background: rgba(255,255,255,.1); border: 0; border-radius: 9px; }
.product-picker__body { min-height: 0; padding: 1rem; overflow: auto; }
.product-picker__filters { display: grid; grid-template-columns: 210px minmax(260px, 1fr) 220px auto; gap: .55rem; margin-bottom: 1rem; }
.product-picker__filters select { min-height: 40px; padding: .48rem .65rem; color: var(--fc-ink, #17362b); background: #fff; border: 1px solid #bdd2c9; border-radius: var(--system-radius, 9px); }
.product-picker ::v-deep .app-table td strong { display: block; color: var(--fc-ink, #17362b); }
.product-picker ::v-deep .app-table td small { color: var(--system-text-muted, #5f716a); font-size: .64rem; }
.product-picker ::v-deep .app-table__scroll { max-height: calc(96vh - 250px); }
.product-picker ::v-deep tr.is-expired td { background: #fff0f0 !important; }
.product-picker__stock { display: inline-flex; min-width: 34px; justify-content: center; padding: .25rem .45rem; color: var(--fc-green-700, #1f6b45); font-weight: 900; background: var(--fc-green-50, #effaf4); border-radius: 999px; }
.product-picker__stock.is-empty { color: #a72f36; background: #fde8e9; }
.product-picker__dialog > footer { display: flex; justify-content: flex-end; padding: .7rem 1rem; background: #fff; border-top: 1px solid var(--system-border-color, #d8e5df); }
@media (max-width: 950px) { .product-picker__filters { grid-template-columns: 1fr 1fr; } }
@media (max-width: 620px) { .product-picker { padding: 0; } .product-picker__dialog { height: 100vh; border-radius: 0; } .product-picker__filters { grid-template-columns: 1fr; } }
</style>

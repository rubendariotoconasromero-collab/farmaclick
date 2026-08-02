<template>
    <warehouse-page-skeleton v-if="loading" :columns="5" />
    <main v-else class="warehouse-lots">
        <app-module-header
            eyebrow="Almacén · Trazabilidad"
            title="Registro de lotes"
            subtitle="Asigna cantidad, código de lote y fecha de vencimiento a los productos seleccionados."
        >
            <template #actions>
                <app-button icon="img/menu/Almacen.png" @click="openPicker">Agregar productos</app-button>
            </template>
        </app-module-header>

        <section class="warehouse-lots__metrics">
            <app-metric-card label="Productos seleccionados" :value="details.length" hint="Líneas del registro" icon="img/menu/Almacen.png" />
            <app-metric-card label="Unidades ingresadas" :value="totalUnits" hint="Suma de cantidades" icon="img/menu/control.png" tone="cyan" />
            <app-metric-card label="Estado" :value="details.length ? 'En preparación' : 'Sin productos'" hint="Registro actual" icon="img/menu/historial.png" tone="neutral" />
        </section>

        <app-data-panel
            eyebrow="Hoja de carga"
            title="Productos y vencimientos"
            subtitle="Completa los datos obligatorios de cada lote antes de guardar."
            flush
        >
            <app-table
                :columns="detailColumns"
                :rows="details"
                row-key="id_articulo"
                min-width="840px"
                empty-title="No hay productos seleccionados"
                empty-message="Usa “Agregar productos” para comenzar el registro de lotes."
            >
                <template #cell-cantidad="{ row }">
                    <input v-model.number="row.cantidad" class="warehouse-lots__cell-input" type="number" min="1">
                </template>
                <template #cell-fecha_vecimiento="{ row }">
                    <input v-model="row.fecha_vecimiento" class="warehouse-lots__cell-input" type="date">
                </template>
                <template #cell-lote="{ row }">
                    <input v-model.trim="row.lote" class="warehouse-lots__cell-input" type="text" placeholder="Código de lote">
                </template>
                <template #cell-actions="{ row }">
                    <app-button variant="danger" @click="$emit('remove-detail', details.indexOf(row))">Quitar</app-button>
                </template>
            </app-table>
            <footer class="warehouse-lots__footer">
                <span>{{ details.length }} producto(s) · {{ totalUnits }} unidad(es)</span>
                <div>
                    <app-button variant="ghost" :disabled="saving || !details.length" @click="$emit('clear')">Limpiar</app-button>
                    <app-button :loading="saving" :disabled="!canSave" @click="$emit('save')">Guardar lotes</app-button>
                </div>
            </footer>
        </app-data-panel>

        <div v-if="pickerOpen" class="warehouse-lots__backdrop" @click.self="pickerOpen = false">
            <section class="warehouse-lots__picker" role="dialog" aria-modal="true" aria-label="Seleccionar productos">
                <header>
                    <div><span>Almacén</span><h2>Agregar productos</h2></div>
                    <button type="button" @click="pickerOpen = false">×</button>
                </header>
                <warehouse-list-toolbar
                    :search="search"
                    :criterion="criterion"
                    :loading="productLoading"
                    :options="criteria"
                    placeholder="Buscar productos…"
                    @update:search="$emit('update:search', $event)"
                    @update:criterion="$emit('update:criterion', $event)"
                    @search="$emit('search')"
                />
                <app-table
                    :columns="productColumns"
                    :rows="products"
                    :loading="productLoading"
                    row-key="id"
                    min-width="980px"
                    empty-title="Sin productos disponibles"
                    empty-message="No se encontraron productos con los filtros actuales."
                >
                    <template #cell-costo_compra="{ value }">{{ money(value) }}</template>
                    <template #cell-costo_unitario="{ value }">{{ money(value) }}</template>
                    <template #cell-actions="{ row }"><app-button @click="$emit('select-product', row)">Agregar</app-button></template>
                </app-table>
                <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
            </section>
        </div>
    </main>
</template>

<script>
import WarehouseListToolbar from './WarehouseListToolbar.vue';

export default {
    name: 'WarehouseLotWorkspace',
    components: { WarehouseListToolbar },
    props: {
        products: { type: Array, default: () => [] },
        details: { type: Array, default: () => [] },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        search: { type: String, default: '' },
        criterion: { type: String, default: 'articulo.nombre_comercial' },
        loading: { type: Boolean, default: false },
        productLoading: { type: Boolean, default: false },
        saving: { type: Boolean, default: false },
    },
    data() {
        return {
            pickerOpen: false,
            criteria: [
                { value: 'articulo.nombre_comercial', label: 'Producto' },
                { value: 'unidad_medida.nombre', label: 'Presentación' },
                { value: 'proveedor.nombre', label: 'Laboratorio' },
                { value: 'categoria.nombre', label: 'Categoría' },
            ],
            detailColumns: [
                { key: 'articulo', label: 'Producto' }, { key: 'cantidad', label: 'Cantidad' },
                { key: 'fecha_vecimiento', label: 'Vencimiento' }, { key: 'lote', label: 'Lote' },
                { key: 'actions', label: 'Acciones' },
            ],
            productColumns: [
                { key: 'articulo', label: 'Producto' }, { key: 'nombre_generico', label: 'Genérico' },
                { key: 'presentacion', label: 'Presentación' }, { key: 'laboratorio', label: 'Laboratorio' },
                { key: 'categoria', label: 'Categoría' }, { key: 'costo_compra', label: 'Costo' },
                { key: 'costo_unitario', label: 'Precio' }, { key: 'stock', label: 'Stock' },
                { key: 'actions', label: 'Acciones' },
            ],
        };
    },
    computed: {
        totalUnits() {
            return this.details.reduce((total, row) => total + Number(row.cantidad || 0), 0);
        },
        canSave() {
            return this.details.length > 0 && this.details.every(row => Number(row.cantidad) > 0 && row.fecha_vecimiento && row.lote);
        },
    },
    methods: {
        money(value) {
            return `${Number(value || 0).toFixed(2)} Bs`;
        },
        openPicker() {
            this.pickerOpen = true;
            this.$emit('open-products');
        },
    },
};
</script>

<style scoped>
.warehouse-lots { display: grid; gap: 1rem; padding: 1.25rem; background: #f4f8f6; }
.warehouse-lots__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
.warehouse-lots__cell-input { min-width: 145px; min-height: 40px; padding: .5rem .7rem; color: #17362b; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; }
.warehouse-lots__footer { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem; color: #5f716a; font-size: .75rem; font-weight: 800; background: #f8fbf9; border-top: 1px solid #d8e5df; }
.warehouse-lots__footer div { display: flex; gap: .5rem; }
.warehouse-lots__backdrop { position: fixed; z-index: 1050; inset: 0; display: grid; padding: 1rem; place-items: center; background: rgba(16, 45, 35, .58); backdrop-filter: blur(3px); }
.warehouse-lots__picker { width: min(1180px, 100%); max-height: calc(100vh - 2rem); overflow: auto; background: #fff; border-radius: 16px; box-shadow: 0 24px 70px rgba(10, 35, 27, .28); }
.warehouse-lots__picker > header { display: flex; justify-content: space-between; padding: 1.1rem 1.25rem; color: #fff; background: linear-gradient(115deg, #163f32, #1f8a4c); border-bottom: 3px solid #3ec6e0; }
.warehouse-lots__picker header span { color: #71d5e8; font-size: .65rem; font-weight: 800; text-transform: uppercase; }
.warehouse-lots__picker h2 { margin: .2rem 0 0; font-size: 1.1rem; }
.warehouse-lots__picker header button { color: #fff; font-size: 1.6rem; background: transparent; border: 0; }
@media (max-width: 900px) { .warehouse-lots__metrics { grid-template-columns: 1fr; } }
@media (max-width: 650px) { .warehouse-lots { padding: .75rem; } .warehouse-lots__footer { align-items: stretch; flex-direction: column; } }
</style>

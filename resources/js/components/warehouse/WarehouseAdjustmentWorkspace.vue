<template>
    <warehouse-page-skeleton v-if="loading" :columns="8" />
    <main v-else class="warehouse-adjustment">
        <app-module-header
            eyebrow="Almacén · Movimientos"
            :title="view === 'form' ? 'Nuevo ajuste de inventario' : 'Ajustes'"
            :subtitle="view === 'form' ? 'Selecciona un motivo y registra los productos que modificarán su existencia.' : 'Consulta la trazabilidad de entradas, salidas y correcciones de inventario.'"
        >
            <template #actions>
                <app-button v-if="view === 'form'" variant="ghost" @click="$emit('cancel')">Cancelar</app-button>
                <app-button v-else icon="img/menu/configuracion.png" @click="$emit('create')">Nuevo ajuste</app-button>
            </template>
        </app-module-header>

        <section class="warehouse-adjustment__metrics">
            <app-metric-card label="Ajustes registrados" :value="pagination.total || rows.length" hint="Historial disponible" icon="img/menu/Almacen.png" />
            <app-metric-card label="Productos en ajuste" :value="details.length" hint="Detalle de la operación actual" icon="img/menu/control.png" tone="cyan" />
            <app-metric-card label="Estado" :value="view === 'form' ? 'En preparación' : 'Consulta'" hint="Flujo operativo" icon="img/menu/historial.png" tone="neutral" />
        </section>

        <app-data-panel v-if="view === 'list'" eyebrow="Historial" title="Movimientos de inventario" subtitle="Busca por producto, categoría o motivo." flush>
            <warehouse-list-toolbar
                :search="search"
                :criterion="criterion"
                :loading="tableLoading"
                :options="historyCriteria"
                placeholder="Buscar ajustes…"
                @update:search="$emit('update:search', $event)"
                @update:criterion="$emit('update:criterion', $event)"
                @search="$emit('search')"
            />
            <app-table
                :columns="historyColumns"
                :rows="rows"
                :loading="tableLoading"
                row-key="id"
                min-width="980px"
                empty-title="Sin ajustes registrados"
                empty-message="No se encontraron movimientos con el criterio seleccionado."
            >
                <template #cell-stock="{ row }">{{ quantityValue(row, 'stock') }}</template>
                <template #cell-stock_anterior="{ row }">{{ quantityValue(row, 'stock_anterior') }}</template>
                <template #cell-stock_actual="{ row }"><strong>{{ quantityValue(row, 'stock_actual') }}</strong></template>
            </app-table>
            <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
        </app-data-panel>

        <template v-else>
            <app-data-panel eyebrow="Configuración" title="Motivo y productos" subtitle="El motivo determina qué valores deben registrarse.">
                <div class="warehouse-adjustment__setup">
                    <label>
                        <span>Motivo del ajuste</span>
                        <select :value="data.id_motivo_ajuste" @change="$emit('update:reason', Number($event.target.value))">
                            <option :value="0" disabled>Selecciona un motivo</option>
                            <option v-for="reason in reasons" :key="reason.id" :value="reason.id">{{ reason.nombre }}</option>
                        </select>
                    </label>
                    <app-button :disabled="Number(data.id_motivo_ajuste) === 0" @click="openPicker">
                        Agregar productos
                    </app-button>
                </div>
            </app-data-panel>

            <app-data-panel eyebrow="Hoja de ajuste" title="Detalle de productos" subtitle="Verifica cantidades, valores y observaciones antes de guardar." flush>
                <app-table
                    :columns="detailColumns"
                    :rows="details"
                    row-key="id_lote"
                    min-width="980px"
                    empty-title="Ajuste sin productos"
                    empty-message="Selecciona un motivo y agrega los productos que deseas modificar."
                >
                    <template #cell-value="{ row }">
                        <div class="warehouse-adjustment__stock-fields">
                            <span v-if="isStockReason">Actual: {{ row.saldoStock || 0 }}</span>
                            <input v-if="isStockReason" v-model.number="row.stock" type="number" min="0">
                            <input v-else-if="Number(data.id_motivo_ajuste) === 4" v-model.number="row.costo_compra" type="number" min="0" step="0.01">
                            <input v-else-if="Number(data.id_motivo_ajuste) === 5" v-model.number="row.costo_unitario" type="number" min="0" step="0.01">
                            <span v-else>Sin valor adicional</span>
                        </div>
                    </template>
                    <template #cell-observacion="{ row }"><input v-model="row.observacion" class="warehouse-adjustment__cell-input" type="text" placeholder="Observación"></template>
                    <template #cell-actions="{ row }"><app-button variant="danger" @click="$emit('remove-detail', details.indexOf(row))">Quitar</app-button></template>
                </app-table>
                <footer class="warehouse-adjustment__footer">
                    <span>{{ details.length }} producto(s) en el ajuste</span>
                    <div>
                        <app-button variant="ghost" @click="$emit('cancel')">Cancelar</app-button>
                        <app-button :loading="saving" :disabled="!details.length || Number(data.id_motivo_ajuste) === 0" @click="$emit('save')">
                            Guardar ajuste
                        </app-button>
                    </div>
                </footer>
            </app-data-panel>
        </template>

        <div v-if="pickerOpen" class="warehouse-adjustment__backdrop" @click.self="pickerOpen = false">
            <section class="warehouse-adjustment__picker" role="dialog" aria-modal="true" aria-label="Seleccionar productos">
                <header>
                    <div><span>Almacén</span><h2>Seleccionar productos</h2></div>
                    <button type="button" @click="pickerOpen = false">×</button>
                </header>
                <warehouse-list-toolbar
                    :search="productSearch"
                    :criterion="productCriterion"
                    :loading="productLoading"
                    :options="productCriteria"
                    placeholder="Buscar productos…"
                    @update:search="$emit('update:product-search', $event)"
                    @update:criterion="$emit('update:product-criterion', $event)"
                    @search="$emit('search-products')"
                />
                <app-table
                    :columns="productColumns"
                    :rows="products"
                    :loading="productLoading"
                    row-key="id_lote"
                    min-width="920px"
                    empty-title="Sin productos"
                    empty-message="Cambia el criterio o término de búsqueda."
                >
                    <template #cell-costo_compra="{ value }">{{ money(value) }}</template>
                    <template #cell-costo_unitario="{ value }">{{ money(value) }}</template>
                    <template #cell-actions="{ row }">
                        <app-button @click="selectProduct(row)">Agregar</app-button>
                    </template>
                </app-table>
                <purchase-pagination :pagination="productPagination" :pages="productPages" @change="$emit('product-page', $event)" />
            </section>
        </div>
    </main>
</template>

<script>
import WarehouseListToolbar from './WarehouseListToolbar.vue';

export default {
    name: 'WarehouseAdjustmentWorkspace',
    components: { WarehouseListToolbar },
    props: {
        rows: { type: Array, default: () => [] },
        products: { type: Array, default: () => [] },
        details: { type: Array, default: () => [] },
        reasons: { type: Array, default: () => [] },
        data: { type: Object, required: true },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        productPagination: { type: Object, required: true },
        productPages: { type: Array, default: () => [] },
        search: { type: String, default: '' },
        criterion: { type: String, default: 'articulo.nombre_comercial' },
        productSearch: { type: String, default: '' },
        productCriterion: { type: String, default: 'articulo.nombre_comercial' },
        view: { type: String, default: 'list' },
        loading: { type: Boolean, default: false },
        tableLoading: { type: Boolean, default: false },
        productLoading: { type: Boolean, default: false },
        saving: { type: Boolean, default: false },
    },
    data() {
        return {
            pickerOpen: false,
            historyCriteria: [
                { value: 'articulo.nombre_comercial', label: 'Producto' },
                { value: 'categoria.nombre', label: 'Categoría' },
                { value: 'motivo_ajuste.nombre', label: 'Motivo' },
            ],
            productCriteria: [
                { value: 'articulo.nombre_comercial', label: 'Producto' },
                { value: 'unidad_medida.nombre', label: 'Presentación' },
                { value: 'proveedor.nombre', label: 'Laboratorio' },
                { value: 'categoria.nombre', label: 'Categoría' },
            ],
            historyColumns: [
                { key: 'lote', label: 'Lote' }, { key: 'categoria', label: 'Categoría' },
                { key: 'producto', label: 'Producto' }, { key: 'fecha_vecimiento', label: 'Vencimiento' },
                { key: 'motivo_ajuste', label: 'Motivo' }, { key: 'stock', label: 'Cantidad' },
                { key: 'stock_anterior', label: 'Stock anterior' }, { key: 'stock_actual', label: 'Stock actual' },
            ],
            detailColumns: [
                { key: 'categoria', label: 'Categoría' }, { key: 'articulo', label: 'Producto' },
                { key: 'value', label: 'Valor del ajuste' }, { key: 'observacion', label: 'Observación' },
                { key: 'fecha_vencimiento', label: 'Vencimiento' }, { key: 'lote', label: 'Lote' },
                { key: 'actions', label: 'Acciones' },
            ],
            productColumns: [
                { key: 'lote', label: 'Lote' }, { key: 'categoria', label: 'Categoría' },
                { key: 'articulo', label: 'Producto' }, { key: 'presentacion', label: 'Presentación' },
                { key: 'laboratorio', label: 'Laboratorio' }, { key: 'costo_compra', label: 'Costo' },
                { key: 'costo_unitario', label: 'Precio' }, { key: 'cantidad', label: 'Stock' },
                { key: 'actions', label: 'Acciones' },
            ],
        };
    },
    computed: {
        isStockReason() {
            return [1, 2, 3].includes(Number(this.data.id_motivo_ajuste));
        },
    },
    watch: {
        view(value) {
            if (value !== 'form') this.pickerOpen = false;
        },
    },
    methods: {
        quantityValue(row, field) {
            return [4, 5].includes(Number(row.id_motivo_ajuste)) ? '—' : (row[field] ?? '—');
        },
        money(value) {
            return `${Number(value || 0).toFixed(2)} Bs`;
        },
        openPicker() {
            this.pickerOpen = true;
            this.$emit('open-products');
        },
        selectProduct(row) {
            this.$emit('select-product', row);
        },
    },
};
</script>

<style scoped>
.warehouse-adjustment { display: grid; gap: 1rem; padding: 1.25rem; background: #f4f8f6; }
.warehouse-adjustment__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
.warehouse-adjustment__setup { display: grid; grid-template-columns: minmax(260px, 1fr) auto; align-items: end; gap: 1rem; }
.warehouse-adjustment__setup label { display: grid; gap: .35rem; color: #315044; font-size: .72rem; font-weight: 800; }
.warehouse-adjustment__setup select, .warehouse-adjustment__stock-fields input, .warehouse-adjustment__cell-input { min-height: 40px; padding: .5rem .7rem; color: #17362b; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; }
.warehouse-adjustment__stock-fields { display: flex; align-items: center; gap: .5rem; }
.warehouse-adjustment__stock-fields span { white-space: nowrap; }
.warehouse-adjustment__stock-fields input { width: 110px; }
.warehouse-adjustment__cell-input { min-width: 170px; }
.warehouse-adjustment__footer { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem; color: #5f716a; font-size: .75rem; font-weight: 800; background: #f8fbf9; border-top: 1px solid #d8e5df; }
.warehouse-adjustment__footer div { display: flex; gap: .5rem; }
.warehouse-adjustment__backdrop { position: fixed; z-index: 1050; inset: 0; display: grid; padding: 1rem; place-items: center; background: rgba(16, 45, 35, .58); backdrop-filter: blur(3px); }
.warehouse-adjustment__picker { width: min(1180px, 100%); max-height: calc(100vh - 2rem); overflow: auto; background: #fff; border-radius: 16px; box-shadow: 0 24px 70px rgba(10, 35, 27, .28); }
.warehouse-adjustment__picker > header { display: flex; justify-content: space-between; padding: 1.1rem 1.25rem; color: #fff; background: linear-gradient(115deg, #163f32, #1f8a4c); border-bottom: 3px solid #3ec6e0; }
.warehouse-adjustment__picker header span { color: #71d5e8; font-size: .65rem; font-weight: 800; text-transform: uppercase; }
.warehouse-adjustment__picker h2 { margin: .2rem 0 0; font-size: 1.1rem; }
.warehouse-adjustment__picker header button { color: #fff; font-size: 1.6rem; background: transparent; border: 0; }
@media (max-width: 900px) { .warehouse-adjustment__metrics { grid-template-columns: 1fr; } }
@media (max-width: 650px) { .warehouse-adjustment { padding: .75rem; } .warehouse-adjustment__setup { grid-template-columns: 1fr; } .warehouse-adjustment__footer { align-items: stretch; flex-direction: column; } }
</style>

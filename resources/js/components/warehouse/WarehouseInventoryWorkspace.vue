<template>
    <warehouse-page-skeleton v-if="loading" :columns="8" />
    <main v-else class="warehouse-inventory">
        <app-module-header
            eyebrow="Almacén · Existencias"
            :title="view === 'detail' ? 'Detalle de lotes' : 'Inventario'"
            :subtitle="view === 'detail' ? 'Revisa los lotes disponibles y sus fechas de vencimiento.' : 'Consulta existencias consolidadas o revisa cada lote de forma individual.'"
        >
            <template v-if="view === 'detail'" #actions>
                <app-button variant="ghost" @click="$emit('back')">Volver al inventario</app-button>
            </template>
        </app-module-header>

        <section class="warehouse-inventory__metrics">
            <app-metric-card label="Productos" :value="productPagination.total || productRows.length" hint="Existencias por producto" icon="img/menu/Almacen.png" />
            <app-metric-card label="Lotes" :value="lotPagination.total || lotRows.length" hint="Registros individuales" icon="img/menu/control.png" tone="cyan" />
            <app-metric-card label="Vista actual" :value="viewLabel" hint="Nivel de detalle seleccionado" icon="img/menu/historial.png" tone="neutral" />
        </section>

        <app-data-panel
            v-if="view === 'detail'"
            eyebrow="Trazabilidad"
            :title="data.nombre || 'Producto'"
            :subtitle="`Stock consolidado: ${data.stock || 0}`"
            flush
        >
            <app-table
                :columns="detailColumns"
                :rows="details"
                :loading="tableLoading"
                row-key="id"
                min-width="650px"
                empty-title="Producto sin lotes"
                empty-message="No existen lotes disponibles para este producto."
                :row-class="expiryClass"
            >
                <template #cell-fecha_vecimiento="{ value }">{{ value || 'Sin fecha' }}</template>
                <template #cell-actions="{ row }">
                    <app-button variant="danger" @click="$emit('remove-lot', row.id)">Eliminar lote</app-button>
                </template>
            </app-table>
        </app-data-panel>

        <template v-else>
            <nav class="warehouse-inventory__tabs" aria-label="Tipo de inventario">
                <button type="button" :class="{ 'is-active': activeTab === 'products' }" @click="activeTab = 'products'">
                    Existencias por producto
                </button>
                <button type="button" :class="{ 'is-active': activeTab === 'lots' }" @click="activeTab = 'lots'">
                    Inventario por lote
                </button>
            </nav>

            <app-data-panel
                v-if="activeTab === 'products'"
                eyebrow="Consolidado"
                title="Existencias por producto"
                subtitle="Stock, costos y presentaciones agrupados por producto."
                flush
            >
                <inventory-toolbar
                    :search="productSearch"
                    :criterion="criterion"
                    :loading="tableLoading"
                    @update:search="$emit('update:product-search', $event)"
                    @update:criterion="$emit('update:criterion', $event)"
                    @search="$emit('search-products')"
                />
                <app-table
                    :columns="productColumns"
                    :rows="productRows"
                    :loading="tableLoading"
                    row-key="id"
                    min-width="1260px"
                    empty-title="Sin existencias"
                    empty-message="No se encontraron productos con el filtro seleccionado."
                    :row-class="expiryClass"
                >
                    <template #cell-costo_compra="{ value }">{{ money(value) }}</template>
                    <template #cell-costo_unitario="{ value }">{{ money(value) }}</template>
                    <template #cell-precio_blister="{ value }">{{ money(value) }}</template>
                    <template #cell-precio_caja="{ value }">{{ money(value) }}</template>
                    <template #cell-stock="{ value }"><strong>{{ value || 0 }}</strong></template>
                    <template #cell-actions="{ row }">
                        <app-button variant="secondary" @click="$emit('view-lots', row)">Ver lotes</app-button>
                    </template>
                </app-table>
                <purchase-pagination :pagination="productPagination" :pages="productPages" @change="$emit('product-page', $event)" />
            </app-data-panel>

            <app-data-panel
                v-else
                eyebrow="Trazabilidad"
                title="Inventario por lote"
                subtitle="Detalle de vencimiento, lote y stock individual."
                flush
            >
                <inventory-toolbar
                    :search="lotSearch"
                    :criterion="criterion"
                    :loading="tableLoading"
                    @update:search="$emit('update:lot-search', $event)"
                    @update:criterion="$emit('update:criterion', $event)"
                    @search="$emit('search-lots')"
                />
                <app-table
                    :columns="lotColumns"
                    :rows="lotRows"
                    :loading="tableLoading"
                    row-key="id"
                    min-width="1320px"
                    empty-title="Sin lotes"
                    empty-message="No se encontraron lotes con el filtro seleccionado."
                    :row-class="expiryClass"
                >
                    <template #cell-costo_compra="{ value }">{{ money(value) }}</template>
                    <template #cell-costo_unitario="{ value }">{{ money(value) }}</template>
                    <template #cell-precio_blister="{ value }">{{ money(value) }}</template>
                    <template #cell-precio_caja="{ value }">{{ money(value) }}</template>
                    <template #cell-stock="{ value }"><strong>{{ value || 0 }}</strong></template>
                    <template #cell-actions="{ row }">
                        <app-button variant="danger" @click="$emit('remove-lot', row.id)">Eliminar</app-button>
                    </template>
                </app-table>
                <purchase-pagination :pagination="lotPagination" :pages="lotPages" @change="$emit('lot-page', $event)" />
            </app-data-panel>
        </template>
    </main>
</template>

<script>
import InventoryToolbar from './WarehouseInventoryToolbar.vue';

export default {
    name: 'WarehouseInventoryWorkspace',
    components: { InventoryToolbar },
    props: {
        productRows: { type: Array, default: () => [] },
        lotRows: { type: Array, default: () => [] },
        details: { type: Array, default: () => [] },
        data: { type: Object, required: true },
        productPagination: { type: Object, required: true },
        lotPagination: { type: Object, required: true },
        productPages: { type: Array, default: () => [] },
        lotPages: { type: Array, default: () => [] },
        productSearch: { type: String, default: '' },
        lotSearch: { type: String, default: '' },
        criterion: { type: String, default: 'articulo.nombre_comercial' },
        view: { type: String, default: 'list' },
        loading: { type: Boolean, default: false },
        tableLoading: { type: Boolean, default: false },
    },
    data() {
        return {
            activeTab: 'products',
            productColumns: [
                { key: 'nombre_comercial', label: 'Producto' }, { key: 'nombre_generico', label: 'Genérico' },
                { key: 'laboratorio', label: 'Laboratorio' }, { key: 'costo_compra', label: 'Costo' },
                { key: 'venta_presentacion', label: 'Unidades' }, { key: 'costo_unitario', label: 'P. unitario' },
                { key: 'cantidad_blister', label: 'Blísteres' }, { key: 'precio_blister', label: 'P. blíster' },
                { key: 'cantidad_caja', label: 'Cajas' }, { key: 'precio_caja', label: 'P. caja' },
                { key: 'stock', label: 'Stock' }, { key: 'actions', label: 'Lotes' },
            ],
            lotColumns: [
                { key: 'articulo', label: 'Producto' }, { key: 'nombre_generico', label: 'Genérico' },
                { key: 'laboratorio', label: 'Laboratorio' }, { key: 'fecha_vecimiento', label: 'Vencimiento' },
                { key: 'lote', label: 'Lote' }, { key: 'costo_compra', label: 'Costo' },
                { key: 'venta_presentacion', label: 'Unidades' }, { key: 'costo_unitario', label: 'P. unitario' },
                { key: 'cantidad_blister', label: 'Blísteres' }, { key: 'precio_blister', label: 'P. blíster' },
                { key: 'cantidad_caja', label: 'Cajas' }, { key: 'precio_caja', label: 'P. caja' },
                { key: 'stock', label: 'Stock' }, { key: 'actions', label: 'Acciones' },
            ],
            detailColumns: [
                { key: 'lote', label: 'Lote' }, { key: 'cantidad', label: 'Cantidad' },
                { key: 'fecha_vecimiento', label: 'Vencimiento' }, { key: 'actions', label: 'Acciones' },
            ],
        };
    },
    computed: {
        viewLabel() {
            if (this.view === 'detail') return 'Detalle de lotes';
            return this.activeTab === 'products' ? 'Por producto' : 'Por lote';
        },
    },
    methods: {
        money(value) {
            return `${Number(value || 0).toFixed(2)} Bs`;
        },
        expiryClass(row) {
            return row.v_fecha ? 'warehouse-inventory__expired' : '';
        },
    },
};
</script>

<style scoped>
.warehouse-inventory { display: grid; gap: 1rem; padding: 1.25rem; background: #f4f8f6; }
.warehouse-inventory__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
.warehouse-inventory__tabs { display: inline-flex; width: fit-content; gap: .35rem; padding: .3rem; background: #e5efe9; border-radius: 10px; }
.warehouse-inventory__tabs button { padding: .55rem .85rem; color: #5f716a; font-size: .75rem; font-weight: 800; background: transparent; border: 0; border-radius: 8px; }
.warehouse-inventory__tabs button.is-active { color: #17693c; background: #fff; box-shadow: 0 3px 10px rgba(23, 54, 43, .08); }
.warehouse-inventory >>> .warehouse-inventory__expired { background: #fff1f1 !important; }
@media (max-width: 900px) { .warehouse-inventory__metrics { grid-template-columns: 1fr; } }
@media (max-width: 640px) { .warehouse-inventory { padding: .75rem; } .warehouse-inventory__tabs { width: 100%; flex-direction: column; } }
</style>

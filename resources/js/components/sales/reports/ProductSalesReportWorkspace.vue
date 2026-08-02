<template>
    <section class="product-sales-report">
        <app-module-header eyebrow="Reportes de ventas" :title="view === 'detail' ? 'Detalle del lote vendido' : 'Ventas por producto'" subtitle="Analice productos vendidos y movimientos generales de inventario por período.">
            <template #actions>
                <app-button v-if="view === 'detail'" variant="ghost" icon="icons/arrow-left.svg" @click="$emit('back')">Volver</app-button>
                <app-button v-if="view === 'detail'" icon="icons/print.svg" :loading="printing" @click="$emit('print')">Imprimir</app-button>
            </template>
        </app-module-header>

        <template v-if="view === 'list'">
            <nav class="report-tabs" aria-label="Tipo de reporte">
                <button type="button" :class="{ active: activeTab === 'sales' }" @click="$emit('change-tab', 'sales')">Productos vendidos</button>
                <button type="button" :class="{ active: activeTab === 'movements' }" @click="$emit('change-tab', 'movements')">Movimientos generales</button>
            </nav>
            <div class="report-metrics">
                <app-metric-card :label="activeTab === 'sales' ? 'Registros de venta' : 'Movimientos'" :value="pagination.total || rows.length" hint="Resultados del período" icon="icons/cart.svg" tone="green" />
                <app-metric-card :label="activeTab === 'sales' ? 'Unidades visibles' : 'Unidades movidas'" :value="visibleUnits" hint="Página actual" icon="icons/applications.svg" tone="cyan" />
                <app-metric-card :label="activeTab === 'sales' ? 'Lotes visibles' : 'Productos visibles'" :value="uniqueProducts" hint="Resumen de la página" icon="icons/barcode.svg" tone="blue" />
            </div>
            <app-data-panel :title="activeTab === 'sales' ? 'Productos vendidos' : 'Kárdex de movimientos'" subtitle="Consulte fechas, lotes, responsables y contrapartes." eyebrow="Análisis" flush>
                <sales-report-toolbar
                    show-dates
                    :start-date="filters.startDate"
                    :end-date="filters.endDate"
                    :criteria="criteria"
                    :criterion="filters.criterion"
                    :search="filters.search"
                    :providers="activeTab === 'movements' ? providers : []"
                    :provider-id="filters.providerId"
                    :loading="loading"
                    @update:startDate="$emit('update-filter', { key: 'startDate', value: $event })"
                    @update:endDate="$emit('update-filter', { key: 'endDate', value: $event })"
                    @update:criterion="$emit('update-filter', { key: 'criterion', value: $event })"
                    @update:search="$emit('update-filter', { key: 'search', value: $event })"
                    @update:providerId="$emit('update-filter', { key: 'providerId', value: $event })"
                    @search="$emit('search')"
                />
                <app-table :columns="activeColumns" :rows="rows" :loading="loading" row-key="rowKey" min-width="1080px">
                    <template #cell-producto="{ row }">
                        <strong>{{ row.nombre_comercial || row.producto || 'Sin producto' }}</strong>
                        <small>{{ row.laboratorio || 'Sin laboratorio' }}</small>
                    </template>
                    <template #cell-lote_info="{ row }">
                        <strong>{{ row.lote || '—' }}</strong>
                        <small v-if="row.fecha_vecimiento">Vence: {{ row.fecha_vecimiento }}</small>
                    </template>
                    <template #cell-movimiento="{ row }">
                        <strong>{{ row.motivo_ajuste || 'Movimiento' }}</strong>
                        <small>Transacción: {{ row.id_transaccion || '—' }}</small>
                    </template>
                    <template #cell-stock="{ row }">{{ stockTransition(row) }}</template>
                    <template #cell-actions="{ row }">
                        <app-button variant="secondary" icon="icons/eye.svg" @click="$emit('view-lot', row)">Ver responsables</app-button>
                    </template>
                </app-table>
                <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
            </app-data-panel>
        </template>

        <template v-else>
            <div class="report-metrics">
                <app-metric-card label="Producto" :value="selectedLot.nombre_comercial || 'Sin producto'" :hint="selectedLot.laboratorio || 'Sin laboratorio'" icon="icons/barcode.svg" tone="green" />
                <app-metric-card label="Lote" :value="selectedLot.lote || '—'" :hint="selectedLot.fecha_vecimiento ? `Vence ${selectedLot.fecha_vecimiento}` : 'Sin vencimiento'" icon="icons/book.svg" tone="cyan" />
                <app-metric-card label="Unidades vendidas" :value="detailUnits" hint="En el período consultado" icon="icons/cart.svg" tone="blue" />
            </div>
            <app-data-panel title="Ventas por responsable" subtitle="Detalle de las salidas asociadas al lote seleccionado." eyebrow="Trazabilidad" flush>
                <sales-report-toolbar
                    :criteria="[{ value: 'users.name', label: 'Responsable' }]"
                    criterion="users.name"
                    :search="detailSearch"
                    :loading="loading"
                    @update:search="$emit('update-detail-search', $event)"
                    @search="$emit('filter-detail')"
                />
                <app-table :columns="detailColumns" :rows="detailRows" :loading="loading" row-key="id_venta" min-width="760px">
                    <template #cell-cantidad="{ value }"><strong>{{ Number(value || 0) }}</strong></template>
                </app-table>
            </app-data-panel>
        </template>
    </section>
</template>

<script>
import SalesReportToolbar from './SalesReportToolbar.vue';

export default {
    name: 'ProductSalesReportWorkspace',
    components: { SalesReportToolbar },
    props: {
        view: { type: String, default: 'list' },
        activeTab: { type: String, default: 'sales' },
        rows: { type: Array, default: () => [] },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        filters: { type: Object, required: true },
        providers: { type: Array, default: () => [] },
        loading: { type: Boolean, default: false },
        selectedLot: { type: Object, default: () => ({}) },
        detailRows: { type: Array, default: () => [] },
        detailSearch: { type: String, default: '' },
        printing: { type: Boolean, default: false },
    },
    data() {
        return {
            salesColumns: [
                { key: 'fecha', label: 'Fecha' }, { key: 'producto', label: 'Producto' },
                { key: 'cliente', label: 'Cliente' }, { key: 'cantidad', label: 'Cantidad' },
                { key: 'lote_info', label: 'Lote y vencimiento' }, { key: 'actions', label: 'Acción' },
            ],
            movementColumns: [
                { key: 'fecha', label: 'Fecha' }, { key: 'hora', label: 'Hora' },
                { key: 'producto', label: 'Producto' }, { key: 'movimiento', label: 'Movimiento' },
                { key: 'cliente_proveedor', label: 'Cliente / proveedor' }, { key: 'stock', label: 'Stock anterior → actual' },
                { key: 'usuario', label: 'Responsable' },
            ],
            detailColumns: [
                { key: 'fecha', label: 'Fecha' }, { key: 'nombre_comercial', label: 'Producto' },
                { key: 'laboratorio', label: 'Laboratorio' }, { key: 'cantidad', label: 'Cantidad' },
                { key: 'lote', label: 'Lote' }, { key: 'usuario', label: 'Responsable' },
            ],
        };
    },
    computed: {
        activeColumns() { return this.activeTab === 'sales' ? this.salesColumns : this.movementColumns; },
        criteria() {
            return this.activeTab === 'sales'
                ? [{ value: 'articulo.nombre_comercial', label: 'Producto' }, { value: 'proveedor.nombre', label: 'Laboratorio' }, { value: 'lote.lote', label: 'Lote' }]
                : [{ value: 'articulo.nombre_comercial', label: 'Producto' }];
        },
        visibleUnits() { return this.rows.reduce((sum, row) => sum + Math.abs(Number(row.cantidad || row.stock || 0)), 0); },
        uniqueProducts() { return new Set(this.rows.map(row => row.nombre_comercial || row.producto).filter(Boolean)).size; },
        detailUnits() { return this.detailRows.reduce((sum, row) => sum + Number(row.cantidad || 0), 0); },
    },
    methods: {
        stockTransition(row) {
            const before = Number(row.stock_general_anterior || 0);
            const after = Number(row.stock_general || 0);
            return `${before.toFixed(0)} → ${after.toFixed(0)}`;
        },
    },
};
</script>

<style scoped>
.product-sales-report { display: grid; gap: 1rem; min-height: 100%; padding: 1.15rem; background: var(--system-body-bg, #f4f9f7); }
.report-tabs { display: inline-flex; justify-self: start; padding: .3rem; background: #e7f0ec; border-radius: 10px; }
.report-tabs button { padding: .5rem .85rem; color: #5f716a; font-size: .74rem; font-weight: 800; background: transparent; border: 0; border-radius: 8px; }
.report-tabs button.active { color: #fff; background: var(--fc-green-600, #1f8a4c); }
.report-metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .85rem; }
strong + small { display: block; margin-top: .15rem; color: var(--system-text-muted, #6f817a); font-size: .66rem; font-weight: 500; }
@media (max-width: 800px) { .report-metrics { grid-template-columns: 1fr; } }
@media (max-width: 700px) { .product-sales-report { padding: .75rem; } }
</style>

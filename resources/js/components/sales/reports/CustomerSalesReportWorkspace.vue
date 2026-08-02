<template>
    <section class="customer-sales-report">
        <app-module-header eyebrow="Reportes de ventas" :title="title" :subtitle="subtitle">
            <template #actions>
                <app-button v-if="view !== 'list'" variant="ghost" icon="icons/arrow-left.svg" @click="$emit('back')">Volver</app-button>
            </template>
        </app-module-header>

        <template v-if="view === 'list'">
            <nav class="report-tabs" aria-label="Tipo de venta">
                <button type="button" :class="{ active: activeTab === 'cash' }" @click="$emit('change-tab', 'cash')">Ventas al contado</button>
                <button type="button" :class="{ active: activeTab === 'credit' }" @click="$emit('change-tab', 'credit')">Cartera de crédito</button>
            </nav>
            <div class="report-metrics">
                <app-metric-card :label="activeTab === 'cash' ? 'Ventas encontradas' : 'Clientes con crédito'" :value="pagination.total || rows.length" hint="Registros según filtro" icon="icons/people.svg" tone="green" />
                <app-metric-card :label="activeTab === 'cash' ? 'Total visible' : 'Saldo visible'" :value="money(visibleTotal)" hint="Página actual" icon="icons/money.svg" tone="cyan" />
                <app-metric-card :label="activeTab === 'cash' ? 'Ticket promedio' : 'Créditos visibles'" :value="activeTab === 'cash' ? money(average) : visibleCount" hint="Resumen de la página" icon="icons/bar-chart.svg" tone="blue" />
            </div>
            <app-data-panel :title="activeTab === 'cash' ? 'Ventas al contado por cliente' : 'Cartera agrupada por cliente'" subtitle="Use el filtro para localizar clientes y revisar sus movimientos." eyebrow="Análisis" flush>
                <sales-report-toolbar
                    :criteria="criteria"
                    :criterion="criterion"
                    :search="search"
                    :loading="loading"
                    placeholder="Cliente o forma de pago..."
                    @update:criterion="$emit('update:criterion', $event)"
                    @update:search="$emit('update:search', $event)"
                    @search="$emit('search')"
                />
                <app-table :columns="activeColumns" :rows="rows" :loading="loading" row-key="id" min-width="900px">
                    <template #cell-cliente="{ value }"><strong>{{ value || 'Sin cliente' }}</strong></template>
                    <template #cell-descuento="{ value }">{{ money(value) }}</template>
                    <template #cell-total="{ value }"><strong>{{ money(value) }}</strong></template>
                    <template #cell-estado="{ value }"><span class="report-status">{{ value || 'Sin estado' }}</span></template>
                    <template #cell-actions="{ row }">
                        <app-button variant="secondary" icon="icons/eye.svg" @click="activeTab === 'cash' ? $emit('view-sale', row) : $emit('view-client', row)">
                            {{ activeTab === 'cash' ? 'Ver comprobante' : 'Ver créditos' }}
                        </app-button>
                    </template>
                </app-table>
                <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
            </app-data-panel>
        </template>

        <template v-else-if="view === 'client-credit'">
            <div class="report-metrics">
                <app-metric-card label="Cliente" :value="selectedClient.cliente || 'Sin cliente'" hint="Cartera seleccionada" icon="icons/people.svg" tone="green" />
                <app-metric-card label="Ventas a crédito" :value="rows.length" hint="Movimientos encontrados" icon="icons/book.svg" tone="cyan" />
                <app-metric-card label="Saldo pendiente" :value="money(creditBalance)" hint="Total por cobrar" icon="icons/money.svg" tone="blue" />
            </div>
            <app-data-panel title="Ventas a crédito del cliente" subtitle="Seleccione una venta para consultar su comprobante." eyebrow="Detalle de cartera" flush>
                <app-table :columns="creditSaleColumns" :rows="rows" :loading="loading" row-key="id" min-width="880px">
                    <template #cell-total="{ value }">{{ money(value) }}</template>
                    <template #cell-saldo="{ value }"><strong>{{ money(value) }}</strong></template>
                    <template #cell-actions="{ row }"><app-button variant="secondary" icon="icons/eye.svg" @click="$emit('view-sale', row)">Ver comprobante</app-button></template>
                </app-table>
            </app-data-panel>
        </template>

        <store-one-sale-record-panel v-else :datos="saleData" :details="details" :loading="loading" />
    </section>
</template>

<script>
import SalesReportToolbar from './SalesReportToolbar.vue';
import StoreOneSaleRecordPanel from '../store-one/StoreOneSaleRecordPanel.vue';

export default {
    name: 'CustomerSalesReportWorkspace',
    components: { SalesReportToolbar, StoreOneSaleRecordPanel },
    props: {
        view: { type: String, default: 'list' },
        activeTab: { type: String, default: 'cash' },
        rows: { type: Array, default: () => [] },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        search: { type: String, default: '' },
        criterion: { type: String, default: 'cliente.nombre' },
        loading: { type: Boolean, default: false },
        selectedClient: { type: Object, default: () => ({}) },
        saleData: { type: Object, required: true },
        details: { type: Array, default: () => [] },
    },
    data() {
        return {
            cashColumns: [
                { key: 'fecha', label: 'Fecha' }, { key: 'cliente', label: 'Cliente' },
                { key: 'formaP', label: 'Forma de pago' }, { key: 'descuento', label: 'Descuento' },
                { key: 'total', label: 'Total' }, { key: 'name', label: 'Responsable' },
                { key: 'estado', label: 'Estado' }, { key: 'actions', label: 'Acción' },
            ],
            creditColumns: [
                { key: 'cliente', label: 'Cliente' }, { key: 'contador', label: 'N.º de créditos' },
                { key: 'total', label: 'Saldo pendiente' }, { key: 'estado', label: 'Estado' },
                { key: 'actions', label: 'Acción' },
            ],
            creditSaleColumns: [
                { key: 'fecha', label: 'Fecha' }, { key: 'tipoP', label: 'Tipo de pago' },
                { key: 'formaP', label: 'Forma de pago' }, { key: 'total', label: 'Total' },
                { key: 'saldo', label: 'Saldo' }, { key: 'estado', label: 'Estado' },
                { key: 'actions', label: 'Acción' },
            ],
        };
    },
    computed: {
        title() {
            if (this.view === 'detail') return 'Comprobante del cliente';
            if (this.view === 'client-credit') return 'Créditos del cliente';
            return 'Ventas por cliente';
        },
        subtitle() {
            return this.view === 'list' ? 'Analice ventas al contado y cartera de crédito desde una sola vista.' : 'Consulte el detalle sin abandonar el contexto del cliente.';
        },
        activeColumns() { return this.activeTab === 'cash' ? this.cashColumns : this.creditColumns; },
        criteria() {
            return this.activeTab === 'cash'
                ? [{ value: 'cliente.nombre', label: 'Cliente' }, { value: 'forma_pago.nombre', label: 'Forma de pago' }]
                : [{ value: 'cliente.nombre', label: 'Cliente' }];
        },
        visibleTotal() { return this.rows.reduce((sum, row) => sum + Number(row.total || 0), 0); },
        visibleCount() { return this.rows.reduce((sum, row) => sum + Number(row.contador || 0), 0); },
        average() { return this.rows.length ? this.visibleTotal / this.rows.length : 0; },
        creditBalance() { return this.rows.reduce((sum, row) => sum + Number(row.saldo || 0), 0); },
    },
    methods: {
        money(value) { return `${Number(value || 0).toFixed(2)} Bs`; },
    },
};
</script>

<style scoped>
.customer-sales-report { display: grid; gap: 1rem; min-height: 100%; padding: 1.15rem; background: var(--system-body-bg, #f4f9f7); }
.report-tabs { display: inline-flex; justify-self: start; padding: .3rem; background: #e7f0ec; border-radius: 10px; }
.report-tabs button { padding: .5rem .85rem; color: #5f716a; font-size: .74rem; font-weight: 800; background: transparent; border: 0; border-radius: 8px; }
.report-tabs button.active { color: #fff; background: var(--fc-green-600, #1f8a4c); box-shadow: 0 3px 8px rgba(31,138,76,.2); }
.report-metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .85rem; }
.report-status { display: inline-flex; padding: .28rem .5rem; color: #17693c; font-size: .67rem; font-weight: 900; background: #e3f5eb; border-radius: 999px; }
@media (max-width: 800px) { .report-metrics { grid-template-columns: 1fr; } }
@media (max-width: 700px) { .customer-sales-report { padding: .75rem; } }
</style>

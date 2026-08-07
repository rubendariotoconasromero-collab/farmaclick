<template>
    <section class="store-history">
        <app-page-skeleton v-if="initialLoading" :rows="6" :columns="7" label="Preparando el historial de ventas" />
        <template v-else>
        <app-module-header
            eyebrow="Ventas · Tienda Primera"
            :title="headerTitle"
            :subtitle="headerSubtitle"
        >
            <template #actions>
                <app-button v-if="mode !== 'list'" variant="ghost" icon="icons/arrow-left.svg" @click="$emit('back')">
                    Volver
                </app-button>
                <app-button v-if="mode === 'detail'" icon="icons/print.svg" :loading="printingFormat === 'carta'" :disabled="printing && printingFormat !== 'carta'" @click="$emit('print', 'carta')">
                    Imprimir carta
                </app-button>
                <app-button v-if="mode === 'detail'" variant="ghost" icon="icons/print.svg" :loading="printingFormat === 'ticket'" :disabled="printing && printingFormat !== 'ticket'" @click="$emit('print', 'ticket')">
                    Imprimir ticket
                </app-button>
            </template>
        </app-module-header>

        <template v-if="mode === 'list'">
            <div v-if="false" class="store-history__metrics">
                <app-metric-card label="Ventas encontradas" :value="pagination.total || rows.length" hint="Registros según el filtro" icon="icons/cart.svg" tone="green" />
                <app-metric-card label="Total visible" :value="money(visibleTotal)" hint="Suma de la página actual" icon="icons/money.svg" tone="cyan" />
                <app-metric-card label="Página actual" :value="pageLabel" hint="Navegación del historial" icon="icons/calendar-check.svg" tone="blue" />
            </div>
            <store-one-sales-history-table
                :rows="rows"
                :pagination="pagination"
                :pages="pages"
                :filters="filters"
                :customers="customers"
                :payment-forms="paymentForms"
                :customer-loading="customerLoading"
                :loading="loading"
                :is-administrator="isAdministrator"
                :voiding-ids="voidingIds"
                @update-filter="$emit('update-filter', $event)"
                @search-customer="$emit('search-customer', $event)"
                @apply-filters="$emit('apply-filters')"
                @clear-filters="$emit('clear-filters')"
                @remove-filter="$emit('remove-filter', $event)"
                @refresh="$emit('refresh')"
                @page="$emit('page', $event)"
                @view="$emit('view', $event)"
                @return="$emit('return', $event)"
                @void="$emit('void', $event)"
            />
        </template>

        <store-one-sale-record-panel
            v-else
            :datos="datos"
            :details="details"
            :editable="mode === 'return'"
            :loading="detailLoading"
            :saving="saving"
            :deleting="deleting"
            :deleting-ids="deletingIds"
            :pending-return-count="pendingReturnCount"
            @remove="$emit('remove-detail', $event)"
            @update-cash="$emit('update-cash', $event)"
            @save="$emit('save-return')"
        />
        </template>
    </section>
</template>

<script>
import StoreOneSalesHistoryTable from './StoreOneSalesHistoryTable.vue';
import StoreOneSaleRecordPanel from './StoreOneSaleRecordPanel.vue';

export default {
    name: 'StoreOneSalesHistoryWorkspace',
    components: { StoreOneSalesHistoryTable, StoreOneSaleRecordPanel },
    props: {
        mode: { type: String, default: 'list' },
        rows: { type: Array, default: () => [] },
        details: { type: Array, default: () => [] },
        datos: { type: Object, required: true },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        filters: { type: Object, required: true },
        customers: { type: Array, default: () => [] },
        paymentForms: { type: Array, default: () => [] },
        customerLoading: { type: Boolean, default: false },
        initialLoading: { type: Boolean, default: false },
        loading: { type: Boolean, default: false },
        detailLoading: { type: Boolean, default: false },
        saving: { type: Boolean, default: false },
        deleting: { type: Boolean, default: false },
        deletingIds: { type: Array, default: () => [] },
        pendingReturnCount: { type: Number, default: 0 },
        isAdministrator: { type: Boolean, default: false },
        voidingIds: { type: Array, default: () => [] },
        printing: { type: Boolean, default: false },
        printingFormat: { type: String, default: null },
    },
    computed: {
        headerTitle() {
            if (this.mode === 'detail') return 'Detalle de venta';
            if (this.mode === 'return') return 'Devolución de venta';
            return 'Historial de ventas';
        },
        headerSubtitle() {
            if (this.mode === 'detail') return 'Revise los productos, importes y condiciones del comprobante.';
            if (this.mode === 'return') return 'Ajuste los productos devueltos con un resumen actualizado antes de confirmar.';
            return 'Consulte y administre las ventas de Tienda Primera con información clara y acciones directas.';
        },
        visibleTotal() {
            return this.rows.reduce((sum, row) => sum + Number(row.total || 0), 0);
        },
        pageLabel() {
            return `${this.pagination.current_page || 1} / ${this.pagination.last_page || 1}`;
        },
    },
    methods: {
        money(value) {
            return `${Number(value || 0).toFixed(2)} Bs`;
        },
    },
};
</script>

<style scoped>
.store-history { display: grid; gap: 1rem; min-height: 100%; padding: 1.15rem; background: var(--system-body-bg, #f4f9f7); }
.store-history__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .85rem; }
@media (max-width: 800px) { .store-history__metrics { grid-template-columns: 1fr; } }
@media (max-width: 700px) { .store-history { padding: .75rem; } }
</style>

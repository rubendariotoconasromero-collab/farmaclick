<template>
    <section class="cash-history">
        <app-module-header
            eyebrow="Caja"
            :title="mode === 'list' ? 'Historial de arqueos' : 'Conciliación de caja'"
            :subtitle="mode === 'list' ? 'Consulte aperturas y cierres por período y responsable.' : 'Revise el conteo físico frente a los movimientos registrados por el sistema.'"
        >
            <template #actions>
                <app-button v-if="mode === 'detail'" variant="ghost" icon="icons/arrow-left.svg" @click="$emit('back')">
                    Volver
                </app-button>
            </template>
        </app-module-header>

        <template v-if="mode === 'list'">
            <div class="cash-history__metrics">
                <app-metric-card label="Arqueos encontrados" :value="pagination.total || rows.length" hint="Registros del período" icon="icons/book.svg" tone="green" />
                <app-metric-card label="Ingresos visibles" :value="money(visibleIncome)" hint="Página actual" icon="icons/arrow-circle-top.svg" tone="cyan" />
                <app-metric-card label="Egresos visibles" :value="money(visibleExpenses)" hint="Página actual" icon="icons/arrow-circle-bottom.svg" tone="blue" />
            </div>
            <cash-history-table
                :rows="rows"
                :pagination="pagination"
                :pages="pages"
                :start-date="startDate"
                :end-date="endDate"
                :search="search"
                :loading="loading"
                @update:startDate="$emit('update:startDate', $event)"
                @update:endDate="$emit('update:endDate', $event)"
                @update:search="$emit('update:search', $event)"
                @search="$emit('search')"
                @page="$emit('page', $event)"
                @view="$emit('view', $event)"
            />
        </template>

        <cash-reconciliation-detail v-else :record="selectedRecord" />
    </section>
</template>

<script>
import CashHistoryTable from './CashHistoryTable.vue';
import CashReconciliationDetail from './CashReconciliationDetail.vue';

export default {
    name: 'CashHistoryWorkspace',
    components: { CashHistoryTable, CashReconciliationDetail },
    props: {
        mode: { type: String, default: 'list' },
        rows: { type: Array, default: () => [] },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        startDate: { type: String, default: '' },
        endDate: { type: String, default: '' },
        search: { type: String, default: '' },
        loading: { type: Boolean, default: false },
        selectedRecord: { type: Object, required: true },
    },
    computed: {
        visibleIncome() {
            return this.rows.reduce((sum, row) => sum + Number(row.total_ingreso_general || 0), 0);
        },
        visibleExpenses() {
            return this.rows.reduce((sum, row) => sum + Number(row.total_egreso_general || 0), 0);
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
.cash-history { display: grid; gap: 1rem; min-height: 100%; padding: 1.15rem; background: var(--system-body-bg, #f4f9f7); }
.cash-history__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .85rem; }
@media (max-width: 800px) { .cash-history__metrics { grid-template-columns: 1fr; } }
@media (max-width: 700px) { .cash-history { padding: .75rem; } }
</style>

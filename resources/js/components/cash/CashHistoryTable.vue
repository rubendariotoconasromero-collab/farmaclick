<template>
    <app-data-panel
        eyebrow="Consulta"
        title="Arqueos registrados"
        subtitle="Filtre por período y responsable para revisar los cierres de caja."
        flush
    >
        <div class="cash-history-filters">
            <app-input
                label="Desde"
                type="date"
                :value="startDate"
                @input="$emit('update:startDate', $event)"
            />
            <app-input
                label="Hasta"
                type="date"
                :value="endDate"
                @input="$emit('update:endDate', $event)"
            />
            <app-input
                label="Responsable"
                :value="search"
                placeholder="Nombre del responsable..."
                @input="$emit('update:search', $event)"
                @keyup.enter.native="$emit('search')"
            />
            <app-button icon="icons/magnifying-glass.svg" :disabled="loading" @click="$emit('search')">
                Buscar
            </app-button>
        </div>

        <app-table
            :columns="columns"
            :rows="rows"
            :loading="loading"
            row-key="id"
            min-width="1120px"
            caption="Historial de arqueos de caja"
            empty-title="No hay arqueos en este período"
            empty-message="Ajuste las fechas o el responsable para ampliar la consulta."
        >
            <template #cell-periodo="{ row }">
                <strong>{{ dateTime(row.fecha_apertura) }}</strong>
                <small>{{ row.fecha_cierre ? `Cierre: ${dateTime(row.fecha_cierre)}` : 'Caja aún abierta' }}</small>
            </template>
            <template #cell-apertura="{ value }">{{ money(value) }}</template>
            <template #cell-ingresos="{ row }"><strong class="cash-value cash-value--income">{{ money(row.total_ingreso_general) }}</strong></template>
            <template #cell-egresos="{ row }"><strong class="cash-value cash-value--expense">{{ money(row.total_egreso_general) }}</strong></template>
            <template #cell-neto="{ row }"><strong class="cash-value">{{ money(netCash(row)) }}</strong></template>
            <template #cell-estado="{ value }">
                <span class="cash-status" :class="{ 'cash-status--open': value === 'Abierta' }">{{ value || 'Sin estado' }}</span>
            </template>
            <template #cell-actions="{ row }">
                <app-button
                    variant="secondary"
                    icon="icons/eye.svg"
                    :disabled="row.estado !== 'Cerrada'"
                    @click="$emit('view', row)"
                >
                    Ver conciliación
                </app-button>
            </template>
        </app-table>

        <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
    </app-data-panel>
</template>

<script>
export default {
    name: 'CashHistoryTable',
    props: {
        rows: { type: Array, default: () => [] },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        startDate: { type: String, default: '' },
        endDate: { type: String, default: '' },
        search: { type: String, default: '' },
        loading: { type: Boolean, default: false },
    },
    data() {
        return {
            columns: [
                { key: 'periodo', label: 'Período' },
                { key: 'name', label: 'Responsable' },
                { key: 'apertura', label: 'Apertura' },
                { key: 'ingresos', label: 'Ingresos' },
                { key: 'egresos', label: 'Egresos' },
                { key: 'neto', label: 'Efectivo esperado' },
                { key: 'estado', label: 'Estado' },
                { key: 'actions', label: 'Acción' },
            ],
        };
    },
    methods: {
        money(value) {
            return `${Number(value || 0).toFixed(2)} Bs`;
        },
        netCash(row) {
            if (row.saldo_efectivo !== null && row.saldo_efectivo !== '' && Number.isFinite(Number(row.saldo_efectivo))) {
                return Number(row.saldo_efectivo);
            }
            return Number(row.apertura || 0)
                + Number(row.total_ingreso_efectivo || 0)
                - Number(row.total_egreso_efectivo || 0);
        },
        dateTime(value) {
            return value ? String(value).replace('T', ' ').substring(0, 16) : '—';
        },
    },
};
</script>

<style scoped>
.cash-history-filters { display: grid; grid-template-columns: minmax(150px, .7fr) minmax(150px, .7fr) minmax(240px, 1.4fr) auto; gap: .65rem; align-items: end; padding: 1rem; background: var(--system-soft-bg, #f8fbf9); border-bottom: 1px solid var(--system-border-color, #d8e5df); }
.cash-value { color: var(--fc-ink, #17362b); white-space: nowrap; }
.cash-value--income { color: var(--fc-green-700, #1f6b45); }
.cash-value--expense { color: #a72f36; }
.cash-status { display: inline-flex; min-width: 72px; justify-content: center; padding: .3rem .55rem; color: #a72f36; font-size: .68rem; font-weight: 900; background: #fde8e9; border-radius: 999px; }
.cash-status--open { color: #17693c; background: #e3f5eb; }
strong + small { display: block; margin-top: .15rem; color: var(--system-text-muted, #6f817a); font-size: .66rem; font-weight: 500; }
@media (max-width: 900px) { .cash-history-filters { grid-template-columns: 1fr 1fr; } }
@media (max-width: 620px) { .cash-history-filters { grid-template-columns: 1fr; } }
</style>

<template>
    <div class="client-credits">
        <div class="client-credits__metrics">
            <app-metric-card label="Cliente" :value="client.cliente || 'Sin cliente'" :hint="`NIT/CI: ${client.matricula || '—'}`" icon="icons/people.svg" tone="green" />
            <app-metric-card label="Saldo pendiente" :value="money(pendingTotal)" hint="Total de créditos abiertos" icon="icons/money.svg" tone="cyan" />
            <app-metric-card label="Créditos abiertos" :value="credits.length" :hint="client.telefono || 'Sin teléfono'" icon="icons/book.svg" tone="blue" />
        </div>

        <app-data-panel
            eyebrow="Detalle del cliente"
            title="Ventas a crédito pendientes"
            subtitle="Cada fila corresponde a una venta con saldo por cobrar."
            flush
        >
            <app-table
                :columns="columns"
                :rows="credits"
                :loading="loading"
                row-key="id"
                min-width="760px"
                empty-title="Cliente sin créditos pendientes"
                empty-message="No existen ventas con saldo abierto para este cliente."
            >
                <template #cell-index="{ row }">{{ credits.indexOf(row) + 1 }}</template>
                <template #cell-monto="{ value }">{{ money(value) }}</template>
                <template #cell-saldo="{ value }"><strong class="client-credits__balance">{{ money(value) }}</strong></template>
                <template #cell-actions="{ row }">
                    <app-button
                        icon="icons/money.svg"
                        :disabled="Number(row.saldo || 0) <= 0"
                        @click="$emit('pay', row)"
                    >
                        Registrar abono
                    </app-button>
                </template>
            </app-table>
        </app-data-panel>
    </div>
</template>

<script>
export default {
    name: 'StoreOneClientCreditsPanel',
    props: {
        client: { type: Object, required: true },
        credits: { type: Array, default: () => [] },
        loading: { type: Boolean, default: false },
    },
    data() {
        return {
            columns: [
                { key: 'index', label: 'N.º' },
                { key: 'fecha', label: 'Fecha' },
                { key: 'telefono', label: 'Teléfono' },
                { key: 'monto', label: 'Monto original' },
                { key: 'saldo', label: 'Saldo' },
                { key: 'actions', label: 'Acción' },
            ],
        };
    },
    computed: {
        pendingTotal() {
            return this.credits.reduce((sum, credit) => sum + Number(credit.saldo || 0), 0);
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
.client-credits { display: grid; gap: 1rem; }
.client-credits__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .85rem; }
.client-credits__balance { color: #a76613; white-space: nowrap; }
@media (max-width: 800px) { .client-credits__metrics { grid-template-columns: 1fr; } }
</style>

<template>
    <app-data-panel
        eyebrow="Conteo físico"
        title="Efectivo entregado"
        subtitle="Desglose de billetes y monedas registrado al cierre."
        flush
    >
        <app-table
            :columns="columns"
            :rows="denominations"
            row-key="key"
            min-width="520px"
            empty-title="Sin conteo registrado"
            empty-message="El arqueo no contiene cantidades por denominación."
        >
            <template #cell-value="{ row }">{{ row.currency === 'USD' ? `${row.value} USD` : `${row.value} Bs` }}</template>
            <template #cell-quantity="{ value }">{{ Number(value || 0) }}</template>
            <template #cell-subtotal="{ value }"><strong>{{ money(value) }}</strong></template>
        </app-table>
        <div class="denomination-total">
            <span>Total contado</span>
            <strong>{{ money(total) }}</strong>
        </div>
    </app-data-panel>
</template>

<script>
export default {
    name: 'CashDenominationTable',
    props: {
        record: { type: Object, required: true },
    },
    data() {
        return {
            columns: [
                { key: 'value', label: 'Denominación' },
                { key: 'quantity', label: 'Cantidad' },
                { key: 'subtotal', label: 'Subtotal' },
            ],
            definitions: [
                { key: 'doscientos', value: 200, currency: 'BOB', multiplier: 200 },
                { key: 'cien', value: 100, currency: 'BOB', multiplier: 100 },
                { key: 'cincuenta', value: 50, currency: 'BOB', multiplier: 50 },
                { key: 'veinte', value: 20, currency: 'BOB', multiplier: 20 },
                { key: 'diez', value: 10, currency: 'BOB', multiplier: 10 },
                { key: 'cinco', value: 5, currency: 'BOB', multiplier: 5 },
                { key: 'dos', value: 2, currency: 'BOB', multiplier: 2 },
                { key: 'uno', value: 1, currency: 'BOB', multiplier: 1 },
                { key: 'cerocinco', value: 0.5, currency: 'BOB', multiplier: 0.5 },
                { key: 'ceroveinte', value: 0.2, currency: 'BOB', multiplier: 0.2 },
                { key: 'cien_dolar', value: 100, currency: 'USD', multiplier: 700 },
            ],
        };
    },
    computed: {
        denominations() {
            return this.definitions.map(item => {
                const quantity = Number(this.record[item.key] || 0);
                return { ...item, quantity, subtotal: quantity * item.multiplier };
            });
        },
        total() {
            return this.denominations.reduce((sum, item) => sum + item.subtotal, 0);
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
.denomination-total { display: flex; justify-content: flex-end; gap: 1rem; padding: .9rem 1rem; color: var(--system-text-muted, #6f817a); background: #f8fbf9; border-top: 1px solid var(--system-border-color, #d8e5df); }
.denomination-total strong { color: var(--fc-green-700, #1f6b45); font-size: 1rem; }
</style>

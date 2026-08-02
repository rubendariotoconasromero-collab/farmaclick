<template>
    <section class="store-payments">
        <app-module-header
            eyebrow="Ventas · Tienda Primera"
            :title="mode === 'clients' ? 'Pagos de ventas' : 'Créditos del cliente'"
            :subtitle="mode === 'clients' ? 'Administre cuentas por cobrar y registre abonos con trazabilidad.' : 'Revise las ventas pendientes antes de seleccionar el crédito a pagar.'"
        >
            <template #actions>
                <span class="store-payments__cash" :class="{ 'is-open': cashState === 'Abierta' }">
                    <i></i>Caja {{ cashState || 'sin verificar' }}
                </span>
                <app-button v-if="mode !== 'clients'" variant="ghost" icon="icons/arrow-left.svg" @click="$emit('back')">
                    Volver
                </app-button>
            </template>
        </app-module-header>

        <template v-if="mode === 'clients'">
            <div class="store-payments__metrics">
                <app-metric-card label="Clientes con deuda" :value="clientsTotal" hint="Cuentas pendientes" icon="icons/people.svg" tone="green" />
                <app-metric-card label="Cartera pendiente" :value="money(portfolioTotal)" hint="Saldo total encontrado" icon="icons/money.svg" tone="cyan" />
                <app-metric-card label="Deuda promedio" :value="money(averageDebt)" hint="Por cliente" icon="icons/bar-chart.svg" tone="blue" />
            </div>
            <store-one-receivable-clients-table
                :rows="clients"
                :search="search"
                :loading="loading"
                :pagination="pagination"
                :pages="pages"
                @update:search="$emit('update:search', $event)"
                @search="$emit('search')"
                @refresh="$emit('refresh')"
                @page="$emit('page', $event)"
                @select="$emit('select-client', $event)"
            />
        </template>

        <store-one-client-credits-panel
            v-else
            :client="selectedClient"
            :credits="credits"
            :loading="creditsLoading"
            @pay="$emit('open-payment', $event)"
        />

        <store-one-payment-dialog
            :open="paymentOpen"
            :loading="paymentLoading"
            :saving="saving"
            :cash-state="cashState"
            :credit="selectedCredit"
            :payment="payment"
            :payment-forms="paymentForms"
            :history="paymentHistory"
            :current-balance="currentBalance"
            :original-amount="originalAmount"
            @update:amount="$emit('update:amount', $event)"
            @update:paymentForm="$emit('update:paymentForm', $event)"
            @update:description="$emit('update:description', $event)"
            @close="$emit('close-payment')"
            @save="$emit('save-payment')"
        />
    </section>
</template>

<script>
import StoreOneReceivableClientsTable from './StoreOneReceivableClientsTable.vue';
import StoreOneClientCreditsPanel from './StoreOneClientCreditsPanel.vue';
import StoreOnePaymentDialog from './StoreOnePaymentDialog.vue';

export default {
    name: 'StoreOnePaymentsWorkspace',
    components: {
        StoreOneReceivableClientsTable,
        StoreOneClientCreditsPanel,
        StoreOnePaymentDialog,
    },
    props: {
        mode: { type: String, default: 'clients' },
        clients: { type: Array, default: () => [] },
        clientsTotal: { type: Number, default: 0 },
        selectedClient: { type: Object, required: true },
        credits: { type: Array, default: () => [] },
        search: { type: String, default: '' },
        loading: { type: Boolean, default: false },
        creditsLoading: { type: Boolean, default: false },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        cashState: { type: String, default: '' },
        paymentOpen: { type: Boolean, default: false },
        paymentLoading: { type: Boolean, default: false },
        saving: { type: Boolean, default: false },
        selectedCredit: { type: Object, default: () => ({}) },
        payment: { type: Object, required: true },
        paymentForms: { type: Array, default: () => [] },
        paymentHistory: { type: Array, default: () => [] },
        currentBalance: { type: Number, default: 0 },
        originalAmount: { type: Number, default: 0 },
        portfolioTotal: { type: Number, default: 0 },
    },
    computed: {
        averageDebt() {
            return this.clientsTotal ? this.portfolioTotal / this.clientsTotal : 0;
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
.store-payments { display: grid; gap: 1rem; min-height: 100%; padding: 1.15rem; background: var(--system-body-bg, #f4f9f7); }
.store-payments__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .85rem; }
.store-payments__cash { display: inline-flex; align-items: center; gap: .4rem; padding: .35rem .6rem; color: #9e2929; font-size: .68rem; font-weight: 900; background: #fff0f0; border: 1px solid #f0caca; border-radius: 999px; }
.store-payments__cash i { width: 7px; height: 7px; background: #d63c3c; border-radius: 50%; }
.store-payments__cash.is-open { color: var(--fc-green-700, #1f6b45); background: var(--fc-green-50, #effaf4); border-color: #c9e5d5; }
.store-payments__cash.is-open i { background: var(--fc-green-500, #2fae66); }
@media (max-width: 800px) { .store-payments__metrics { grid-template-columns: 1fr; } }
@media (max-width: 700px) { .store-payments { padding: .75rem; } }
</style>

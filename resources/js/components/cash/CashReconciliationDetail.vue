<template>
    <div class="cash-reconciliation">
        <div class="cash-reconciliation__metrics">
            <app-metric-card label="Monto de apertura" :value="money(record.apertura)" :hint="dateTime(record.fecha_apertura)" icon="icons/lock-unlocked.svg" tone="green" />
            <app-metric-card label="Efectivo esperado" :value="money(expectedCash)" hint="Apertura + ingresos − egresos" icon="icons/money.svg" tone="cyan" />
            <app-metric-card label="Efectivo contado" :value="money(countedCash)" hint="Conteo físico entregado" icon="icons/check.svg" tone="blue" />
            <app-metric-card label="Diferencia" :value="money(difference)" :hint="differenceHint" icon="icons/balance-scale.svg" :tone="difference === 0 ? 'neutral' : 'blue'" />
        </div>

        <div class="cash-reconciliation__grid">
            <app-data-panel
                eyebrow="Hoja de conciliación"
                title="Movimientos del arqueo"
                :subtitle="`${record.name || 'Sin responsable'} · Cierre ${dateTime(record.fecha_cierre)}`"
                flush
            >
                <div class="reconciliation-sheet">
                    <section>
                        <header><span>Ingresos</span><strong>{{ money(totalIncome) }}</strong></header>
                        <dl>
                            <div><dt>Apertura de caja</dt><dd>{{ money(record.apertura) }}</dd></div>
                            <div><dt>Ventas en efectivo</dt><dd>{{ money(record.total_contado) }}</dd></div>
                            <div><dt>Ventas por depósito</dt><dd>{{ money(record.total_contado_deposito) }}</dd></div>
                            <div><dt>Cobros de crédito en efectivo</dt><dd>{{ money(record.total_credito) }}</dd></div>
                            <div><dt>Cobros de crédito por depósito</dt><dd>{{ money(record.total_credito_deposito) }}</dd></div>
                        </dl>
                    </section>
                    <section class="reconciliation-sheet__expenses">
                        <header><span>Egresos</span><strong>{{ money(totalExpenses) }}</strong></header>
                        <dl>
                            <div><dt>Gastos en efectivo</dt><dd>{{ money(record.gastos) }}</dd></div>
                            <div><dt>Gastos por depósito</dt><dd>{{ money(record.gastos_deposito) }}</dd></div>
                            <div><dt>Compras en efectivo</dt><dd>{{ money(record.total_contado_compra) }}</dd></div>
                            <div><dt>Compras por depósito</dt><dd>{{ money(record.total_contado_deposito_compra) }}</dd></div>
                            <div><dt>Pagos de crédito en efectivo</dt><dd>{{ money(record.total_credito_compra) }}</dd></div>
                            <div><dt>Pagos de crédito por depósito</dt><dd>{{ money(record.total_credito_deposito_compra) }}</dd></div>
                        </dl>
                    </section>
                </div>
                <div class="reconciliation-totals">
                    <div><span>Ingreso general</span><strong>{{ money(record.total_ingreso_general) }}</strong></div>
                    <div><span>Egreso general</span><strong>{{ money(record.total_egreso_general) }}</strong></div>
                    <div><span>Saldo efectivo</span><strong>{{ money(expectedCash) }}</strong></div>
                    <div class="reconciliation-totals__difference" :class="{ 'has-difference': difference !== 0 }">
                        <span>Diferencia de caja</span><strong>{{ money(difference) }}</strong>
                    </div>
                </div>
            </app-data-panel>

            <cash-denomination-table :record="record" />
        </div>
    </div>
</template>

<script>
import CashDenominationTable from './CashDenominationTable.vue';

export default {
    name: 'CashReconciliationDetail',
    components: { CashDenominationTable },
    props: {
        record: { type: Object, required: true },
    },
    computed: {
        totalIncome() {
            return Number(this.record.apertura || 0) + Number(this.record.total_ingreso_general || 0);
        },
        totalExpenses() {
            return Number(this.record.total_egreso_general || 0);
        },
        expectedCash() {
            if (
                this.record.saldo_efectivo !== null
                && this.record.saldo_efectivo !== ''
                && Number.isFinite(Number(this.record.saldo_efectivo))
            ) {
                return Number(this.record.saldo_efectivo);
            }
            return Number(this.record.apertura || 0)
                + Number(this.record.total_ingreso_efectivo || 0)
                - Number(this.record.total_egreso_efectivo || 0);
        },
        countedCash() {
            const factors = {
                doscientos: 200, cien: 100, cincuenta: 50, veinte: 20, diez: 10,
                cinco: 5, dos: 2, uno: 1, cerocinco: 0.5, ceroveinte: 0.2, cien_dolar: 700,
            };
            return Object.keys(factors).reduce(
                (sum, key) => sum + Number(this.record[key] || 0) * factors[key],
                0,
            );
        },
        difference() {
            if (
                this.record.diferencia !== null
                && this.record.diferencia !== ''
                && Number.isFinite(Number(this.record.diferencia))
            ) {
                return Number(this.record.diferencia);
            }
            return this.countedCash - this.expectedCash;
        },
        differenceHint() {
            if (this.difference === 0) return 'Caja conciliada';
            return this.difference > 0 ? 'Sobrante registrado' : 'Faltante registrado';
        },
    },
    methods: {
        money(value) {
            return `${Number(value || 0).toFixed(2)} Bs`;
        },
        dateTime(value) {
            return value ? String(value).replace('T', ' ').substring(0, 16) : '—';
        },
    },
};
</script>

<style scoped>
.cash-reconciliation { display: grid; gap: 1rem; }
.cash-reconciliation__metrics { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: .8rem; }
.cash-reconciliation__grid { display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(390px, .65fr); gap: 1rem; align-items: start; }
.reconciliation-sheet { display: grid; grid-template-columns: 1fr 1fr; }
.reconciliation-sheet section { min-width: 0; }
.reconciliation-sheet section + section { border-left: 1px solid var(--system-border-color, #d8e5df); }
.reconciliation-sheet header { display: flex; justify-content: space-between; gap: 1rem; padding: .8rem 1rem; color: var(--fc-green-700, #1f6b45); background: #effaf4; }
.reconciliation-sheet__expenses header { color: #a72f36; background: #fff3f3; }
.reconciliation-sheet header span { font-size: .7rem; font-weight: 900; text-transform: uppercase; letter-spacing: .05em; }
.reconciliation-sheet dl { margin: 0; padding: .35rem 1rem .7rem; }
.reconciliation-sheet dl > div { display: flex; justify-content: space-between; gap: 1rem; padding: .52rem 0; border-bottom: 1px solid #e5ece8; }
.reconciliation-sheet dt { color: var(--system-text-muted, #6f817a); font-size: .71rem; font-weight: 600; }
.reconciliation-sheet dd { margin: 0; color: var(--fc-ink, #17362b); font-size: .74rem; font-weight: 900; white-space: nowrap; }
.reconciliation-totals { display: grid; grid-template-columns: repeat(4, 1fr); border-top: 1px solid var(--system-border-color, #d8e5df); }
.reconciliation-totals > div { display: grid; gap: .25rem; padding: .8rem 1rem; background: #f8fbf9; border-right: 1px solid var(--system-border-color, #d8e5df); }
.reconciliation-totals span { color: var(--system-text-muted, #6f817a); font-size: .66rem; font-weight: 800; text-transform: uppercase; }
.reconciliation-totals strong { color: var(--fc-ink, #17362b); }
.reconciliation-totals__difference.has-difference strong { color: #a72f36; }
@media (max-width: 1200px) { .cash-reconciliation__grid { grid-template-columns: 1fr; } }
@media (max-width: 900px) { .cash-reconciliation__metrics { grid-template-columns: 1fr 1fr; } .reconciliation-totals { grid-template-columns: 1fr 1fr; } }
@media (max-width: 650px) { .cash-reconciliation__metrics, .reconciliation-sheet, .reconciliation-totals { grid-template-columns: 1fr; } .reconciliation-sheet section + section { border-left: 0; border-top: 1px solid var(--system-border-color, #d8e5df); } }
</style>

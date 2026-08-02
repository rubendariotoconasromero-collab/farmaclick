<template>
    <div v-if="open" class="payment-dialog" role="presentation" @mousedown.self="$emit('close')">
        <section class="payment-dialog__panel" role="dialog" aria-modal="true" aria-labelledby="payment-dialog-title">
            <header>
                <div>
                    <span>Cuenta por cobrar</span>
                    <h2 id="payment-dialog-title">Registrar abono</h2>
                    <p>Venta del {{ credit.fecha || '—' }} · Saldo {{ money(currentBalance) }}</p>
                </div>
                <button type="button" aria-label="Cerrar" @click="$emit('close')">×</button>
            </header>

            <div v-if="loading" class="payment-dialog__loading">
                <span></span>
                <strong>Cargando información del crédito...</strong>
            </div>

            <template v-else>
                <div class="payment-dialog__content">
                <div v-if="cashState !== 'Abierta'" class="payment-dialog__warning">
                    <img :src="icon('lock-locked.svg')" alt="">
                    <div>
                        <strong>La caja está cerrada</strong>
                        <span>Debe aperturar caja antes de registrar un abono.</span>
                    </div>
                </div>

                <div class="payment-dialog__body">
                    <div class="payment-dialog__form">
                        <div class="payment-dialog__dates">
                            <app-input label="Fecha del abono" type="date" :value="payment.fecha" readonly />
                            <app-input label="Vencimiento" type="date" :value="payment.fecha_final" readonly />
                        </div>
                        <app-input
                            label="Importe del abono"
                            type="number"
                            min="0"
                            step="0.01"
                            :value="payment.amortizacion"
                            :error="amountError"
                            :disabled="cashState !== 'Abierta' || currentBalance <= 0"
                            placeholder="0.00"
                            @input="$emit('update:amount', $event)"
                        >
                            <template #suffix>Bs</template>
                        </app-input>
                        <label class="payment-dialog__field">
                            <span>Forma de pago</span>
                            <select
                                :value="payment.id_forma_pago"
                                class="form-select"
                                :disabled="cashState !== 'Abierta'"
                                @change="$emit('update:paymentForm', Number($event.target.value))"
                            >
                                <option :value="0" disabled>Seleccione una forma de pago</option>
                                <option v-for="form in paymentForms" :key="form.id" :value="form.id">{{ form.nombre }}</option>
                            </select>
                        </label>
                        <app-input
                            label="Descripción"
                            multiline
                            :value="payment.descripcion"
                            placeholder="Detalle opcional del abono"
                            @input="$emit('update:description', $event)"
                        />
                    </div>

                    <aside class="payment-dialog__summary">
                        <span>Resumen del abono</span>
                        <dl>
                            <div><dt>Monto original</dt><dd>{{ money(originalAmount) }}</dd></div>
                            <div><dt>Saldo actual</dt><dd>{{ money(currentBalance) }}</dd></div>
                            <div class="payment-dialog__new-balance"><dt>Nuevo saldo</dt><dd>{{ money(newBalance) }}</dd></div>
                        </dl>
                    </aside>
                </div>

                <app-data-panel title="Historial de abonos" subtitle="Movimientos registrados para este crédito." flush>
                    <app-table
                        :columns="columns"
                        :rows="history"
                        row-key="id"
                        min-width="680px"
                        empty-title="Sin movimientos"
                        empty-message="Este crédito todavía no tiene abonos registrados."
                    >
                        <template #cell-index="{ row }">{{ history.indexOf(row) + 1 }}</template>
                        <template #cell-monto_total="{ value }">{{ money(value) }}</template>
                        <template #cell-amortizacion="{ value }">{{ money(value) }}</template>
                        <template #cell-saldo="{ value }"><strong>{{ money(value) }}</strong></template>
                    </app-table>
                </app-data-panel>
                </div>
            </template>

            <footer>
                <app-button variant="ghost" :disabled="saving" @click="$emit('close')">Cancelar</app-button>
                <app-button
                    icon="icons/check.svg"
                    :loading="saving"
                    :disabled="loading || cashState !== 'Abierta' || Boolean(amountError) || !payment.id_forma_pago"
                    @click="$emit('save')"
                >
                    Guardar abono
                </app-button>
            </footer>
        </section>
    </div>
</template>

<script>
export default {
    name: 'StoreOnePaymentDialog',
    props: {
        open: { type: Boolean, default: false },
        loading: { type: Boolean, default: false },
        saving: { type: Boolean, default: false },
        cashState: { type: String, default: '' },
        credit: { type: Object, default: () => ({}) },
        payment: { type: Object, required: true },
        paymentForms: { type: Array, default: () => [] },
        history: { type: Array, default: () => [] },
        currentBalance: { type: Number, default: 0 },
        originalAmount: { type: Number, default: 0 },
    },
    data() {
        return {
            columns: [
                { key: 'index', label: 'N.º' },
                { key: 'fecha', label: 'Fecha' },
                { key: 'monto_total', label: 'Monto total' },
                { key: 'amortizacion', label: 'Abono' },
                { key: 'forma', label: 'Forma de pago' },
                { key: 'saldo', label: 'Saldo' },
            ],
        };
    },
    computed: {
        amount() {
            return Number(this.payment.amortizacion || 0);
        },
        newBalance() {
            return Math.max(0, this.currentBalance - this.amount);
        },
        amountError() {
            if (!this.payment.amortizacion) return '';
            if (this.amount <= 0) return 'Ingrese un importe mayor a cero.';
            if (this.amount > this.currentBalance) return 'El abono no puede superar el saldo actual.';
            return '';
        },
    },
    methods: {
        money(value) {
            return `${Number(value || 0).toFixed(2)} Bs`;
        },
        icon(name) {
            const mainIndex = window.location.pathname.indexOf('/main');
            const base = mainIndex >= 0 ? window.location.pathname.substring(0, mainIndex) : '';
            return `${base}/icons/${name}`;
        },
    },
};
</script>

<style scoped>
.payment-dialog { position: fixed; inset: 0; z-index: 1080; display: grid; place-items: center; padding: 1rem; background: rgba(12, 35, 27, .64); backdrop-filter: blur(3px); }
.payment-dialog__panel { display: grid; grid-template-rows: auto minmax(0, 1fr) auto; width: min(1080px, 100%); max-height: calc(100vh - 2rem); overflow: hidden; background: var(--system-body-bg, #f4f9f7); border: 1px solid #c8ddd4; border-radius: 16px; box-shadow: 0 24px 70px rgba(7, 28, 20, .3); }
.payment-dialog__panel > header { display: flex; justify-content: space-between; gap: 1rem; padding: 1rem 1.15rem; color: #fff; background: linear-gradient(115deg, #163f32, #1f8a4c); border-bottom: 3px solid var(--fc-cyan-500, #3ec6e0); }
.payment-dialog__panel > header span { color: #71d5e8; font-size: .67rem; font-weight: 900; text-transform: uppercase; letter-spacing: .07em; }
.payment-dialog__panel h2, .payment-dialog__panel p { margin: 0; }
.payment-dialog__panel h2 { margin-top: .15rem; font-size: 1.2rem; }
.payment-dialog__panel p { margin-top: .25rem; color: #d8eee5; font-size: .75rem; }
.payment-dialog__panel > header button { align-self: start; width: 34px; height: 34px; color: #fff; font-size: 1.5rem; line-height: 1; background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.25); border-radius: 9px; }
.payment-dialog__panel > footer { display: flex; justify-content: flex-end; gap: .55rem; padding: .85rem 1rem; background: #fff; border-top: 1px solid var(--system-border-color, #d8e5df); }
.payment-dialog__content { min-height: 0; overflow: auto; }
.payment-dialog__body { display: grid; grid-template-columns: minmax(0, 1fr) 290px; gap: 1rem; padding: 1rem; }
.payment-dialog__form { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem; }
.payment-dialog__dates { display: grid; grid-template-columns: 1fr 1fr; gap: .7rem; grid-column: 1 / -1; }
.payment-dialog__field { display: grid; gap: .35rem; margin: 0; }
.payment-dialog__field > span { color: #315044; font-size: .73rem; font-weight: 800; }
.payment-dialog__field .form-select { min-height: 40px; border-color: #bdd2c9; border-radius: 8px; }
.payment-dialog__summary { padding: 1rem; background: #fff; border: 1px solid var(--system-border-color, #d8e5df); border-top: 4px solid var(--fc-cyan-500, #3ec6e0); border-radius: 12px; }
.payment-dialog__summary > span { color: var(--fc-blue-600, #0e93b5); font-size: .67rem; font-weight: 900; text-transform: uppercase; letter-spacing: .06em; }
.payment-dialog__summary dl { margin: .65rem 0 0; }
.payment-dialog__summary dl > div { display: flex; justify-content: space-between; gap: 1rem; padding: .55rem 0; border-bottom: 1px solid #e5ece8; }
.payment-dialog__summary dt { color: #6f817a; font-size: .72rem; }
.payment-dialog__summary dd { margin: 0; color: var(--fc-ink, #17362b); font-size: .78rem; font-weight: 900; }
.payment-dialog__summary .payment-dialog__new-balance { border-bottom: 0; }
.payment-dialog__new-balance dt, .payment-dialog__new-balance dd { color: var(--fc-green-700, #1f6b45); font-size: .9rem; }
.payment-dialog__warning { display: flex; align-items: center; gap: .8rem; margin: 1rem 1rem 0; padding: .75rem; color: #765410; background: #fff8e8; border: 1px solid #ecdba9; border-radius: 9px; }
.payment-dialog__warning img { width: 25px; opacity: .65; }
.payment-dialog__warning div { display: flex; flex-direction: column; }
.payment-dialog__warning span { font-size: .72rem; }
.payment-dialog__loading { display: flex; min-height: 260px; align-items: center; justify-content: center; gap: .7rem; color: #5f716a; }
.payment-dialog__loading span { width: 22px; height: 22px; border: 3px solid #cfe2da; border-right-color: var(--fc-green-600, #1f8a4c); border-radius: 50%; animation: payment-spin .7s linear infinite; }
@keyframes payment-spin { to { transform: rotate(360deg); } }
@media (max-width: 820px) {
    .payment-dialog__body { grid-template-columns: 1fr; }
    .payment-dialog__form { grid-template-columns: 1fr; }
    .payment-dialog__dates { grid-template-columns: 1fr; }
}
</style>

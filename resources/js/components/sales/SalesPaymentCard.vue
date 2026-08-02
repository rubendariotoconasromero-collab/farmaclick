<template>
    <app-data-panel
        title="Condición de pago"
        subtitle="Defina cómo se cobrará esta venta."
        eyebrow="Paso 3"
    >
        <div class="payment-card">
            <label class="payment-card__field">
                <span>Tipo de pago *</span>
                <select v-model="datos.id_tipo_pago">
                    <option :value="0" disabled>Seleccione</option>
                    <option v-for="type in paymentTypes" :key="type.id" :value="type.id">{{ type.nombre }}</option>
                </select>
            </label>

            <label v-if="Number(datos.id_tipo_pago) === 1" class="payment-card__field">
                <span>Forma de pago *</span>
                <select v-model="datos.id_forma_pago">
                    <option :value="0" disabled>Seleccione</option>
                    <option v-for="form in paymentForms" :key="form.id" :value="form.id">{{ form.nombre }}</option>
                </select>
            </label>

            <app-input
                v-else
                v-model="datosPago.fecha_final"
                type="date"
                label="Vencimiento del crédito"
            />

            <div class="payment-card__status" :class="{ 'is-credit': Number(datos.id_tipo_pago) === 2 }">
                <img :src="statusIcon" alt="" aria-hidden="true">
                <span>
                    <strong>{{ statusTitle }}</strong>
                    <small>{{ statusHint }}</small>
                </span>
            </div>
        </div>
    </app-data-panel>
</template>

<script>
export default {
    name: 'SalesPaymentCard',
    props: {
        datos: { type: Object, required: true },
        datosPago: { type: Object, required: true },
        paymentTypes: { type: Array, default: () => [] },
        paymentForms: { type: Array, default: () => [] },
    },
    computed: {
        isCredit() {
            return Number(this.datos.id_tipo_pago) === 2;
        },
        statusTitle() {
            return this.isCredit ? 'Cuenta por cobrar' : 'Cobro inmediato';
        },
        statusHint() {
            return this.isCredit
                ? 'La venta aparecerá en Pagos de ventas.'
                : 'El importe se registrará en la caja abierta.';
        },
        statusIcon() {
            const icon = this.isCredit ? 'credit-card.svg' : 'money.svg';
            const mainIndex = window.location.pathname.indexOf('/main');
            const base = mainIndex >= 0 ? window.location.pathname.substring(0, mainIndex) : '';
            return `${base}/icons/${icon}`;
        },
    },
};
</script>

<style scoped>
.payment-card { display: grid; gap: .8rem; }
.payment-card__field { display: grid; gap: .35rem; color: var(--fc-ink, #17362b); font-size: .73rem; font-weight: 800; }
.payment-card__field select { min-height: 40px; padding: .48rem .68rem; color: var(--fc-ink, #17362b); background: #fff; border: 1px solid #bdd2c9; border-radius: var(--system-radius, 9px); outline: 0; }
.payment-card__field select:focus { border-color: var(--fc-blue-600, #0e93b5); box-shadow: 0 0 0 3px var(--system-focus-ring, rgba(62,198,224,.24)); }
.payment-card__status { display: flex; align-items: center; gap: .65rem; padding: .7rem; background: var(--fc-green-50, #effaf4); border: 1px solid #cde6d7; border-radius: var(--system-radius, 9px); }
.payment-card__status.is-credit { background: var(--fc-cyan-50, #effbfd); border-color: #c7e8ef; }
.payment-card__status img { width: 24px; height: 24px; opacity: .75; filter: invert(46%) sepia(47%) saturate(741%) hue-rotate(95deg); }
.payment-card__status span { display: flex; flex-direction: column; }
.payment-card__status strong { color: var(--fc-ink, #17362b); font-size: .76rem; }
.payment-card__status small { color: var(--system-text-muted, #5f716a); font-size: .66rem; }
</style>

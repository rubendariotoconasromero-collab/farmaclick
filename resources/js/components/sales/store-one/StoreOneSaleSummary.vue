<template>
    <section class="store-summary">
        <header><span>Resumen de venta</span><strong>{{ itemCount }} líneas</strong></header>
        <div class="store-summary__row"><span>Unidades vendidas</span><strong>{{ unitCount }}</strong></div>
        <div class="store-summary__row"><span>Subtotal</span><strong>{{ money(subtotal) }}</strong></div>
        <label class="store-summary__field">
            <span>Descuento</span>
            <input :value="datos.descuento" type="number" min="0" :max="subtotal" step="0.01" @input="update('descuento', $event.target.value)">
        </label>

        <template v-if="Number(datos.id_forma_pago) === 6">
            <label class="store-summary__field">
                <span>Parte en efectivo</span>
                <input :value="datos.total_efectivo" type="number" min="0" :max="total" step="0.01" @input="update('total_efectivo', $event.target.value)">
            </label>
            <div class="store-summary__row"><span>Parte por depósito</span><strong>{{ money(depositTotal) }}</strong></div>
        </template>

        <template v-if="[2, 6].includes(Number(datos.id_forma_pago)) && Number(datos.id_tipo_pago) === 1">
            <label class="store-summary__field store-summary__field--cash">
                <span>Efectivo recibido</span>
                <input :value="datos.efectivo" type="number" min="0" step="0.01" @input="update('efectivo', $event.target.value)">
            </label>
            <div class="store-summary__change"><span>Cambio</span><strong>{{ money(change) }}</strong></div>
        </template>

        <div class="store-summary__total"><span>Total a cobrar</span><strong>{{ money(total) }}</strong></div>
        <div class="store-summary__actions">
            <app-button variant="ghost" block :disabled="saving" @click="$emit('clear')">Limpiar venta</app-button>
            <app-button icon="icons/save.svg" block :loading="saving" @click="$emit('save')">Guardar e imprimir</app-button>
        </div>
    </section>
</template>

<script>
export default {
    name: 'StoreOneSaleSummary',
    props: {
        datos: { type: Object, required: true },
        itemCount: { type: Number, default: 0 },
        unitCount: { type: Number, default: 0 },
        subtotal: { type: Number, default: 0 },
        total: { type: Number, default: 0 },
        depositTotal: { type: Number, default: 0 },
        change: { type: Number, default: 0 },
        saving: { type: Boolean, default: false },
    },
    methods: {
        update(field, value) {
            this.$emit('update-value', { field, value: Number(value || 0) });
        },
        money(value) {
            return `${Number(value || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} Bs`;
        },
    },
};
</script>

<style scoped>
.store-summary { position: sticky; top: calc(var(--fc-header-height, 70px) + 1rem); overflow: hidden; background: #fff; border: 1px solid var(--system-border-color, #d8e5df); border-radius: var(--system-radius-lg, 14px); box-shadow: var(--system-shadow, 0 6px 20px rgba(23,54,43,.08)); }
.store-summary header { display: flex; justify-content: space-between; padding: 1rem; color: #fff; background: linear-gradient(115deg, var(--fc-green-700, #1f6b45), var(--fc-green-600, #1f8a4c)); border-bottom: 3px solid var(--fc-cyan-500, #3ec6e0); }
.store-summary header span { font-size: .68rem; font-weight: 900; text-transform: uppercase; letter-spacing: .07em; }
.store-summary header strong { font-size: .72rem; }
.store-summary__row { display: flex; justify-content: space-between; min-height: 45px; align-items: center; margin: 0 1rem; color: var(--system-text-muted, #5f716a); border-bottom: 1px solid var(--system-border-color, #d8e5df); }
.store-summary__row strong { color: var(--fc-ink, #17362b); }
.store-summary__field { display: grid; gap: .3rem; padding: .65rem 1rem; color: var(--system-text-muted, #5f716a); font-size: .7rem; font-weight: 800; }
.store-summary__field input { width: 100%; min-height: 38px; padding: .4rem .6rem; color: var(--fc-ink, #17362b); text-align: right; border: 1px solid #bdd2c9; border-radius: var(--system-radius, 9px); outline: 0; }
.store-summary__field--cash { background: #fff8e8; }
.store-summary__change { display: flex; justify-content: space-between; margin: 0 1rem; padding: .65rem; color: #805d14; background: #fff4d5; border-radius: 8px; }
.store-summary__total { display: flex; flex-direction: column; gap: .2rem; margin: .75rem; padding: 1rem; color: #fff; background: linear-gradient(120deg, var(--fc-green-600, #1f8a4c), var(--fc-green-500, #2fae66)); border-radius: 10px; }
.store-summary__total span { font-size: .68rem; font-weight: 800; text-transform: uppercase; }
.store-summary__total strong { font-size: 1.5rem; line-height: 1; }
.store-summary__actions { display: grid; gap: .45rem; padding: 0 .75rem .75rem; }
</style>

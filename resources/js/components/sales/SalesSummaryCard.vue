<template>
    <section class="summary-card" aria-label="Resumen de venta">
        <header>
            <span>Resumen</span>
            <strong>{{ itemCount }} {{ itemCount === 1 ? 'producto' : 'productos' }}</strong>
        </header>

        <div class="summary-card__row">
            <span>Unidades</span>
            <strong>{{ unitCount }}</strong>
        </div>
        <div class="summary-card__row">
            <span>Subtotal</span>
            <strong>{{ money(subtotal) }}</strong>
        </div>
        <label class="summary-card__discount">
            <span>Descuento general</span>
            <div><input :value="discount" type="number" min="0" :max="subtotal" step="0.01" @input="$emit('update:discount', Number($event.target.value || 0))"><small>Bs</small></div>
        </label>
        <div class="summary-card__total">
            <span>Total a cobrar</span>
            <strong>{{ money(total) }}</strong>
        </div>

        <div class="summary-card__actions">
            <app-button variant="ghost" block :disabled="saving" @click="$emit('clear')">Limpiar venta</app-button>
            <app-button icon="icons/save.svg" block :loading="saving" @click="$emit('save')">Guardar venta</app-button>
        </div>
    </section>
</template>

<script>
export default {
    name: 'SalesSummaryCard',
    props: {
        itemCount: { type: Number, default: 0 },
        unitCount: { type: Number, default: 0 },
        subtotal: { type: Number, default: 0 },
        discount: { type: [Number, String], default: 0 },
        saving: { type: Boolean, default: false },
    },
    computed: {
        total() {
            return Math.max(0, Number(this.subtotal || 0) - Number(this.discount || 0));
        },
    },
    methods: {
        money(value) {
            return `${Number(value || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} Bs`;
        },
    },
};
</script>

<style scoped>
.summary-card { position: sticky; top: calc(var(--fc-header-height, 70px) + 1rem); overflow: hidden; background: #fff; border: 1px solid var(--system-border-color, #d8e5df); border-radius: var(--system-radius-lg, 14px); box-shadow: var(--system-shadow, 0 6px 20px rgba(23,54,43,.08)); }
.summary-card header { display: flex; align-items: center; justify-content: space-between; padding: 1rem; color: #fff; background: linear-gradient(115deg, var(--fc-green-700, #1f6b45), var(--fc-green-600, #1f8a4c)); border-bottom: 3px solid var(--fc-cyan-500, #3ec6e0); }
.summary-card header span { font-size: .68rem; font-weight: 900; text-transform: uppercase; letter-spacing: .07em; }
.summary-card header strong { font-size: .75rem; }
.summary-card__row { display: flex; align-items: center; justify-content: space-between; min-height: 48px; margin: 0 1rem; color: var(--system-text-muted, #5f716a); border-bottom: 1px solid var(--system-border-color, #d8e5df); }
.summary-card__row strong { color: var(--fc-ink, #17362b); }
.summary-card__discount { display: grid; gap: .35rem; padding: .85rem 1rem; color: var(--system-text-muted, #5f716a); font-size: .72rem; font-weight: 800; }
.summary-card__discount > div { display: flex; overflow: hidden; background: #fff; border: 1px solid #bdd2c9; border-radius: var(--system-radius, 9px); }
.summary-card__discount input { width: 100%; min-height: 40px; padding: .45rem .65rem; color: var(--fc-ink, #17362b); text-align: right; border: 0; outline: 0; }
.summary-card__discount small { display: grid; padding: 0 .65rem; place-items: center; color: var(--fc-green-700, #1f6b45); background: var(--fc-green-50, #effaf4); }
.summary-card__total { display: flex; flex-direction: column; gap: .2rem; margin: 0 .8rem; padding: 1rem; color: #fff; background: linear-gradient(120deg, var(--fc-green-600, #1f8a4c), var(--fc-green-500, #2fae66)); border-radius: 10px; }
.summary-card__total span { font-size: .7rem; font-weight: 800; text-transform: uppercase; }
.summary-card__total strong { font-size: 1.55rem; line-height: 1; }
.summary-card__actions { display: grid; gap: .45rem; padding: .8rem; }
</style>

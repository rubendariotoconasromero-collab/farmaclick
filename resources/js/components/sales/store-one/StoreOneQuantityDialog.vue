<template>
    <div v-if="open" class="quantity-dialog" role="dialog" aria-modal="true" aria-labelledby="quantity-dialog-title">
        <section>
            <header><small>Cantidad inicial</small><h5 id="quantity-dialog-title">{{ product.articulo }}</h5></header>
            <div class="quantity-dialog__body">
                <label>
                    <span>Cantidad *</span>
                    <input ref="quantity" :value="value" type="number" min="1" :max="product.stock" @input="$emit('update:value', $event.target.value)" @keyup.enter="$emit('confirm')">
                </label>
                <div class="quantity-dialog__stock"><span>Stock disponible</span><strong>{{ product.stock || 0 }}</strong></div>
                <small v-if="error" class="quantity-dialog__error">{{ error }}</small>
            </div>
            <footer>
                <app-button variant="ghost" @click="$emit('cancel')">Cancelar</app-button>
                <app-button icon="icons/check.svg" :disabled="!Number(value)" @click="$emit('confirm')">Agregar producto</app-button>
            </footer>
        </section>
    </div>
</template>

<script>
export default {
    name: 'StoreOneQuantityDialog',
    props: {
        open: { type: Boolean, default: false },
        product: { type: Object, default: () => ({}) },
        value: { type: [String, Number], default: '' },
        error: { type: String, default: '' },
    },
    watch: {
        open(value) {
            if (value) this.$nextTick(() => this.$refs.quantity && this.$refs.quantity.focus());
        },
    },
};
</script>

<style scoped>
.quantity-dialog { position: fixed; inset: 0; z-index: 1080; display: grid; padding: 1rem; place-items: center; background: rgba(11,45,35,.62); backdrop-filter: blur(3px); }
.quantity-dialog > section { width: min(440px, 100%); overflow: hidden; background: #fff; border-radius: var(--system-radius-lg, 14px); box-shadow: var(--system-shadow-lg, 0 24px 70px rgba(10,56,42,.26)); }
.quantity-dialog header { padding: 1rem 1.2rem; color: #fff; background: linear-gradient(110deg, var(--system-sidebar-bg, #163f32), var(--fc-green-600, #1f8a4c)); border-bottom: 3px solid var(--fc-cyan-500, #3ec6e0); }
.quantity-dialog header small { color: #80dcec; font-size: .65rem; font-weight: 900; text-transform: uppercase; }
.quantity-dialog header h5 { margin: .2rem 0 0; font-weight: 800; }
.quantity-dialog__body { display: grid; gap: .75rem; padding: 1.2rem; }
.quantity-dialog label { display: grid; gap: .35rem; color: var(--fc-ink, #17362b); font-size: .72rem; font-weight: 800; }
.quantity-dialog input { min-height: 48px; padding: .5rem .7rem; color: var(--fc-ink, #17362b); font-size: 1.15rem; text-align: center; border: 1px solid #bdd2c9; border-radius: var(--system-radius, 9px); outline: 0; }
.quantity-dialog input:focus { border-color: var(--fc-blue-600, #0e93b5); box-shadow: 0 0 0 3px var(--system-focus-ring, rgba(62,198,224,.24)); }
.quantity-dialog__stock { display: flex; justify-content: space-between; padding: .65rem; color: var(--system-text-muted, #5f716a); background: var(--fc-green-50, #effaf4); border-radius: 8px; }
.quantity-dialog__stock strong { color: var(--fc-green-700, #1f6b45); }
.quantity-dialog__error { color: #b62e35; font-weight: 700; }
.quantity-dialog footer { display: flex; justify-content: flex-end; gap: .5rem; padding: .75rem 1rem; border-top: 1px solid var(--system-border-color, #d8e5df); }
@media (max-width: 700px) {
    .quantity-dialog footer { flex-direction: column-reverse; }
    .quantity-dialog footer .app-button { width: 100%; }
}
</style>

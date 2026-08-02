<template>
    <app-data-panel
        title="Cliente y fecha"
        subtitle="Identifique al comprador antes de agregar productos."
        eyebrow="Paso 1"
    >
        <div class="customer-card">
            <div v-if="datos.id_cliente" class="customer-card__selected">
                <div class="customer-card__avatar">{{ initial }}</div>
                <div class="customer-card__identity">
                    <small>Cliente seleccionado</small>
                    <strong>{{ datos.cliente }}</strong>
                    <span>{{ rateLabel }}</span>
                </div>
                <app-button
                    variant="secondary"
                    icon="icons/swap-horizontal.svg"
                    :disabled="disabled"
                    data-bs-toggle="modal"
                    data-bs-target="#salesCustomerModal"
                    @click="$emit('search')"
                >
                    Cambiar
                </app-button>
            </div>
            <button
                v-else
                class="customer-card__empty"
                type="button"
                :disabled="disabled"
                data-bs-toggle="modal"
                data-bs-target="#salesCustomerModal"
                @click="$emit('search')"
            >
                <span class="customer-card__plus">+</span>
                <span><strong>Seleccionar cliente</strong><small>Buscar por nombre, NIT o CI</small></span>
            </button>

            <app-input v-model="datos.fecha" type="date" label="Fecha de venta" readonly />
        </div>
    </app-data-panel>
</template>

<script>
export default {
    name: 'SalesCustomerCard',
    props: {
        datos: { type: Object, required: true },
        rateLabel: { type: String, required: true },
        disabled: { type: Boolean, default: false },
    },
    computed: {
        initial() {
            return String(this.datos.cliente || '?').trim().charAt(0).toUpperCase();
        },
    },
};
</script>

<style scoped>
.customer-card { display: grid; grid-template-columns: minmax(0, 1fr) 210px; gap: 1rem; align-items: end; }
.customer-card__selected { display: flex; align-items: center; gap: .8rem; min-height: 72px; padding: .8rem; background: var(--fc-green-50, #effaf4); border: 1px solid #c9e5d5; border-radius: var(--system-radius, 9px); }
.customer-card__avatar { display: grid; flex: 0 0 44px; width: 44px; height: 44px; place-items: center; color: #fff; font-size: 1.05rem; font-weight: 900; background: var(--fc-green-600, #1f8a4c); border-radius: 12px; }
.customer-card__identity { display: flex; min-width: 0; flex: 1; flex-direction: column; }
.customer-card__identity small { color: var(--system-text-muted, #5f716a); font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; }
.customer-card__identity strong { overflow: hidden; color: var(--fc-ink, #17362b); font-size: .95rem; text-overflow: ellipsis; white-space: nowrap; }
.customer-card__identity span { width: max-content; margin-top: .2rem; padding: .16rem .4rem; color: var(--fc-blue-600, #0e93b5); font-size: .65rem; font-weight: 800; background: var(--fc-cyan-50, #effbfd); border-radius: 999px; }
.customer-card__empty { display: flex; align-items: center; gap: .8rem; min-height: 72px; padding: .8rem; color: var(--fc-ink, #17362b); text-align: left; background: #fff; border: 1px dashed #9dc9b0; border-radius: var(--system-radius, 9px); }
.customer-card__empty:hover:not(:disabled) { background: var(--fc-green-50, #effaf4); border-color: var(--fc-green-500, #2fae66); }
.customer-card__empty:disabled { opacity: .55; }
.customer-card__empty > span:last-child { display: flex; flex-direction: column; }
.customer-card__empty small { color: var(--system-text-muted, #5f716a); font-size: .7rem; }
.customer-card__plus { display: grid; width: 38px; height: 38px; place-items: center; color: #fff; font-size: 1.35rem; background: var(--fc-green-600, #1f8a4c); border-radius: 10px; }
@media (max-width: 720px) { .customer-card { grid-template-columns: 1fr; } }
</style>

<template>
    <app-data-panel title="Cliente y cobro" subtitle="Identifique al cliente y configure la condición de pago." eyebrow="Paso 1">
        <div class="setup-grid">
            <div class="setup-customer">
                <label for="store-sale-customer">Cliente</label>
                <div class="setup-customer__input">
                    <app-input
                        id="store-sale-customer"
                        :value="keyword"
                        placeholder="Nombre del cliente"
                        autocomplete="off"
                        @input="onCustomerInput"
                    />
                    <button v-if="keyword" type="button" aria-label="Limpiar cliente" @click="$emit('clear-customer')">×</button>
                </div>
                <div v-if="customers.length && keyword" class="setup-customer__results">
                    <button v-for="customer in customers" :key="customer.id" type="button" @click="$emit('select-customer', customer)">
                        <span>{{ initial(customer.nombre) }}</span>
                        <div><strong>{{ customer.nombre }} {{ customer.apellidos || '' }}</strong><small>{{ customer.ci || customer.matricula || 'Sin documento' }}</small></div>
                    </button>
                </div>
                <small class="setup-customer__hint">Si el nombre no existe, se registrará al guardar la venta.</small>
            </div>

            <app-input v-model="datos.fecha" type="date" label="Fecha de venta" readonly />

            <label class="setup-field">
                <span>Tipo de pago *</span>
                <select v-model="datos.id_tipo_pago">
                    <option :value="0" disabled>Seleccione</option>
                    <option v-for="type in paymentTypes" :key="type.id" :value="type.id">{{ type.nombre }}</option>
                </select>
            </label>

            <label v-if="Number(datos.id_tipo_pago) === 1" class="setup-field">
                <span>Forma de pago *</span>
                <select v-model="datos.id_forma_pago">
                    <option :value="0" disabled>Seleccione</option>
                    <option v-for="form in paymentForms" :key="form.id" :value="form.id">{{ form.nombre }}</option>
                </select>
            </label>

            <app-input v-else v-model="datosPago.fecha_final" type="date" label="Vencimiento del crédito" />
        </div>
    </app-data-panel>
</template>

<script>
export default {
    name: 'StoreOneSaleSetupCard',
    props: {
        datos: { type: Object, required: true },
        datosPago: { type: Object, required: true },
        keyword: { type: String, default: '' },
        customers: { type: Array, default: () => [] },
        paymentTypes: { type: Array, default: () => [] },
        paymentForms: { type: Array, default: () => [] },
    },
    methods: {
        onCustomerInput(value) {
            this.$emit('update:keyword', value);
            this.$emit('search-customer', value);
        },
        initial(name) {
            return String(name || '?').trim().charAt(0).toUpperCase();
        },
    },
};
</script>

<style scoped>
.setup-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .9rem; }
.setup-customer { position: relative; grid-column: 1 / -1; }
.setup-customer > label, .setup-field > span { display: block; margin-bottom: .35rem; color: var(--fc-ink, #17362b); font-size: .73rem; font-weight: 800; }
.setup-customer__input { display: grid; grid-template-columns: 1fr auto; }
.setup-customer__input button { width: 40px; color: #a72f36; font-size: 1.15rem; background: #fff1f1; border: 1px solid #efcbcd; border-left: 0; border-radius: 0 var(--system-radius, 9px) var(--system-radius, 9px) 0; }
.setup-customer__results { position: absolute; top: 66px; right: 0; left: 0; z-index: 20; max-height: 250px; overflow: auto; background: #fff; border: 1px solid var(--system-border-color, #d8e5df); border-radius: var(--system-radius, 9px); box-shadow: var(--system-shadow-lg, 0 24px 70px rgba(10,56,42,.26)); }
.setup-customer__results button { display: flex; width: 100%; align-items: center; gap: .7rem; padding: .65rem .75rem; color: var(--fc-ink, #17362b); text-align: left; background: #fff; border: 0; border-bottom: 1px solid #edf2ef; }
.setup-customer__results button:hover { background: var(--fc-green-50, #effaf4); }
.setup-customer__results button > span { display: grid; flex: 0 0 34px; width: 34px; height: 34px; place-items: center; color: #fff; font-weight: 900; background: var(--fc-green-600, #1f8a4c); border-radius: 9px; }
.setup-customer__results button > div { display: flex; flex-direction: column; }
.setup-customer__results small, .setup-customer__hint { color: var(--system-text-muted, #5f716a); font-size: .66rem; }
.setup-customer__hint { display: block; margin-top: .3rem; }
.setup-field { display: block; }
.setup-field select { width: 100%; min-height: 40px; padding: .48rem .68rem; color: var(--fc-ink, #17362b); background: #fff; border: 1px solid #bdd2c9; border-radius: var(--system-radius, 9px); outline: 0; }
.setup-field select:focus { border-color: var(--fc-blue-600, #0e93b5); box-shadow: 0 0 0 3px var(--system-focus-ring, rgba(62,198,224,.24)); }
@media (max-width: 650px) { .setup-grid { grid-template-columns: 1fr; } .setup-customer { grid-column: auto; } }
</style>

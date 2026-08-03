<template>
    <app-data-panel title="Cliente y cobro" subtitle="Identifique al cliente y configure la condición de pago." eyebrow="Paso 1">
        <div class="setup-grid">
            <div class="setup-customer">
                <app-live-search
                    id="store-sale-customer"
                    label="Cliente"
                    :value="datos.id_cliente"
                    :search-value="keyword"
                    :items="customers"
                    :loading="customerLoading"
                    track-by="id"
                    display-by="nombre"
                    placeholder="Buscar cliente por nombre…"
                    hint="Si el nombre no existe, se registrará al guardar la venta."
                    empty-text="No se encontraron clientes"
                    @search="onCustomerInput"
                    @select="onCustomerSelect"
                />
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
        customerLoading: { type: Boolean, default: false },
        paymentTypes: { type: Array, default: () => [] },
        paymentForms: { type: Array, default: () => [] },
    },
    methods: {
        onCustomerInput(value) {
            this.$emit('update:keyword', value);
            this.$emit('search-customer', value);
        },
        onCustomerSelect(customer) {
            if (customer) {
                this.$emit('select-customer', customer);
                return;
            }
            this.$emit('clear-customer');
        },
    },
};
</script>

<style scoped>
.setup-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .7rem .9rem; }
.setup-customer { position: relative; }
.setup-field > span { display: block; margin-bottom: .35rem; color: var(--fc-ink, #17362b); font-size: .73rem; font-weight: 800; }
.setup-field { display: block; }
.setup-field select { width: 100%; min-height: 40px; padding: .48rem .68rem; color: var(--fc-ink, #17362b); background: #fff; border: 1px solid #bdd2c9; border-radius: var(--system-radius, 9px); outline: 0; }
.setup-field select:focus { border-color: var(--fc-blue-600, #0e93b5); box-shadow: 0 0 0 3px var(--system-focus-ring, rgba(62,198,224,.24)); }
@media (max-width: 650px) { .setup-grid { grid-template-columns: 1fr; } }
</style>

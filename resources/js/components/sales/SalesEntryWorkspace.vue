<template>
    <section class="sales-workspace">
        <app-module-header
            eyebrow="Ventas"
            title="Nueva venta"
            subtitle="Registre cliente, productos y cobro en un flujo único."
        >
            <template #actions>
                <app-button variant="ghost" :disabled="saving" @click="$emit('cancel')">Limpiar formulario</app-button>
            </template>
        </app-module-header>

        <nav class="sales-progress" aria-label="Progreso de la venta">
            <div class="sales-progress__item" :class="{ 'is-complete': customerReady, 'is-current': !customerReady }">
                <span>{{ customerReady ? '✓' : '1' }}</span>
                <div><strong>Cliente</strong><small>{{ customerReady ? datos.cliente : 'Pendiente' }}</small></div>
            </div>
            <i></i>
            <div class="sales-progress__item" :class="{ 'is-complete': productsReady, 'is-current': customerReady && !productsReady }">
                <span>{{ productsReady ? '✓' : '2' }}</span>
                <div><strong>Productos</strong><small>{{ productsReady ? `${details.length} agregados` : 'Pendiente' }}</small></div>
            </div>
            <i></i>
            <div class="sales-progress__item" :class="{ 'is-complete': paymentReady, 'is-current': productsReady && !paymentReady }">
                <span>{{ paymentReady ? '✓' : '3' }}</span>
                <div><strong>Pago</strong><small>{{ paymentStatus }}</small></div>
            </div>
        </nav>

        <div class="sales-workspace__grid">
            <div class="sales-workspace__main">
                <sales-customer-card
                    :datos="datos"
                    :rate-label="priceLabel"
                    :disabled="disableCustomer"
                    @search="$emit('search-customers')"
                />

                <sales-line-items
                    :details="details"
                    :price-field="priceField"
                    :price-label="priceLabel"
                    :customer-selected="customerReady"
                    :disabled="disableProducts"
                    @search-products="$emit('search-products')"
                    @update-price="updatePrice"
                    @update-quantity="updateQuantity"
                    @remove="$emit('remove-line', $event)"
                />
            </div>

            <aside class="sales-workspace__aside">
                <sales-payment-card
                    :datos="datos"
                    :datos-pago="datosPago"
                    :payment-types="paymentTypes"
                    :payment-forms="paymentForms"
                />
                <sales-summary-card
                    :item-count="details.length"
                    :unit-count="unitCount"
                    :subtotal="calculatedSubtotal"
                    :discount="datos.descuento"
                    :saving="saving"
                    @update:discount="datos.descuento = $event"
                    @clear="$emit('cancel')"
                    @save="$emit('save')"
                />
            </aside>
        </div>

        <sales-customer-picker-modal
            :customers="customers"
            :search="catalogSearch"
            @update:search="$emit('update:catalogSearch', $event)"
            @search="$emit('search-customers')"
            @select="$emit('select-customer', $event)"
            @close="$emit('clear-customer-search')"
        />

        <sales-product-picker-modal
            :products="products"
            :selected-ids="selectedProductIds"
            :search="catalogSearch"
            :price-label="priceLabel"
            @update:search="$emit('update:catalogSearch', $event)"
            @search="$emit('search-products')"
            @select="$emit('select-product', $event)"
            @close="$emit('clear-product-search')"
        />
    </section>
</template>

<script>
import SalesCustomerCard from './SalesCustomerCard.vue';
import SalesPaymentCard from './SalesPaymentCard.vue';
import SalesSummaryCard from './SalesSummaryCard.vue';
import SalesLineItems from './SalesLineItems.vue';
import SalesCustomerPickerModal from './SalesCustomerPickerModal.vue';
import SalesProductPickerModal from './SalesProductPickerModal.vue';

export default {
    name: 'SalesEntryWorkspace',
    components: {
        SalesCustomerCard,
        SalesPaymentCard,
        SalesSummaryCard,
        SalesLineItems,
        SalesCustomerPickerModal,
        SalesProductPickerModal,
    },
    props: {
        datos: { type: Object, required: true },
        datosPago: { type: Object, required: true },
        details: { type: Array, default: () => [] },
        products: { type: Array, default: () => [] },
        customers: { type: Array, default: () => [] },
        paymentTypes: { type: Array, default: () => [] },
        paymentForms: { type: Array, default: () => [] },
        catalogSearch: { type: String, default: '' },
        calculatedSubtotal: { type: Number, default: 0 },
        disableCustomer: { type: Boolean, default: false },
        disableProducts: { type: Boolean, default: false },
        saving: { type: Boolean, default: false },
    },
    computed: {
        customerReady() {
            return Boolean(this.datos.id_cliente);
        },
        productsReady() {
            return this.details.length > 0;
        },
        paymentReady() {
            if (Number(this.datos.id_tipo_pago) === 1) return Boolean(Number(this.datos.id_forma_pago));
            if (Number(this.datos.id_tipo_pago) === 2) return Boolean(this.datosPago.fecha_final);
            return false;
        },
        paymentStatus() {
            if (!this.paymentReady) return 'Pendiente';
            return Number(this.datos.id_tipo_pago) === 2 ? 'Crédito' : 'Contado';
        },
        priceField() {
            return ({
                1: 'costo_unitario',
                2: 'costo_mayorista',
                3: 'costo_preferencial',
            })[Number(this.datos.id_descuento)] || 'costo_unitario';
        },
        priceLabel() {
            return ({
                1: 'Tarifa unitaria',
                2: 'Tarifa mayorista',
                3: 'Tarifa preferencial',
            })[Number(this.datos.id_descuento)] || 'Tarifa por definir';
        },
        unitCount() {
            return this.details.reduce((total, row) => total + Number(row.cantidad || 0), 0);
        },
        selectedProductIds() {
            return this.details.map(row => Number(row.id_articulo));
        },
    },
    methods: {
        updatePrice({ row, value }) {
            this.$set(row, this.priceField, Number(value || 0));
        },
        updateQuantity({ row, value }) {
            this.$set(row, 'cantidad', value === '' ? '' : Number(value));
        },
    },
};
</script>

<style scoped>
.sales-workspace { display: grid; gap: 1rem; min-height: 100%; padding: 1.15rem; background: var(--system-body-bg, #f4f9f7); }
.sales-progress { display: grid; grid-template-columns: minmax(130px, 1fr) minmax(30px, .35fr) minmax(130px, 1fr) minmax(30px, .35fr) minmax(130px, 1fr); align-items: center; padding: .75rem 1rem; background: #fff; border: 1px solid var(--system-border-color, #d8e5df); border-radius: var(--system-radius-lg, 14px); box-shadow: var(--system-shadow, 0 6px 20px rgba(23,54,43,.08)); }
.sales-progress > i { height: 2px; background: var(--system-border-color, #d8e5df); }
.sales-progress__item { display: flex; align-items: center; gap: .65rem; min-width: 0; color: var(--system-text-muted, #5f716a); }
.sales-progress__item > span { display: grid; flex: 0 0 32px; width: 32px; height: 32px; place-items: center; color: #6f817a; font-size: .75rem; font-weight: 900; background: #edf3f0; border: 1px solid #d5e1dc; border-radius: 50%; }
.sales-progress__item > div { display: flex; min-width: 0; flex-direction: column; }
.sales-progress__item strong { color: var(--fc-ink, #17362b); font-size: .74rem; }
.sales-progress__item small { overflow: hidden; font-size: .64rem; text-overflow: ellipsis; white-space: nowrap; }
.sales-progress__item.is-current > span { color: #fff; background: var(--fc-blue-600, #0e93b5); border-color: var(--fc-blue-600, #0e93b5); box-shadow: 0 0 0 4px var(--system-focus-ring, rgba(62,198,224,.24)); }
.sales-progress__item.is-complete > span { color: #fff; background: var(--fc-green-600, #1f8a4c); border-color: var(--fc-green-600, #1f8a4c); }
.sales-workspace__grid { display: grid; grid-template-columns: minmax(0, 1fr) 310px; gap: 1rem; align-items: start; }
.sales-workspace__main, .sales-workspace__aside { display: grid; gap: 1rem; min-width: 0; }
@media (max-width: 1050px) {
    .sales-workspace__grid { grid-template-columns: 1fr; }
    .sales-workspace__aside { grid-template-columns: minmax(0, 1fr) minmax(280px, .8fr); }
}
@media (max-width: 720px) {
    .sales-workspace { padding: .75rem; }
    .sales-progress { grid-template-columns: 1fr; gap: .55rem; }
    .sales-progress > i { display: none; }
    .sales-workspace__aside { grid-template-columns: 1fr; }
}
</style>

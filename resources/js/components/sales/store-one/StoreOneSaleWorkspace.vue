<template>
    <section class="store-sale-page">
        <app-module-header eyebrow="Ventas" title="Nueva venta" subtitle="Tienda Primera · Venta por lote y presentación">
            <template #actions><span class="cash-status" :class="{ 'is-open': cashState === 'Abierta' }"><i></i>Caja {{ cashState || 'sin verificar' }}</span></template>
        </app-module-header>

        <div v-if="cashState !== 'Abierta'" class="cash-warning">
            <img :src="lockIcon" alt="" aria-hidden="true">
            <div><strong>La caja está cerrada</strong><span>Debe aperturar la caja antes de registrar una venta.</span></div>
        </div>

        <template v-else>
            <nav class="store-sale-progress" aria-label="Progreso de venta">
                <span :class="{ done: customerReady }"><b>{{ customerReady ? '✓' : '1' }}</b> Cliente y pago</span>
                <i></i>
                <span :class="{ done: details.length }"><b>{{ details.length ? '✓' : '2' }}</b> Productos y lotes</span>
                <i></i>
                <span :class="{ done: readyToSave }"><b>{{ readyToSave ? '✓' : '3' }}</b> Confirmación</span>
            </nav>

            <div class="store-sale-grid">
                <div class="store-sale-main">
                    <store-one-sale-setup-card
                        :datos="datos"
                        :datos-pago="datosPago"
                        :keyword="customerKeyword"
                        :customers="customers"
                        :payment-types="paymentTypes"
                        :payment-forms="paymentForms"
                        @update:keyword="$emit('update:customerKeyword', $event)"
                        @search-customer="$emit('search-customer', $event)"
                        @select-customer="$emit('select-customer', $event)"
                        @clear-customer="$emit('clear-customer')"
                    />
                    <store-one-sale-lines
                        :details="details"
                        :disabled="saving"
                        @open-products="$emit('open-products')"
                        @update-line="$emit('update-line', $event)"
                        @remove="$emit('remove-line', $event)"
                    />
                </div>
                <aside>
                    <store-one-sale-summary
                        :datos="datos"
                        :item-count="details.length"
                        :unit-count="unitCount"
                        :subtotal="subtotal"
                        :total="total"
                        :deposit-total="depositTotal"
                        :change="change"
                        :saving="saving"
                        @update-value="$emit('update-summary', $event)"
                        @clear="$emit('clear')"
                        @save="$emit('save')"
                    />
                </aside>
            </div>
        </template>

        <store-one-product-picker
            :open="productModalOpen"
            :products="products"
            :suppliers="suppliers"
            :selected-ids="selectedLotIds"
            :pagination="pagination"
            :pages="pages"
            :search="productSearch"
            :criterion="productCriterion"
            :supplier-id="supplierId"
            @update:search="$emit('update:productSearch', $event)"
            @update:criterion="$emit('update:productCriterion', $event)"
            @update:supplierId="$emit('update:supplierId', $event)"
            @search="$emit('search-products')"
            @page="$emit('product-page', $event)"
            @select="$emit('select-product', $event)"
            @close="$emit('close-products')"
        />

        <store-one-quantity-dialog
            :open="quantityOpen"
            :product="selectedProduct"
            :value="quantity"
            :error="quantityError"
            @update:value="$emit('update:quantity', $event)"
            @cancel="$emit('cancel-quantity')"
            @confirm="$emit('confirm-quantity')"
        />
    </section>
</template>

<script>
import StoreOneSaleSetupCard from './StoreOneSaleSetupCard.vue';
import StoreOneSaleLines from './StoreOneSaleLines.vue';
import StoreOneSaleSummary from './StoreOneSaleSummary.vue';
import StoreOneProductPicker from './StoreOneProductPicker.vue';
import StoreOneQuantityDialog from './StoreOneQuantityDialog.vue';

export default {
    name: 'StoreOneSaleWorkspace',
    components: { StoreOneSaleSetupCard, StoreOneSaleLines, StoreOneSaleSummary, StoreOneProductPicker, StoreOneQuantityDialog },
    props: {
        cashState: { type: String, default: '' },
        datos: { type: Object, required: true },
        datosPago: { type: Object, required: true },
        customerKeyword: { type: String, default: '' },
        customers: { type: Array, default: () => [] },
        paymentTypes: { type: Array, default: () => [] },
        paymentForms: { type: Array, default: () => [] },
        details: { type: Array, default: () => [] },
        products: { type: Array, default: () => [] },
        suppliers: { type: Array, default: () => [] },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        productModalOpen: { type: Boolean, default: false },
        productSearch: { type: String, default: '' },
        productCriterion: { type: String, default: '' },
        supplierId: { type: Number, default: 0 },
        selectedProduct: { type: Object, default: () => ({}) },
        quantityOpen: { type: Boolean, default: false },
        quantity: { type: [String, Number], default: '' },
        quantityError: { type: String, default: '' },
        subtotal: { type: Number, default: 0 },
        total: { type: Number, default: 0 },
        depositTotal: { type: Number, default: 0 },
        change: { type: Number, default: 0 },
        saving: { type: Boolean, default: false },
    },
    computed: {
        customerReady() { return Boolean(String(this.customerKeyword || '').trim()); },
        unitCount() { return this.details.reduce((sum, row) => sum + Number(row.cantidad || 0), 0); },
        readyToSave() { return this.customerReady && this.details.length > 0 && this.total >= 0; },
        selectedLotIds() { return this.details.map(row => Number(row.id_lote)); },
        lockIcon() {
            const mainIndex = window.location.pathname.indexOf('/main');
            const base = mainIndex >= 0 ? window.location.pathname.substring(0, mainIndex) : '';
            return `${base}/icons/lock-locked.svg`;
        },
    },
};
</script>

<style scoped>
.store-sale-page { display: grid; gap: 1rem; min-height: 100%; padding: 1.15rem; background: var(--system-body-bg, #f4f9f7); }
.cash-status { display: inline-flex; align-items: center; gap: .4rem; padding: .35rem .6rem; color: #9e2929; font-size: .68rem; font-weight: 900; background: #fff0f0; border: 1px solid #f0caca; border-radius: 999px; }
.cash-status i { width: 7px; height: 7px; background: #d63c3c; border-radius: 50%; }
.cash-status.is-open { color: var(--fc-green-700, #1f6b45); background: var(--fc-green-50, #effaf4); border-color: #c9e5d5; }
.cash-status.is-open i { background: var(--fc-green-500, #2fae66); }
.cash-warning { display: flex; align-items: center; gap: 1rem; min-height: 130px; padding: 1.2rem; background: #fff8e8; border: 1px solid #ecdba9; border-radius: var(--system-radius-lg, 14px); box-shadow: var(--system-shadow, 0 6px 20px rgba(23,54,43,.08)); }
.cash-warning img { width: 38px; height: 38px; opacity: .65; }
.cash-warning div { display: flex; flex-direction: column; }
.cash-warning strong { color: #765410; font-size: 1rem; }
.cash-warning span { color: #8d753e; }
.store-sale-progress { display: grid; grid-template-columns: 1fr minmax(30px,.4fr) 1fr minmax(30px,.4fr) 1fr; align-items: center; padding: .7rem 1rem; background: #fff; border: 1px solid var(--system-border-color, #d8e5df); border-radius: var(--system-radius-lg, 14px); }
.store-sale-progress > i { height: 2px; background: var(--system-border-color, #d8e5df); }
.store-sale-progress > span { display: flex; align-items: center; gap: .5rem; color: var(--system-text-muted, #5f716a); font-size: .7rem; font-weight: 800; }
.store-sale-progress b { display: grid; width: 29px; height: 29px; place-items: center; background: #edf3f0; border-radius: 50%; }
.store-sale-progress span.done { color: var(--fc-green-700, #1f6b45); }
.store-sale-progress span.done b { color: #fff; background: var(--fc-green-600, #1f8a4c); }
.store-sale-grid { display: grid; grid-template-columns: minmax(0,1fr) 310px; gap: 1rem; align-items: start; }
.store-sale-main { display: grid; gap: 1rem; min-width: 0; }
@media (max-width: 1050px) { .store-sale-grid { grid-template-columns: 1fr; } }
@media (max-width: 700px) { .store-sale-page { padding: .75rem; } .store-sale-progress { grid-template-columns: 1fr; gap: .45rem; } .store-sale-progress > i { display: none; } }
</style>

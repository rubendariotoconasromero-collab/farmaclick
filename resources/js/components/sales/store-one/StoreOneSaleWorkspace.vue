<template>
    <section class="store-sale-page" :class="{ 'is-loading': initialLoading }">
        <app-page-skeleton
            v-if="initialLoading"
            :rows="5"
            :columns="6"
            label="Preparando el punto de venta"
        />

        <template v-else>
        <app-module-header eyebrow="Ventas" title="Nueva venta" subtitle="Tienda Primera · Venta por lote y presentación">
            <template #actions>
                <app-button v-if="lastSaleId" variant="secondary" icon="icons/print.svg" @click="$emit('open-report-modal')">Imprimir última nota</app-button>
                <span class="cash-status" :class="cashStatusClass" role="status" aria-live="polite">
                    <i></i>{{ cashStatusLabel }}
                </span>
            </template>
        </app-module-header>

        <div v-if="cashLoading" class="cash-feedback is-checking" role="status" aria-live="polite">
            <span class="cash-feedback__loader" aria-hidden="true"></span>
            <div><strong>Verificando la caja</strong><span>Estamos consultando el estado actual antes de habilitar la venta.</span></div>
        </div>

        <div v-else-if="cashError" class="cash-feedback is-error" role="alert">
            <img :src="lockIcon" alt="" aria-hidden="true">
            <div><strong>No se pudo verificar la caja</strong><span>{{ cashError }}</span></div>
            <button type="button" class="cash-feedback__retry" @click="$emit('retry-cash')">Reintentar</button>
        </div>

        <div v-else-if="cashState === 'Cerrada'" class="cash-feedback is-closed">
            <img :src="lockIcon" alt="" aria-hidden="true">
            <div><strong>La caja está cerrada</strong><span>Debe aperturar la caja antes de registrar una venta.</span></div>
        </div>

        <template v-else-if="cashState === 'Abierta'">
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
                        :customer-loading="customerLoading"
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
        <app-report-format-modal
            :open="reportModalOpen"
            :loading-format="reportLoadingFormat"
            context-label="Venta registrada"
            title="¿Cómo desea generar la nota de venta?"
            footer-hint="Puede volver a imprimir la última nota desde el encabezado del módulo."
            @select="$emit('generate-report', $event)"
            @close="$emit('close-report-modal')"
        />
        </template>
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
        cashLoading: { type: Boolean, default: false },
        cashError: { type: String, default: '' },
        initialLoading: { type: Boolean, default: false },
        datos: { type: Object, required: true },
        datosPago: { type: Object, required: true },
        customerKeyword: { type: String, default: '' },
        customers: { type: Array, default: () => [] },
        customerLoading: { type: Boolean, default: false },
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
        lastSaleId: { type: Number, default: 0 },
        reportModalOpen: { type: Boolean, default: false },
        reportLoadingFormat: { type: String, default: '' },
    },
    computed: {
        cashStatusClass() {
            return {
                'is-open': this.cashState === 'Abierta' && !this.cashLoading && !this.cashError,
                'is-checking': this.cashLoading,
                'is-error': Boolean(this.cashError),
            };
        },
        cashStatusLabel() {
            if (this.cashLoading) return 'Verificando caja';
            if (this.cashError) return 'Caja sin verificar';
            return `Caja ${this.cashState === 'Abierta' ? 'abierta' : 'cerrada'}`;
        },
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
.store-sale-page.is-loading { padding: 0; }
.cash-status { display: inline-flex; align-items: center; gap: .4rem; padding: .35rem .6rem; color: #9e2929; font-size: .68rem; font-weight: 900; background: #fff0f0; border: 1px solid #f0caca; border-radius: 999px; }
.cash-status i { width: 7px; height: 7px; background: #d63c3c; border-radius: 50%; }
.cash-status.is-open { color: var(--fc-green-700, #1f6b45); background: var(--fc-green-50, #effaf4); border-color: #c9e5d5; }
.cash-status.is-open i { background: var(--fc-green-500, #2fae66); }
.cash-status.is-checking { color: #17647a; background: #edf9fc; border-color: #bde5ee; }
.cash-status.is-checking i { background: #3ebed8; animation: cash-pulse 1.1s ease-in-out infinite; }
.cash-status.is-error { color: #9e2929; background: #fff0f0; border-color: #f0caca; }
.cash-feedback { display: flex; align-items: center; gap: 1rem; min-height: 130px; padding: 1.2rem; border-radius: var(--system-radius-lg, 14px); box-shadow: var(--system-shadow, 0 6px 20px rgba(23,54,43,.08)); }
.cash-feedback img { width: 38px; height: 38px; opacity: .65; }
.cash-feedback div { display: flex; flex: 1; flex-direction: column; }
.cash-feedback strong { font-size: 1rem; }
.cash-feedback.is-closed { background: #fff8e8; border: 1px solid #ecdba9; }
.cash-feedback.is-closed strong { color: #765410; }
.cash-feedback.is-closed span { color: #8d753e; }
.cash-feedback.is-checking { color: #17647a; background: #f2fbfd; border: 1px solid #bee5ed; }
.cash-feedback.is-error { color: #8f3030; background: #fff5f5; border: 1px solid #efcccc; }
.cash-feedback__loader { flex: 0 0 38px; width: 38px; height: 38px; border: 3px solid #bde5ee; border-top-color: #2baac5; border-radius: 50%; animation: cash-spin .8s linear infinite; }
.cash-feedback__retry { padding: .55rem .85rem; color: #fff; font-size: .72rem; font-weight: 800; background: var(--fc-green-600, #1f8a4c); border: 0; border-radius: 8px; cursor: pointer; }
.cash-feedback__retry:hover { background: var(--fc-green-700, #1f6b45); }
@keyframes cash-spin { to { transform: rotate(360deg); } }
@keyframes cash-pulse { 50% { opacity: .35; transform: scale(.78); } }
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

<template>
    <section class="purchase-page">
        <app-page-skeleton v-if="initialLoading" />
        <div v-else class="purchase-page__content">
        <app-module-header
            eyebrow="Compras"
            :title="listado === 2 ? 'Detalle de compra' : 'Nueva compra'"
            :subtitle="listado === 2
                ? 'Consulta la información y los productos registrados.'
                : 'Registra proveedor, condiciones de pago y productos en un solo flujo.'"
        >
            <template v-if="listado === 2" #actions>
                <app-button variant="ghost" icon="icons/arrow-left.svg" @click="$emit('cancel')">Volver</app-button>
            </template>
        </app-module-header>

        <template v-if="listado === 0">
            <div class="purchase-layout">
                <app-data-panel title="Datos de la compra" subtitle="Información comercial y condición de pago." eyebrow="Paso 1">
                    <div class="purchase-form-grid">
                        <app-live-search
                            class="purchase-field--wide"
                            label="Proveedor"
                            required
                            :value="datos.id_proveedor"
                            :items="providers"
                            track-by="id"
                            display-by="nombre"
                            placeholder="Buscar proveedor…"
                            @input="datos.id_proveedor = $event"
                        />
                        <app-input v-model="datos.fecha" type="date" label="Fecha" />
                        <label class="purchase-field">
                            <span>Tipo de pago *</span>
                            <select v-model="datos.id_tipo_pago">
                                <option value="0" disabled>Seleccione el tipo</option>
                                <option v-for="type in paymentTypes" :key="type.id" :value="type.id">{{ type.nombre }}</option>
                            </select>
                        </label>
                        <app-input
                            v-if="Number(datos.id_tipo_pago) === 2"
                            v-model="datosPago.fecha_final"
                            type="date"
                            label="Vencimiento del crédito"
                        />
                        <label v-if="Number(datos.id_tipo_pago) === 1" class="purchase-field">
                            <span>Forma de pago *</span>
                            <select v-model="datos.id_forma_pago">
                                <option value="0" disabled>Seleccione la forma</option>
                                <option v-for="form in paymentForms" :key="form.id" :value="form.id">{{ form.nombre }}</option>
                            </select>
                        </label>
                        <app-input
                            v-model="datos.descripcion"
                            class="purchase-field--full"
                            label="Descripción"
                            placeholder="Observaciones opcionales de la compra"
                            multiline
                            :rows="1"
                        />
                    </div>
                </app-data-panel>

                <aside class="purchase-summary">
                    <span>Resumen</span>
                    <div><small>Productos</small><strong>{{ details.length }}</strong></div>
                    <div><small>Subtotal</small><strong>Bs {{ money(calculatedTotal) }}</strong></div>
                    <div class="purchase-summary__discount">
                        <small>Descuento general</small>
                        <input v-model.number="datos.descuento" type="number" min="0" step="0.01">
                    </div>
                    <div class="purchase-summary__total"><small>Total</small><strong>Bs {{ money(grandTotal) }}</strong></div>
                    <template v-if="Number(datos.id_forma_pago) === 6">
                        <div class="purchase-summary__discount">
                            <small>Pago en efectivo</small>
                            <input v-model.number="datos.total_efectivo" type="number" min="0" :max="grandTotal" step="0.01">
                        </div>
                        <div><small>Pago por depósito</small><strong>Bs {{ money(mixedDeposit) }}</strong></div>
                    </template>
                </aside>
            </div>

            <app-data-panel title="Productos de la compra" subtitle="Edite costo, cantidad, vencimiento, lote y descuento directamente en la tabla." eyebrow="Paso 2" flush>
                <template #actions>
                    <app-button
                        icon="icons/plus.svg"
                        :disabled="Number(datos.id_proveedor) === 0"
                        data-bs-toggle="modal"
                        data-bs-target="#modalArticulo"
                        @click="$emit('search-products')"
                    >
                        Agregar productos
                    </app-button>
                </template>
                <app-table
                    :columns="lineColumns"
                    :rows="details"
                    :loading="detailsLoading"
                    row-key="id_tienda_articulo"
                    min-width="1120px"
                    empty-title="Sin productos agregados"
                    empty-message="Seleccione un proveedor y agregue los productos de esta compra."
                >
                    <template #cell-actions="{ row }">
                        <button class="purchase-icon-button purchase-icon-button--danger" type="button" aria-label="Quitar producto" @click="$emit('remove-line', details.indexOf(row))">×</button>
                    </template>
                    <template #cell-articulo="{ row }"><strong>{{ row.articulo }}</strong><small class="purchase-cell-note">{{ row.presentacion || row.categoria }}</small></template>
                    <template #cell-costo_compra="{ row }"><input v-model.number="row.costo_compra" class="purchase-cell-input" type="number" min="0" step="0.01"></template>
                    <template #cell-cantidad="{ row }"><input v-model.number="row.cantidad" class="purchase-cell-input" type="number" min="0" step="1"></template>
                    <template #cell-fecha_vecimiento="{ row }"><input v-model="row.fecha_vecimiento" class="purchase-cell-input" type="date"></template>
                    <template #cell-lote="{ row }">
                        <input v-model="row.lote" class="purchase-cell-input" :class="{ 'is-invalid': row.loteDuplicado }" type="text" placeholder="Lote">
                        <small v-if="row.loteDuplicado" class="purchase-error">Lote repetido</small>
                    </template>
                    <template #cell-descuento="{ row }"><input v-model.number="row.descuento" class="purchase-cell-input" type="number" min="0" step="0.01"></template>
                    <template #cell-subtotal="{ row }"><strong>Bs {{ money(lineSubtotal(row)) }}</strong></template>
                </app-table>
            </app-data-panel>

            <div v-if="errors.length" class="purchase-errors" role="alert">
                <strong>Revise la información:</strong>
                <span v-for="error in errors" :key="error">{{ error }}</span>
            </div>

            <footer class="purchase-footer">
                <app-button variant="secondary" :disabled="isBusy" @click="$emit('cancel')">Limpiar</app-button>
                <app-button icon="icons/save.svg" :loading="isBusy" @click="$emit('save')">Guardar compra</app-button>
            </footer>
        </template>

        <template v-else-if="listado === 2">
            <div class="purchase-detail-grid">
                <app-metric-card label="Proveedor" :value="datos.proveedor || '—'" hint="Proveedor registrado" icon="icons/truck.svg" tone="green" />
                <app-metric-card label="Fecha" :value="datos.fecha || '—'" hint="Fecha de compra" icon="icons/calendar.svg" tone="cyan" />
                <app-metric-card label="Forma de pago" :value="datos.formaPago || '—'" hint="Condición registrada" icon="icons/credit-card.svg" tone="neutral" />
                <app-metric-card label="Total" :value="`Bs ${money(datos.total)}`" hint="Importe de la compra" icon="icons/money.svg" tone="blue" />
            </div>
            <app-data-panel title="Detalle de productos" :subtitle="datos.descripcion || 'Sin observaciones registradas.'" eyebrow="Comprobante" flush>
                <app-table :columns="detailColumns" :rows="details" :loading="detailsLoading" min-width="760px" empty-title="Sin productos" empty-message="Esta compra no tiene líneas disponibles.">
                    <template #cell-costo_compra="{ value }">Bs {{ money(value) }}</template>
                    <template #cell-sub_total="{ row }">Bs {{ money(row.sub_total || lineSubtotal(row)) }}</template>
                </app-table>
            </app-data-panel>
        </template>

        <div class="modal fade" id="modalArticulo" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content purchase-modal">
                    <div class="modal-header">
                        <div><small>Laboratorio: {{ selectedProviderName }}</small><h5>Agregar productos</h5></div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="purchase-search">
                            <select :value="productCriterion" @change="$emit('update:productCriterion', $event.target.value)">
                                <option value="articulo.nombre_comercial">Producto</option>
                                <option value="unidad_medida.nombre">Presentación</option>
                                <option value="proveedor.nombre">Laboratorio</option>
                                <option value="categoria.nombre">Categoría</option>
                            </select>
                            <app-input :value="productSearch" placeholder="Buscar en el catálogo…" @input="onProductSearchInput" @keyup.enter="$emit('search-products')" />
                            <app-button icon="icons/magnifying-glass.svg" @click="$emit('search-products')">Buscar</app-button>
                        </div>
                        <app-table
                            class="purchase-product-table"
                            :columns="productColumns"
                            :rows="products"
                            :loading="productsLoading"
                            min-width="850px"
                            fill-height
                            empty-title="Sin resultados"
                            empty-message="Cambie el término o criterio de búsqueda."
                        >
                            <template #cell-costo_compra="{ value }">Bs {{ money(value) }}</template>
                            <template #cell-action="{ row }"><app-button variant="secondary" @click="$emit('select-product', row)">Agregar</app-button></template>
                        </app-table>
                        <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('product-page', $event)" />
                    </div>
                    <div class="modal-footer"><app-button variant="secondary" data-bs-dismiss="modal">Cerrar</app-button></div>
                </div>
            </div>
        </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'PurchaseEntryWorkspace',
    props: {
        datos: { type: Object, required: true },
        datosPago: { type: Object, required: true },
        providers: { type: Array, default: () => [] },
        paymentTypes: { type: Array, default: () => [] },
        paymentForms: { type: Array, default: () => [] },
        details: { type: Array, default: () => [] },
        products: { type: Array, default: () => [] },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        productSearch: { type: String, default: '' },
        productCriterion: { type: String, default: 'articulo.nombre_comercial' },
        listado: { type: Number, default: 0 },
        calculatedTotal: { type: Number, default: 0 },
        errors: { type: Array, default: () => [] },
        isBusy: { type: Boolean, default: false },
        initialLoading: { type: Boolean, default: false },
        productsLoading: { type: Boolean, default: false },
        detailsLoading: { type: Boolean, default: false },
    },
    data() {
        return {
            lineColumns: [
                { key: 'actions', label: '' }, { key: 'articulo', label: 'Producto' }, { key: 'tienda', label: 'Tienda' },
                { key: 'costo_compra', label: 'Costo' }, { key: 'cantidad', label: 'Cantidad' },
                { key: 'fecha_vecimiento', label: 'Vencimiento' }, { key: 'lote', label: 'Lote' },
                { key: 'descuento', label: 'Descuento' }, { key: 'subtotal', label: 'Subtotal', className: 'text-right' },
            ],
            detailColumns: [
                { key: 'articulo', label: 'Producto' }, { key: 'tienda', label: 'Tienda' },
                { key: 'costo_compra', label: 'Costo', className: 'text-right' }, { key: 'cantidad', label: 'Cantidad' },
                { key: 'sub_total', label: 'Subtotal', className: 'text-right' },
            ],
            productColumns: [
                { key: 'categoria', label: 'Categoría' }, { key: 'articulo', label: 'Producto' },
                { key: 'presentacion', label: 'Presentación' }, { key: 'laboratorio', label: 'Laboratorio' },
                { key: 'costo_compra', label: 'Costo', className: 'text-right' }, { key: 'action', label: '' },
            ],
        };
    },
    computed: {
        grandTotal() {
            return Math.max(0, Number(this.calculatedTotal || 0) - Number(this.datos.descuento || 0));
        },
        mixedDeposit() {
            return Math.max(0, this.grandTotal - Number(this.datos.total_efectivo || 0));
        },
        selectedProviderName() {
            const provider = this.providers.find(item => String(item.id) === String(this.datos.id_proveedor));
            return provider ? provider.nombre : 'Sin proveedor';
        },
    },
    watch: {
        calculatedTotal: { immediate: true, handler(value) { this.syncTotals(value); } },
        'datos.descuento'() { this.syncTotals(this.calculatedTotal); },
        'datos.total_efectivo'() { this.datos.total_deposito = this.mixedDeposit; },
    },
    beforeDestroy() {
        clearTimeout(this.productSearchTimer);
    },
    methods: {
        lineSubtotal(row) {
            return Number(row.costo_compra || 0) * Number(row.cantidad || 0) - Number(row.descuento || 0);
        },
        syncTotals(value) {
            this.datos.sub_total = Number(value || 0).toFixed(2);
            this.datos.total = this.grandTotal;
            this.datos.total_deposito = this.mixedDeposit;
        },
        onProductSearchInput(value) {
            this.$emit('update:productSearch', value);
            clearTimeout(this.productSearchTimer);
            this.productSearchTimer = setTimeout(() => this.$emit('search-products'), 350);
        },
        money(value) {
            return Number(value || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
    },
};
</script>

<style scoped>
.purchase-page { display: grid; gap: 1rem; padding: 1.15rem; background: #f4f8f6; }
.purchase-page__content { display: contents; }
.purchase-layout { display: grid; grid-template-columns: minmax(0, 1fr) 250px; gap: .75rem; align-items: stretch; }
.purchase-form-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .55rem .7rem; }
.purchase-field { display: flex; flex-direction: column; gap: .25rem; color: #315044; font-size: .7rem; font-weight: 800; }
.purchase-field--wide { grid-column: span 2; }
.purchase-field--full { grid-column: 1 / -1; }
.purchase-field select, .purchase-search select { min-height: 36px; padding: .38rem .6rem; color: #17362b; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; outline: 0; }
.purchase-field select:focus, .purchase-search select:focus { border-color: #0e93b5; box-shadow: 0 0 0 3px rgba(62, 198, 224, .18); }
.purchase-summary { display: flex; flex-direction: column; gap: .35rem; padding: 1rem; color: #315044; background: linear-gradient(160deg, #effaf4, #fff); border: 1px solid #cfe0d8; border-top: 4px solid #3ec6e0; border-radius: 14px; box-shadow: 0 6px 18px rgba(23,54,43,.06); }
.purchase-summary > span { margin-bottom: .35rem; color: #0e93b5; font-size: .67rem; font-weight: 900; text-transform: uppercase; letter-spacing: .07em; }
.purchase-summary > div { display: flex; align-items: center; justify-content: space-between; gap: 1rem; min-height: 44px; padding: .55rem .2rem; border-bottom: 1px solid #dbe8e2; }
.purchase-summary small { font-weight: 700; }
.purchase-summary strong { color: #17362b; font-size: .94rem; }
.purchase-summary__discount input { width: 100px; padding: .38rem; text-align: right; border: 1px solid #bdd2c9; border-radius: 6px; }
.purchase-summary__total { margin-top: auto; color: #fff !important; background: #1f8a4c; border: 0 !important; border-radius: 8px; padding: .72rem !important; }
.purchase-summary__total strong { color: #fff; font-size: 1.05rem; }
.purchase-cell-input { width: 100%; min-width: 86px; padding: .4rem .48rem; color: #17362b; font-size: .76rem; background: #f2fbff; border: 1px solid #cce9ef; border-radius: 5px; }
.purchase-cell-input:focus { background: #fff; border-color: #0e93b5; outline: 2px solid rgba(62,198,224,.2); }
.purchase-cell-note { display: block; color: #6f817a; font-size: .66rem; }
.purchase-error { display: block; margin-top: .18rem; color: #d63c3c; font-size: .65rem; }
.purchase-icon-button { width: 28px; height: 28px; color: #fff; font-size: 1.1rem; line-height: 1; background: #d63c3c; border: 0; border-radius: 6px; }
.purchase-errors { display: flex; flex-wrap: wrap; gap: .3rem .8rem; padding: .75rem 1rem; color: #9e2929; font-size: .74rem; background: #fff0f0; border: 1px solid #f0caca; border-radius: 9px; }
.purchase-footer { position: sticky; bottom: 0; z-index: 2; display: flex; justify-content: flex-end; gap: .55rem; padding: .85rem 1rem; background: rgba(244,248,246,.94); border: 1px solid #d8e5df; border-radius: 12px; backdrop-filter: blur(8px); }
.purchase-detail-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 1rem; }
.purchase-modal .modal-header { color: #fff; background: linear-gradient(110deg, #173f32, #1f8a4c); border-bottom: 3px solid #3ec6e0; }
.purchase-modal .modal-header h5 { margin: .1rem 0 0; font-weight: 800; }
.purchase-modal .modal-header small { color: #71d5e8; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; }
.purchase-modal .modal-body { display: flex; flex-direction: column; min-height: 0; }
.purchase-search { flex: 0 0 auto; display: grid; grid-template-columns: 220px minmax(260px, 1fr) auto; gap: .55rem; margin-bottom: 1rem; }
.purchase-product-table { flex: 1; min-height: 0; margin-bottom: .75rem; }
.purchase-modal .modal-body > *:last-child { flex: 0 0 auto; }
@media (max-width: 1000px) { .purchase-layout { grid-template-columns: 1fr; } .purchase-form-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } .purchase-detail-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 650px) { .purchase-page { padding: .75rem; } .purchase-form-grid, .purchase-detail-grid, .purchase-search { grid-template-columns: 1fr; } .purchase-field--wide, .purchase-field--full { grid-column: auto; } .purchase-footer { flex-direction: column-reverse; } }
</style>

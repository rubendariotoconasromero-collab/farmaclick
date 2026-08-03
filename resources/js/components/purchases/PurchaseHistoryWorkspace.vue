<template>
    <section class="history-page">
        <app-page-skeleton v-if="initialLoading" />
        <div v-else class="history-page__content">
        <app-module-header
            eyebrow="Compras"
            :title="headerTitle"
            :subtitle="headerSubtitle"
        >
            <template v-if="listado !== 0" #actions>
                <app-button variant="ghost" icon="icons/arrow-left.svg" @click="$emit('back')">Volver al historial</app-button>
            </template>
        </app-module-header>

        <template v-if="listado === 0">
            <section class="history-overview">
                <app-metric-card label="Compras registradas" :value="pagination.total || rows.length" hint="Total según la consulta" icon="img/menu/compra.png" :loading="recordsLoading" />
                <app-metric-card label="Compras activas en página" :value="pendingCount" hint="Operaciones con estado Registrado" icon="icons/wallet.svg" tone="cyan" :loading="recordsLoading" />
                <app-metric-card label="Importe de la página" :value="`Bs ${money(pageAmount)}`" hint="Suma de los resultados visibles" icon="icons/money.svg" tone="neutral" :loading="recordsLoading" />
            </section>
            <app-data-panel
                title="Historial de compras"
                subtitle="Busque, revise, imprima o anule compras registradas."
                eyebrow="Consulta"
                flush
            >
                <div class="history-filter-panel">
                    <div class="history-filter-grid">
                        <div class="history-period">
                            <app-input :value="filters.fecha_desde" type="date" label="Desde" @input="updateFilter('fecha_desde', $event)" />
                            <app-input :value="filters.fecha_hasta" type="date" label="Hasta" @input="updateFilter('fecha_hasta', $event)" />
                        </div>
                        <app-live-search
                            :value="filters.proveedor_id"
                            :items="providers"
                            label="Proveedor"
                            track-by="id"
                            display-by="nombre"
                            placeholder="Todos los proveedores"
                            @input="updateFilter('proveedor_id', $event)"
                        />
                        <label class="history-filter-field">
                            <span>Estado</span>
                            <select :value="filters.estado" @change="updateFilter('estado', $event.target.value)">
                                <option value="">Todos los estados</option>
                                <option value="Registrado">Registrado</option>
                                <option value="Cancelado">Cancelado</option>
                                <option value="Anulado">Anulado</option>
                            </select>
                        </label>
                        <app-multi-select-dropdown
                            :value="filters.formas_pago"
                            :options="filterPaymentForms"
                            label="Formas de pago"
                            placeholder="Todas las formas"
                            menu-title="Filtrar por formas de pago"
                            track-by="id"
                            display-by="nombre"
                            @input="updateFilter('formas_pago', $event)"
                        />
                    </div>
                    <div class="history-filter-actions">
                        <span>{{ activeFilterCount ? `${activeFilterCount} filtros activos` : 'Sin filtros aplicados' }}</span>
                        <app-button v-if="activeFilterCount" variant="ghost" @click="$emit('clear-filters')">Limpiar</app-button>
                        <app-button icon="icons/magnifying-glass.svg" @click="$emit('apply-filters')">Aplicar filtros</app-button>
                    </div>
                    <div v-if="filterChips.length" class="history-filter-chips" aria-label="Filtros activos">
                        <button v-for="chip in filterChips" :key="chip.key" type="button" @click="$emit('remove-filter', chip.key)">
                            {{ chip.label }} <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>

                <app-table
                    :columns="historyColumns"
                    :rows="rows"
                    :loading="recordsLoading"
                    min-width="1120px"
                    empty-title="No hay compras"
                    empty-message="No se encontraron compras con el criterio seleccionado."
                >
                    <template #cell-fecha="{ value }"><span class="history-date">{{ value }}</span></template>
                    <template #cell-proveedor="{ row }"><strong>{{ row.proveedor }}</strong><small>{{ row.name || 'Sin usuario' }}</small></template>
                    <template #cell-total="{ value }"><strong>Bs {{ money(value) }}</strong></template>
                    <template #cell-estado="{ row }"><span class="history-badge" :class="statusClass(row)">{{ statusLabel(row) }}</span></template>
                    <template #cell-formaP="{ row }">{{ paymentLabel(row.formaP) }}</template>
                    <template #cell-actions="{ row }">
                        <div class="history-actions">
                            <app-icon-button icon="icons/eye.svg" label="Ver detalle" @click="$emit('view', row)" />
                            <app-icon-button v-if="row.estado === 'Registrado'" icon="icons/pencil.svg" label="Modificar" @click="$emit('edit', row)" />
                            <app-icon-button icon="icons/print.svg" label="Imprimir" @click="$emit('print', row)" />
                            <app-icon-button v-if="row.estado === 'Registrado'" variant="danger" icon="icons/ban.svg" label="Anular" @click="$emit('cancel', row)" />
                        </div>
                    </template>
                </app-table>
                <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
            </app-data-panel>
        </template>

        <template v-else-if="listado === 2">
            <div class="history-metrics">
                <app-metric-card label="Proveedor" :value="datos.proveedor || '—'" hint="Proveedor de la compra" icon="icons/truck.svg" tone="green" />
                <app-metric-card label="Total" :value="`Bs ${money(datos.total)}`" hint="Importe registrado" icon="icons/money.svg" tone="cyan" />
                <app-metric-card label="Forma de pago" :value="paymentLabel(datos.formaPago)" hint="Condición de pago" icon="icons/credit-card.svg" tone="neutral" />
            </div>
            <app-data-panel title="Información del comprobante" :subtitle="datos.descripcion || 'Sin observaciones.'" eyebrow="Detalle">
                <div class="history-detail-form">
                    <app-input :value="datos.proveedor" label="Proveedor" readonly />
                    <app-input v-model="datos.fecha" type="date" label="Fecha de compra" />
                    <app-input :value="paymentLabel(datos.formaPago)" label="Forma de pago" readonly />
                </div>
                <div class="history-inline-actions">
                    <app-button variant="secondary" icon="icons/print.svg" @click="$emit('print', datos)">Imprimir comprobante</app-button>
                    <app-button icon="icons/save.svg" :loading="isBusy" @click="$emit('save-date')">Guardar nueva fecha</app-button>
                </div>
            </app-data-panel>
            <app-data-panel title="Productos registrados" subtitle="Detalle de cantidades, costos y descuentos." eyebrow="Líneas" flush>
                <app-table :columns="detailColumns" :rows="activeDetails" :loading="detailsLoading" min-width="900px">
                    <template #cell-articulo="{ row }"><strong>{{ row.articulo }}</strong><small>{{ row.categoria }}</small></template>
                    <template #cell-costo_compra="{ value }">Bs {{ money(value) }}</template>
                    <template #cell-descuento="{ value }">Bs {{ money(value) }}</template>
                    <template #cell-sub_total="{ row }"><strong>Bs {{ money(row.sub_total || lineSubtotal(row)) }}</strong></template>
                </app-table>
            </app-data-panel>
        </template>

        <template v-else-if="listado === 3">
            <div class="history-edit-layout">
                <app-data-panel title="Datos de la compra" subtitle="Actualice la condición y los datos generales." eyebrow="Edición">
                    <div class="history-edit-form">
                        <label class="history-field">
                            <span>Proveedor</span>
                            <select v-model="datos.id_proveedor" disabled>
                                <option v-for="provider in providers" :key="provider.id" :value="provider.id">{{ provider.nombre }}</option>
                            </select>
                        </label>
                        <app-input v-model="datos.fecha" type="date" label="Fecha" />
                        <label class="history-field">
                            <span>Tipo de pago</span>
                            <select v-model="datos.id_tipo_pago" @change="$emit('payment-type-change')">
                                <option value="0" disabled>Seleccione el tipo</option>
                                <option v-for="type in paymentTypes" :key="type.id" :value="type.id">{{ type.nombre }}</option>
                            </select>
                        </label>
                        <app-input v-if="Number(datos.id_tipo_pago) === 2" v-model="datosPago.fecha_final" type="date" label="Vencimiento" />
                        <label v-if="Number(datos.id_tipo_pago) === 1" class="history-field">
                            <span>Forma de pago</span>
                            <select v-model="datos.id_forma_pago" @change="$emit('payment-form-change')">
                                <option v-for="form in paymentForms" :key="form.id" :value="form.id">{{ form.nombre }}</option>
                            </select>
                        </label>
                        <app-input v-model="datos.descripcion" class="history-field--full" label="Descripción" multiline :rows="2" />
                    </div>
                </app-data-panel>
                <aside class="history-total-card">
                    <span>Total actualizado</span>
                    <strong>Bs {{ money(grandTotal) }}</strong>
                    <small>{{ activeDetails.length }} productos activos</small>
                    <label>Descuento general<input v-model.number="datos.descuento" type="number" min="0" step="0.01"></label>
                    <template v-if="datos.formaPago === 'Mixta' || Number(datos.id_forma_pago) === 6">
                        <label>Efectivo<input v-model.number="datos.total_efectivo" type="number" min="0" step="0.01"></label>
                        <div><span>Depósito</span><b>Bs {{ money(mixedDeposit) }}</b></div>
                    </template>
                </aside>
            </div>

            <app-data-panel title="Productos de la compra" subtitle="La tabla funciona como una hoja editable de costos y existencias." eyebrow="Edición de líneas" flush>
                <template #actions>
                    <app-button icon="icons/plus.svg" data-bs-toggle="modal" data-bs-target="#modalArticuloHistorial" @click="$emit('search-products')">Agregar producto</app-button>
                </template>
                <app-table :columns="editColumns" :rows="activeDetails" :loading="detailsLoading" min-width="1240px" row-key="id_articulo">
                    <template #cell-actions="{ row }"><button class="history-remove" type="button" @click="$emit('remove-line', { index: details.indexOf(row), newItem: row.articulo_nuevo, articleId: row.id_articulo })">×</button></template>
                    <template #cell-articulo="{ row }"><strong>{{ row.articulo }}</strong><small>{{ row.categoria }}</small></template>
                    <template #cell-costo_compra="{ row }"><input v-model.number="row.costo_compra" type="number" min="0" step="0.01"></template>
                    <template #cell-cantidad="{ row }"><input v-model.number="row.cantidad" type="number" min="0" step="1"><small v-if="Number(row.cantidad) < Number(row.cantidad_auxiliar)" class="history-warning">Stock lote: {{ row.stock }}</small></template>
                    <template #cell-fecha_vecimiento="{ row }"><input v-model="row.fecha_vecimiento" type="date"></template>
                    <template #cell-lote="{ row }"><input v-model="row.lote" type="text"></template>
                    <template #cell-descuento="{ row }"><input v-model.number="row.descuento" type="number" min="0" step="0.01"></template>
                    <template #cell-subtotal="{ row }"><strong>Bs {{ money(lineSubtotal(row)) }}</strong></template>
                </app-table>
            </app-data-panel>
            <footer class="history-footer">
                <app-button variant="secondary" :disabled="isBusy" @click="$emit('back')">Cancelar</app-button>
                <app-button icon="icons/save.svg" :loading="isBusy" @click="$emit('save-edit')">Guardar modificaciones</app-button>
            </footer>
        </template>

        <div class="modal fade" id="modalArticuloHistorial" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content history-modal">
                    <div class="modal-header"><div><small>Catálogo</small><h5>Agregar productos a la compra</h5></div><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                    <div class="modal-body">
                        <div class="history-product-search">
                            <select :value="productCriterion" @change="$emit('update:productCriterion', $event.target.value)">
                                <option value="articulo.nombre_comercial">Producto</option>
                                <option value="unidad_medida.nombre">Presentación</option>
                                <option value="proveedor.nombre">Laboratorio</option>
                                <option value="categoria.nombre">Categoría</option>
                            </select>
                            <app-input :value="productSearch" placeholder="Buscar producto…" @input="$emit('update:productSearch', $event)" @keyup.enter="$emit('search-products')" />
                            <app-button icon="icons/magnifying-glass.svg" @click="$emit('search-products')">Buscar</app-button>
                        </div>
                        <app-table :columns="productColumns" :rows="products" :loading="productsLoading" min-width="820px">
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
    name: 'PurchaseHistoryWorkspace',
    props: {
        listado: { type: Number, default: 0 },
        rows: { type: Array, default: () => [] },
        details: { type: Array, default: () => [] },
        products: { type: Array, default: () => [] },
        providers: { type: Array, default: () => [] },
        paymentTypes: { type: Array, default: () => [] },
        paymentForms: { type: Array, default: () => [] },
        filterPaymentForms: { type: Array, default: () => [] },
        filters: {
            type: Object,
            default: () => ({ fecha_desde: '', fecha_hasta: '', proveedor_id: '', estado: '', formas_pago: [] }),
        },
        datos: { type: Object, required: true },
        datosPago: { type: Object, required: true },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        search: { type: String, default: '' },
        criterion: { type: String, default: 'proveedor.nombre' },
        productSearch: { type: String, default: '' },
        productCriterion: { type: String, default: 'articulo.nombre_comercial' },
        calculatedTotal: { type: Number, default: 0 },
        initialLoading: { type: Boolean, default: false },
        recordsLoading: { type: Boolean, default: false },
        detailsLoading: { type: Boolean, default: false },
        productsLoading: { type: Boolean, default: false },
        isBusy: { type: Boolean, default: false },
    },
    data() {
        return {
            historyColumns: [
                { key: 'fecha', label: 'Fecha' }, { key: 'proveedor', label: 'Proveedor / usuario' },
                { key: 'descripcion', label: 'Descripción' }, { key: 'total', label: 'Total', className: 'text-right' },
                { key: 'estado', label: 'Estado' }, { key: 'tipo', label: 'Tipo' },
                { key: 'formaP', label: 'Forma de pago' }, { key: 'actions', label: 'Acciones' },
            ],
            detailColumns: [
                { key: 'articulo', label: 'Producto' }, { key: 'tienda', label: 'Tienda' },
                { key: 'costo_compra', label: 'Costo' }, { key: 'cantidad', label: 'Cantidad' },
                { key: 'descuento', label: 'Descuento' }, { key: 'sub_total', label: 'Subtotal' },
            ],
            editColumns: [
                { key: 'actions', label: '' }, { key: 'articulo', label: 'Producto' }, { key: 'tienda', label: 'Tienda' },
                { key: 'costo_compra', label: 'Costo' }, { key: 'cantidad', label: 'Cantidad' },
                { key: 'fecha_vecimiento', label: 'Vencimiento' }, { key: 'lote', label: 'Lote' },
                { key: 'descuento', label: 'Descuento' }, { key: 'subtotal', label: 'Subtotal' },
            ],
            productColumns: [
                { key: 'categoria', label: 'Categoría' }, { key: 'articulo', label: 'Producto' },
                { key: 'presentacion', label: 'Presentación' }, { key: 'laboratorio', label: 'Laboratorio' },
                { key: 'costo_compra', label: 'Costo' }, { key: 'action', label: '' },
            ],
        };
    },
    computed: {
        activeDetails() {
            return this.details.filter(row => Number(row.eliminado || 0) === 0);
        },
        headerTitle() {
            if (this.listado === 2) return 'Detalle de compra';
            if (this.listado === 3) return 'Modificar compra';
            return 'Historial de compras';
        },
        headerSubtitle() {
            if (this.listado === 2) return 'Revise el comprobante y sus productos asociados.';
            if (this.listado === 3) return 'Actualice datos, productos, lotes y condición de pago.';
            return 'Consulte y gestione las compras registradas.';
        },
        grandTotal() {
            return Math.max(0, Number(this.calculatedTotal || 0) - Number(this.datos.descuento || 0));
        },
        mixedDeposit() {
            return Math.max(0, this.grandTotal - Number(this.datos.total_efectivo || 0));
        },
        pendingCount() {
            return this.rows.filter(row => this.statusLabel(row) !== 'Cancelado' && this.statusLabel(row) !== 'Anulado').length;
        },
        pageAmount() {
            return this.rows.reduce((total, row) => total + Number(row.total || 0), 0);
        },
        activeFilterCount() {
            return ['fecha_desde', 'fecha_hasta', 'proveedor_id', 'estado'].filter(key => Boolean(this.filters[key])).length
                + (this.filters.formas_pago && this.filters.formas_pago.length ? 1 : 0);
        },
        filterChips() {
            const chips = [];
            if (this.filters.fecha_desde || this.filters.fecha_hasta) {
                chips.push({ key: 'fechas', label: `Fecha: ${this.filters.fecha_desde || 'inicio'} a ${this.filters.fecha_hasta || 'hoy'}` });
            }
            if (this.filters.proveedor_id) {
                const provider = this.providers.find(item => String(item.id) === String(this.filters.proveedor_id));
                chips.push({ key: 'proveedor_id', label: `Proveedor: ${provider ? provider.nombre : this.filters.proveedor_id}` });
            }
            if (this.filters.estado) chips.push({ key: 'estado', label: `Estado: ${this.filters.estado}` });
            if (this.filters.formas_pago && this.filters.formas_pago.length) {
                const labels = this.filterPaymentForms
                    .filter(item => this.filters.formas_pago.some(id => String(id) === String(item.id)))
                    .map(item => item.nombre);
                chips.push({ key: 'formas_pago', label: `Pago: ${labels.join(', ')}` });
            }
            return chips;
        },
    },
    watch: {
        grandTotal: { immediate: true, handler(value) { this.datos.total = Number(value).toFixed(2); this.datos.sub_total = Number(this.calculatedTotal || 0).toFixed(2); this.datos.total_deposito = this.mixedDeposit; } },
        mixedDeposit(value) { this.datos.total_deposito = value; },
    },
    methods: {
        updateFilter(key, value) { this.$emit('update-filter', { key, value }); },
        lineSubtotal(row) { return Number(row.costo_compra || 0) * Number(row.cantidad || 0) - Number(row.descuento || 0); },
        money(value) { return Number(value || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); },
        paymentLabel(value) { return value === 'Cuenta por Cobrar' ? 'Cuenta por pagar' : (value || '—'); },
        statusLabel(row) { return row.estado; },
        statusClass(row) { const value = this.statusLabel(row); return value === 'Anulado' ? 'is-cancelled' : value === 'Cancelado' ? 'is-paid' : 'is-open'; },
    },
};
</script>

<style scoped>
.history-page { display: grid; gap: 1rem; padding: 1.15rem; background: #f4f8f6; }
.history-page__content { display: contents; }
.history-overview { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
.history-filter-panel { padding: 1rem; background: #f8fbf9; border-bottom: 1px solid #d8e5df; }
.history-filter-grid { display: grid; grid-template-columns: minmax(280px, 1.15fr) minmax(220px, 1fr) minmax(170px, .65fr) minmax(220px, .9fr); gap: .7rem; align-items: end; }
.history-period { display: grid; grid-template-columns: 1fr 1fr; gap: .55rem; }
.history-filter-field, .history-field { display: flex; flex-direction: column; gap: .35rem; color: #315044; font-size: .72rem; font-weight: 800; }
.history-filter-field select, .history-field select, .history-product-search select { min-height: 40px; padding: .48rem .65rem; color: #17362b; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; }
.history-filter-actions { display: flex; justify-content: flex-end; align-items: center; gap: .45rem; margin-top: .75rem; }
.history-filter-actions > span { margin-right: auto; color: #6f817a; font-size: .68rem; font-weight: 700; }
.history-filter-chips { display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .65rem; padding-top: .65rem; border-top: 1px dashed #cbdcd5; }
.history-filter-chips button { padding: .3rem .5rem; color: #17693c; background: #e5f5eb; border: 1px solid #c2e4cf; border-radius: 999px; font-size: .67rem; font-weight: 800; cursor: pointer; }
.history-filter-chips button:hover { color: #a52b2b; background: #fff0f0; border-color: #efcaca; }
.history-date { white-space: nowrap; }
.history-page small { display: block; color: #6f817a; font-size: .66rem; }
.history-badge { display: inline-flex; padding: .25rem .48rem; font-size: .66rem; font-weight: 900; border-radius: 999px; }
.history-badge.is-open { color: #0b718b; background: #e3f8fc; }
.history-badge.is-paid { color: #17693c; background: #dff4e7; }
.history-badge.is-cancelled { color: #a52b2b; background: #fff0f0; }
.history-actions { display: flex; gap: .25rem; }
.history-metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
.history-detail-form { display: grid; grid-template-columns: repeat(3, 1fr); gap: .8rem; }
.history-inline-actions { display: flex; justify-content: flex-end; gap: .5rem; margin-top: 1rem; }
.history-edit-layout { display: grid; grid-template-columns: minmax(0, 1fr) 270px; gap: 1rem; }
.history-edit-form { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: .8rem; }
.history-field--full { grid-column: 1 / -1; }
.history-total-card { display: flex; flex-direction: column; gap: .6rem; padding: 1rem; color: #315044; background: linear-gradient(150deg, #173f32, #1f8a4c); border-radius: 14px; box-shadow: 0 8px 22px rgba(23,54,43,.14); }
.history-total-card > span, .history-total-card > small { color: #bfe1d2; font-size: .7rem; font-weight: 800; text-transform: uppercase; }
.history-total-card > strong { color: #fff; font-size: 1.55rem; }
.history-total-card label { display: flex; flex-direction: column; gap: .28rem; color: #d8eee5; font-size: .7rem; font-weight: 800; }
.history-total-card input { min-height: 37px; padding: .4rem .55rem; color: #17362b; background: #fff; border: 0; border-radius: 6px; }
.history-total-card > div { display: flex; justify-content: space-between; color: #fff; }
.history-page ::v-deep .history-edit-layout + .app-data-panel input { width: 100%; min-width: 88px; padding: .4rem; font-size: .75rem; background: #f2fbff; border: 1px solid #cce9ef; border-radius: 5px; }
.history-remove { width: 28px; height: 28px; color: #fff; font-size: 1.1rem; background: #d63c3c; border: 0; border-radius: 6px; }
.history-warning { color: #b87900 !important; }
.history-footer { position: sticky; bottom: 0; z-index: 2; display: flex; justify-content: flex-end; gap: .5rem; padding: .85rem 1rem; background: rgba(244,248,246,.94); border: 1px solid #d8e5df; border-radius: 12px; backdrop-filter: blur(8px); }
.history-modal .modal-header { color: #fff; background: linear-gradient(110deg, #173f32, #1f8a4c); border-bottom: 3px solid #3ec6e0; }
.history-modal h5 { margin: .1rem 0 0; font-weight: 800; }
.history-modal .modal-header small { color: #71d5e8; font-weight: 800; text-transform: uppercase; }
.history-product-search { display: grid; grid-template-columns: 220px 1fr auto; gap: .55rem; margin-bottom: 1rem; }
@media (max-width: 1100px) { .history-filter-grid { grid-template-columns: 1fr 1fr; } }
@media (max-width: 900px) { .history-edit-layout { grid-template-columns: 1fr; } .history-metrics, .history-overview { grid-template-columns: 1fr; } }
@media (max-width: 650px) { .history-page { padding: .75rem; } .history-filter-grid, .history-period, .history-detail-form, .history-edit-form, .history-product-search { grid-template-columns: 1fr; } .history-filter-actions { flex-wrap: wrap; } .history-filter-actions > span { width: 100%; } .history-field--full { grid-column: auto; } .history-footer { flex-direction: column-reverse; } }
</style>

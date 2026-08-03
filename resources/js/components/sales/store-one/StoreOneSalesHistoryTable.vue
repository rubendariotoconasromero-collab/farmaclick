<template>
    <app-data-panel
        eyebrow="Consulta"
        title="Ventas registradas"
        subtitle="Filtre por período, cliente, estado o forma de pago y administre cada comprobante."
        flush
    >
        <div class="history-filter-panel">
            <div class="history-filter-grid">
                <div class="history-period">
                    <app-input :value="filters.fecha_desde" type="date" label="Desde" @input="updateFilter('fecha_desde', $event)" />
                    <app-input :value="filters.fecha_hasta" type="date" label="Hasta" @input="updateFilter('fecha_hasta', $event)" />
                </div>
                <app-live-search
                    :value="filters.cliente_id"
                    :items="customers"
                    :loading="customerLoading"
                    label="Cliente"
                    track-by="id"
                    display-by="nombre"
                    placeholder="Todos los clientes"
                    @search="$emit('search-customer', $event)"
                    @input="updateFilter('cliente_id', $event)"
                />
                <label class="history-filter-field">
                    <span>Estado</span>
                    <select :value="filters.estado" @change="updateFilter('estado', $event.target.value)">
                        <option value="">Estados activos</option>
                        <option value="Entregado">Entregada</option>
                        <option value="Devolucion">Con devolución</option>
                        <option value="Anulado">Anulada</option>
                    </select>
                </label>
                <app-multi-select-dropdown
                    :value="filters.formas_pago"
                    :options="paymentForms"
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
                <app-button variant="secondary" icon="icons/reload.svg" :disabled="loading" @click="$emit('refresh')">Actualizar</app-button>
                <app-button icon="icons/magnifying-glass.svg" :disabled="loading" @click="$emit('apply-filters')">Aplicar filtros</app-button>
            </div>
            <div v-if="filterChips.length" class="history-filter-chips" aria-label="Filtros activos">
                <button v-for="chip in filterChips" :key="chip.key" type="button" @click="$emit('remove-filter', chip.key)">
                    {{ chip.label }} <span aria-hidden="true">×</span>
                </button>
            </div>
        </div>

        <app-table
            :columns="columns"
            :rows="rows"
            :loading="loading"
            row-key="id"
            min-width="990px"
            caption="Historial de ventas de Tienda Primera"
            empty-title="No hay ventas registradas"
            empty-message="Pruebe con otro nombre de cliente o actualice la consulta."
        >
            <template #cell-descuento="{ value }">{{ money(value) }}</template>
            <template #cell-total="{ value }"><strong class="history-total">{{ money(value) }}</strong></template>
            <template #cell-estado="{ value }">
                <span class="history-status" :class="statusClass(value)">{{ statusLabel(value) }}</span>
            </template>
            <template #cell-pago="{ row }">
                <span class="history-payment">{{ row.tipoP || 'Sin tipo' }}</span>
                <small>{{ row.formaP || 'Sin forma registrada' }}</small>
            </template>
            <template #cell-actions="{ row }">
                <div class="history-actions">
                    <app-button variant="secondary" icon="icons/eye.svg" title="Ver detalle" @click="$emit('view', row)">
                        Detalle
                    </app-button>
                    <app-button
                        v-if="canReturn(row)"
                        variant="secondary"
                        icon="icons/action-undo.svg"
                        title="Registrar devolución"
                        @click="$emit('return', row)"
                    >
                        Devolución
                    </app-button>
                    <app-button
                        v-if="canVoid(row)"
                        variant="danger"
                        icon="icons/lock-locked.svg"
                        title="Anular venta"
                        :loading="voidingIds.includes(row.id)"
                        :disabled="voidingIds.includes(row.id)"
                        @click="$emit('void', row)"
                    >
                        Anular
                    </app-button>
                </div>
            </template>
        </app-table>

        <purchase-pagination
            :pagination="pagination"
            :pages="pages"
            @change="$emit('page', $event)"
        />
    </app-data-panel>
</template>

<script>
export default {
    name: 'StoreOneSalesHistoryTable',
    props: {
        rows: { type: Array, default: () => [] },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        filters: { type: Object, required: true },
        customers: { type: Array, default: () => [] },
        paymentForms: { type: Array, default: () => [] },
        customerLoading: { type: Boolean, default: false },
        loading: { type: Boolean, default: false },
        isAdministrator: { type: Boolean, default: false },
        voidingIds: { type: Array, default: () => [] },
    },
    data() {
        return {
            columns: [
                { key: 'fecha', label: 'Fecha' },
                { key: 'cliente', label: 'Cliente' },
                { key: 'descuento', label: 'Descuento' },
                { key: 'total', label: 'Total' },
                { key: 'estado', label: 'Estado' },
                { key: 'pago', label: 'Condición de pago' },
                { key: 'actions', label: 'Acciones' },
            ],
        };
    },
    computed: {
        activeFilterCount() {
            return ['fecha_desde', 'fecha_hasta', 'cliente_id', 'estado'].filter(key => Boolean(this.filters[key])).length
                + (this.filters.formas_pago && this.filters.formas_pago.length ? 1 : 0);
        },
        filterChips() {
            const chips = [];
            if (this.filters.fecha_desde || this.filters.fecha_hasta) {
                chips.push({ key: 'fechas', label: `Fecha: ${this.filters.fecha_desde || 'inicio'} a ${this.filters.fecha_hasta || 'hoy'}` });
            }
            if (this.filters.cliente_id) {
                const customer = this.customers.find(item => String(item.id) === String(this.filters.cliente_id));
                chips.push({ key: 'cliente_id', label: `Cliente: ${customer ? customer.nombre : `#${this.filters.cliente_id}`}` });
            }
            if (this.filters.estado) chips.push({ key: 'estado', label: `Estado: ${this.statusLabel(this.filters.estado)}` });
            if (this.filters.formas_pago && this.filters.formas_pago.length) {
                const labels = this.paymentForms
                    .filter(item => this.filters.formas_pago.some(id => String(id) === String(item.id)))
                    .map(item => item.nombre);
                chips.push({ key: 'formas_pago', label: `Pago: ${labels.join(', ')}` });
            }
            return chips;
        },
    },
    methods: {
        updateFilter(key, value) { this.$emit('update-filter', { key, value }); },
        money(value) {
            return `${Number(value || 0).toFixed(2)} Bs`;
        },
        statusLabel(value) {
            return String(value || 'Sin estado').replace('Devolucion', 'Devolución');
        },
        statusClass(value) {
            const status = String(value || '').toLowerCase();
            if (status === 'anulado') return 'history-status--danger';
            if (status === 'entregado') return 'history-status--success';
            if (status === 'devolucion') return 'history-status--warning';
            return 'history-status--info';
        },
        canReturn(row) {
            return ['Entregado', 'Devolucion'].includes(row.estado);
        },
        canVoid(row) {
            return this.isAdministrator && ['Entregado', 'Devolucion'].includes(row.estado);
        },
    },
};
</script>

<style scoped>
.history-filter-panel { padding: 1rem; background: var(--system-soft-bg, #f8fbf9); border-bottom: 1px solid var(--system-border-color, #d8e5df); }
.history-filter-grid { display: grid; grid-template-columns: 1.25fr 1fr .75fr 1fr; gap: .7rem; align-items: end; }
.history-period { display: grid; grid-template-columns: 1fr 1fr; gap: .45rem; }
.history-filter-field { display: grid; gap: .35rem; margin: 0; }
.history-filter-field > span { color: #315044; font-size: .73rem; font-weight: 800; }
.history-filter-field select { width: 100%; min-height: 40px; padding: .48rem .65rem; color: #17362b; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; }
.history-filter-actions { display: flex; justify-content: flex-end; align-items: center; gap: .55rem; margin-top: .8rem; }
.history-filter-actions > span { margin-right: auto; color: var(--system-text-muted, #6f817a); font-size: .7rem; font-weight: 700; }
.history-filter-chips { display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .65rem; }
.history-filter-chips button { padding: .3rem .55rem; color: #17693c; background: #e3f5eb; border: 1px solid #c7e6d3; border-radius: 999px; font-size: .68rem; font-weight: 800; cursor: pointer; }
.history-filter-chips button span { margin-left: .25rem; }
.history-total { color: var(--fc-green-700, #1f6b45); white-space: nowrap; }
.history-payment { display: block; color: var(--fc-ink, #17362b); font-weight: 800; }
.history-payment + small { display: block; margin-top: .15rem; color: var(--system-text-muted, #6f817a); font-size: .68rem; }
.history-status { display: inline-flex; min-width: 82px; justify-content: center; padding: .3rem .55rem; color: #08758f; font-size: .68rem; font-weight: 900; background: #e1f5fa; border-radius: 999px; }
.history-status--success { color: #17693c; background: #e3f5eb; }
.history-status--warning { color: #8a6213; background: #fff3d2; }
.history-status--danger { color: #a72f36; background: #fde8e9; }
.history-actions { display: flex; flex-wrap: wrap; gap: .35rem; }
@media (max-width: 720px) {
    .history-filter-grid { grid-template-columns: 1fr; }
    .history-filter-actions { flex-wrap: wrap; justify-content: stretch; }
    .history-filter-actions > span { width: 100%; }
}
@media (max-width: 1200px) and (min-width: 721px) { .history-filter-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
</style>

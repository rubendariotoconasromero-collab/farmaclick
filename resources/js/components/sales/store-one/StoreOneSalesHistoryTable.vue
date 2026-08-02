<template>
    <app-data-panel
        eyebrow="Consulta"
        title="Ventas registradas"
        subtitle="Busque por cliente y administre cada comprobante desde una sola vista."
        flush
    >
        <div class="history-toolbar">
            <label class="history-toolbar__field">
                <span>Buscar cliente</span>
                <app-input
                    :value="search"
                    placeholder="Nombre del cliente..."
                    @input="$emit('update:search', $event)"
                    @keyup.enter.native="$emit('search')"
                />
            </label>
            <app-button
                variant="secondary"
                icon="icons/reload.svg"
                :disabled="loading"
                @click="$emit('refresh')"
            >
                Actualizar
            </app-button>
            <app-button
                icon="icons/magnifying-glass.svg"
                :disabled="loading"
                @click="$emit('search')"
            >
                Buscar
            </app-button>
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
                    <button type="button" title="Ver detalle" @click="$emit('view', row)">
                        <img :src="icon('eye.svg')" alt=""><span>Detalle</span>
                    </button>
                    <button
                        v-if="canReturn(row)"
                        type="button"
                        class="history-actions__return"
                        title="Registrar devolución"
                        @click="$emit('return', row)"
                    >
                        <img :src="icon('action-undo.svg')" alt=""><span>Devolución</span>
                    </button>
                    <button
                        v-if="canVoid(row)"
                        type="button"
                        class="history-actions__danger"
                        title="Anular venta"
                        @click="$emit('void', row)"
                    >
                        <img :src="icon('lock-locked.svg')" alt=""><span>Anular</span>
                    </button>
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
        search: { type: String, default: '' },
        loading: { type: Boolean, default: false },
        isAdministrator: { type: Boolean, default: false },
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
    methods: {
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
        icon(name) {
            const mainIndex = window.location.pathname.indexOf('/main');
            const base = mainIndex >= 0 ? window.location.pathname.substring(0, mainIndex) : '';
            return `${base}/icons/${name}`;
        },
    },
};
</script>

<style scoped>
.history-toolbar {
    display: grid;
    grid-template-columns: minmax(260px, 1fr) auto auto;
    gap: .65rem;
    align-items: end;
    padding: 1rem;
    background: var(--system-soft-bg, #f8fbf9);
    border-bottom: 1px solid var(--system-border-color, #d8e5df);
}
.history-toolbar__field { display: grid; gap: .35rem; margin: 0; }
.history-toolbar__field > span { color: var(--system-text-muted, #5f716a); font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; }
.history-total { color: var(--fc-green-700, #1f6b45); white-space: nowrap; }
.history-payment { display: block; color: var(--fc-ink, #17362b); font-weight: 800; }
.history-payment + small { display: block; margin-top: .15rem; color: var(--system-text-muted, #6f817a); font-size: .68rem; }
.history-status { display: inline-flex; min-width: 82px; justify-content: center; padding: .3rem .55rem; color: #08758f; font-size: .68rem; font-weight: 900; background: #e1f5fa; border-radius: 999px; }
.history-status--success { color: #17693c; background: #e3f5eb; }
.history-status--warning { color: #8a6213; background: #fff3d2; }
.history-status--danger { color: #a72f36; background: #fde8e9; }
.history-actions { display: flex; flex-wrap: wrap; gap: .35rem; }
.history-actions button { display: inline-flex; align-items: center; gap: .3rem; min-height: 31px; padding: .3rem .48rem; color: #17693c; font-size: .68rem; font-weight: 800; background: #effaf4; border: 1px solid #c9e5d5; border-radius: 7px; }
.history-actions button:hover { background: #e1f4e9; }
.history-actions img { width: 14px; height: 14px; filter: invert(37%) sepia(20%) saturate(1290%) hue-rotate(98deg); }
.history-actions .history-actions__return { color: #08758f; background: #ecf9fc; border-color: #c2e6ee; }
.history-actions .history-actions__return img { filter: invert(43%) sepia(73%) saturate(950%) hue-rotate(153deg); }
.history-actions .history-actions__danger { color: #a72f36; background: #fff3f3; border-color: #f0cece; }
.history-actions .history-actions__danger img { filter: invert(29%) sepia(72%) saturate(1248%) hue-rotate(324deg); }
@media (max-width: 720px) {
    .history-toolbar { grid-template-columns: 1fr 1fr; }
    .history-toolbar__field { grid-column: 1 / -1; }
    .history-actions button span { display: none; }
}
</style>

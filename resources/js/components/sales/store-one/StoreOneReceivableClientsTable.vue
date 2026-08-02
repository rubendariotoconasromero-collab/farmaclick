<template>
    <app-data-panel
        eyebrow="Cuentas por cobrar"
        title="Clientes con saldo pendiente"
        subtitle="Seleccione un cliente para revisar sus ventas a crédito y registrar abonos."
        flush
    >
        <div class="receivable-toolbar">
            <label>
                <span>Buscar cliente</span>
                <app-input
                    :value="search"
                    placeholder="Nombre del cliente..."
                    @input="$emit('update:search', $event)"
                    @keyup.enter.native="$emit('search')"
                />
            </label>
            <app-button variant="secondary" icon="icons/reload.svg" :disabled="loading" @click="$emit('refresh')">
                Actualizar
            </app-button>
            <app-button icon="icons/magnifying-glass.svg" :disabled="loading" @click="$emit('search')">
                Buscar
            </app-button>
        </div>

        <app-table
            :columns="columns"
            :rows="rows"
            :loading="loading"
            row-key="id"
            min-width="760px"
            caption="Clientes con cuentas por cobrar"
            empty-title="No hay clientes con deuda"
            empty-message="No se encontraron saldos pendientes para el filtro actual."
        >
            <template #cell-cliente="{ value }"><strong>{{ value || 'Sin nombre' }}</strong></template>
            <template #cell-saldo_cliente="{ value }"><strong class="receivable-debt">{{ money(value) }}</strong></template>
            <template #cell-actions="{ row }">
                <app-button variant="secondary" icon="icons/eye.svg" @click="$emit('select', row)">
                    Ver créditos
                </app-button>
            </template>
        </app-table>

        <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
    </app-data-panel>
</template>

<script>
export default {
    name: 'StoreOneReceivableClientsTable',
    props: {
        rows: { type: Array, default: () => [] },
        search: { type: String, default: '' },
        loading: { type: Boolean, default: false },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
    },
    data() {
        return {
            columns: [
                { key: 'matricula', label: 'NIT / CI' },
                { key: 'cliente', label: 'Cliente' },
                { key: 'telefono', label: 'Celular' },
                { key: 'saldo_cliente', label: 'Saldo pendiente' },
                { key: 'actions', label: 'Acción' },
            ],
        };
    },
    methods: {
        money(value) {
            return `${Number(value || 0).toFixed(2)} Bs`;
        },
    },
};
</script>

<style scoped>
.receivable-toolbar { display: grid; grid-template-columns: minmax(260px, 1fr) auto auto; gap: .65rem; align-items: end; padding: 1rem; background: var(--system-soft-bg, #f8fbf9); border-bottom: 1px solid var(--system-border-color, #d8e5df); }
.receivable-toolbar label { display: grid; gap: .35rem; margin: 0; }
.receivable-toolbar label > span { color: var(--system-text-muted, #5f716a); font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .05em; }
.receivable-debt { color: #a76613; white-space: nowrap; }
@media (max-width: 720px) {
    .receivable-toolbar { grid-template-columns: 1fr 1fr; }
    .receivable-toolbar label { grid-column: 1 / -1; }
}
</style>

<template>
    <section class="sales-history">
        <app-module-header
            eyebrow="Ventas"
            :title="listado === 2 ? 'Detalle de venta' : 'Historial de ventas'"
            :subtitle="listado === 2 ? 'Información del comprobante y productos vendidos.' : 'Consulte las ventas directas y sus comprobantes.'"
        >
            <template #actions>
                <app-button v-if="listado === 2" variant="ghost" icon="icons/arrow-left.svg" @click="$emit('back')">Volver</app-button>
                <app-button v-if="listado === 2" icon="icons/print.svg" @click="$emit('print')">Imprimir</app-button>
            </template>
        </app-module-header>

        <template v-if="listado !== 2">
            <div class="metrics">
                <app-metric-card label="Registros encontrados" :value="pagination.total || rows.length" hint="Ventas directas" icon="icons/cart.svg" tone="green" />
                <app-metric-card label="Página actual" :value="`${pagination.current_page || 1} / ${pagination.last_page || 1}`" hint="Navegación del historial" icon="icons/book.svg" tone="cyan" />
                <app-metric-card label="Total visible" :value="money(visibleTotal)" hint="Suma de la página actual" icon="icons/money.svg" tone="blue" />
            </div>

            <app-data-panel title="Ventas registradas" subtitle="Busque por cliente, tienda o usuario." eyebrow="Consulta" flush>
                <div class="toolbar">
                    <select :value="criterion" class="form-select" @change="$emit('update:criterion', $event.target.value)">
                        <option value="cliente.nombre">Cliente</option>
                        <option value="tienda.nombre">Tienda</option>
                        <option value="users.name">Usuario</option>
                    </select>
                    <app-input
                        :value="search"
                        placeholder="Escriba para buscar..."
                        @input="$emit('update:search', $event)"
                        @keyup.enter.native="$emit('search')"
                    />
                    <app-button icon="icons/magnifying-glass.svg" @click="$emit('search')">Buscar</app-button>
                </div>
                <app-table :columns="columns" :rows="rows" row-key="id" min-width="1040px" empty-message="No existen ventas para los filtros seleccionados.">
                    <template #cell-total="{ value }"><strong>{{ money(value) }}</strong></template>
                    <template #cell-estado="{ value }"><span class="status" :class="statusClass(value)">{{ value || 'Sin estado' }}</span></template>
                    <template #cell-actions="{ row }">
                        <app-button variant="secondary" icon="icons/eye.svg" @click="$emit('view', row)">Ver detalle</app-button>
                    </template>
                </app-table>
                <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
            </app-data-panel>
        </template>

        <template v-else>
            <div class="metrics metrics--detail">
                <app-metric-card label="Cliente" :value="datos.cliente || 'Sin cliente'" hint="Titular del comprobante" icon="icons/people.svg" tone="green" />
                <app-metric-card label="Fecha" :value="datos.fecha || '—'" hint="Fecha de emisión" icon="icons/calendar.svg" tone="cyan" />
                <app-metric-card label="Total" :value="money(datos.total)" :hint="datos.tipoPago || 'Condición no registrada'" icon="icons/money.svg" tone="blue" />
            </div>
            <app-data-panel title="Productos vendidos" subtitle="Detalle económico de la venta." eyebrow="Comprobante" flush>
                <app-table :columns="detailColumns" :rows="details" row-key="id" min-width="820px">
                    <template #cell-precio="{ row }">{{ money(detailPrice(row)) }}</template>
                    <template #cell-sub_total="{ value }"><strong>{{ money(value) }}</strong></template>
                </app-table>
                <div class="totals">
                    <span>Subtotal <strong>{{ money(datos.sub_total) }}</strong></span>
                    <span>Descuento <strong>{{ money(datos.descuento) }}</strong></span>
                    <span class="totals__grand">Total <strong>{{ money(datos.total) }}</strong></span>
                </div>
            </app-data-panel>
        </template>
    </section>
</template>

<script>
export default {
    name: 'SalesHistoryWorkspace',
    props: {
        rows: { type: Array, default: () => [] },
        details: { type: Array, default: () => [] },
        datos: { type: Object, required: true },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        listado: { type: Number, default: 0 },
        search: { type: String, default: '' },
        criterion: { type: String, default: 'cliente.nombre' },
    },
    data() {
        return {
            columns: [
                { key: 'fecha', label: 'Fecha' }, { key: 'cliente', label: 'Cliente' },
                { key: 'tienda', label: 'Tienda' }, { key: 'descuento', label: 'Descuento' },
                { key: 'total', label: 'Total' }, { key: 'name', label: 'Usuario' },
                { key: 'estado', label: 'Estado' }, { key: 'tipoP', label: 'Pago' },
                { key: 'actions', label: 'Acciones' },
            ],
            detailColumns: [
                { key: 'articulo', label: 'Producto' }, { key: 'tienda', label: 'Tienda' },
                { key: 'precio', label: 'Precio' }, { key: 'cantidad', label: 'Cantidad' },
                { key: 'sub_total', label: 'Subtotal' },
            ],
        };
    },
    computed: {
        visibleTotal() {
            return this.rows.reduce((sum, row) => sum + Number(row.total || 0), 0);
        },
    },
    methods: {
        money(value) {
            return `${Number(value || 0).toFixed(2)} Bs`;
        },
        detailPrice(row) {
            return row.costo_venta || row.costo_unitario || row.costo_mayorista || row.costo_preferencial || 0;
        },
        statusClass(value) {
            const status = String(value || '').toLowerCase();
            if (status === 'anulado') return 'status--danger';
            if (status === 'entregado') return 'status--success';
            if (status === 'concluido') return 'status--warning';
            return 'status--info';
        },
    },
};
</script>

<style scoped>
.sales-history { display: grid; gap: 1rem; padding: 1.25rem; }
.metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .85rem; }
.toolbar { display: grid; grid-template-columns: minmax(150px, 220px) minmax(240px, 1fr) auto; gap: .65rem; padding: 1rem; border-bottom: 1px solid #d8e5df; background: #f8fbf9; }
.status { display: inline-flex; padding: .3rem .55rem; color: #17693c; font-size: .68rem; font-weight: 800; background: #e3f5eb; border-radius: 999px; }
.status--danger { color: #a72f36; background: #fde8e9; }
.status--warning { color: #8a6213; background: #fff3d2; }
.status--info { color: #08758f; background: #e1f5fa; }
.totals { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 1.25rem; padding: 1rem; color: #667a72; }
.totals span { display: flex; gap: .5rem; }
.totals__grand { color: #17693c; font-size: 1rem; }
@media (max-width: 900px) { .metrics { grid-template-columns: 1fr; } }
@media (max-width: 700px) { .sales-history { padding: .8rem; } .toolbar { grid-template-columns: 1fr; } }
</style>

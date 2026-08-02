<template>
    <section class="sales-payments">
        <app-module-header
            eyebrow="Ventas"
            :title="listado === 2 ? 'Detalle de venta' : 'Pagos de ventas'"
            :subtitle="listado === 2 ? 'Revise los productos del comprobante.' : 'Controle saldos y registre amortizaciones de ventas a crédito.'"
        >
            <template v-if="listado === 2" #actions>
                <app-button variant="ghost" icon="icons/arrow-left.svg" @click="$emit('back')">Volver</app-button>
                <app-button icon="icons/print.svg" @click="$emit('print')">Imprimir</app-button>
            </template>
        </app-module-header>

        <template v-if="listado !== 2">
            <div class="metrics">
                <app-metric-card label="Ventas a crédito" :value="pagination.total || rows.length" hint="Registros encontrados" icon="icons/credit-card.svg" tone="green" />
                <app-metric-card label="Cartera visible" :value="money(visibleTotal)" hint="Total de la página" icon="icons/money.svg" tone="cyan" />
                <app-metric-card label="Página actual" :value="`${pagination.current_page || 1} / ${pagination.last_page || 1}`" hint="Navegación de cartera" icon="icons/book.svg" tone="blue" />
            </div>
            <app-data-panel title="Cuentas por cobrar" subtitle="Seleccione una venta a crédito para revisar o registrar pagos." eyebrow="Cartera" flush>
                <div class="toolbar">
                    <select :value="criterion" class="form-select" @change="$emit('update:criterion', $event.target.value)">
                        <option value="cliente.nombre">Cliente</option>
                        <option value="tienda.nombre">Tienda</option>
                        <option value="users.name">Usuario</option>
                    </select>
                    <app-input :value="search" placeholder="Buscar venta..." @input="$emit('update:search', $event)" @keyup.enter.native="$emit('search')" />
                    <app-button icon="icons/magnifying-glass.svg" @click="$emit('search')">Buscar</app-button>
                </div>
                <app-table :columns="columns" :rows="rows" row-key="id" min-width="1020px" empty-message="No se encontraron ventas pendientes para los filtros seleccionados.">
                    <template #cell-total="{ value }"><strong>{{ money(value) }}</strong></template>
                    <template #cell-estado="{ value }"><span class="status" :class="{ 'status--paid': value === 'Entregado' }">{{ value || 'Pendiente' }}</span></template>
                    <template #cell-payment="{ row }">
                        <app-button
                            variant="secondary"
                            icon="icons/money.svg"
                            data-bs-toggle="modal"
                            data-bs-target="#salesPaymentModal"
                            :disabled="row.tipoP === 'Contado'"
                            @click="$emit('pay', row)"
                        >Registrar pago</app-button>
                    </template>
                    <template #cell-actions="{ row }"><button class="link-button" type="button" @click="$emit('view', row)">Ver detalle</button></template>
                </app-table>
                <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
            </app-data-panel>
        </template>

        <template v-else>
            <div class="metrics">
                <app-metric-card label="Cliente" :value="datos.cliente || 'Sin cliente'" hint="Titular del comprobante" icon="icons/people.svg" tone="green" />
                <app-metric-card label="Fecha" :value="datos.fecha || '—'" hint="Fecha de venta" icon="icons/calendar.svg" tone="cyan" />
                <app-metric-card label="Total" :value="money(datos.total)" hint="Monto del comprobante" icon="icons/money.svg" tone="blue" />
            </div>
            <app-data-panel title="Productos vendidos" subtitle="Detalle del comprobante seleccionado." eyebrow="Venta" flush>
                <app-table :columns="detailColumns" :rows="details" row-key="id" min-width="760px">
                    <template #cell-precio="{ row }">{{ money(row.costo_venta || row.costo_unitario) }}</template>
                    <template #cell-sub_total="{ value }"><strong>{{ money(value) }}</strong></template>
                </app-table>
            </app-data-panel>
        </template>

        <div id="salesPaymentModal" class="modal fade" tabindex="-1" aria-labelledby="salesPaymentTitle" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div><small>Cuenta por cobrar</small><h5 id="salesPaymentTitle" class="modal-title">Registrar amortización</h5></div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar" @click="$emit('clear-payment')"></button>
                    </div>
                    <div class="modal-body">
                        <div class="balance-grid">
                            <article><span>Monto original</span><strong>{{ money(payment.monto_total || datos.total) }}</strong></article>
                            <article><span>Saldo actual</span><strong>{{ money(currentBalance) }}</strong></article>
                            <article class="balance-grid__new"><span>Saldo después del pago</span><strong>{{ money(projectedBalance) }}</strong></article>
                        </div>
                        <div class="payment-layout">
                            <app-data-panel title="Nuevo pago" subtitle="Ingrese el monto y la forma de pago." eyebrow="Amortización">
                                <div class="payment-form">
                                    <label><span>Fecha</span><input v-model="payment.fecha" type="date" class="form-control"></label>
                                    <label><span>Monto *</span><input v-model.number="payment.amortizacion" type="number" min="0" step="0.01" class="form-control"></label>
                                    <label><span>Forma de pago *</span>
                                        <select v-model="payment.id_forma_pago" class="form-select">
                                            <option :value="0" disabled>Seleccione</option>
                                            <option v-for="form in paymentForms" :key="form.id" :value="form.id">{{ form.nombre }}</option>
                                        </select>
                                    </label>
                                    <label class="payment-form__full"><span>Descripción</span><textarea v-model="payment.descripcion" class="form-control" rows="3" placeholder="Observación opcional"></textarea></label>
                                </div>
                                <div class="payment-actions">
                                    <app-button variant="ghost" data-bs-dismiss="modal" @click="$emit('clear-payment')">Cancelar</app-button>
                                    <app-button icon="icons/check.svg" :disabled="saving || Number(payment.amortizacion) <= 0" @click="$emit('save-payment')">{{ saving ? 'Guardando...' : 'Registrar pago' }}</app-button>
                                </div>
                            </app-data-panel>
                            <app-data-panel title="Historial de pagos" subtitle="Movimientos registrados para esta venta." eyebrow="Movimientos" flush>
                                <app-table :columns="paymentColumns" :rows="paymentHistory" row-key="id" min-width="560px" empty-message="Esta venta todavía no tiene amortizaciones.">
                                    <template #cell-monto_total="{ value }">{{ money(value) }}</template>
                                    <template #cell-amortizacion="{ value }">{{ money(value) }}</template>
                                    <template #cell-saldo="{ value }"><strong>{{ money(value) }}</strong></template>
                                </app-table>
                            </app-data-panel>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'SalesPaymentsWorkspace',
    props: {
        rows: { type: Array, default: () => [] }, details: { type: Array, default: () => [] },
        datos: { type: Object, required: true }, payment: { type: Object, required: true },
        paymentHistory: { type: Array, default: () => [] }, lastPayment: { type: Object, default: () => ({}) },
        paymentForms: { type: Array, default: () => [] }, pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] }, listado: { type: Number, default: 0 },
        search: { type: String, default: '' }, criterion: { type: String, default: 'cliente.nombre' },
        saving: { type: Boolean, default: false },
    },
    data() {
        return {
            columns: [
                { key: 'fecha', label: 'Fecha' }, { key: 'cliente', label: 'Cliente' }, { key: 'tienda', label: 'Tienda' },
                { key: 'total', label: 'Total' }, { key: 'estado', label: 'Estado' }, { key: 'name', label: 'Usuario' },
                { key: 'payment', label: 'Pago' }, { key: 'actions', label: 'Acciones' },
            ],
            detailColumns: [
                { key: 'articulo', label: 'Producto' }, { key: 'tienda', label: 'Tienda' }, { key: 'precio', label: 'Precio' },
                { key: 'cantidad', label: 'Cantidad' }, { key: 'sub_total', label: 'Subtotal' },
            ],
            paymentColumns: [
                { key: 'fecha', label: 'Fecha' }, { key: 'monto_total', label: 'Monto original' },
                { key: 'amortizacion', label: 'Pago' }, { key: 'saldo', label: 'Saldo' }, { key: 'descripcion', label: 'Descripción' },
            ],
        };
    },
    computed: {
        visibleTotal() { return this.rows.reduce((sum, row) => sum + Number(row.total || 0), 0); },
        currentBalance() {
            const value = this.lastPayment && this.lastPayment.saldo;
            return value === undefined || value === null ? Number(this.payment.saldo || this.payment.monto_total || 0) : Number(value);
        },
        projectedBalance() { return Math.max(0, this.currentBalance - Number(this.payment.amortizacion || 0)); },
    },
    methods: { money(value) { return `${Number(value || 0).toFixed(2)} Bs`; } },
};
</script>

<style scoped>
.sales-payments { display: grid; gap: 1rem; padding: 1.25rem; }
.metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .85rem; }
.toolbar { display: grid; grid-template-columns: minmax(150px, 220px) minmax(240px, 1fr) auto; gap: .65rem; padding: 1rem; border-bottom: 1px solid #d8e5df; background: #f8fbf9; }
.status { display: inline-flex; padding: .3rem .55rem; color: #8a6213; font-size: .68rem; font-weight: 800; background: #fff3d2; border-radius: 999px; }
.status--paid { color: #17693c; background: #e3f5eb; }
.link-button { color: #087f9b; font-size: .75rem; font-weight: 800; background: none; border: 0; }
.modal-content { border: 0; border-radius: 16px; box-shadow: 0 22px 55px rgba(23,54,43,.2); }
.modal-header { padding: 1rem 1.25rem; border-color: #d8e5df; }
.modal-header small { color: #0e93b5; font-size: .65rem; font-weight: 800; text-transform: uppercase; }
.modal-title { color: #17362b; font-weight: 800; }
.modal-body { padding: 1.2rem; background: #f5faf7; }
.balance-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .7rem; margin-bottom: 1rem; }
.balance-grid article { display: flex; flex-direction: column; padding: 1rem; background: #fff; border: 1px solid #d8e5df; border-radius: 12px; }
.balance-grid span { color: #6f817a; font-size: .7rem; font-weight: 800; text-transform: uppercase; }
.balance-grid strong { color: #17362b; font-size: 1.2rem; }
.balance-grid__new { background: #eaf8f0 !important; border-color: #bce1cc !important; }
.payment-layout { display: grid; grid-template-columns: minmax(310px, .8fr) minmax(420px, 1.2fr); gap: 1rem; }
.payment-form { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem; }
.payment-form label { display: grid; gap: .3rem; color: #52675f; font-size: .72rem; font-weight: 800; }
.payment-form__full { grid-column: 1 / -1; }
.payment-actions { display: flex; justify-content: flex-end; gap: .5rem; margin-top: 1rem; }
@media (max-width: 950px) { .metrics, .balance-grid, .payment-layout { grid-template-columns: 1fr; } }
@media (max-width: 700px) { .sales-payments { padding: .8rem; } .toolbar, .payment-form { grid-template-columns: 1fr; } }
</style>

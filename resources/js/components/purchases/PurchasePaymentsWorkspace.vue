<template>
    <section class="payments-page">
        <app-page-skeleton v-if="initialLoading" />
        <div v-else class="payments-page__content">
        <app-module-header
            eyebrow="Compras"
            :title="listado === 1 ? 'Cuentas del proveedor' : 'Pagos de compras'"
            :subtitle="listado === 1
                ? 'Seleccione una compra pendiente para registrar su amortización.'
                : 'Consulte proveedores con deuda y gestione sus pagos pendientes.'"
        >
            <template #actions>
                <span class="payments-cash-status" :class="{ 'is-open': estadoCaja === 'Abierta' }">
                    <span></span>{{ estadoCaja === 'Abierta' ? 'Caja abierta' : 'Caja cerrada' }}
                </span>
            </template>
        </app-module-header>

        <template v-if="listado === 0">
            <section class="payments-overview">
                <app-metric-card label="Proveedores pendientes" :value="providers.length" hint="Con cuentas por pagar" icon="icons/truck.svg" :loading="providersLoading" />
                <app-metric-card label="Deuda visible" :value="`Bs ${money(totalDebt)}`" hint="Saldo de los resultados actuales" icon="icons/wallet.svg" tone="cyan" :loading="providersLoading" />
                <app-metric-card label="Estado de caja" :value="estadoCaja || 'Sin verificar'" hint="Necesaria para registrar pagos" icon="img/menu/control.png" tone="neutral" />
            </section>
            <app-data-panel title="Proveedores con saldo" subtitle="Busque por nombre y seleccione el proveedor que desea gestionar." eyebrow="Cuentas por pagar" flush>
                <div class="payments-toolbar">
                    <app-input
                        :value="search"
                        label="Buscar proveedor"
                        placeholder="Nombre, NIT o CI…"
                        @input="$emit('update:search', $event)"
                        @keyup="$emit('typing')"
                        @keyup.enter="$emit('search')"
                    />
                    <app-button icon="icons/magnifying-glass.svg" @click="$emit('search')">Buscar</app-button>
                </div>
                <app-table :columns="providerColumns" :rows="providers" :loading="providersLoading" min-width="760px" empty-title="Sin cuentas pendientes" empty-message="No se encontraron proveedores con saldo por pagar.">
                    <template #cell-cliente="{ row }"><strong>{{ row.cliente }}</strong><small>{{ row.telefono || 'Sin teléfono' }}</small></template>
                    <template #cell-monto="{ value }"><strong class="payments-debt">Bs {{ money(value) }}</strong></template>
                    <template #cell-action="{ row }"><app-button variant="secondary" icon="icons/arrow-right.svg" @click="$emit('select-provider', row)">Ver compras</app-button></template>
                </app-table>
                <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
            </app-data-panel>
        </template>

        <template v-else>
            <div class="payments-provider-card">
                <app-button variant="ghost" icon="icons/arrow-left.svg" @click="$emit('back')">Volver</app-button>
                <div><span>Proveedor</span><strong>{{ datos.cliente || '—' }}</strong></div>
                <div><span>NIT / CI</span><strong>{{ datos.matricula || '—' }}</strong></div>
                <div><span>Teléfono</span><strong>{{ datos.telefono || '—' }}</strong></div>
                <div class="payments-provider-card__total"><span>Deuda total</span><strong>Bs {{ money(datos.monto) }}</strong></div>
            </div>

            <app-data-panel title="Compras a crédito" subtitle="Cada fila representa una nota pendiente o parcialmente pagada." eyebrow="Estado de cuenta" flush>
                <app-table :columns="accountColumns" :rows="accounts" :loading="accountsLoading" min-width="820px" empty-title="Sin compras pendientes" empty-message="Este proveedor no tiene documentos disponibles para pago.">
                    <template #cell-number="{ row }">{{ accounts.indexOf(row) + 1 }}</template>
                    <template #cell-monto="{ value }">Bs {{ money(value) }}</template>
                    <template #cell-saldo="{ value }"><strong :class="Number(value) > 0 ? 'payments-debt' : 'payments-paid'">Bs {{ money(value) }}</strong></template>
                    <template #cell-action="{ row }">
                        <app-button
                            :variant="Number(row.saldo) > 0 ? 'primary' : 'secondary'"
                            :disabled="Number(row.saldo) <= 0"
                            icon="icons/money.svg"
                            data-bs-toggle="modal"
                            data-bs-target="#modalPago"
                            @click="$emit('pay', row)"
                        >
                            {{ Number(row.saldo) > 0 ? 'Registrar pago' : 'Pagado' }}
                        </app-button>
                    </template>
                </app-table>
            </app-data-panel>
        </template>

        <div class="modal fade" id="modalPago" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content payments-modal">
                    <div class="modal-header">
                        <div><small>Cuenta por pagar</small><h5>Registrar amortización</h5></div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div v-if="estadoCaja !== 'Abierta'" class="payments-warning">
                            <img :src="asset('icons/warning.svg')" alt="" aria-hidden="true">
                            <div><strong>La caja está cerrada</strong><span>Debe aperturar caja antes de registrar un pago.</span></div>
                        </div>
                        <template v-else>
                            <div class="payments-balance-grid">
                                <app-metric-card label="Monto original" :value="`Bs ${money(ultimoPago.monto_total)}`" hint="Total de la compra" icon="icons/description.svg" tone="neutral" />
                                <app-metric-card label="Saldo actual" :value="`Bs ${money(ultimoPago.saldo)}`" hint="Antes de este pago" icon="icons/wallet.svg" tone="cyan" />
                                <app-metric-card label="Nuevo saldo" :value="`Bs ${money(calculatedBalance)}`" hint="Después de la amortización" icon="icons/functions.svg" tone="green" />
                            </div>
                            <app-data-panel title="Datos del pago" subtitle="Ingrese el importe y la forma en que se realizó." eyebrow="Nuevo movimiento">
                                <div class="payments-form">
                                    <app-input v-model="datosPago.fecha" type="date" label="Fecha" readonly />
                                    <app-input v-model="datosPago.fecha_final" type="date" label="Fecha límite" readonly />
                                    <app-input v-model="datosPago.amortizacion" type="number" label="Importe a pagar" min="0" step="0.01">
                                        <template #prefix>Bs</template>
                                    </app-input>
                                    <label class="payments-field">
                                        <span>Forma de pago</span>
                                        <select v-model="datosPago.id_forma_pago">
                                            <option value="0" disabled>Seleccione la forma</option>
                                            <option v-for="form in paymentForms" :key="form.id" :value="form.id">{{ form.nombre }}</option>
                                        </select>
                                    </label>
                                    <app-input v-model="datosPago.descripcion" class="payments-form__full" label="Descripción" placeholder="Referencia u observación del pago" multiline :rows="2" />
                                </div>
                            </app-data-panel>
                            <app-data-panel title="Historial de amortizaciones" subtitle="Movimientos registrados para esta compra." eyebrow="Trazabilidad" flush>
                                <app-table :columns="paymentColumns" :rows="paymentHistory" :loading="paymentHistoryLoading" min-width="720px" empty-title="Sin pagos previos" empty-message="Esta será la primera amortización de la compra.">
                                    <template #cell-number="{ row }">{{ paymentHistory.indexOf(row) + 1 }}</template>
                                    <template #cell-monto_total="{ value }">Bs {{ money(value) }}</template>
                                    <template #cell-amortizacion="{ value }">Bs {{ money(value) }}</template>
                                    <template #cell-saldo="{ value }"><strong>Bs {{ money(value) }}</strong></template>
                                </app-table>
                            </app-data-panel>
                        </template>
                    </div>
                    <div class="modal-footer">
                        <app-button variant="secondary" data-bs-dismiss="modal" @click="$emit('clear-payment')">Cerrar</app-button>
                        <app-button v-if="estadoCaja === 'Abierta'" icon="icons/save.svg" :loading="isBusy" @click="$emit('save-payment')">Guardar pago</app-button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'PurchasePaymentsWorkspace',
    props: {
        listado: { type: Number, default: 0 },
        providers: { type: Array, default: () => [] },
        accounts: { type: Array, default: () => [] },
        paymentForms: { type: Array, default: () => [] },
        paymentHistory: { type: Array, default: () => [] },
        datos: { type: Object, required: true },
        datosPago: { type: Object, required: true },
        ultimoPago: { type: Object, default: () => ({}) },
        estadoCaja: { type: String, default: '' },
        search: { type: String, default: '' },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        calculatedBalance: { type: Number, default: 0 },
        isBusy: { type: Boolean, default: false },
        initialLoading: { type: Boolean, default: false },
        providersLoading: { type: Boolean, default: false },
        accountsLoading: { type: Boolean, default: false },
        paymentHistoryLoading: { type: Boolean, default: false },
    },
    data() {
        return {
            providerColumns: [
                { key: 'matricula', label: 'NIT / CI' }, { key: 'cliente', label: 'Proveedor' },
                { key: 'monto', label: 'Saldo total', className: 'text-right' }, { key: 'action', label: '' },
            ],
            accountColumns: [
                { key: 'number', label: 'Nº' }, { key: 'fecha', label: 'Fecha' }, { key: 'telefono', label: 'Teléfono' },
                { key: 'monto', label: 'Monto', className: 'text-right' }, { key: 'saldo', label: 'Saldo', className: 'text-right' },
                { key: 'action', label: '' },
            ],
            paymentColumns: [
                { key: 'number', label: 'Nº' }, { key: 'fecha', label: 'Fecha' },
                { key: 'monto_total', label: 'Monto original' }, { key: 'amortizacion', label: 'Pago' },
                { key: 'formaP', label: 'Forma de pago' }, { key: 'saldo', label: 'Saldo' },
            ],
        };
    },
    computed: {
        totalDebt() {
            return this.providers.reduce((total, provider) => total + Number(provider.monto || 0), 0);
        },
    },
    methods: {
        money(value) { return Number(value || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 }); },
        asset(path) { const index = window.location.pathname.indexOf('/main'); const base = index >= 0 ? window.location.pathname.substring(0, index) : ''; return `${base}/${path}`; },
    },
};
</script>

<style scoped>
.payments-page { display: grid; gap: 1rem; padding: 1.15rem; background: #f4f8f6; }
.payments-page__content { display: contents; }
.payments-overview { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
.payments-cash-status { display: inline-flex; align-items: center; gap: .45rem; padding: .45rem .65rem; color: #e6eee9; font-size: .73rem; font-weight: 800; background: rgba(9,44,33,.28); border: 1px solid rgba(255,255,255,.22); border-radius: 999px; }
.payments-cash-status > span { width: 8px; height: 8px; background: #e0a43a; border-radius: 50%; }
.payments-cash-status.is-open > span { background: #71d5e8; box-shadow: 0 0 0 4px rgba(113,213,232,.16); }
.payments-toolbar { display: grid; grid-template-columns: minmax(260px, 1fr) auto; align-items: end; gap: .6rem; max-width: 720px; padding: 1rem; background: #f8fbf9; border-bottom: 1px solid #d8e5df; }
.payments-page small { display: block; color: #6f817a; font-size: .67rem; }
.payments-debt { color: #b45120; }
.payments-paid { color: #1f8a4c; }
.payments-provider-card { display: grid; grid-template-columns: auto repeat(3, minmax(0,1fr)) minmax(160px,.8fr); align-items: center; gap: .8rem; padding: .85rem 1rem; background: #fff; border: 1px solid #d8e5df; border-radius: 12px; box-shadow: 0 5px 16px rgba(23,54,43,.06); }
.payments-provider-card > div { min-width: 0; padding-left: .8rem; border-left: 1px solid #dbe8e2; }
.payments-provider-card span, .payments-provider-card strong { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.payments-provider-card span { color: #6f817a; font-size: .65rem; font-weight: 800; text-transform: uppercase; }
.payments-provider-card strong { margin-top: .2rem; color: #17362b; font-size: .82rem; }
.payments-provider-card__total { padding: .65rem .75rem !important; color: #fff; background: #1f8a4c; border: 0 !important; border-radius: 8px; }
.payments-provider-card__total span, .payments-provider-card__total strong { color: #fff; }
.payments-modal .modal-header { color: #fff; background: linear-gradient(110deg, #173f32, #1f8a4c); border-bottom: 3px solid #3ec6e0; }
.payments-modal .modal-header h5 { margin: .1rem 0 0; font-weight: 800; }
.payments-modal .modal-header small { color: #71d5e8; font-weight: 800; text-transform: uppercase; }
.payments-modal .modal-body { display: grid; gap: 1rem; background: #f4f8f6; }
.payments-warning { display: flex; align-items: center; gap: .8rem; padding: 1rem; color: #7a5312; background: #fff8e8; border: 1px solid #edd69e; border-radius: 10px; }
.payments-warning img { width: 28px; filter: invert(49%) sepia(74%) saturate(520%); }
.payments-warning strong, .payments-warning span { display: block; }
.payments-warning span { margin-top: .15rem; font-size: .76rem; }
.payments-balance-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: .8rem; }
.payments-form { display: grid; grid-template-columns: repeat(2, 1fr); gap: .8rem; }
.payments-form__full { grid-column: 1 / -1; }
.payments-field { display: flex; flex-direction: column; gap: .35rem; color: #315044; font-size: .73rem; font-weight: 800; }
.payments-field select { min-height: 40px; padding: .48rem .65rem; color: #17362b; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; }
@media (max-width: 900px) { .payments-provider-card { grid-template-columns: 1fr 1fr; } .payments-provider-card > .app-button { grid-column: 1 / -1; } .payments-overview { grid-template-columns: 1fr; } }
@media (max-width: 650px) { .payments-page { padding: .75rem; } .payments-toolbar, .payments-balance-grid, .payments-form, .payments-provider-card { grid-template-columns: 1fr; } .payments-form__full { grid-column: auto; } }
</style>

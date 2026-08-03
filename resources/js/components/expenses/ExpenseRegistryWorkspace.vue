<template>
    <section class="expenses-page">
        <app-page-skeleton v-if="initialLoading" :rows="6" :columns="6" label="Cargando registro de gastos" />
        <template v-else>
        <app-module-header
            eyebrow="Gastos"
            title="Registro de gastos"
            subtitle="Registre egresos, controle su forma de pago y consulte el historial."
        >
            <template #actions>
                <app-button icon="icons/plus.svg" @click="$emit('create')">Registrar gasto</app-button>
            </template>
        </app-module-header>

        <div class="expenses-metrics">
            <app-metric-card label="Gastos registrados" :value="expenseCount" hint="Cantidad de movimientos" icon="icons/description.svg" tone="green" :loading="tableLoading" />
            <app-metric-card label="Motivos disponibles" :value="reasonCount" hint="Categorías de gasto" icon="icons/tags.svg" tone="cyan" :loading="tableLoading" />
            <app-metric-card label="Total visible" :value="`Bs ${money(visibleTotal)}`" hint="Suma de la página actual" icon="icons/money.svg" tone="blue" :loading="tableLoading" />
        </div>

        <app-data-panel title="Historial de gastos" subtitle="Consulte movimientos por motivo o monto." eyebrow="Seguimiento" flush>
            <div class="expenses-toolbar">
                <app-select
                    :value="criterion"
                    :options="searchCriteria"
                    label="Buscar por"
                    @input="$emit('update:criterion', $event)"
                />
                <app-input
                    :value="search"
                    label="Texto de búsqueda"
                    placeholder="Motivo o importe…"
                    @input="$emit('update:search', $event)"
                    @keyup.enter="$emit('search')"
                />
                <app-button icon="icons/magnifying-glass.svg" @click="$emit('search')">Buscar</app-button>
            </div>
            <app-table :columns="columns" :rows="rows" :loading="tableLoading" min-width="900px" empty-title="Sin gastos registrados" empty-message="Los movimientos aparecerán aquí después del primer registro.">
                <template #cell-fecha="{ row }"><span class="expense-date">{{ row.fecha_mostrar || row.fecha }}</span></template>
                <template #cell-motivo="{ value }"><strong>{{ value }}</strong></template>
                <template #cell-monto="{ value }"><strong class="expense-amount">Bs {{ money(value) }}</strong></template>
                <template #cell-forma="{ value }"><span class="expense-payment">{{ value || '—' }}</span></template>
                <template #cell-actions="{ row }">
                    <div class="expense-actions">
                        <app-icon-button icon="icons/pencil.svg" label="Modificar gasto" @click="$emit('edit', row)" />
                    </div>
                </template>
            </app-table>
            <purchase-pagination :pagination="pagination" :pages="pages" @change="$emit('page', $event)" />
        </app-data-panel>

        <div v-if="modal" class="expense-registry-backdrop" @click.self="$emit('close')">
            <section class="expense-registry-dialog" role="dialog" aria-modal="true" aria-labelledby="expense-dialog-title">
                <header>
                    <div><span>Movimiento de caja</span><h2 id="expense-dialog-title">{{ action === 1 ? 'Registrar gasto' : 'Modificar gasto' }}</h2></div>
                    <button type="button" aria-label="Cerrar" @click="$emit('close')">×</button>
                </header>
                <div class="expense-registry-dialog__content">
                    <div class="expense-form">
                        <div class="expense-field expense-field--full">
                            <div class="expense-reason-control">
                                <app-select
                                    v-model="datos.id_motivo_gasto"
                                    :options="reasons"
                                    option-value="id"
                                    option-label="nombre"
                                    label="Motivo de gasto"
                                    placeholder="Seleccione un motivo"
                                    :placeholder-value="0"
                                    required
                                    :error="fieldError('id_motivo_gasto')"
                                />
                                <app-button variant="secondary" icon="icons/plus.svg" @click="$emit('create-reason')">Nuevo</app-button>
                            </div>
                        </div>
                        <app-input v-model="datos.fecha" type="date" label="Fecha" required />
                        <app-select
                            v-model="datos.id_forma_pago"
                            :options="paymentForms"
                            option-value="id"
                            option-label="nombre"
                            label="Forma de pago"
                            placeholder="Seleccione la forma"
                            :placeholder-value="0"
                            required
                            :error="fieldError('id_forma_pago')"
                        />
                        <app-input v-model="datos.monto" type="number" label="Monto total" min="0" step="0.01" required :error="fieldError('monto')">
                            <template #prefix>Bs</template>
                        </app-input>
                        <app-input v-if="Number(datos.id_forma_pago) === 6" v-model="datos.efectivo" type="number" label="Importe en efectivo" min="0" :max="datos.monto" step="0.01" @input="$emit('calculate-deposit')">
                            <template #prefix>Bs</template>
                        </app-input>
                        <div v-if="Number(datos.id_forma_pago) === 6" class="expense-deposit">
                            <span>Importe por depósito</span>
                            <strong>Bs {{ money(mixedDeposit) }}</strong>
                            <small>Calculado automáticamente</small>
                        </div>
                        <app-input v-model="datos.descripcion" class="expense-field--full" label="Descripción" placeholder="Detalle o referencia del gasto" multiline :rows="3" />
                    </div>

                    <aside class="expense-summary">
                        <span>Resumen del egreso</span>
                        <div><small>Monto total</small><strong>Bs {{ money(datos.monto) }}</strong></div>
                        <div><small>Efectivo</small><strong>Bs {{ money(cashAmount) }}</strong></div>
                        <div><small>Depósito / digital</small><strong>Bs {{ money(depositAmount) }}</strong></div>
                        <div class="expense-summary__total"><small>Total distribuido</small><strong>Bs {{ money(distributedTotal) }}</strong></div>
                    </aside>
                </div>
                <div v-if="validationErrors.length" class="expense-validation">
                    <span v-for="error in validationErrors" :key="error">{{ error }}</span>
                </div>
                <footer>
                    <app-button variant="secondary" @click="$emit('close')">Cancelar</app-button>
                    <app-button icon="icons/save.svg" :loading="saving" @click="$emit(action === 1 ? 'save' : 'update')">
                        {{ action === 1 ? 'Guardar gasto' : 'Guardar cambios' }}
                    </app-button>
                </footer>
            </section>
        </div>

        <div v-if="reasonModal" class="expense-registry-backdrop expense-registry-backdrop--nested" @click.self="$emit('close-reason')">
            <section class="quick-reason-dialog" role="dialog" aria-modal="true">
                <header><div><span>Creación rápida</span><h2>Nuevo motivo de gasto</h2></div><button type="button" @click="$emit('close-reason')">×</button></header>
                <div class="quick-reason-dialog__body">
                    <app-input v-model="reasonData.nombre" label="Nombre" required placeholder="Nombre del motivo" :error="reasonFieldError('nombre')" />
                    <app-input v-model="reasonData.descripcion" label="Descripción" multiline :rows="3" />
                </div>
                <footer>
                    <app-button variant="secondary" @click="$emit('close-reason')">Cancelar</app-button>
                    <app-button icon="icons/save.svg" :loading="reasonSaving" @click="$emit('save-reason')">Guardar motivo</app-button>
                </footer>
            </section>
        </div>
        </template>
    </section>
</template>

<script>
export default {
    name: 'ExpenseRegistryWorkspace',
    props: {
        rows: { type: Array, default: () => [] },
        reasons: { type: Array, default: () => [] },
        paymentForms: { type: Array, default: () => [] },
        datos: { type: Object, required: true },
        reasonData: { type: Object, required: true },
        expenseCount: { type: [Number, String], default: 0 },
        reasonCount: { type: [Number, String], default: 0 },
        modal: { type: [Number, Boolean], default: false },
        reasonModal: { type: Boolean, default: false },
        action: { type: Number, default: 1 },
        pagination: { type: Object, required: true },
        pages: { type: Array, default: () => [] },
        search: { type: String, default: '' },
        criterion: { type: String, default: 'motivo_gasto.nombre' },
        serverErrors: { type: Object, default: () => ({}) },
        validationErrors: { type: Array, default: () => [] },
        saving: { type: Boolean, default: false },
        initialLoading: { type: Boolean, default: false },
        tableLoading: { type: Boolean, default: false },
        reasonSaving: { type: Boolean, default: false },
        reasonErrors: { type: Object, default: () => ({}) },
    },
    data() {
        return {
            searchCriteria: [
                { value: 'motivo_gasto.nombre', label: 'Motivo de gasto' },
                { value: 'gasto.monto', label: 'Monto' },
            ],
            columns: [
                { key: 'fecha', label: 'Fecha' }, { key: 'motivo', label: 'Motivo' },
                { key: 'monto', label: 'Monto', className: 'text-right' }, { key: 'forma', label: 'Forma de pago' },
                { key: 'descripcion', label: 'Descripción' }, { key: 'actions', label: 'Acciones' },
            ],
        };
    },
    computed: {
        visibleTotal() {
            return this.rows.reduce((total, row) => total + Number(row.monto || 0), 0);
        },
        mixedDeposit() {
            return Math.max(0, Number(this.datos.monto || 0) - Number(this.datos.efectivo || 0));
        },
        cashAmount() {
            if (Number(this.datos.id_forma_pago) === 6) return Number(this.datos.efectivo || 0);
            return this.isCashForm ? Number(this.datos.monto || 0) : 0;
        },
        depositAmount() {
            if (Number(this.datos.id_forma_pago) === 6) return this.mixedDeposit;
            return this.isCashForm ? 0 : Number(this.datos.monto || 0);
        },
        distributedTotal() {
            return this.cashAmount + this.depositAmount;
        },
        isCashForm() {
            const selected = this.paymentForms.find(form => Number(form.id) === Number(this.datos.id_forma_pago));
            return selected ? String(selected.nombre).toLowerCase().includes('efectivo') : Number(this.datos.id_forma_pago) === 2;
        },
    },
    watch: {
        mixedDeposit: {
            immediate: true,
            handler(value) {
                if (Number(this.datos.id_forma_pago) === 6) this.datos.deposito = value;
            },
        },
    },
    methods: {
        money(value) {
            return Number(value || 0).toLocaleString('es-BO', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },
        fieldError(field) {
            const value = this.serverErrors[field];
            return Array.isArray(value) ? value[0] : (value || '');
        },
        reasonFieldError(field) {
            const value = this.reasonErrors[field];
            return Array.isArray(value) ? value[0] : (value || '');
        },
        asset(path) {
            const index = window.location.pathname.indexOf('/main');
            const base = index >= 0 ? window.location.pathname.substring(0, index) : '';
            return `${base}/${path}`;
        },
    },
};
</script>

<style scoped>
.expenses-page { display: grid; gap: 1rem; padding: 1.15rem; background: #f4f8f6; }
.expenses-metrics { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 1rem; }
.expenses-toolbar { display: grid; grid-template-columns: 220px minmax(260px,1fr) auto; align-items: end; gap: .6rem; padding: 1rem; background: #f8fbf9; border-bottom: 1px solid #d8e5df; }
.expense-field { display: flex; flex-direction: column; gap: .35rem; color: #315044; font-size: .73rem; font-weight: 800; }
.expense-field--full { grid-column: 1 / -1; }
.expense-date { white-space: nowrap; }
.expense-amount { color: #b45120; font-variant-numeric: tabular-nums; }
.expense-payment { display: inline-flex; padding: .24rem .45rem; color: #0b718b; font-size: .67rem; font-weight: 800; background: #e8f9fc; border-radius: 999px; }
.expense-actions { display: flex; align-items: center; justify-content: center; }
.expense-registry-backdrop { position: fixed; inset: 0; z-index: 1055; display: grid; padding: 1rem; place-items: center; background: rgba(9,33,26,.58); backdrop-filter: blur(3px); }
.expense-registry-backdrop--nested { z-index: 1065; background: rgba(9,33,26,.7); }
.expense-registry-dialog { overflow: hidden; width: min(980px,100%); max-height: calc(100vh - 2rem); overflow-y: auto; background: #fff; border-radius: 14px; box-shadow: 0 24px 70px rgba(0,0,0,.25); }
.expense-registry-dialog header, .quick-reason-dialog header { display: flex; align-items: flex-start; justify-content: space-between; padding: 1rem 1.15rem; color: #fff; background: linear-gradient(110deg,#173f32,#1f8a4c); border-bottom: 3px solid #3ec6e0; }
.expense-registry-dialog header span, .quick-reason-dialog header span { color: #71d5e8; font-size: .65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; }
.expense-registry-dialog h2, .quick-reason-dialog h2 { margin: .1rem 0 0; font-size: 1.05rem; font-weight: 800; }
.expense-registry-dialog header button, .quick-reason-dialog header button { color: #fff; font-size: 1.5rem; line-height: 1; background: transparent; border: 0; }
.expense-registry-dialog__content { display: grid; grid-template-columns: minmax(0,1fr) 270px; gap: 1rem; padding: 1.15rem; }
.expense-form { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: .85rem; }
.expense-reason-control { display: grid; grid-template-columns: 1fr auto; align-items: end; gap: .45rem; }
.expense-deposit { display: flex; flex-direction: column; justify-content: center; padding: .65rem .75rem; color: #315044; background: #e8f9fc; border: 1px solid #cae9ef; border-radius: 8px; }
.expense-deposit span, .expense-deposit small { font-size: .68rem; }
.expense-deposit strong { margin: .2rem 0; color: #0b718b; font-size: 1rem; }
.expense-summary { display: flex; flex-direction: column; gap: .25rem; padding: 1rem; color: #315044; background: linear-gradient(155deg,#effaf4,#fff); border: 1px solid #cfe0d8; border-top: 4px solid #3ec6e0; border-radius: 12px; }
.expense-summary > span { margin-bottom: .3rem; color: #0e93b5; font-size: .67rem; font-weight: 900; text-transform: uppercase; }
.expense-summary > div { display: flex; align-items: center; justify-content: space-between; gap: .8rem; min-height: 43px; padding: .5rem .2rem; border-bottom: 1px solid #dbe8e2; }
.expense-summary small { font-weight: 700; }
.expense-summary strong { color: #17362b; }
.expense-summary__total { margin-top: auto; padding: .65rem !important; color: #fff; background: #1f8a4c; border: 0 !important; border-radius: 7px; }
.expense-summary__total strong { color: #fff; }
.expense-validation { display: flex; flex-wrap: wrap; gap: .25rem .8rem; margin: 0 1.15rem .8rem; padding: .65rem .75rem; color: #a52b2b; font-size: .72rem; background: #fff0f0; border: 1px solid #f0caca; border-radius: 8px; }
.expense-error { color: #d63c3c; font-size: .68rem; }
.expense-registry-dialog footer, .quick-reason-dialog footer { display: flex; justify-content: flex-end; gap: .5rem; padding: .85rem 1.15rem; background: #f4f8f6; border-top: 1px solid #d8e5df; }
.quick-reason-dialog { overflow: hidden; width: min(560px,100%); background: #fff; border-radius: 14px; box-shadow: 0 24px 70px rgba(0,0,0,.3); }
.quick-reason-dialog__body { display: grid; gap: .8rem; padding: 1.15rem; }
@media (max-width: 800px) { .expenses-metrics { grid-template-columns: 1fr; } .expense-registry-dialog__content { grid-template-columns: 1fr; } }
@media (max-width: 650px) { .expenses-page { padding: .75rem; } .expenses-toolbar, .expense-form { grid-template-columns: 1fr; } .expense-field--full { grid-column: auto; } .expense-registry-dialog footer, .quick-reason-dialog footer { flex-direction: column-reverse; } }
</style>

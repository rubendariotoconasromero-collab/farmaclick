<template>
    <main class="main caja-module">
        <div class="caja-module__content">
            <app-module-header
                eyebrow="Operaciones"
                title="Arqueo de caja"
                subtitle="Controle la apertura, concilie los movimientos y cierre la caja desde una vista ordenada."
            >
                <template #actions>
                    <span class="caja-status" :class="isCajaOpen ? 'caja-status--open' : 'caja-status--closed'">
                        <span class="caja-status__dot"></span>
                        {{ isCajaOpen ? 'Caja abierta' : 'Caja cerrada' }}
                    </span>
                </template>
            </app-module-header>

            <section class="caja-actions">
                <app-data-panel
                    title="Apertura inicial"
                    subtitle="Registre el efectivo disponible al iniciar el turno."
                    eyebrow="Paso 1"
                >
                    <div class="caja-opening">
                        <app-input
                            id="caja-apertura"
                            v-model="datos.apertura"
                            type="number"
                            label="Monto de apertura"
                            min="0"
                            step="0.01"
                            :disabled="isLoading || isSaving || isCajaOpen"
                            @keyup.enter="aperturarCaja"
                        >
                            <template #prefix>Bs</template>
                        </app-input>
                        <app-button
                            icon="icons/lock-unlocked.svg"
                            :loading="isSaving && pendingAction === 'open'"
                            :disabled="isLoading || isCajaOpen"
                            @click="aperturarCaja"
                        >
                            Dar apertura
                        </app-button>
                    </div>
                </app-data-panel>

                <app-data-panel
                    title="Caja actual"
                    subtitle="Información del turno que se encuentra en curso."
                    eyebrow="Estado"
                >
                    <div v-if="isCajaOpen" class="caja-current">
                        <div>
                            <span>Apertura</span>
                            <strong>Bs {{ formatAmount(activeCaja.apertura) }}</strong>
                        </div>
                        <div>
                            <span>Responsable</span>
                            <strong>{{ activeCaja.name || '—' }}</strong>
                        </div>
                        <div>
                            <span>Fecha y hora</span>
                            <strong>{{ activeCaja.fecha_apertura || '—' }}</strong>
                        </div>
                    </div>
                    <div v-else class="caja-empty-state">
                        <img :src="publicAsset('icons/info.svg')" alt="" aria-hidden="true">
                        <span>No existe una caja abierta para este usuario.</span>
                    </div>
                </app-data-panel>

                <app-data-panel
                    title="Conciliación y cierre"
                    subtitle="Compare el efectivo físico con los movimientos del sistema."
                    eyebrow="Paso 2"
                >
                    <div class="caja-close-action">
                        <p>
                            {{ isCajaOpen
                                ? 'La caja está lista para iniciar el conteo y la conciliación.'
                                : 'Primero debe registrar una apertura para habilitar el arqueo.' }}
                        </p>
                        <app-button
                            variant="secondary"
                            icon="icons/calculator.svg"
                            :disabled="isLoading || !isCajaOpen"
                            block
                            @click="btnArqueo"
                        >
                            Iniciar arqueo
                        </app-button>
                    </div>
                </app-data-panel>
            </section>

            <app-data-panel
                title="Registro de caja abierta"
                subtitle="Datos principales del turno activo."
                eyebrow="Seguimiento"
                flush
            >
                <app-table
                    :columns="cajaColumns"
                    :rows="arrayCaja"
                    :loading="isLoading"
                    min-width="680px"
                    caption="Registro de la caja abierta"
                    empty-title="Sin caja abierta"
                    empty-message="La información del turno aparecerá aquí después de dar apertura."
                >
                    <template #cell-apertura="{ value }">
                        <strong>Bs {{ formatAmount(value) }}</strong>
                    </template>
                    <template #cell-total_neto="{ value }">
                        <strong class="caja-positive">Bs {{ formatAmount(value) }}</strong>
                    </template>
                    <template #cell-estado="{ value }">
                        <span class="caja-table-status">{{ value || 'Abierta' }}</span>
                    </template>
                </app-table>
            </app-data-panel>

            <section v-if="listado === 1" class="caja-workbook" aria-label="Hoja de arqueo">
                <div class="caja-workbook__header">
                    <div>
                        <span>Hoja de trabajo</span>
                        <h2>Conteo y conciliación</h2>
                        <p>Ingrese la cantidad de cada denominación. Los importes y la diferencia se calculan automáticamente.</p>
                    </div>
                    <app-button variant="ghost" icon="icons/x.svg" @click="ocultarListado1">
                        Cerrar vista
                    </app-button>
                </div>

                <div class="caja-metrics">
                    <app-metric-card
                        label="Efectivo esperado"
                        :value="`Bs ${formatAmount(datos.saldo_total)}`"
                        hint="Saldo calculado por el sistema"
                        icon="icons/functions.svg"
                        tone="green"
                    />
                    <app-metric-card
                        label="Efectivo contado"
                        :value="`Bs ${formatAmount(total_efec)}`"
                        hint="Total de billetes y monedas"
                        icon="icons/money.svg"
                        tone="cyan"
                    />
                    <app-metric-card
                        label="Diferencia"
                        :value="`Bs ${formatAmount(datos.diferencia)}`"
                        :hint="differenceLabel"
                        icon="icons/balance-scale.svg"
                        :tone="Number(datos.diferencia) === 0 ? 'neutral' : 'blue'"
                    />
                </div>

                <div class="caja-sheets" :class="{ 'caja-sheets--single': !isAdmin }">
                    <app-data-panel
                        title="Conteo físico"
                        subtitle="Complete la columna Cantidad como en una hoja de cálculo."
                        eyebrow="Hoja A"
                        flush
                    >
                        <div class="cash-sheet">
                            <table>
                                <caption class="visually-hidden">Conteo de efectivo por denominación</caption>
                                <thead>
                                    <tr>
                                        <th>Denominación</th>
                                        <th>Moneda</th>
                                        <th class="cash-sheet__quantity">Cantidad</th>
                                        <th class="cash-sheet__amount">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="row in denominationRows" :key="row.field">
                                        <td><strong>{{ row.label }}</strong></td>
                                        <td>{{ row.currency }}</td>
                                        <td class="cash-sheet__editable">
                                            <input
                                                v-model.number="datos[row.field]"
                                                type="number"
                                                min="0"
                                                step="1"
                                                :aria-label="`Cantidad de ${row.label}`"
                                                @input="calcularArqueo"
                                            >
                                        </td>
                                        <td class="cash-sheet__amount">Bs {{ formatAmount(denominationSubtotal(row)) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3">Total efectivo contado</th>
                                        <th class="cash-sheet__amount">Bs {{ formatAmount(total_efec) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </app-data-panel>

                    <app-data-panel
                        v-if="isAdmin"
                        title="Resumen del sistema"
                        subtitle="Ingresos, egresos y balance del turno agrupados por categoría."
                        eyebrow="Hoja B"
                        flush
                    >
                        <app-table
                            class="caja-summary-table"
                            :columns="summaryColumns"
                            :rows="summaryRows"
                            :row-class="summaryRowClass"
                            row-key="key"
                            min-width="100%"
                            caption="Resumen financiero del arqueo"
                        >
                            <template #cell-concepto="{ row }">
                                <span>{{ row.concepto }}</span>
                            </template>
                            <template #cell-monto="{ row }">
                                <strong v-if="row.kind !== 'group'">Bs {{ formatAmount(row.monto) }}</strong>
                            </template>
                        </app-table>
                    </app-data-panel>
                </div>

                <div class="caja-workbook__footer">
                    <div class="caja-balance" :class="differenceClass">
                        <span>{{ differenceLabel }}</span>
                        <strong>Bs {{ formatAmount(datos.diferencia) }}</strong>
                    </div>
                    <div class="caja-workbook__buttons">
                        <app-button variant="secondary" @click="ocultarListado1">Cancelar</app-button>
                        <app-button
                            icon="icons/lock-locked.svg"
                            :loading="isSaving && pendingAction === 'close'"
                            @click="cerrarCaja"
                        >
                            Confirmar cierre de caja
                        </app-button>
                    </div>
                </div>
            </section>
        </div>
    </main>
</template>

<script>
import Swal from 'sweetalert2';

const numberValue = value => {
    const parsed = Number(value);
    return Number.isFinite(parsed) ? parsed : 0;
};

const emptyDatos = () => ({
    id: 0,
    fecha_apertura: '',
    fecha_cierre: '',
    doscientos: 0,
    cien: 0,
    cincuenta: 0,
    veinte: 0,
    diez: 0,
    cinco: 0,
    dos: 0,
    uno: 0,
    cerocinco: 0,
    ceroveinte: 0,
    cien_dolar: 0,
    registro_venta: 0,
    total_ingreso_efec: 0,
    registro_compra: 0,
    apertura: 0,
    total: 0,
    gastos: 0,
    gastos_deposito: 0,
    saldo_sistema: 0,
    saldo_efectivo: 0,
    diferencia: 0,
    id_usuario: 0,
    estado: 'Abierta',
    grupo: 0,
    total_contado: 0,
    total_contado_deposito: 0,
    total_credito: 0,
    total_credito_deposito: 0,
    total_contado_compra: 0,
    total_contado_deposito_compra: 0,
    total_credito_compra: 0,
    total_credito_deposito_compra: 0,
    total_ingreso_efectivo: 0,
    total_egreso_efectivo: 0,
    gasto_efectivo2: 0,
    saldo_total: 0,
});

const denominationRows = Object.freeze([
    { field: 'doscientos', label: '200', currency: 'Bolivianos', multiplier: 200 },
    { field: 'cien', label: '100', currency: 'Bolivianos', multiplier: 100 },
    { field: 'cincuenta', label: '50', currency: 'Bolivianos', multiplier: 50 },
    { field: 'veinte', label: '20', currency: 'Bolivianos', multiplier: 20 },
    { field: 'diez', label: '10', currency: 'Bolivianos', multiplier: 10 },
    { field: 'cinco', label: '5', currency: 'Bolivianos', multiplier: 5 },
    { field: 'dos', label: '2', currency: 'Bolivianos', multiplier: 2 },
    { field: 'uno', label: '1', currency: 'Bolivianos', multiplier: 1 },
    { field: 'cerocinco', label: '0,50', currency: 'Bolivianos', multiplier: 0.5 },
    { field: 'ceroveinte', label: '0,20', currency: 'Bolivianos', multiplier: 0.2 },
    { field: 'cien_dolar', label: '100', currency: 'Dólares · TC 7', multiplier: 700 },
]);

export default {
    name: 'FrmCaja',
    data() {
        return {
            datos: emptyDatos(),
            usuario: {},
            arrayCaja: [],
            arrayArqueo: [],
            ventaefectivo: {},
            ventaDeposito: {},
            ventaCobrarefectivo: {},
            ventaCobrarDeposito: {},
            gastoEfec: {},
            gastoDep: {},
            compraefectivo: {},
            compraDeposito: {},
            compraCobrarefectivo: {},
            compraCobrarDeposito: {},
            listado: 0,
            total_efec: 0,
            isLoading: true,
            isSaving: false,
            pendingAction: '',
            denominationRows,
            summaryColumns: [
                { key: 'concepto', label: 'Concepto' },
                { key: 'monto', label: 'Monto', className: 'text-right' },
            ],
        };
    },
    computed: {
        isCajaOpen() {
            return this.arrayCaja.length > 0;
        },
        activeCaja() {
            return this.arrayCaja[0] || {};
        },
        isAdmin() {
            return Number(this.usuario.id_grupo) === 1;
        },
        cajaColumns() {
            const base = [
                { key: 'fecha_apertura', label: 'Fecha de apertura' },
                { key: 'apertura', label: 'Monto inicial', className: 'text-right' },
                { key: 'name', label: 'Responsable' },
                { key: 'estado', label: 'Estado' },
            ];

            if (this.isAdmin) {
                base.splice(2, 0, { key: 'total_neto', label: 'Saldo neto', className: 'text-right' });
            }

            return base;
        },
        summaryRows() {
            return [
                { key: 'income-group', concepto: 'INGRESOS DE CAJA', kind: 'group' },
                { key: 'opening', concepto: 'Apertura inicial', monto: this.datos.apertura },
                { key: 'cash-sales', concepto: 'Ventas al contado · efectivo', monto: this.datos.total_contado },
                { key: 'deposit-sales', concepto: 'Ventas al contado · depósitos', monto: this.datos.total_contado_deposito },
                { key: 'credit-cash', concepto: 'Cobros de ventas a crédito · efectivo', monto: this.datos.total_credito },
                { key: 'credit-deposit', concepto: 'Cobros de ventas a crédito · depósitos', monto: this.datos.total_credito_deposito },
                { key: 'income-cash-total', concepto: 'Total ingresos en efectivo', monto: this.datos.total_ingreso_efec, kind: 'subtotal' },
                { key: 'income-total', concepto: 'Total ingresos general', monto: this.datos.registro_venta, kind: 'total' },
                { key: 'expense-group', concepto: 'EGRESOS Y GASTOS', kind: 'group' },
                { key: 'expenses-cash', concepto: 'Gastos · efectivo', monto: this.datos.gastos },
                { key: 'expenses-deposit', concepto: 'Gastos · depósitos', monto: this.datos.gastos_deposito },
                { key: 'purchases-cash', concepto: 'Compras al contado · efectivo', monto: this.datos.total_contado_compra },
                { key: 'purchases-deposit', concepto: 'Compras al contado · depósitos', monto: this.datos.total_contado_deposito_compra },
                { key: 'payable-cash', concepto: 'Pagos de compras a crédito · efectivo', monto: this.datos.total_credito_compra },
                { key: 'payable-deposit', concepto: 'Pagos de compras a crédito · depósitos', monto: this.datos.total_credito_deposito_compra },
                { key: 'expense-cash-total', concepto: 'Total egresos en efectivo', monto: this.datos.gasto_efectivo2, kind: 'subtotal' },
                { key: 'expense-total', concepto: 'Total egresos general', monto: this.datos.total_egreso_efectivo, kind: 'total' },
                { key: 'balance-group', concepto: 'BALANCE FINAL', kind: 'group' },
                { key: 'expected', concepto: 'Efectivo esperado por el sistema', monto: this.datos.saldo_total, kind: 'total' },
                { key: 'counted', concepto: 'Efectivo físico contado', monto: this.total_efec, kind: 'total' },
                { key: 'difference', concepto: 'Diferencia de caja', monto: this.datos.diferencia, kind: 'balance' },
            ];
        },
        differenceLabel() {
            const difference = numberValue(this.datos.diferencia);
            if (Math.abs(difference) < 0.005) return 'Caja cuadrada';
            return difference > 0 ? 'Sobrante de efectivo' : 'Faltante de efectivo';
        },
        differenceClass() {
            const difference = numberValue(this.datos.diferencia);
            if (Math.abs(difference) < 0.005) return 'caja-balance--ok';
            return difference > 0 ? 'caja-balance--positive' : 'caja-balance--negative';
        },
    },
    created() {
        this.loadModule();
    },
    methods: {
        async loadModule() {
            this.isLoading = true;
            try {
                await Promise.all([this.usuarioAuth(), this.cargarResumenArqueo()]);
                await this.listarCajaAbierta();
            } catch (error) {
                this.showRequestError(error, 'No se pudo cargar la información de caja.');
            } finally {
                this.isLoading = false;
            }
        },
        async usuarioAuth() {
            const response = await axios.get('/usuario_auth');
            this.usuario = response.data || {};
        },
        async cargarResumenArqueo() {
            const response = await axios.get('/arqueo/resumen');
            const result = response.data || {};
            this.ventaefectivo = result.ventaefectivo || {};
            this.ventaDeposito = result.ventaDeposito || {};
            this.ventaCobrarefectivo = result.ventaCobrarefectivo || {};
            this.ventaCobrarDeposito = result.ventaCobrarDeposito || {};
            this.gastoEfec = result.gastoEfec || {};
            this.gastoDep = result.gastoDep || {};
            this.compraefectivo = result.compraefectivo || {};
            this.compraDeposito = result.compraDeposito || {};
            this.compraCobrarefectivo = result.compraCobrarefectivo || {};
            this.compraCobrarDeposito = result.compraCobrarDeposito || {};
        },
        async listarCajaAbierta() {
            const response = await axios.get('/arqueo_usuario');
            this.arrayCaja = Array.isArray(response.data) ? response.data : [];

            if (!this.isCajaOpen) return;

            const cashSales = numberValue(this.ventaefectivo.total_e);
            const depositSales = numberValue(this.ventaDeposito.total_d);
            const creditCash = numberValue(this.ventaCobrarefectivo.total_e);
            const creditDeposit = numberValue(this.ventaCobrarDeposito.total_d);
            const cashPurchases = numberValue(this.compraefectivo.total_e);
            const depositPurchases = numberValue(this.compraDeposito.total_d);
            const payableCash = numberValue(this.compraCobrarefectivo.total_e);
            const payableDeposit = numberValue(this.compraCobrarDeposito.total_d);
            const cashExpenses = numberValue(this.gastoEfec.total_e);
            const depositExpenses = numberValue(this.gastoDep.total_d);

            this.arrayCaja = this.arrayCaja.map(item => ({
                ...item,
                total_ingreso_general: cashSales + depositSales + creditCash + creditDeposit,
                total_ingreso_efectivo: cashSales + creditCash,
                total_egreso_general: cashPurchases + depositPurchases + payableCash + payableDeposit + cashExpenses + depositExpenses,
                total_egreso_efectivo: cashPurchases + payableCash + cashExpenses,
                total_neto: numberValue(item.apertura) + cashSales + creditCash - cashPurchases - payableCash - cashExpenses,
            }));

            this.datos.grupo = numberValue(this.activeCaja.grupo);
        },
        async traerArqueo() {
            const response = await axios.get('/arqueo2');
            this.arrayArqueo = Array.isArray(response.data) ? response.data : [];
            if (!this.arrayArqueo.length) return;

            this.datos.id = numberValue(this.arrayArqueo[0].id);
            this.datos.apertura = numberValue(this.arrayArqueo[0].apertura);
            this.datos.registro_compra = numberValue(this.arrayArqueo[0].registro_compra);
            this.calcularArqueo();
        },
        calcularArqueo() {
            const opening = numberValue(this.datos.apertura);
            const cashSales = numberValue(this.ventaefectivo.total_e);
            const depositSales = numberValue(this.ventaDeposito.total_d);
            const creditCash = numberValue(this.ventaCobrarefectivo.total_e);
            const creditDeposit = numberValue(this.ventaCobrarDeposito.total_d);
            const cashExpenses = numberValue(this.gastoEfec.total_e);
            const depositExpenses = numberValue(this.gastoDep.total_d);
            const cashPurchases = numberValue(this.compraefectivo.total_e);
            const depositPurchases = numberValue(this.compraDeposito.total_d);
            const payableCash = numberValue(this.compraCobrarefectivo.total_e);
            const payableDeposit = numberValue(this.compraCobrarDeposito.total_d);

            this.datos.total_contado = cashSales;
            this.datos.total_contado_deposito = depositSales;
            this.datos.total_credito = creditCash;
            this.datos.total_credito_deposito = creditDeposit;
            this.datos.registro_venta = opening + cashSales + depositSales + creditCash + creditDeposit;
            this.datos.total_ingreso_efec = opening + cashSales + creditCash;
            this.datos.gastos = cashExpenses;
            this.datos.gastos_deposito = depositExpenses;
            this.datos.total_contado_compra = cashPurchases;
            this.datos.total_contado_deposito_compra = depositPurchases;
            this.datos.total_credito_compra = payableCash;
            this.datos.total_credito_deposito_compra = payableDeposit;
            this.datos.total_egreso_efectivo = cashExpenses + depositExpenses + cashPurchases + depositPurchases + payableCash + payableDeposit;
            this.datos.gasto_efectivo2 = cashExpenses + cashPurchases + payableCash;
            this.datos.saldo_total = this.datos.total_ingreso_efec - this.datos.gasto_efectivo2;
            this.total_efec = this.denominationRows.reduce(
                (total, row) => total + this.denominationSubtotal(row),
                0
            );
            this.datos.saldo_efectivo = this.total_efec;
            this.datos.diferencia = this.total_efec - this.datos.saldo_total;
        },
        denominationSubtotal(row) {
            return numberValue(this.datos[row.field]) * row.multiplier;
        },
        async btnArqueo() {
            if (!this.isCajaOpen) {
                return Swal.fire('Caja cerrada', 'Debe realizar la apertura antes de iniciar el arqueo.', 'info');
            }

            this.isLoading = true;
            try {
                await this.cargarResumenArqueo();
                await this.traerArqueo();
                this.listado = 1;
                this.$nextTick(() => {
                    const sheet = this.$el.querySelector('.caja-workbook');
                    if (sheet) sheet.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            } catch (error) {
                this.showRequestError(error, 'No se pudo preparar el arqueo.');
            } finally {
                this.isLoading = false;
            }
        },
        ocultarListado1() {
            this.listado = 0;
        },
        async aperturarCaja() {
            const opening = numberValue(this.datos.apertura);
            if (opening <= 0) {
                return Swal.fire('Monto inválido', 'Ingrese un monto de apertura mayor a cero.', 'warning');
            }
            if (this.isCajaOpen) {
                return Swal.fire('Caja abierta', 'Cierre la caja actual antes de iniciar una nueva.', 'warning');
            }

            this.isSaving = true;
            this.pendingAction = 'open';
            try {
                await axios.post('/arqueo/guardar', { ...this.datos, apertura: opening });
                await Promise.all([this.cargarResumenArqueo(), this.listarCajaAbierta()]);
                this.datos.apertura = 0;
                Swal.fire({ icon: 'success', title: 'Caja abierta correctamente', timer: 1600, showConfirmButton: false });
            } catch (error) {
                this.showRequestError(error, 'No se pudo realizar la apertura.');
            } finally {
                this.isSaving = false;
                this.pendingAction = '';
            }
        },
        async cerrarCaja() {
            const negativeRow = this.denominationRows.find(row => numberValue(this.datos[row.field]) < 0);
            if (negativeRow) {
                return Swal.fire('Cantidad inválida', `La denominación ${negativeRow.label} no admite valores negativos.`, 'warning');
            }

            this.calcularArqueo();
            const confirmation = await Swal.fire({
                title: '¿Confirmar cierre de caja?',
                html: `Efectivo contado: <strong>Bs ${this.formatAmount(this.total_efec)}</strong><br>Diferencia: <strong>Bs ${this.formatAmount(this.datos.diferencia)}</strong>`,
                icon: Math.abs(numberValue(this.datos.diferencia)) < 0.005 ? 'question' : 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, cerrar caja',
                cancelButtonText: 'Revisar conteo',
                confirmButtonColor: '#1f8a4c',
            });
            if (!confirmation.isConfirmed) return;

            this.isSaving = true;
            this.pendingAction = 'close';
            const cajaId = this.datos.id;
            try {
                await axios.put('/arqueo/modificar', this.datos);
                await axios.post(`/arqueo/actualizar_ventas?id_caja=${cajaId}`);
                this.resetCaja();
                await Promise.all([this.cargarResumenArqueo(), this.listarCajaAbierta()]);
                Swal.fire({ icon: 'success', title: 'Caja cerrada correctamente', timer: 1800, showConfirmButton: false });
            } catch (error) {
                this.showRequestError(error, 'No se pudo cerrar la caja.');
            } finally {
                this.isSaving = false;
                this.pendingAction = '';
            }
        },
        resetCaja() {
            this.datos = emptyDatos();
            this.arrayArqueo = [];
            this.total_efec = 0;
            this.listado = 0;
        },
        summaryRowClass(row) {
            return row.kind ? `caja-summary-row--${row.kind}` : '';
        },
        formatAmount(value) {
            return numberValue(value).toLocaleString('es-BO', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            });
        },
        publicAsset(path) {
            const mainIndex = window.location.pathname.indexOf('/main');
            const base = mainIndex >= 0 ? window.location.pathname.substring(0, mainIndex) : '';
            return `${base}/${String(path).replace(/^\/+/, '')}`;
        },
        showRequestError(error, fallback) {
            const message = error && error.response && error.response.data && error.response.data.error
                ? error.response.data.error
                : fallback;
            Swal.fire('Ocurrió un problema', message, 'error');
        },
    },
};
</script>

<style scoped>
.caja-module { min-height: 100%; background: #f4f8f6; }
.caja-module__content { display: grid; gap: 1rem; padding: 1.15rem; }
.caja-status { display: inline-flex; align-items: center; gap: .5rem; padding: .48rem .72rem; color: #fff; font-size: .76rem; font-weight: 800; background: rgba(9, 44, 33, .28); border: 1px solid rgba(255, 255, 255, .24); border-radius: 999px; }
.caja-status__dot { width: 8px; height: 8px; background: #aebbb6; border: 2px solid rgba(255, 255, 255, .7); border-radius: 50%; }
.caja-status--open .caja-status__dot { background: #71d5e8; box-shadow: 0 0 0 4px rgba(113, 213, 232, .16); }
.caja-actions { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
.caja-opening { display: grid; grid-template-columns: minmax(0, 1fr) auto; align-items: end; gap: .7rem; }
.caja-current { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .65rem; }
.caja-current div { min-width: 0; padding: .7rem; background: #f4f9f6; border: 1px solid #deebe5; border-radius: 9px; }
.caja-current span, .caja-current strong { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.caja-current span { margin-bottom: .25rem; color: #6f817a; font-size: .66rem; font-weight: 800; text-transform: uppercase; letter-spacing: .035em; }
.caja-current strong { color: #17362b; font-size: .8rem; }
.caja-empty-state { display: flex; min-height: 58px; align-items: center; gap: .7rem; color: #6f817a; font-size: .78rem; }
.caja-empty-state img { width: 25px; height: 25px; opacity: .58; filter: invert(44%) sepia(16%) saturate(378%) hue-rotate(101deg); }
.caja-close-action p { min-height: 38px; margin: 0 0 .65rem; color: #6f817a; font-size: .77rem; line-height: 1.45; }
.caja-positive { color: #1f8a4c; }
.caja-table-status { display: inline-flex; padding: .25rem .52rem; color: #17693c; font-size: .69rem; font-weight: 800; background: #dff4e7; border-radius: 999px; }
.caja-workbook { overflow: hidden; background: #fff; border: 1px solid #cfe0d8; border-radius: 14px; box-shadow: 0 10px 28px rgba(23, 54, 43, .09); }
.caja-workbook__header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; padding: 1.1rem 1.25rem; color: #fff; background: linear-gradient(112deg, #173f32, #1f8a4c); border-bottom: 3px solid #3ec6e0; }
.caja-workbook__header span { color: #71d5e8; font-size: .65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .07em; }
.caja-workbook__header h2 { margin: .15rem 0; font-size: 1.15rem; font-weight: 800; }
.caja-workbook__header p { margin: 0; color: #d8eee5; font-size: .76rem; }
.caja-workbook__header .app-button { color: #fff; }
.caja-metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; padding: 1rem; background: #f4f8f6; border-bottom: 1px solid #d8e5df; }
.caja-sheets { display: grid; grid-template-columns: minmax(390px, .85fr) minmax(480px, 1.15fr); gap: 1rem; padding: 1rem; }
.caja-sheets--single { grid-template-columns: minmax(0, 720px); justify-content: center; }
.cash-sheet { overflow: auto; max-height: 540px; }
.cash-sheet table { width: 100%; border-collapse: separate; border-spacing: 0; color: #315044; font-size: .78rem; }
.cash-sheet th, .cash-sheet td { height: 43px; padding: .52rem .72rem; border-right: 1px solid #dbe8e2; border-bottom: 1px solid #dbe8e2; }
.cash-sheet th:last-child, .cash-sheet td:last-child { border-right: 0; }
.cash-sheet thead th { position: sticky; top: 0; z-index: 1; color: #315044; font-size: .66rem; text-transform: uppercase; letter-spacing: .05em; background: #effaf4; }
.cash-sheet tbody tr:nth-child(even) { background: #fafcfb; }
.cash-sheet tbody tr:hover { background: #f0fbf7; }
.cash-sheet__quantity { width: 120px; text-align: center; }
.cash-sheet__editable { padding: 0 !important; background: #f2fbff; box-shadow: inset 3px 0 #3ec6e0; }
.cash-sheet__editable input { width: 100%; height: 42px; padding: .45rem .7rem; color: #17362b; font: inherit; font-weight: 800; text-align: center; background: transparent; border: 0; outline: 0; }
.cash-sheet__editable input:focus { background: #fff; box-shadow: inset 0 0 0 2px #0e93b5; }
.cash-sheet__amount { text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap; }
.cash-sheet tfoot th { position: sticky; bottom: 0; color: #fff; background: #315044; border-color: #315044; }
.caja-summary-table ::v-deep .app-table__scroll { max-height: 540px; }
.caja-summary-table ::v-deep .caja-summary-row--group td { color: #fff; font-size: .69rem; font-weight: 800; letter-spacing: .055em; background: #315044; }
.caja-summary-table ::v-deep .caja-summary-row--subtotal td { font-weight: 800; background: #effaf4; border-top: 1px solid #b9d6ca; }
.caja-summary-table ::v-deep .caja-summary-row--total td { color: #17362b; font-weight: 800; background: #e4f5eb; }
.caja-summary-table ::v-deep .caja-summary-row--balance td { color: #0b718b; font-weight: 900; background: #e8f9fc; border-top: 2px solid #3ec6e0; }
.caja-summary-table ::v-deep td:last-child, .caja-summary-table ::v-deep th:last-child { text-align: right; font-variant-numeric: tabular-nums; }
.caja-workbook__footer { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 1rem 1.15rem; background: #f4f8f6; border-top: 1px solid #d8e5df; }
.caja-balance { display: flex; min-width: 230px; align-items: center; justify-content: space-between; gap: 1rem; padding: .62rem .8rem; color: #315044; background: #edf3f0; border-left: 4px solid #80938b; border-radius: 7px; }
.caja-balance span { font-size: .72rem; font-weight: 800; }
.caja-balance strong { font-size: 1rem; font-variant-numeric: tabular-nums; }
.caja-balance--ok { color: #17693c; background: #dff4e7; border-color: #2fae66; }
.caja-balance--positive { color: #0b718b; background: #e8f9fc; border-color: #3ec6e0; }
.caja-balance--negative { color: #a52b2b; background: #fff0f0; border-color: #d63c3c; }
.caja-workbook__buttons { display: flex; gap: .55rem; }
@media (max-width: 1200px) {
    .caja-actions { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .caja-actions > :last-child { grid-column: 1 / -1; }
    .caja-sheets { grid-template-columns: 1fr; }
}
@media (max-width: 760px) {
    .caja-module__content { padding: .75rem; }
    .caja-actions, .caja-metrics { grid-template-columns: 1fr; }
    .caja-actions > :last-child { grid-column: auto; }
    .caja-opening { grid-template-columns: 1fr; }
    .caja-current { grid-template-columns: 1fr; }
    .caja-sheets { grid-template-columns: minmax(0, 1fr); padding: .7rem; }
    .caja-workbook__header, .caja-workbook__footer { align-items: stretch; flex-direction: column; }
    .caja-workbook__buttons { flex-direction: column-reverse; }
    .caja-balance { min-width: 0; }
}
</style>

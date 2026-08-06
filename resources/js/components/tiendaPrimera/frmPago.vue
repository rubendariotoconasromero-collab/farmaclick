<template>
    <store-one-payments-workspace
        :mode="mode"
        :clients="arrayCliente"
        :clients-total="allClients.length"
        :selected-client="datos"
        :credits="arrayDetalleCliente"
        :search="buscar"
        :loading="loading"
        :credits-loading="creditsLoading"
        :pagination="pagination"
        :pages="pagesNumber"
        :cash-state="estadoCaja"
        :payment-open="paymentOpen"
        :payment-loading="paymentLoading"
        :saving="saving"
        :selected-credit="selectedCredit"
        :payment="datosPago"
        :payment-forms="arrayForma"
        :payment-history="arrayCXCobrar"
        :current-balance="currentBalance"
        :original-amount="originalAmount"
        :portfolio-total="portfolioTotal"
        @update:search="buscar = $event; scheduleSearch()"
        @search="listarCliente"
        @refresh="refreshClients"
        @page="cambiarPagina"
        @select-client="pasarPago"
        @back="volverPagoVenta"
        @open-payment="realizarPagos"
        @update:amount="datosPago.amortizacion = $event"
        @update:paymentForm="datosPago.id_forma_pago = $event"
        @update:description="datosPago.descripcion = $event"
        @close-payment="closePayment"
        @save-payment="guardarAmortizacion"
    />
</template>

<script>
import moment from 'moment';
import Swal from '../../utils/appSwal';
import StoreOnePaymentsWorkspace from '../sales/store-one/StoreOnePaymentsWorkspace.vue';

const emptyClient = () => ({
    id: 0,
    cliente: '',
    matricula: '',
    telefono: '',
    monto: 0,
    saldo_cliente: 0,
});

const emptyPayment = () => ({
    id: 0,
    fecha: moment().format('YYYY-MM-DD'),
    fecha_final: moment().format('YYYY-MM-DD'),
    monto_total: 0,
    saldo: 0,
    amortizacion: '',
    descripcion: '',
    id_pago: 0,
    id_forma_pago: 2,
});

export default {
    name: 'FrmTiendaPrimeraPago',
    components: { StoreOnePaymentsWorkspace },
    data() {
        return {
            mode: 'clients',
            datos: emptyClient(),
            datosPago: emptyPayment(),
            allClients: [],
            arrayCliente: [],
            arrayDetalleCliente: [],
            arrayCXCobrar: [],
            arrayForma: [],
            selectedCredit: {},
            currentBalance: 0,
            originalAmount: 0,
            buscar: '',
            criterio: 'nombre_cliente',
            id_tienda: 1,
            tipo_producto: 'Venta Directa',
            estadoCaja: '',
            loading: false,
            creditsLoading: false,
            paymentLoading: false,
            paymentOpen: false,
            saving: false,
            searchTimer: null,
            requestSequence: 0,
            perPage: 10,
            offset: 3,
            pagination: {
                total: 0,
                current_page: 1,
                per_page: 10,
                last_page: 1,
                from: 0,
                to: 0,
            },
        };
    },
    computed: {
        portfolioTotal() {
            return this.allClients.reduce(
                (sum, client) => sum + Number(client.saldo_cliente || 0),
                0,
            );
        },
        pagesNumber() {
            if (this.pagination.last_page <= 1) {
                return [];
            }

            let from = Math.max(1, this.pagination.current_page - this.offset);
            const to = Math.min(this.pagination.last_page, from + this.offset * 2);
            const pages = [];

            while (from <= to) {
                pages.push(from);
                from += 1;
            }

            return pages;
        },
    },
    methods: {
        async verificarCaja() {
            try {
                const response = await axios.get('/arqueo_caja/estado_caja');
                this.estadoCaja = response.data && response.data.estado
                    ? response.data.estado
                    : 'Cerrada';
            } catch (error) {
                this.estadoCaja = 'Cerrada';
                console.error(error);
            }
        },
        async listarCliente() {
            const sequence = ++this.requestSequence;
            this.loading = true;

            try {
                const response = await axios.get('/cliente_pago', {
                    params: {
                        buscar: this.buscar,
                        criterio: this.criterio,
                        id_tienda: this.id_tienda,
                        tipo_producto: this.tipo_producto,
                    },
                });

                if (sequence !== this.requestSequence) {
                    return;
                }

                this.allClients = (Array.isArray(response.data) ? response.data : [])
                    .filter(client => Number(client.saldo_cliente || 0) > 0);
                this.applyClientPage(1);
            } catch (error) {
                if (sequence === this.requestSequence) {
                    this.allClients = [];
                    this.applyClientPage(1);
                    this.showError('No fue posible cargar las cuentas por cobrar.');
                }
                console.error(error);
            } finally {
                if (sequence === this.requestSequence) {
                    this.loading = false;
                }
            }
        },
        applyClientPage(page) {
            const total = this.allClients.length;
            const lastPage = Math.max(1, Math.ceil(total / this.perPage));
            const currentPage = Math.min(Math.max(1, page), lastPage);
            const start = (currentPage - 1) * this.perPage;
            const end = Math.min(start + this.perPage, total);

            this.arrayCliente = this.allClients.slice(start, end);
            this.pagination = {
                total,
                current_page: currentPage,
                per_page: this.perPage,
                last_page: lastPage,
                from: total ? start + 1 : 0,
                to: end,
            };
        },
        cambiarPagina(page) {
            if (page === this.pagination.current_page) {
                return;
            }
            this.applyClientPage(page);
        },
        scheduleSearch() {
            window.clearTimeout(this.searchTimer);
            this.searchTimer = window.setTimeout(() => this.listarCliente(), 350);
        },
        refreshClients() {
            window.clearTimeout(this.searchTimer);
            this.listarCliente();
            this.verificarCaja();
        },
        async pasarPago(client) {
            this.datos = {
                ...emptyClient(),
                ...client,
                monto: Number(client.monto || 0),
                saldo_cliente: Number(client.saldo_cliente || 0),
            };
            this.mode = 'credits';
            await this.cargarDetalle();
        },
        async cargarDetalle() {
            if (!this.datos.cliente) {
                this.arrayDetalleCliente = [];
                return;
            }

            this.creditsLoading = true;
            try {
                const response = await axios.get('/pagos_cliente', {
                    params: {
                        buscar: this.datos.cliente,
                        id_tienda: this.id_tienda,
                        tipo_producto: this.tipo_producto,
                    },
                });
                this.arrayDetalleCliente = (Array.isArray(response.data) ? response.data : [])
                    .filter(credit => Number(credit.saldo || 0) > 0);
            } catch (error) {
                this.arrayDetalleCliente = [];
                this.showError('No fue posible cargar los créditos del cliente.');
                console.error(error);
            } finally {
                this.creditsLoading = false;
            }
        },
        volverPagoVenta() {
            this.closePayment();
            this.mode = 'clients';
            this.datos = emptyClient();
            this.arrayDetalleCliente = [];
            this.listarCliente();
        },
        async realizarPagos(credit) {
            this.selectedCredit = credit;
            this.datosPago = emptyPayment();
            this.arrayCXCobrar = [];
            this.currentBalance = Number(credit.saldo || 0);
            this.originalAmount = Number(credit.monto || 0);
            this.paymentOpen = true;
            this.paymentLoading = true;

            try {
                const [formsResponse, paymentResponse, cashResponse] = await Promise.all([
                    axios.get('/formaPago/selectFormaPago2'),
                    axios.get('/pago_venta', {
                        params: {
                            buscar: credit.id,
                            id_tienda: this.id_tienda,
                            tipo_producto: this.tipo_producto,
                        },
                    }),
                    axios.get('/arqueo_caja/estado_caja'),
                ]);

                this.arrayForma = Array.isArray(formsResponse.data) ? formsResponse.data : [];
                this.estadoCaja = cashResponse.data && cashResponse.data.estado
                    ? cashResponse.data.estado
                    : 'Cerrada';
                const payments = Array.isArray(paymentResponse.data) ? paymentResponse.data : [];
                const paymentRecord = payments[0];

                if (!paymentRecord) {
                    throw new Error('La venta no tiene un registro de pago asociado.');
                }

                this.datosPago.id_pago = paymentRecord.id;
                this.datosPago.fecha_final = paymentRecord.fecha_final || this.datosPago.fecha_final;
                this.datosPago.monto_total = Number(paymentRecord.monto || credit.monto || 0);
                this.originalAmount = this.datosPago.monto_total;
                await this.listarCXCobrar(paymentRecord.id);
            } catch (error) {
                this.showError(error.message || 'No fue posible preparar el registro del abono.');
                this.closePayment();
                console.error(error);
            } finally {
                this.paymentLoading = false;
            }
        },
        async listarCXCobrar(paymentId) {
            const response = await axios.get('/detalle_pago_credito', {
                params: { id_pago: paymentId },
            });
            this.arrayCXCobrar = Array.isArray(response.data) ? response.data : [];
            const lastPayment = this.arrayCXCobrar.length
                ? this.arrayCXCobrar[this.arrayCXCobrar.length - 1]
                : null;

            this.currentBalance = lastPayment
                ? Number(lastPayment.saldo || 0)
                : Number(this.selectedCredit.saldo || 0);
            this.originalAmount = lastPayment
                ? Number(lastPayment.monto_total || this.originalAmount)
                : this.originalAmount;
            this.datosPago.saldo = this.currentBalance;
        },
        closePayment() {
            if (this.saving) {
                return;
            }
            this.paymentOpen = false;
            this.paymentLoading = false;
            this.selectedCredit = {};
            this.arrayCXCobrar = [];
            this.currentBalance = 0;
            this.originalAmount = 0;
            this.datosPago = emptyPayment();
        },
        async guardarAmortizacion() {
            if (this.saving || this.paymentLoading) {
                return;
            }

            const amount = Number(this.datosPago.amortizacion || 0);
            if (this.estadoCaja !== 'Abierta') {
                this.showError('Debe aperturar caja antes de registrar un abono.');
                return;
            }
            if (amount <= 0) {
                this.showError('Ingrese un importe mayor a cero.');
                return;
            }
            if (amount > this.currentBalance) {
                this.showError('El abono no puede superar el saldo actual.');
                return;
            }
            if (!this.datosPago.id_forma_pago) {
                this.showError('Seleccione una forma de pago.');
                return;
            }

            this.saving = true;
            let closeWhenFinished = false;
            try {
                await axios.post('/c_x_cobrar/guardar', {
                    fecha: this.datosPago.fecha,
                    monto_total: Number(this.originalAmount).toFixed(2),
                    amortizacion: amount.toFixed(2),
                    saldo: Number(this.currentBalance).toFixed(2),
                    descripcion: this.datosPago.descripcion,
                    id_pago: this.datosPago.id_pago,
                    id_forma_pago: this.datosPago.id_forma_pago,
                });

                await this.listarCXCobrar(this.datosPago.id_pago);
                await this.cargarDetalle();
                await this.listarCliente();
                this.datosPago.amortizacion = '';
                this.datosPago.descripcion = '';
                this.datosPago.id_forma_pago = 2;

                await Swal.fire({
                    icon: 'success',
                    title: 'Abono registrado',
                    text: `Nuevo saldo: ${this.currentBalance.toFixed(2)} Bs`,
                    timer: 1400,
                    showConfirmButton: false,
                });

                closeWhenFinished = this.currentBalance <= 0;
            } catch (error) {
                this.showError('No fue posible registrar el abono.');
                console.error(error);
            } finally {
                this.saving = false;
                if (closeWhenFinished) {
                    this.closePayment();
                }
            }
        },
        showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un problema',
                text: message,
            });
        },
    },
    mounted() {
        this.listarCliente();
        this.verificarCaja();
    },
    beforeDestroy() {
        window.clearTimeout(this.searchTimer);
        this.requestSequence += 1;
    },
};
</script>

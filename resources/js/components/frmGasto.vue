<template>
    <main class="main">
        <expense-registry-workspace
            :rows="arrayGasto"
            :reasons="arrayTipoMotivoGasto"
            :payment-forms="arrayForma"
            :datos="datos"
            :reason-data="datosMotivoGasto"
            :expense-count="pagination.total"
            :reason-count="arrayTipoMotivoGasto.length"
            :modal="modal"
            :reason-modal="quickReasonModal"
            :action="tipoAccion"
            :pagination="pagination"
            :pages="pagesNumber"
            :search="buscar"
            :criterion="criterio"
            :server-errors="errores"
            :reason-errors="erroresMotivo"
            :validation-errors="errorMostrarMsjGasto"
            :saving="isSaving"
            :reason-saving="isReasonSaving"
            :initial-loading="initialLoading"
            :table-loading="tableLoading"
            @update:search="actualizarBusqueda"
            @update:criterion="actualizarCriterio"
            @search="listarGasto(1)"
            @page="cambiarPagina"
            @create="abrirModal('registrar')"
            @edit="abrirModal('modificar', $event)"
            @close="cerrarModal"
            @save="guardarGasto"
            @update="modificarGasto"
            @calculate-deposit="cambiarDeposito"
            @create-reason="abrirMotivoRapido"
            @close-reason="cerrarMotivoRapido"
            @save-reason="guardarMotivoGasto"
        />
    </main>
</template>

<script>
import moment from 'moment';
import { toast } from '../utils/appSwal';

const formularioVacio = () => ({
    id: 0,
    fecha: moment().format('YYYY-MM-DD'),
    monto: '',
    descripcion: '',
    id_motivo_gasto: 0,
    id_forma_pago: 2,
    efectivo: 0,
    deposito: 0,
    monto_aux: 0,
    efectivo_aux: 0,
    deposito_aux: 0,
    id_forma_pago_aux: 0,
});

const motivoVacio = () => ({ id: 0, nombre: '', descripcion: '' });
const paginacionVacia = () => ({ total: 0, current_page: 1, per_page: 15, last_page: 1, from: 0, to: 0 });

export default {
    name: 'FrmGasto',
    data() {
        return {
            datos: formularioVacio(),
            datosMotivoGasto: motivoVacio(),
            arrayGasto: [],
            arrayForma: [],
            arrayTipoMotivoGasto: [],
            modal: false,
            quickReasonModal: false,
            tipoAccion: 1,
            errores: {},
            erroresMotivo: {},
            errorMostrarMsjGasto: [],
            pagination: paginacionVacia(),
            offset: 3,
            criterio: 'motivo_gasto.nombre',
            buscar: '',
            initialLoading: true,
            tableLoading: false,
            isSaving: false,
            isReasonSaving: false,
            searchTimer: null,
            requestSequence: 0,
        };
    },
    computed: {
        pagesNumber() {
            if (!this.pagination.last_page) return [];
            let from = Math.max(1, this.pagination.current_page - this.offset);
            const to = Math.min(this.pagination.last_page, from + (this.offset * 2));
            const pages = [];
            while (from <= to) pages.push(from++);
            return pages;
        },
    },
    methods: {
        async inicializar() {
            this.initialLoading = true;
            try {
                await Promise.all([this.listarGasto(1), this.cargarCatalogos()]);
            } finally {
                this.initialLoading = false;
                this.tableLoading = false;
            }
        },
        async cargarCatalogos() {
            try {
                const responses = await Promise.all([
                    axios.get('/motivo_gasto/selectMotivoGasto'),
                    axios.get('/formaPago/selectFormaPago2'),
                ]);
                this.arrayTipoMotivoGasto = responses[0].data || [];
                this.arrayForma = responses[1].data || [];
            } catch (error) {
                toast.fire({ icon: 'error', title: 'No se pudieron cargar los catálogos' });
                throw error;
            }
        },
        async listarGasto(page = 1) {
            const sequence = ++this.requestSequence;
            this.tableLoading = !this.initialLoading;
            try {
                const { data } = await axios.get('/gasto', {
                    params: { page, buscar: this.buscar.trim(), criterio: this.criterio },
                });
                if (sequence !== this.requestSequence) return;
                this.arrayGasto = data.data || [];
                this.pagination = {
                    total: data.total || 0,
                    current_page: data.current_page || 1,
                    per_page: data.per_page || 15,
                    last_page: data.last_page || 1,
                    from: data.from || 0,
                    to: data.to || 0,
                };
            } catch (error) {
                if (sequence === this.requestSequence) {
                    toast.fire({ icon: 'error', title: 'No se pudieron cargar los gastos' });
                }
            } finally {
                if (sequence === this.requestSequence && !this.initialLoading) this.tableLoading = false;
            }
        },
        actualizarBusqueda(value) {
            this.buscar = value;
            clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(() => this.listarGasto(1), 450);
        },
        actualizarCriterio(value) {
            this.criterio = value;
            if (this.buscar.trim()) this.listarGasto(1);
        },
        cambiarPagina(page) {
            if (page !== this.pagination.current_page) this.listarGasto(page);
        },
        cambiarDeposito() {
            this.datos.deposito = Math.max(0, Number(this.datos.monto || 0) - Number(this.datos.efectivo || 0));
        },
        abrirModal(accion, row = {}) {
            this.errores = {};
            this.errorMostrarMsjGasto = [];
            this.tipoAccion = accion === 'modificar' ? 2 : 1;
            if (this.tipoAccion === 1) {
                this.datos = formularioVacio();
            } else {
                this.datos = {
                    id: row.id,
                    fecha: row.fecha,
                    monto: row.monto,
                    descripcion: row.descripcion || '',
                    id_motivo_gasto: row.id_motivo_gasto,
                    id_forma_pago: row.id_forma_pago,
                    efectivo: row.efectivo || 0,
                    deposito: row.deposito || 0,
                    monto_aux: row.monto || 0,
                    efectivo_aux: row.efectivo || 0,
                    deposito_aux: row.deposito || 0,
                    id_forma_pago_aux: row.id_forma_pago,
                };
            }
            this.modal = true;
        },
        cerrarModal() {
            if (this.isSaving) return;
            this.modal = false;
            this.datos = formularioVacio();
            this.errores = {};
            this.errorMostrarMsjGasto = [];
        },
        validarGasto() {
            const errors = [];
            if (!Number(this.datos.id_motivo_gasto)) errors.push('Seleccione un motivo de gasto.');
            if (!Number(this.datos.id_forma_pago)) errors.push('Seleccione una forma de pago.');
            if (!this.datos.fecha) errors.push('Seleccione la fecha del gasto.');
            if (Number(this.datos.monto) <= 0) errors.push('El monto debe ser mayor a cero.');
            if (Number(this.datos.id_forma_pago) === 6 && Number(this.datos.efectivo) > Number(this.datos.monto)) {
                errors.push('El efectivo no puede superar el monto total.');
            }
            this.errorMostrarMsjGasto = errors;
            return errors.length === 0;
        },
        normalizarDistribucion() {
            const paymentId = Number(this.datos.id_forma_pago);
            const amount = Number(this.datos.monto || 0);
            if (paymentId === 6) {
                this.datos.efectivo = Number(this.datos.efectivo || 0);
                this.datos.deposito = Math.max(0, amount - this.datos.efectivo);
            } else if (paymentId === 2) {
                this.datos.efectivo = amount;
                this.datos.deposito = 0;
            } else {
                this.datos.efectivo = 0;
                this.datos.deposito = amount;
            }
        },
        async guardarGasto() {
            if (!this.validarGasto()) return;
            await this.persistirGasto('/gasto/guardar', 'post', 'Gasto registrado');
        },
        async modificarGasto() {
            if (!this.validarGasto()) return;
            await this.persistirGasto('/gasto/modificar', 'put', 'Gasto actualizado');
        },
        async persistirGasto(url, method, successTitle) {
            this.isSaving = true;
            this.errores = {};
            this.normalizarDistribucion();
            try {
                await axios[method](url, this.datos);
                this.modal = false;
                this.datos = formularioVacio();
                toast.fire({ icon: 'success', title: successTitle });
                await this.listarGasto(1);
            } catch (error) {
                this.errores = error.response && error.response.data ? (error.response.data.errors || {}) : {};
                if (!Object.keys(this.errores).length) {
                    toast.fire({ icon: 'error', title: 'No se pudo guardar el gasto' });
                }
            } finally {
                this.isSaving = false;
            }
        },
        abrirMotivoRapido() {
            this.datosMotivoGasto = motivoVacio();
            this.erroresMotivo = {};
            this.quickReasonModal = true;
        },
        cerrarMotivoRapido() {
            if (this.isReasonSaving) return;
            this.quickReasonModal = false;
            this.datosMotivoGasto = motivoVacio();
            this.erroresMotivo = {};
        },
        async guardarMotivoGasto() {
            if (!this.datosMotivoGasto.nombre.trim()) {
                this.erroresMotivo = { nombre: ['El nombre no puede estar vacío.'] };
                return;
            }
            this.isReasonSaving = true;
            this.erroresMotivo = {};
            try {
                const { data } = await axios.post('/motivo_gasto/guardar', this.datosMotivoGasto);
                if (data && data.error === 0) {
                    this.erroresMotivo = { nombre: ['Ya existe un motivo con ese nombre.'] };
                    return;
                }
                const createdName = this.datosMotivoGasto.nombre;
                const { data: reasons } = await axios.get('/motivo_gasto/selectMotivoGasto');
                this.arrayTipoMotivoGasto = reasons || [];
                const created = this.arrayTipoMotivoGasto.find(item => item.nombre === createdName);
                if (created) this.datos.id_motivo_gasto = created.id;
                this.quickReasonModal = false;
                this.datosMotivoGasto = motivoVacio();
                toast.fire({ icon: 'success', title: 'Motivo registrado' });
            } catch (error) {
                this.erroresMotivo = error.response && error.response.data ? (error.response.data.errors || {}) : {};
                if (!Object.keys(this.erroresMotivo).length) {
                    toast.fire({ icon: 'error', title: 'No se pudo guardar el motivo' });
                }
            } finally {
                this.isReasonSaving = false;
            }
        },
    },
    mounted() {
        this.inicializar();
    },
    beforeDestroy() {
        clearTimeout(this.searchTimer);
        this.requestSequence += 1;
    },
};
</script>

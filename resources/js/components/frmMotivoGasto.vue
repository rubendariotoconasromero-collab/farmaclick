<template>
    <main class="main">
        <expense-reasons-workspace
            :rows="arrayMotivoGasto"
            :datos="datos"
            :count="pagination.total"
            :modal="modal"
            :action="tipoAccion"
            :pagination="pagination"
            :pages="pagesNumber"
            :search="buscar"
            :server-errors="errores"
            :validation-errors="errorMostrarMsjMotivoGasto"
            :saving="isSaving"
            :initial-loading="initialLoading"
            :table-loading="tableLoading"
            @update:search="actualizarBusqueda"
            @search="listarMotivoGasto(1)"
            @page="cambiarPagina"
            @create="abrirModal('registrar')"
            @edit="abrirModal('modificar', $event)"
            @close="cerrarModal"
            @save="guardarMotivoGasto"
            @update="modificarMotivoGasto"
        />
    </main>
</template>

<script>
import { toast } from '../utils/appSwal';

const formularioVacio = () => ({ id: 0, nombre: '', descripcion: '' });
const paginacionVacia = () => ({
    total: 0,
    current_page: 1,
    per_page: 15,
    last_page: 1,
    from: 0,
    to: 0,
});

export default {
    name: 'FrmMotivoGasto',
    data() {
        return {
            datos: formularioVacio(),
            arrayMotivoGasto: [],
            modal: false,
            tipoAccion: 1,
            errores: {},
            errorMostrarMsjMotivoGasto: [],
            pagination: paginacionVacia(),
            offset: 3,
            buscar: '',
            isSaving: false,
            initialLoading: true,
            tableLoading: false,
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
        async listarMotivoGasto(page = 1) {
            const sequence = ++this.requestSequence;
            this.tableLoading = !this.initialLoading;
            try {
                const { data } = await axios.get('/motivo_gasto', {
                    params: { page, buscar: this.buscar.trim(), criterio: 'nombre' },
                });
                if (sequence !== this.requestSequence) return;
                this.arrayMotivoGasto = data.data || [];
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
                    toast.fire({ icon: 'error', title: 'No se pudieron cargar los motivos' });
                }
            } finally {
                if (sequence === this.requestSequence) {
                    this.initialLoading = false;
                    this.tableLoading = false;
                }
            }
        },
        actualizarBusqueda(value) {
            this.buscar = value;
            clearTimeout(this.searchTimer);
            this.searchTimer = setTimeout(() => this.listarMotivoGasto(1), 450);
        },
        cambiarPagina(page) {
            if (page !== this.pagination.current_page) this.listarMotivoGasto(page);
        },
        abrirModal(accion, row = {}) {
            this.errores = {};
            this.errorMostrarMsjMotivoGasto = [];
            this.tipoAccion = accion === 'modificar' ? 2 : 1;
            this.datos = this.tipoAccion === 2
                ? { id: row.id, nombre: row.nombre || '', descripcion: row.descripcion || '' }
                : formularioVacio();
            this.modal = true;
        },
        cerrarModal() {
            if (this.isSaving) return;
            this.modal = false;
            this.datos = formularioVacio();
            this.errores = {};
            this.errorMostrarMsjMotivoGasto = [];
        },
        validar() {
            this.errorMostrarMsjMotivoGasto = [];
            if (!this.datos.nombre || !this.datos.nombre.trim()) {
                this.errorMostrarMsjMotivoGasto.push('El nombre del motivo no puede estar vacío.');
            }
            return this.errorMostrarMsjMotivoGasto.length === 0;
        },
        async guardarMotivoGasto() {
            if (!this.validar()) return;
            await this.persistir('/motivo_gasto/guardar', 'post', 'Motivo registrado');
        },
        async modificarMotivoGasto() {
            if (!this.validar()) return;
            await this.persistir('/motivo_gasto/modificar', 'put', 'Motivo actualizado');
        },
        async persistir(url, method, successTitle) {
            this.isSaving = true;
            this.errores = {};
            try {
                const { data } = await axios[method](url, this.datos);
                if (data && data.error === 0) {
                    toast.fire({ icon: 'warning', title: 'Ya existe un motivo con ese nombre' });
                    return;
                }
                this.modal = false;
                this.datos = formularioVacio();
                toast.fire({ icon: 'success', title: successTitle });
                await this.listarMotivoGasto(1);
            } catch (error) {
                this.errores = error.response && error.response.data ? (error.response.data.errors || {}) : {};
                if (!Object.keys(this.errores).length) {
                    toast.fire({ icon: 'error', title: 'No se pudo guardar el motivo' });
                }
            } finally {
                this.isSaving = false;
            }
        },
    },
    mounted() {
        this.listarMotivoGasto();
    },
    beforeDestroy() {
        clearTimeout(this.searchTimer);
        this.requestSequence += 1;
    },
};
</script>

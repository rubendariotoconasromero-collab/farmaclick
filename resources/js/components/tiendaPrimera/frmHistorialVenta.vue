<template>
    <store-one-sales-history-workspace
        :mode="mode"
        :rows="arrayVenta"
        :details="arrayDetalle"
        :datos="datos"
        :pagination="pagination"
        :pages="pagesNumber"
        :filters="filters"
        :customers="filterCustomers"
        :payment-forms="filterPaymentForms"
        :customer-loading="filterCustomerLoading"
        :initial-loading="initialLoading"
        :loading="loading"
        :detail-loading="detailLoading"
        :saving="saving"
        :deleting="eliminandoDetalle"
        :deleting-ids="detalleEliminandoIds"
        :pending-return-count="returnDetailIds.length"
        :is-administrator="isAdministrator"
        :voiding-ids="voidingSaleIds"
        :printing="printing"
        @update-filter="actualizarFiltro"
        @search-customer="buscarClientesFiltro"
        @apply-filters="aplicarFiltros"
        @clear-filters="limpiarFiltros"
        @remove-filter="quitarFiltro"
        @refresh="refreshHistory"
        @page="cambiarPagina"
        @view="verVenta"
        @return="verModificar"
        @void="anularVenta"
        @back="volverVentaListado"
        @print="cargarPdf(datos.id, datos.foto)"
        @remove-detail="eliminarDetalleDb"
        @update-cash="updateReturnCash"
        @save-return="modificarCantidad"
    />
</template>

<script>
import Swal from '../../utils/appSwal';
import StoreOneSalesHistoryWorkspace from '../sales/store-one/StoreOneSalesHistoryWorkspace.vue';

const emptyPagination = () => ({
    total: 0,
    current_page: 1,
    per_page: 0,
    last_page: 1,
    from: 0,
    to: 0,
});

const emptySale = () => ({
    id: 0,
    fecha: '',
    sub_total: 0,
    descuento: 0,
    total: 0,
    totalAux: 0,
    total_efectivo: 0,
    total_deposito: 0,
    total_efectivo_aux: 0,
    total_deposito_aux: 0,
    cliente: '',
    tipoPago: '',
    formaPago: '',
    id_descuento: 0,
    id_usuario: 0,
    id_venta: 0,
    foto: '',
    empresa_nombre: '',
    empresa_direccion: '',
    estado: '',
});

export default {
    name: 'FrmTiendaPrimeraHistorialVenta',
    components: { StoreOneSalesHistoryWorkspace },
    data() {
        return {
            mode: 'list',
            datos: emptySale(),
            arrayVenta: [],
            arrayDetalle: [],
            arrayDetalle2: [],
            arrayEmpresa: [],
            pagination: emptyPagination(),
            usuario: {},
            filters: {
                fecha_desde: '',
                fecha_hasta: '',
                cliente_id: '',
                estado: '',
                formas_pago: [],
            },
            filterCustomers: [{ id: 1, nombre: 'Público general' }],
            filterPaymentForms: [],
            filterCustomerLoading: false,
            filterCustomerTimer: null,
            filterCustomerSequence: 0,
            initialLoading: true,
            offset: 3,
            loading: false,
            detailLoading: false,
            saving: false,
            printing: false,
            eliminandoDetalle: false,
            detalleEliminandoIds: [],
            returnDetailIds: [],
            voidingSaleIds: [],
            requestSequence: 0,
        };
    },
    computed: {
        isAdministrator() {
            return Number(this.usuario.id_grupo) === 1;
        },
        pagesNumber() {
            if (!this.pagination.to || this.pagination.last_page <= 1) {
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
        detailSubtotal() {
            return this.arrayDetalle.reduce(
                (sum, detail) => sum + Number(detail.costo_venta || 0) * Number(detail.cantidad || 0),
                0,
            );
        },
        detailTotal() {
            return Math.max(0, this.detailSubtotal - Number(this.datos.descuento || 0));
        },
    },
    methods: {
        filtrosConsulta(page) {
            const params = { page };
            if (this.filters.fecha_desde) params.fecha_desde = this.filters.fecha_desde;
            if (this.filters.fecha_hasta) params.fecha_hasta = this.filters.fecha_hasta;
            if (this.filters.cliente_id) params.cliente_id = this.filters.cliente_id;
            if (this.filters.estado) params.estado = this.filters.estado;
            if (this.filters.formas_pago.length) params.formas_pago = this.filters.formas_pago;
            return params;
        },
        consultaRouter(page) {
            const query = {};
            if (this.filters.fecha_desde) query.desde = this.filters.fecha_desde;
            if (this.filters.fecha_hasta) query.hasta = this.filters.fecha_hasta;
            if (this.filters.cliente_id) query.cliente = String(this.filters.cliente_id);
            if (this.filters.estado) query.estado = this.filters.estado;
            if (this.filters.formas_pago.length) query.formas = this.filters.formas_pago.join(',');
            if (Number(page) > 1) query.page = String(page);
            return query;
        },
        sincronizarFiltrosRouter(page) {
            if (!this.$router || !this.$route) return;
            this.$router.replace({ name: this.$route.name, query: this.consultaRouter(page) }).catch(() => {});
        },
        cargarFiltrosRouter() {
            const query = this.$route ? this.$route.query : {};
            this.filters.fecha_desde = query.desde || '';
            this.filters.fecha_hasta = query.hasta || '';
            this.filters.cliente_id = query.cliente || '';
            this.filters.estado = ['Entregado', 'Devolucion', 'Anulado'].includes(query.estado) ? query.estado : '';
            this.filters.formas_pago = query.formas
                ? String(query.formas).split(',').filter(Boolean).map(Number).filter(Number.isFinite)
                : [];
        },
        normalizarFiltros() {
            const formasPermitidas = this.filterPaymentForms.map(item => String(item.id));
            this.filters.formas_pago = this.filters.formas_pago.filter(id => formasPermitidas.includes(String(id)));
            if (this.filters.cliente_id && !Number.isInteger(Number(this.filters.cliente_id))) {
                this.filters.cliente_id = '';
            }
        },
        actualizarFiltro({ key, value }) {
            this.$set(this.filters, key, value);
        },
        aplicarFiltros() {
            if (this.filters.fecha_desde && this.filters.fecha_hasta && this.filters.fecha_desde > this.filters.fecha_hasta) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Rango de fechas no válido',
                    text: 'La fecha inicial no puede ser posterior a la fecha final.',
                });
                return;
            }
            this.sincronizarFiltrosRouter(1);
            this.listarVenta(1);
        },
        limpiarFiltros() {
            this.filters = { fecha_desde: '', fecha_hasta: '', cliente_id: '', estado: '', formas_pago: [] };
            this.filterCustomers = [{ id: 1, nombre: 'Público general' }];
            this.sincronizarFiltrosRouter(1);
            this.listarVenta(1);
        },
        quitarFiltro(key) {
            if (key === 'fechas') {
                this.filters.fecha_desde = '';
                this.filters.fecha_hasta = '';
            } else if (key === 'formas_pago') {
                this.filters.formas_pago = [];
            } else {
                this.filters[key] = '';
            }
            this.aplicarFiltros();
        },
        async cargarFormasPagoFiltro() {
            try {
                const { data } = await axios.get('/formaPago/selectFormaP');
                this.filterPaymentForms = Array.isArray(data) ? data : [];
            } catch (error) {
                this.filterPaymentForms = [];
                console.error(error);
            }
        },
        async cargarClienteFiltroSeleccionado() {
            if (!this.filters.cliente_id || Number(this.filters.cliente_id) === 1) return;
            try {
                const { data } = await axios.get('/cliente/selectClienteId', {
                    params: { id_cliente: this.filters.cliente_id },
                });
                const customer = (Array.isArray(data) ? data : []).find(
                    item => String(item.id) === String(this.filters.cliente_id),
                );
                if (customer) this.filterCustomers = [this.filterCustomers[0], customer];
            } catch (error) {
                console.error(error);
            }
        },
        buscarClientesFiltro(term) {
            const sequence = ++this.filterCustomerSequence;
            window.clearTimeout(this.filterCustomerTimer);
            if (!String(term || '').trim()) {
                const selected = this.filterCustomers.find(item => String(item.id) === String(this.filters.cliente_id));
                this.filterCustomers = [{ id: 1, nombre: 'Público general' }, ...(selected && Number(selected.id) !== 1 ? [selected] : [])];
                this.filterCustomerLoading = false;
                return;
            }
            this.filterCustomerTimer = window.setTimeout(async () => {
                this.filterCustomerLoading = true;
                try {
                    const { data } = await axios.get('/cliente/selectCliente', { params: { filtro: term } });
                    if (sequence !== this.filterCustomerSequence) return;
                    const rows = Array.isArray(data.data) ? data.data : (Array.isArray(data) ? data : []);
                    const publicCustomer = { id: 1, nombre: 'Público general' };
                    const selected = this.filterCustomers.find(item => String(item.id) === String(this.filters.cliente_id));
                    const merged = [publicCustomer, ...(selected ? [selected] : []), ...rows];
                    this.filterCustomers = merged.filter((item, index, items) => items.findIndex(candidate => String(candidate.id) === String(item.id)) === index);
                } catch (error) {
                    if (sequence === this.filterCustomerSequence) this.filterCustomers = [{ id: 1, nombre: 'Público general' }];
                    console.error(error);
                } finally {
                    if (sequence === this.filterCustomerSequence) this.filterCustomerLoading = false;
                }
            }, 250);
        },
        async usuarioAuth() {
            try {
                const response = await axios.get('/usuario_auth');
                this.usuario = response.data || {};
            } catch (error) {
                this.usuario = {};
                console.error(error);
            }
        },
        async listarVenta(page = 1) {
            const sequence = ++this.requestSequence;
            this.loading = true;

            try {
                const response = await axios.get('/venta_tienda1', {
                    params: this.filtrosConsulta(page),
                });

                if (sequence !== this.requestSequence) {
                    return;
                }

                this.arrayVenta = Array.isArray(response.data.data) ? response.data.data : [];
                this.pagination = {
                    total: Number(response.data.total || 0),
                    current_page: Number(response.data.current_page || 1),
                    per_page: Number(response.data.per_page || 0),
                    last_page: Number(response.data.last_page || 1),
                    from: Number(response.data.from || 0),
                    to: Number(response.data.to || 0),
                };
            } catch (error) {
                if (sequence === this.requestSequence) {
                    this.arrayVenta = [];
                    this.pagination = emptyPagination();
                    this.showError('No fue posible cargar el historial de ventas.');
                }
                console.error(error);
            } finally {
                if (sequence === this.requestSequence) {
                    this.loading = false;
                }
            }
        },
        refreshHistory() {
            this.listarVenta(this.pagination.current_page || 1);
        },
        cambiarPagina(page) {
            if (page < 1 || page > this.pagination.last_page || page === this.pagination.current_page) {
                return;
            }
            this.sincronizarFiltrosRouter(page);
            this.listarVenta(page);
        },
        hydrateSale(sale) {
            this.datos = {
                ...emptySale(),
                id: sale.id,
                id_venta: sale.id,
                cliente: sale.cliente,
                fecha: sale.fecha,
                descuento: Number(sale.descuento || 0),
                tipoPago: sale.tipoP || '',
                formaPago: sale.formaP || '',
                id_descuento: sale.id_descuento || 0,
                id_usuario: sale.id_usuario || 0,
                total: Number(sale.total || 0),
                totalAux: Number(sale.total || 0),
                sub_total: Number(sale.sub_total || 0),
                total_efectivo: Number(sale.total_efectivo || 0),
                total_deposito: Number(sale.total_deposito || 0),
                total_efectivo_aux: Number(sale.total_efectivo || 0),
                total_deposito_aux: Number(sale.total_deposito || 0),
                estado: sale.estado || '',
            };
        },
        async loadSaleDetails(saleId, includePackages = true) {
            const requests = [axios.get('/venta/permiso/detalle_tienda1', { params: { id: saleId } })];

            if (includePackages) {
                requests.push(axios.get('/paquete/permiso/detalle/venta', { params: { id: saleId } }));
            }

            const responses = await Promise.all(requests);
            const details = Array.isArray(responses[0].data) ? responses[0].data : [];
            const packages = includePackages && Array.isArray(responses[1].data)
                ? responses[1].data.map((item, index) => ({
                    ...item,
                    id: `package-${item.id_paquete}-${index}`,
                    isPackage: true,
                }))
                : [];

            this.arrayDetalle2 = packages;
            this.arrayDetalle = [
                ...details.map(item => ({ ...item, isPackage: false })),
                ...packages,
            ];
        },
        async loadCompanyForDetails() {
            const storeId = this.arrayDetalle.length ? Number(this.arrayDetalle[0].id_tienda) : 0;
            if (!storeId) {
                return;
            }

            try {
                const response = await axios.get('/tienda', {
                    params: { page: 1, buscar: '', criterio: 'nombre' },
                });
                this.arrayEmpresa = Array.isArray(response.data.data) ? response.data.data : [];
                const company = this.arrayEmpresa.find(item => Number(item.id) === storeId);

                if (company) {
                    this.datos.foto = company.foto || '';
                    this.datos.empresa_nombre = company.nombre || '';
                    this.datos.empresa_direccion = company.direccion || '';
                }
            } catch (error) {
                console.error(error);
            }
        },
        async verVenta(sale) {
            this.hydrateSale(sale);
            this.mode = 'detail';
            this.detailLoading = true;
            this.arrayDetalle = [];

            try {
                await this.loadSaleDetails(sale.id, true);
                await this.loadCompanyForDetails();
            } catch (error) {
                this.showError('No fue posible cargar el detalle de la venta.');
                console.error(error);
            } finally {
                this.detailLoading = false;
            }
        },
        async verModificar(sale) {
            this.hydrateSale(sale);
            this.mode = 'return';
            this.detailLoading = true;
            this.arrayDetalle = [];
            this.returnDetailIds = [];

            try {
                await this.loadSaleDetails(sale.id, true);
            } catch (error) {
                this.showError('No fue posible preparar la devolución.');
                console.error(error);
            } finally {
                this.detailLoading = false;
            }
        },
        volverVentaListado() {
            this.mode = 'list';
            this.arrayDetalle = [];
            this.arrayDetalle2 = [];
            this.returnDetailIds = [];
            this.datos = emptySale();
        },
        async eliminarDetalleDb(detail) {
            if (
                this.eliminandoDetalle
                || this.detalleEliminandoIds.includes(detail.id)
                || detail.isPackage
            ) {
                return;
            }

            const confirmation = await Swal.fire({
                title: '¿Devolver este producto?',
                text: 'El producto se preparará para devolución. Los cambios se aplicarán al confirmar.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Preparar devolución',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
            });

            if (!confirmation.isConfirmed) {
                return;
            }

            this.returnDetailIds.push(Number(detail.id));
            this.arrayDetalle = this.arrayDetalle.filter(item => item.id !== detail.id);
            this.syncTotals();
        },
        syncTotals() {
            this.datos.sub_total = Number(this.detailSubtotal.toFixed(2));
            this.datos.total = Number(this.detailTotal.toFixed(2));

            if (this.datos.formaPago === 'Mixta') {
                this.datos.total_efectivo = Number(
                    Math.min(this.datos.total, Number(this.datos.total_efectivo || 0)).toFixed(2),
                );
                this.datos.total_deposito = Number(
                    Math.max(0, this.datos.total - Number(this.datos.total_efectivo || 0)).toFixed(2),
                );
            }
        },
        updateReturnCash(value) {
            const normalized = Math.max(0, Math.min(this.detailTotal, Number(value || 0)));
            this.datos.total_efectivo = Number(normalized.toFixed(2));
            this.datos.total_deposito = Number(
                Math.max(0, this.detailTotal - this.datos.total_efectivo).toFixed(2),
            );
        },
        async modificarCantidad() {
            if (!this.returnDetailIds.length || this.saving) {
                return;
            }

            this.syncTotals();
            this.saving = true;

            try {
                await axios.put('/venta/modificarVenta', {
                    id_venta: this.datos.id_venta,
                    returned_detail_ids: this.returnDetailIds,
                    total_efectivo: this.datos.total_efectivo,
                });
                await Swal.fire({
                    icon: 'success',
                    title: 'Devolución actualizada',
                    text: 'Los totales y las cantidades fueron actualizados correctamente.',
                    timer: 1400,
                    showConfirmButton: false,
                });
                this.volverVentaListado();
                await this.listarVenta(1);
            } catch (error) {
                this.showError(this.errorMessage(error, 'No fue posible actualizar la devolución.'));
                console.error(error);
            } finally {
                this.saving = false;
            }
        },
        async anularVenta(sale) {
            if (this.voidingSaleIds.includes(sale.id)) {
                return;
            }

            const confirmation = await Swal.fire({
                title: '¿Anular esta venta?',
                text: 'Esta decisión no se puede revertir.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, anular',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
            });

            if (!confirmation.isConfirmed) {
                return;
            }

            this.voidingSaleIds.push(sale.id);
            try {
                await axios.put('/venta/anular_tienda1', {
                    id: sale.id,
                });
                await this.listarVenta(this.pagination.current_page || 1);
                await Swal.fire({
                    icon: 'success',
                    title: 'Venta anulada',
                    timer: 1200,
                    showConfirmButton: false,
                });
            } catch (error) {
                this.showError(this.errorMessage(error, 'No fue posible anular la venta.'));
                console.error(error);
            } finally {
                this.voidingSaleIds = this.voidingSaleIds.filter(id => id !== sale.id);
            }
        },
        async cargarPdf(id, foto) {
            if (!id || this.printing) {
                return;
            }

            this.printing = true;
            try {
                const response = await axios.get('/venta/pdfVentasGeneral', {
                    params: { id, foto },
                    responseType: 'blob',
                });
                const url = URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
                window.open(url, '_blank');
                window.setTimeout(() => URL.revokeObjectURL(url), 60000);
            } catch (error) {
                this.showError('No fue posible generar el reporte de la venta.');
                console.error(error);
            } finally {
                this.printing = false;
            }
        },
        showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un problema',
                text: message,
            });
        },
        errorMessage(error, fallback) {
            const response = error && error.response && error.response.data;
            if (response && response.errors) {
                const firstGroup = Object.values(response.errors)[0];
                if (Array.isArray(firstGroup) && firstGroup.length) return firstGroup[0];
            }
            return (response && response.message) || fallback;
        },
    },
    async mounted() {
        this.initialLoading = true;
        await this.cargarFormasPagoFiltro();
        this.cargarFiltrosRouter();
        this.normalizarFiltros();
        await this.cargarClienteFiltroSeleccionado();
        const initialPage = Math.max(1, Number(this.$route && this.$route.query.page) || 1);
        await Promise.all([this.listarVenta(initialPage), this.usuarioAuth()]);
        this.initialLoading = false;
    },
    beforeDestroy() {
        window.clearTimeout(this.filterCustomerTimer);
        this.requestSequence += 1;
        this.filterCustomerSequence += 1;
    },
};
</script>

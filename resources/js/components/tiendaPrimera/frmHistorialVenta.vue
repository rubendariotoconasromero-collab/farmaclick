<template>
    <store-one-sales-history-workspace
        :mode="mode"
        :rows="arrayVenta"
        :details="arrayDetalle"
        :datos="datos"
        :pagination="pagination"
        :pages="pagesNumber"
        :search="buscar"
        :loading="loading"
        :detail-loading="detailLoading"
        :saving="saving"
        :deleting="eliminandoDetalle"
        :deleting-ids="detalleEliminandoIds"
        :is-administrator="isAdministrator"
        :printing="printing"
        @update:search="buscar = $event; scheduleSearch()"
        @search="listarVenta(1)"
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
import Swal from 'sweetalert2';
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
            buscar: '',
            criterio: 'cliente.nombre',
            offset: 3,
            loading: false,
            detailLoading: false,
            saving: false,
            printing: false,
            eliminandoDetalle: false,
            detalleEliminandoIds: [],
            searchTimer: null,
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
                    params: {
                        page,
                        buscar: this.buscar,
                        criterio: this.criterio,
                    },
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
        scheduleSearch() {
            window.clearTimeout(this.searchTimer);
            this.searchTimer = window.setTimeout(() => this.listarVenta(1), 350);
        },
        refreshHistory() {
            window.clearTimeout(this.searchTimer);
            this.listarVenta(this.pagination.current_page || 1);
        },
        cambiarPagina(page) {
            if (page < 1 || page > this.pagination.last_page || page === this.pagination.current_page) {
                return;
            }
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
            const packages = includePackages && Array.isArray(responses[1].data) ? responses[1].data : [];

            this.arrayDetalle2 = packages;
            this.arrayDetalle = [...details, ...packages];
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

            try {
                await this.loadSaleDetails(sale.id, false);
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
            this.datos = emptySale();
        },
        async eliminarDetalleDb(detail) {
            if (
                this.eliminandoDetalle
                || this.detalleEliminandoIds.includes(detail.id)
                || this.arrayDetalle.length <= 1
            ) {
                return;
            }

            const confirmation = await Swal.fire({
                title: '¿Devolver este producto?',
                text: 'El producto será retirado de la venta y se actualizará el inventario.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, devolver',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
            });

            if (!confirmation.isConfirmed) {
                return;
            }

            this.eliminandoDetalle = true;
            this.detalleEliminandoIds.push(detail.id);

            try {
                await axios.put(`/detalle/eliminar?id_detalle_venta=${encodeURIComponent(detail.id)}`, {
                    id: detail.id,
                    id_usuario: this.datos.id_usuario,
                    totalAux: this.datos.totalAux,
                    total: this.datos.total,
                    id_tipo_pago: this.datos.tipoPago,
                });
                await this.loadSaleDetails(this.datos.id, true);
                this.syncTotals();
                await Swal.fire({
                    icon: 'success',
                    title: 'Producto devuelto',
                    text: 'El producto fue retirado correctamente.',
                    timer: 1100,
                    showConfirmButton: false,
                });
            } catch (error) {
                this.showError('No fue posible devolver el producto.');
                console.error(error);
            } finally {
                this.eliminandoDetalle = false;
                this.detalleEliminandoIds = this.detalleEliminandoIds.filter(id => id !== detail.id);
            }
        },
        syncTotals() {
            this.datos.sub_total = Number(this.detailSubtotal.toFixed(2));
            this.datos.total = Number(this.detailTotal.toFixed(2));

            if (this.datos.formaPago === 'Mixta') {
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
            if (!this.arrayDetalle.length || this.saving) {
                return;
            }

            if (this.arrayDetalle.some(detail => Number(detail.cantidad) - Number(detail.cantidad_auxiliar || 0) < 0)) {
                this.showError('No hay stock disponible para completar el ajuste.');
                return;
            }

            this.syncTotals();
            this.saving = true;

            try {
                await axios.put('/venta/modificarVenta', {
                    id_venta: this.datos.id_venta,
                    totalAux: this.datos.totalAux,
                    total: this.datos.total,
                    sub_total: this.datos.sub_total,
                    id_usuario: this.datos.id_usuario,
                    formaPago: this.datos.formaPago,
                    total_efectivo: this.datos.total_efectivo,
                    total_deposito: this.datos.total_deposito,
                    total_efectivo_aux: this.datos.total_efectivo_aux,
                    total_deposito_aux: this.datos.total_deposito_aux,
                    detalle: this.arrayDetalle,
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
                this.showError('No fue posible actualizar la devolución.');
                console.error(error);
            } finally {
                this.saving = false;
            }
        },
        async anularVenta(sale) {
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

            try {
                await axios.put('/venta/anular_tienda1', {
                    id: sale.id,
                    id_usuario: sale.id_usuario,
                });
                await this.listarVenta(this.pagination.current_page || 1);
                await Swal.fire({
                    icon: 'success',
                    title: 'Venta anulada',
                    timer: 1200,
                    showConfirmButton: false,
                });
            } catch (error) {
                this.showError('No fue posible anular la venta.');
                console.error(error);
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
    },
    mounted() {
        this.listarVenta(1);
        this.usuarioAuth();
    },
    beforeDestroy() {
        window.clearTimeout(this.searchTimer);
        this.requestSequence += 1;
    },
};
</script>

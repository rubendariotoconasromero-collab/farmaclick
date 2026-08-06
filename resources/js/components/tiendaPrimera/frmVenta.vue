<template>
    <main class="main">
        <store-one-sale-workspace
            :cash-state="estadoCaja"
            :cash-loading="cargandoCaja"
            :cash-error="errorCaja"
            :initial-loading="cargaInicial"
            :datos="datos"
            :datos-pago="datosPago"
            :customer-keyword="keywordCliente"
            :customers="filteredClientes"
            :customer-loading="buscandoClientes"
            :payment-types="arrayPago"
            :payment-forms="arrayFormaContado"
            :details="arrayDetalle"
            :products="arrayArticulo"
            :suppliers="arrayProveedor"
            :pagination="pagination"
            :pages="pagesNumber"
            :product-modal-open="modalP"
            :product-search="buscarP"
            :product-criterion="criterioP"
            :supplier-id="Number(datos.id_proveedor || 0)"
            :selected-product="selectedProduct"
            :quantity-open="showQuantityModal"
            :quantity="quantityInput"
            :quantity-error="quantityError"
            :subtotal="subtotalVenta"
            :total="totalVenta"
            :deposit-total="totalDeposito"
            :change="cambioVenta"
            :saving="isSaving"
            :last-sale-id="lastSaleId"
            :report-modal-open="reportModalOpen"
            :report-loading-format="reportLoadingFormat"
            @update:customerKeyword="keywordCliente = $event"
            @search-customer="buscarClientes"
            @select-customer="seleccionarCliente"
            @clear-customer="limpiarCliente"
            @open-products="abrirModalP"
            @update-line="actualizarCampoDetalle"
            @remove-line="eliminarDetalle"
            @update-summary="actualizarResumen"
            @clear="limpiarVenta"
            @save="guardarVenta"
            @update:productSearch="buscarP = $event"
            @update:productCriterion="criterioP = $event"
            @update:supplierId="filtrarProveedor"
            @search-products="listarArticulo(1, buscarP, criterioP)"
            @product-page="cambiarPagina"
            @select-product="seleccionarTiendaArticulo"
            @close-products="cerrarModalP"
            @update:quantity="quantityInput = $event"
            @cancel-quantity="cancelarCantidad"
            @confirm-quantity="confirmarCantidad"
            @retry-cash="verificarCaja"
            @open-report-modal="reportModalOpen = true"
            @close-report-modal="cerrarModalReporte"
            @generate-report="generarNotaVenta"
        />
    </main>
</template>

<script>
import Swal, { toast } from '../../utils/appSwal';
import moment from 'moment';
import StoreOneSaleWorkspace from '../sales/store-one/StoreOneSaleWorkspace.vue';

const crearDatosVenta = () => ({
    id: 0,
    fecha: moment().format('YYYY-MM-DD'),
    sub_total: 0,
    descuento: 0,
    total: 0,
    id_tipo_pago: 1,
    id_forma_pago: 2,
    id_cliente: 1,
    cliente: '',
    estado: '',
    tipo_venta: 'Venta Directa',
    tipo_producto: 'Producto Venta',
    id_proveedor: 0,
    total_efectivo: 0,
    total_deposito: 0,
    efectivo: 0,
    cambio: 0,
    id_descuento: 1,
});

const crearDatosPago = () => ({
    fecha_final: moment().format('YYYY-MM-DD'),
    saldo: 0,
    descripcion: '',
});

export default {
    components: { StoreOneSaleWorkspace },
    data() {
        return {
            keywordCliente: '',
            filteredClientes: [],
            customerSearchTimer: null,
            customerSearchSequence: 0,
            buscandoClientes: false,
            showQuantityModal: false,
            quantityInput: '',
            quantityError: '',
            selectedProduct: {},
            modalP: false,
            isSaving: false,
            lastSaleId: 0,
            reportModalOpen: false,
            reportLoadingFormat: '',
            datos: crearDatosVenta(),
            datosPago: crearDatosPago(),
            arrayDetalle: [],
            arrayArticulo: [],
            arrayProductoPaquete: [],
            arrayPago: [],
            arrayForma: [],
            arrayProveedor: [],
            estadoCaja: '',
            cargandoCaja: true,
            errorCaja: '',
            cargaInicial: true,
            criterioP: 'articulo.nombre_comercial',
            buscarP: '',
            pagination: {
                total: 0, current_page: 0, per_page: 0,
                last_page: 0, from: 0, to: 0,
            },
            offset: 3,
        };
    },
    computed: {
        arrayFormaContado() {
            return this.arrayForma.filter(item => Number(item.id) !== 1);
        },
        subtotalVenta() {
            return Number(this.arrayDetalle.reduce(
                (sum, item) => sum + Number(item.sub_total || 0),
                0,
            ).toFixed(2));
        },
        totalVenta() {
            return Number((this.subtotalVenta - Number(this.datos.descuento || 0)).toFixed(2));
        },
        totalDeposito() {
            return Number((this.totalVenta - Number(this.datos.total_efectivo || 0)).toFixed(2));
        },
        cambioVenta() {
            const base = Number(this.datos.id_forma_pago) === 6
                ? Number(this.datos.total_efectivo || 0)
                : this.totalVenta;
            return Math.max(0, Number(this.datos.efectivo || 0) - base);
        },
        pagesNumber() {
            if (!this.pagination.to) return [];
            let from = Math.max(1, this.pagination.current_page - this.offset);
            const to = Math.min(this.pagination.last_page, from + (this.offset * 2));
            const pages = [];
            while (from <= to) pages.push(from++);
            return pages;
        },
    },
    methods: {
        buscarClientes(busqueda) {
            const sequence = ++this.customerSearchSequence;
            this.datos.id_cliente = 0;
            this.datos.cliente = busqueda;
            clearTimeout(this.customerSearchTimer);
            this.buscandoClientes = false;

            if (!String(busqueda || '').trim()) {
                this.filteredClientes = [];
                return;
            }

            this.customerSearchTimer = setTimeout(async () => {
                this.buscandoClientes = true;
                try {
                    const { data } = await axios.get('/cliente/selectCliente', {
                        params: { filtro: busqueda },
                    });
                    if (sequence === this.customerSearchSequence) {
                        this.filteredClientes = data.data || data;
                    }
                } catch (error) {
                    console.error(error);
                    if (sequence === this.customerSearchSequence) {
                        this.filteredClientes = [];
                    }
                } finally {
                    if (sequence === this.customerSearchSequence) {
                        this.buscandoClientes = false;
                    }
                }
            }, 250);
        },
        seleccionarCliente(cliente) {
            this.customerSearchSequence += 1;
            clearTimeout(this.customerSearchTimer);
            this.buscandoClientes = false;
            this.keywordCliente = cliente.nombre;
            this.datos.id_cliente = Number(cliente.id);
            this.datos.cliente = cliente.nombre;
            this.datos.id_descuento = [1, 2, 3].includes(Number(cliente.descuento))
                ? Number(cliente.descuento)
                : 1;
            this.filteredClientes = [];
        },
        limpiarCliente() {
            this.customerSearchSequence += 1;
            clearTimeout(this.customerSearchTimer);
            this.buscandoClientes = false;
            this.keywordCliente = '';
            this.datos.id_cliente = 1;
            this.datos.cliente = 'Público General';
            this.datos.id_descuento = 1;
            this.filteredClientes = [];
        },
        seleccionarTiendaArticulo(producto) {
            if (this.arrayDetalle.some(item => Number(item.id_lote) === Number(producto.id))) {
                toast.fire({ icon: 'error', title: 'Este lote ya se encuentra agregado.' });
                return;
            }
            this.selectedProduct = { ...producto };
            this.quantityInput = '';
            this.quantityError = '';
            this.showQuantityModal = true;
        },
        cancelarCantidad() {
            this.showQuantityModal = false;
            this.quantityInput = '';
            this.quantityError = '';
            this.selectedProduct = {};
        },
        confirmarCantidad() {
            const cantidad = Number(this.quantityInput);
            if (!Number.isInteger(cantidad) || cantidad <= 0) {
                this.quantityError = 'La cantidad debe ser un número entero mayor a cero.';
                return;
            }
            if (cantidad > Number(this.selectedProduct.stock)) {
                this.quantityError = 'La cantidad supera el stock disponible.';
                return;
            }

            const detalle = {
                ...this.selectedProduct,
                id_lote: Number(this.selectedProduct.id),
                id_tienda_articulo: Number(this.selectedProduct.id_articulo),
                cantidad,
                contador: 0,
                venta_cantidad: Number(this.selectedProduct.venta_presentacion || 1),
                costo_venta: Number(this.selectedProduct.costo_unitario || 0),
                descuento_stock: 0,
                sub_total: 0,
                producto_venta: 'Venta Producto',
                stock: Number(this.selectedProduct.stock || 0),
            };
            this.actualizarLineaDetalle(detalle);
            this.arrayDetalle.push(detalle);
            this.datos.estado = 'Entregado';
            this.cancelarCantidad();
            toast.fire({ icon: 'success', title: 'Producto agregado.' });
        },
        actualizarCampoDetalle({ row, field, value }) {
            this.$set(row, field, value === '' ? '' : Number(value));
            this.actualizarLineaDetalle(row);
        },
        actualizarLineaDetalle(detalle) {
            const presentacion = Number(detalle.contador);
            const cantidad = Number(detalle.cantidad || 0);
            const configuraciones = {
                0: {
                    factor: Number(detalle.venta_presentacion || 1),
                    precio: Number(detalle.costo_unitario || 0),
                },
                1: {
                    factor: Number(detalle.cantidad_blister || 0),
                    precio: Number(detalle.precio_blister || 0),
                },
                2: {
                    factor: Number(detalle.cantidad_caja || 0),
                    precio: Number(detalle.precio_caja || 0),
                },
            };
            const configuracion = configuraciones[presentacion] || configuraciones[0];
            detalle.venta_cantidad = configuracion.factor;
            detalle.descuento_stock = cantidad * configuracion.factor;
            detalle.costo_venta = configuracion.precio;
            detalle.sub_total = Number((cantidad * configuracion.precio).toFixed(2));
        },
        eliminarDetalle(index) {
            const detalle = this.arrayDetalle[index];
            if (detalle && detalle.producto_venta === 'Venta Paquete') {
                this.arrayProductoPaquete = this.arrayProductoPaquete.filter(
                    paquete => paquete.id_paquete != detalle.id_paquete,
                );
            }
            this.arrayDetalle.splice(index, 1);
        },
        actualizarResumen({ field, value }) {
            this.$set(this.datos, field, Number(value || 0));
        },
        async listarArticulo(page = 1, buscar = '', criterio = this.criterioP) {
            try {
                const { data } = await axios.get('/tienda/listarSinPaginateVenta', {
                    params: {
                        page,
                        buscar,
                        criterio,
                        id_proveedor: Number(this.datos.id_proveedor || 0),
                    },
                });
                this.arrayArticulo = data.data || [];
                this.pagination = {
                    total: data.total, current_page: data.current_page,
                    per_page: data.per_page, last_page: data.last_page,
                    from: data.from, to: data.to,
                };
            } catch (error) {
                console.error(error);
                this.mostrarAlerta('No fue posible consultar el inventario.');
            }
        },
        abrirModalP() {
            this.buscarP = '';
            this.modalP = true;
            this.listarArticulo(1, '', this.criterioP);
        },
        cerrarModalP() {
            this.modalP = false;
            this.buscarP = '';
        },
        cambiarPagina(page) {
            this.listarArticulo(page, this.buscarP, this.criterioP);
        },
        filtrarProveedor(id) {
            this.datos.id_proveedor = Number(id);
            this.listarArticulo(1, this.buscarP, this.criterioP);
        },
        validarVenta() {
            if (this.estadoCaja !== 'Abierta') return 'Debe aperturar la caja antes de vender.';
            if (!this.arrayDetalle.length) return 'Debe agregar al menos un producto.';
            if (this.arrayDetalle.some(item => !Number.isInteger(Number(item.cantidad)) || Number(item.cantidad) <= 0)) return 'Existe una cantidad inválida.';
            if (this.arrayDetalle.some(item => Number(item.descuento_stock) > Number(item.stock))) return 'Existe un producto con stock insuficiente.';
            if (Number(this.datos.descuento) < 0 || Number(this.datos.descuento) > this.subtotalVenta) return 'El descuento no es válido.';
            if (this.totalVenta < 0) return 'El total no puede ser negativo.';
            if (![1, 2].includes(Number(this.datos.id_tipo_pago))) return 'Seleccione el tipo de pago.';
            if (Number(this.datos.id_tipo_pago) === 1 && !Number(this.datos.id_forma_pago)) return 'Seleccione la forma de pago.';
            if (Number(this.datos.id_tipo_pago) === 2 && this.datosPago.fecha_final < this.datos.fecha) return 'El vencimiento no puede ser anterior a la venta.';
            if (Number(this.datos.id_forma_pago) === 6 && (Number(this.datos.total_efectivo) < 0 || Number(this.datos.total_efectivo) > this.totalVenta)) return 'La parte en efectivo del pago mixto no es válida.';
            return '';
        },
        async guardarVenta() {
            if (this.isSaving) return;

            if (!String(this.keywordCliente || '').trim()) {
                this.datos.cliente = 'SN';
                this.datos.id_cliente = 1;
            } else {
                this.datos.cliente = this.keywordCliente.trim();
                if (!this.datos.id_cliente || (this.datos.id_cliente === 1 && this.datos.cliente !== 'SN')) {
                    this.datos.id_cliente = 0;
                }
            }

            const error = this.validarVenta();
            if (error) {
                this.mostrarAlerta(error);
                return;
            }

            this.datos.sub_total = this.subtotalVenta;
            this.datos.total = this.totalVenta;
            this.datos.total_deposito = Number(this.datos.id_forma_pago) === 6
                ? this.totalDeposito
                : ([3, 4, 5].includes(Number(this.datos.id_forma_pago)) ? this.totalVenta : 0);
            this.datos.cambio = this.cambioVenta;
            this.isSaving = true;

            Swal.fire({
                title: 'Procesando venta',
                html: 'Guardando información…',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
            });

            try {
                const response = await axios.post('/venta/guardar_tienda1', {
                    ...this.datos,
                    id_servicio: this.datos.id,
                    fecha_final: this.datosPago.fecha_final,
                    descripcion_pago: this.datosPago.descripcion,
                    saldo: this.totalVenta,
                    detalle: this.arrayDetalle,
                    monto_total: this.totalVenta,
                    id_forma_pago: Number(this.datos.id_tipo_pago) === 2
                        ? Number(this.arrayForma[0] ? this.arrayForma[0].id : 1)
                        : Number(this.datos.id_forma_pago),
                    id_costo_pago: Number(this.datos.id_descuento || 1),
                    stock_producto_paquete: this.arrayProductoPaquete,
                });

                const saleId = Number(response.data && response.data.id);
                if (!saleId) {
                    throw new Error('La venta fue procesada sin devolver un identificador.');
                }

                this.lastSaleId = saleId;
                await Swal.fire({
                    icon: 'success',
                    title: 'Venta registrada',
                    showConfirmButton: false,
                    timer: 1100,
                });
                this.limpiarVenta();
                await this.listarArticulo();
                this.reportModalOpen = true;
            } catch (requestError) {
                console.error(requestError);
                const message = requestError.response && requestError.response.data
                    ? (requestError.response.data.message || requestError.response.data.error)
                    : '';
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo registrar la venta',
                    text: message || 'Revise la información e intente nuevamente.',
                });
            } finally {
                this.isSaving = false;
            }
        },
        mostrarAlerta(mensaje) {
            Swal.fire({ icon: 'error', title: 'Revise la venta', text: mensaje });
        },
        cerrarModalReporte() {
            if (!this.reportLoadingFormat) {
                this.reportModalOpen = false;
            }
        },
        async generarNotaVenta(formato) {
            if (!this.lastSaleId || !['carta', 'ticket'].includes(formato) || this.reportLoadingFormat) return;

            const reportWindow = window.open('', '_blank');
            this.reportLoadingFormat = formato;
            try {
                const response = await axios.get(`/venta/tienda1/${this.lastSaleId}/nota/${formato}`, {
                    responseType: 'blob',
                });
                const reportUrl = URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
                if (reportWindow) reportWindow.location.href = reportUrl;
                else window.open(reportUrl, '_blank');
                this.reportModalOpen = false;
                window.setTimeout(() => URL.revokeObjectURL(reportUrl), 60000);
            } catch (error) {
                if (reportWindow) reportWindow.close();
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'No se pudo generar la nota',
                    text: 'Intente nuevamente o seleccione el otro formato.',
                });
            } finally {
                this.reportLoadingFormat = '';
            }
        },
        limpiarVenta() {
            this.datos = crearDatosVenta();
            this.datosPago = crearDatosPago();
            this.keywordCliente = '';
            this.filteredClientes = [];
            this.arrayDetalle = [];
            this.arrayProductoPaquete = [];
            this.buscarP = '';
            if (this.arrayFormaContado.length) {
                this.datos.id_forma_pago = Number(this.arrayFormaContado[0].id);
            }
        },
        async cargarCatalogos() {
            try {
                const [pagos, formas, proveedores] = await Promise.all([
                    axios.get('/tipoPago/selectTipoP'),
                    axios.get('/formaPago/selectFormaP'),
                    axios.get('/proveedor/selectProveedor'),
                ]);
                this.arrayPago = pagos.data;
                this.arrayForma = formas.data;
                this.arrayProveedor = proveedores.data;
            } catch (error) {
                console.error(error);
                this.mostrarAlerta('No fue posible cargar los catálogos de venta.');
            }
        },
        async verificarCaja() {
            if (this.cargandoCaja && !this.cargaInicial) return;

            this.cargandoCaja = true;
            this.errorCaja = '';
            try {
                const { data } = await axios.get('/arqueo_caja/estado_caja');
                const estado = data && data.estado;

                if (!['Abierta', 'Cerrada'].includes(estado)) {
                    throw new Error('La respuesta del estado de caja no es valida.');
                }

                this.estadoCaja = estado;
            } catch (error) {
                console.error(error);
                this.estadoCaja = '';
                this.errorCaja = 'No fue posible consultar el estado de la caja.';
            } finally {
                this.cargandoCaja = false;
            }
        },
    },
    async mounted() {
        try {
            await Promise.all([
                this.cargarCatalogos(),
                this.verificarCaja(),
            ]);
        } finally {
            this.cargaInicial = false;
        }
    },
    beforeDestroy() {
        clearTimeout(this.customerSearchTimer);
        this.customerSearchSequence += 1;
    },
};
</script>

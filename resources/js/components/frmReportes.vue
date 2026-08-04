<template>
    <main class="main">
        <report-center-workspace
            :loading="catalogLoading"
            :dates="datos"
            :cash-filters="filtros"
            :base-sections="reportSections"
            :dated-sections="reportSectionsFecha"
            :user-reports="userReports"
            :client-reports="clienteReports"
            :laboratory-reports="proveedorReports"
            :users="arrayUsuario"
            :selected-user="usuarioSeleccionado"
            :selected-client="clienteSeleccionado"
            :selected-laboratories="laboratoriosSeleccionados"
            :cash-records="arqueos"
            :cash-loading="arqueosLoading"
            @run-report="generarReporte"
            @select-entity="seleccionarEntidadReporte"
            @load-cash="cargarArqueos"
            @download-cash="descargarReporteArqueo"
        />
    </main>
</template>

<script>
import moment from 'moment';
import Swal, { closeAlert, showProcessingAlert, toast } from '../utils/appSwal';

const report = (label, url, icon, mode = 'static', filename = '') => ({
    label,
    url,
    icon: icon.startsWith('/') ? icon : `/${icon}`,
    mode,
    filename,
});

export default {
    name: 'FrmReportes',
    data() {
        return {
            catalogLoading: true,
            arqueosLoading: false,
            usuarioSeleccionado: null,
            clienteSeleccionado: null,
            laboratoriosSeleccionados: [],
            filtros: {
                fecha_inicio: moment().subtract(7, 'days').format('YYYY-MM-DD'),
                fecha_fin: moment().format('YYYY-MM-DD'),
                id_usuario: '',
            },
            datos: {
                fecha_inicio: moment().format('YYYY-MM-DD'),
                fecha_fin: moment().format('YYYY-MM-DD'),
                id_usuario: 0,
                id_cliente: 0,
                anio: moment().format('YYYY'),
            },
            datosTienda: {
                tipo_venta: 'Venta Directa',
                id_tienda: 1,
            },
            arrayUsuario: [],
            arqueos: [],
            reportSections: [
                {
                    title: 'Productos',
                    reports: [
                        report('Producto', '/reporte/ExcelProductoInventario', 'icons/basket.svg', 'excel', 'Productos.xlsx'),
                        report('Laboratorio', '/reporte/pdfProveedor', 'icons/beaker.svg'),
                        report('Inventario Bs', '/reporte/ExcelProducto', 'icons/storage.svg', 'excel', 'Inventario.xlsx'),
                        report('Stock mínimo', '/reporte/ExcelProductoMinimo', 'icons/warning.svg', 'excel', 'Stock minimo.xlsx'),
                        report('Producto por vencerse', '/reporte/pdfProductoVencerse', 'icons/calendar.svg'),
                    ],
                },
                {
                    title: 'Datos',
                    reports: [
                        report('Personal', '/reporte/pdfPersonal', 'icons/contact.svg'),
                        report('Usuario', '/reporte/pdfUsuario', 'icons/user.svg'),
                        report('Cliente', '/reporte/pdfCliente', 'icons/people.svg'),
                        report('Caja', '/reporte/pdfCaja', 'icons/calculator.svg', 'dates'),
                    ],
                },
            ],
            reportSectionsFecha: [
                {
                    title: 'Compras',
                    reports: [
                        report('General', '/reporte/pdfCompraGeneral', 'icons/truck.svg', 'dates'),
                        report('Detallada', '/reporte/pdfCompraDetallada', 'icons/list-rich.svg', 'dates'),
                        report('Anuladas', '/reporte/pdfCompraDetalladaAnular', 'icons/ban.svg', 'dates'),
                        report('Cuotas compra', '/reporte/pdfPagoCompra', 'icons/credit-card.svg', 'dates'),
                        report('Gasto detallado', '/reporte/pdfGasto', 'icons/description.svg', 'dates'),
                        report('Gasto efectivo', '/reporte/pdfGastoCliente', 'icons/money.svg', 'dates'),
                        report('Pago crédito', '/reporte/pdfCompraProveedorCredito', 'icons/file.svg', 'dates'),
                    ],
                },
                {
                    title: 'Ventas',
                    reports: [
                        report('General', '/reporte/pdfVentaGeneral', 'icons/chart-line.svg', 'sale'),
                        report('Detallada', '/reporte/pdfVentaDetallada', 'icons/list.svg', 'sale'),
                        report('Cuotas ventas', '/reporte/pdfPagoVenta', 'icons/credit-card.svg', 'sale'),
                        report('Pago crédito', '/reporte/pdfVentaClienteCredito', 'icons/wallet.svg', 'dates'),
                        report('Anuladas', '/reporte/pdfVentaDetalladaAnulada', 'icons/x-circle.svg', 'sale'),
                        report('Devolución', '/reporte/pdfVentaDetalladaDevolucion', 'icons/action-undo.svg', 'sale'),
                    ],
                },
                {
                    title: 'Forma de Pago Venta',
                    reports: [
                        report('Efectivo', '/reporte/pdfVentaDetalladaEfectivo', 'icons/money.svg', 'sale'),
                        report('Transferencia', '/reporte/pdfVentaDetalladaTransfencia', 'icons/transfer.svg', 'sale'),
                        report('QR', '/reporte/pdfVentaDetalladaQr', 'icons/qr-code.svg', 'sale'),
                        report('Depósito', '/reporte/pdfVentaDetalladaDeposito', 'icons/institution.svg', 'sale'),
                        report('Mixta', '/reporte/pdfVentaDetalladaMixta', 'icons/wallet.svg', 'sale'),
                    ],
                },
            ],
            userReports: [
                report('General', '/reporte/pdfVentaGeneralUsuario', 'icons/bar-chart.svg', 'user-sale'),
                report('Venta detallada', '/reporte/pdfVentaDetalladaUsuario', 'icons/clipboard.svg', 'user-sale'),
                report('Pago crédito', '/reporte/pdfVentaClienteCreditoUsuario', 'icons/credit-card.svg', 'user-dates'),
                report('Efectivo', '/reporte/pdfVentaDetalladaEfectivoUsuario', 'icons/dollar.svg', 'user-sale'),
                report('Transferencia', '/reporte/pdfVentaDetalladaTransfenciaUsuario', 'icons/transfer.svg', 'user-sale'),
                report('QR', '/reporte/pdfVentaDetalladaQrUsuario', 'icons/qr-code.svg', 'user-sale'),
                report('Depósito', '/reporte/pdfVentaDetalladaDepositoUsuario', 'icons/institution.svg', 'user-sale'),
                report('Mixta', '/reporte/pdfVentaDetalladaMixtaUsuario', 'icons/wallet.svg', 'user-sale'),
            ],
            clienteReports: [
                report('Venta', '/reporte/pdfVentaDetalladaCliente', 'icons/cart.svg', 'client-sale'),
            ],
            proveedorReports: [
                report('Vencimiento 3 meses', '/reporte/pdfVentaProductos_Vencimiento', 'icons/warning.svg', 'laboratory-expiry'),
                report('Vencimiento 1 año', '/reporte/pdfVentaProductos_Vencimiento1', 'icons/calendar-check.svg', 'laboratory-expiry'),
                report('Productos', '/reporte/pdfProductoLaboratorio', 'icons/medical-cross.svg', 'laboratory'),
            ],
        };
    },
    methods: {
        seleccionarEntidadReporte({ type, item }) {
            if (type === 'user') {
                this.usuarioSeleccionado = item;
                this.datos.id_usuario = item.id;
            } else if (type === 'client') {
                this.clienteSeleccionado = item;
                this.datos.id_cliente = item.id;
            } else if (type === 'laboratory') {
                this.laboratoriosSeleccionados = item;
            }
        },
        validarFechas() {
            if (!this.datos.fecha_inicio || !this.datos.fecha_fin) {
                toast.fire({ icon: 'warning', title: 'Seleccione el período del reporte' });
                return false;
            }
            if (this.datos.fecha_inicio > this.datos.fecha_fin) {
                toast.fire({ icon: 'warning', title: 'La fecha inicial no puede superar la final' });
                return false;
            }
            return true;
        },
        parametrosReporte(item) {
            const dates = { fecha_inicio: this.datos.fecha_inicio, fecha_fin: this.datos.fecha_fin };
            const sale = { ...dates, tipo_venta: this.datosTienda.tipo_venta, id_tienda: this.datosTienda.id_tienda };

            if (['dates', 'sale', 'user-sale', 'user-dates', 'client-sale'].includes(item.mode) && !this.validarFechas()) {
                return null;
            }
            if (item.mode.startsWith('user-')) {
                if (!this.usuarioSeleccionado) return this.solicitarSeleccion('un usuario');
                return { ...(item.mode === 'user-sale' ? sale : dates), id_usuario: this.usuarioSeleccionado.id };
            }
            if (item.mode === 'client-sale') {
                if (!this.clienteSeleccionado) return this.solicitarSeleccion('un cliente');
                return { ...sale, id_cliente: this.clienteSeleccionado.id };
            }
            if (item.mode === 'laboratory' || item.mode === 'laboratory-expiry') {
                if (!this.laboratoriosSeleccionados.length) return this.solicitarSeleccion('un laboratorio');
                if (item.mode === 'laboratory-expiry') {
                    return {
                        id_tienda: 1,
                        id_proveedor: this.laboratoriosSeleccionados.map(laboratorio => laboratorio.id),
                        anio: this.datos.anio,
                    };
                }
                if (this.laboratoriosSeleccionados.length > 1) {
                    toast.fire({ icon: 'warning', title: 'Este reporte admite un único laboratorio, seleccione solo uno' });
                    return null;
                }
                return { id_proveedor: this.laboratoriosSeleccionados[0].id };
            }
            if (item.mode === 'dates') return dates;
            if (item.mode === 'sale') return sale;
            return {};
        },
        solicitarSeleccion(entity) {
            toast.fire({ icon: 'warning', title: `Seleccione ${entity} antes de continuar` });
            return null;
        },
        async generarReporte(item) {
            const params = this.parametrosReporte(item);
            if (params === null) return;

            const isExcel = item.mode === 'excel';
            this.mostrarCarga();
            try {
                const response = await axios.get(item.url, { params, responseType: 'blob' });
                closeAlert();
                if (isExcel) {
                    this.descargarArchivo(response.data, item.filename || 'Reporte.xlsx', response.headers['content-type']);
                } else {
                    this.abrirPdf(response.data);
                }
            } catch (error) {
                closeAlert();
                this.mostrarError();
            }
        },
        async cargarArqueos() {
            this.arqueosLoading = true;
            try {
                const { data } = await axios.get('/listado_arqueos', { params: this.filtros });
                this.arqueos = data || [];
            } catch (error) {
                toast.fire({ icon: 'error', title: 'No fue posible cargar los arqueos' });
            } finally {
                this.arqueosLoading = false;
            }
        },
        async descargarReporteArqueo({ id, type }) {
            this.mostrarCarga();
            try {
                const { data, headers } = await axios.get(`/reportes/arqueo/${id}/${type}`, {
                    params: this.filtros.id_usuario ? { id_usuario: this.filtros.id_usuario } : {},
                    responseType: 'blob',
                });
                closeAlert();
                const contentType = headers['content-type'] || '';
                if (contentType.includes('pdf')) {
                    this.abrirPdf(data);
                } else {
                    this.descargarArchivo(data, `reporte_arqueo_${type}.xlsx`, contentType);
                }
            } catch (error) {
                closeAlert();
                this.mostrarError();
            }
        },
        abrirPdf(data) {
            const url = URL.createObjectURL(new Blob([data], { type: 'application/pdf' }));
            const opened = window.open(url, '_blank');
            if (!opened) {
                Swal.fire({
                    icon: 'success',
                    title: 'Reporte listo',
                    html: 'Tu navegador bloqueó la apertura automática. <a href="' + url + '" target="_blank" rel="noopener">Haz clic aquí para abrirlo</a>.',
                });
            }
            window.setTimeout(() => URL.revokeObjectURL(url), 60000);
        },
        descargarArchivo(data, filename, contentType) {
            const url = URL.createObjectURL(new Blob([data], { type: contentType || 'application/octet-stream' }));
            const anchor = document.createElement('a');
            anchor.href = url;
            anchor.download = filename;
            document.body.appendChild(anchor);
            anchor.click();
            anchor.remove();
            window.setTimeout(() => URL.revokeObjectURL(url), 1000);
        },
        mostrarCarga() {
            showProcessingAlert({
                title: 'Generando reporte',
                text: 'Estamos procesando la información y preparando el documento…',
            });
        },
        mostrarError() {
            Swal.fire({
                icon: 'error',
                title: 'No se pudo generar el reporte',
                text: 'Verifique los filtros e inténtelo nuevamente.',
            });
        },
        async cargarCatalogos() {
            try {
                const { data } = await axios.get('/usuario/selectUsuario');
                this.arrayUsuario = data || [];
            } catch (error) {
                toast.fire({ icon: 'warning', title: 'No fue posible cargar el catálogo de usuarios' });
            }
        },
    },
    async mounted() {
        this.catalogLoading = true;
        await Promise.all([this.cargarCatalogos(), this.cargarArqueos()]);
        this.catalogLoading = false;
    },
};
</script>

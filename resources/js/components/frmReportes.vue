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
            :clients="arrayCliente"
            :laboratories="arrayLaboratorio"
            :selected-user="usuarioSeleccionado"
            :selected-client="clienteSeleccionado"
            :selected-laboratory="laboratorioSeleccionado"
            :cash-records="arqueos"
            :cash-loading="arqueosLoading"
            @select-entity="seleccionarEntidadReporte"
            @load-cash="cargarArqueos"
            @download-cash="downloadArqueoReport($event.id, $event.type)"
        />
        <div v-if="false">
        <div class="my-4">
            <div class="card shadow-lg">
                <div class="card-header bg-info text-white text-center">
                    <h5 class="mb-0">REPORTES</h5>
                </div>

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                            type="button" role="tab" aria-controls="home" aria-selected="true">Reportes
                            Generales</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">Reportes x
                            Arqueos</button>
                    </li>

                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane show active mt-3" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card-body">
                            <!-- Tarjetas de Reportes sin fecha -->
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 g-6">
                                <div v-for="section in reportSections" :key="section.title" class="col">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-header bg-secondary text-dark text-uppercase">
                                            {{ section.title }}
                                        </div>
                                        <div class="card-body d-flex flex-column align-items-start">
                                            <button v-for="report in section.reports" :key="report.label"
                                                class="btn btn-outline-info mb-2 w-100 d-flex justify-content-between align-items-center"
                                                @click="report.method">
                                                <span>
                                                    <i :class="report.icon"></i> {{ report.label }}
                                                </span>
                                                <i class="fas fa-file-pdf"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Filtros de Fecha -->
                            <div class="row mb-4 mt-4">
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="fecha_inicio" class="form-label">Desde</label>
                                        <input type="date" id="fecha_inicio" class="form-control"
                                            v-model="datos.fecha_inicio">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="fecha_fin" class="form-label">Hasta</label>
                                        <input type="date" id="fecha_fin" class="form-control"
                                            v-model="datos.fecha_fin">
                                    </div>
                                </div>
                            </div>

                            <!-- Tarjetas de Reportes con fecha -->
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                                <div v-for="section in reportSectionsFecha" :key="section.title" class="col">
                                    <div class="card h-100 shadow-sm border-0">
                                        <div class="card-header bg-secondary text-dark text-uppercase">
                                            {{ section.title }}
                                        </div>
                                        <div class="card-body d-flex flex-column align-items-start">
                                            <button v-for="report in section.reports" :key="report.label"
                                                class="btn btn-outline-info mb-2 w-100 d-flex justify-content-between align-items-center"
                                                @click="report.method">
                                                <span>
                                                    <i :class="report.icon"></i> {{ report.label }}
                                                </span>
                                                <i class="fas fa-file-pdf"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Reportes con Filtros Específicos -->
                            <div class="row mt-4">
                                <!-- Reportes por Usuario -->
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card shadow-sm border-0 h-100">
                                        <div class="card-header bg-secondary text-dark text-uppercase">
                                            Reportes por Usuario
                                        </div>
                                        <div class="card-body">

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Usuario Seleccionado:</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="flex-grow-1 p-2 bg-light rounded">
                                                        <div v-if="usuarioSeleccionado">
                                                            <strong>{{ usuarioSeleccionado.nombre }}</strong>
                                                        </div>
                                                        <div v-else class="text-muted fst-italic">No seleccionado</div>
                                                    </div>
                                                    <button @click="abrirModal('usuario')"
                                                        class="btn btn-info text-white">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-column">
                                                <button v-for="report in userReports" :key="report.label"
                                                    class="btn btn-outline-info mb-2 d-flex justify-content-between align-items-center"
                                                    @click="report.method">
                                                    <span>
                                                        <i :class="report.icon"></i> {{ report.label }}
                                                    </span>
                                                    <i class="fas fa-file-pdf"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reportes por Cliente -->
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card shadow-sm border-0 h-100">
                                        <div class="card-header bg-secondary text-dark text-uppercase">
                                            Reportes por Cliente
                                        </div>
                                        <div class="card-body">

                                            <!-- <div class="form-group mb-3">
                                                <label for="id_cliente" class="form-label">Seleccione el Cliente</label>
                                                <select id="id_cliente" class="form-select" v-model="datos.id_cliente">
                                                    <option value="0" disabled>Seleccione el Cliente</option>
                                                    <option v-for="cliente in arrayCliente" :key="cliente.id"
                                                        :value="cliente.id">
                                                        {{ cliente.nombre }}
                                                    </option>
                                                </select>
                                            </div> -->


                                            <div class="mb-3 w-100">
                                                <label class="form-label fw-bold">Cliente Seleccionado:</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="flex-grow-1 bg-light rounded p-2">
                                                        <div v-if="clienteSeleccionado">
                                                            <strong>{{ clienteSeleccionado.nombre }}</strong>
                                                            <div class="text-muted small">Matrícula: {{
                                                                clienteSeleccionado.matricula }}</div>
                                                        </div>
                                                        <div v-else class="text-muted fst-italic">No seleccionado</div>
                                                    </div>
                                                    <button @click="abrirModal('cliente')"
                                                        class="btn btn-info text-white">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>


                                            <div class="d-flex flex-column">
                                                <button v-for="report in clienteReports" :key="report.label"
                                                    class="btn btn-outline-info mb-2 d-flex justify-content-between align-items-center"
                                                    @click="report.method">
                                                    <span>
                                                        <i :class="report.icon"></i> {{ report.label }}
                                                    </span>
                                                    <i class="fas fa-file-pdf"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reportes por Proveedor -->
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card shadow-sm border-0 h-100">
                                        <div class="card-header bg-secondary text-dark text-uppercase">
                                            Reportes por Laboratorio
                                        </div>
                                        <div class="card-body">

                                            <!-- <div class="form-group mb-3">
                                                <label for="id_proveedor" class="form-label">Seleccione
                                                    Laboratorio</label>
                                                <select id="id_proveedor" class="form-select"
                                                    v-model="datos.id_proveedor">
                                                    <option value="0" disabled>Seleccione Laboratorio</option>
                                                    <option v-for="proveedor in arrayLaboratorio" :key="proveedor.id"
                                                        :value="proveedor.id">
                                                        {{ proveedor.nombre }}
                                                    </option>
                                                </select>
                                            </div> -->

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Laboratorio Seleccionado:</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="flex-grow-1 p-2 bg-light rounded">
                                                        <div v-if="laboratorioSeleccionado">
                                                            <strong>{{ laboratorioSeleccionado.nombre }}</strong>
                                                        </div>
                                                        <div v-else class="text-muted fst-italic">No seleccionado</div>
                                                    </div>
                                                    <button @click="abrirModal('laboratorio')"
                                                        class="btn btn-info text-white">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-column">
                                                <button v-for="report in proveedorReports" :key="report.label"
                                                    class="btn btn-outline-info mb-2 d-flex justify-content-between align-items-center"
                                                    @click="report.method">
                                                    <span>
                                                        <i :class="report.icon"></i> {{ report.label }}
                                                    </span>
                                                    <i class="fas fa-file-pdf"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Fecha Inicio</label>
                                        <input type="date" class="form-control" v-model="filtros.fecha_inicio">
                                    </div>
                                    <div class="col-md-3">
                                        <label>Fecha Fin</label>
                                        <input type="date" class="form-control" v-model="filtros.fecha_fin">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Usuario</label>
                                        <select class="form-control" v-model="filtros.id_usuario">
                                            <option value="">Todos</option>
                                            <option v-for="user in arrayUsuario" :value="user.id" :key="user.id">
                                                {{ user.nombre }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button class="btn btn-info text-white" @click="cargarArqueos">
                                            <i class="fas fa-search me-1"></i> Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="bg-info">
                                            <tr class="bg-info">
                                                <th class="text-white">Usuario</th>
                                                <th class="text-white">Apertura</th>
                                                <th class="text-white">Cierre</th>
                                                <th class="text-white">Estado</th>
                                                <th class="text-white">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="arq in arqueos" :key="arq.id">
                                                <td>{{ arq.usuario }}</td>
                                                <td>{{ arq.fecha_apertura ? formatDate(arq.fecha_apertura) : '—' }}</td>
                                                <td>{{ arq.fecha_cierre ? formatDate(arq.fecha_cierre) : 'Abierto' }}
                                                </td>
                                                <td>
                                                    <span :class="{
                                                        'badge bg-dark': arq.estado === 'Cerrada',
                                                        'badge bg-success': arq.estado === 'Abierta',
                                                        'badge bg-secondary': arq.estado !== 'Cerrada' && arq.estado !== 'Abierta'
                                                    }">{{ arq.estado || '—' }}</span>
                                                </td>
                                                <td>
                                                    <div class="dropdown" v-show="arq.estado === 'Cerrada'">
                                                        <button class="btn btn-sm btn-outline-dark dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown">
                                                            Reportes
                                                        </button>
                                                        <ul class="dropdown-menu">

                                                            <li><a class="dropdown-item" href="#"
                                                                    @click.prevent="downloadArqueoReport(arq.id, 'efectivo')">Efectivo</a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="#"
                                                                    @click.prevent="downloadArqueoReport(arq.id, 'transferencia')">Transferencia</a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="#"
                                                                    @click.prevent="downloadArqueoReport(arq.id, 'deposito')">Depósito</a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="#"
                                                                    @click.prevent="downloadArqueoReport(arq.id, 'qr')">QR</a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="#"
                                                                    @click.prevent="downloadArqueoReport(arq.id, 'mixta')">Mixta</a>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item" href="#"
                                                                    @click.prevent="downloadArqueoReport(arq.id, 'general')"><strong>General</strong></a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="#"
                                                                    @click.prevent="downloadArqueoReport(arq.id, 'general_detallada')"><strong>General
                                                                        Detallado</strong></a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr v-if="arqueos.length === 0">
                                                <td colspan="8" class="text-center text-muted">No hay registros</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <template v-if="arqueos.length < 8">
                                        <br><br><br><br><br><br><br><br>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

   
        <div class="modal fade" id="modalBusqueda" tabindex="-1" data-bs-backdrop="static" ref="modalBusqueda">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header" :class="'bg-info'">
                        <h5 class="modal-title text-white">
                           
                            Buscar {{ tituloModal }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" @click="cerrarModal"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Buscador -->
                        <div class="search-box mb-4">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control form-control-lg"
                                    :placeholder="placeholderBusqueda" v-model="busqueda" @input="buscarElemento"
                                    ref="inputBusqueda">
                                <button v-if="busqueda" class="btn btn-outline-secondary" @click="limpiarBusqueda">
                                    <i class="fas fa-x-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Loading -->
                        <div v-if="cargando" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                            <p class="mt-3 text-muted">Buscando...</p>
                        </div>

                        <!-- Lista de resultados -->
                        <div v-else-if="itemsFiltrados.length > 0" class="list-group">
                            <button v-for="item in itemsFiltrados" :key="item.id" @click="seleccionarItem(item)"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                :class="{ 'active': esItemSeleccionado(item) }">
                                <div class="d-flex flex-column">
                                    <h6 class="mb-1">{{ item.nombre.toUpperCase() }}</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <small v-if="tipoModal === 'cliente' && item.matricula">
                                            CI: {{ item.matricula }}
                                        </small>
                                        <small v-if="tipoModal === 'cliente' && item.telefono" class="ms-3">
                                            Telf: {{ item.telefono }}
                                        </small>
                                    </div>
                                </div>
                                <i v-if="esItemSeleccionado(item)" class="fas fa-check-circle fs-4"></i>
                            </button>
                        </div>

                        <!-- Sin resultados -->
                        <div v-else class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted"></i>
                            <p class="mt-3 text-muted">No se encontraron resultados</p>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary text-white" @click="cerrarModal">
                            <i class="fas fa-circle-times me-2"></i>Cancelar
                        </button>
                        <button type="button" class="btn" :class="'btn-info text-white'" @click="confirmarSeleccion" :disabled="!itemSeleccionadoTemp">
                            <i class="bi bi-check-circle me-2"></i>Seleccionar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
</template>

<script>
import Swal from 'sweetalert2';
import moment from 'moment';

export default {
    data() {
        return {
            // adicion
            clienteSeleccionado: null,
            usuarioSeleccionado: null,
            laboratorioSeleccionado: null,
            
            // Control del modal
            modalInstance: null,
            tipoModal: '',
            busqueda: '',
            cargando: false,
            itemSeleccionadoTemp: null,

            resultadosClientes: [],
            debounceTimer: null,
            catalogLoading: true,
            arqueosLoading: true,

            filtros: {
                fecha_inicio: moment().subtract(7, 'days').format('YYYY-MM-DD'),
                fecha_fin: moment().format('YYYY-MM-DD'),
                id_usuario: ''
            },
            //usuarios: [],
            arqueos: [],

            datos: {
                fecha_inicio: moment().format('YYYY-MM-DD'),
                fecha_fin: moment().format('YYYY-MM-DD'),
                id_usuario: 0,
                id_cliente: 0,
                id_proveedor: 0,
                año: moment().format('YYYY'),
            },
            datos_tienda: {
                venta_directa: 'Venta Directa',
                almacen: { id: 1, nombre: '' },
            },
            arrayUsuario: [],
            arrayCliente: [],
            arrayLaboratorio: [],

            reportSections: [
                {
                    title: "Productos",
                    reports: [
                        { label: "Producto", method: () => this.generateReport('/reporte/ExcelProductoInventario'), icon: "fas fa-boxes" },
                        { label: "Laboratorio", method: () => this.generateReport('/reporte/pdfProveedor'), icon: "fas fa-flask" },
                        { label: "Inventario Bs", method: () => this.generateReport('/reporte/ExcelProducto'), icon: "fas fa-warehouse" },
                        { label: "Stock Mínimo", method: () => this.generateReport('/reporte/ExcelProductoMinimo'), icon: "fas fa-exclamation-triangle" },
                        { label: "Producto por Vencerse", method: () => this.cargarPdfProductoVencerse(), icon: "fas fa-calendar-times" },
                    ],
                },
                {
                    title: "Datos",
                    reports: [
                        { label: "Personal", method: () => this.generateReport('/reporte/pdfPersonal'), icon: "fas fa-user-tie" },
                        { label: "Usuario", method: () => this.generateReport('/reporte/pdfUsuario'), icon: "fas fa-users-cog" },
                        { label: "Cliente", method: () => this.generateReport('/reporte/pdfCliente'), icon: "fas fa-user-friends" },
                        { label: "Caja", method: () => this.generateReportWithDates('/reporte/pdfCaja'), icon: "fas fa-cash-register" },
                    ],
                },
            ],

            reportSectionsFecha: [
                {
                    title: "Compras",
                    reports: [
                        { label: "General", method: () => this.generateReportWithDates('/reporte/pdfCompraGeneral'), icon: "fas fa-shopping-cart" },
                        { label: "Detallada", method: () => this.generateReportWithDates('/reporte/pdfCompraDetallada'), icon: "fas fa-list-alt" },
                        { label: "Anuladas", method: () => this.generateReportWithDates('/reporte/pdfCompraDetalladaAnular'), icon: "fas fa-ban" },
                        { label: "Cuotas Compra", method: () => this.generateReportWithDates('/reporte/pdfPagoCompra'), icon: "fas fa-credit-card" },
                        { label: "Gasto Detallado", method: () => this.generateReportWithDates('/reporte/pdfGasto'), icon: "fas fa-receipt" },
                        { label: "Gasto Efectivo", method: () => this.generateReportWithDates('/reporte/pdfGastoCliente'), icon: "fas fa-money-bill-wave" },
                        { label: "Pago Crédito", method: () => this.generateReportWithDates('/reporte/pdfCompraProveedorCredito'), icon: "fas fa-file-invoice-dollar" },
                    ],
                },
                {
                    title: "Ventas",
                    reports: [
                        { label: "General", method: () => this.generateVentaReport('/reporte/pdfVentaGeneral'), icon: "fas fa-chart-line" },
                        { label: "Detallada", method: () => this.generateVentaReport('/reporte/pdfVentaDetallada'), icon: "fas fa-list" },
                        { label: "Cuotas Ventas", method: () => this.generateVentaReport('/reporte/pdfPagoVenta'), icon: "fas fa-credit-card" },
                        { label: "Pago Crédito", method: () => this.generateReportWithDates('/reporte/pdfVentaClienteCredito'), icon: "fas fa-wallet" },
                        { label: "Anuladas", method: () => this.generateVentaReport('/reporte/pdfVentaDetalladaAnulada'), icon: "fas fa-times-circle" },
                        { label: "Devolución", method: () => this.generateVentaReport('/reporte/pdfVentaDetalladaDevolucion'), icon: "fas fa-undo" },
                    ],
                },
                {
                    title: "Forma de Pago Venta",
                    reports: [
                        { label: "Efectivo", method: () => this.generateVentaReport('/reporte/pdfVentaDetalladaEfectivo'), icon: "fas fa-money-bill" },
                        { label: "Transferencia", method: () => this.generateVentaReport('/reporte/pdfVentaDetalladaTransfencia'), icon: "fas fa-exchange-alt" },
                        { label: "Qr", method: () => this.generateVentaReport('/reporte/pdfVentaDetalladaQr'), icon: "fas fa-qrcode" },
                        { label: "Depósito", method: () => this.generateVentaReport('/reporte/pdfVentaDetalladaDeposito'), icon: "fas fa-university" },
                        { label: "Mixta", method: () => this.generateVentaReport('/reporte/pdfVentaDetalladaMixta'), icon: "fas fa-handshake" },
                    ],
                },
            ],

            userReports: [
                { label: "General", method: () => this.generateUserReport('/reporte/pdfVentaGeneralUsuario'), icon: "fas fa-chart-bar" },
                { label: "Venta Detallada", method: () => this.generateUserReport('/reporte/pdfVentaDetalladaUsuario'), icon: "fas fa-clipboard-list" },
                { label: "Pago Crédito", method: () => this.generateReportWithUser('/reporte/pdfVentaClienteCreditoUsuario'), icon: "fas fa-money-check" },
                { label: "Efectivo", method: () => this.generateUserReport('/reporte/pdfVentaDetalladaEfectivoUsuario'), icon: "fas fa-dollar-sign" },
                { label: "Transferencia", method: () => this.generateUserReport('/reporte/pdfVentaDetalladaTransfenciaUsuario'), icon: "fas fa-exchange-alt" },
                { label: "Qr", method: () => this.generateUserReport('/reporte/pdfVentaDetalladaQrUsuario'), icon: "fas fa-qrcode" },
                { label: "Depósito", method: () => this.generateUserReport('/reporte/pdfVentaDetalladaDepositoUsuario'), icon: "fas fa-landmark" },
                { label: "Mixta", method: () => this.generateUserReport('/reporte/pdfVentaDetalladaMixtaUsuario'), icon: "fas fa-coins" },
            ],

            clienteReports: [
                { label: "Venta", method: () => this.generateClienteReport('/reporte/pdfVentaDetalladaCliente'), icon: "fas fa-shopping-bag" },
            ],

            proveedorReports: [
                { label: "Vencimiento 3 Meses", method: () => this.generateProveedorReport('/reporte/pdfVentaProductos_Vencimiento'), icon: "fas fa-exclamation-circle" },
                { label: "Vencimiento 1 Año", method: () => this.generateProveedorReport('/reporte/pdfVentaProductos_Vencimiento1'), icon: "fas fa-calendar-check" },
                { label: "Productos", method: () => this.generateProveedorReportSimple('/reporte/pdfProductoLaboratorio'), icon: "fas fa-pills" },
            ],
        };
    },

    computed:{
        tituloModal() {
            const titulos = {
                'cliente': 'Cliente',
                'usuario': 'Usuario',
                'laboratorio': 'Laboratorio'
            };
            return titulos[this.tipoModal] || '';
        },

        placeholderBusqueda() {
            const placeholders = {
                'cliente': 'Buscar por nombre o matrícula...',
                'usuario': 'Buscar por nombre de usuario...',
                'laboratorio': 'Buscar por nombre de laboratorio...'
            };
            return placeholders[this.tipoModal] || 'Buscar...';
        },

        itemsFiltrados() {
            if (this.tipoModal === 'cliente') {
                return this.resultadosClientes;
            } else if (this.tipoModal === 'usuario') {
                return this.filtrarLocal(this.arrayUsuario);
            } else if (this.tipoModal === 'laboratorio') {
                return this.filtrarLocal(this.arrayLaboratorio);
            }
            return [];
        }
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
                this.laboratorioSeleccionado = item;
                this.datos.id_proveedor = item.id;
            }
        },
        abrirModal(tipo) {
            this.tipoModal = tipo;
            this.busqueda = '';
            this.itemSeleccionadoTemp = null;

            // Cargar item actual como temp
            if (tipo === 'cliente') {
                this.itemSeleccionadoTemp = this.clienteSeleccionado;
            } else if (tipo === 'usuario') {
                this.itemSeleccionadoTemp = this.usuarioSeleccionado;
            } else if (tipo === 'laboratorio') {
                this.itemSeleccionadoTemp = this.laboratorioSeleccionado;
            }

            $('#modalBusqueda').modal('show');

            // Focus en input después de abrir
            this.$nextTick(() => {
                if (this.$refs.inputBusqueda) {
                    this.$refs.inputBusqueda.focus();
                }
            });

            // Búsqueda inicial para clientes
            if (tipo === 'cliente') {
                this.buscarClientes('');
            }
        },

        cerrarModal() {
            $('#modalBusqueda').modal('hide');
            this.busqueda = '';
            this.itemSeleccionadoTemp = null;
        },

        buscarElemento() {
            if (this.tipoModal === 'cliente') {
                // Implementar debounce para búsqueda de clientes
                clearTimeout(this.debounceTimer);
                this.debounceTimer = setTimeout(() => {
                    this.buscarClientes(this.busqueda);
                }, 300);
            }
            // Para usuario y laboratorio el computed hace el trabajo
        },

        async buscarClientes(criterio) {
            this.cargando = true;
            try {
                const response = await axios.get('/clientes/buscar', {
                    params: { criterio: criterio }
                });
                this.resultadosClientes = response.data;
            } catch (error) {
                console.error('Error al buscar clientes:', error);
                this.$swal({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo realizar la búsqueda de clientes'
                });
            } finally {
                this.cargando = false;
            }
        },

        filtrarLocal(array) {
            if (!this.busqueda.trim()) {
                return array;
            }

            const busquedaLower = this.busqueda.toLowerCase();
            return array.filter(item =>
                item.nombre.toLowerCase().includes(busquedaLower)
            );
        },

        seleccionarItem(item) {
            this.itemSeleccionadoTemp = item;
        },

        confirmarSeleccion() {
            if (!this.itemSeleccionadoTemp) return;

            if (this.tipoModal === 'cliente') {
                this.clienteSeleccionado = this.itemSeleccionadoTemp;
                this.$emit('cliente-seleccionado', this.clienteSeleccionado);
            } else if (this.tipoModal === 'usuario') {
                this.usuarioSeleccionado = this.itemSeleccionadoTemp;
                this.$emit('usuario-seleccionado', this.usuarioSeleccionado);
            } else if (this.tipoModal === 'laboratorio') {
                this.laboratorioSeleccionado = this.itemSeleccionadoTemp;
                this.$emit('laboratorio-seleccionado', this.laboratorioSeleccionado);
            }

            this.cerrarModal();
        },

        esItemSeleccionado(item) {
            return this.itemSeleccionadoTemp && this.itemSeleccionadoTemp.id === item.id;
        },

        limpiarBusqueda() {
            this.busqueda = '';
            if (this.tipoModal === 'cliente') {
                this.buscarClientes('');
            }
        },

        downloadArqueoReport(arqueoId, tipo) {
            // Construir la URL igual que antes
            const baseUrl = '/reportes/arqueo';
            const params = new URLSearchParams();
            if (this.filtros.id_usuario) {
                params.append('id_usuario', this.filtros.id_usuario);
            }
            const queryString = params.toString();
            const fullUrl = `${baseUrl}/${arqueoId}/${tipo}${queryString ? '?' + queryString : ''}`;

            // Mostrar loading
            this.showLoading();

            // Descargar con axios
            axios.get(fullUrl, { responseType: 'blob' })
                .then(response => {
                    Swal.close();

                    // Detectar tipo de archivo para decidir cómo abrirlo
                    const contentType = response.headers['content-type'];
                    if (contentType && contentType.includes('application/pdf')) {
                        this.openPDF(response);
                    } else if (contentType && contentType.includes('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
                        this.downloadExcel(response, 'arqueo_reporte.xlsx');
                    } else {
                        // Fallback genérico
                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', `reporte_arqueo_${tipo}.pdf`); // o .xlsx según corresponda
                        document.body.appendChild(link);
                        link.click();
                        window.URL.revokeObjectURL(url);
                        link.remove();
                    }
                })
                .catch(error => {
                    Swal.close();
                    this.showError();
                    console.error('Error al descargar el reporte de arqueo:', error);
                });
        },
        formatDate(dateString) {
            if (!dateString) return '';
            const d = new Date(dateString);
            return d.toLocaleDateString('es-BO', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        },

        cargarArqueos() {
            this.arqueosLoading = true;
            return axios.get('/listado_arqueos', { params: this.filtros })
                .then(response => {
                    this.arqueos = response.data;
                })
                .catch(error => {
                    console.error(error);
                    this.$toaster.error('No fue posible cargar los arqueos.');
                })
                .finally(() => {
                    this.arqueosLoading = false;
                });
        },
        getUrlReporte(arqueoId, tipo) {
            const baseUrl = '/reportes/arqueo';
            const params = new URLSearchParams();
            if (this.filtros.id_usuario) {
                params.append('id_usuario', this.filtros.id_usuario);
            }

            const queryString = params.toString();
            return `${baseUrl}/${arqueoId}/${tipo}${queryString ? '?' + queryString : ''}`;
        },


        generateReport(url) {
            this.showLoading(); // Mostrar sin temporizador

            axios.get(url, { responseType: 'blob' })
                .then(response => {
                    Swal.close(); // Cerrar al terminar con éxito
                    if (url.includes('Excel')) {
                        this.downloadExcel(response, url);
                    } else {
                        this.openPDF(response);
                    }
                })
                .catch(error => {
                    Swal.close(); // Cerrar también en caso de error
                    this.showError();
                    console.log(error);
                });
        },

        generateReportWithDates(url) {
            const fullUrl = `${url}?fecha_inicio=${this.datos.fecha_inicio}&fecha_fin=${this.datos.fecha_fin}`;
            this.showLoading(); // ← Sin argumentos
            axios.get(fullUrl, { responseType: 'blob' })
                .then(response => {
                    Swal.close(); // ← Cerrar al terminar
                    this.openPDF(response);
                })
                .catch(error => {
                    Swal.close(); // ← Cerrar en error también
                    this.showError();
                    console.log(error);
                });
        },

        generateVentaReport(url) {
            const fullUrl = `${url}?fecha_inicio=${this.datos.fecha_inicio}&fecha_fin=${this.datos.fecha_fin}&tipo_venta=${this.datos_tienda.venta_directa}&id_tienda=${this.datos_tienda.almacen.id}`;
            this.showLoading();
            axios.get(fullUrl, { responseType: 'blob' })
                .then(response => {
                    Swal.close();
                    this.openPDF(response);
                })
                .catch(error => {
                    Swal.close();
                    this.showError();
                    console.log(error);
                });
        },

        generateUserReport(url) {
            if (!this.usuarioSeleccionado) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Tiene que seleccionar un usuario.',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }
            this.datos.id_usuario=this.usuarioSeleccionado.id;
            const fullUrl = `${url}?fecha_inicio=${this.datos.fecha_inicio}&fecha_fin=${this.datos.fecha_fin}&tipo_venta=${this.datos_tienda.venta_directa}&id_tienda=${this.datos_tienda.almacen.id}&id_usuario=${this.datos.id_usuario}`;
            this.showLoading();
            axios.get(fullUrl, { responseType: 'blob' })
                .then(response => {
                    Swal.close();
                    this.openPDF(response);
                })
                .catch(error => {
                    Swal.close();
                    this.showError();
                    console.log(error);
                });
        },

        generateReportWithUser(url) {
            const fullUrl = `${url}?fecha_inicio=${this.datos.fecha_inicio}&fecha_fin=${this.datos.fecha_fin}&id_usuario=${this.datos.id_usuario}`;
            this.showLoading();
            axios.get(fullUrl, { responseType: 'blob' })
                .then(response => {
                    Swal.close();
                    this.openPDF(response);
                })
                .catch(error => {
                    Swal.close();
                    this.showError();
                    console.log(error);
                });
        },

        generateClienteReport(url) {
            // Validar que el cliente esté seleccionado
            if (!this.clienteSeleccionado || !this.clienteSeleccionado.id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Cliente no seleccionado',
                    text: 'Por favor, selecciona un cliente antes de generar el reporte.',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            this.datos.id_cliente = this.clienteSeleccionado.id;
            const fullUrl = `${url}?fecha_inicio=${this.datos.fecha_inicio}&fecha_fin=${this.datos.fecha_fin}&tipo_venta=${this.datos_tienda.venta_directa}&id_tienda=${this.datos_tienda.almacen.id}&id_cliente=${this.datos.id_cliente}`;
            this.showLoading();
            axios.get(fullUrl, { responseType: 'blob' })
                .then(response => {
                    Swal.close();
                    this.openPDF(response);
                })
                .catch(error => {
                    Swal.close();
                    this.showError();
                    console.log(error);
                });
        },

        generateProveedorReport(url) {
            // Validar que el laboratorio (proveedor) esté seleccionado
            if (!this.laboratorioSeleccionado || !this.laboratorioSeleccionado.id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Laboratorio no seleccionado',
                    text: 'Por favor, selecciona un laboratorio antes de generar el reporte.',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            this.datos.id_proveedor = this.laboratorioSeleccionado.id;
            const fullUrl = `${url}?id_tienda=1&id_proveedor=${this.datos.id_proveedor}&anio=${this.datos.año}`;
            this.showLoading();
            axios.get(fullUrl, { responseType: 'blob' })
                .then(response => {
                    Swal.close();
                    this.openPDF(response);
                })
                .catch(error => {
                    Swal.close();
                    this.showError();
                    console.log(error);
                });
        },

        // generateProveedorReportSimple(url) {
        //     const fullUrl = `${url}?id_proveedor=${this.datos.id_proveedor}`;
        //     this.showLoading();
        //     axios.get(fullUrl, { responseType: 'blob' })
        //         .then(response => {
        //             Swal.close();
        //             this.openPDF(response);
        //         })
        //         .catch(error => {
        //             Swal.close();
        //             this.showError();
        //             console.log(error);
        //         });
        // },

        generateProveedorReportSimple(url) {
            // Validar que el laboratorio (proveedor) esté seleccionado
            if (!this.laboratorioSeleccionado || !this.laboratorioSeleccionado.id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Laboratorio no seleccionado',
                    text: 'Por favor, selecciona un laboratorio antes de generar el reporte.',
                    confirmButtonText: 'Aceptar'
                });
                return;
            }

            this.datos.id_proveedor = this.laboratorioSeleccionado.id;
            const fullUrl = `${url}?id_proveedor=${this.datos.id_proveedor}`;
            this.showLoading();
            axios.get(fullUrl, { responseType: 'blob' })
                .then(response => {
                    Swal.close();
                    this.openPDF(response);
                })
                .catch(error => {
                    Swal.close();
                    this.showError();
                    console.log(error);
                });
        },

        cargarPdfProductoVencerse() {
            this.showLoading();
            axios.get('/reporte/pdfProductoVencerse', { responseType: 'blob' })
                .then(response => {
                    Swal.close();
                    this.openPDF(response);
                })
                .catch(error => {
                    Swal.close();
                    this.showError();
                    console.log(error);
                });
        },

        openPDF(response) {
            const blob = new Blob([response.data], { type: 'application/pdf' });
            const downloadUrl = URL.createObjectURL(blob);
            window.open(downloadUrl, '_blank');
        },

        downloadExcel(response, url) {
            let filename = 'Reporte.xlsx';
            if (url.includes('ProductoInventario')) filename = 'Producto.xlsx';
            else if (url.includes('ExcelProducto') && !url.includes('Minimo')) filename = 'Inventario.xlsx';
            else if (url.includes('Minimo')) filename = 'Stock Minimo.xlsx';

            const blob = new Blob([response.data], { type: 'data:application/vnd.ms-excel' });
            const downloadUrl = URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = downloadUrl;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        },

        showLoading(time) {
            Swal.fire({
                title: 'Cargando Reporte!',
                html: 'Por favor espere...',
                timer: time,
                timerProgressBar: true,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });
        },

        showLoading() {
            Swal.fire({
                title: 'Cargando Reporte!',
                html: 'Por favor espere...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        },

        showError() {
            Swal.fire({
                icon: 'error',
                title: 'Error al Cargar el Reporte!',
                text: 'Comuníquese con el Administrador del Sistema',
            });
        },

        selectUsuario() {
            return axios.get('/usuario/selectUsuario')
                .then(response => {
                    this.arrayUsuario = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        selectCliente() {
            return axios.get('/cliente/selectCliente')
                .then(response => {
                    this.arrayCliente = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },

        selectLaboratorio() {
            return axios.get('/proveedor/selectProveedor')
                .then(response => {
                    this.arrayLaboratorio = response.data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
    },

    async mounted() {
        this.catalogLoading = true;
        await Promise.all([
            this.selectUsuario(),
            this.selectCliente(),
            this.selectLaboratorio(),
            this.cargarArqueos(),
        ]);
        this.catalogLoading = false;
    }
}
</script>

<style scoped>
.list-group .active{
    background-color: rgb(66, 142, 255);
    border-color:rgb(66, 142, 255); 
}
.card-header {
    font-size: 1rem;
    font-weight: bold;
}

.btn {
    font-size: 0.9rem;
    text-align: left;
}

/* .card {
    transition: transform 0.2s;
} */

/* .card:hover {
    transform: translateY(-5px);
} */
</style>

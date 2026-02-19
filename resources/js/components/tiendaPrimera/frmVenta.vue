<template>
    <main class="main">
        <div class="row">
            <div class="col">
                &nbsp;
                <div class="card">
                    <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">VENTA</h3>
                    </div>
                    <template v-if="listado == 0 && estadoCaja == 'Abierta'">
                        <div class="card-body">
                            <div class="form-group row" style='margin-left: 1%'>
                                <form class="row">
                                
                                   <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="buscarCliente" class="form-label">Cliente</label>
                                            <div class="input-group">
                                                <input type="text" v-model="keywordCliente" id="buscarCliente"
                                                    class="form-control" placeholder="Nombre del cliente"
                                                    @input="listarClientesAPI(keywordCliente)" />
                                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                            </div>
                                        </div>
                                        <div class="position-relative">
                                            <transition name="fade">
                                                <div v-if="filteredClientes.length > 0 && keywordCliente != ''"
                                                    class="com-completion-results shadow-sm mt-1"
                                                    style="z-index: 500; position: absolute; width: 100%; background: #fff; border: 1px solid #dee2e6; max-height: 250px; overflow: auto;">
                                                    <ul class="list-unstyled m-0">
                                                        <li v-for="cliente in filteredClientes"
                                                            :key="cliente.id_cliente" @click="selectedCliente(cliente)"
                                                            class="cursor-pointer p-2 border-bottom hover-bg">
                                                            <div class="container-fluid p-0 dropdown-item">
                                                                <div class="row align-items-center">
                                                                    <div class="col-auto pe-0">
                                                                        <i class="fas fa-user-circle text-muted"></i>
                                                                    </div>
                                                                    <div class="col">
                                                                        <h6 class="mb-0">{{ cliente.nombre }} {{
                                                                            cliente.apellidos }}</h6>
                                                                        <small class="text-muted">{{ cliente.ci
                                                                            }}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </transition>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Fecha</label>
                                        <input type="date" class="form-control" v-model="datos.fecha" disabled>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Tipo Pago</label>
                                        <select class="form-select" v-model="datos.id_tipo_pago">
                                            <option value="0" disabled>Seleccione el Tipo Pago</option>
                                            <option v-for="tipo_pago in arrayPago" :key="tipo_pago.id"
                                                :value="tipo_pago.id" v-text="tipo_pago.nombre"></option>
                                        </select>
                                    </div>

                                    <div class="col-md-6" v-if="datos.id_tipo_pago == 2">
                                        <label class="form-label">Fecha Final</label>
                                        <input type="date" class="form-control" v-model="datosPago.fecha_final">
                                    </div>

                                    <div class="col-md-6" v-if="datos.id_tipo_pago == 1">
                                        <label class="form-label">Forma Pago</label>
                                        <select class="form-select" v-model="datos.id_forma_pago">
                                            <option value="0" disabled>Seleccione la Forma de Pago</option>
                                            <option v-for="forma_pago in arrayForma2" :key="forma_pago.id"
                                                :value="forma_pago.id" v-text="forma_pago.nombre"></option>
                                        </select>
                                    </div>

                                    <div class="col-md-6"></div>

                                    <div class="col-md-12 mt-2">
                                        <label>Productos <span style="color:red;">(*Seleccione)</span></label>
                                        <br>
                                        <a href="#" class="btn btn-info text-white" @click="abrirModalP()"
                                            :disabled="isDisabledProducto">
                                            <i class="fa fa-search"></i> Agregar Productos
                                        </a>
                                    </div>
                                </form>
                            </div>

                            <br>

                            <div class="form-group row">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                                        <thead style="background-color: #46546C">
                                            <tr>
                                                <th scope="col" class="text-white">Eliminar</th>
                                                <th scope="col" class="text-white">Nombre</th>
                                                <th scope="col" class="text-white">Lote</th>
                                                <th scope="col" class="text-white">F. Vencimiento</th>
                                                <th scope="col" class="text-white">Cant. Presentación</th>
                                                <th scope="col" class="text-white">Presentación</th>
                                                <th scope="col" class="text-white">Cantidad</th>
                                                <th scope="col" class="text-white">Precio</th>
                                                <th scope="col" class="text-white">Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle, index) in arrayDetalle" :key="detalle.id_lote || index">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button"
                                                        class="btn btn-danger btn-sm">
                                                        <i class="icon-trash text-white"></i>
                                                    </button>
                                                </td>
                                                <td v-text="detalle.articulo"></td>
                                                <td v-text="detalle.lote"></td>
                                                <td v-text="detalle.fecha_vecimiento"></td>
                                                <td v-text="detalle.venta_cantidad"></td>
                                                <td>
                                                    <select class="form-select" v-model.number="detalle.contador"
                                                        @change="actualizarLineaDetalle(detalle)">
                                                        <option value="0">Unitario</option>
                                                        <option value="1">Blister</option>
                                                        <option value="2">Caja</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div v-if="datos.tipo_venta == 'Venta Directa'">
                                                        <input v-model.number="detalle.cantidad" type="number" min="0"
                                                            class="form-control" @input="actualizarLineaDetalle(detalle)">
                                                    </div>
                                                    <div v-else>
                                                        {{ detalle.cantidad }}
                                                    </div>
                                                    <span style="color:red;"
                                                        v-show="detalle.descuento_stock > parseFloat(detalle.stock)">
                                                        Stock insuficiente: {{ detalle.stock }}
                                                    </span>
                                                </td>
                                                <td v-text="detalle.costo_venta"></td>
                                                <td>
                                                    {{ detalle.sub_total.toFixed(2) }} bs
                                                </td>
                                            </tr>

                                            <tr style="background-color: #CEECF5">
                                                <td colspan="8" align="right"> <strong>Sub Total:</strong> </td>
                                                <td>{{ computedSubTotal }} bs</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="8" align="right"> <strong>Descuento:</strong> </td>
                                                <td>
                                                    <template v-if="datos.tipo_venta == 'Venta Directa'">
                                                        <vue-numeric v-model="datos.descuento" :precision="2"
                                                            class="form-control" :max="parseFloat(datos.sub_total)"></vue-numeric>
                                                    </template>
                                                    <template v-else>{{ datos.descuento }}</template>
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="8" align="right"> <strong>Total:</strong> </td>
                                                <td>{{ computedTotal }} bs</td>
                                            </tr>

                                            <template v-if="datos.id_forma_pago == 6">
                                                <tr style="background-color: #CEECF5">
                                                    <td colspan="8" align="right"> <strong>Total Efect.:</strong> </td>
                                                    <td>
                                                        <vue-numeric v-model="datos.total_efectivo" :precision="2"
                                                            class="form-control"></vue-numeric>
                                                    </td>
                                                </tr>
                                                <tr style="background-color: #CEECF5">
                                                    <td colspan="8" align="right"> <strong>Total Dep.:</strong> </td>
                                                    <td>{{ computedTotalDeposito }} bs</td>
                                                </tr>
                                            </template>

                                            <template v-if="datos.id_forma_pago == 6 || datos.id_forma_pago == 2">
                                                <tr style="background-color: #FF6775">
                                                    <td colspan="8" align="right" class="text-white"><strong>Efectivo:</strong></td>
                                                    <td>
                                                        <vue-numeric v-model="datos.efectivo" :precision="2"
                                                            class="form-control"></vue-numeric>
                                                    </td>
                                                </tr>
                                                <tr style="background-color: #FF6775">
                                                    <td colspan="8" align="right" class="text-white"><strong>Cambio:</strong></td>
                                                    <td class="text-white">{{ computedCambio }} bs</td>
                                                </tr>
                                            </template>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="9" class="text-center">No hay Productos agregados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end"
                                style='width:96%;margin-left: 2.2%'>
                                <button class="btn btn-danger me-md-2 text-white" type="button"
                                    @click="volverVentaListado()">Cancelar</button>
                                <button class="btn btn-info btn-lg text-white" type="button"
                                    @click="guardarVenta()">Guardar</button>
                            </div>
                        </div>
                    </template>

                    <template v-if="listado == 0 && estadoCaja == 'Cerrada'">
                        <div class="alert alert-warning alert-dismissable text-center">
                            <strong>¡Cuidado!</strong> Se requiere Aperturar Caja Primero.
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalArticulo" tabindex="-1" :class="{ 'mostrar': modalP }" role="dialog"
            aria-hidden="true" style="display: none; height:106%;">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <br><br>
                    <div class="modal-header btn btn-info text-white" style="height:45px;width:100%;padding-right:30px">
                        <h5 class="modal-title ">BUSQUEDA DE PRODUCTOS</h5>
                        <button type="button" class="btn-close btn-close-white" @click="cerrarModalP()"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <select class="form-select col-md-3" v-model="criterioP">
                                        <option value="articulo.nombre_comercial">Nombre Comercial</option>
                                        <option value="articulo.nombre_generico">Nombre Generico</option>
                                        <option value="unidad_medida.nombre">Presentación</option>
                                        <option value="proveedor.nombre">Laboratorio</option>
                                        <option value="articulo.descripcion">Acción Terapeutica</option>
                                        <option value="categoria.cod_proveedor">Cod. Proveedor</option>
                                    </select>&nbsp;&nbsp;

                                    <input type="text" v-model="buscarP"
                                        @keyup.enter="listarArticulo(1, buscarP, criterioP)" @keyup="BuscandoProducto()"
                                        class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;
                                    <div>
                                        <select class="form-select" v-model="datos.id_proveedor"
                                            @change="selecionarProductoProveedor(datos.id_proveedor)">
                                            <option value="0" disabled>Seleccione Laboratorio</option>
                                            <option v-for="proveedor in arrayProveedor" :key="proveedor.id"
                                                :value="proveedor.id" v-text="proveedor.nombre"></option>
                                        </select>
                                    </div>&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticulo(1, buscarP, criterioP)"
                                        class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>&nbsp;&nbsp;
                                    <button type="button" class="btn btn-danger text-white"
                                        @click="cerrarModalP()">Cerrar</button>
                                </div>
                            </div>
                        </div>&nbsp;

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead style="background-color: #46546C">
                                    <tr>
                                        <th scope="col" class="text-white">Nombre Comercial</th>
                                        <th scope="col" class="text-white">Nombre Generico</th>
                                        <th scope="col" class="text-white">Presentación</th>
                                        <th scope="col" class="text-white">Laboratorio</th>
                                        <th scope="col" class="text-white">F. Vencimiento</th>
                                        <th scope="col" class="text-white">Lote</th>
                                        <th scope="col" class="text-white">Precio Unidad</th>
                                        <th scope="col" class="text-white">Precio Blister</th>
                                        <th scope="col" class="text-white">Precio Caja</th>
                                        <th scope="col" class="text-white">Stock</th>
                                        <th scope="col" class="text-white">Opción</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayArticulo.length">
                                    <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                        <td :class="isVencido(articulo.fecha_vecimiento) ? 'bg-rojo' : ''"
                                            v-text="articulo.articulo"></td>
                                        <td :class="isVencido(articulo.fecha_vecimiento) ? 'bg-rojo' : ''"
                                            v-text="articulo.nombre_generico"></td>
                                        <td :class="isVencido(articulo.fecha_vecimiento) ? 'bg-rojo' : ''"
                                            v-text="articulo.presentacion"></td>
                                        <td :class="isVencido(articulo.fecha_vecimiento) ? 'bg-rojo' : ''"
                                            v-text="articulo.laboratorio"></td>
                                        <td :class="isVencido(articulo.fecha_vecimiento) ? 'bg-rojo' : ''"
                                            v-text="articulo.fecha_vecimiento"></td>
                                        <td :class="isVencido(articulo.fecha_vecimiento) ? 'bg-rojo' : ''"
                                            v-text="articulo.lote"></td>
                                        <td :class="isVencido(articulo.fecha_vecimiento) ? 'bg-rojo' : ''"
                                            v-text="articulo.costo_unitario"></td>
                                        <td :class="isVencido(articulo.fecha_vecimiento) ? 'bg-rojo' : ''"
                                            v-text="articulo.precio_blister"></td>
                                        <td :class="isVencido(articulo.fecha_vecimiento) ? 'bg-rojo' : ''"
                                            v-text="articulo.precio_caja"></td>
                                        <td :class="isVencido(articulo.fecha_vecimiento) ? 'bg-rojo' : ''"
                                            v-text="articulo.stock"></td>
                                        <td :class="isVencido(articulo.fecha_vecimiento) ? 'bg-rojo' : ''">
                                            <button type="button" @click="seleccionarTiendaArticulo(articulo)"
                                                class="btn btn-success btn-sm">
                                                <i class="fa fa-check text-white"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="11" class="text-center">No se encontraron productos</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer py-4">
                            <nav>
                                <ul class="pagination justify-content-end mb-0">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#"
                                            @click.prevent="cambiarPagina(pagination.current_page - 1)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page"
                                        :class="[page == pagination.current_page ? 'active' : '']">
                                        <a class="page-link" href="#"
                                            @click.prevent="cambiarPagina(page)">{{ page }}</a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#"
                                            @click.prevent="cambiarPagina(pagination.current_page + 1)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="quantity-modal-overlay" v-show="showQuantityModal"
            style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 10000; display: flex; align-items: center; justify-content: center;">
            <div class="quantity-modal-content"
                style="background: white; border-radius: 10px; padding: 30px; min-width: 400px; max-width: 500px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);">
                <div class="text-center mb-4">
                    <h5 class="mb-2" style="color: #333;font-size: 16px;">Ingrese la cantidad</h5>
                    <p class="text-dark mb-0" v-text="selectedProduct.articulo"
                        style="font-size: 22px;font-weight: 600;"></p>
                </div>
                <div class="form-group mb-4">
                    <label class="form-label" style="font-weight: 500; color: #555;">Cantidad:</label>
                    <input type="number" class="form-control text-center" v-model="quantityInput"
                        @keyup.enter="confirmarCantidad()" ref="quantityInputRef"
                        style="border: 2px solid #e9ecef; border-radius: 8px; padding: 12px 15px; font-size: 20px;"
                        :style="quantityError ? 'border-color: #dc3545;' : ''" placeholder="Ingrese cantidad" min="1"
                        :max="selectedProduct.stock">
                    <div v-if="quantityError" class="text-danger mt-2" style="font-size: 14px;">
                        <i class="fa fa-exclamation-circle me-1"></i> {{ quantityError }}
                    </div>
                    <div class="text-info mt-2" style="font-size: 13px;">
                        <i class="fa fa-info-circle me-1"></i> Stock disponible: {{ selectedProduct.stock }}
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary text-white" @click="cancelarCantidad()">
                        <i class="fa fa-times me-1"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-success text-white" @click="confirmarCantidad()"
                        :disabled="!quantityInput || quantityInput <= 0">
                        <i class="fa fa-check me-1"></i> Guardar
                    </button>
                </div>
            </div>
        </div>

        <frm-toast ref="toast"></frm-toast>
    </main>
</template>

<script>
import Swal from 'sweetalert2';
import moment from 'moment';

export default {
    data() {
        return {
            keywordCliente: '',    
            filteredClientes: [], 
            mostrarLista: false, 
            timeoutBuscador: null,

            setTimeoutCliente: null,
            showQuantityModal: false,
            quantityInput: '',
            quantityError: '',
            selectedProduct: {},
            modalP: 0,
            isVisible: false,
     
            isDisabledProducto: false,
   
            datos: {
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
                id_descuento: ''
            },
            datosPago: {
                id: 0,
                fecha: moment().format('YYYY-MM-DD'),
                fecha_final: moment().format('YYYY-MM-DD'),
                saldo: 0,
                anticipo: 0,
                descripcion: '',
            },


            arrayDetalle: [],
            arrayArticulo: [],
            arrayProductoPaquete: [],
            arrayCliente: [],
            arrayPago: [],
            arrayForma: [],
            arrayForma2: [],
            arrayProveedor: [],
            

            listado: 0,
            estadoCaja: '',
            criterioP: 'articulo.nombre_comercial',
            buscarP: '',
            buscarCliente: '',
            setTimeoutBuscador: null,
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
            },
            offset: 3,
            anio: moment().format('YYYY'),
        }
    },
    computed: {
        computedSubTotal() {
            let resultado = 0;
            if (this.datos.tipo_venta == 'Venta Directa') {
                resultado = this.arrayDetalle.reduce((sum, item) => sum + item.sub_total, 0);
            } else {
                resultado = this.arrayDetalle.reduce((sum, item) => sum + (item.costo_venta * item.cantidad), 0);
            }
            this.datos.sub_total = resultado.toFixed(2);
            return this.datos.sub_total;
        },
        computedTotal() {
            const total = parseFloat(this.datos.sub_total) - parseFloat(this.datos.descuento || 0);
            this.datos.total = total.toFixed(2);
            return this.datos.total;
        },
        computedTotalDeposito() {
            const dep = parseFloat(this.datos.total) - parseFloat(this.datos.total_efectivo || 0);
            this.datos.total_deposito = dep.toFixed(2);
            return dep.toFixed(2);
        },
        computedCambio() {
            let baseResta = (this.datos.id_forma_pago == 6) ? this.datos.total_efectivo : this.datos.total;
            const cambio = parseFloat(this.datos.efectivo || 0) - parseFloat(baseResta);
            this.datos.cambio = cambio > 0 ? cambio.toFixed(2) : "0.00";
            return this.datos.cambio;
        },
        filteredCliente() {
            return this.arrayCliente;
        },
        pagesNumber() {
            if (!this.pagination.to) return [];
            let from = this.pagination.current_page - this.offset;
            if (from < 1) from = 1;
            let to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) to = this.pagination.last_page;
            
            const pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },
    methods: {
 
        limpiarCliente() {
            this.keywordCliente = '';
            this.datos.id_cliente = 1; 
            this.datos.cliente = 'Público General';
            this.datos.id_descuento = 0;
            this.filteredClientes = [];
            this.mostrarLista = false;
        },

        // async listarClientesAPI(busqueda) {
        //     try {
        //         const url = `/cliente/selectCliente?filtro=${encodeURIComponent(busqueda)}`;
        //         const { data } = await axios.get(url);
        //         this.filteredClientes = data.data ? data.data : data; 
        //     } catch (error) {
        //         console.error(error);
        //         this.filteredClientes = [];
        //     } finally {
        //         this.is_busy_cliente = false;
        //     }
        // },
        async listarClientesAPI(busqueda) {

            this.datos.id_cliente = 0; 
            this.datos.cliente = busqueda; 
            try {
                const url = `/cliente/selectCliente?filtro=${encodeURIComponent(busqueda)}`;
                const { data } = await axios.get(url);
                this.filteredClientes = data.data ? data.data : data; 
            } catch (error) {
                console.error(error);
                this.filteredClientes = [];
            } finally {
                this.is_busy_cliente = false;
            }
        },

        selectedCliente(cliente) {
            console.log("Cliente seleccionado:", cliente);
            this.keywordCliente = cliente.nombre;
            this.datos.id_cliente = cliente.id; 
            this.datos.cliente = cliente.nombre;
            this.filteredClientes = [];
        },

        guardarCliente(val) {
            if(!val){
                this.datos.id_cliente = 1;
            }
        },

        seleccionarTiendaArticulo(data) {
            if (this.arrayDetalle.some(item => item.id_lote === data.id)) {
                this.$refs.toast.error('Este lote ya se encuentra agregado!');
                return;
            }

            this.selectedProduct = { ...data };
            this.quantityInput = '';
            this.quantityError = '';
            this.showQuantityModal = true;
            
            this.$nextTick(() => {
                if (this.$refs.quantityInputRef) this.$refs.quantityInputRef.focus();
            });
        },

        cancelarCantidad() {
            this.showQuantityModal = false;
            this.selectedProduct = {};
        },

        confirmarCantidad() {
            const cant = parseInt(this.quantityInput);

            if (!cant || cant <= 0) {
                this.quantityError = 'La cantidad debe ser mayor a 0';
                return;
            }
            if (cant > parseInt(this.selectedProduct.stock)) {
                this.quantityError = 'Cantidad supera el stock disponible';
                return;
            }

            const det = {
                ...this.selectedProduct, 
                id_lote: this.selectedProduct.id,
                id_tienda_articulo: this.selectedProduct.id_articulo,
                cantidad: cant,
                contador: 0,
                venta_cantidad: this.selectedProduct.venta_presentacion,
                costo_venta: this.selectedProduct.costo_unitario,
                descuento_stock: 0,
                sub_total: 0,
                producto_venta: 'Venta Producto',
                stock: parseInt(this.selectedProduct.stock)
            };

            this.actualizarLineaDetalle(det);
            this.arrayDetalle.push(det);

            this.datos.estado = 'Entregado';
            this.showQuantityModal = false;
            this.$refs.toast.success('Producto agregado...');
        },

        eliminarDetalle(index) {
            const item = this.arrayDetalle[index];
            if (item.producto_venta == 'Venta Paquete') {
                this.arrayProductoPaquete = this.arrayProductoPaquete.filter(p => p.id_paquete != item.id_paquete);
            }
            this.arrayDetalle.splice(index, 1);
        },

        actualizarLineaDetalle(detalle) {
            let factorConversion = 0;
            let precioUnitarioAplicado = 0;
            let cantidadVisual = parseFloat(detalle.cantidad) || 0;

            switch (parseInt(detalle.contador)) {
                case 0:
                    factorConversion = parseFloat(detalle.venta_presentacion || 1);
                    precioUnitarioAplicado = parseFloat(detalle.costo_unitario);
                    detalle.venta_cantidad = detalle.venta_presentacion;
                    break;
                case 1: 
                    factorConversion = parseFloat(detalle.cantidad_blister || 0);
                    precioUnitarioAplicado = parseFloat(detalle.precio_blister);
                    detalle.venta_cantidad = detalle.cantidad_blister;
                    break;
                case 2:
                    factorConversion = parseFloat(detalle.cantidad_caja || 0);
                    precioUnitarioAplicado = parseFloat(detalle.precio_caja);
                    detalle.venta_cantidad = detalle.cantidad_caja;
                    break;
            }

            detalle.descuento_stock = cantidadVisual * factorConversion;
            detalle.costo_venta = precioUnitarioAplicado;
            
            if (this.datos.tipo_venta === 'Venta Directa') {
                detalle.sub_total = cantidadVisual * precioUnitarioAplicado;
            } else {
                detalle.sub_total = detalle.costo_venta * cantidadVisual;
            }
        },

        async listarArticulo(page = 1, buscar = '', criterio = '') {
            try {
                const url = `/tienda/listarSinPaginateVenta?page=${page}&buscar=${encodeURIComponent(buscar)}&criterio=${encodeURIComponent(criterio)}`;
                const { data } = await axios.get(url);
                this.arrayArticulo = data.data;
                this.pagination = {
                    total: data.total,
                    current_page: data.current_page,
                    per_page: data.per_page,
                    last_page: data.last_page,
                    from: data.from,
                    to: data.to
                };
            } catch (error) {
                console.error("Error listando artículos:", error);
            } finally {}
        },

        async verificarCaja() {
            try {
                const { data } = await axios.get('/arqueo_caja/estado_caja');
                this.estadoCaja = data.estado;
            } catch (error) {
                console.error(error);
            }
        },

        async selectCliente(buscar = '') {
            try {
                const url = `/cliente/selectCliente?filtro=${encodeURIComponent(buscar)}`;
                const { data } = await axios.get(url);
                this.arrayCliente = data.data ? data.data : data; 
                
            } catch (error) {
                console.error("Error buscando clientes:", error);
            } finally {}
        },

        onClienteKeyup(keyword) {
            this.guardarCliente();
            this.isVisible = true; 
            clearTimeout(this.setTimeoutCliente);
            if(this.datos.cliente == ''){
                this.selectCliente('');
                return;
            }
            this.setTimeoutCliente = setTimeout(() => {
                this.selectCliente(keyword);
            }, 350);
        },


        async selectProveedor() {
            try {
                const { data } = await axios.get('/proveedor/selectProveedor');
                this.arrayProveedor = data;
            } catch (error) { console.error(error); }
        },

        async selectTipoP() {
            try {
                const { data } = await axios.get('/tipoPago/selectTipoP');
                this.arrayPago = data;
            } catch (error) { console.error(error); }
        },

        async selectFormaP() {
            try {
                const { data } = await axios.get('/formaPago/selectFormaP');
                this.arrayForma = data;
                this.arrayForma2 = data.filter(item => item.id !== 1);
            } catch (error) { console.error(error); }
        },

        async selecionarProductoProveedor(proveedorId) {
            this.buscarCliente = '';
            this.isVisible = false;
            try {
                const url = `/tienda/listarSinPaginateVenta?buscar=${this.buscarP}&criterio=${this.criterioP}&id_proveedor=${proveedorId}`;
                const { data } = await axios.get(url);
                this.arrayArticulo = data;
            } catch (error) { console.error(error); }
        },


        async guardarVenta() {
            if (!this.keywordCliente || this.keywordCliente.trim() === '') {
                this.datos.cliente = 'SN';
                this.datos.id_cliente = 1;
            } 
            else {
                this.datos.cliente = this.keywordCliente.trim();
                if (this.datos.id_cliente === 0 || (this.datos.id_cliente === 1 && this.datos.cliente !== 'SN')) {
                    this.datos.id_cliente = 0; 
                }
            }

            if (this.arrayDetalle.length <= 0) return this.mostrarAlerta('No hay productos agregados.');
            if (this.arrayDetalle.some(item => item.cantidad <= 0)) return this.mostrarAlerta('Cantidad inválida en algún producto.');
            if (this.arrayDetalle.some(item => (item.stock - item.descuento_stock) < 0)) return this.mostrarAlerta('Stock insuficiente.');
            if (parseFloat(this.datos.total) < 0) return this.mostrarAlerta('El total no puede ser negativo.');

            Swal.fire({
                title: 'Procesando Venta',
                html: 'Guardando información...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            try {
                const payload = {
                    ...this.datos,
                    id_servicio: this.datos.id,
                    fecha_final: this.datosPago.fecha_final,
                    descripcion_pago: this.datosPago.descripcion,
                    saldo: this.datosPago.saldo,
                    detalle: this.arrayDetalle,
                    monto_total: this.datos.total,
                    id_forma_pago: (this.datos.id_tipo_pago == 2) ? this.arrayForma[0].id : this.datos.id_forma_pago,
                    id_costo_pago: this.datos.id_descuento,
                    stock_producto_paquete: this.arrayProductoPaquete
                };

                await axios.post('/venta/guardar_tienda1', payload);

                Swal.fire({
                    icon: 'success',
                    title: 'Venta registrada',
                    showConfirmButton: false,
                    timer: 1500
                });

                this.cargarPdf2();
                this.volverVentaListado();
                this.limpiarDatosVenta();
                this.listarArticulo();
                this.selectCliente();

            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'Error en el servidor al guardar la venta', 'error');
            } finally{
                this.limpiarCliente();
            }
        },

        mostrarAlerta(mensaje) {
            Swal.fire({ icon: 'error', title: 'Error...', text: mensaje });
        },

        cargarPdf2() {
            axios.get('/venta/pdfVentasGeneral2', { responseType: 'blob' })
                .then(response => {
                    const url = URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
                    window.open(url, '_blank');
                });
        },

        volverVentaListado() {
            this.limpiarDatosVenta();
            this.listado = 0;
            this.arrayDetalle = [];
        },

        limpiarDatosVenta() {
            this.datos = {
                ...this.datos,
                fecha: moment().format('YYYY-MM-DD'),
                sub_total: 0,
                descuento: 0,
                total: 0,
                cambio: 0,
                efectivo: 0,
                total_efectivo: 0,
                total_deposito: 0,
                cliente: '',
                id_cliente: 1,
                id_forma_pago:2,
                id_tipo_pago:1,
            };
            this.isDisabledProducto = false;
        },

        BuscandoProducto() {
            clearTimeout(this.setTimeoutBuscador);
            this.setTimeoutBuscador = setTimeout(() => {
                this.listarArticulo(1, this.buscarP, this.criterioP);
            }, 400);
        },

        toggleDropdown() {
            this.isVisible = !this.isVisible;
            if (this.isVisible) {
                setTimeout(() => this.isVisible = false, 10000);
            }
        },

        abrirModalP() {
            this.buscarP = '';
            this.listarArticulo(1, '', this.criterioP);
            this.modalP = 1;
        },

        cerrarModalP() {
            this.modalP = 0;
            this.buscarP = '';
            this.listarArticulo(1, '', this.criterioP);
        },

        cambiarPagina(page) {
            this.pagination.current_page = page;
            this.listarArticulo(page, this.buscarP, this.criterioP);
        },

        isVencido(fecha) {
            return this.anio >= moment(fecha).format('YYYY');
        }
    },
    async mounted() {
        await Promise.all([
            this.listarArticulo(),
            this.selectCliente(),
            this.selectProveedor(),
            this.selectTipoP(),
            this.selectFormaP(),
            this.verificarCaja()
        ]);
    }
}
</script>

<style scoped lang="scss">

.quantity-modal-overlay {
    animation: fadeIn 0.1s ease-out;
}
.quantity-modal-content {
    animation: slideIn 0s ease-out;
    transform: scale(1);
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.2s ease;
    }
    .form-control:focus {
        border-color: #17a2b8;
        box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
    }
}

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes slideIn {
    from { opacity: 0; transform: translateY(-20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.dropdown-wrapper {
    position: relative;
    z-index: 2;
    .selected-item {
        height: 35px; 
        border-radius: 5px;
        padding: 0 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        .drop-down-icon {
            transform: rotate(0deg);
            transition: all 0.5s ease;
            &.dropdown { transform: rotate(180deg); }
        }
    }
    .dropdown-popover {
        position: absolute;
        border: 1px solid lightgray;
        top: 40px;
        left: 0;
        right: 0;
        background-color: #fff;
        max-width: 100%;
        padding: 10px;
        visibility: hidden;
        transition: all 0.2s linear;
        max-height: 0px;
        overflow: hidden;
        z-index: 100;
        &.visible {
            max-height: 450px;
            visibility: visible;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .options ul {
            list-style: none;
            text-align: left;
            padding-left: 0;
            max-height: 200px;
            overflow-y: auto;
            li {
                border-bottom: 1px solid #eee;
                padding: 8px;
                cursor: pointer;
                &:hover {
                    background: #44536E;
                    color: #fff;
                }
            }
        }
    }
}

.modal-content { width: 100% !important; }
.mostrar {
    display: block !important;
    opacity: 1 !important;
    position: fixed !important;
    background-color: rgba(0,0,0,0.5) !important;
}
.bg-rojo { background-color: #FFD2D6 !important; }
.sinborde { border: 0; }


.hover-bg:hover {
    background-color: #f1f3f5; /* Gris muy suave al pasar el mouse */
    transition: background-color 0.2s;
}
.cursor-pointer {
    cursor: pointer;
}
/* Animación suave de aparición */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
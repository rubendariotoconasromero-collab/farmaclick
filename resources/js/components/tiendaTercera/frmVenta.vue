<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                    <div class="card">
                    <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">VENTA</h3></div>
                        <!-- Listado de Ventas -->
                        <template v-if="listado==0 && estadoCaja == 'Abierta'">
                            <div class="card-body">
                                <div class="form-group row" style='margin-left: 1%'>   
                                <form class="row">
                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1" class="form-label">Cliente</label>
                                        <div class="input-group mb-6">
                                            <!-- <input type="text" readonly class="form-control" placeholder="Buscar Clientes.."  v-model="datos.cliente" aria-label="Buscar Productos.." aria-describedby="button-addon2"> -->

                                            <section class="dropdown-wrapper form-control bg-disabled">
                                                <div @click="isVisible = !isVisible" class="selected-item">
                                                    <span v-if="datos.cliente==''">Seleccione Cliente</span>
                                                    <span v-else>{{datos.cliente }}</span>
                                                    <svg :class="isVisible && datos.tipo_venta != 'Venta Servicio' ? 'dropdown' : ''" class="drop-down-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>
                                                </div>
                                                <div :class="isVisible && datos.tipo_venta != 'Venta Servicio' ? 'visible' : 'invisible'" class="dropdown-popover">
                                                    <input type="text" class="form-control" placeholder="Buscar Clientes.."  v-model="buscarCliente" aria-label="Buscar Productos..">
                                                    <div class="text-center"><span v-if="filteredCliente.length === 0">No existen Clientes</span></div>
                                                    <div class="options">
                                                        <ul>
                                                            <li @click="selectedCliente(cliente)" v-for="(cliente, index) in filteredCliente" :key="`cliente-${index}`">{{cliente.nombre}}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </section>

                                            &nbsp;&nbsp;&nbsp;
                                            <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalCliente" @click="listarCliente(buscarP)" :disabled="isDisabledCliente"><i class="fa fa-search"></i> Agregar Clientes</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                        <input type="date" class="form-control"  v-model="datos.fecha" disabled>  
                                    </div>
                                    <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Tipo Pago</label>
                                        <select class="form-select" v-model="datos.id_tipo_pago">
                                            <option value="0" disabled>Seleccione el Tipo Pago</option>
                                            <option v-for="tipo_pago in arrayPago" :key="tipo_pago.id" :value="tipo_pago.id" v-text="tipo_pago.nombre"></option>
                                        </select>  
                                    </div>
                                    <div class="col-md-6" v-if="datos.id_tipo_pago == 2">
                                        <label for="exampleInputPassword1" class="form-label">Fecha Final</label>
                                        <input type="date" class="form-control"  v-model="datosPago.fecha_final">  
                                    </div>
                                    <div class="col-md-6" v-if="datos.id_tipo_pago == 1">
                                        <label for="exampleInputPassword1" class="form-label">Forma Pago</label>
                                        <template>
                                            <select class="form-select" v-model="datos.id_forma_pago">
                                                <option value="0" disabled>Seleccione la Forma de Pago</option>
                                                <option v-for="forma_pago in arrayForma2" :key="forma_pago.id" :value="forma_pago.id" v-text="forma_pago.nombre"></option>
                                            </select>  
                                        </template> 
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Pagos</label>
                                        <template v-if="datos.id_descuento == 1">
                                            <input value="Unitario" type="text"  id="Unitario" class="form-control" disabled>
                                        </template> 
                                        <template v-if="datos.id_descuento == 2">
                                            <input value="Mayorista" type="text"  id="Mayorista" class="form-control" disabled>
                                        </template>
                                        <template v-if="datos.id_descuento == 3">
                                            <input value="Preferencial" type="text"  id="Preferencial" class="form-control" disabled>
                                        </template>
                                        <template v-if="datos.id_descuento == 0">
                                            <input value="" type="text"  class="form-control" disabled>
                                        </template> 
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1" class="form-label">Orden de Servicio</label>
                                        <div class="input-group mb-6">
                                            <input type="text" readonly class="form-control" placeholder="Buscar Orden.."  v-model="datos.estado" aria-label="Buscar Orden.." aria-describedby="button-addon2">
                                            &nbsp;&nbsp;&nbsp;
                                            <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalServicio" @click="listarOrden(buscarP)" :disabled="isDisabledOrden"><i class="fa fa-search"></i> Seleccionar Orden</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    </div>&nbsp;
                                    <div class="col-md-12">
                                        <label>Productos<span style="color:red;" >(*Seleccione)</span></label>
                                        <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalArticulo" @click="listarArticulo(buscarP)"  :disabled="isDisabledProducto"><i class="fa fa-search"></i> Agregar Productos</button>
                                    </div> 
                                </form>           
                                    <div class="col-md-12">
                                        <div v-show="errorPago" class="form-group row div-error">
                                            <div class="text-center text-error">
                                                <div v-for="error in errorMostrarMsjPago" :key="error" v-text="error">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="table-responsive">
                                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                                        <thead style="background-color: #46546C">
                                            <tr>                      
                                                <th scope="col" class="text-white">Opción</th>
                                                <th scope="col" class="text-white">Categoria</th>
                                                <th scope="col" class="text-white">Nombre</th>
                                                <th scope="col" class="text-white">Marca</th>
                                                <th scope="col" class="text-white">Tienda</th>
                                                <th scope="col" class="text-white">Precio</th>
                                                <th scope="col" class="text-white">Cantidad</th>
                                                <th scope="col" class="text-white">Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                                </td>
                                                <td v-text="detalle.categoria"></td>
                                                <td v-text="detalle.articulo"></td>
                                                <td v-text="detalle.marca"></td>
                                                <td v-text="detalle.tienda"></td>
                                                <td>
                                                    <div v-if="datos.tipo_venta=='Venta Directa'">
                                                        <template v-if="datos.id_descuento == 1">
                                                            <vue-numeric v-model="detalle.costo_unitario" :precision="2" id="Unitario" class="form-control"></vue-numeric>
                                                        </template> 
                                                        <template v-if="datos.id_descuento == 2">
                                                            <vue-numeric v-model="detalle.costo_mayorista" :precision="2" id="Mayorista" class="form-control"></vue-numeric>
                                                        </template>
                                                        <template v-if="datos.id_descuento == 3">
                                                            <vue-numeric v-model="detalle.costo_preferencial" :precision="2" id="Preferencial" class="form-control"></vue-numeric>
                                                        </template>
                                                        <template v-if="datos.id_descuento != 1 && datos.id_descuento != 2 && datos.id_descuento != 3">
                                                            <vue-numeric :value='0' :precision="2"  id="Preferencial" class="form-control"></vue-numeric>
                                                        </template> 
                                                    </div>
                                                    <div v-if="datos.tipo_venta=='Venta Servicio'">
                                                        {{detalle.costo_venta}}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div v-if="datos.tipo_venta=='Venta Directa'">
                                                        <vue-numeric v-model="detalle.cantidad" type="number" value="3" class="form-control"></vue-numeric>
                                                    </div>
                                                    <div v-if="datos.tipo_venta=='Venta Servicio'">
                                                        {{detalle.cantidad}}
                                                    </div>
                                                    <span style="color:red;" v-show="detalle.cantidad>detalle.stock && datos.tipo_venta!='Venta Servicio'">Stock: {{detalle.stock}}</span>
                                                </td>
                                                <td>
                                                    <div v-if="datos.tipo_venta=='Venta Directa'">
                                                        <template v-if="datos.id_descuento == 1">
                                                                {{detalle.sub_total = detalle.costo_unitario*detalle.cantidad}}
                                                        </template>   
                                                        <template v-if="datos.id_descuento == 2">
                                                                {{detalle.sub_total = detalle.costo_mayorista*detalle.cantidad}}
                                                        </template>
                                                        <template v-if="datos.id_descuento == 3">
                                                                {{detalle.sub_total = detalle.costo_preferencial*detalle.cantidad}}
                                                        </template>
                                                    </div>
                                                    <div v-if="datos.tipo_venta=='Venta Servicio'">
                                                        {{detalle.sub_total = detalle.costo_venta*detalle.cantidad}} bs
                                                    </div>
                                                </td>                                                                             
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="7" align="right"> <strong>Sub Total:</strong> </td>
                                                <td>{{datos.sub_total = calcularSubTotal.toFixed(2)}} bs</td> 
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="7" align="right"> <strong>Descuento:</strong> </td>
                                                <td>
                                                    <div v-if="datos.tipo_venta=='Venta Directa'">
                                                        <vue-numeric v-model="datos.descuento" :precision="2" value="0" class="form-control" :max="parseFloat(datos.sub_total)"></vue-numeric>
                                                    </div>
                                                    <div v-if="datos.tipo_venta=='Venta Servicio'">
                                                        {{datos.descuento}}
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="7" align="right"> <strong>Total:</strong> </td>
                                                <td>{{datos.total = datos.sub_total- datos.descuento}} bs</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="8">No hay Productos agregados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end" style='width:96%;margin-left: 2.2%'>
                                    <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverVentaListado()">Cancelar</button>
                                    <button class="btn btn-info btn-lg text-white" type="button" @click="guardarVenta()">Guardar</button>
                                </div>
                            </div>
                        </template>
                        <template v-if="listado==0 && estadoCaja == 'Cerrada'">
                            <div class="alert alert-warning alert-dismissable text-center">
                                <strong>¡Cuidado!</strong> Se requiere Aperturar Caja Primero.
                            </div>
                        </template>
                        <!-- Fin Listado de Ventas -->
                    </div>
                </div>
            </div>
        <!-- </div>   -->
        <!--Modal Formulario Producto-->
        <div class="modal fade" id="modalArticulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white" style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDA DE PRODUCTOS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(buscarP)" @keyup="BuscandoProducto()" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticulo(buscarP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>                      
                                    <th scope="col" class="text-white">Categoria</th>  
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">Marca</th>
                                    <th scope="col" class="text-white">Tienda</th>
                                    <th scope="col" class="text-white">Precio Unitario</th>
                                    <th scope="col" class="text-white">Precio Mayorista</th>
                                    <th scope="col" class="text-white">Precio Preferencial</th>
                                    <th scope="col" class="text-white">stock</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayArticulo.length">
                                <tr v-for="tienda_articulo in arrayArticulo" :key="tienda_articulo.id">
                                    <td v-text="tienda_articulo.categoria"></td>
                                    <td v-text="tienda_articulo.articulo"></td>
                                    <td v-text="tienda_articulo.marca"></td>
                                    <td v-text="tienda_articulo.tienda"></td>
                                    <td v-text="tienda_articulo.costo_unitario"></td>
                                    <td v-text="tienda_articulo.costo_mayorista"></td>
                                    <td v-text="tienda_articulo.costo_preferencial"></td>
                                    <td v-text="tienda_articulo.stock"></td>
                                    <td>
                                        <button type="button" @click="seleccionarTiendaArticulo(tienda_articulo)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>   
                                    </td>                                 
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="9">No hay Productos agregados</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal" @click="limpiarArticulo()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin moda Formulario Producto-->

        <!--Modal Formulario Cliente-->
        <div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white"  style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDAS DE CLIENTES</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarCliente(buscarP)" @keyup="BuscandoCliente()" class="form-control" placeholder="Texto a buscar" :disabled="isDisabledCliente">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarCliente(buscarP)" class="btn btn-info text-white" :disabled="isDisabledCliente"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>                      
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">CI/NIT</th>
                                    <th scope="col" class="text-white">Telefono</th>
                                    <th scope="col" class="text-white">Direccion</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayTipoCliente.length">
                                <tr v-for="cliente in arrayTipoCliente" :key="cliente.id">
                                    <td v-text="cliente.nombre"></td>
                                    <td v-text="cliente.matricula"></td>
                                    <td v-text="cliente.telefono"></td>
                                    <td v-text="cliente.direccion"></td>
                                    <td>
                                        <button type="button"  data-dismiss="modal" class="btn btn-success btn-sm" data-bs-dismiss="modal"  :disabled="isDisabledCliente" @click="seleccionarCliente(cliente)"><i class="fa fa-check text-white"></i></button> 
                                    </td>                                 
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="5">No hay Cliente agregados</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal" @click="limpiarCliente()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin modal Formulario Cliente-->

        <!--Modal Formulario Orden de Servicio-->
        <div class="modal fade" id="modalServicio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white"  style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDAS ORDEN DE SERVICIO</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarOrden(buscarP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarOrden(buscarP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead style="background-color: #46546C">
                                    <tr>                      
                                        <th scope="col" class="text-white">Nombre</th>
                                        <th scope="col" class="text-white">Personal</th>
                                        <th scope="col" class="text-white text-center">Estado</th>
                                        <th scope="col" class="text-white">Descuento</th>
                                        <th scope="col" class="text-white">Sub total</th>
                                        <th scope="col" class="text-white">Total</th>
                                        <th scope="col" class="text-white">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayOrden.length">
                                    <tr v-for="orden_servicio in arrayOrden" :key="orden_servicio.id">
                                        <td v-text="orden_servicio.cliente"></td>
                                        <td v-text="orden_servicio.personal"></td>
                                        <td class="text-center">
                                            <template v-if="orden_servicio.estado=='Recepcionado'">
                                                <span class="badge bg-info tamaño">{{orden_servicio.estado}}</span>                                           
                                            </template>
                                            <template v-if="orden_servicio.estado=='Concluido'">
                                                <span class="badge bg-warning tamaño">{{orden_servicio.estado}}</span>
                                            </template>
                                            <template v-if="orden_servicio.estado=='Entregado'">
                                                <span class="badge bg-success tamaño">{{orden_servicio.estado}}</span>
                                            </template>
                                            <template v-if="orden_servicio.estado=='Anulado'">
                                                <span class="badge bg-danger tamaño">{{orden_servicio.estado}}</span>                                           
                                            </template>
                                        </td> 
                                        <td v-text="orden_servicio.descuento"></td>
                                        <td v-text="orden_servicio.sub_total"></td>
                                        <td v-text="orden_servicio.total"></td>
                                        <td>
                                            <button type="button"  data-dismiss="modal" @click="seleccionarOrdenServicio(orden_servicio)" class="btn btn-success btn-sm" data-bs-dismiss="modal"><i class="fa fa-check text-white"></i></button> 
                                        </td>                                 
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="8">No hay Orden de Servicio</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal" @click="limpiarDatosVenta()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin modal Formulario Orden de Servicio-->
    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    import moment from 'moment';
    export default {
        created() {
            this.datos.costo_pago = 1;
        },
        data(){
            return {
                datos : {
                    id : 0,
                    fecha :  moment().format('YYYY-MM-DD'),
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_tipo_pago : 1,
                    id_forma_pago : 2,
                    id_cliente : 0,
                    cliente : '',
                    tipoPago : '',
                    formaPago : '',
                    costo_pago : '',
                    id_descuento: '',
                    personal:'',
                    estado:'',
                    tipo_venta: 'Venta Directa',
                    id_orden_servicio: null,
                },
                datosPago:{
                    id: 0,
                    fecha :  moment().format('YYYY-MM-DD'),
                    fecha_final : moment().format('YYYY-MM-DD'),
                    //monto_total: 0,
                    saldo : 0,
                    anticipo : 0,
                    descripcion: '',
                },   
                arrayVenta : [],
                arrayDetalle : [],
                arrayArticulo : [],
                arrayCliente: [],
                arrayCostoPago: [{id:1,nombre:'Unitario'},{id:2,nombre:'Mayorista'},{id:3,nombre:'Preferencial'}],
                arrayTipoCliente: [],
                arrayPersonal: [],
                arrayPago: [],
                arrayForma: [],
                arrayForma2: [],
                arrayOrden : [],  
                listado : 0,
                tipoAccion : 0,
                errorPago : 0,
                errorMostrarMsjPago : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : '',
                buscarP : '',
                isDisabled: true,
                isDisabledCliente: false,
                isDisabledProducto: false,
                isDisabledOrden: false,
                setTimeoutBuscador: '',
                isVisible: false,
                buscarCliente: '',
                estadoCaja: '',
            }
        },
        computed : {
            isActived: function(){
                return this.pagination.current_page;
            },
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }                
                var from = this.pagination.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            calcularSubTotal: function(){
                var resultado = 0.0;
                if(this.datos.tipo_venta=='Venta Directa') {
                    for(var i=0;i<this.arrayDetalle.length;i++){
                        if(this.datos.id_descuento ==1){
                        resultado = resultado + (this.arrayDetalle[i].costo_unitario*this.arrayDetalle[i].cantidad);
                        }else if(this.datos.id_descuento ==2)
                        { resultado = resultado + (this.arrayDetalle[i].costo_mayorista*this.arrayDetalle[i].cantidad);
                        }else if(this.datos.id_descuento ==3){
                        resultado = resultado + (this.arrayDetalle[i].costo_preferencial*this.arrayDetalle[i].cantidad);
                        }else
                        {}
                    }
                } else {
                    for(var i=0;i<this.arrayDetalle.length;i++){
                        resultado = resultado + (this.arrayDetalle[i].costo_venta*this.arrayDetalle[i].cantidad);
                    }
                }
                
                return resultado;
            },
            calcularSaldoAnticipado: function(){
                this.datosPago.saldo = this.datos.total - this.datosPago.anticipo;
            },
            filteredCliente(){
                const data = this.buscarCliente.toLowerCase();
                if(this.buscarCliente == ""){
                    return this.arrayCliente;
                }
                return this.arrayCliente.filter((item)=>{
                    return Object.values(item).some((word=>String(word).toLowerCase().includes(data)))
                })
            }
        },
        methods : {
            verificarCaja(){
                let me = this;
                var url='/arqueo_caja/estado_caja';
                axios.get(url).then(function(response){
                    me.estadoCaja= response.data.estado;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarArticulo(buscarP){
                let me = this;
                me.buscarCliente= '';
                me.isVisible = false;
                var url='/tienda/listarSinPaginate2/tienda3?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayArticulo= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarArticuloBusquedaRapida(){
                let me = this;
                me.buscarCliente= '';
                me.isVisible = false;
                var url='/tienda/listarSinPaginate2/tienda3?buscar=' + me.buscarP;
                axios.get(url).then(function(response){
                    me.arrayArticulo= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            BuscandoProducto(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticuloBusquedaRapida,350)
            },
            listarCliente(buscarP){
                let me = this;
                me.buscarCliente= '';
                me.isVisible = false;
                var url='/cliente/listarSinPaginate?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayTipoCliente= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarClienteBusquedaRapida(){
                let me = this;
                var url='/cliente/listarSinPaginate?buscar=' + me.buscarP;
                axios.get(url).then(function(response){
                    me.arrayTipoCliente= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            BuscandoCliente(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarClienteBusquedaRapida,350)
            },
            selectedCliente(cliente){
                this.datos.cliente='';
                this.datos.id_cliente=0;
                this.datos.id_descuento='';
                this.isVisible = false;
                this.datos.cliente = cliente.nombre;
                this.datos.id_cliente = cliente.id;
                this.datos.id_descuento = cliente.descuento;
            },
            listarOrden(buscarP){
                let me = this;
                me.buscarCliente= '',
                me.isVisible = false;
                var url='/servicio/listarOrdenSinPaginate_tienda3?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayOrden= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].id_articulo==id){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            
            seleccionarTiendaArticulo(data=[]){
                let me = this;
                if(me.encuentra(data['id_articulo'])){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Este producto ya se encuentra agregado!'
                    })
                }
                else{
                    me.arrayDetalle.push({
                        id_tienda_articulo : data['id'],
                        id_articulo : data['id_articulo'],
                        articulo : data['articulo'],
                        tienda : data['tienda'],
                        costo_unitario : data['costo_unitario'],
                        costo_mayorista : data['costo_mayorista'],
                        costo_preferencial : data['costo_preferencial'],
                        costo_compra : data['costo_compra'],
                        marca : data['marca'],
                        id_categoria : data['id_categoria'],
                        categoria : data['categoria'],
                        stock : data['stock'],
                        cantidad : 1,
                        sub_total : data['sub_total'],
                    });
                    me.datos.estado= 'Entregado';
                    me.datos.tipo_venta= 'Venta Directa'
                    me.isDisabled = false;
                    me.isDisabledOrden = true;
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Producto agregado...',
                        showConfirmButton: false,
                        timer: 500
                    });
                }
            },

            seleccionarCliente(data=[]){
                this.buscarCliente= '',
                this.datos.id_cliente=data['id'];
                this.datos.cliente= data['nombre'];
                this.datos.id_descuento= data['descuento'];
            },
            volverVentaListado(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.cliente = '';
                me.datos.estado = '';
                me.datos.id_tipo_pago = 1,
                me.datos.id_forma_pago = 2,
                me.buscarP = '';
                me.listado = 0;
                me.isDisabledCliente = false;
                me.isDisabledProducto = false;
                me.isDisabledOrden = false;
                me.buscar = '';
            },
            selectCostoPago(){
                let me=this;
                var url='/articulo/selectPrecio';
                axios.get(url).then(function(response){
                    me.arrayCostoPago=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectCliente(){
                let me=this;
                var url='/cliente/selectCliente';
                axios.get(url).then(function(response){
                    me.arrayCliente=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectTipoP(){
                let me=this;
                var url='/tipoPago/selectTipoP';
                axios.get(url).then(function(response){
                    me.arrayPago=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectFormaP(){
                let me=this;
                var url='/formaPago/selectFormaP';
                axios.get(url).then(function(response){
                    me.arrayForma=response.data;
                    me.arrayForma2 = response.data;
                    me.arrayForma2 = me.arrayForma2.filter((item) => item.id !== 1);
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            verVenta(data=[]){
                let me = this;
                me.listado = 2;
                me.datos.id=data['id'];
                me.datos.cliente=data['cliente'];
                me.datos.fecha=data['fecha'];
                me.datos.descuento=data['descuento'];
                me.datos.tipoPago=data['tipoP'];
                me.datos.formaPago=data['formaP'];

                var url='/venta/permiso/detalle/tienda3?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            guardarVenta(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe Productos agregados!'
                    })
                }
                if(this.datos.cliente == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar un Cliente!'
                    })
                }
                if(this.arrayDetalle.find(seg => (seg.stock - seg.cantidad < 0 && this.datos.tipo_venta!='Venta Servicio'))){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No hay stock para el producto!'
                    })
                } else {
                    if(this.datos.total < 0){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'El total no puedes ser Negativo!'
                        })
                    } else {
                        let me = this;
                        axios.post('/venta/guardar_tienda3',{
                            'id_servicio': me.datos.id,
                            'fecha': me.datos.fecha,
                            'fecha_final': me.datosPago.fecha_final,
                            'sub_total': me.datos.sub_total,
                            'descuento': me.datos.descuento,
                            'total': me.datos.total,
                            'estado': me.datos.estado,
                            'id_cliente': me.datos.id_cliente,
                            'id_tipo_pago': me.datos.id_tipo_pago,
                            'id_forma_pago': (me.datos.id_tipo_pago == 2) ? me.arrayForma[0].id : me.datos.id_forma_pago,
                            'id_costo_pago': me.datos.id_descuento,
                            'detalle': me.arrayDetalle,
                            'monto_total': me.datos.total,
                            'descripcion_pago': me.datosPago.descripcion,
                            'saldo': me.datosPago.saldo,
                            'tipo_venta': me.datos.tipo_venta,
                        }).then(function(response){
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Venta registrado exitosamente',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            me.cargarPdf2()
                            me.volverVentaListado();
                            me.limpiarDatosVenta();
                            console.log(me.datos);
                        })
                        .catch(function(error){
                            console.log(error);
                        });
                    }
                }
                
            },        
            validarCompra(){
                this.errorPago = 0;
                this.errorMostrarMsjPago = [];

                if(!this.datos.nombre) this.errorMostrarMsjPago.push("El nombre del Pago no puede estar vacio ");
                if(this.errorMostrarMsjPago.length) this.errorPago=1;
                return this.errorPago;
            },
            frmVenta(){
                this.listado = 1;
                this.selectCliente();
                this.selectTipoP();
                this.selectFormaP();        
            },
            anularVenta(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Anular esta Compra??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/venta/anular3',{'id': id}).then(function (response) {
                        swalWithBootstrapButtons.fire(
                        'Habilitado!',
                        'Este compra se ha Anulado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este categoria no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            limpiarDatosVenta(){
                let me = this;
                me.datos = {
                    id : 0,
                    fecha : moment().add(10,'days').format('YYYY-MM-DD'),
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_tipo_pago : 1,
                    id_forma_pago : 2,
                    id_cliente : 0,
                    id_personal : 1,
                    cliente : '',
                    tipoPago : '',
                    formaPago : '',
                    costo_pago : '',
                    id_descuento: '',
                    personal:'',
                    estado:'',
                    
                }
                me.arrayOrden = [];
                me.arrayDetalle = [];
                me.isDisabledOrden = false;
                me.isDisabledCliente=false;
                me.isDisabledProducto=false;
                me.buscarP = '';
                me.buscar = '';
                me.buscarCliente= '';
                me.isDisabled=false;
            },
            limpiarArticulo(){
                this.arrayArticulo = [];
                this.buscarP = '';
                this.buscar = '';
                this.arrayDetalle.forEach(item => item.saldoStock = 0);
            },
            limpiarCliente(){
                this.arrayTipoCliente = [];
                this.buscarP = '';
                this.buscar = '';
            },
            calcularSaldo() {
                this.datosPago.saldo = this.datos.total;
            },
            cerrarModalPago() {
                this.datosPago = this.anticipo;
            },
            seleccionarOrdenServicio(data=[]) {
                let me = this;
                me.datos.id=data['id'];
                me.datos.id_cliente=data['id_cliente'];
                me.datos.cliente=data['cliente'];
                me.datos.id_descuento=data['id_descuento'];
                me.datos.tipo_venta='Venta Servicio';
                me.datos.estado='Entregado';
                me.datos.descuento=data['descuento']

                var url='/servicio/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });

                me.isDisabledCliente=true;
                me.isDisabledProducto=true;
            },

            numericValidate(event) {
                const keyValidate = new RegExp('^[0-9]*$');
                const keysCaracter = ['Delete', 'Backspace', 'ArrowLeft', 'ArrowRight', 'KeyX', 'KeyC', 'KeyV', 'Home', 'End', 'Tab'];
                if (keysCaracter.includes(event.code)) {
                    switch (event.code) {
                    case 'KeyX':
                        if (!event.ctrlKey) {
                        event.preventDefault();
                        }
                        break;
                    case 'KeyC':
                        if (!event.ctrlKey) {
                        event.preventDefault();
                        }
                        break;
                    case 'KeyV':
                        if (!event.ctrlKey) {
                        event.preventDefault();
                        }
                        break;
                    default:
                        break;
                    }
                    return;
                }
                if (!keyValidate.test(event.key)) {
                    event.preventDefault();
                }
            },
            cargarPdf2() {
                axios.get('/venta/pdfVentasGeneral2',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            }

        },
        mounted() {
            this.selectCliente();
            this.selectTipoP();
            this.selectFormaP();  
            this.verificarCaja();
             
        }
    }
</script>
<style scoped lang="scss">
    .dropdown-wrapper{
        position: relative;
        //margin: 0 auto;

        .selected-item{
            height: 25px;
            //border: 2px solid lightgray;
            border-radius: 5px;
            padding: 5px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            //font-size: 16px;
            //font-weight: 500;

            .drop-down-icon{
                transform: rotate(0deg);
                transition: all 0.35s ease;
                &.dropdown{
                    transform: rotate(180deg);
                }
            }
        }

        .dropdown-popover{
            position: absolute;
            border: 2px solid lightgray;
            top: 46;
            left: 0;
            right: 0;
            background-color: #fff;
            max-width: 100%;
            align-items: center;
            padding: 10px;
            visibility: hidden;
            transition: all 0.5s linear;
            max-height: 0px;
            overflow: hidden;


            &.visible{
                max-height: 450px;
                visibility: visible;
            }
            input{
                width: 100%;
                height: 30px;
                border: 2px solid lightgray;
                font-size: 18px;
                padding-left: 8px;
            }

            .options{
                width: 100%;
                padding-top: 12px;

                ul{
                    list-style: none;
                    text-align:left;
                    padding-left: 2px;
                    max-height: 200px;
                    overflow-y: scroll;
                    overflow-x: hidden;
                }

                li{
                    width: 100%;
                    border-bottom: 1px solid lightgray;
                    padding: 5px;
                    border: 1px solid lightgray;
                    background-color: #f1f1f1;
                    cursor: pointer;
                    &:hover {
                        background: #44536E;
                        color: #fff;
                        font-weight: bold;
                    }
                }
            }
        }
    }



    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .mostrar{
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #d1cdcd7a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
    .footer{
        position:relative !important;
        width: auto !important;
    }
    .tamaño{
        width: 100px !important; 
    }
    .bg-disabled{
        background-color: #D8DBE1;
    }
</style>
<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                    <div class="card">
                    <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">Vacuna</h3></div>
                        <!-- Listado de Ventas -->
                        <template v-if="listado==0 && estadoCaja == 'Abierta'">
                            <div class="card-body">
                                <div class="form-group row" style='margin-left: 1%'>   
                                <form class="row">
                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1" class="form-label">Animal</label>
                                        <div class="input-group mb-6">
                                            <!-- <input type="text" readonly class="form-control" placeholder="Buscar Clientes.."  v-model="datos.cliente" aria-label="Buscar Productos.." aria-describedby="button-addon2"> -->

                                            <section class="dropdown-wrapper form-control bg-disabled">
                                                <div @click="isVisible = !isVisible" class="selected-item">
                                                    <span v-if="datos.animal==''">Seleccione el Animal</span>
                                                    <span v-else>{{datos.animal }}</span>
                                                    <svg :class="isVisible ? 'dropdown' : ''" class="drop-down-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>
                                                </div>
                                                <div :class="isVisible  ? 'visible' : 'invisible'" class="dropdown-popover">
                                                    <input type="text" class="form-control" placeholder="Buscar Clientes.."  v-model="buscarCliente" aria-label="Buscar Productos..">
                                                    <div class="text-center"><span v-if="filteredCliente.length === 0">No existen Clientes</span></div>
                                                    <div class="options">
                                                        <ul>
                                                            <li @click="selectedCliente(animal)" v-for="(animal, index) in filteredCliente" :key="`cliente-${index}`">{{animal.nombre}}</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </section>

                                            &nbsp;&nbsp;&nbsp;
                                            <!-- <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalCliente" @click="listarCliente(buscarP)" :disabled="isDisabledCliente"><i class="fa fa-search"></i> Agregar Clientes</button> -->
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                    </div>&nbsp;
                                    <div class="col-md-12">
                                        <label>Vacuna<span style="color:red;" >(*Seleccione)</span></label>
                                        <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalArticulo" @click="listarArticulo(buscarP)"  :disabled="isDisabledProducto"><i class="fa fa-search"></i> Agregar Vacunas</button>

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
                                                <th scope="col" class="text-white">Nombre</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                                </td>
                                                <td v-text="detalle.articulo"></td>
                                                                           
                                            </tr>

                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="7">No hay Productos agregados</td>
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
                        <!-- nuevo -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-select col-md-3" v-model="criterioP">
                                        <option value="articulo.nombre_comercial">Producto</option>   
                                        <option value="categoria.nombre">Categoria</option>
                                        <!-- <option value="articulo.marca">Marca</option>                                     -->
                                    </select>&nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(buscarP, criterioP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticulo(buscarP, criterioP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                       <!-- fin -->
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>
                                    <th scope="col" class="text-white">Categoria</th>                      
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">Tienda</th>
                                    <th scope="col" class="text-white">Precio Venta</th>
                                    <th scope="col" class="text-white">stock</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayArticulo.length">
                                <tr v-for="tienda_articulo in arrayArticulo" :key="tienda_articulo.id">
                                    <td v-text="tienda_articulo.categoria"></td>
                                    <td v-text="tienda_articulo.articulo"></td>
                                    <td v-text="tienda_articulo.tienda"></td>
                                    <td v-text="tienda_articulo.costo_unitario"></td>
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

        <!--Modal Formulario Orden de Paquete-->
        <div class="modal fade" id="modalPaquete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white"  style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDAS PAQUETES</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarPaquete(buscarP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarPaquete(buscarP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead style="background-color: #46546C">
                                    <tr>                      
                                        <th scope="col" class="text-white">Nombre</th>
                                        <th scope="col" class="text-white">Fecha</th>
                                        <th scope="col" class="text-white">Fecha Final</th>
                                        <th scope="col" class="text-white">Precio</th>
                                        <th scope="col" class="text-white">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayPaquete.length">
                                    <tr v-for="paquete in arrayPaquete" :key="paquete.id">
                                        <td v-text="paquete.nombre"></td>
                                        <td v-text="paquete.fecha_inicio"></td>
                                        <td v-text="paquete.fecha_final"></td>
                                        <td v-text="paquete.total"></td>
                                        <td>
                                            <button type="button"  data-dismiss="modal" @click="seleccionarPaquete(paquete)" class="btn btn-success btn-sm" data-bs-dismiss="modal"><i class="fa fa-check text-white"></i></button> 
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
                    id_animal : 0,
                    animal : '',
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
                arrayDetalleProducto : [],
                arrayProductoPaquete: [],
                //arrayDetallePaqueteProducto : [],
                arrayAnimal: [],
                arrayCostoPago: [{id:1,nombre:'Unitario'},{id:2,nombre:'Mayorista'},{id:3,nombre:'Preferencial'}],
                arrayTipoCliente: [],
                arrayPersonal: [],
                arrayPago: [],
                arrayForma: [],
                arrayForma2: [],
                arrayOrden : [],  
                arrayPaquete : [],  
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
                criterioP : 'articulo.nombre_comercial',
                buscar : '',
                buscarP : '',
                isDisabled: true,
                isDisabledCliente: false,
                isDisabledProducto: false,
                isDisabledOrden: false,
                isDisabledPaquete: false,
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

                        resultado = resultado + (this.arrayDetalle[i].costo_unitario*this.arrayDetalle[i].cantidad);
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
                    return this.arrayAnimal;
                }
                return this.arrayAnimal.filter((item)=>{
                    return Object.values(item).some((word=>String(word).toLowerCase().includes(data)))
                })
            }

            // actualizarStockProducto(id,cantidad,paquete){
            //     let me = this;

            //     if(paquete == 'Venta Paquete'){
            //         me.arrayProductoPaquete.forEach(item => { item.id_paquete == id ? item.cantidad = item.cantidad*cantidad : ''});
            //     }
                
            // },
        },
        methods : {
            actualizarStockProducto(id,cantidad,paquete){
                let me = this;
                if(paquete == 'Venta Paquete'){ 
                    me.arrayProductoPaquete.forEach(
                        item => { 
                            item.id_paquete == id  && item.tipo_producto== 'Producto Venta'? item.cantidad_aux = item.cantidad*cantidad : '';
                        });
                }

            },
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
            // listarArticulo(buscarP){
            //     let me = this;
            //     me.buscarCliente= '';
            //     me.isVisible = false;
            //     var url='/tienda/listarSinPaginateVacuna?buscar=' + buscarP;
            //     axios.get(url).then(function(response){
            //         me.arrayArticulo= response.data;
            //     })
            //     .catch(function(error){
            //         console.log(error);
            //     });
            // },
            listarArticulo(buscarP, criterioP){
                let me = this;
                me.buscarCliente= '';
                me.isVisible = false;
                var url='/tienda/listarSinPaginateVacuna?buscar=' + buscarP + '&criterio=' + criterioP;
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
                var url='/tienda/listarSinPaginateVacuna?buscar=' + me.buscarP;
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
            selectedCliente(animal){
                this.datos.animal='';
                this.datos.id_animal=0;
                this.datos.id_descuento='';
                this.isVisible = false;
                this.datos.animal = animal.nombre;
                this.datos.id_animal = animal.id;
                //this.datos.id_descuento = cliente.descuento;
            },
            listarOrden(buscarP){
                let me = this;
                me.buscarCliente= '',
                me.isVisible = false;
                var url='/servicio/listarOrdenSinPaginate_tienda1?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayOrden= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarPaquete(buscarP){
                let me = this;
                me.buscarCliente= '',
                me.isVisible = false;
                var url='/paquete/listarOrdenSinPaginate?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayPaquete= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].id_articulo==id ){
                        sw=true;
                    }
                }
                return sw;
            },
            encuentraPaquete(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].id_paquete==id ){
                        sw=true;
                    }
                }
                return sw;
            },
            
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            
            eliminarProductoPaquete(id){
                let array = [];
                array = this.arrayProductoPaquete.filter(item => item.id_paquete != id);
                this.arrayProductoPaquete = array;
            },
            
            eliminarDetalle(index){
                let me = this;
                let id_paquete = 0;
                if(me.arrayDetalle[index].producto_venta=='Venta Paquete'){
                    id_paquete = me.arrayDetalle[index].id_paquete;
                    me.eliminarProductoPaquete(id_paquete);
                }
                me.arrayDetalle.splice(index,1);
            },


            seleccionarPaquete(data=[]){
                let me = this;
                if(me.encuentraPaquete(data['id'])){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Este paquete ya se encuentra agregado!'
                    })
                }
                else{
                    
                    me.arrayDetalle.push({
                        //id_tienda_articulo : data['id'],
                        id_paquete : data['id'],
                        articulo : data['nombre'],
                        tienda : '',
                        costo_unitario : data['total'],
                        costo_mayorista : '',
                        costo_preferencial : '',
                        costo_compra : '',
                        marca : '',
                        id_categoria : '',
                        categoria : '',
                        stock : 1000,
                        cantidad : 1,
                        producto_venta: 'Venta Paquete'
                        //sub_total : data['sub_total'],
                    });
                    me.datos.estado= 'Entregado';
                    me.datos.tipo_venta= 'Venta Directa'
                    me.isDisabled = false;
                    me.isDisabledOrden = true;
                    //me.isDisabledPaquete = true;
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Producto agregado...',
                        showConfirmButton: false,
                        timer: 500
                    });
                }
                this.cargarDetallePaquete(data['id']);

                    
            },

            cargarDetallePaquete(id){
                let me = this;
                var url='/paquete/permiso/detalle?id=' + id;
                axios.get(url).then(function(response){
                    me.arrayDetalleProducto= response.data;
                    me.arrayDetalleProducto.forEach(
                    item => { 
                        item.cantidad_aux = item.cantidad*1;
                    });

                    if(me.arrayProductoPaquete.length==0){
                        me.arrayProductoPaquete = me.arrayDetalleProducto;
                    }else{
                        me.arrayProductoPaquete=me.arrayProductoPaquete.concat(me.arrayDetalleProducto);
                    }
                    
                })
                .catch(function(error){
                    console.log(error);
                });
            },



            cargarArticuloPaquete(){
                this.arrayProductoPaquete = this.arrayDetalleProducto;
                // me.arrayProductoPaquete.push({
                //     id_tienda_articulo : data['id'],
                //     id_articulo : data['id_articulo'],
                //     articulo : data['articulo'],
                //     tienda : data['tienda'],
                //     costo_unitario : data['costo_unitario'],
                //     costo_mayorista : data['costo_mayorista'],
                //     costo_preferencial : data['costo_preferencial'],
                //     costo_compra : data['costo_compra'],
                //     marca : data['marca'],
                //     id_categoria : data['id_categoria'],
                //     categoria : data['categoria'],
                //     stock : data['stock'],
                //     cantidad : 1,
                //     sub_total : data['sub_total'],
                // });
                
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
                        producto_venta: 'Venta Producto'
                    });
                    me.datos.estado= 'Entregado';
                    me.datos.tipo_venta= 'Venta Directa'
                    me.isDisabled = false;
                    me.isDisabledOrden = true;
                    me.isDisabledPaquete = true;
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
                me.isDisabledPaquete = false;
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
            selectAnimal(){
                let me=this;
                var url='/animal/selectAnimal';
                axios.get(url).then(function(response){
                    me.arrayAnimal=response.data;
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

                var url='/venta/permiso/detalle/tienda1?id=' + data['id'];
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
                if(this.datos.animal == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar el nombre del animal!'
                    })
                }
                     else {
                            let me = this;
                            axios.post('/vacuna/guardar',{

                                'id_animal': me.datos.id_animal,
                                'detalle': me.arrayDetalle,

                            }).then(function(response){
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Venta registrado exitosamente',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                me.cargarPdf2();
                                me.volverVentaListado();
                                me.limpiarDatosVenta();
                                console.log(me.datos);
                            })
                            .catch(function(error){
                                console.log(error);
                            });
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
                this.selectAnimal();
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
                    axios.put('/venta/anular1',{'id': id}).then(function (response) {
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
                me.isDisabledPaquete=false;
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
                me.isDisabledPaquete=true;
            },
            seleccionarPaquete2(data=[]) {
                let me = this;
                // me.datos.id=data['id'];
                // me.datos.id_cliente=data['id_cliente'];
                // me.datos.cliente=data['cliente'];
                // me.datos.id_descuento=data['id_descuento'];
                // me.datos.tipo_venta='Venta Servicio';
                me.datos.estado='Entregado';
                // me.datos.descuento=data['descuento']

                var url='/paquete/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });

                //me.isDisabledCliente=true;
                //me.isDisabledProducto=true;
                me.isDisabledOrden=true;
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
            this.selectAnimal();
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
                transition: all 0.5s ease;
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
            transition: all 0.35s linear;
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
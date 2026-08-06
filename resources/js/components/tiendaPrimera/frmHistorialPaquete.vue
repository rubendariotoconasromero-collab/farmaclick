<template>
    <main class="main">
        <div class="row">
            <div class="col">
            &nbsp;
            <div class="card">
                <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">HISTORIAL DE PROMOCIONES</h3></div>
                <template v-if="listado==0">
                    <div class="form-group row">
                        <div class="col-md-8">
                            &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                <select class="form-select col-md-3" v-model="criterio">
                                    <option value="cliente.nombre">Nombre</option>
                                    <!-- <option value="users.nombre">Usuario</option> -->
                                </select>
                                &nbsp;&nbsp;&nbsp;
                                <input type="text" v-model="buscar" @keyup.enter="listarPaquete(1, buscar, criterio)" @keyup="BuscandoVenta()" class="form-control" placeholder="Texto a buscar">
                                &nbsp;&nbsp;&nbsp;
                                <button type="submit" @click="listarPaquete(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- Light table -->
                    <div class="table-responsive">
                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                        <thead style="background-color: #46546C">
                            <tr>                      
                                <th scope="col" class="text-white">Fecha</th>
                                <th scope="col" class="text-white">Paquete</th>
                                <th scope="col" class="text-white">Fecha Final</th>
                                <!-- <th scope="col" class="text-white">Descuento</th>  -->
                                <th scope="col" class="text-white">Total</th>
                                <th scope="col" class="text-white text-center">Estado</th>
                                <th scope="col" class="text-white text-center">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="venta in arrayVenta" :key="venta.id">
                                <td v-text="venta.fecha_inicio"></td>
                                <td v-text="venta.nombre"></td>
                                <td v-text="venta.fecha_final"></td>
                                <td v-text="venta.total"></td>
                                <td class="text-center">
                                    <template v-if="venta.estado==1">
                                        <span class="badge bg-success">Activo</span>
                                    </template>
                                    <template v-else>
                                        <span class="badge bg-danger">Desactivo</span>
                                    </template>
                                </td> 
                                <td class="text-center">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" @click="verVenta(venta)"><i class="fa fa-eye text-success"></i> Ver detalle</a></li>
                                        <!-- <li><a class="dropdown-item" href="#" @click="editarDetalle(venta)"><i class="fa fa-edit text-warning"></i> Modificar</a></li> -->
                                        <!-- <template v-if="venta.estado!='0'"> -->
                                                <li><a class="dropdown-item" href="#" @click="editarDetalle(venta)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                        <!-- </template> -->
                                        <!-- <template v-if="venta.estado==1">
                                            <li><a class="dropdown-item" href="#" @click="desactivarCliente(venta.id)"><i class="fa fa-unlock text-success"></i> Desactivar</a></li>
                                        </template>
                                        <template v-else>
                                            <li><a class="dropdown-item" href="#" @click="activarCliente(venta.id)"><i class="fa fa-lock text-danger"></i> Activar</a></li>
                                        </template> -->
                                    </ul>
                                </td>                                 
                            </tr>
                        </tbody>
                    </table>
                    </div>
                    <!-- Card Pagination -->
                    <div class="card-footer py-4">
                        <nav>
                            <ul class="pagination justify-content-end mb-0">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page==isActived ? 'active' :'']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page">1</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </template>
                <!-- Ver -->
                <template v-if="listado==2">
                    <div class="card-header row m-0">
                        <div class="col-md-4">
                            <button type="button" class="btn btn-danger text-white" @click="volverServicioListado()">
                                <i class="fa fa-reply-all"></i>&nbsp;Volver
                            </button>
                            <!-- <button type="button" @click="cargarPdf(datos.id, datos.foto)" class="btn btn-info">
                                <i class="icon-doc text-white"></i><span class="text-white">&nbsp;Reporte</span>
                            </button>  -->
                        </div>
                        <div class="col-md-4 text-center"><h3 class="mb-0">REGISTRO DE PAQUETE</h3></div>
                        <div class="col-md-4">&nbsp;</div>
                        <!-- <div class="col-md-2">
                            <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt=""> 
                        </div>  -->
                    </div> 
                    <div class="card-body">
                        <div class="form-group row" style='margin-left: 1%'>   
                            <form class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Nombre</label>
                                    <input type="text" class="form-control"  v-model="datos.nombre" disabled>  
                                </div>
                                <div class="col-md-6"> 
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha_inicio" disabled>  
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha Final</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha_final" disabled>  
                                </div>
                                <div class="col-md-12">
                                    <label for="exampleInputPassword1" class="form-label">Descripcion</label>
                                    <textarea class="form-control" v-model="datos.descripcion" rows="2" disabled></textarea>
                                </div>   
                                &nbsp;
                                

                            </form>    
                        </div>

                        <br>
                        <div class="form-group row">
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                                <thead style="background-color: #46546C">
                                    <tr>                      
                                        <th scope="col" class="text-white">Categoria</th>
                                        <th scope="col" class="text-white">Nombre</th>
                                        <th scope="col" class="text-white">Precio</th>
                                        <th scope="col" class="text-white">Cantidad</th>
                                        <th scope="col" class="text-white">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayDetalle.length">
                                    <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                        <td v-text="detalle.categoria"></td>
                                        <td v-text="detalle.articulo"></td>
                                        <td v-text="detalle.costo_venta"></td>
                                        <td v-text="detalle.cantidad"></td>
                                        <td>
                                            {{detalle.sub_total}} bs
                                        </td>                                                                             
                                    </tr>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="4" align="right"> <strong>Sub Total:</strong> </td>
                                        <td>{{datos.sub_total}} bs</td> 
                                    </tr>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="4" align="right"> <strong>Descuento:</strong> </td>
                                        <td v-text="datos.descuento"></td>
                                    </tr>
                                        <tr style="background-color: #CEECF5">
                                        <td colspan="4" align="right"> <strong>Sub Total:</strong> </td>
                                        <td>{{datos.total}} bs</td>
                                    </tr>

                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="6">No hay Permisos agregados</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end" style='width:96%;margin-left: 2.2%'>
                            <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverVentaListado()">Cancelar</button>
                            <button class="btn btn-info btn-lg text-white" type="button" @click="guardarServicio()">Guardar</button>
                        </div>
                    </div>
                </template>
                <!-- Fin de ver -->
                <!-- Modificar -->
                <template v-if="listado==3">
                        <div class="card-body">
                            <div class="form-group row" style='margin-left: 1%'>   
                            <form class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Nombre</label>
                                    <input type="text" class="form-control"  v-model="datos.nombre">  
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha_inicio">  
                                </div>

                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha Final</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha_final">  
                                </div>
                                <div class="col-md-6">
                                        <label for="inputPassword" class="form-label">Estado</label>
                                        <div class="col-sm-10">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" v-model="datos.estado">
                                                <label class="form-check-label" for="inlineRadio1">Activo</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="0" v-model="datos.estado">
                                                <label class="form-check-label" for="inlineRadio2">Inactivo</label>
                                            </div>
                                        </div>
                                    </div>   

                                <div class="col-md-12">
                                    <label for="exampleInputPassword1" class="form-label">Descripcion</label>
                                    <textarea class="form-control" v-model="datos.descripcion" rows="2"></textarea>
                                </div>   
                                &nbsp;
                                 <div class="col-md-12">
                                    <label>Productos<span style="color:red;" >(*Seleccione)</span></label>
                                    <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalArticulo2" @click="listarArticulo(buscarP), listarArticulo2(buscarP)"><i class="fa fa-search"></i> Agregar Productos/Servicios</button>
                                </div>              

                            </form>           
                                <div class="col-md-12">
                                    <div v-show="errorCompra" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjCompra" :key="error" v-text="error">

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
                                            <th scope="col" class="text-white">Precio</th>
                                            <th scope="col" class="text-white">Cantidad</th>
                                            <th scope="col" class="text-white">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="index">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                            </td>
                                            <td v-text="detalle.categoria"></td>
                                            <td v-text="detalle.articulo"></td>
                                            <td v-text="detalle.costo_unitario"></td>
                                            <!-- <td>
                                                <template v-if="datos.id_descuento == 1">
                                                    <vue-numeric v-model="detalle.costo_unitario" type="number" id="Unitario" class="form-control"></vue-numeric>
                                                </template> 
                                                <template v-if="datos.id_descuento == 2">
                                                    <vue-numeric v-model="detalle.costo_mayorista" type="number" id="Mayorista" class="form-control"></vue-numeric>
                                                </template>
                                                <template v-if="datos.id_descuento == 3">
                                                    <vue-numeric v-model="detalle.costo_preferencial" type="number" id="Preferencial" class="form-control"></vue-numeric>
                                                </template>
                                                <template v-if="datos.id_descuento != 1 && datos.id_descuento != 2 && datos.id_descuento != 3">
                                                    <vue-numeric :value='0' type="number"  id="Preferencial" class="form-control"></vue-numeric>
                                                </template> 
                                            </td> -->
                                            <td>

                                                <vue-numeric v-model="detalle.cantidad" type="number" class="form-control"></vue-numeric>
                                                <div v-if="detalle.tipo_producto == 'Producto Venta'">
                                                    <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.stock}}</span>
                                                </div>
                                            </td>
                                            <td>     
                                                {{detalle.sub_total = detalle.costo_unitario*detalle.cantidad}}  

                                            </td>                                                                             
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="5" align="right"> <strong>Sub Total:</strong> </td>
                                            <td>{{datos.sub_total = calcularSubTotal.toFixed(2)}} bs</td> 
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="5" align="right"> <strong>Descuento:</strong> </td>
                                            <td><vue-numeric v-model="datos.descuento" :precision="2" value="0" class="form-control" :max="parseFloat(datos.sub_total)"></vue-numeric></td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="5" align="right"> <strong>Total:</strong> </td>
                                            <td>{{datos.total = datos.sub_total- datos.descuento}} bs</td>
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="7">No hay Productos/Servicios agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end" style='width:96%;margin-left: 2.2%'>
                                <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverVentaListado()">Cancelar</button>
                                <button class="btn btn-info btn-lg text-white" type="button" @click="modificarPaquete()">Guardar</button>
                            </div>
                        </div>
                </template>
                <!-- Fin de modificar -->                
            </div>
            </div>
        </div>

        <!--Modal Formulario Producto/Servicio-->
        <div class="modal fade" id="modalArticulo2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white" style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDA DE PRODUCTOS/SERVICIOS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Servicios</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Productos</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <!-- Servicios -->
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        &nbsp;&nbsp;<div class="input-group">
                                            <input type="text" v-model="buscarP" @keyup.enter="listarArticulo2(buscarP)" @keyup="BuscandoProductoServicio()" class="form-control" placeholder="Texto a buscar">
                                            &nbsp;&nbsp;&nbsp;
                                            <button type="submit" @click="listarArticulo2(buscarP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                        </div>  
                                    </div>                   
                                </div>&nbsp;
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead style="background-color: #46546C">
                                            <tr>        
                                                <th scope="col" class="text-white">Categoria</th>              
                                                <th scope="col" class="text-white">Nombre</th>
                                                <th scope="col" class="text-white">Tienda</th>
                                                <th scope="col" class="text-white">Precio Unitario</th>
                                                <th scope="col" class="text-white">Precio Mayorista</th>
                                                <th scope="col" class="text-white">Precio Preferencial</th>
                                                <th scope="col" class="text-white">Opción</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayArticuloServicio.length">
                                            <tr v-for="tienda_articulo in arrayArticuloServicio" :key="tienda_articulo.id">
                                                <td v-text="tienda_articulo.categoria"></td>
                                                <td v-text="tienda_articulo.articulo"></td>
                                                <td v-text="tienda_articulo.tienda"></td>
                                                <td v-text="tienda_articulo.costo_unitario"></td>
                                                <td v-text="tienda_articulo.costo_mayorista"></td>
                                                <td v-text="tienda_articulo.costo_preferencial"></td>
                                                <td>
                                                    <button type="button" @click="seleccionarTiendaArticulo2(tienda_articulo)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>   
                                                </td>                                 
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="7">No hay Servicios</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <!-- Fin Servicios -->
                            <!-- Fin Productos -->
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        &nbsp;&nbsp;<div class="input-group">
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
                                                <th scope="col" class="text-white">Stock</th>
                                                <th scope="col" class="text-white">Opción</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayArticulo.length">
                                            <tr v-for="(tienda_articulo_servicio,index) in arrayArticulo" :key="index">
                                                <td v-text="tienda_articulo_servicio.categoria"></td>
                                                <td v-text="tienda_articulo_servicio.articulo"></td>
                                                <td v-text="tienda_articulo_servicio.marca"></td>
                                                <td v-text="tienda_articulo_servicio.tienda"></td>
                                                <td v-text="tienda_articulo_servicio.costo_unitario"></td>
                                                <td v-text="tienda_articulo_servicio.costo_mayorista"></td>
                                                <td v-text="tienda_articulo_servicio.costo_preferencial"></td>
                                                <td v-text="tienda_articulo_servicio.stock"></td>
                                                <td>
                                                    <button type="button" @click="seleccionarTiendaArticulo2(tienda_articulo_servicio)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>   
                                                </td>                                 
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="9">No hay Productos/Servicios Agregados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Fin Productos -->
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal" @click="cerrarProductos()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin modal Formulario Producto/Servicio-->
    </main>
</template>

<script>
    import Swal, { dangerConfirm } from '../../utils/appSwal';
    import moment from 'moment';
    export default {
        created() {
            this.Actualizar();
             this.datos.estado = '1';
        },
        data(){
            return {
                datos : {
                    id : 0,
                    fecha_inicio : moment().format('YYYY-MM-DD'),
                    fecha_final : moment().format('YYYY-MM-DD'),
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    nombre : 0,
                    estado:'1',
                    descripcion:'',

                },

                arrayEmpresa : [],            
                arrayVenta : [],
                arrayDetalle : [],
                arrayArticulo : [],
                arrayArticuloServicio : [],
                arrayCliente: [],
                arrayCostoPago: [{id:1,nombre:'Unitario'},{id:2,nombre:'Mayorista'},{id:3,nombre:'Preferencial'}],
                arrayTipoCliente: [],
                arrayClienteId: [],
                arrayPersonal: [],
                arrayPago: [],
                arrayForma: [],
                listado : 0,
                tipoAccion : 0,
                errorCompra : 0,
                errorMostrarMsjCompra : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'cliente.nombre',
                buscar : '',
                buscarP : '',
                disabledRecepcionado: false,
                disabledConcluido: false,
                disabledEntregado: false,
                disabledAnulado: false,
                setTimeoutBuscador : ''
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
            calcularTotal: function(){
                var resultado = 0.0;
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
                return resultado;
            },
            calcularSubTotal: function(){
                var resultado = 0.0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    resultado = resultado + (this.arrayDetalle[i].costo_unitario*this.arrayDetalle[i].cantidad);
                }
                return resultado;
            }
        },
        methods : {
            listarPaquete(page, buscar, criterio){
                let me=this;
                var url='/paquete?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayVenta=response.data.data;
                    me.pagination={total:response.data.total, 
                        current_page:response.data.current_page,
                        per_page: response.data.per_page,
                        last_page: response.data.last_page,
                        from: response.data.from,
                        to: response.data.to
                    }
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            listarVentaServicioVentaDirecta(){
                let me=this;
                var url='/servicio_tienda1?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayVenta=response.data.data;
                    me.pagination={total:response.data.total, 
                        current_page:response.data.current_page,
                        per_page: response.data.per_page,
                        last_page: response.data.last_page,
                        from: response.data.from,
                        to: response.data.to
                    }
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            BuscandoVenta(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarVentaServicioVentaDirecta,350)
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarPaquete(page, buscar, criterio);
            },
            listarArticulo(buscarP){
                let me = this;
                var url='/tienda/producto_tienda1?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayArticulo= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarArticulo2(buscarP){
                let me = this;
                var url='/tienda/servicio_tienda1?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayArticuloServicio= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarCliente(buscarP){
                let me = this;
                var url='/cliente/listarSinPaginate?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayTipoCliente= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarPersonal(buscarP){
                let me = this;
                var url='/personal/listarSinPaginate?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayPersonal= response.data;
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
                        categoria : data['categoria'],
                        id_articulo : data['id_articulo'],
                        marca : data['marca'],
                        articulo : data['articulo'],
                        tienda : data['tienda'],
                        costo_unitario : data['costo_unitario'],
                        costo_mayorista : data['costo_mayorista'],
                        costo_preferencial : data['costo_preferencial'],
                        stock : data['stock'],
                        cantidad : 1,
                        sub_total : data['sub_total']
                    });
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Producto agregado...',
                        showConfirmButton: false,
                        timer: 500
                    });
                }
            },
            seleccionarTiendaArticulo2(data=[]){
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
                        categoria : data['categoria'],
                        id_articulo : data['id_articulo'],
                        articulo : data['articulo'],
                        marca : data['marca'],
                        tienda : data['tienda'],
                        costo_unitario : data['costo_unitario'],
                        costo_mayorista : data['costo_mayorista'],
                        costo_preferencial : data['costo_preferencial'],
                        costo_compra : data['costo_compra'],
                        tipo_producto : data['tipo_producto'],
                        stock : data['stock'],
                        cantidad : 1,
                        sub_total : data['sub_total']
                    });
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
                this.datos.id_cliente=data['id'];
                this.datos.cliente= data['nombre'];
                this.datos.id_descuento= data['descuento'];
                this.arrayCliente=[];
            },
            seleccionarPersonal(data=[]){
                this.datos.id_personal=data['id'];
                this.datos.personal= data['nombre'];
            },
            cancelarCompra(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.nombre = '';
                me.buscarP = '';
            },
            volverVentaListado(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.nombre = '';
                me.buscarP = '';
                me.listado = 0;
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

            listarClienteId(buscarP){
                let me = this;
                var url='/cliente/selectClienteId?id_cliente=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayClienteId= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            
            selectFormaP(){
                let me=this;
                var url='/formaPago/selectFormaP';
                axios.get(url).then(function(response){
                    me.arrayForma=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            verVenta(data=[]){
                let me = this;
                me.listado = 2;
                me.datos.id=data['id'];
                me.datos.nombre=data['nombre'];
                me.datos.fecha_inicio=data['fecha_inicio'];
                me.datos.fecha_final=data['fecha_final'];
                me.datos.estado=data['estado'];
                me.datos.descuento=data['descuento'];
                me.datos.descripcion=data['descripcion'];
                me.datos.sub_total=data['sub_total'];
                me.datos.total=data['total'];

                var url='/paquete/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });

                // var url='/tienda?page=' + 1 + '&buscar=' + this.buscar + '&criterio=' + this.criterio;
                // axios.get(url).then(function(response){
                //     me.arrayEmpresa=response.data.data;
                //     me.empresa = me.arrayEmpresa.find(seg => (seg.id == me.arrayDetalle[0].id_tienda));
                //     me.datos.foto = me.empresa.foto;
                //     me.datos.empresa_nombre = me.empresa.nombre;
                //     me.datos.empresa_direccion = me.empresa.direccion;
                //     me.pagination={total:response.data.total, 
                //         current_page:response.data.current_page,
                //         per_page: response.data.per_page,
                //         last_page: response.data.last_page,
                //         from: response.data.from,
                //         to: response.data.to
                //     }
                // })
                // .catch(function(error){
                //     console.log(error)
                // });
            },

            editarDetalle(data=[]){
                let me = this;
                me.listado = 3;
                me.datos.id=data['id'];
                me.datos.nombre=data['nombre'];
                me.datos.fecha_inicio=data['fecha_inicio'];
                me.datos.fecha_final=data['fecha_final'];
                me.datos.estado=data['estado'];
                me.datos.descuento=data['descuento'];
                me.datos.descripcion=data['descripcion'];
                me.datos.sub_total=data['sub_total'];
                me.datos.total=data['total'];

                var url='/paquete/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },


            modificarPaquete(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe Productos agregados!'
                    })
                }
                if(this.arrayDetalle.find(seg => ( seg.tipo_producto == 'Producto Venta' ? seg.stock - seg.cantidad < 0 : ''))){
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
                        axios.put('/paquete/modificar',{
                            'id': me.datos.id,
                            'nombre': me.datos.nombre,
                            'fecha_inicio': me.datos.fecha_inicio,
                            'fecha_final': me.datos.fecha_final,
                            'sub_total': me.datos.sub_total,
                            'descuento': me.datos.descuento,
                            'total': me.datos.total,
                            'estado': me.datos.estado,
                            'descripcion': me.datos.descripcion,
                            'detalle': me.arrayDetalle,
                            'tipo_producto': me.arrayDetalle[0].tipo_producto,
                        }).then(function(response){
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Paquete modificado exitosamente',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            //me.cargarPdf2();
                            me.volverVentaListado();
                            me.limpiarDatosVenta();
                            me.listarPaquete(1,'', 'nombre');
                        })
                        .catch(function(error){
                            console.log(error);
                        });
                    }
                }
            },      
            actualizarServicio(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe Productos agregados!'
                    })
                }
                let me = this;
                axios.put('/servicio/modificar',{
                    'id': me.datos.id,
                    'fecha': me.datos.fecha,
                    'sub_total': me.datos.sub_total,
                    'descuento': me.datos.descuento,
                    'total': me.datos.total,
                    'estado': me.datos.estado,
                    'descripcion': me.datos.descripcion,
                    'id_cliente': me.datos.id_cliente,
                    'id_personal': me.datos.id_personal,
                    'id_costo_pago': me.datos.costo_pago,
                    'detalle': me.arrayDetalle,
                    'tipo_producto': me.datos.tipo_producto,
                }).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Venta registrado exitosamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    me.volverVentaListado();
                    me.listarPaquete(1,'', 'nombre');
                    me.limpiarDatosVenta();
                    console.log(me.datos.sub_total)
                })
                .catch(function(error){
                    console.log(error);
                });
            },       
            validarCompra(){
                this.errorCompra = 0;
                this.errorMostrarMsjCompra = [];

                if(!this.datos.nombre) this.errorMostrarMsjCompra.push("El nombre del Compra no puede estar vacio ");
                if(this.errorMostrarMsjCompra.length) this.errorCompra=1;
                return this.errorCompra;
            },
            frmServicio(){
                this.listado = 1;
                this.selectCliente();
                this.selectTipoP();
                this.selectFormaP();        
            },
            anularServicio(id){
                dangerConfirm.fire({
                    title: 'Esta seguro de Anular esta Servicio??',
                    text: "No Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/servicio/anular',{'id': id}).then(function (response) {
                        me.listarPaquete(1,'', 'nombre');
                        Swal.fire(
                        'Anulado!',
                        'Este servicio se ha Anulado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                    'Cancelado',
                    'Este servicio no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            limpiarDatosVenta(){
                this.datos = {
                    id : 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_tipo_pago : 0,
                    id_forma_pago : 0,
                    id_cliente : 0,
                    id_personal : 0,
                    cliente : '',
                    tipoPago : '',
                    formaPago : '',
                    costo_pago : '',
                    id_descuento: '',
                    tipo:'Tipo Venta',
                    personal:'',
                    estado:'Recepcionado',
                    descripcion:'',
                    arrayArticulo:[],
                    
                }
            },
            desactivarCliente(id){
                dangerConfirm.fire({
                    title: 'Esta seguro de Inhabilitar este Cliente??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Inhabilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/paquete/desactivar',{'id': id}).then(function (response) {
                        me.listarPaquete(1,'', 'nombre');
                        Swal.fire(
                        'Inhabilitado!',
                        'Este cliente se ha Inhabilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                    'Cancelado',
                    'Este cliente no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            activarCliente(id){
                dangerConfirm.fire({
                    title: 'Esta seguro de Habilitar este Cliente??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/paquete/activar',{'id': id}).then(function (response) {
                        me.listarPaquete(1,'', 'nombre');
                        Swal.fire(
                        'Habilitado!',
                        'Este cliente se ha Habilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                    'Cancelado',
                    'Este cliente no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            volverServicioListado(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.nombre = '';
                me.buscarP = '';
                me.listado = 0;
                me.limpiarDatosVenta();
            },
            cargarPdf(id,foto) {
                axios.get('/servicio/pdfOrdenServiciosGeneral?id=' + id  + '&foto='+ foto,{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            Actualizar(){
                var url='/mostrar';
                axios.put(url).then(function(response){
                    //me.arrayDetalle = response.data;
                    me.listarPaquete(1,'', 'nombre');
                })
                .catch(function(error){
                    console.log(error)
                });
            },     
        },
        mounted() {
            //this.Actualizar();
            this.listarPaquete(1, this.buscar, this.criterio);
            // this.selectCliente();
            // this.selectTipoP();
            // this.selectFormaP();  
  
             
        }
    }
</script>
<style>
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
</style>
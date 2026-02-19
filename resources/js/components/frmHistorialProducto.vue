<template>
    <main class="main">
        <!-- <div class="container"> -->
        <div class="row">
            <div class="col">
                &nbsp;
                <div class="card">
                    <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">MOVIMIENTO DE PRODUCTO</h3>
                    </div>
                    <br>
                    <template v-if="listado==0">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true">Movimiento Venta Producto</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile" aria-selected="false">Movimiento
                                    General Producto</button>
                            </li>
                        </ul>
                        <br>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                                <div class="form-group row" id="home">
                                    <div class="col-md-8">
                                        <div class="input-group" style='width:96%;margin-left: 3.3%'>
                                            <div>
                                                <input type="date" class="form-control" v-model="datos.fecha_producto"
                                                    @click="listarInventario2(1,buscar,criterio)"
                                                    @change="listarInventario2(1,buscar,criterio)">
                                            </div>
                                            &nbsp;&nbsp;&nbsp;
                                            <div>
                                                <input type="date" class="form-control" v-model="datos.fecha_fin"
                                                    @click="listarInventario2(1,buscar,criterio)"
                                                    @change="listarInventario2(1,buscar,criterio)">
                                            </div>
                                            &nbsp;&nbsp;&nbsp;
                                            <select class="form-select col-md-3" v-model="criterio">
                                                <option value="articulo.nombre_comercial">Producto</option>
                                                <option value="proveedor.nombre">Laboratorio </option>
                                                <!-- <option value="nombre">Categoria </option> -->
                                            </select>
                                            <!-- &nbsp;&nbsp;&nbsp;
                                            <input type="text" v-model="buscar" @keyup.enter="listarInventario(buscar,criterio)" @keyup="BuscandoArticulo()" class="form-control" placeholder="Texto a buscar">
                                            &nbsp;&nbsp;&nbsp;
                                            <button type="submit" @click="listarInventario(buscar,criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                            &nbsp;&nbsp;&nbsp; -->
                                            &nbsp;&nbsp;&nbsp;
                                            <input type="text" v-model="buscar"
                                                @keyup.enter="listarInventario2(1, buscar, criterio)"
                                                @keyup="BuscandoArticulo()" class="form-control"
                                                placeholder="Texto a buscar">
                                            &nbsp;&nbsp;&nbsp;
                                            <button type="submit" @click="listarInventario2(1, buscar, criterio)"
                                                class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar
                                                P</button>
                                            &nbsp;&nbsp;&nbsp;
                                            <!-- <button type="submit" @click="Actualizar()" class="btn btn-info text-white"> Actualizar Inventario</button> -->
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <!-- Light table -->
                                <!--<div class="table-responsive">
                                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                                        <thead style="background-color: #46546c">
                                            <tr>
                                                <th scope="col" class="text-white">Fecha</th>
                                                <th scope="col" class="text-white">Producto</th>
                                                <th scope="col" class="text-white">Laboratorio</th>
                                                <th scope="col" class="text-white">Cantidad</th>
                                                <th scope="col" class="text-white">Lote</th>
                                                <th scope="col" class="text-white">F. Vencimiento</th>
                                                <th scope="col" class="text-white">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="venta in arrayTienda" :key="venta.id">
                                                    <td  v-text="venta.fecha"></td>
                                                    <td  v-text="venta.nombre_comercial"></td>
                                                    <td  v-text="venta.laboratorio"></td>
                                                    <td  v-text="venta.cantidad"></td>
                                                    <td  v-text="venta.lote"></td>
                                                    <td  v-text="venta.fecha_vecimiento"></td>
                                                    <td>
                                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="#" @click="ver(venta.id_lote)"><i class="fa fa-eye text-success"></i> Ver Usuarios</a></li>
                                                        </ul>
                                                    </td>   
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>-->
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle"
                                        style="width:96%;margin-left:2.2%">
                                        <thead class="text-white" style="background-color: #46546c">
                                            <tr>
                                                <th class="text-white">Fecha</th>
                                                <th class="text-white">Producto</th>
                                                <th class="text-white">Laboratorio</th>
                                                <th class="text-white">Cantidad</th>
                                                <th class="text-white">Lote</th>
                                                <th class="text-white">F. Vencimiento</th>
                                                <th class="text-white">Opciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="venta in arrayTienda" :key="venta.id_lote">
                                                <td>{{ venta.fecha }}</td>
                                                <td>{{ venta.nombre_comercial }}</td>
                                                <td>{{ venta.laboratorio }}</td>
                                                <td>{{ venta.cantidad }}</td>
                                                <td>{{ venta.lote }}</td>
                                                <td>{{ venta.fecha_vecimiento }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown">
                                                            Acción
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                    @click.prevent="ver(venta.id_lote)">
                                                                    <i class="fa fa-eye text-success"></i> Ver Usuarios
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
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
                                                <a class="page-link" href="#"
                                                    @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                            </li>
                                            <li class="page-item" v-for="page in pagesNumber" :key="page"
                                                :class="[page==isActived ? 'active' :'']">
                                                <a class="page-link" href="#"
                                                    @click.prevent="cambiarPagina(page, buscar, criterio)"
                                                    v-text="page">1</a>
                                            </li>
                                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                                <a class="page-link" href="#"
                                                    @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                                <div class="form-group row" id="profile">
                                    <div class="col-md-8">
                                        <div class="input-group" style='width:96%;margin-left: 3.3%'>
                                            <div>
                                                <input type="date" class="form-control" v-model="datos.fecha_inicio">
                                            </div>
                                            &nbsp;&nbsp;&nbsp;
                                            <div>
                                                <input type="date" class="form-control" v-model="datos.fecha_final">
                                            </div>
                                            &nbsp;&nbsp;&nbsp;
                                            <select class="form-select col-md-3" v-model="criterio">
                                                <option value="articulo.nombre_comercial">Producto</option>
                                                <!-- <option value="proveedor.nombre">Laboratorio </option> -->
                                                <!-- <option value="nombre">Categoria </option> -->
                                            </select>

                                            &nbsp;&nbsp;&nbsp;
                                            <input type="text" v-model="buscarProducto"
                                                @keyup.enter="listarProducto(1,buscarProducto,criterio)"
                                                @keyup="BuscandoProducto()" class="form-control"
                                                placeholder="Texto a buscar">
                                            &nbsp;&nbsp;&nbsp;
                                            <select class="form-select" v-model="datos.id_proveedor"
                                                @change="selecionarProductoProveedor(datos.id_proveedor)">
                                                <option value="0" disabled>Seleccione el Laboratorio</option>
                                                <option v-for="proveedor in arrayProveedor" :key="proveedor.id"
                                                    :value="proveedor.id" v-text="proveedor.nombre"></option>
                                            </select>
                                            &nbsp;&nbsp;&nbsp;
                                            <button type="submit" @click="listarProducto(1,buscarProducto,criterio)"
                                                class="btn btn-info text-white"><i class="fa fa-search"></i>
                                                Buscar</button>
                                            &nbsp;&nbsp;&nbsp;
                                        </div>
                                        <br>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label for=""><strong>PRODUCTO</strong> :
                                            {{StockProducto.producto}} &nbsp;&nbsp;&nbsp;&nbsp; <strong>CANTIDAD
                                                :</strong> {{isNaN(StockProducto.total)? 0 :
                                            (parseFloat(StockProducto.total)).toFixed(0)}}</label>
                                    </div>
                                </div>

                                <br>
                                <!-- Light table -->
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                                        <thead style="background-color: #46546c">
                                            <tr>
                                                <th scope="col" class="text-white">Fecha</th>
                                                <th scope="col" class="text-white">Hora</th>
                                                <th scope="col" class="text-white">Producto</th>
                                                <th scope="col" class="text-white">Laboratorio</th>
                                                <th scope="col" class="text-white">Lote</th>
                                                <th scope="col" class="text-white">Transacción</th>
                                                <th scope="col" class="text-white">Cod. Transacción</th>
                                                <th scope="col" class="text-white">Costo</th>
                                                <th scope="col" class="text-white">Descuento</th>
                                                <th scope="col" class="text-white">Stock lote</th>
                                                <th scope="col" class="text-white">Stock Anterior</th>
                                                <th scope="col" class="text-white">Cantidad</th>
                                                <th scope="col" class="text-white">Stock General</th>
                                                <th scope="col" class="text-white">Usuario</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="producto in arrayProducto" :key="producto.id">
                                                <td v-text="producto.fecha"></td>
                                                <td v-text="producto.hora"></td>
                                                <td v-text="producto.producto"></td>
                                                <td v-text="producto.laboratorio"></td>
                                                <td v-text="producto.lote"></td>
                                                <td v-text="producto.motivo_ajuste"></td>
                                                <td v-text="producto.id_transaccion"></td>
                                                <td v-text="producto.costo_compra"></td>
                                                <td v-text="producto.descuento"></td>
                                                <td v-text="producto.stock_actual"></td>
                                                <td>{{isNaN(producto.stock_general_anterior)? 0 :
                                                    (parseFloat(producto.stock_general_anterior)).toFixed(0)}}</td>
                                                <td v-text="producto.stock"></td>
                                                <td>{{(parseFloat(producto.stock_general)).toFixed(0)}}</td>
                                                <td v-text="producto.usuario"></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <!-- Card Pagination -->
                                <div class="card-footer py-4">
                                    <nav>
                                        <ul class="pagination justify-content-end mb-0">
                                            <li class="page-item" v-if="paginationAjuste.current_page > 1">
                                                <a class="page-link" href="#"
                                                    @click.prevent="cambiarPaginaAjuste(paginationAjuste.current_page - 1, buscarProducto, criterio)">Ant</a>
                                            </li>
                                            <li class="page-item" v-for="page in pagesNumberAjuste" :key="page"
                                                :class="[page==isActived ? 'active' :'']">
                                                <a class="page-link" href="#"
                                                    @click.prevent="cambiarPaginaAjuste(page, buscarProducto, criterio)"
                                                    v-text="page">1</a>
                                            </li>
                                            <li class="page-item"
                                                v-if="paginationAjuste.current_page < paginationAjuste.last_page">
                                                <a class="page-link" href="#"
                                                    @click.prevent="cambiarPaginaAjuste(paginationAjuste.current_page + 1, buscarProducto, criterio)">Sig</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>

                            </div>
                        </div>
                    </template>

                    <template v-if="listado==1">
                        <div class="card-header row m-0">
                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger text-white" @click="volverVentaListado()">
                                    <i class="fa fa-reply-all"></i>&nbsp;Volver
                                </button>
                                <button type="button" @click="cargarPdfHistorialVentaUsuario(datos.id_lote)"
                                    class="btn btn-info">
                                    <i class="icon-doc text-white"></i><span class="text-white">&nbsp;Reporte</span>
                                </button>
                            </div>
                            <div class="col-md-4 text-center">
                                <h3 class="mb-0">HISTORIAL PRODUCTO USUARIO</h3>
                            </div>
                            <div class="col-md-4">&nbsp;</div>
                            <!-- <div class="col-md-2">
                                <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt=""> 
                            </div>  -->
                        </div>
                        <br>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group" style='width:96%;margin-left: 3.3%'>
                                    <!-- <div>
                                    <input type="date" class="form-control"  v-model="datos.fecha_producto" @click="listarProductoUsuario(datos.id_lote,buscarU,criterioP)" @change="listarProductoUsuario(datos.id_lote,buscarU,criterioP)">  
                                </div>
                                &nbsp;&nbsp;&nbsp;
                                <div>
                                    <input type="date" class="form-control"  v-model="datos.fecha_fin" @click="listarProductoUsuario(datos.id_lote,buscarU,criterioP)" @change="listarProductoUsuario(datos.id_lote,buscarU,criterioP)">  
                                </div> -->
                                    <select class="form-select col-md-3" v-model="criterioP">
                                        <option value="users.name">Responsable</option>
                                        <!-- <option value="proveedor.nombre">Laboratorio </option> -->
                                        <!-- <option value="nombre">Categoria </option> -->
                                    </select>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscarU"
                                        @keyup.enter="listarProductoUsuario(datos.id_lote,buscarU,criterioP)"
                                        class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit"
                                        @click="listarProductoUsuario(datos.id_lote,buscarU,criterioP)"
                                        class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                    &nbsp;&nbsp;&nbsp;
                                    <!-- <button type="submit" @click="Actualizar()" class="btn btn-info text-white"> Actualizar Inventario</button> -->
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Light table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                                <thead style="background-color: #46546c">
                                    <tr>
                                        <th scope="col" class="text-white">Fecha</th>
                                        <th scope="col" class="text-white">Producto</th>
                                        <th scope="col" class="text-white">Laboratorio</th>
                                        <th scope="col" class="text-white">Cantidad</th>
                                        <th scope="col" class="text-white">Lote</th>
                                        <th scope="col" class="text-white">Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="usuario in arrayUsuario" :key="usuario.id">
                                        <td v-text="usuario.fecha"></td>
                                        <td v-text="usuario.nombre_comercial"></td>
                                        <td v-text="usuario.laboratorio"></td>
                                        <td v-text="usuario.cantidad"></td>
                                        <td v-text="usuario.lote"></td>
                                        <td v-text="usuario.usuario"></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                        <!-- Card Pagination -->
                        <div class="card-footer py-4">
                            <nav>
                                <ul class="pagination justify-content-end mb-0">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#"
                                            @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page"
                                        :class="[page==isActived ? 'active' :'']">
                                        <a class="page-link" href="#"
                                            @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page">1</a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#"
                                            @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>

                </div>
            </div>
            <template v-if="listado==3">
                <template v-if="inventario==1">

                </template>
            </template>
        </div>

        <!-- </div> -->

    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    import moment from 'moment';
import { toFixed } from 'accounting-js';
    export default {
        created()
        {
            //this.Actualizar();
            // this.listarPaquete();
            // this.listarInventario(this.buscar);
           // this.listarInventario(1, this.buscar, this.criterio);
            // this.listarInventario(1, this.buscar, this.criterio);
            // this.listarInventario(1, this.buscar, this.criterio);
            //this.limpiar();
            
            // setTimeout(function(){

            // }, 3000);
        },
        data(){
            return {
                datos : {
                    id : 0,
                    nombre : '',
                    stock : 0,
                    matricula : '',
                    telefono : '',
                    direccion : '',
                    descripcion : '',
                    estado : '1',
                    fecha_producto: moment().format('YYYY-MM-DD'),
                    fecha_producto2: moment().format('YYYY-MM-DD'),
                    fecha_fin: moment().format('YYYY-MM-DD'),
                    fecha_fin2: moment().format('YYYY-MM-DD'),

                    fecha_inicio: moment().format('YYYY-MM-DD'),
                    fecha_final: moment().format('YYYY-MM-DD'),

                    fecha_vence : moment().add(30,'days').format('YYYY-MM-DD'),
                    id_lote:0,
                    id_articulo:0,
                    id_proveedor:0,

                },
                v_fecha:0,
                arrayTienda : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCliente : 0,
                errorMostrarMsjCliente : [],
                arrayDetalleLote : [],
                arrayProductoLote:[],
                arrayUsuario:[],
                arrayProveedor:[],
                arrayProducto:[],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                paginationAjuste : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'articulo.nombre_comercial',
                criterioP: 'users.name',
                buscar : '',
                buscarU : '',
                buscarProducto: '',
                listado : 0,
                inventario :0,
                setTimeoutBuscador: '',
                StockProducto:{},
                StockProducto2:{},


            }
        },
        computed : {
            isActived: function(){
                return this.pagination.current_page;
            },
            isActivedServicio: function(){
                return this.paginationAjuste.current_page;
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
            pagesNumberAjuste: function(){
                if(!this.paginationAjuste.to){
                    return [];
                }
                var from = this.paginationAjuste.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if(to >= this.paginationAjuste.last_page){
                    to = this.paginationAjuste.last_page;
                }
                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
        },
        methods : {
            ver(id_lote){
                this.listado = 1
                this.datos.id_lote=id_lote
                this.listarProductoUsuario(id_lote,'','users.name')
            },
            selectProveedor(){
                let me=this;
                var url='/proveedor/selectProveedor';
                axios.get(url).then(function(response){
                    me.arrayProveedor=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            ProductoCantidad(id){
                let me=this;
                var url='/ajuste/lote?id_articulo=' + id ;
                axios.get(url).then(function(response){
                    me.StockProducto=response.data[0];

                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            cargarPdfHistorialVentaUsuario(id) {
                let time=500;
                //this.downloadReport(time);
                axios.get('/reporte/pdfHistorialProductoUsuario?id_lote=' + id + '&fecha_producto=' + this.datos.fecha_producto +'&fecha_fin=' + this.datos.fecha_fin +'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        //this.errorReport();
                        console.log(error);
                    })
            },
            listarProductoUsuario(id_lote, buscarU, criterioP){
                let me=this;
                var url='/cantidadProductoUsuario?id_lote=' + id_lote  + '&fecha_producto=' + me.datos.fecha_producto +'&fecha_fin=' + me.datos.fecha_fin +'&buscar=' + buscarU + '&criterioP=' + criterioP;
                axios.get(url).then(function(response){
                    me.arrayUsuario=response.data;

                    // me.pagination={total:response.data.total,
                    //     current_page:response.data.current_page,
                    //     per_page: response.data.per_page,
                    //     last_page: response.data.last_page,
                    //     from: response.data.from,
                    //     to: response.data.to
                    // }
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            listarArticuloBusquedaRapida2(){
                let me=this;
                var url='/cantidadProductoUsuario?id_lote=' + me.id_lote  + '&fecha_producto=' + me.datos.fecha_producto +'&fecha_fin=' + me.datos.fecha_fin +'&buscar=' + me.buscarU + '&criterioP=' + me.criterioP;
                axios.get(url).then(function(response){
                    me.arrayUsuario=response.data;

                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            BuscandoArticulo(){
                let me = this;
                // if(me.buscar == ''){
                //   me.listarInventario2(me.buscar,me.criterio)  
                // }else{
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticuloBusquedaRapida2,350)
            // }
            },
            volverVentaListado(){
                let me = this;
                me.listado = 0;
                // me.buscar = '';
                // me.datos.fecha_producto = moment().format('YYYY-MM-DD');
                // me.datos.fecha_fin = moment().format('YYYY-MM-DD');
                // me.listarInventario2(me.buscar,me.criterio)
            },
            listarPaquete(){
                let me=this;


                var url='/articulo/detalleLote/principal';
                axios.get(url).then(function(response){
                    me.arrayProductoLote=response.data;

                })
                .catch(function(error){
                    console.log(error)
                });

               // me.validarFecha();
            },
            // listarInventario(buscar,criterio){
            //     let me=this;

            //     var url='/cantidadProducto?buscar=' + buscar + '&criterio=' + criterio;
            //     axios.get(url).then(function(response){
            //         me.arrayTienda=response.data;

            //     })
            //     .catch(function(error){
            //         console.log(error)
            //     });
            //    // me.validarFecha();
            // },
            /*listarInventario2(page,buscar,criterio){
                let me=this;
                console.log(me.datos.fecha_producto,me.datos.fecha_fin);
                var url='/cantidadProductoFecha?page=' + page + '&fecha_producto=' + me.datos.fecha_producto +'&fecha_fin=' + me.datos.fecha_fin  + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayTienda=response.data.data;

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
               // me.validarFecha();
            },*/

            listarInventario2(page = 1, buscar = '', criterio = '') {
                const me = this;
                const { fecha_producto, fecha_fin } = me.datos;

                const params = new URLSearchParams({
                    page,
                    fecha_producto,
                    fecha_fin,
                    buscar,
                    criterio
                }).toString();

                axios.get(`/cantidadProductoFecha?${params}`)
                    .then(response => {
                        const data = response.data;
                        me.arrayTienda = data.data;
                        me.pagination = {
                            total: data.total,
                            current_page: data.current_page,
                            per_page: data.per_page,
                            last_page: data.last_page,
                            from: data.from,
                            to: data.to
                        };
                    })
                    .catch(error => console.error(error));
            },

            listarProducto(page,buscarProducto,criterio){
                let me=this;
                //console.log(me.datos.fecha_inicio,me.datos.fecha_final);
                // var url='/ajuste/producto?fecha_inicio=' + me.datos.fecha_inicio +'&fecha_final=' + me.datos.fecha_final  + '&buscarProducto=' + buscarProducto + '&criterio=' + me.criterio;
                var url='/ajuste/producto?page=' + page + '&fecha_inicio=' + me.datos.fecha_inicio +'&fecha_final=' + me.datos.fecha_final  + '&buscarProducto=' + buscarProducto + '&criterio=' + me.criterio + '&id_proveedor=' + me.datos.id_proveedor;
                axios.get(url).then(function(response){
                    me.arrayProducto=response.data.data;

                    me.paginationAjuste={total:response.data.total, 
                        current_page:response.data.current_page,
                        per_page: response.data.per_page,
                        last_page: response.data.last_page,
                        from: response.data.from,
                        to: response.data.to
                    }

                    if(me.buscarProducto == '')
                    {
                        me.StockProducto={};
                    }else{
                        me.ProductoCantidad(me.arrayProducto[0].id_articulo)
                     }
                })
                .catch(function(error){
                    console.log(error)
                });
               // me.validarFecha();
            },
            selecionarProductoProveedor(proveedor)
            {
                let me = this;
                var url='/ajuste/producto?buscarProducto=' + me.buscarProducto + '&criterio=' + me.criterio+ '&id_proveedor=' + proveedor + '&fecha_inicio=' + me.datos.fecha_inicio +'&fecha_final=' + me.datos.fecha_final;
                axios.get(url).then(function(response){
                    me.arrayProducto= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });

            },
            listarProductoBusquedaRapida(){
                let me=this;
                var url='/ajuste/producto?page=' + 1 + '&fecha_inicio=' + me.datos.fecha_inicio +'&fecha_final=' + me.datos.fecha_final  + '&buscarProducto=' + me.buscarProducto + '&criterio=' + me.criterio + '&id_proveedor=' + me.datos.id_proveedor;
                axios.get(url).then(function(response){
                    me.arrayProducto=response.data.data;
                   
                   me.paginationAjuste={total:response.data.total, 
                        current_page:response.data.current_page,
                        per_page: response.data.per_page,
                        last_page: response.data.last_page,
                        from: response.data.from,
                        to: response.data.to
                    }
                    if(me.buscarProducto == '')
                    {
                        me.StockProducto={};
                    }else{
                        me.ProductoCantidad(me.arrayProducto[0].id_articulo)
                        console.log(me.arrayProducto[0].id_articulo);

                    }
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            BuscandoProducto(){
                let me = this;
                // if(me.buscar == ''){
                //   me.listarInventario2(me.buscar,me.criterio)  
                // }else{
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarProductoBusquedaRapida,350)
            // }
            },
            // async listarInventario(page, buscar, criterio){
            //     let me=this;
            //     me.listarPaquete();;
            //     try {
            //         var url='/cantidadProducto?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            //         const res = await axios.get(url)
            //         me.arrayTienda=res.data.data;
            //         me.pagination={total:res.data.total,
            //             current_page:res.data.current_page,
            //             per_page: res.data.per_page,
            //             last_page: res.data.last_page,
            //             from: res.data.from,
            //             to: res.data.to
            //         }

            //     } catch (error) {
            //         if(error.response.data){
            //             this.errores=error.response.data.errors;
            //         }
            //     }
            //    me.validarFecha();
            // },

            listarArticuloBusquedaRapida(){
                let me=this;
                var url='/cantidadProductoFecha?page=' + 1 + '&fecha_producto=' + me.datos.fecha_producto +'&fecha_fin=' + me.datos.fecha_fin  + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayTienda=response.data.data;
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
            BuscandoArticulo(){
                let me = this;
                // if(me.buscar == ''){
                //   me.listarInventario2(me.buscar,me.criterio)  
                // }else{
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticuloBusquedaRapida,350)
            // }
            },

            async cargarProductoLote(id){

                // let me = this;
                // var arrayLote = [];
                // var url='/articulo/detalleLote?buscar=' + id;
                // axios.get(url).then(function(response){
                //     arrayLote= response.data;
                // })
                // .catch(function(error){
                //     console.log(error);
                // });

                // return arrayLote;

                let me= this;
                try {
                    var arrayLote = [];
                    var url='/articulo/detalleLote?buscar=' + id;
                    const res2  = await axios.get(url)
                    me.arrayProductoLote= res2.data;

                    //return arrayLote;
                } catch (error) {
                     if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }
            },
            contador()
            {
                try {
                    
                    let me = this;
                    me.arrayTienda.forEach(item2 => {
                    var url='/articulo/contador?id=' + item2.id;
                    axios.get(url).then(function(response){
                        //me.arrayDetalle = response.data;
                       // me.listarPaquete(1,'', 'nombre');
                    })
                    .catch(function(error){
                        console.log(error)
                    });
                     });
                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }
            },


            validarFecha(){
                this.arrayProductoLote;
                this.arrayTienda;
                console.log(this.arrayProductoLote);
                console.log(this.arrayTienda);
            },
            ///////////////////////
            // cargarDetallePaquete(id){
            //     let me = this;
            //     var url='/paquete/permiso/detalle?id=' + id;
            //     axios.get(url).then(function(response){
            //         me.arrayDetalleProducto= response.data;

            //         //me.validarStock();

            //         me.arrayDetalleProducto.forEach(
            //             item => {
            //                 item.cantidad_aux = item.cantidad*1;
            //         });

            //         if(me.arrayProductoPaquete.length==0){
            //             me.arrayProductoPaquete = me.arrayDetalleProducto;
            //         }else{
            //             me.arrayProductoPaquete=me.arrayProductoPaquete.concat(me.arrayDetalleProducto);
            //         }

            //     })
            //     .catch(function(error){
            //         console.log(error);
            //     });
            // },
            /////////////

            validarFecha()
            {
                      let me = this;
                      //console.log(me.arrayTienda);
                      me.arrayTienda.forEach(element => {
                        element.v_fecha = 0;
                        console.log(element.id)
                        let arrayLote = [];
                        var url='/articulo/detalleLote?buscar=' + 1;
                        axios.get(url).then(function(response){
                          me.arrayProductoLote= response.data;
                            //console.log(me.arrayProductoLote);

                            // me.arrayProductoLote.forEach(item =>
                            // {
                            //     element.v_fecha = 0;
                            //     //console.log(element.v_fecha);
                            // });
                        })
                        .catch(function(error){
                            console.log(error);
                        });
                        console.log(me.arrayProductoLote);

                        // setTimeout(function(){

                        // }, 3000);
                     });
            },

            cambiarPagina(page,buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarInventario2(page, buscar, criterio);
            },
            cambiarPaginaAjuste(page,buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarProducto(page, buscar, criterio);
            },
            // MostrarLotes(data=[]){
            //     let me = this;
            //     me.listado = 1;
            //     me.datos.id=data['id'];
            //     me.datos.nombre=data['nombre_comercial'];
            //     me.datos.stock=data['stock'];
            //     me.cargarDetalle();
            // },
            // cargarDetalle(){
            //     let me = this;
            //     var url='/articulo/detalleLote?buscar=' + me.datos.id;
            //     axios.get(url).then(function(response){
            //         me.arrayDetalleLote= response.data;
            //         me.arrayDetalleLote.forEach(element => {
            //                 if(me.datos.fecha_vence > element.fecha_vecimiento ){
            //                     element.v_fecha = 1;
            //                 }else{
            //                     element.v_fecha = 0;
            //                 }

            //          });
            //     })
            //     .catch(function(error){
            //         console.log(error);
            //     });
            // },
            limpiar()
            {
                this.arrayTienda = [];
                this.arrayProductoLote= []
            },
            // Actualizar(){
            //     let me = this;
            //      me.listarPaquete();
            //      me.listarInventario(1, this.buscar, this.criterio);

            //     // Swal.fire({
            //     //     position: 'top-end',
            //     //     icon: 'success',
            //     //     title: 'Sistema Actualizado...',
            //     //     showConfirmButton: false,
            //     //     timer: 2000
            //     // });
            // },
            // anularLote(id){
            //     const swalWithBootstrapButtons = Swal.mixin({
            //         customClass: {
            //             confirmButton: 'btn btn-success',
            //             cancelButton: 'btn btn-danger'
            //         },
            //         buttonsStyling: false
            //     })

            //     swalWithBootstrapButtons.fire({
            //         title: 'Esta seguro de Eliminar este Lote??',
            //         text: "No Puede revertir esta decision!",
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonText: 'Si, Habilitar!',
            //         cancelButtonText: 'No, cancelar!',
            //         reverseButtons: true
            //     }).then((result) => {
            //     if (result.isConfirmed) {
            //         let me = this;
            //         axios.put('/lote/anular',{'id': id}).then(function (response) {
            //             me.cargarDetalle();
            //             me.listarInventario(1,'', 'nombre_comercial');
            //             me.listarInventario(1,'', 'nombre_comercial');
            //             swalWithBootstrapButtons.fire(
            //                 'Anulado!',
            //             'Este lote se ha Eliminado.',
            //             'success'
            //             )
            //             me.inventario=1;
            //             //me.inventario = 1;
            //         }).catch(function (error) {
            //             console.log(error);
            //         });                    
            //     } else if (result.dismiss === Swal.DismissReason.cancel) {
            //         swalWithBootstrapButtons.fire(
            //         'Cancelado',
            //         'Esta lote no ha tenido cambios :)',
            //         'error'
            //         )
            //     }
            //     })   
            // } 
        },
        mounted() {
            this.listarPaquete();
            this.listarInventario2(1,this.buscar,this.criterio);
            this.listarProducto(1,this.buscarProducto,this.criterio);
            this.selectProveedor();

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
    .bg-rojo{
     background-color: 	#FFD2D6 !important;
     /* color: white !important; */
    }
</style>

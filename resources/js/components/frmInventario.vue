<template>
    <main class="main warehouse-legacy warehouse-legacy--inventory">
        <warehouse-inventory-workspace
            :product-rows="arrayTienda2"
            :lot-rows="arrayTienda"
            :details="arrayDetalleLote"
            :data="datos"
            :product-pagination="pagination"
            :lot-pagination="paginationProducto"
            :product-pages="pagesNumber"
            :lot-pages="pagesNumberProducto"
            :product-search="buscarT"
            :lot-search="buscar"
            :criterion="criterio"
            :view="listado == 1 ? 'detail' : 'list'"
            :loading="loading"
            :table-loading="tableLoading"
            @update:product-search="buscarT = $event"
            @update:lot-search="buscar = $event"
            @update:criterion="criterio = $event"
            @search-products="refreshProductInventory(1)"
            @search-lots="refreshLotInventory(1)"
            @product-page="refreshProductInventory($event)"
            @lot-page="refreshLotInventory($event)"
            @view-lots="MostrarLotes"
            @back="listado = 0"
            @remove-lot="anularLote"
        />
        <div v-if="false">
        <warehouse-module-intro
            title="Inventario"
            subtitle="Consulta existencias por producto y lote, identifica vencimientos y controla el stock disponible."
            primary-label="Productos"
            :primary-value="paginationProducto.total || arrayProductoLote.length"
            primary-hint="Productos en la consulta"
            secondary-label="Lotes"
            :secondary-value="pagination.total || arrayTienda.length"
            secondary-hint="Lotes en la consulta"
            tertiary-label="Vista"
            tertiary-value="Stock actual"
            tertiary-hint="Información operativa"
        />
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                   <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">INVENTARIO</h3>
                    </div>
                    <br>
                    <template v-if="listado==0">
                    <!-- Inicio -->
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Lotes</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Productos</button>
                        </li>
                    </ul>
                    <br>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                        <div class="form-group row" id="home">
                            <div class="form-group row">
                            <div class="col-md-8">
                            <div class="input-group"  style='width:96%;margin-left: 3.3%'>
                                <select class="form-select col-md-3" v-model="criterio">
                                    <option value="articulo.nombre_comercial">Nombre</option>
                                    <option value="proveedor.nombre">Laboratorio </option>
                                    <option value="categoria.nombre">Categoria </option>
                                    <!-- <option value="cod_veterinaria">Cod. Veterinaria </option> -->
                                </select>
                                &nbsp;&nbsp;&nbsp;
                                <input type="text" v-model="buscarT" @keyup.enter="listarInventario2(1, buscarT, criterio)" @keyup="BuscandoArticulo2()" class="form-control" placeholder="Texto a buscar">
                                &nbsp;&nbsp;&nbsp;
                                <button type="submit" @click="listarInventario2(1, buscarT, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                &nbsp;&nbsp;&nbsp;
                               <!-- <button type="submit" @click="Actualizar()" class="btn btn-info text-white"> Actualizar Inventario</button> -->
                            </div>
                        </div>
                    </div>
                        </div>
                    <br>
                    <!-- Light table -->
                    <div class="table-responsive">
                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                        <thead style="background-color: #46546c">
                            <tr>
 
                                <th scope="col" class="text-white">Nombre Comercial</th>
                                <th scope="col" class="text-white">Nombre Generico</th>
                                <th scope="col" class="text-white">Laboratorio</th>
                                <th scope="col" class="text-white">Costo Compra</th>
                                <th scope="col" class="text-white">Cantidad Unitario</th>
                                <th scope="col" class="text-white">Precio Unitario</th>
                                <th scope="col" class="text-white">Cantidad Blister</th>
                                <th scope="col" class="text-white">Precio Blister</th>
                                <th scope="col" class="text-white">Cantidad Caja</th>
                                <th scope="col" class="text-white">Precio Caja</th>
                                <th scope="col" class="text-white">Stock</th>
                                <!-- <th scope="col" class="text-white">V_fecha</th> -->
                                <th scope="col" class="text-white">Lotes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="tienda_articulo in arrayTienda2" :key="tienda_articulo.id">
                                    <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.nombre_comercial"></td>
                                    <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.nombre_generico"></td>
                                    <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.laboratorio"></td>
                                    <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.costo_compra"></td>
                                    <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.venta_presentacion"></td>
                                    <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.costo_unitario"></td>
                                    <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.cantidad_blister"></td>
                                    <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.precio_blister"></td> 
                                    <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.cantidad_caja"></td>
                                    <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.precio_caja"></td>
                                    <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.stock"></td>
                                    <!-- <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.v_fecha"></td> -->
                                    <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''">
                                        <button type="button" class="btn btn-success text-white position-relative" @click="MostrarLotes(tienda_articulo)">Ver</button>
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
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscarT, criterio)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page==isActived ? 'active' :'']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscarT, criterio)" v-text="page">1</a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscarT, criterio)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="form-group row" id="profile">
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <div class="input-group"  style='width:96%;margin-left: 3.3%'>
                                        <select class="form-select col-md-3" v-model="criterio">
                                            <option value="articulo.nombre_comercial">Nombre</option>
                                            <option value="proveedor.nombre">Laboratorio </option>
                                            <option value="nombre">Categoria </option>
                                            <!-- <option value="cod_veterinaria">Cod. Veterinaria </option> -->
                                        </select>
                                        &nbsp;&nbsp;&nbsp;
                                        <input type="text" v-model="buscar" @keyup.enter="listarInventario(1, buscar, criterio)" @keyup="BuscandoArticulo()" class="form-control" placeholder="Texto a buscar">
                                        &nbsp;&nbsp;&nbsp;
                                        <button type="submit" @click="listarInventario(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                        &nbsp;&nbsp;&nbsp;
                                    <!-- <button type="submit" @click="Actualizar()" class="btn btn-info text-white"> Actualizar Inventario</button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Light table -->
                        <div class="table-responsive">
                        <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                            <thead style="background-color: #46546c">
                                <tr>
    
                                    <th scope="col" class="text-white">Nombre Comercial</th>
                                    <th scope="col" class="text-white">Nombre Generico</th>
                                    <th scope="col" class="text-white">Laboratorio</th>
                                    <th scope="col" class="text-white">Fecha Vencimiento</th>
                                    <th scope="col" class="text-white">Lote</th>
                                    <th scope="col" class="text-white">Costo Compra</th>
                                    <th scope="col" class="text-white">Cantidad Unitario</th>
                                    <th scope="col" class="text-white">Precio Unitario</th>
                                    <th scope="col" class="text-white">Cantidad Blister</th>
                                    <th scope="col" class="text-white">Precio Blister</th>
                                    <th scope="col" class="text-white">Cantidad Caja</th>
                                    <th scope="col" class="text-white">Precio Caja</th>
                                    <th scope="col" class="text-white">Stock</th>
                                    <!-- <th scope="col" class="text-white">V_fecha</th> -->
                                    <th scope="col" class="text-white">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tienda_articulo in arrayTienda" :key="tienda_articulo.id">
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.articulo"></td>
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.nombre_generico"></td>
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.laboratorio"></td>
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.fecha_vecimiento"></td>
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.lote"></td>
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.costo_compra"></td>
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.venta_presentacion"></td>
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.costo_unitario"></td>
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.cantidad_blister"></td>
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.precio_blister"></td> 
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.cantidad_caja"></td>
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.precio_caja"></td>
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.stock"></td>
                                        <!-- <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''" v-text="tienda_articulo.v_fecha"></td> -->
                                        <td :class="tienda_articulo.v_fecha ?'bg-rojo' :''">
                                            <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" @click="anularLote(tienda_articulo.id)"><i class="fa fa-lock text-danger"></i> Eliminar</a></li>

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
                                    <li class="page-item" v-if="paginationProducto.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaProducto(paginationProducto.current_page - 1, buscar, criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumberProducto" :key="page" :class="[page==isActivedProducto ? 'active' :'']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaProducto(page, buscar, criterio)" v-text="page">1</a>
                                    </li>
                                    <li class="page-item" v-if="paginationProducto.current_page < paginationProducto.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaProducto(paginationProducto.current_page + 1, buscar, criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    </div>
                    </template>
                    <template v-if="listado==1">
                        <div class="card-body border" >
                            <button type="button" class="btn btn-danger text-white" @click="listado=0"><i class="fa fa-reply-all"></i>&nbsp;Volver</button>
                            <div class="form-group row">
                                <form class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Nombre</label>
                                    <input type="text" class="form-control"  v-model="datos.nombre" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Stock</label>
                                    <input type="text" class="form-control"  v-model="datos.stock" disabled>
                                </div>
                                </form>
                            </div>

                            <br>
                            <div class="form-group row">
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead style="background-color: #46546C">
                                        <tr>
                                            <th scope="col" class="text-white">Lote</th>
                                            <th scope="col" class="text-white">Cantidad</th>
                                            <th scope="col" class="text-white">Fecha Vencimiento</th>
                                            <th scope="col" class="text-white">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalleLote.length">
                                        <tr v-for="detalleLote in arrayDetalleLote" :key="detalleLote.id">
                                            <td :class="detalleLote.v_fecha ?'bg-rojo' :''" v-text="detalleLote.lote"></td>
                                            <td :class="detalleLote.v_fecha ?'bg-rojo' :''" v-text="detalleLote.cantidad"></td>
                                            <td :class="detalleLote.v_fecha ?'bg-rojo' :''" v-text="detalleLote.fecha_vecimiento"></td>
                                            <td>
                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="#" @click="anularLote(detalleLote.id)"><i class="fa fa-lock text-danger"></i> Eliminar</a></li>

                                                </ul>
                                            </td>  
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="4">No hay Permisos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
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

        </div>
    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    import moment from 'moment';
    export default {
        created()
        {
            //this.Actualizar();
            this.listarPaquete();
            this.listarInventario2(1, this.buscarT, this.criterio);
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
                    fecha_vence : moment().add(30,'days').format('YYYY-MM-DD'),

                },
                v_fecha:0,
                arrayTienda : [],
                arrayTienda2 : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCliente : 0,
                errorMostrarMsjCliente : [],
                arrayDetalleLote : [],
                arrayProductoLote:[],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                paginationProducto : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'articulo.nombre_comercial',
                buscar : '',
                buscarT : '',
                listado : 0,
                inventario :0,
                setTimeoutBuscador: '',
                anio : moment().format('YYYY'),
                loading: true,
                tableLoading: false,

            }
        },
        computed : {
            isActived: function(){
                return this.pagination.current_page;
            },
            isActivedProducto: function(){
                return this.paginationProducto.current_page;
            },
            pagesNumberProducto: function(){
                if(!this.paginationProducto.to){
                    return [];
                }
                var from = this.paginationProducto.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if(to >= this.paginationProducto.last_page){
                    to = this.paginationProducto.last_page;
                }
                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
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
            }
        },
        methods : {
            async refreshProductInventory(page){
                this.tableLoading = true;
                try {
                    await this.listarInventario2(page, this.buscarT, this.criterio);
                } finally {
                    this.tableLoading = false;
                }
            },
            async refreshLotInventory(page){
                this.tableLoading = true;
                try {
                    await this.listarInventario(page, this.buscar, this.criterio);
                } finally {
                    this.tableLoading = false;
                }
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
            // listarInventario(page, buscar, criterio){
            //     let me=this;

            //     var url='/tienda/inventario?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            //     axios.get(url).then(function(response){
            //         me.arrayTienda=response.data.data;
            //         me.pagination={total:response.data.total,
            //             current_page:response.data.current_page,
            //             per_page: response.data.per_page,
            //             last_page: response.data.last_page,
            //             from: response.data.from,
            //             to: response.data.to
            //         }
            //     })
            //     .catch(function(error){
            //         console.log(error)
            //     });
            //    // me.validarFecha();
            // },
                async listarInventario2(page, buscarT, criterio){
                let me=this;
                me.listarPaquete();
                try {
                    var url='/tienda/inventario?page=' + page + '&buscar=' + buscarT + '&criterio=' + criterio;
                    const res = await axios.get(url)
                    me.arrayTienda2=res.data.data;
                    me.pagination={total:res.data.total,
                        current_page:res.data.current_page,
                        per_page: res.data.per_page,
                        last_page: res.data.last_page,
                        from: res.data.from,
                        to: res.data.to
                    }

                   
                    me.arrayTienda2.forEach(item2 => {
                    var url='/articulo/contador?id=' + item2.id;
                    axios.get(url).then(function(response){
                        //me.arrayDetalle = response.data;
                       // me.listarPaquete(1,'', 'nombre');
                    })
                    .catch(function(error){
                        console.log(error)
                    });
                     });

                    me.arrayTienda2.forEach(element => {
                        element.v_fecha = 0;

                     });

                    me.arrayTienda2.forEach(element => {
                        //element.v_fecha = 0;
                        //console.log(element.id)
                        //var arrayLote = [];
                        //var arrayProductoLote = [];
                        //me.cargarProductoLote(element.id);
                        //me.arrayProductoLote = me.cargarProductoLote(element.id);
                        //console.log(me.arrayProductoLote);
                        //console.log(me.arrayProductoLote)
                        var sw = 0;
                        element.v_fecha = 0;
                        me.arrayProductoLote.forEach(item =>
                        {
                            if(item.id_producto == element.id){
                                if(me.datos.fecha_vence > item.fecha_vecimiento || sw==1 ){
                                //console.log(item.fecha_vecimiento)

                                    element.v_fecha = 1;
                                    sw = 1;
                                }else{
                                    //console.log(item.fecha_vecimiento)
                                    element.v_fecha = 0;
                                }
                            }
                            //console.log(element.v_fecha);
                        });
                        //console.log(me.arrayProductoLote);

                        // setTimeout(function(){

                        // }, 3000);
                     });


                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }
               // me.validarFecha();
            },

            async listarArticulo2BusquedaRapida(){
                let me=this;
                me.listarPaquete();
                try {

                var url='/tienda/inventario?page=' + 1 + '&buscar=' + me.buscarT + '&criterio=' + me.criterio;
                    const res = await axios.get(url)
                    me.arrayTienda2=res.data.data;
                    me.pagination={total:res.data.total,
                        current_page:res.data.current_page,
                        per_page: res.data.per_page,
                        last_page: res.data.last_page,
                        from: res.data.from,
                        to: res.data.to
                    }
                
                 me.arrayTienda2.forEach(item2 => {
                    var url='/articulo/contador?id=' + item2.id;
                    axios.get(url).then(function(response){
                        //me.arrayDetalle = response.data;
                       // me.listarPaquete(1,'', 'nombre');
                    })
                    .catch(function(error){
                        console.log(error)
                    });
                     });

                    me.arrayTienda2.forEach(element => {
                        element.v_fecha = 0;

                     });

                    me.arrayTienda2.forEach(element => {
                        //element.v_fecha = 0;
                        //console.log(element.id)
                        //var arrayLote = [];
                        //var arrayProductoLote = [];
                        //me.cargarProductoLote(element.id);
                        //me.arrayProductoLote = me.cargarProductoLote(element.id);
                        //console.log(me.arrayProductoLote);
                        //console.log(me.arrayProductoLote)
                        var sw = 0;
                        element.v_fecha = 0;
                        me.arrayProductoLote.forEach(item =>
                        {
                            if(item.id_producto == element.id){
                                if(me.datos.fecha_vence > item.fecha_vecimiento || sw==1 ){
                                //console.log(item.fecha_vecimiento)

                                    element.v_fecha = 1;
                                    sw = 1;
                                }else{
                                    //console.log(item.fecha_vecimiento)
                                    element.v_fecha = 0;
                                }
                            }
                            //console.log(element.v_fecha);
                        });
                        //console.log(me.arrayProductoLote);

                        // setTimeout(function(){

                        // }, 3000);
                     });


                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }                
            },
            BuscandoArticulo2(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticulo2BusquedaRapida,350)
            },

            async listarInventario(page, buscar, criterio){
                let me=this;
                //me.listarPaquete();
                try {
                    var url='tienda/listarSinPaginateInventario?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                    const res = await axios.get(url)
                    me.arrayTienda=res.data.data;
                    me.arrayTienda.forEach(element => {
                            var fecha_anio=  moment(element.fecha_vecimiento).format('YYYY')
                            if(me.anio >= fecha_anio){
                                element.v_fecha = 1;
                            }else{
                            }
                     });
                    me.paginationProducto={total:res.data.total,
                        current_page:res.data.current_page,
                        per_page: res.data.per_page,
                        last_page: res.data.last_page,
                        from: res.data.from,
                        to: res.data.to
                    }

                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }
            },

            async listarArticuloBusquedaRapida(){
                let me=this;
                //me.listarPaquete();
                try {

                var url='tienda/listarSinPaginateInventario?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                    const res = await axios.get(url)
                    me.arrayTienda=res.data.data;
                    me.arrayTienda.forEach(element => {
                            var fecha_anio=  moment(element.fecha_vecimiento).format('YYYY')
                            if(me.anio >= fecha_anio){
                                element.v_fecha = 1;
                            }else{
                            }
                     });
                    me.paginationProducto={total:res.data.total,
                        current_page:res.data.current_page,
                        per_page: res.data.per_page,
                        last_page: res.data.last_page,
                        from: res.data.from,
                        to: res.data.to
                    }
                
                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }                
            },
            BuscandoArticulo(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticuloBusquedaRapida,350)
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

            cambiarPagina(page,buscarT, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarInventario2(page, buscarT, criterio);
            },
            cambiarPaginaProducto(page, buscar, criterio){
                let me=this;
                me.paginationProducto.current_page=page;
                me.listarInventario(page, buscar, criterio);
            },
            MostrarLotes(data=[]){
                let me = this;
                me.listado = 1;
                me.datos.id=data['id_tienda_articulo'];
                me.datos.nombre=data['nombre_comercial'];
                me.datos.stock=data['stock'];
                me.cargarDetalle();
            },
            cargarDetalle(){
                let me = this;
                var url='/articulo/detalleLote?buscar=' + me.datos.id;
                axios.get(url).then(function(response){
                    me.arrayDetalleLote= response.data;
                    me.arrayDetalleLote.forEach(element => {
                            if(me.datos.fecha_vence > element.fecha_vecimiento ){
                                element.v_fecha = 1;
                            }else{
                                element.v_fecha = 0;
                            }

                     });
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            limpiar()
            {
                this.arrayTienda = [];
                this.arrayProductoLote= []
            },
            Actualizar(){
                let me = this;
                 me.listarPaquete();
                 me.listarInventario(1, this.buscar, this.criterio);

                // Swal.fire({
                //     position: 'top-end',
                //     icon: 'success',
                //     title: 'Sistema Actualizado...',
                //     showConfirmButton: false,
                //     timer: 2000
                // });
            },
            anularLote(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Eliminar este Lote??',
                    text: "No Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/lote/anular',{'id': id}).then(function (response) {
                        me.cargarDetalle();
                        me.listarInventario(1,'', 'nombre_comercial');
                        me.listarInventario(1,'', 'nombre_comercial');
                        me.listarInventario2(1,'', 'nombre_comercial');
                        me.listarInventario2(1,'', 'nombre_comercial');
                        me.buscar = '';
                        swalWithBootstrapButtons.fire(
                            'Anulado!',
                        'Este lote se ha Eliminado.',
                        'success'
                        )
                        me.inventario=1;
                        //me.inventario = 1;
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Esta lote no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            } 
        },
        async mounted() {
            this.listarPaquete();
            try {
                await Promise.all([
                    this.listarInventario(1, this.buscar, this.criterio),
                    this.listarInventario2(1, this.buscarT, this.criterio),
                ]);
            } finally {
                this.loading = false;
            }
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

<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                    <template v-if="listado==0">
                    <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">SERVICIOS</h3>
                    </div>
                    <!-- prueba card -->
                    <div class="row mt-2" style='width:90%;margin-left: 1.5%'>
                        <div class="col-sm-6 col-lg-3" >
                            <div class="card mb-4" style="--cui-card-cap-bg: #F76D00">
                                <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                <strong> SERVICIOS </strong>
                                </div>
                                <div class="card-body row text-center">
                                    <div class="col">
                                        <div class="avatar avatar-md"><img class="avatar-img" src="img/sit_norte/icons8-producto-48.png"></div>
                                    </div>
                                    <div class="col">
                                        <div class="fs-5 fw-semibold">{{servicio_registro}}</div>
                                        <div class="text-uppercase text-medium-emphasis small">Registros</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <!-- /.col-->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card mb-4" style="--cui-card-cap-bg: #0E91FF">
                                <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                <strong> CATEGORIA </strong>
                                </div>
                                <div class="card-body row text-center">
                                <div class="col">
                                    <div class="fs-5 fw-semibold">{{categoria_registro}}</div>
                                    <div class="text-uppercase text-medium-emphasis small">Registros</div>
                                </div>
                                <div class="vr"></div>
                                <div class="col">
                                    <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#frmCategoria">
                                        <i  class="icon-plus"></i>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                            new
                                            <span class="visually-hidden">unread messages</span>
                                        </span>
                                    </button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- prueba fin card -->


                        <div class="card-header">
                            <button type="button" @click="btnNuevoArticulo()" class="btn btn-info text-white" style='margin-left: 1.2%'>
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>


                        <!-- Inicio -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                        
                        </ul>
                        <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">

                            <div class="form-group row" id="home">
                            <div class="col-md-6" >
                                &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 4.4%'>
                                    <div class="col-md-3">
                                        <select class="form-select" v-model="criterio">
                                            <option value="articulo.nombre_comercial">Nombre Comercial</option>
                                            <option value="categoria.nombre">Categoria</option>                                       
                                        </select>
                                    </div>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup.enter="listarArticulo(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticulo(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Light table -->
                        <div class="table-responsive">
                        <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                            <thead style="background-color: #46546C">
                                <tr>
                                    <th scope="col" class="text-white">Cod. Producto</th> 
                                    <th scope="col" class="text-white">Categoria</th>                     
                                    <th scope="col" class="text-white">Nombre Comercial</th>
                                    <th scope="col" class="text-white">Precio Venta</th>
                                    <th scope="col" class="text-white">Estado</th>
                                    <th scope="col" class="text-white">Opciones</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayArticulo.length">
                                <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                    <td v-text="articulo.cod_producto"></td>
                                    <td v-text="articulo.categoria"></td>
                                    <td v-text="articulo.nombre_comercial"></td>
                                    <td v-text="articulo.costo_unitario"></td>
                                    <td>
                                        <template v-if="articulo.estado==1">
                                            <span class="badge bg-success">Activo</span>
                                        </template>
                                        <template v-else>
                                            <span class="badge bg-danger">Desactivo</span>
                                        </template>
                                     </td> 
                                    <td>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" @click="editarArticulo(articulo)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                             <template v-if="articulo.estado==1">
                                            <li><a class="dropdown-item" href="#" @click="desactivarArticulo(articulo.id)"><i class="fa fa-unlock text-success"></i> Desactivar</a></li>
                                        </template>
                                        <template v-else>
                                            <li><a class="dropdown-item" href="#" @click="activarArticulo(articulo.id)"><i class="fa fa-lock text-danger"></i> Activar</a></li>
                                        </template>
                                        </ul>
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
                        <!-- Card Pagination -->


                        </div>
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            
                        <div class="form-group row" id="profile">
                          <div class="col-md-8" >
                                &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                    <div class="col-md-3">
                                        <select class="form-select col-md-3" v-model="criterio">
                                            <option value="articulo.nombre_comercial">Servicio</option>   
                                            <option value="categoria.nombre">Categoria</option>                                  
                                        </select>
                                    </div>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup.enter="listar2(1, buscar, criterio)" @keyup="BuscandoArticuloServicio()" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listar2(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Light table -->
                        <div class="table-responsive">
                        <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                            <thead style="background-color: #46546C">
                                <tr>
                                    <th scope="col" class="text-white">Cod. Servicio</th> 
                                    <th scope="col" class="text-white">Categoria</th>                       
                                    <th scope="col" class="text-white">Nombre Comercial</th>
                                    <th scope="col" class="text-white">Precio Venta</th>
                                    <th scope="col" class="text-white">Estado</th>
                                    <th scope="col" class="text-white">Opciones</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayArticulo2.length">
                                <tr v-for="articulo in arrayArticulo2" :key="articulo.id">
                                    <td v-text="articulo.cod_producto"></td>
                                    <td v-text="articulo.categoria"></td>
                                    <td v-text="articulo.nombre_comercial"></td>
                                    <td v-text="articulo.costo_unitario"></td>
                                    <td>
                                        <template v-if="articulo.estado==1">
                                            <span class="badge bg-success">Activo</span>
                                        </template>
                                        <template v-else>
                                            <span class="badge bg-danger">Desactivo</span>
                                        </template>
                                     </td> 
                                    <td>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" @click="editarArticulo(articulo)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                             <template v-if="articulo.estado==1">
                                            <li><a class="dropdown-item" href="#" @click="desactivarArticulo(articulo.id)"><i class="fa fa-unlock text-success"></i> Desactivar</a></li>
                                        </template>
                                        <template v-else>
                                            <li><a class="dropdown-item" href="#" @click="activarArticulo(articulo.id)"><i class="fa fa-lock text-danger"></i> Activar</a></li>
                                        </template>
                                        </ul>
                                    </td>                                 
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="11">No hay Servicios agregados</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        <!-- Card Pagination -->

                        </div>
                        </div>
                        <!--fin  -->

                        



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

                    <template v-if="listado==1">
                        <div class="card-body">
                            <div class="card-header text-center">
                            <div class="row">
                            <div class="col-md-1">
                                <button type="button" @click="ocultarListado1()" class="btn btn-danger text-white " >
                                    <i class="fa fa-reply-all"></i>&nbsp;Volver
                                   
                                </button>
                                 
                            </div>
                            <div class="col-md-9 text-center">
                                 <h3 class="mb-0">DATOS DE SERVICIOS</h3>  
                            </div>


                            </div>
                            </div>
                            <div class="card-header text-center line p-0 m-0"  style="background-color: #3399FF">
                                
                            </div>&nbsp;
                               
                            <form class="row g-3">
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Nombre Comercial</label>
                                    <input type="text" class="form-control" v-model="datos.nombre_comercial">

                                    <div class="row" v-if="errores.nombre_comercial">
                                        <div class="col-sm-10"><span class="text-danger">{{errores.nombre_comercial[0]}}</span></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Categoria</label>
                                    <select class="form-select" v-model="datos.id_categoria">
                                        <option value="0" disabled>Seleccione la Categoria</option>
                                        <option v-for="articulo in arrayTipoCategoria" :key="articulo.id" :value="articulo.id" v-text="articulo.nombre"></option>
                                    </select>
                                    <div class="row" v-if="errores.id_categoria">
                                        <!-- <div class="col-sm-2">&nbsp;</div> -->
                                        <div class="col-sm-10"><span class="text-danger">{{errores.id_categoria[0]}}</span></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Precio Venta</label>
                                    <input type="number" class="form-control" v-model="datos.costo_unitario" min="0">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Descripción</label>
                                    <input type="text" class="form-control" v-model="datos.descripcion">
                                </div>

                                <div class="col-md-12">
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
                            </form>
                            <div class="header-divider"></div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-danger me-md-2 text-white" type="button" @click="ocultarListado1()">Cancelar</button>
                                <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==1"  type="button" @click="guardarArticulo()">Guardar Servicio</button>
                                <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==2"  type="button" @click="modificarArticulo()">Modificar Servicio</button>
                            </div>
                        </div>
                        
                    </template>
                  </div>
                </div>
            </div>
        <!-- </div> -->

        <!--Modal Formulario Categoria-->
        <div class="modal fade" id="frmCategoria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white">
                        <h4 class="modal-title ">Registro Categoria</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nombre</label>
                                <div><input type="text" class="form-control" v-model="datosCategoria.nombre"></div>
                            </div>
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
                                <textarea class="form-control" id="message-text" v-model="datosCategoria.descripcion"></textarea>
                                <!-- <div class="col-sm-10"><input type="text" class="form-control" v-model="datosCategoria.descripcion"></div> -->
                            </div>
                            <div v-show="errorArticulo" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-info text-white" @click="guardarCategoria() ">Guardar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Cargo-->

    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    import moment from 'moment';
    export default {

        data(){
            return {
                datos : {
                    id : 0,
                    fecha_vencimiento :  moment().format('YYYY-MM-DD'),
                    cod_proveedor : 0,
                    cod_veterinaria : 0,
                    nombre_comercial : '',
                    nombre_generico: '',
                    presentacion: '',
                    contenido_unidad: '',
                    costo_compra: 0,
                    costo_unitario: 0,
                    stock_minimo: 0,
                    tipo : '0',
                    tipo_producto : 'Producto Servicio',
                    descripcion : '',
                    estado : '1',
                    id_categoria : 0,
                    id_unidad : 0,
                    id_proveedor : 0,
                },
                datosCategoria : {
                    id : 0,
                    nombre : '',
                    descripcion : '',
                    estado : '1',
                }, 
                categoria_registro :0,
                personal_registro :0, 
                servicio_registro :0,                               
                arrayArticulo : [],
                arrayArticulo2 : [],
                errores:{},
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorArticulo : 0,
                errorMostrarMsjArticulo : [],
                pagination : {
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
                arrayTipoCategoria : [],
                listado: 0,
                setTimeoutBuscador: '',
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
            }
        },
        methods : {
            listarArticulo(page, buscar, criterio){
                let me=this;
                var url='/articulo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayArticulo=response.data.data;
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
            listar2BusquedaRapida(){
                let me=this;
                var url='/articulo2?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayArticulo2=response.data.data;
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
            BuscandoArticuloServicio(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listar2BusquedaRapida,350)
            },
            listar2(page, buscar, criterio){
                let me=this;
                var url='/articulo2?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayArticulo2=response.data.data;
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
            btnNuevoArticulo(){
                this.selectCategoria();
                let me = this;
                me.listado=1;
                this.tipoAccion = 1;
                me.datos = {
                     id : 0,
                    fecha_vencimiento :  moment().format('YYYY-MM-DD'),
                    cod_proveedor : 0,
                    cod_veterinaria : 0,
                    nombre_comercial : '',
                    nombre_generico: '',
                    presentacion: '',
                    contenido_unidad: '',
                    costo_compra: 0,
                    costo_unitario: 0,
                    stock_minimo: 0,
                    tipo : '0',
                    tipo_producto : 'Producto Servicio',
                    descripcion : '',
                    estado : '1',
                    id_categoria : 0,
                    id_unidad : 0,
                    id_proveedor : 0,
                };
            },
           async guardarArticulo(){
 
                try {
                    let me = this;
                    let data = {
                        id : me.datos.id,
                        cod_proveedor : me.datos.cod_proveedor,
                        cod_veterinaria : me.datos.cod_veterinaria,
                        nombre_comercial : me.datos.nombre_comercial,
                        nombre_generico : me.datos.nombre_generico,
                        presentacion : me.datos.presentacion,
                        contenido_unidad: me.datos.contenido_unidad,
                        costo_compra: me.datos.costo_compra,
                        costo_unitario: me.datos.costo_unitario,
                        stock_minimo: me.datos.stock_minimo,
                        fecha_vencimiento: me.datos.fecha_vencimiento,
                        tipo : me.datos.tipo,
                        tipo_producto : 'Producto Servicio',
                        descripcion : me.datos.descripcion,
                        estado : me.datos.estado,
                        id_categoria : me.datos.id_categoria >0 ? me.datos.id_categoria : "",
                    }
                    const res = await axios.post('/articulo/guardarServicio', data);
                    if(res.data.error==0){
                        //me.$toaster.error('Matricula ya existe...');
                        Swal.fire({
                            icon: 'error',
                            title: 'Nombre ya existe...',
                            text: 'Debe usar otra nombre!'
                        })
                    }
                    else{
                        me.listado =0;
                        me.registrosArticulo();
                        me.listarArticulo(1, '', 'producto.nombre');   
                        me.listar2(1, '', 'producto.nombre');
                        me.errores ={};
                        me.datos = {
                        id : 0,
                        cod_producto : '',
                        cod_proveedor : '',
                        cod_ean : '',
                        nombre : '',
                        marca : '',
                        costo_compra: 0,
                        costo_unitario: 0,
                        costo_mayorista: 0,
                        costo_preferencial: 0,
                        stock_minimo: 0,
                        tipo_producto : 'Producto Servicio',
                        descripcion : '',
                        estado : '1',
                        id_categoria : 0,
                    },
                    me.registrosArticulo();
                    me.registrosArticuloServicio();
                    me.arrayArticulo = [];
                    me.arrayArticulo2 = [];
                    me.arrayTipoCategoria = [];
   
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Registro agregado...',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }  
                    
                } catch (error) {
                    console.log(this.datos);
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }
            },
            async modificarArticulo(){
                try {
                    let me = this;
                    const res = await axios.put('/articulo/modificarServicio',me.datos);
                    if(res.data.error==0){
                        Swal.fire({
                            icon: 'error',
                            title: 'Nombre ya existe...',
                            text: 'Debe usar otra nombre!'
                        })
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Registro modificado...',
                            showConfirmButton: false,
                            timer: 1500
                        }) 
                        me.listado =0;
                        me.listarArticulo(1,'', 'articulo.nombre_comercial');
                        me.listar2(1,'', 'articulo.nombre_comercial');
                    }    
                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }
            },
            registrosCategoria(){
                let me=this;
                var url='/categoria/cantidad';
                axios.get(url).then(function(response){
                    me.categoria_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            registrosArticulo(){
                let me=this;
                var url='/articulo/cantidad';
                axios.get(url).then(function(response){
                    me.personal_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            registrosArticuloServicio(){
                let me=this;
                var url='/articulo/cantidadServicio';
                axios.get(url).then(function(response){
                    me.servicio_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            selectCategoria(){
                let me=this;
                var url='/categoria/selectCategoria';
                axios.get(url).then(function(response){
                    me.arrayTipoCategoria=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            editarArticulo(data=[]){
                this.selectCategoria();
                let me = this;
                me.listado=1;
                this.tipoAccion = 2;
                this.datos.id = data['id'];
                this.datos.cod_proveedor = data['cod_proveedor'];
                this.datos.nombre_comercial = data['nombre_comercial'];
                this.datos.nombre_generico = data['nombre_generico'];
                this.datos.costo_unitario = data['costo_unitario'];
                this.datos.descripcion = data['descripcion'];
                this.datos.estado = data['estado'];
                this.datos.id_categoria = data['id_categoria'];
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarArticulo(page, buscar, criterio);
                me.listar2(page, buscar, criterio);
            },
            ocultarListado1(){
                this.listado=0;
                this.errores = {};
            },
            guardarCategoria(){
            //   if(this.validarArticulo()){
            //         return;
            //     }
                let me = this;
                axios.post('/categoria/guardar',this.datosCategoria).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.registrosCategoria();  
                    me.limpiarDatosCategoria();    
                })
                .catch(function(error){
                    console.log(error);
                });
                this.selectCategoria();
            },
            limpiarDatosCategoria(){
                this.datosCategoria = {
                    id : 0,
                    nombre : '',
                    descripcion : '',
                    estado : '1',
                }
                this.errores = {}
            },
            validarArticulo(){
                this.errorArticulo = 0;
                this.errorMostrarMsjArticulo = [];

                if(!this.datos.nombre) this.errorMostrarMsjArticulo.push("El nombre del Cliente no puede estar vacio ");
                if(this.errorMostrarMsjArticulo.length) this.errorArticulo=1;
                return this.errorArticulo;
            },
            desactivarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Inhabilitar este Articulo??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Inhabilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/articulo/desactivar',{'id': id}).then(function (response) {
                        me.listarArticulo(1,'', 'nombre');
                        me.listar2(1,'', 'nombre');
                        swalWithBootstrapButtons.fire(
                        'Inhabilitado!',
                        'Este articulo se ha Inhabilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este cargo no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            activarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Habilitar este Articulo??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/articulo/activar',{'id': id}).then(function (response) {
                        me.listarArticulo(1,'', 'nombre');
                        me.listar2(1,'', 'nombre');
                        swalWithBootstrapButtons.fire(
                        'Habilitado!',
                        'Este articulo se ha Habilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este articulo no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            } 
        },
        mounted() {
            //this.listarArticulo(1, this.buscar, this.criterio);
            this.listar2(1, this.buscar, this.criterio);
            this.registrosCategoria();
            this.registrosArticulo();
            this.registrosArticuloServicio();
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
    .line{
        height:3px !important;
    }
</style>

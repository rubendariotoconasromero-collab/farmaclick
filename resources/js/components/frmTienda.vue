<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                   <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">ADMINISTRACION TIENDA - PRODUCTO - SERVICIO</h3></div>
                        <!-- VISTA PRINCIPAL -->
                        <template v-if="listado==0">
                            &nbsp;
                            <div class="form-group row" style='width:96%;margin-left:1%'>
                                <div class="col-md-12">
                                    <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" v-model="datos.id">
                                        <option value="0">Seleccione Tienda</option>
                                        <option v-for="tienda in arrayTienda" :key="tienda.id" :value="tienda.id" v-text="tienda.nombre"></option>
                                    </select>
                                </div>

                                <div class="col-md-12">
                                    <button type="button" @click="gestionarInventario(),listarArticulo(),listarArticuloProducto(),listarArticuloServicio()" class="btn btn-info text-white"><i class="fa fa-search"></i> Ver Inventario</button>
                                    <button type="button" @click="gestionarArticulo()" class="btn btn-info text-white" :disabled="datos.id==1"><i class="fa fa-th"></i> Gestionar Productos</button>

                                    <button type="button" @click="gestionarServicio()" class="btn btn-info text-white" :disabled="datos.id==1"><i class="fa fa-th"></i> Gestionar Servicios</button>
                                </div>
                            </div>
                            <br>
                        </template>
                        <!-- FIN VISTA PRINCIPAL -->

                        <!-- AGREGAR PRODUCTOS A TIENDA-->
                        <template v-if="listado==1">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <select class="form-select form-select-lg mb-3" v-model="datos.id" disabled>
                                            <option value="0">Seleccione Tienda</option>
                                            <option v-for="tienda in arrayTienda" :key="tienda.id" :value="tienda.id" v-text="tienda.nombre"></option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="button" @click="volverTiendaPrincipal()" class="btn btn-danger text-white"><i class="fa fa-reply-all"></i> Volver</button>
                                        <button type="button" class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#modalArticulo" @click="listarProducto2(buscar,criterio)"><i class="fa fa-th"></i> Agregar productos</button>
                                    </div>
                                </div>
                                <br>
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead style="background-color: #46546C">
                                        <tr>                      
                                            <th scope="col" class="text-white">Opción</th>
                                            <th scope="col" class="text-white">Cod. Producto</th> 
                                            <th scope="col" class="text-white">Cod. Proveedor</th>
                                            <th scope="col" class="text-white">Categoria</th>                       
                                            <th scope="col" class="text-white">Nombre</th>
                                            <th scope="col" class="text-white">Marca</th>
                                            <th scope="col" class="text-white">Costo Compra</th>
                                            <th scope="col" class="text-white">Costo Unitario</th>
                                            <th scope="col" class="text-white">Costo Mayorista</th>
                                            <th scope="col" class="text-white">Costo Preferencial</th>
                                            <th scope="col" class="text-white">Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                            </td>
                                            <td v-text="detalle.cod_producto"></td>
                                            <td v-text="detalle.cod_proveedor"></td>
                                            <td v-text="detalle.categoria"></td>
                                            <td v-text="detalle.nombre"></td>
                                            <td v-text="detalle.marca"></td>
                                            <td v-text="detalle.costo_compra"></td>
                                            <td v-text="detalle.costo_unitario"></td>
                                            <td v-text="detalle.costo_mayorista"></td>
                                            <td v-text="detalle.costo_preferencial"></td>
                                            <template v-if="detalle.tipo_producto=='Producto Venta'">
                                                <td>
                                                    <vue-numeric v-model="detalle.stock" type="number" class="form-control"/>
                                                    <span style="color:red;" v-show="detalle.stock<0">Error...</span>
                                                </td>  
                                            </template>
                                            <template v-else>
                                                <td></td>   
                                            </template> 
                                                                    
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="11">No hay Productos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-info btn-lg text-white" type="button" @click="guardarTienda()">Guardar</button>
                                </div>
                            </div>
                        </template>
                        <!-- FIN AGREGAR PRODUCTOS A TIENDA-->

                        <!-- AGREGAR SERVICIOS A TIENDA -->
                        <template v-if="listado==3">
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <select class="form-select form-select-lg mb-3" v-model="datos.id" disabled>
                                            <option value="0">Seleccione Tienda</option>
                                            <option v-for="tienda in arrayTienda" :key="tienda.id" :value="tienda.id" v-text="tienda.nombre"></option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="button" @click="volverTiendaPrincipal()" class="btn btn-danger text-white"><i class="fa fa-reply-all"></i> Volver</button>
                                        <button type="button" class="btn btn-success text-white" data-bs-toggle="modal" data-bs-target="#modalServicio" @click="listarServicio2(buscar,criterio)"><i class="fa fa-th"></i> Agregar servicios</button>
                                    </div>
                                </div>
                                <br>
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead style="background-color: #46546C">
                                        <tr>                      
                                            <th scope="col" class="text-white">Opción</th>
                                            <th scope="col" class="text-white">Cod. Servicio</th>    
                                            <th scope="col" class="text-white">Categoria</th>                  
                                            <th scope="col" class="text-white">Nombre</th>
                                            <th scope="col" class="text-white">Costo Unitario</th>
                                            <th scope="col" class="text-white">Costo Mayorista</th>
                                            <th scope="col" class="text-white">Costo Preferencial</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                            </td>
                                            <td v-text="detalle.cod_producto"></td>
                                            <td v-text="detalle.categoria"></td>
                                            <td v-text="detalle.nombre"></td>
                                            <td v-text="detalle.costo_unitario"></td>
                                            <td v-text="detalle.costo_mayorista"></td>
                                            <td v-text="detalle.costo_preferencial"></td>
                                                                    
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="9">No hay Servicios agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-info btn-lg text-white" type="button" @click="guardarTienda()">Guardar</button>
                                </div>
                            </div>
                        </template>
                        <!-- FIN AGREGAR SERVICIOS A TIENDA  -->

                        

                        <!-- LISTA PRODUCTOS Y SERVICIOS-->
                        <template v-if="listado==2">
                            <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <select class="form-select form-select-lg mb-3" v-model="datos.id" disabled>
                                        <option value="0">Seleccione Tienda</option>
                                        <option v-for="tienda in arrayTienda" :key="tienda.id" :value="tienda.id" v-text="tienda.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Inicio -->
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Ventas</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Servicios</button>
                                    </li>
                                    <li class="nav-item" role="presentation">&nbsp;&nbsp;&nbsp;&nbsp;
                                        <button type="button" @click="volverTiendaPrincipal()" class="btn btn-danger text-white"><i class="fa fa-reply-all"></i> Volver</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <!-- Light table -->
                                            <div class="table-responsive mt-4 mb-2">
                                                <table class="table table-striped table-hover">
                                                    
                                                    <thead style="background-color: #46546C">
                                                        <tr>       
                                                            <th scope="col" class="text-white">Cod. Producto</th> 
                                                            <th scope="col" class="text-white">Cod. Proveedor</th>                       
                                                            <th scope="col" class="text-white">Categoria</th>                       
                                                            <th scope="col" class="text-white">Nombre</th>
                                                            <th scope="col" class="text-white">Marca</th>
                                                            <th scope="col" class="text-white">Costo Compra</th>
                                                            <th scope="col" class="text-white">Costo Unitario</th>
                                                            <th scope="col" class="text-white">Costo Mayorista</th>
                                                            <th scope="col" class="text-white">Costo Preferencial</th>
                                                            <th scope="col" class="text-white">Stock</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-if="arrayTiendaArticuloProducto.length">
                                                        <tr v-for="(t_articulo,index) in arrayTiendaArticuloProducto" :key="index">
                                                            <td v-text="t_articulo.cod_producto"></td>
                                                            <td v-text="t_articulo.cod_proveedor"></td>
                                                            <td v-text="t_articulo.categoria"></td>
                                                            <td v-text="t_articulo.nombre"></td>
                                                            <td v-text="t_articulo.marca"></td>
                                                            <td v-text="t_articulo.costo_compra"></td>
                                                            <td v-text="t_articulo.costo_unitario"></td>
                                                            <td v-text="t_articulo.costo_mayorista"></td>
                                                            <td v-text="t_articulo.costo_preferencial"></td>
                                                            <template v-if="t_articulo.tipo_producto=='Producto Venta'">
                                                                <td v-text="t_articulo.stock"></td>   
                                                            </template>
                                                            <template v-else>
                                                                <td></td>   
                                                            </template>     
                                                        </tr>
                                                    </tbody>
                                                    <tbody v-else>
                                                        <tr>
                                                            <td colspan="10">No hay Productos agregados</td>
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
                                    </div>
                                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                        <!-- Light table -->
                                            <div class="table-responsive mt-4 mb-2">
                                                <table class="table table-striped table-hover">
                                                    <thead style="background-color: #46546C">
                                                        <tr>                  
                                                            <th scope="col" class="text-white">Cod. Producto</th>   
                                                            <th scope="col" class="text-white">Categoria</th>                   
                                                            <th scope="col" class="text-white">Nombre</th>
                                                            <th scope="col" class="text-white">Costo Unitario</th>
                                                            <th scope="col" class="text-white">Costo Mayorista</th>
                                                            <th scope="col" class="text-white">Costo Preferencial</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-if="arrayTiendaArticuloServicio.length">
                                                        <tr v-for="(t_articulo,index) in arrayTiendaArticuloServicio" :key="index">
                                                            <td v-text="t_articulo.cod_producto"></td>
                                                            <td v-text="t_articulo.categoria"></td>
                                                            <td v-text="t_articulo.nombre"></td>
                                                            <td v-text="t_articulo.costo_unitario"></td>
                                                            <td v-text="t_articulo.costo_mayorista"></td>
                                                            <td v-text="t_articulo.costo_preferencial"></td>  
                                                        </tr>
                                                    </tbody>
                                                    <tbody v-else>
                                                        <tr>
                                                            <td colspan="8">No hay Servicios agregados</td>
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
                                        
                                    </div>
                                </div>
                            <!--fin  -->
                            </div>
                        </template>
                        <!-- FIN PRODUCTOS Y SERVICIOS POR TIENDA -->



                    </div>
                </div>
            </div>
        <!-- </div> -->

        <!--Modal Formulario Articulo-->
        <div class="modal fade" id="modalArticulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white" style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDAS DE PRODUCTOS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-select col-md-3" v-model="criterio">
                                        <option value="articulo.nombre">Nombre</option>
                                        <option value="articulo.cod_producto">Cod. Articulo</option>
                                        <option value="categoria.nombre">Categoria</option>
                                    </select>&nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup.enter="listarProducto2(buscar,criterio)" @keyup="BuscandoProducto()" class="form-control" placeholder="Texto a buscar">&nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarProducto2(buscar,criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>                      
                                    <th scope="col" class="text-white">Cod. Producto</th> 
                                    <th scope="col" class="text-white">Cod. Proveedor</th>                       
                                    <th scope="col" class="text-white">Categoria</th>                       
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">Marca</th>
                                    <th scope="col" class="text-white">Costo Compra</th>
                                    <th scope="col" class="text-white">Costo Unitario</th>
                                    <th scope="col" class="text-white">Costo Mayorista</th>
                                    <th scope="col" class="text-white">Costo Preferencial</th>
                                    <th scope="col" class="text-white">Stock</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayFilterProducto.length">
                                <tr v-for="producto in arrayFilterProducto" :key="producto.id">
                                    <td v-text="producto.cod_producto"></td>
                                    <td v-text="producto.cod_proveedor"></td>
                                    <td v-text="producto.categoria"></td>
                                    <td v-text="producto.nombre"></td>
                                    <td v-text="producto.marca"></td>
                                    <td v-text="producto.costo_compra"></td>
                                    <td v-text="producto.costo_unitario"></td>
                                    <td v-text="producto.costo_mayorista"></td>
                                    <td v-text="producto.costo_preferencial"></td>
                                    <template v-if="producto.tipo_producto=='Producto Venta'">
                                        <td v-text="producto.stock"></td>   
                                    </template>
                                    <template v-else>
                                        <td></td>   
                                    </template> 
                                    <td>
                                        <button type="button" @click="seleccionarProducto(producto)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>   
                                    </td>                                 
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="11">No hay Productos agregados</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" @click="volverCargarProductos()" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Articulo-->

        <!--Modal Formulario Servicio-->
        <div class="modal fade" id="modalServicio" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white" style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDAS DE SERVICIOS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-select col-md-3" v-model="criterio">
                                        <option value="articulo.nombre">Nombre</option>
                                        <option value="articulo.cod_producto">Cod. Servicio</option>
                                        <option value="categoria.nombre">Categoria</option>
                                    </select>&nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup.enter="listarServicio2(buscar,criterio)" @keyup="BuscandoProductoServicio()" class="form-control" placeholder="Texto a buscar">&nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarServicio2(buscar,criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>                      
                                    <th scope="col" class="text-white">Cod. Servicio</th>  
                                    <th scope="col" class="text-white">Categoria</th>                    
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">Costo Unitario</th>
                                    <th scope="col" class="text-white">Costo Mayorista</th>
                                    <th scope="col" class="text-white">Costo Preferencial</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayFilterServicio.length">
                                <tr v-for="producto in arrayFilterServicio" :key="producto.id">
                                    <td v-text="producto.cod_producto"></td>
                                    <td v-text="producto.categoria"></td>
                                    <td v-text="producto.nombre"></td>
                                    <td v-text="producto.costo_unitario"></td>
                                    <td v-text="producto.costo_mayorista"></td>
                                    <td v-text="producto.costo_preferencial"></td>
                                    <td>
                                        <button type="button" @click="seleccionarProducto(producto)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>   
                                    </td>                                 
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="9">No hay Servicios agregados</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" @click="volverCargarServicios()" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Servicio-->

    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    export default {
        data(){
            return {
                datos : {
                    id : 0,
                    cod_tienda : '',
                    nombre : '',
                    direccion : '',
                    cod_almacen : '',
                    estado : '1',
                },                               
                arrayTienda : [],
                arrayTiendaArticulo : [],
                arrayTiendaArticuloProducto : [],
                arrayTiendaArticuloServicio : [],
                arrayProducto : [],
                arrayDetalle : [],
                arrayFilterProducto : [],

                arrayServicio : [],
                arrayFilterServicio : [],

                arrayProductoArticulo : [],
                arrayFilterProductoArticulo : [],
                arrayProductoServicio : [],
                arrayFilterProductoServicio : [],
                listado : 0,
                errorTienda : 0,
                errorMostrarMsjTienda : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'articulo.nombre',
                buscar : '',
                setTimeoutBuscador: ''
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
            selectTienda(){
                let me=this;
                var url='/tienda/selectTienda';
                axios.get(url).then(function(response){
                    me.arrayTienda=response.data;
                }).catch(function(error){
                    console.log(error)
                });                
            },
            gestionarArticulo(){
                let me = this;
                if(me.datos.id==0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Seleccione una Tienda!'
                    })
                }
                else{
                    me.listado = 1;
                }
            },
            gestionarServicio(){
                let me = this;
                if(me.datos.id==0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Seleccione una Tienda!'
                    })
                }
                else{
                    me.listado = 3;
                }
            },
            gestionarInventario(){
                let me = this;
                if(me.datos.id==0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Seleccione una Tienda!'
                    })
                }
                else{
                    me.listado = 2;
                }
            },

            
            listarArticulo(){
                let me = this;
                if(me.datos.id==0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Seleccione una Tienda!'
                    })
                }
                else{
                    var url='/tienda/detalle/articulo?id=' + me.datos.id;
                    axios.get(url).then(function(response){
                        me.arrayTiendaArticulo= response.data;
                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }
            },


            listarArticuloProducto(){
                let me = this;
                if(me.datos.id==0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Seleccione una Tienda!'
                    })
                }
                else{
                    var url='/tienda/detalle/articulo_producto?id=' + me.datos.id;
                    axios.get(url).then(function(response){
                        me.arrayTiendaArticuloProducto= response.data;
                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }
            },

            listarArticuloServicio(){
                let me = this;
                if(me.datos.id==0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Seleccione una Tienda!'
                    })
                }
                else{
                    var url='/tienda/detalle/articulo_servicio?id=' + me.datos.id;
                    axios.get(url).then(function(response){
                        me.arrayTiendaArticuloServicio= response.data;
                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }
            },




            volverTiendaPrincipal(){
                this.listado=0;
                this.arrayDetalle = [];
                this.arrayTiendaArticulo = [];
                this.datos.id=0;
            },

            listarProducto2(buscar, criterio){
                let me = this;
                var url='/articulo/listarSinPaginate2Producto?buscar=' + buscar + '&criterio='+ criterio;
                axios.get(url).then(function(response){
                    me.arrayProducto= response.data;
                    me.arrayFilterProducto = me.arrayProducto;
                    
                    me.listarArticulo();
                })
                .catch(function(error){
                    console.log(error);
                });
            },

            listarArticuloBusquedaRapida(){
                let me = this;
                var url='/articulo/listarSinPaginate2Producto?buscar=' + me.buscar + '&criterio='+ me.criterio;
                axios.get(url).then(function(response){
                    me.arrayProducto= response.data;
                    me.arrayFilterProducto = me.arrayProducto;
                    
                    me.listarArticulo();
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
            listarServicio2(buscar, criterio){
                let me = this;
                var url='/articulo/listarSinPaginate2Servicio?buscar=' + buscar +'&criterio='+ criterio;
                axios.get(url).then(function(response){
                    me.arrayServicio= response.data;
                    me.arrayFilterServicio = me.arrayServicio;
                    
                    me.listarArticulo();
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarServicio2BusquedaRapida(){
                let me = this;
                var url='/articulo/listarSinPaginate2Servicio?buscar=' + me.buscar +'&criterio='+ me.criterio;
                axios.get(url).then(function(response){
                    me.arrayServicio= response.data;
                    me.arrayFilterServicio = me.arrayServicio;
                    
                    me.listarArticulo();
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            BuscandoProductoServicio(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarServicio2BusquedaRapida,350)
            },
            listarProducto(buscar, criterio){
                let me = this;
                var url='/articulo/listarSinPaginate2?buscar=' + buscar+'&criterio='+criterio;
                axios.get(url).then(function(response){
                    me.arrayProductoArticulo= response.data;
                    me.arrayFilterProductoArticulo = me.arrayProductoArticulo;
                    
                    me.listarArticulo();
                })
                .catch(function(error){
                    console.log(error);
                });
            },

            listarServicio(buscar, criterio){
                let me = this;
                var url='/articulo/listarSinPaginate2Servicio?buscar=' + buscar+'&criterio='+criterio;
                axios.get(url).then(function(response){
                    me.arrayProductoServicio= response.data;
                    me.arrayFilterProductoServicio = me.arrayProductoServicio;
                    
                    me.listarArticulo();
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
            seleccionarProducto(data=[]){
                let me = this;
                if(me.encuentra(data['id_articulo'])){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Ya se encuentra agregado!'
                    })
                }
                else{
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Articulo agregado...',
                        showConfirmButton: false,
                        timer: 500
                    });

                    me.arrayDetalle.push({
                        id : data['id'],
                        id_articulo: data['id_articulo'],
                        cod_producto : data['cod_producto'],
                        cod_proveedor : data['cod_proveedor'],
                        id_categoria : data['id_categoria'],
                        categoria : data['categoria'],
                        nombre : data['nombre'],
                        marca : data['marca'],
                        costo_compra : data['costo_compra'],
                        costo_unitario : data['costo_unitario'],
                        costo_mayorista : data['costo_mayorista'],
                        costo_preferencial : data['costo_preferencial'],
                        stock : (data['tipo_producto'] == 'Producto Servicio') ? 1 : 0,
                        saldoStock : data['tipo_producto'] == 'Producto Servicio' ? 1 : data['stock'],
                        tipo_producto : data['tipo_producto']
                    }); 

                }
            },
            guardarTienda(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe articulos agregados!'
                    })
                }
                let me = this;
                if(this.arrayDetalle.find(
                        seg => (seg.saldoStock - seg.stock < 0 ||
                        seg.saldoStock == 0
                    ))){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No hay stock para el producto!'
                    })
                } else {
                    if(this.arrayDetalle.find( seg => ( seg.stock == 0 ) )){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Debe Agregar Stock a la Tienda!'
                        })
                    } else {
                        axios.post('/tienda/articulo/guardar',{
                            'id_tienda': me.datos.id,
                            'detalle': me.arrayDetalle
                        }).then(function(response){
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Tienda - Articulo registrado exitosamente',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            me.volverTiendaPrincipal();
                        })
                        .catch(function(error){
                            console.log(error);
                        });
                    }
                }
            },
            cerrarProducto()
            {
                this.arrayProducto=[];
                this.arrayFilterProducto=[];
                this.buscar = '';
            },
            volverCargarProductos(){
                this.listado=1;
                this.arrayProducto=[];
                this.arrayFilterProducto=[];
            },
            volverCargarServicios(){
                this.listado=3;
                this.arrayProducto=[];
                this.arrayFilterProducto=[];
            }
        },
        
        mounted() {
            this.selectTienda();
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
</style>
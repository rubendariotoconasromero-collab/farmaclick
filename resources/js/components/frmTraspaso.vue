<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                <div class="card-header text-center text-white text-white" style="background-color: #3399FF"><h3 class="mb-0">TRASPASO</h3></div>
                    <template v-if="listado==0">
                        <div class="card-header">
                            <button type="button" @click="listadoTraspaso()" class="btn btn-info text-white" style='margin-left: 1%'>
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8">
                                &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.5%'>
                                    <div class="col-md-4">
                                        <select class="form-select col-md-3" v-model="criterio">
                                            <option value="t1.nombre">Tienda Origen</option>
                                            <option value="t2.nombre">Tienda Destino</option>
                                        </select>
                                    </div>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup.enter="listarTraspaso(1, buscar, criterio)" @keyup="BuscandoTraspaso()" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarTraspaso(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
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
                                    <th scope="col" class="text-white">Tienda Origen</th>
                                    <th scope="col" class="text-white">Tienda Destino</th>
                                    <th scope="col" class="text-white">Glosa</th>
                                    <th scope="col" class="text-white">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(traspaso, index) in arrayTraspaso" :key="index">
                                    <td v-text="traspaso.fecha"></td> 
                                    <td v-text="traspaso.tienda1"></td>   
                                    <td v-text="traspaso.tienda2"></td> 
                                    <td v-text="traspaso.glosa"></td>
                                    <td>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" @click="verTraspaso(traspaso)"><i class="fa fa-eye text-warning"></i> Ver detalle</a></li>
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

                    <template v-if="listado==1">
                        <div class="card-header row m-0">
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger text-white" @click="limpiarTraspaso()">
                                    <i class="fa fa-reply-all"></i>&nbsp;Volver
                                </button>
                            </div>
                            <div class="col-md-8 text-center"><h3 class="mb-0">FORMULARIO DE TRASPASO DE PRODUCTO</h3></div>
                        </div> 
                        <div class="row m-2 p-2">
                            <div class="col-md-8"><h3 class="mb-0">TIENDA ORIGEN</h3></div>
                        </div>
                        <div class="card-header text-center line p-0 m-0"  style="background-color: #3399FF"></div>
                        <div class="card-body">                    
                            <div class="form-group row">          
                                <div class="">
                                    <label for="exampleInputPassword1" class="form-label">Origen</label>

                                    <select class="form-select form-select mb-3" v-model="datos.id_tienda1" @change="inicializarDetalles($event)">
                                        <option value="0" disabled>Seleccione Tienda</option>
                                        <option v-for="tienda in arrayTienda1" :key="tienda.id" :value="tienda.id" v-text="tienda.nombre"></option>
                                    </select>

                                </div>

                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Glosa</label>
                                    <textarea class="form-control" v-model="datos.glosa" rows="2"></textarea>
                                </div>   
    
                                
                            </div>

                            <br>
                                <div class="form-group row">
                                <div class="form-group row mb-3">
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <select class="form-select col-md-3" v-model="criterioP">
                                                <option value="articulo.nombre">Nombre</option>
                                                <option value="articulo.cod_producto">Cod. Producto</option>
                                                <option value="categoria.nombre">Categoria</option>
                                            </select>&nbsp;&nbsp;&nbsp;
                                            <input type="text" v-model="buscarP" @keyup.enter="listarArticuloProducto($event,buscarP,criterioP)" @keyup="BuscandoProducto()" class="form-control" placeholder="Texto a buscar">&nbsp;&nbsp;&nbsp;
                                            <button type="submit" @click="listarArticuloProducto($event,buscarP,criterioP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                        </div>  
                                    </div>                   
                                </div>&nbsp;&nbsp;
                                <div class="table-responsive"  style="overflow-y: auto; max-height: 350px;">
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
                                        <tbody v-if="arrayTiendaArticulo1.length && datos.id_tienda2 > 0">
                                            <tr v-for="producto in arrayTiendaArticulo1" :key="producto.id">
                                                <td v-text="producto.cod_producto"></td>
                                                <td v-text="producto.cod_proveedor"></td>
                                                <td v-text="producto.categoria"></td>
                                                <td v-text="producto.nombre"></td>
                                                <td v-text="producto.marca"></td>
                                                <td v-text="producto.costo_compra"></td>
                                                <td v-text="producto.costo_unitario"></td>
                                                <td v-text="producto.costo_mayorista"></td>
                                                <td v-text="producto.costo_preferencial"></td>
                                                <template v-if="id_detalle == producto.id">
                                                    <td>{{producto.saldoStock = producto.stock-cantidad}}</td>
                                                </template>
                                                <template v-else>
                                                    <td>{{producto.saldoStock}}</td>
                                                </template>
                                                <td>
                                                    <button type="button" @click="seleccionarProducto(producto)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>   
                                                </td>                                 
                                            </tr>
                                        </tbody>
                                        <tbody v-if="!arrayTiendaArticulo1.length">
                                            <tr>
                                                <td colspan="11">No hay Productos agregados</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-if="arrayTiendaArticulo1.length && datos.id_tienda2 == 0">
                                            <tr>
                                                <td colspan="11" class="text-danger">Debe seleccionar tienda de destino</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="row m-2 p-2">
                            <div class="col-md-8"><h3 class="mb-0">TIENDA DESTINO</h3></div>
                        </div>
                        
                        <div class="card-header text-center line p-0 m-0"  style="background-color: #3399FF"></div>
                        <div class="card-body">
                            <!-- <button type="button" class="btn btn-danger text-white" @click="listadoTraspaso()"><i class="fa fa-reply-all"></i>&nbsp;Volver</button>
                            <div class="card-header text-center"><h3 class="mb-0">REGISTRO DE GRUPO - PERMISOS</h3></div>       -->                      
                            <div class="form-group row">          
                                <div class="mb-2">
                                    <label for="exampleInputPassword1" class="form-label">Destino</label>
                                    <template v-if="arrayTienda2 == null">
                                        <select class="form-select form-select" v-model="datos.id_tienda2" disabled>
                                                <option value="0" disabled>Primero debe seleccionar Tienda de Origen</option>
                                        </select>
                                    </template>
                                    <template v-else>
                                        <select class="form-select form-select" v-model="datos.id_tienda2" @change="productosTienda2($event)">
                                            <option value="0" disabled>Seleccione Tienda</option>
                                            <option v-for="tienda in arrayTienda2" :key="tienda.id" :value="tienda.id" v-text="tienda.nombre"></option>
                                        </select>
                                    </template>
                                    <div class="col-md-8 mt-3">
                                        <button type="button" class="btn btn-info text-white position-relative" @click="listarArticuloProductoTienda2(buscarP2,criterioP2)" data-bs-toggle="modal" data-bs-target="#modalArticulo" :disabled="datos.id_tienda2>0 ? false : true"> <i class="fa fa-search"></i> Ver Productos</button>
                                    </div>    
                                    <div class="row" v-if="erroresGrupo.nombre">
                                        <div class="col-sm-10"><span class="text-danger p-1">{{erroresGrupo.nombre[0]}}</span></div>
                                    </div>
                                </div> 
                            </div>
                            <br>
                            
                            <div class="form-group row mb-3">
                                <div class="table-responsive" style="overflow-y: auto; max-height: 350px;">
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
                                                        <vue-numeric v-model="detalle.stock" type="number" class="form-control" @input="actualizarStock($event,detalle)"/>
                                                        <span style="color:red;" v-show="detalle.saldoStock - detalle.stock<0">Stock: {{detalle.saldoStock}}</span>
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
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-danger me-md-2 text-white" type="button" @click="limpiarTraspaso()">Cancelar</button>
                                <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==1" type="button" @click="guardarTraspaso()">Guardar</button>
                                <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==2" type="button" @click="modificarGrupo()">Modificar</button>
                            </div>
                        </div>
                    </template>


                    <template v-if="listado==2">
                        <div class="card-header row m-0">
                            <div class="col-md-2"><button type="button" class="btn btn-danger text-white" @click="limpiarTraspaso()">
                                <i class="fa fa-reply-all"></i>&nbsp;Volver</button>
                                <button type="button" @click="cargarPdf(datos.id, datos.foto, datos.empresa_nombre)" class="btn btn-info">
                                    <i class="icon-doc text-white"></i><span class="text-white">&nbsp;Reporte</span>
                                </button> 
                            </div>
                            <div class="col-md-8 text-center"><h3 class="mb-0">FORMULARIO DE TRASPASO DE PRODUCTO</h3></div>
                        </div> 
                        <div class="card-body">                    
                            <div class="form-group row">          
                                <div class="">
                                    <label for="exampleInputPassword1" class="form-label">Origen</label>
                                    <input type="text" class="form-control mb-3" v-model="datos.tienda1" disabled>
                                    <label for="exampleInputPassword1" class="form-label">Destino</label>
                                    <input type="text" class="form-control mb-3" v-model="datos.tienda2" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Glosa</label>
                                    <textarea class="form-control" v-model="datos.glosa" rows="2" disabled></textarea>
                                </div>   
                            </div>
                            <br>
                            <div class="form-group row">
                                <div class="table-responsive"  style="overflow-y: auto; max-height: 350px;">
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
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle) in arrayDetalle" :key="detalle.id">
                                                <td v-text="detalle.cod_producto"></td>
                                                <td v-text="detalle.cod_proveedor"></td>
                                                <td v-text="detalle.categoria"></td>
                                                <td v-text="detalle.nombre"></td>
                                                <td v-text="detalle.marca"></td>
                                                <td v-text="detalle.costo_compra"></td>
                                                <td v-text="detalle.costo_unitario"></td>
                                                <td v-text="detalle.costo_mayorista"></td>
                                                <td v-text="detalle.costo_preferencial"></td>
                                                <td v-text="detalle.cantidad"></td>
                                                                        
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
                        </div>
                    </template>




                </div>
                </div>
            </div>
        <!-- </div> -->

        <!--Modal Formulario Articulo-->
        <div class="modal fade" id="modalArticulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white" style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDAS DE PRODUCTOS {{datos.tienda2}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-select col-md-3" v-model="criterioP2">
                                        <option value="articulo.nombre">Nombre</option>
                                        <option value="articulo.cod_producto">Cod. Articulo</option>
                                        <option value="categoria.nombre">Categoria</option>
                                    </select>&nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscarP2" @keyup.enter="listarArticuloProductoTienda2(buscarP2,criterioP2)"  @keyup="BuscandoProductoTiendaDestino()" class="form-control" placeholder="Texto a buscar">&nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticuloProductoTienda2(buscarP2,criterioP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
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
                                </tr>
                            </thead>
                            <tbody v-if="arrayTiendaArticulo2.length">
                                <tr v-for="producto in arrayTiendaArticulo2" :key="producto.id">
                                    <td v-text="producto.cod_producto"></td>
                                    <td v-text="producto.cod_proveedor"></td>
                                    <td v-text="producto.categoria"></td>
                                    <td v-text="producto.nombre"></td>
                                    <td v-text="producto.marca"></td>
                                    <td v-text="producto.costo_compra"></td>
                                    <td v-text="producto.costo_unitario"></td>
                                    <td v-text="producto.costo_mayorista"></td>
                                    <td v-text="producto.costo_preferencial"></td>
                                    <td v-text="producto.stock"></td>                                 
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

    </main>
</template>

<script>
    import Swal from '../utils/appSwal';
    import moment from 'moment';
    export default {
        data(){
            return {
                datos : {
                    id : 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    hora : moment().format('HH:mm:ss'),
                    id_tienda1 : 0,
                    id_tienda2 : 0,
                    tienda1 : '',
                    tienda2 : '',
                    nombre : '',
                    glosa : '',
                    estado : '1',
                    detalle:[],
                },
                     
                arrayDetalle : [],

           
                arrayTraspaso : [],
                
                listado : 0,
                modal : 0,
                tipoAccion : 0,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 't1.nombre',
                criterioP : 'articulo.nombre',
                buscar : '',
                buscarP : '',
                erroresGrupo : {},

                arrayTienda1: [],
                arrayTienda2: null,
                arrayTiendaArticulo1: [],
                arrayTiendaArticulo2: [],
                cantidad: 0,
                id_detalle: 0,
                saldoStock: 0,
                buscarP2: '',
                criterioP2: 'articulo.nombre',
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
            selectTienda(){
                let me=this;
                var url='/tienda/selectTienda';
                axios.get(url).then(function(response){
                    me.arrayTienda1=response.data;
                }).catch(function(error){
                    console.log(error)
                });                
            },
            cargarPdf(id,foto,empresa_nombre) {
                axios.get('/traspaso/pdfTraspaso?id='
                 + id  + '&foto='+ foto
                 + '&empresa_nombre=' + empresa_nombre,{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            selectTienda2(tienda1){
                let me=this;
                var url='/tienda/selectTienda2?id_tienda1=' + tienda1;
                axios.get(url).then(function(response){
                    me.arrayTienda2=response.data;
                }).catch(function(error){
                    console.log(error)
                });                
            },
            listadoTraspaso(){
                this.listado = 1;
                this.tipoAccion = 1;
                this.arrayDetalle=[];
                this.datos = {
                    id : 0,
                    id_tienda1 : 0,
                    id_tienda2 : 0,
                    tienda1 : {},
                    tienda2 : '',
                    nombre : '',
                    glosa : '',
                    estado : '1',
                    fecha : moment().format('YYYY-MM-DD'),
                    hora : moment().format('HH:mm:ss'),
                    detalle:[],
                }
                this.criterioP = 'articulo.nombre',
                this.buscarP = '',
                this.buscarP2 = '',
                this.criterioP2 = 'articulo.nombre',
                this.buscar = ''
                this.cantidad = 0,
                this.id_detalle = 0,
                this.saldoStock = 0,
                this.arrayTienda2 = null,
                this.selectTienda()
            },
            listarArticuloProducto(event,buscar,criterio){
                    let me=this;
                    var url='/tienda/detalle/articulo_producto?id=' + me.datos.id_tienda1 + '&buscar=' + buscar + '&criterio=' + criterio;
                    axios.get(url).then(function(response){
                        me.arrayTiendaArticulo1= response.data;
                        me.arrayTiendaArticulo1.forEach(item => item.saldoStock = item.stock)
                    })
                    .catch(function(error){
                        console.log(error);
                    });
            },
            listarArticuloProductoBusquedaRapida(){
                    let me=this;
                    var url='/tienda/detalle/articulo_producto?id=' + me.datos.id_tienda1 + '&buscar=' + me.buscarP + '&criterio=' + me.criterioP;
                    axios.get(url).then(function(response){
                        me.arrayTiendaArticulo1= response.data;
                        me.arrayTiendaArticulo1.forEach(item => item.saldoStock = item.stock)
                    })
                    .catch(function(error){
                        console.log(error);
                    });
            },
            BuscandoProducto(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticuloProductoBusquedaRapida,350)
            },
            listarArticuloProductoTienda2BuscadaRapida(){
                let me=this;
                var url='/tienda/detalle/articulo_producto?id=' + me.datos.id_tienda2 + '&buscar=' + me.buscarP2 + '&criterio=' + me.criterioP2;
                axios.get(url).then(function(response){
                    me.arrayTiendaArticulo2= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            BuscandoProductoTiendaDestino(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticuloProductoTienda2BuscadaRapida,350)
            },
            listarArticuloProductoTienda2(buscar,criterio){
                    let me=this;
                    var url='/tienda/detalle/articulo_producto?id=' + me.datos.id_tienda2 + '&buscar=' + buscar + '&criterio=' + criterio;
                    axios.get(url).then(function(response){
                        me.arrayTiendaArticulo2= response.data;
                    })
                    .catch(function(error){
                        console.log(error);
                    });
            },
            inicializarDetalles($event){
                let me=this;
                me.datos.id_tienda2 = 0;
                me.arrayDetalle = [];
                me.arrayTiendaArticulo1 = [],
                me.arrayTiendaArticulo2= [],
                me.selectTienda2(me.datos.id_tienda1);
                me.criterioP = 'articulo.nombre',
                me.buscarP = '',
                me.buscarP2 = '',
                me.criterioP2 = 'articulo.nombre',
                me.buscar = ''
                me.listarArticuloProducto(event,me.buscar,me.criterio),
                me.cantidad = 0,
                me.id_detalle = 0,
                me.saldoStock = 0
            },
            productosTienda2($event) {
                let me=this;
                let tienda2 = {};
                tienda2 = me.arrayTienda1.find(f => f.id == me.datos.id_tienda2);
                me.datos.tienda2 = tienda2.nombre.toUpperCase() ? tienda2.nombre.toUpperCase() : '' ;

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
                if(parseInt(me.datos.id_tienda2)<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Seleccione tienda de destino!'
                    })
                } else{
                    if(me.encuentra(data['id_articulo'])){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Ya se encuentra agregado!'
                        })
                    } else {
                        if(data['stock']<=0){
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: data['nombre']+' No cuenta con stock!'
                            })
                        }else{
                            me.arrayDetalle.push({
                                id : data['id'],
                                id_articulo: data['id_articulo'],
                                cod_producto : data['cod_producto'],
                                cod_proveedor : data['cod_proveedor'],
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

                    }
                }
                
            },
            volverCargarProductos(){
                this.arrayTiendaArticulo2 = [];
                this.buscarP2 = '';
                this.criterioP2 = 'articulo.nombre';
            },
            async guardarTraspaso(){
                try {
                    if(this.arrayDetalle.find(seg => (seg.saldoStock - seg.stock < 0))){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'No hay stock para el producto!'
                        })
                    } else {
                        if(this.arrayDetalle.length<=0){
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: 'No Existe Productos agregados!'
                            })
                        } else {
                            let me = this;
                            me.datos.detalle= me.arrayDetalle;
                            const res = await axios.post('/traspaso/guardar',this.datos)
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Registro agregado...',
                                showConfirmButton: false,
                                timer: 1500
                            })
                            me.limpiarTraspaso();
                            me.listarTraspaso(1, '', 'id');
                            // me.listarGrupo(1,'', 'nombre');  
                        }  
                    }
                } catch (error) {
                }
            },
            actualizarStock(event,detalle){
                this.arrayTiendaArticulo1.forEach((det) => {
                    if (det.id == detalle.id) {
                        this.saldoStock = det.stock;
                    }
                });
                if(detalle.stock <= this.saldoStock ){
                    this.id_detalle = detalle.id;
                    this.cantidad = detalle.stock;
                } else {
                    this.cantidad = this.saldoStock;
                }
            },
            limpiarTraspaso(){
                this.listado = 0;
                this.tipoAccion = 1;
                this.arrayDetalle=[];
                this.arrayTiendaArticulo1= [],
                this.datos = {
                    id : 0,
                    id_tienda1 : 0,
                    id_tienda2 : 0,
                    tienda1 : '',
                    tienda2 : '',
                    nombre : '',
                    glosa : '',
                    estado : '1',
                    fecha : moment().format('YYYY-MM-DD'),
                    hora : moment().format('HH:mm:ss'),
                    detalle:[],
                }
                this.arrayTienda1 = [],
                this.arrayTienda2 = null,
                this.criterioP = 'articulo.nombre',
                this.buscarP = '',
                this.buscar = ''
                this.cantidad = 0,
                this.id_detalle = 0,
                this.saldoStock = 0
            },
            verDetalleTraspaso(){
                this.listado = 2;
                this.tipoAccion = 1;
                this.arrayDetalle=[];
                this.datos = {
                    id : 0,
                    id_tienda1 : 0,
                    id_tienda2 : 0,
                    tienda1 : {},
                    tienda2 : '',
                    nombre : '',
                    glosa : '',
                    estado : '1',
                    fecha : moment().format('YYYY-MM-DD'),
                    hora : moment().format('HH:mm:ss'),
                    detalle:[],
                }
                this.criterioP = 'articulo.nombre',
                this.buscarP = '',
                this.buscarP2 = '',
                this.criterioP2 = 'articulo.nombre',
                this.buscar = ''
                this.cantidad = 0,
                this.id_detalle = 0,
                this.saldoStock = 0,
                this.arrayTienda2 = null,
                this.selectTienda()
            },
            verTraspaso(data=[]){
                let me = this;
                console.log(data);

                me.listado = 2;
                me.datos.id=data['id'],
                me.datos.tienda1=data['tienda1'];
                me.datos.tienda2=data['tienda2'];
                me.datos.glosa=data['glosa'];
                // me.datos.fecha=data['fecha'];
                // me.datos.descuento=data['descuento'];
                // me.datos.tipoPago=data['tipoP'];
                // me.datos.formaPago=data['formaP'];

                var url='/traspaso/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarTraspaso(page, buscar, criterio){
                let me=this;
                var url='/traspaso?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayTraspaso=response.data.data;
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
            listarTraspasoBusquedaRapida(){
                let me=this;
                var url='/traspaso?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayTraspaso=response.data.data;
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
            BuscandoTraspaso(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarTraspasoBusquedaRapida,500)
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarTraspaso(page, buscar, criterio);
            },
        },
        mounted() {
            this.selectTienda();
        },
        created() {
            this.listarTraspaso(1, this.buscar, 'id');
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
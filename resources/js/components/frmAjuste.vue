<template>
    <main class="main warehouse-legacy warehouse-legacy--adjustments">
        <warehouse-adjustment-workspace
            :rows="arrayAjuste"
            :products="arrayArticulo"
            :details="arrayDetalle"
            :reasons="arrayMotivo"
            :data="datos"
            :pagination="pagination"
            :pages="pagesNumber"
            :product-pagination="productPagination"
            :product-pages="productPagesNumber"
            :search="buscar"
            :criterion="criterio"
            :product-search="buscarP"
            :product-criterion="criterioP"
            :view="listado == 1 ? 'form' : 'list'"
            :loading="loading"
            :table-loading="tableLoading"
            :product-loading="productLoading"
            :saving="saving"
            @update:search="buscar = $event"
            @update:criterion="criterio = $event"
            @update:product-search="buscarP = $event"
            @update:product-criterion="criterioP = $event"
            @update:reason="updateAdjustmentReason"
            @search="refreshAdjustments(1)"
            @page="refreshAdjustments($event)"
            @create="btnNuevoAjuste"
            @cancel="ocultarListado1"
            @open-products="openAdjustmentProducts"
            @search-products="refreshAdjustmentProducts(1)"
            @product-page="refreshAdjustmentProducts($event)"
            @select-product="selectAdjustmentProduct"
            @remove-detail="eliminarDetalle"
            @save="guardarAjuste"
        />
        <div v-if="false">
        <warehouse-module-intro
            title="Ajustes de inventario"
            subtitle="Registra entradas o salidas justificadas y revisa el historial de movimientos aplicados al stock."
            primary-label="Ajustes"
            :primary-value="pagination.total || arrayAjuste.length"
            primary-hint="Movimientos registrados"
            secondary-label="Detalle actual"
            :secondary-value="arrayDetalle.length"
            secondary-hint="Productos por procesar"
            tertiary-label="Control"
            tertiary-value="Trazable"
            tertiary-hint="Movimiento y motivo asociados"
        />
        <!-- <div class="container"> -->
        <div class="row">
            <div class="col">
            &nbsp;
                <div class="card">
                    <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">AJUSTE</h3>
                    </div>
                    <!-- prueba card -->
                    <div class="row mt-2" style='width:90%;margin-left: 1.3%'>
                        <div class="col-sm-6 col-lg-3">
                            <div class="card mb-4" style="--cui-card-cap-bg: #F6C800">
                                <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                <strong> AJUSTE </strong>
                                </div>
                                <div class="card-body row text-center">
                                    <div class="col">
                                        <div class="avatar avatar-md"><img class="avatar-img" src="img/sit_norte/cliente.png"></div>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="col">
                                        <div class="fs-5 fw-semibold">{{categoria_registro}}</div>
                                        <div class="text-uppercase text-medium-emphasis small ">Registros</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- prueba fin card -->
                    <template v-if="listado==0">
                        <div class="card-header">
                            <button type="button" @click="btnNuevoAjuste()" class="btn btn-info text-white" style='margin-left: 1%'>
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8">
                                &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                    <div class="col-md-4">
                                        <select class="form-select col-md-3" v-model="criterio">
                                            <option value="articulo.nombre_comercial">Producto</option>
                                            <option value="categoria.nombre">Categoria</option>
                                            <option value="motivo_ajuste.nombre">Motivo Ajuste</option>
                                        </select>
                                    </div>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup="BuscandoAjuste()" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarAjuste(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Light table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                                <thead style="background-color: #46546C">
                                    <tr>
                                        <th scope="col" class="text-white">Lote</th>
                                        <th scope="col" class="text-white">Categoria</th>
                                        <th scope="col" class="text-white">Producto</th>
                                        <th scope="col" class="text-white">Fecha Vencimiento</th>
                                        <th scope="col" class="text-white">Motivo Ajuste</th>
                                        <th scope="col" class="text-white">Cantidad</th>
                                        <th scope="col" class="text-white">Stock Anterior</th>
                                        <th scope="col" class="text-white">Stock Actual</th>
                                        <!-- <th scope="col" class="text-white">Costo Compra</th>
                                        <th scope="col" class="text-white">Precio Venta</th> -->
                                        <!-- <th scope="col" class="text-white">P. Mayorista</th>
                                        <th scope="col" class="text-white">P. Preferencial</th>
                                        <th scope="col" class="text-white">Precio Venta</th> -->
                                    </tr>
                                </thead>
                                <tbody v-if="arrayAjuste.length">
                                    <tr v-for="ajuste in arrayAjuste" :key="ajuste.id">
                                        <td v-text="ajuste.lote"></td>
                                        <td v-text="ajuste.categoria"></td>
                                        <td v-text="ajuste.producto"></td>
                                        <td v-text="ajuste.fecha_vecimiento"></td>
                                        <td v-text="ajuste.motivo_ajuste"></td>
                                        <td v-text="ajuste.id_motivo_ajuste!=4 ? (ajuste.id_motivo_ajuste!=5 ? ajuste.stock : '-') : '-'"></td>
                                        <td v-text="ajuste.id_motivo_ajuste!=4 ? (ajuste.id_motivo_ajuste!=5 ? ajuste.stock_anterior : '-') : '-'"></td>
                                        <td v-text="ajuste.id_motivo_ajuste!=4 ? (ajuste.id_motivo_ajuste!=5 ? ajuste.stock_actual : '-') : '-'"></td>
                                        <!-- <td v-text="ajuste.id_motivo_ajuste==4 || ajuste.id_motivo_ajuste==6 ? ajuste.costo_compra :'-'"></td>
                                        <td v-text="ajuste.id_motivo_ajuste==5 ? ajuste.costo_unitario:'-'"></td>   -->
                                        <!-- <td v-text="ajuste.id_motivo_ajuste==5 ? ajuste.costo_mayorista:'-'"></td>   
                                        <td v-text="ajuste.id_motivo_ajuste==5 ? ajuste.costo_preferencial:'-'"></td>   
                                        <td v-text="ajuste.id_motivo_ajuste==7 || ajuste.id_motivo_ajuste==8 || ajuste.id_motivo_ajuste==9 ? ajuste.costo_venta:'-'"></td>                                     -->
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="12">No hay Ajustes agregados</td>
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
                        <div class="card-body">
                            <div class="card-header text-center">
                                <div class="row">
                                    <div class="col-md-1">
                                        <button type="button" @click="ocultarListado1()" class="btn btn-danger text-white " >
                                            <i class="fa fa-reply-all"></i>&nbsp;Volver
                                        </button>   
                                    </div>
                                    <div class="col-md-9 text-center">
                                        <h3 class="mb-0">DATOS AJUSTES</h3>  
                                    </div>
                                </div>
                            </div>
                            <div class="card-header text-center line p-0 m-0"  style="background-color: #3399FF"></div>&nbsp;
                            <form class="row">
                                <div class="col-md-6 mb-2">
                                <label for="exampleInputPassword1" class="form-label">Motivo Ajuste</label>
                                    <select class="form-select" v-model="datos.id_motivo_ajuste"  @change="seleccionarMotivo($event)">
                                        <option value="0" disabled>Seleccione el Motivo</option>
                                        <option v-for="motivo in arrayMotivo" :key="motivo.id" :value="motivo.id" v-text="motivo.nombre"></option>
                                    </select>  
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Productos<span style="color:red;" >(*Seleccione)</span></label>
                                    <div class="input-group mb-6">
                                        <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" @click="buscarP='',listarArticulo(1,buscarP,criterioP)" data-bs-target="#modalArticulo" :disabled="datos.id_motivo_ajuste == 0"><i class="fa fa-search"></i> Agregar</button>
                                    </div>
                                </div>
                                <!-- <template v-if="datos.id_motivo_ajuste == 1 || datos.id_motivo_ajuste == 2 || datos.id_motivo_ajuste == 3">
                                    <div class="col-md-6 mb-2">
                                        <label for="exampleInputPassword1" class="form-label">Stock</label>
                                        <input type="number" class="form-control" v-model="datos.stock">
                                    </div>    
                                </template>  --> 
                                <!-- <template v-if="datos.id_motivo_ajuste == 4">
                                    <div class="col-md-6" :class="errores.costo_compra ? 'mb-1' : 'mb-2'">
                                        <label for="exampleInputPassword1" class="form-label">Costo Compra</label>
                                        <input type="text" class="form-control" v-model="datos.costo_compra">
                                        <div class="row mb-2" v-if="errores.costo_compra">
                                            <div class="col-sm-10"><span class="text-danger">{{errores.costo_compra[0]}}</span></div>
                                        </div>  
                                    </div>                       
                                </template> -->
                                <!-- <template v-if="datos.id_motivo_ajuste == 5">
                                    <div class="col-md-6 mb-2">
                                        <label for="exampleInputPassword1" class="form-label">Precio Unitario</label>
                                        <input type="text" class="form-control" v-model="datos.costo_unitario">
                                        <div class="row mb-2" v-if="errores.costo_unitario">
                                            <div class="col-sm-10"><span class="text-danger">{{errores.costo_unitario[0]}}</span></div>
                                        </div>  
                                    </div>
                                </template> -->
                                <!-- <template v-if="datos.id_motivo_ajuste == 5">
                                    <div class="col-md-6 mb-2">
                                        <label for="exampleInputPassword1" class="form-label">Precio Mayorista</label>
                                        <input type="text" class="form-control" v-model="datos.costo_mayorista">
                                        <div class="row mb-2" v-if="errores.costo_mayorista">
                                            <div class="col-sm-10"><span class="text-danger">{{errores.costo_mayorista[0]}}</span></div>
                                        </div>  
                                    </div>
                                </template> -->
                                <!-- <template v-if="datos.id_motivo_ajuste == 5">
                                    <div class="col-md-6 mb-2">
                                        <label for="exampleInputPassword1" class="form-label">Precio Preferencial</label>
                                        <input type="text" class="form-control" v-model="datos.costo_preferencial">
                                        <div class="row mb-2" v-if="errores.costo_preferencial">
                                            <div class="col-sm-10"><span class="text-danger">{{errores.costo_preferencial[0]}}</span></div>
                                        </div>  
                                    </div>
                                </template> -->
                                <!-- <div class="col-md-12">
                                    <label for="exampleInputPassword1" class="form-label">Observacion</label>
                                    <textarea class="form-control" v-model="datos.observacion" rows="2"></textarea>
                                </div>  -->
                            </form>
                                <br>

                                    <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead style="background-color: #46546C">
                                            <tr>                      
                                                <th scope="col" class="text-white">Opción</th>
                                                <th scope="col" class="text-white">Categoria</th>
                                                <th scope="col" class="text-white">Nombre</th>
                                                <!-- <th scope="col" class="text-white">Marca</th> -->
                                                <th v-if="datos.id_motivo_ajuste == 1 || datos.id_motivo_ajuste == 2 || datos.id_motivo_ajuste == 3" scope="col" class="text-white">Stock Actual</th>
                                                <th v-if="datos.id_motivo_ajuste == 1 || datos.id_motivo_ajuste == 2 || datos.id_motivo_ajuste == 3" scope="col" class="text-white">Stock</th>
                                                <th v-if="datos.id_motivo_ajuste == 4" scope="col" class="text-white">Precio de Compra</th>
                                                <th v-if="datos.id_motivo_ajuste == 5" scope="col" class="text-white">Precio Venta</th>
                                                <!-- <th v-if="datos.id_motivo_ajuste == 5" scope="col" class="text-white">Precio Mayorista</th>
                                                <th v-if="datos.id_motivo_ajuste == 5" scope="col" class="text-white">Precio Preferencial</th> -->
                                                <th scope="col" class="text-white">Observación</th>
                                                <th scope="col" class="text-white">Fecha Vencimiento</th>
                                                <th scope="col" class="text-white">Lote</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                                </td>
                                                <td v-text="detalle.categoria"></td>
                                                <td v-text="detalle.articulo"></td>
                                                <!-- <td v-text="detalle.marca"></td> -->
                                                <td v-if="datos.id_motivo_ajuste == 1 || datos.id_motivo_ajuste == 2 || datos.id_motivo_ajuste == 3">
                                                    <vue-numeric v-model="detalle.saldoStock" type="number" value="3" class="form-control" :disabled="true"></vue-numeric>
                                                </td>
                                                <td v-if="datos.id_motivo_ajuste == 1 || datos.id_motivo_ajuste == 2 || datos.id_motivo_ajuste == 3">
                                                    <vue-numeric v-model="detalle.stock" type="number" value="3" class="form-control"></vue-numeric>
                                                </td>
                                                <td v-if="datos.id_motivo_ajuste == 4">
                                                    <vue-numeric v-model="detalle.costo_compra" type="number" value="3" class="form-control"></vue-numeric>
                                                </td>
                                                <td v-if="datos.id_motivo_ajuste == 5">
                                                    <vue-numeric v-model="detalle.costo_unitario" type="number" value="3" class="form-control"></vue-numeric>
                                                </td>  
                                                <!-- <td v-if="datos.id_motivo_ajuste == 5">
                                                    <vue-numeric v-model="detalle.costo_mayorista" type="number" value="3" class="form-control"></vue-numeric>
                                                </td>  
                                                <td v-if="datos.id_motivo_ajuste == 5">
                                                    <vue-numeric v-model="detalle.costo_preferencial" type="number" value="3" class="form-control"></vue-numeric>
                                                </td>   -->
                                                <td>
                                                    <input v-model="detalle.observacion" type="text"  id="Preferencial" class="form-control">
                                                </td>       
                                                <td>
                                                    <input v-model="detalle.fecha_vencimiento" type="date"  id="Preferencial" class="form-control" :disabled="true">
                                                </td>     
                                                <td>
                                                    <input v-model="detalle.lote" type="text"  id="Preferencial" class="form-control" :disabled="true">
                                                </td>                                                               
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="8">No hay Productos agregados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                            <div class="header-divider"></div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-danger me-md-2 text-white" type="button" @click="ocultarListado1()">Cancelar</button>
                                <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==1"  type="button" @click="guardarAjuste()">Guardar Ajuste</button>
                                <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==2"  type="button" @click="modificarArticulo()">Modificar Articulo</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <!-- </div> -->

        <!--Modal Formulario Producto-->
        <div class="modal fade" id="modalArticulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white" style="height:45px;">
                        <h5 class="modal-title ">Busqueda de Productos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-select col-md-3" v-model="criterioP">
                                        <option value="articulo.nombre_comercial">Producto</option>   
                                        <option value="unidad_medida.nombre">Presentación</option>                                    
                                        <option value="proveedor.nombre">Laboratorio</option>                                    
                                        <option value="categoria.nombre">Categoria</option>
                                    </select>&nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(1,buscarP, criterioP)" @keyup="BuscandoProducto()" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticulo(1,buscarP, criterioP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>
                                    <th scope="col" class="text-white">Lote</th>
                                    <th scope="col" class="text-white">Categoria</th>                    
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">presentación</th>
                                    <th scope="col" class="text-white">Laboratorio</th>
                                    <th scope="col" class="text-white">Precio Compra</th>
                                    <th scope="col" class="text-white">Precio Venta</th>
                                    <th scope="col" class="text-white">Stock</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayArticulo.length">
                                <tr v-for="(tienda_articulo, index) in arrayArticulo" :key="index">
                                    <td  v-text="tienda_articulo.lote"></td>
                                    <td  v-text="tienda_articulo.categoria"></td>
                                    <td  v-text="tienda_articulo.articulo"></td>
                                    <td  v-text="tienda_articulo.presentacion"></td>
                                    <td  v-text="tienda_articulo.laboratorio"></td>
                                    <td  v-text="tienda_articulo.costo_compra"></td>
                                    <td  v-text="tienda_articulo.costo_unitario"></td>
                                    <td  v-text="tienda_articulo.cantidad"></td>
                                    <td >
                                        <button type="button"  data-dismiss="modal" @click="seleccionarArticulo(tienda_articulo,index)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button> 
                                    </td>                                 
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
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscarP, criterioP)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page==isActived ? 'active' :'']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscarP, criterioP)" v-text="page">1</a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscarP, criterioP)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin modal Formulario Producto-->
        </div>
    </main>
</template>

<script>
    import Swal, { dangerConfirm } from '../utils/appSwal';
    import moment from 'moment';
    export default {
        data(){
            return {
                datos : {
                    id : 0,
                    stock : 0,
                    fecha_vencimiento : '',
                    lote : '',
                    costo_compra : 0,
                    costo_venta : 0,
                    costo_unitario : 0,
                    costo_mayorista : 0,
                    costo_preferencial : 0,
                    observacion : '',
                    id_articulo : 0,
                    id_motivo_ajuste : 0,
                    producto : '',
                    saldoStock : 0,
                    //fecha_vencimiento :  moment().format('YYYY-MM-DD'),
                    
                },
                selectArticulo:{},
                categoria_registro :0,
                personal_registro :0,                                
                arrayAjuste : [],
                arrayArticulo: [],
                arrayDetalle : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                categoria_registro :0, 
                errorArticulo : 0,
                modalCuenta:0,
                errorMostrarMsjArticulo : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                productPagination : {
                    'total' : 0,
                    'current_page' : 1,
                    'per_page' : 0,
                    'last_page' : 1,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'articulo.nombre_comercial',
                buscar : '',
                buscarP : '',
                arrayMotivo : [],
                listado: 0,
                setTimeoutBuscador: '',
                errores:{},
                criterioP : 'articulo.nombre_comercial',
                loading: true,
                tableLoading: false,
                productLoading: false,
                saving: false,
            }
        },
        computed : {
            productPagesNumber: function(){
                if(!this.productPagination.to){
                    return [];
                }
                var from = Math.max(1, this.productPagination.current_page - this.offset);
                var to = Math.min(this.productPagination.last_page, from + (this.offset * 2));
                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
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
            updateAdjustmentReason(value){
                this.datos.id_motivo_ajuste = value;
                this.seleccionarMotivo();
            },
            async refreshAdjustments(page){
                this.tableLoading = true;
                try {
                    await this.listarAjuste(page, this.buscar, this.criterio);
                } finally {
                    this.tableLoading = false;
                }
            },
            openAdjustmentProducts(){
                this.buscarP = '';
                this.refreshAdjustmentProducts(1);
            },
            async refreshAdjustmentProducts(page){
                this.productLoading = true;
                try {
                    await this.listarArticulo(page, this.buscarP, this.criterioP);
                } finally {
                    this.productLoading = false;
                }
            },
            selectAdjustmentProduct(row){
                this.seleccionarArticulo(row, this.arrayArticulo.indexOf(row));
            },
            listarAjuste(page, buscar, criterio){
                let me=this;
                var url='/ajuste?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                return axios.get(url).then(function(response){
                    me.arrayAjuste=response.data.data;
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
            listarAjusteBusquedaRapida(){
                let me=this;
                var url='/ajuste?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayAjuste=response.data.data;
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
            BuscandoAjuste(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarAjusteBusquedaRapida,350)
            },

            listarArticulo(page,buscarP,criterioP){
                let me = this;
                var url='/tienda/listarSinPaginateAjuste?page=' + page + '&buscar=' + buscarP+ '&criterio=' + criterioP;
                return axios.get(url).then(function(response){
                    me.arrayArticulo= response.data.data;
                      me.productPagination={total:response.data.total,
                        current_page:response.data.current_page,
                        per_page: response.data.per_page,
                        last_page: response.data.last_page,
                        from: response.data.from,
                        to: response.data.to
                    }
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarArticuloBusquedaRapida(){
                let me = this;
                var url='/tienda/listarSinPaginateAjuste?page=' + 1 + '&buscar=' + me.buscarP+ '&criterio=' + me.criterioP;
                axios.get(url).then(function(response){
                    me.arrayArticulo=response.data.data;
                    me.productPagination={total:response.data.total,
                        current_page:response.data.current_page,
                        per_page: response.data.per_page,
                        last_page: response.data.last_page,
                        from: response.data.from,
                        to: response.data.to
                    }
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


            btnNuevoAjuste(){
                this.selectMotivo();
                let me = this;
                me.listado=1;
                this.tipoAccion = 1;
                me.datos = {
                    id : 0,
                    stock : 0,
                    costo_compra : 0,
                    costo_venta : 0,
                    observacion : '',
                    id_articulo : 0,
                    id_motivo_ajuste : 0,
                    producto : '',
                    //fecha_vencimiento :  moment().format('YYYY-MM-DD'),
                };
                me.selectArticulo={};
            },


            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].id_lote==id){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            
            seleccionarArticulo(data=[],index){
                let me = this;
                if(me.encuentra(data['id_lote'])){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Este producto ya se encuentra agregado!'
                    })
                }
                else{
                    me.arrayDetalle.push({
                        id_articulo:data['id_articulo'],
                        categoria : data['categoria'],
                        articulo : data['articulo'],
                        marca : data['marca'],
                        costo_compra:data['costo_compra'],
                        costo_venta: 0,
                        costo_unitario: data['costo_unitario'],
                        costo_mayorista: data['costo_mayorista'],
                        costo_preferencial: data['costo_preferencial'],
                        observacion: '',
                        stock: 0,
                        saldoStock:data['cantidad'],
                        id_lote:data['id_lote'],
                        fecha_vencimiento : data['fecha_vecimiento'],
                        lote : data['lote'],
                        

                    });
                    me.arrayArticulo.splice(index,1);
                    // Swal.fire({
                    //     position: 'top-end',
                    //     icon: 'success',
                    //     title: 'Producto agregado...',
                    //     showConfirmButton: false,
                    //     timer: 500
                    // });
                }
            },









            registrosAjuste(){
                let me=this;
                var url='/ajuste/cantidad';
                axios.get(url).then(function(response){
                    me.categoria_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },

            guardarAjuste(){
                if(this.validarAjuste()){
                    return;
                }
                let me = this;
                    if(me.datos.id_motivo_ajuste != 3){
                        if(me.datos.id_motivo_ajuste == 4|| me.datos.id_motivo_ajuste == 5){
                            me.ajuste();
                        }else{
                            if(me.arrayDetalle.find(seg => (seg.stock <= 0))){
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error...',
                                    text: 'Debe agregar Stock para producto!'
                                })
                            } else {
                                if(me.datos.id_motivo_ajuste == 1 && me.arrayDetalle.find(seg => (seg.saldoStock > 0))){
                                dangerConfirm.fire({
                                    title: 'Esta seguro de restablecer el stock?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#00C055',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Si, actualizar!',
                                    cancelButtonText: 'No, cancelar!',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            me.ajuste();
                                        }
                                    })
                                }else{
                                    me.ajuste();
                                }
                            }
                        }
                    }else{
                        if(me.arrayDetalle.find(seg => (seg.saldoStock - seg.stock >= 0))) {
                            me.ajuste();
                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: 'No hay stock para el egreso!'
                            })
                        }
                    }


            },



            async ajuste(){
                this.saving = true;
                try {
                    let me = this;
                    const res = await axios.post('/ajuste/guardar',{
                        'detalle': me.arrayDetalle,
                        'id_motivo_ajuste': me.datos.id_motivo_ajuste,
                    });
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.listado =0;
                    me.listarAjuste(1,'','articulo.nombre');
                    me.datos = {
                        id : 0,
                        stock : 0,
                        costo_compra : '',
                        costo_venta : '',
                        costo_unitario : '',
                        costo_mayorista : '',
                        costo_preferencial : '',
                        observacion : '',
                        id_articulo : 0,
                        id_motivo_ajuste : 0,
                        saldoStock : 0,
                    },
                    me.selectArticulo={};
                    me.arrayAjuste = [];
                    me.arrayDetalle = [];
                    me.arrayMotivo = [];
                    me.errores={};
                    me.registrosAjuste();
                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                } finally {
                    this.saving = false;
                }
            },
            seleccionarMotivo(){
                    this.errores={};
                    this.datos.stock = 0;
                    this.datos.costo_compra = this.selectArticulo.costo_compra;
                    this.datos.costo_unitario = this.selectArticulo.costo_unitario;
                    this.datos.costo_mayorista = this.selectArticulo.costo_mayorista;
                    this.datos.costo_preferencial = this.selectArticulo.costo_preferencial;
            },
            selectMotivo(){
                let me=this;
                var url='/motivo/selectMotivo';
                axios.get(url).then(function(response){
                    me.arrayMotivo=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarAjuste(page, buscar, criterio);
               // me.listarArticulo(page, buscar, criterio);
            },
            ocultarListado1(){
                this.listado=0;
                this.selectArticulo={}
                this.errores={};
            },
            ocultarListado2(){
                this.listado=1;
            },
            abrirProducto(){
                this.modalCuenta=1;
            },
            cerrarProducto(){
                this.modalCuenta=0;
            },
                
            validarAjuste(){
                let me = this;
                if(me.arrayDetalle.length <= 0){
                    Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Debe Seleccionar algun Producto!'
                        })
                    return true;
                }
                if(me.datos.id_motivo_ajuste == 0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Seleccionar un Motivo de Ajuste!'
                    })
                    return true;
                }
                return false;
            }           
        },
        async mounted() {
            try {
                await this.listarAjuste(1, this.buscar, this.criterio);
            } finally {
                this.loading = false;
            }
            this.registrosAjuste();

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

<template>
    <main class="main warehouse-legacy warehouse-legacy--lots">
        <warehouse-lot-workspace
            :products="arrayArticulo"
            :details="arrayDetalle"
            :pagination="pagination"
            :pages="pagesNumber"
            :search="buscarP"
            :criterion="criterioP"
            :loading="loading"
            :product-loading="productLoading"
            :saving="saving"
            @update:search="buscarP = $event"
            @update:criterion="criterioP = $event"
            @open-products="openLotProducts"
            @search="refreshLotProducts(1)"
            @page="refreshLotProducts($event)"
            @select-product="seleccionarTiendaArticulo"
            @remove-detail="eliminarDetalle"
            @clear="cancelarCompra"
            @save="guardarLote"
        />
        <div v-if="false">
        <warehouse-module-intro
            title="Lotes"
            subtitle="Carga existencias por lote y fecha de vencimiento para mantener la trazabilidad de cada producto."
            primary-label="Productos seleccionados"
            :primary-value="arrayDetalle.length"
            primary-hint="Detalle listo para registrar"
            secondary-label="Resultados"
            :secondary-value="arrayArticulo.length"
            secondary-hint="Productos disponibles"
            tertiary-label="Control"
            tertiary-value="Vencimientos"
            tertiary-hint="Seguimiento por lote"
        />
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                   <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">LOTES</h3></div>
                    <template v-if="listado==0">
                        <div class="card-body">
                            <!-- <button type="button" class="btn btn-danger text-white" @click="volverCompraListado()"><i class="fa fa-reply-all"></i>&nbsp;Volver</button>
                            <div class="card-header text-center" style="background-color: #CEECF5"><h3 class="mb-0">REGISTRO DE COMPRA - ALMACEN GENERAL</h3></div>                             -->
                            <div class="form-group row" style='margin-left: 1%'>   
                            <form class="row">
                                <!-- <div class="col-md-6">
                                <label for="exampleInputPassword1" class="form-label">Proveedor<span style="color:red;" > *</span></label>
                                    <select class="form-select" v-model="datos.id_proveedor">
                                        <option value="0" disabled>Seleccione el Proveedor</option>
                                        <option v-for="proveedor in arrayProveedor" :key="proveedor.id" :value="proveedor.id" v-text="proveedor.nombre"></option>
                                    </select>  
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
                                </div> -->
                                <!-- <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Descuento</label>
                                    <input type="number" class="form-controls" v-model="datos.descuento">  
                                </div> -->

                                <!-- <div class="col-md-12">
                                    <label for="exampleInputPassword1" class="form-label">Descripcion</label>
                                    <textarea class="form-control" v-model="datos.descripcion" rows="2"></textarea>
                                </div>    -->
                                &nbsp;
                                <div class="col-md-12">
                                    <label>Productos Lote</label>
                                    <button type="button"  class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalArticulo" @click="listarArticulo(1,buscarP,criterioP)"><i class="fa fa-search"></i> Agregar Productos</button>
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
                                            <th scope="col" class="text-white">Nombre</th>
                                            <!-- <th scope="col" class="text-white">Tienda</th>
                                            <th scope="col" class="text-white">Precio</th> -->
                                            <th scope="col" class="text-white">Cantidad</th>
                                            <th scope="col" class="text-white">Fecha Vecimiento</th>
                                            <th scope="col" class="text-white">Lote</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                            </td>
                                            <td v-text="detalle.articulo"></td>
                                            <!-- <td v-text="detalle.tienda"></td>
                                            <td><input v-model="detalle.costo_compra" type="number" value="3" class="form-control"></td> -->
                                            <td><input v-model="detalle.cantidad" type="number" value="3" class="form-control" min="0"></td>
                                            <td><input v-model="detalle.fecha_vecimiento" type="date" value="3" class="form-control"></td>                                                                             
                                            <td><input v-model="detalle.lote" type="text" value="3" class="form-control"></td>                                                                             
                                        </tr>
                                        <!-- <tr style="background-color: #CEECF5">
                                            <td colspan="6" align="right"> <strong>Total:</strong> </td>
                                            <td>{{datos.total = calcularTotal.toFixed(2)}} bs</td> 
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="6" align="right"> <strong>Descuento:</strong> </td>
                                            <td><input v-model="datos.descuento" type="number" value="0" class="form-control"></td>
                                        </tr>
                                         <tr style="background-color: #CEECF5">
                                            <td colspan="6" align="right"> <strong>Sub Total:</strong> </td>
                                            <td>{{datos.sub_total = datos.total- datos.descuento}} bs</td>
                                        </tr> -->

                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="7">No hay Permisos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end" style='width:96%;margin-left: 2.2%'>
                                <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverCompraListado()">Cancelar</button>
                                <button class="btn btn-info btn-lg text-white" type="button" @click="guardarLote()">Guardar</button>
                            </div>
                        </div>
                    </template>
                    
                    <template v-if="listado==2">
                        <div class="card-body">
                            <button type="button" class="btn btn-danger text-white" @click="volverCompraListado()"><i class="fa fa-reply-all"></i>&nbsp;Volver</button>
                            <div class="card-header text-center"><h3 class="mb-0">REGISTRO DE COMPRA</h3></div>                            
                            <div class="form-group row">          
                                <form class="row g-3">
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Proveedor</label>
                                    <input type="text" class="form-control"  v-model="datos.proveedor" disabled>  
                                </div> 
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha" disabled>  
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Forma Pago</label>
                                    <input type="text" class="form-control"  v-model="datos.formaPago" disabled>  
                                </div> 
                                <!-- <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Descuento</label>
                                    <input type="number" class="form-control" v-model="datos.descuento">  
                                </div> -->

                                <div class="col-md-12">
                                    <label for="exampleInputPassword1" class="form-label">Descripcion</label>
                                    <textarea class="form-control" v-model="datos.descripcion" rows="2" disabled></textarea>
                                </div>   
                            
                            </form>    
                            </div>

                            <br>
                            <div class="form-group row">
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead style="background-color: #46546C">
                                        <tr>                      
                                            <th scope="col" class="text-white">Nombre</th>
                                            <th scope="col" class="text-white">Tienda</th>
                                            <th scope="col" class="text-white">Precio</th>
                                            <th scope="col" class="text-white">Cantidad</th>
                                            <th scope="col" class="text-white">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.articulo"></td>
                                            <td v-text="detalle.tienda"></td>
                                            <td v-text="detalle.costo_compra"></td>
                                            <td v-text="detalle.cantidad"></td>
                                            <td v-text="detalle.sub_total"></td>                                                                             
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="4" align="right"> <strong>Total:</strong> </td>
                                            <td>{{datos.total = calcularTotal.toFixed(2)}} bs</td> 
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="4" align="right"> <strong>Descuento:</strong> </td>
                                            <td v-text="datos.descuento"></td>
                                        </tr>
                                         <tr style="background-color: #CEECF5">
                                            <td colspan="4" align="right"> <strong>Sub Total:</strong> </td>
                                            <td>{{datos.sub_total = (datos.total- datos.descuento).toFixed(2)}} bs</td>
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
                        </div>
                    </template>
                </div>
                </div>
            </div>
        <!-- </div>   -->

        <!--Modal Formulario Articulo-->
        <div class="modal fade" id="modalArticulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white" style="height:45px;" >
                      <h5 class="modal-title ">BUSQUEDA DE PRODUCTOS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

<!-- df -->
                    <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-select col-md-3" v-model="criterioP">
                                        <option value="articulo.nombre_comercial">Producto</option>   
                                        <option value="unidad_medida.nombre">Presentación</option>                                    
                                        <option value="proveedor.nombre">Laboratorio</option>                                    
                                        <option value="categoria.nombre">Categoria</option>
                                    </select>&nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(1,buscarP, criterioP)" @keyup="BuscandoArticulo()" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticulo(1,buscarP, criterioP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
<!-- KCda                         -->
                        <!-- <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(buscarP,criterioPbuscarP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticulo(buscarP,criterioPbuscarP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp; -->
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>                      
                                    <th scope="col" class="text-white">Categoria</th>
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">Presentación</th>
                                    <th scope="col" class="text-white">Laboratorio</th>
                                    <th scope="col" class="text-white">Precio</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tienda_articulo in arrayArticulo" :key="tienda_articulo.id">
                                    <td v-text="tienda_articulo.categoria"></td>
                                    <td v-text="tienda_articulo.articulo"></td>
                                    <td v-text="tienda_articulo.presentacion"></td>
                                    <td v-text="tienda_articulo.laboratorio"></td>
                                    <td v-text="tienda_articulo.costo_unitario"></td>

                                    <td>
                                        <button type="button" @click="seleccionarTiendaArticulo(tienda_articulo)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>   
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
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscarP, criterioP)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page==isActived ? 'active' :'']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page">1</a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscarP, criterioP)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
          </div>
       
            </div>
        <!--Fin modal Formulario Articulo-->

        <!--Modal Formulario Pago al Crédito-->
        <div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white">
                        <h4 class="modal-title ">Pago al Credito</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Fecha</label>
                                <div><input type="date" class="form-control" v-model="datosPago.fecha" disabled></div>
                            </div>
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-4 col-form-label">Monto Total</label>
                                <div><input type="number" class="form-control" v-model="datos.total" disabled></div>
                            </div>
                            <!-- <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Anticipo</label>
                                <div><input type="number" class="form-control" v-model="datosPago.anticipo" v-on:keyup.enter="calcularSaldoAnticipado()"></div>
                            </div> -->
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Saldo</label>
                                <div><input type="number" class="form-control" v-model="datosPago.saldo" disabled></div>
                            </div>
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
                                <textarea class="form-control" id="message-text" v-model="datosPago.descripcion"></textarea>
                                <!-- <div class="col-sm-10"><input type="text" class="form-control" v-model="datosCategoria.descripcion"></div> -->
                            </div>
                            <div v-show="errorPago" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjPago" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-info text-white" data-bs-dismiss="modal">Guardar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Pago al credito-->
        </div>
    </main>
</template>

<script>

    import Swal from 'sweetalert2';
    import moment from 'moment';
    export default {
        created() {
            this.datos.id_forma_pago = 2;
        },
        data(){
            return {
                datos : {
                    id : 0,
                    fecha_vecimiento : '',
                    
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    descripcion : '',
                    id_proveedor : 0,
                    id_forma_pago : 2,
                    id_tipo_pago : 1,
                    proveedor : '',
                    tipoPago : '',
                    formaPago : '',
                },
                datosPago:{
                    id: 0,
                    fecha_inicio : moment().format('YYYY-MM-DD'),
                    fecha_final : moment().add(1,'month').format('YYYY-MM-DD'),
                    saldo : 0,
                    anticipo : 0,
                    descripcion: '',


                },    
                          
                arrayCompra : [],
                arrayPago: [],
                arrayDetalle : [],
                arrayForma2: [],
                arrayForma: [],
                arrayArticulo : [],
                arrayProveedor: [],
                arrayFormaPago: [],
                listado : 0,
                tipoAccion : 0,
                errorCompra : 0,
                errorPago : 0,
                errorMostrarMsjPago : [],
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
                criterio : 'nombre',
                buscar : '',
                buscarP : '',
                criterioP : 'articulo.nombre_comercial',
                setTimeoutBuscador: '',
                loading: true,
                productLoading: false,
                saving: false,
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
                    resultado = resultado + (this.arrayDetalle[i].costo_compra*this.arrayDetalle[i].cantidad);
                }
                return resultado;
            }
        },
        methods : {
            openLotProducts(){
                this.buscarP = '';
                this.refreshLotProducts(1);
            },
            async refreshLotProducts(page){
                this.productLoading = true;
                try {
                    await this.listarArticulo(page, this.buscarP, this.criterioP);
                } finally {
                    this.productLoading = false;
                }
            },
            // listarCompra(page, buscar, criterio){
            //     let me=this;
            //     var url='/compra?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
            //     axios.get(url).then(function(response){
            //         me.arrayCompra=response.data.data;
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
            // },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarArticulo(page, buscar, criterio);
            },
            // listarArticulo(buscarP,criterioPbuscarP){
            //     let me = this;
            //     var url='/tienda/listarSinPaginate?buscar=' + buscarP;
            //     axios.get(url).then(function(response){
            //         me.arrayArticulo= response.data;
            //     })
            //     .catch(function(error){
            //         console.log(error);
            //     });
            // },
            listarArticulo(page,buscarP, criterioP){
                let me = this;
                
                var url='/tienda/listarSinPaginateLote?page=' + page + '&buscar=' + buscarP + '&criterio=' + criterioP;
                return axios.get(url).then(function(response){
                    me.arrayArticulo= response.data.data;
                    me.pagination={total:response.data.total, 
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
                let me=this;
                var url='/tienda/listarSinPaginateLote?page=' + 1 + '&buscar=' + me.buscarP + '&criterio=' + me.criterioP;
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
            BuscandoArticulo(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticuloBusquedaRapida,350)
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
                // if(me.encuentra(data['id_articulo'])){
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Error...',
                //         text: 'Este producto ya se encuentra agregado!'
                //     })
                // }
                // else{
                    me.arrayDetalle.push({
                        id_tienda_articulo : data['id'],
                        id_articulo : data['id_articulo'],
                        articulo : data['articulo'],
                        tienda : data['tienda'],
                        costo_compra : data['costo_compra'],
                        cantidad : 1,
                        sub_total : data['sub_total'],
                        lote : '',
                        fecha_vecimiento : moment().format('YYYY-MM-DD'),
                    });
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Producto agregado...',
                        showConfirmButton: false,
                        timer: 500
                    });
              //  }
            },
            cancelarCompra(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.nombre = '';
                me.buscarP = '';
            },
            volverCompraListado(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.nombre = '';
                me.buscarP = '';
                me.listado = 0;
                me.limpiarDatosCompra();
            },
            selectProveedor(){
                let me=this;
                var url='/proveedor/selectProveedor';
                return axios.get(url).then(function(response){
                    me.arrayProveedor=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectTipoP(){
                let me=this;
                var url='/tipoPago/selectTipoP';
                return axios.get(url).then(function(response){
                    me.arrayPago=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectFormaP(){
                let me=this;
                var url='/formaPago/selectFormaP';
                return axios.get(url).then(function(response){
                    me.arrayForma=response.data;
                    //me.arrayFormaPago=response.data;
                    me.arrayForma2 = response.data;
                    me.arrayForma2 = me.arrayForma2.filter((item) => item.id !== 1);
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            verCompra(data=[]){
                let me = this;
                me.listado = 2;
                me.datos.id=data['id'];
                me.datos.proveedor=data['proveedor'];
                me.datos.fecha=data['fecha'];
                me.datos.descripcion=data['descripcion'];
                me.datos.descuento=data['descuento'];
                me.datos.estado=data['estado'];
                me.datos.formaPago=data['formaP'];

                var url='/compra/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            // validarCompra(){
            //     this.errorCompra = 0;

            //     if(this.datos.id_proveedor==""){
            //         Swal.fire({
            //             icon: 'error',
            //             title: 'Seleccione un Proveedor'
            //         })
            //     } 
            //     else
            //     {
            //         if(this.datos.id_tipo_pago==0){
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Seleccione un Tipo de Pago'
            //             })
            //         } 
            //         else
            //         {
            //             if(this.datos.id_forma_pago==0){
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'Seleccione una Forma de Pago'
            //                 })
            //             } 
            //             else
            //             {
            //                 if(this.arrayDetalle.length<=0){
            //                     Swal.fire({
            //                         icon: 'error',
            //                         title: 'Agregue Productos a la Compra'
            //                     })
            //                 } 
            //                 else
            //                 {
            //                     this.errorCompra = 3;
            //                 }
            //             }
            //         }
            //     }
            //     return this.errorCompra;
            // },
                        validarCompra(){
                this.errorCompra = 0;

                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Agregue Productos a la Lote'
                    })
                } 
                else
                {
                    
                    if(this.arrayDetalle.find(seg => (seg.cantidad <= 0 ))){
                    Swal.fire({
                        icon: 'error',
                        title: 'Cantidad no puede se menor a 0'
                    })
                    }else{ 
                         this.errorCompra = 3;
                    }
                }
            
                    
                
                return this.errorCompra;
            },
            guardarLote(){
                this.validarCompra();
                if(this.errorCompra==3){
                        let me = this;
                        me.saving = true;
                        axios.post('/lote/guardar',{
                            'detalle': me.arrayDetalle,
                        }).then(function(response){
                            console.log(me.arrayDetalle);
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Lote registrado exitosamente',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            me.volverCompraListado();
                            me.limpiarDatosCompra();
                        })
                        .catch(function(error){
                            console.log(error);
                        })
                        .finally(function(){
                            me.saving = false;
                        });
                }       
            },
            limpiarDatosCompra(){
                this.datos = {
                    id : 0,
                    id_proveedor : 0,
                    id_forma_pago : 2,
                    id_tipo_pago : 1,
                    fecha : moment().format('YYYY-MM-DD'),
                    descripcion : '',
                    sub_total : 0,
                    descuento : 0,
                    total : 0, 
                }
            },        
            // validarCompra(){
            //     this.errorCompra = 0;
            //     this.errorMostrarMsjCompra = [];

            //     if(!this.datos.nombre) this.errorMostrarMsjCompra.push("El nombre del Compra no puede estar vacio ");
            //     if(this.errorMostrarMsjCompra.length) this.errorCompra=1;
            //     return this.errorCompra;
            // },
            frmCompra(){
                this.listado = 1;
                this.selectProveedor();
                this.selectFormaP();       
            },
            cargarPdf2() {
            axios.get('/compra/pdfCompraGeneral2',{responseType: 'blob'})
                .then(response => {
                    var blob = new Blob([response.data], {type: 'application/pdf'});
                    var downloadUrl = URL.createObjectURL(blob);
                    window.open(downloadUrl, '_blank');
                })
                .catch(error => {
                    console.log(error);
                })
            },
            anularCompra(id){
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
                    axios.put('/compra/anular',{'id': id}).then(function (response) {
                        me.listarCompra(1,'', 'nombre');
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
            } 
        },
        async mounted() {
            try {
                await Promise.all([
                    this.selectProveedor(),
                    this.selectFormaP(),
                    this.selectTipoP(),
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
</style>

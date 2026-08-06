<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                   <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">PROMOCIONES</h3></div>
                    <template v-if="listado==0">
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
                                    <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalArticulo2" @click="listarArticulo(buscarP, criterioP), listarArticulo2(buscarP, criterioP)"><i class="fa fa-search"></i> Agregar Productos/Servicios</button>
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
                                <button class="btn btn-info btn-lg text-white" type="button" @click="guardarPaquete()">Guardar</button>
                            </div>
                        </div>
                    </template>
                </div>
                </div>
            </div>
        <!-- </div>   -->
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
                                            <select class="form-select col-md-3" v-model="criterioP">
                                                <option value="articulo.nombre_comercial">Producto</option>   
                                                <option value="categoria.nombre">Categoria</option>
                                                <!-- <option value="articulo.marca">Marca</option>                                     -->
                                            </select>&nbsp;&nbsp;&nbsp;
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
                                            <select class="form-select col-md-3" v-model="criterioP">
                                                <option value="articulo.nombre_comercial">Producto</option>   
                                                <option value="categoria.nombre">Categoria</option>
                                                <!-- <option value="articulo.marca">Marca</option>                                     -->
                                            </select>&nbsp;&nbsp;&nbsp;
                                            <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(buscarP, criterioP)" @keyup="BuscandoProducto()" class="form-control" placeholder="Texto a buscar">
                                            &nbsp;&nbsp;&nbsp;
                                            <button type="submit" @click="listarArticulo(buscarP, criterioP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
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
    import Swal from '../../utils/appSwal';
    import moment from 'moment';
    export default {
        created() {
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
                    nombre : '',
                    estado:'1',
                    descripcion:'',
                },          
                arrayVenta : [],
                arrayDetalle : [],
                arrayArticulo : [],
                arrayArticuloServicio : [],
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
                criterioP : 'articulo.nombre_comercial',
                criterio : 'nombre',
                buscar : '',
                buscarP : '',
                selectProducto : true,
                isVisible: false,
                buscarCliente: '',
                setTimeoutBuscador: '',
                isVisiblePersonal: false,
                buscarPersonal: '',
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
                for(var i=0;i<this.arrayDetalle.length;i++){
                    resultado = resultado + (this.arrayDetalle[i].costo_unitario*this.arrayDetalle[i].cantidad);
                }
                return resultado;
            },
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
            listarArticulo(buscarP, criterioP){
                let me = this;
                me.buscarCliente= '';
                me.isVisible = false;
                me.buscarPersonal= '';
                me.isVisiblePersonal = false;
                var url='/tienda/producto_tienda1?buscar=' + buscarP+ '&criterio=' + criterioP;
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
                me.buscarPersonal= '';
                me.isVisiblePersonal = false;
                var url='/tienda/producto_tienda1?buscar=' + me.buscarP+ '&criterio=' + me.criterioP;
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
            listarArticulo2(buscarP, criterioP){
                let me = this;
                me.buscarCliente= '';
                me.isVisible = false;
                me.buscarPersonal= '';
                me.isVisiblePersonal = false;
                var url='/tienda/servicio_tienda1?buscar=' + buscarP+ '&criterio=' + criterioP;
                axios.get(url).then(function(response){
                    me.arrayArticuloServicio= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarArticulo2BusquedaRapida(){
                let me = this;
                me.buscarCliente= '';
                me.isVisible = false;
                me.buscarPersonal= '';
                me.isVisiblePersonal = false;
                var url='/tienda/servicio_tienda1?buscar=' + me.buscarP+ '&criterio=' + me.criterioP;
                axios.get(url).then(function(response){
                    me.arrayArticuloServicio= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            BuscandoProductoServicio(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticulo2BusquedaRapida,350)
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
            volverVentaListado(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.nombre = '';
                me.buscarP = '';
                me.listado = 0;
            },
            guardarPaquete(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe Productos agregados!'
                    })
                }else{
                    if(this.datos.nombre == ''){
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Debe Agregar el Nombre!'
                        })
                    }
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
                        axios.post('/paquete/guardar',{
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
                                title: 'Paquete registrado exitosamente',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            //me.cargarPdf2();
                            me.volverVentaListado();
                            me.limpiarDatosVenta();
                        })
                        .catch(function(error){
                            console.log(error);
                        });
                    }
                }
            },        
            validarCompra(){
                this.errorCompra = 0;
                this.errorMostrarMsjCompra = [];

                if(!this.datos.nombre) this.errorMostrarMsjCompra.push("El nombre del Compra no puede estar vacio ");
                if(this.errorMostrarMsjCompra.length) this.errorCompra=1;
                return this.errorCompra;
            },
            limpiarDatosVenta(){
                this.datos = {
                    id : 0,
                    fecha_inicio : moment().format('YYYY-MM-DD'),
                    fecha_final : moment().format('YYYY-MM-DD'),
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_cliente : 0,
                    id_personal : 0,
                    cliente : '',
                    tipoPago : '',
                    formaPago : '',
                    costo_pago : '',
                    id_descuento: '',
                    tipo_producto: '',
                    personal:'',
                    estado:'1',
                    descripcion:'',
                    arrayArticulo:[],
                    
                }
                this.buscarCliente= ''
            },
            cerrarProductos(){
                this.arrayArticulo = [];
                this.arrayArticuloServicio = [];
                this.buscarP = '';
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
                axios.get('/servicio/pdfOrdenServiciosGeneral2',{responseType: 'blob'})
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
            //this.listarVenta(1, this.buscar, this.criterio);
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
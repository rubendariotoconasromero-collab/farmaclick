<template>
    <main class="main">
        
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                   <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">PAGOS DE VENTAS SERVICIO</h3>
                    </div>
                    <br>

                    <template v-if="listado==0">
                    <div class="form-group row">
                        <div class="col-md-8">
                            <div class="input-group"  style='width:96%;margin-left: 3.3%'>
                                <select class="form-select col-md-3" v-model="criterio">
                                    <option value="nombre_cliente">Cliente</option>
                                </select>
                                &nbsp;&nbsp;&nbsp;
                                <input type="text" v-model="buscar" @keyup.enter="listarCliente(buscar)" @keyup="BuscandoCliente()" class="form-control" placeholder="Texto a buscar" style='width:30%'>
                                &nbsp;&nbsp;&nbsp;
                                <button type="submit" @click="listarCliente(buscar)" class="btn btn-info text-white"><i class="fa fa-search"></i> buscar</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- Light table -->
                    <div class="table-responsive">
                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                        <thead style="background-color: #46546c">
                            <tr>                      
                                <th scope="col" class="text-white">NIT/CI</th> 
                                <th scope="col" class="text-white">Cliente</th> 
                                <th scope="col" class="text-white">Celular</th>                       
                                <th scope="col" class="text-white">Monto</th>
                                <th scope="col" class="text-white">Pago</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cliente in arrayCliente" :key="cliente.id">
                                    <td v-text="cliente.matricula"></td>
                                    <td v-text="cliente.cliente"></td>
                                    <td v-text="cliente.telefono"></td>
                                    <td v-text="cliente.monto"></td>
                                    <td>
                                        <button type="button" class="btn btn-info text-white position-relative" @click="PasarPago(cliente)">Pagos</button>    
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
                        <div class="card-body border" >  
                            <button type="button" class="btn btn-danger text-white" @click="volverPagoVenta()"><i class="fa fa-reply-all"></i>&nbsp;Volver</button>                   
                            <div class="form-group row">          
                                <form class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">NIT/CI</label>
                                    <input type="text" class="form-control"  v-model="datos.matricula" disabled>  
                                </div> 
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Proveedor</label>
                                    <input type="text" class="form-control"  v-model="datos.cliente" disabled>  
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control"  v-model="datos.telefono" disabled>  
                                </div> 
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Monto Total</label>
                                    <input type="text" class="form-control"  v-model="datos.monto" disabled>  
                                </div>
                                </form>    
                            </div>

                            <br>
                            <div class="form-group row">
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead style="background-color: #46546C">
                                        <tr>                      
                                            <th scope="col" class="text-white" width="10%">Número</th>
                                            <th scope="col" class="text-white">Fecha</th>
                                            <th scope="col" class="text-white">Teléfono</th>
                                            <th scope="col" class="text-white">Monto</th>
                                            <th scope="col" class="text-white">Saldo</th>
                                            <th scope="col" class="text-white">Opciones</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalleCliente.length">
                                        <tr v-for="(detalle_cliente,index) in arrayDetalleCliente" :key="index">
                                            <td v-text="index+1"></td>
                                            <td v-text="detalle_cliente.fecha"></td>
                                            <td v-text="detalle_cliente.telefono"></td>
                                            <td v-text="detalle_cliente.monto"></td>  
                                            <td v-text="detalle_cliente.saldo"></td>     
                                            <td>
                                                <button type="button" class="text-white position-relative" :class="detalle_cliente.saldo > 0 ? 'btn btn-info ' : 'btn btn-success'" data-bs-toggle="modal" data-bs-target="#modalPago" @click="realizarPagos(detalle_cliente)">Pagos</button>    
                                            </td>                                                                      
                                        </tr>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">No hay Pagos Realizados</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </template>

        <!--Modal Formulario Pago al Crédito-->
        <div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white">
                        <h4 class="modal-title ">Pago al Credito</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <template v-if="estadoCaja == 'Abierta'">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="row">
                                    <div class="mb-3 col-sm-6">
                                        <label for="inputPassword" class="col-sm-6 col-form-label">Fecha</label>
                                        <div><input type="date" class="form-control" v-model="datosPago.fecha" disabled></div>
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="inputPassword" class="col-sm-6 col-form-label">Fecha Final</label>
                                        <div><input type="date" class="form-control" v-model="datosPago.fecha_final" disabled></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-sm-6">
                                        <label for="inputPassword" class="col-sm-6 col-form-label">Pago</label>
                                        <div>
                                            <input type="number" class="form-control" v-model="datosPago.amortizacion" v-on:change="parseFloat(calcularSaldo)" :disabled="datosPago.saldo > 0 ? false : true">
                                        </div>
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="inputPassword" class="col-sm-6 col-form-label">Saldo</label>
                                        <div class="border border-secondary rounded p-2 bg-gris">
                                            {{calcularSaldo.toFixed(2)}}
                                        </div>
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="inputPassword" class="col-sm-6 col-form-label">Monto Total</label>
                                        <div><input type="number" class="form-control" v-model="ultimoPago.monto_total" disabled></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="inputPassword" class="col-sm-6 col-form-label">Descripcion</label>
                                    <textarea class="form-control" id="message-text" v-model="datosPago.descripcion"></textarea>
                                </div>&nbsp;&nbsp;&nbsp;

                                <table class="table table-striped table-hover">
                                    <thead style="background-color: #46546C">
                                        <tr>          
                                            <th scope="col" class="text-white">Numero</th>            
                                            <th scope="col" class="text-white">Fecha</th>
                                            <th scope="col" class="text-white">Monto Total</th>
                                            <th scope="col" class="text-white">Pagos</th>
                                            <th scope="col" class="text-white">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(c_x_cobrar,index) in arrayCXCobrar" :key="c_x_cobrar.id">
                                            <td class="text-center" width=10%>{{index+1}}</td>
                                            <td v-text="c_x_cobrar.fecha"></td>
                                            <td v-text="c_x_cobrar.monto_total"></td>
                                            <td v-text="c_x_cobrar.amortizacion"></td>
                                            <td v-text="c_x_cobrar.saldo"></td>                                
                                        </tr>
                                    </tbody>
                                </table>

                            </form>
                        </template>
                        <template v-if="estadoCaja == 'Cerrada'">
                            <div class="alert alert-warning alert-dismissable text-center">
                                <strong>¡Cuidado!</strong> Se requiere Aperturar Caja Primero.
                            </div>
                        </template>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal" @click="limpiarCXCobrar()">Cerrar</button>
                        <button type="button" class="btn btn-info text-white"  data-bs-dismiss="modal" @click="guardarAmortizacion()">Guardar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Pago al credito-->

                </div>
                </div>
            </div>
        <!-- </div> -->
        



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
                    nombre : '',
                    matricula : '',
                    telefono : '',
                    direccion : '',
                    descripcion : '',
                    estado : '1',
                },
                datosPago:{
                    id: 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    fecha_final : moment().format('YYYY-MM-DD'),
                    monto_total: '',
                    saldo : 0,
                    amortizacion :0,
                    descripcion: '',
                    id_pago: '',
                },    
                id_compra_aux:0, 
                arrayCXCobrar : [],
                ultimoPago : {},
                CXCobrar: [],                            
                arrayTienda : [],
                arrayCliente : [],
                arrayDetalleCliente : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCliente : 0,
                listado : 0,
                errorMostrarMsjCliente : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre_cliente',
                buscar : '',
                setTimeoutBuscador: '',
                id_tienda : 3,
                tipo_producto: 'Venta Servicio',
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
            calcularSaldo: function(){
                var resultado = 0.0
                var saldo = this.datosPago.amortizacion;
                resultado = this.ultimoPago.saldo - saldo;
                return resultado;
            }
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
            realizarPagos(pago) {
                console.log(pago);
                this.listarPagos(pago.id);
                this.id_compra_aux=pago.id;
                this.listarCXCobrar(pago.id);
                this.datosPago.monto_total = pago.total;
                this.datosPago.saldo = pago.saldo;
                this.datosPago.amortizacion = this.datosPago.amortizacion;
            },
            limpiarCXCobrar(){
                this.datosPago ={
                    id: 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    monto_total: 0,
                    saldo : 0,
                    amortizacion : 0,
                    descripcion: '',
                    amortizacion: 0,
                    id_pago: 0,
                },
                
                this.ultimoPago = {
                    monto_total: 0,
                    amorticacion: 0,
                    saldo: 0,
                };
            },
            listarCXCobrar(pago){
                let me = this;
                var url='/detalle_pago_credito?id_pago=' + pago;
                axios.get(url).then(function(response){
                    me.arrayCXCobrar= response.data;
                    me.CXCobrar = response.data;
                    me.cargarUltimoPago();
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            cargarUltimoPago(){
                const array = this.CXCobrar;
                this.ultimoPago=array[array.length-1]; 
            },
            listarPagos(buscarP){
                let me = this;
                var url='/pago_venta?buscar=' + buscarP + '&id_tienda=' + me.id_tienda +  '&tipo_producto=' + me.tipo_producto;
                axios.get(url).then(function(response){
                    me.arrayListaPagos= response.data;
                    me.datosPago.fecha_final=me.arrayListaPagos[0].fecha_final;
                    me.datosPago.id_pago=me.arrayListaPagos[0].id;
                    
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarCliente(buscar){
                let me = this;
                var url='/cliente_pago?buscar=' + buscar + '&id_tienda=' + me.id_tienda +  '&tipo_producto=' + me.tipo_producto;
                axios.get(url).then(function(response){
                    me.arrayCliente= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarClienteBusquedaRapida(){
                let me = this;
                var url='/cliente_pago?buscar=' + me.buscar + '&id_tienda=' + me.id_tienda +  '&tipo_producto=' + me.tipo_producto;
                axios.get(url).then(function(response){
                    me.arrayCliente= response.data;
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
            volverPagoVenta(){
                let me = this;
                me.arrayCliente = [];
                me.arrayDetalleCliente = [];
                me.listado = 0;
                this.listarCliente(this.buscar);
            },
            limpiarDatosPagos() {
                this.datosPago.amortizacion = 0;
              },
            guardarAmortizacion() {
              let me = this;
              if (me.datosPago.amortizacion == 0) {
                Swal.fire({
                  icon: "error",
                  title: "Error...",
                  text: "Ingrese un monto!",
                });
                me.limpiarCXCobrar();
              } else {
                console.log(me.ultimoPago.amortizacion);
                if (me.datosPago.amortizacion > parseFloat(me.ultimoPago.saldo)) {
                  Swal.fire({
                    icon: "error",
                    title: "Error...",
                    text: "Monto Mayor al Saldo!",
                  });
                  me.limpiarPago();
                } else {
                  axios.post("/c_x_cobrar/guardar", {
                      fecha: me.datosPago.fecha,
                      monto_total: parseFloat(me.ultimoPago.monto_total).toFixed(2),
                      amortizacion: parseFloat(me.datosPago.amortizacion).toFixed(2),
                      saldo: me.ultimoPago.saldo,
                      descripcion: me.datosPago.descripcion,
                      id_pago: me.datosPago.id_pago,
                    })
                    .then(function (response) {
                      me.limpiarPago();
                      Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "Pago registrado exitosamente",
                        showConfirmButton: false,
                        timer: 1500,
                      });
                      me.cargarDetalle();
                      me.volverVentaListado();
                      me.listarVenta(1, "", "nombre");
                      me.listarCXCobrar(me.datosPago.id_pago);
                      me.listarPagosArticulos();
                    })
                    .catch(function (error) {
                      console.log(error);
                    });
                }
              }
            },
            PasarPago(data=[]){
                let me = this;
                me.listado = 1;
                me.datos.id=data['id'];
                me.datos.cliente=data['cliente'];
                me.datos.telefono=data['telefono'];
                me.datos.monto=data['monto'];
                me.datos.matricula=data['matricula'];
                me.cargarDetalle();
            },
            cargarDetalle(){
                let me = this;
                var url='/pagos_cliente?buscar=' + me.datos.cliente  + '&id_tienda=' + me.id_tienda +  '&tipo_producto=' + me.tipo_producto;
                axios.get(url).then(function(response){
                    me.arrayDetalleCliente= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            limpiarPago(){
                this.datosPago.amortizacion = 0;
                this.datosPago.descripcion = '';
            },
            cambiarPagina(page,buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarCliente( buscar);
            }
        },
        mounted() {
            this.listarCliente(this.buscar);
            this.verificarCaja();
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
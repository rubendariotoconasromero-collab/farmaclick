<template>
    <main class="main">
        <!-- <div class="container"> -->

                &nbsp;
                    <div class="card pb-4">
                        <div class="card-header text-center text-white" style="background-color: #3399FF">
                            <h3 class="mb-0">REPORTES</h3>
                        </div>
                        <div class="col-md-8 mx-2" >
                            &nbsp;
                            <div class="input-group">
                            <table width="100%" height="5%"  >
                                <tr width="100%">
                                    <td style="width:10%;text-align:center">
                                        Desde
                                    </td>
                                    <td style="width:15%;text-align:left">
                                        <input type="date" class="form-control"  v-model="datos.fecha_inicio">  
                                    </td>
                                    <td style="width:10%;text-align:center">
                                        Hasta
                                    </td>
                                    <td style="width:15%;text-align:left">
                                        <input type="date" class="form-control"  v-model="datos.fecha_fin">  
                                    </td>
                                    <td style="width:57%;text-align:left">
                                        &nbsp;
                                    </td>
                                </tr>
                            </table>
                            </div>
                        </div>
                        <br>
                <div class="card-body col-sm-12">
                    <div class="row">
                        <div class="card bg-light px-0 m-3" style="max-width: 16rem;">
                            <div class="card-header text-uppercase">PRODUCTOS</div>
                            <div class="card-body p-0">

                                <!-- <div><button type="button" @click="cargarPdfProducto()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Producto</button></div> -->
                                <div><button type="button" @click="cargarPdfProductoExcel()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Producto</button></div>
                               
                                <div><button type="button" @click="cargarPdfProveedor()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Laboratorio</button></div>

                                <!-- <div><button type="button" @click="cargarPdfProductoStock()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Inventario Bs</button></div> -->
                                <div><button type="button" @click="cargarPdfProductoStockExcel()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Inventario Bs</button></div>

                                <!-- <div><button type="button" @click="cargarPdfProductoStockMinimo()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Stock Minimo</button></div> -->
                                <div><button type="button" @click="cargarPdfProductoStockMinimoExcel()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Stock Minimo</button></div>

                                <div><button type="button" @click="cargarPdfProductoVencerse()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Producto por Vencerse</button></div>



                            </div>
                        </div>
                        <div class="card bg-light px-0 m-3" style="max-width: 16rem;">
                            <div class="card-header text-uppercase">DATOS</div>
                            <div class="card-body p-0">

                                <div><button type="button" @click="cargarPdfPersonal()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Personal</button></div>

                                <div><button type="button" @click="cargarPdfUsuario()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Usuario</button></div>

                                <div><button type="button" @click="cargarPdfCliente()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Cliente</button></div>

                                <div><button type="button" @click="cargarPdfCaja()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Caja</button></div>
                            </div>
                        </div>
                        <div class="card bg-light px-0 m-3" style="max-width: 16rem;">
                            <div class="card-header text-uppercase">COMPRAS</div>
                            <div class="card-body p-0">

                                <div><button type="button" @click="cargarPdfCompraGeneral()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>General</button></div>

                                <div><button type="button" @click="cargarPdfCompraDetallada()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Detallada</button></div>

                                <div><button type="button" @click="cargarPdfCompraDetalladaAnulada()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Anuladas</button></div>

                                <div><button type="button" @click="cargarPdfPagoCompra()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Cuotas Compra</button></div>

                                <div><button type="button" @click="cargarPdfGasto()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Gasto Detallado</button></div>

                                <div><button type="button" @click="cargarPdfGastoCliente()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Gasto Efectivo</button></div>

                                <div><button type="button" @click="cargarPdfPagoCompraCredito()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Pago Credito</button></div>


                            </div>
                        </div>
                        <div class="card bg-light px-0 m-3" style="max-width: 16rem;">
                            <div class="card-header text-uppercase">VENTAS</div>
                            <div class="card-body p-0">

                                <div><button type="button" @click="cargarPdfVentaGeneral(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>General</button></div>

                                <div><button type="button" @click="cargarPdfVentaDetallada(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Detallada</button></div>

                                <div><button type="button" @click="cargarPdfPagoVenta(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Cuotas Ventas</button></div>

                                <div><button type="button" @click="cargarPdfPagoVentaCredito()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Pago Credito</button></div>
                                
                                <div><button type="button" @click="cargarPdfVentaDetalladaAnulada(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Anuladas</button></div>    
                                
                                <div><button type="button" @click="cargarPdfVentaDetalladaDevolucion(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Devolución</button></div>
                                



                                            
                                            <!-- <div><button type="button" @click="cargarPdfAlmacenPrincipal(2)"  class="btn"><img src="img/sit_norte/PDF.png" width=50px>Productos</button></div> -->
                            </div>
                        </div>
                        <div class="card bg-light px-0 m-3" style="max-width: 16rem;">
                            <div class="card-header text-uppercase">FORMA DE PAGO VENTA</div>
                            <div class="card-body p-0">

                                            <div><button type="button" @click="cargarPdfVentaDetalladaEfectivo(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Efectivo</button></div>

                                            <div><button type="button" @click="cargarPdfVentaDetalladaTransferencia(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Transferencia</button></div>

                                            <div><button type="button" @click="cargarPdfVentaDetalladaQr(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Qr</button></div>

                                            <div><button type="button" @click="cargarPdfVentaDetalladaDeposito(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Deposito</button></div>

                                            <div><button type="button" @click="cargarPdfVentaDetalladaMixta(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Mixta</button></div>

                                            
                                            <!-- <div><button type="button" @click="cargarPdfAlmacenPrincipal(2)"  class="btn"><img src="img/sit_norte/PDF.png" width=50px>Productos</button></div> -->
                            </div>
                        </div>
                        <div class="card bg-light px-0 m-3" style="max-width: 16rem;">
                            <!-- <div class="card-header text-uppercase">Seleccione el Usuario:  -->
                                <select class="form-select" v-model="datos.id_usuario">
                                    <option value="0" disabled>Seleccione el Usuario</option>
                                    <option v-for="tipo_pago in arrayUsuario" :key="tipo_pago.id" :value="tipo_pago.id" v-text="tipo_pago.nombre"></option>
                                </select>
                            <!-- </div> -->
                            <div class="card-body p-0">

                                <div><button type="button" @click="cargarPdfVentaGeneralUsuario(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>General</button></div>
                                <div><button type="button" @click="cargarPdfVentaDetalladaUsuario(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Venta Detalladas</button></div>
                                <div><button type="button" @click="cargarPdfPagoVentaCreditoUsuario()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Pago Credito</button></div>
                                <div><button type="button" @click="cargarPdfVentaDetalladaUsuarioEfectivo(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Efectivo</button></div>
                                <div><button type="button" @click="cargarPdfVentaDetalladaUsuarioTransferencia(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Transferencia</button></div>
                                <div><button type="button" @click="cargarPdfVentaDetalladaUsuarioQr(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Qr</button></div>
                                <div><button type="button" @click="cargarPdfVentaDetalladaUsuarioDeposito(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Deposito</button></div>
                                <div><button type="button" @click="cargarPdfVentaDetalladaUsuarioMixta(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Mixta</button></div>

                            </div>
                        </div>
                        <div class="card bg-light px-0 m-3" style="max-width: 16rem;">
                            <!-- <div class="card-header text-uppercase">Seleccione el Usuario:  -->
                                <select class="form-select" v-model="datos.id_cliente">
                                    <option value="0" disabled>Seleccione el Cliente</option>
                                    <option v-for="tipo_pago in arrayCliente" :key="tipo_pago.id" :value="tipo_pago.id" v-text="tipo_pago.nombre"></option>
                                </select>
                            <!-- </div> -->
                            <div class="card-body p-0">
                                <div><button type="button" @click="cargarPdfVentaDetalladaCliente(datos_tienda.venta_directa, datos_tienda.almacen.id)" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Venta</button></div>
                            </div>
                        </div>
                        <div class="card bg-light px-0 m-3" style="max-width: 16rem;">
                            <!-- <div class="card-header text-uppercase">Seleccione el Usuario:  -->
                                <select class="form-select" v-model="datos.id_proveedor">
                                    <option value="0" disabled>Seleccione Laboratorio</option>
                                    <option v-for="proveedor in arrayLaboratorio" :key="proveedor.id" :value="proveedor.id" v-text="proveedor.nombre"></option>
                                </select>
                            <!-- </div> -->
                            <div class="card-body p-0">
                                <div><button type="button" @click="cargarPdfProductos_vencimiento()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Vencimiento 3 Meses</button></div>
                                <div><button type="button" @click="cargarPdfProductos_vencimiento_Año()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Vencimiento 1 Año</button></div>
                                <div><button type="button" @click="cargarPdfProductosLote()" class="btn"><img src="img/sit_norte/PDF.png" width=50px>Productos</button></div>
                            </div>
                            <!-- <div class="card-body p-0">
                            </div> -->
                        </div>
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
                    fecha_inicio : moment().format('YYYY-MM-DD'),
                    fecha_fin : moment().format('YYYY-MM-DD'),
                    id_usuario:0,
                    id_cliente:0,
                    id_proveedor:0,
                    año : moment().format('YYYY'),
                },
                datos_tienda : {
                    venta_directa : 'Venta Directa',
                    venta_servicio : 'Venta Servicio',
                    almacen : {id:0,nombre:''},
                    tienda_primera : {id:0,nombre:''},
                    tienda_segunda : {id:0,nombre:''},
                    tienda_tercera : {id:0,nombre:''}
                },                         
                arrayTienda : [],
                arrayLaboratorio : [],
                arrayUsuario : [],
                arrayCliente : [],
                mes : moment().format('MM'),
                arrayProducto : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorCliente : 0,
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
                criterio : 'nombre',
                buscarP : ''
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
            selectUsuario(){
                let me=this;
                var url='/usuario/selectUsuario';
                axios.get(url).then(function(response){
                    me.arrayUsuario=response.data;
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
            selectLaboratorio(){
                let me=this;
                var url='/proveedor/selectProveedor';
                axios.get(url).then(function(response){
                    me.arrayLaboratorio=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectTienda(){
                let me=this;
                var url='/tienda/selectTienda';
                axios.get(url).then(function(response){
                    me.arrayTienda=response.data;
                    me.datos_tienda.almacen.nombre = me.arrayTienda[0].nombre;
                    me.datos_tienda.almacen.id = me.arrayTienda[0].id;
                    me.datos_tienda.tienda_primera.nombre = me.arrayTienda[1].nombre;
                    me.datos_tienda.tienda_primera.id = me.arrayTienda[1].id;
                    me.datos_tienda.tienda_segunda.nombre = me.arrayTienda[2].nombre;
                    me.datos_tienda.tienda_segunda.id = me.arrayTienda[2].id;
                    me.datos_tienda.tienda_tercera.nombre = me.arrayTienda[3].nombre;
                    me.datos_tienda.tienda_tercera.id = me.arrayTienda[3].id;
                }).catch(function(error){
                    console.log(error)
                });                
            },
            listarProducto(buscarP){
                // let me = this;
                // var url='/producto4?buscar=' + buscarP ;
                // axios.get(url).then(function(response){
                //     me.arrayProducto= response.data;
                // })
                // .catch(function(error){
                //     console.log(error);
                // });
            },
            cargarPdfCliente() {
                let time=1000;
                this.downloadReport(time);
                axios.get('/reporte/pdfCliente',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfPersonal() {
                let time=700;
                this.downloadReport(time);
                axios.get('/reporte/pdfPersonal',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfProveedor() {
                let time=300;
                this.downloadReport(time);
                axios.get('/reporte/pdfProveedor',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfUsuario() {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfUsuario',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfProducto() {
                let time=6000;
                this.downloadReport(time);
                axios.get('/reporte/pdfProducto',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfProductoExcel() {
                let time=6000;
                this.downloadReport(time);
                axios.get('/reporte/ExcelProductoInventario',{responseType: 'blob'})
                .then(response => {
                    var blob = new Blob([response.data], {type: 'data:application/vnd.ms-excel'});
                    var downloadUrl = URL.createObjectURL(blob);
                    var a = document.createElement("a");
                    a.href = downloadUrl;
                    a.download = "Producto.xlsx";
                    document.body.appendChild(a);
                    a.click();
                })
                .catch(error => {
                    console.log(error);
                })
            },

            cargarPdfAlmacen() {
                axios.get('/reporte/pdfAlmacen',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },


            cargarPdfAjuste() {
                axios.get('/reporte/pdfAjuste',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            cargarPdfOrdenGeneral() {
                axios.get('/reporte/pdfOrdenGeneral?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            cargarPdfCompraGeneral() {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfCompraGeneral?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfProformaGeneral() {
                axios.get('/reporte/pdfProformaGeneral?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            cargarPdfVentaGeneral(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaGeneral?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetallada(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetallada?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetalladaDevolucion(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaDevolucion?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetalladaAnulada(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaAnulada?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaGeneralUsuario(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaGeneralUsuario?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'&id_usuario='+this.datos.id_usuario+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetalladaUsuario(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaUsuario?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'&id_usuario='+this.datos.id_usuario ,{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetalladaUsuarioEfectivo(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaEfectivoUsuario?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'&id_usuario='+this.datos.id_usuario+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetalladaUsuarioTransferencia(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaTransfenciaUsuario?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'&id_usuario='+this.datos.id_usuario+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetalladaUsuarioQr(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaQrUsuario?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'&id_usuario='+this.datos.id_usuario+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetalladaUsuarioDeposito(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaDepositoUsuario?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'&id_usuario='+this.datos.id_usuario+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetalladaUsuarioMixta(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaMixtaUsuario?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'&id_usuario='+this.datos.id_usuario+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetalladaCliente(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaCliente?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'&id_cliente='+this.datos.id_cliente+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfProductos_vencimiento() {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaProductos_Vencimiento?id_tienda='+1+'&id_proveedor='+this.datos.id_proveedor+'&anio='+this.datos.año+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },

            cargarPdfProductos_vencimiento_Año() {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaProductos_Vencimiento1?id_tienda='+1+'&id_proveedor='+this.datos.id_proveedor+'&anio='+this.datos.año+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfProductosLote() {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfProductoLaboratorio?id_proveedor='+this.datos.id_proveedor +'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },


            cargarPdfVentaDetalladaEfectivo(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaEfectivo?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetalladaTransferencia(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaTransfencia?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetalladaQr(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaQr?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetalladaDeposito(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaDeposito?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfVentaDetalladaMixta(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaDetalladaMixta?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfOrdenDetallada() {
                axios.get('/reporte/pdfOrdenDetallada?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            cargarPdfCompraDetallada() {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfCompraDetallada?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfCompraDetalladaAnulada() {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfCompraDetalladaAnular?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfProformaDetallada() {
                axios.get('/reporte/pdfProformaDetallada?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            cargarPdfCaja() {
                let time=300;
                this.downloadReport(time);
                axios.get('/reporte/pdfCaja?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfGasto() {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfGasto?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfGastoCliente() {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfGastoCliente?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfPagoCompra() {
                axios.get('/reporte/pdfPagoCompra?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            cargarPdfPagoVenta(tipo_venta, tienda) {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfPagoVenta?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&tipo_venta='+tipo_venta+'&id_tienda='+tienda+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfPagoVentaCredito() {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaClienteCredito?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfPagoVentaCreditoUsuario() {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfVentaClienteCreditoUsuario?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'&id_usuario='+this.datos.id_usuario+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfPagoCompraCredito() {
                let time=500;
                this.downloadReport(time);
                axios.get('/reporte/pdfCompraProveedorCredito?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },

            cargarPdfTraspasoGeneral() {
                axios.get('/reporte/pdfTraspasoGeneral?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },

            cargarPdfTraspasoDetallado() {
                axios.get('/reporte/pdfTraspasoDetallado?fecha_inicio=' + this.datos.fecha_inicio+'&fecha_fin='+this.datos.fecha_fin+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            cargarPdfMascota() {
                let time=300;
                this.downloadReport(time);
                axios.get('/reporte/pdfPaciente',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfProductoStock() {
                let time=6000;
                this.downloadReport(time);
                axios.get('/reporte/pdfProductoStock',{responseType: 'blob'})
                    .then(response => {
                            var blob = new Blob([response.data], {type: 'application/pdf'});
                            var downloadUrl = URL.createObjectURL(blob);
                            window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfProductoStockExcel() {
                let time=6000;
                this.downloadReport(time);
                axios.get('/reporte/ExcelProducto',{responseType: 'blob'})
                .then(response => {
                    var blob = new Blob([response.data], {type: 'data:application/vnd.ms-excel'});
                    var downloadUrl = URL.createObjectURL(blob);
                    var a = document.createElement("a");
                    a.href = downloadUrl;
                    a.download = "Inventario.xlsx";
                    document.body.appendChild(a);
                    a.click();
                })
                .catch(error => {
                    console.log(error);
                })
            },
            cargarPdfProductoStockMinimo() {
                let time=6000;
                this.downloadReport(time);
                axios.get('/reporte/pdfProductoMinimo',{responseType: 'blob'})
                    .then(response => {
                            var blob = new Blob([response.data], {type: 'application/pdf'});
                            var downloadUrl = URL.createObjectURL(blob);
                            window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            cargarPdfProductoStockMinimoExcel() {
                let time=6000;
                this.downloadReport(time);
                axios.get('/reporte/ExcelProductoMinimo',{responseType: 'blob'})
                .then(response => {
                    var blob = new Blob([response.data], {type: 'data:application/vnd.ms-excel'});
                    var downloadUrl = URL.createObjectURL(blob);
                    var a = document.createElement("a");
                    a.href = downloadUrl;
                    a.download = "Stock Minimo.xlsx";
                    document.body.appendChild(a);
                    a.click();
                })
                .catch(error => {
                    console.log(error);
                })
            },
            downloadReport(milisecond){
                let timerInterval
                    Swal.fire({
                    title: 'Cargando Reporte!',
                    html: 'Por favor espere!! <b></b> milliseconds.',
                    timer: milisecond,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                        timerInterval = setInterval(() => {
                        b.textContent = Swal.getTimerLeft()
                        }, 300)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                    }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        console.log('I was closed by the timer')
                    }
                })
            },
            errorReport(){
                Swal.fire({
                    icon: 'error',
                    title: 'Error al Cargar el Reporte!...',
                    text: 'Comuniquese con el Administrador del Sistema',
                })
            },

            cambiarPagina(page,buscarP, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarProducto( buscarP);
            }
        },
        mounted() {
            this.listarProducto(this.buscarP);
            this.selectTienda();
            this.selectUsuario();
            this.selectCliente();
            this.selectLaboratorio();
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
    .bg-blue{
     background-color: 	#E96B00 !important;
    }
</style>
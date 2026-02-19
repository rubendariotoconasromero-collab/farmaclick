<template>
    <main class="main">
        <!--BreadCrumb-->

        <div class="container-fluid">
            <div class="card">
                <div class="card-header">

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card card-chart">
                                <div class="card-header text-white text-center" style="background-color: #e55353">
                                    <div style="font-size: 15px;">TOTAL VENTAS</div>
                                </div>
                                <div class="card-content">
                                        <h5  class="card-title" align="center">{{ totalVenta }} Bs</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-chart">
                                <div class="card-header text-white text-center" style="background-color: #f9b115">
                                    <div style="font-size: 15px;">TOTAL COMPRAS</div>
                                </div>
                                <div class="card-content">
                                        <h5  class="card-title" align="center">{{ totalCompra }} Bs</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-chart">
                                <div class="card-header text-white text-center" style="background-color: #008080">
                                    <div style="font-size: 15px;">ADMINISTRADORES</div>
                                </div>
                                <div class="card-content">
                                        <h5  class="card-title" align="center">{{ totalAdministrador }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                     <!-- <div class="row">
                        <div class="col-md-4">
                            <div class="card card-chart">
                                <div class="card-header text-white text-center" style="background-color: #f9b115">
                                    <div style="font-size: 15px;">TOTAL COMPRAS</div>
                                </div>
                                <div class="card-content">
                                        <h5  class="card-title" align="center">{{ totalCompra }} Bs</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-chart">
                                <div class="card-header text-white text-center" style="background-color: #008080">
                                    <div style="font-size: 15px;">ADMINISTRADORES</div>
                                </div>
                                <div class="card-content">
                                        <h5  class="card-title" align="center">{{ totalAdministrador }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-chart">
                                <div class="card-header text-white text-center" style="background-color: #454545">
                                    <div style="font-size: 15px;">CAJEROS</div>
                                </div>
                                <div class="card-content">
                                        <h5  class="card-title" align="center">{{ totalCajero }}</h5>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <!-- <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header text-white" style="background-color: #f9b115">
                                    <h4>VENTAS DIRECTAS</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="ventaDirecta">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer" style="background-color: #FFFF">
                                    <p>Venta realizadas de Productos</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header text-white" style="background-color: #f9b115">
                                    <h4>VENTAS SERVICIO</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="ventaServicio">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer" style="background-color: #FFFF">
                                    <p>Ventas realizadas de Servicios</p>
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header text-white" style="background-color: #e55353">
                                    <h4>VENTAS</h4>
                                </div>
                                <div class="card-content" style="margin: auto;">
                                    <div class="ct-chart" style="position: relative; height:40vh; width:40vh">
                                        <canvas id="venta">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer" style="background-color: #FFFF">
                                    <p>Ventas Generales por Tienda.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header text-white" style="background-color: #f9b115">
                                    <h4>COMPRAS</h4>
                                </div>
                                <div class="card-content" style="margin: auto;">
                                    <div class="ct-chart" style="position: relative; height:40vh; width:40vh; display: flex; justify-content: center; align-items: center;">
                                        <canvas id="compra">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer" style="background-color: #FFFF">
                                    <p>Compras de los últimos meses.</p>
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header text-white" style="background-color: #f9b115">
                                    <h4>VENTAS</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="venta">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer" style="background-color: #FFFF">
                                    <p>Ventas Generales por Tienda.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-chart">
                                <div class="card-header text-white" style="background-color: #f9b115">
                                    <h4>COMPRAS</h4>
                                </div>
                                <div class="card-content">
                                    <div class="ct-chart">
                                        <canvas id="compra">
                                        </canvas>
                                    </div>
                                </div>
                                <div class="card-footer" style="background-color: #FFFF">
                                    <p>Compras de los últimos meses.</p>
                                </div>
                            </div>
                        </div> -->
                        </div>
                        <div class="col-md-12">
                            <!-- <template v-if="datos.grupo != '1'"> -->
                                <div class="card" style="--cui-card-cap-bg: green">
                                <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                <strong> PRODUCTO POR AÑO</strong>
                                &nbsp;
                                <button class="btn btn-danger text-white" @click="cargarPdfProducto()">Imprimir</button>
                                </div>   
                            <!-- <h3 class="text-center"></h3> -->
                        <!-- Light table -->
                            <div class="table-responsive" style="overflow-y: auto; height: 200px;">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2.5%'>
                                <thead  >
                                <!-- <thead  style="background-color: #7FC9FF"> -->
                                    <tr>
                                        <th scope="col" >Nombre</th>
                                        <th scope="col" >F. Vencimiento</th>
                                        <th scope="col" >Laboratorio</th>
                                        <th scope="col" >Presentacion</th>
                                        <th scope="col" >Stock</th>
                                        <!-- <th scope="col">Foto</th>
                                        <th scope="col">Opciones</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="producto in arrayProducto" :key="producto.id">
                                        <td v-text="producto.articulo" ></td>
                                        <td v-text="producto.fecha_vecimiento" ></td>
                                        <td v-text="producto.laboratorio" ></td>
                                        <td v-text="producto.presentacion" ></td>
                                        <td v-text="producto.stock" ></td>
                                        <!-- <td><img :src="'img/mi_empresa/'+ producto.foto" width="60" align="left" alt=""></td>      -->
                                        <!-- <td>
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Acción</button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" @click="abrirModal('personal','modificar',personal)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                            </ul>
                                        </td>                                  -->
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                            </div>
                        <!-- </template >
                        <template v-else>
                        </template> -->
                        </div>
                        <div class="col-md-12">
                            &nbsp;
                        </div>
                        <div class="col-md-12">
                            <!-- <template v-if="datos.grupo != '1'"> -->
                                <div class="card" style="--cui-card-cap-bg: green">
                                <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                <strong> PRODUCTO POR 3 MESES</strong>
                                &nbsp;
                                <button class="btn btn-danger text-white" @click="cargarPdfProductoMeses()">Imprimir</button>
                                </div>   
                            <!-- <h3 class="text-center"></h3> -->
                        <!-- Light table -->
                            <div class="table-responsive" style="overflow-y: auto; height: 200px;">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2.5%'>
                                <thead  >
                                <!-- <thead  style="background-color: #7FC9FF"> -->
                                    <tr>
                                        <th scope="col" >Nombre</th>
                                        <th scope="col" >F. Vencimiento</th>
                                        <th scope="col" >Laboratorio</th>
                                        <th scope="col" >Presentacion</th>
                                        <th scope="col" >Stock</th>
                                        <!-- <th scope="col">Foto</th>
                                        <th scope="col">Opciones</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="producto in arrayProductoMeses" :key="producto.id">
                                        <td v-text="producto.articulo" ></td>
                                        <td v-text="producto.fecha_vecimiento" ></td>
                                        <td v-text="producto.laboratorio" ></td>
                                        <td v-text="producto.presentacion" ></td>
                                        <td v-text="producto.stock" ></td>
                                        <!-- <td><img :src="'img/mi_empresa/'+ producto.foto" width="60" align="left" alt=""></td>      -->
                                        <!-- <td>
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Acción</button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" @click="abrirModal('personal','modificar',personal)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                            </ul>
                                        </td>                                  -->
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                            </div>
                        <!-- </template >
                        <template v-else>
                        </template> -->
                        </div>
                    </div>
                </div>
                <div class="row">

                </div>
            </div>

    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    import moment from 'moment';
    export default {
        created() {
            this.listarProducto();
            this.listarProductoMeses();
        },
        data(){
            return {
            mesProducto : moment().format('MM'),
            anio : moment().format('YYYY'),
                
            totalVenta: 0,
            totalVentaD: 0,
            totalVentaS: 0,
            totalCompra: 0,
            totalAdministrador: 0,
            totalCajero: 0,
            
            varCompra:'',
            charCompra:null,
            compra:[],
            varTotalCompra:[],
            varMesCompra:[],

            varVenta:null,
            charVenta:null,
            venta:[],
            varTotalVenta:[],
            //varMesVenta:[],
            varTienda:[],

            ventaD:[],
            varVentaD:null,
            charVentaD:null,
            varTotalVentaD:[],
            varMesVentaD:[],

            varVentaDirecta:null,
            charVentaDirecta:null,
            ventaDirecta:[],
            varTotalVentaDirecta:[],
            varTiendaDirecta:[],

            varVentaServicio:null,
            charVentaServicio:null,
            ventaServicio:[],
            varTotalVentaServicio:[],
            varTiendaServicio:[],

            arrayProducto : [],
            arrayProductoMeses : [],


            }
        },
        methods: {
            /*cargarPdfProducto() {
                let time=6000;
                this.downloadReport(time);
                axios.get('/reporte/listarProductoMes?anio='+this.anio+'',{responseType: 'blob'})
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
            cargarPdfProductoMeses() {
                let time=6000;
                this.downloadReport(time);
                axios.get('/reporte/listarProductoMeses?anio='+this.anio+'',{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },*/

            procesarDescargaPdf(url) {
                Swal.fire({
                    title: 'Generando PDF',
                    html: 'Por favor espere, procesando el documento...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                axios.get(url, {
                    params: { 
                        anio: this.anio 
                    },
                    responseType: 'blob'
                })
                .then(response => {
                    const blob = new Blob([response.data], { type: 'application/pdf' });
                    const downloadUrl = URL.createObjectURL(blob);
                    window.open(downloadUrl, '_blank');
                    Swal.close(); 
                })
                .catch(error => {
                    console.log(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo generar el reporte PDF.'
                    });
                });
            },

            cargarPdfProducto() {
                this.procesarDescargaPdf('/reporte/listarProductoMes');
            },

            cargarPdfProductoMeses() {
                this.procesarDescargaPdf('/reporte/listarProductoMeses');
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
            listarProducto(){
                let me=this;
                var url='/dashboard/Producto';
                axios.get(url).then(function(response){
                    me.arrayProducto=response.data;
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
            listarProductoMeses(){
                let me=this;
                var url='/dashboard/Producto/Meses?&anio=' + this.anio ;
                axios.get(url).then(function(response){
                    me.arrayProductoMeses=response.data;
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
            getCompra(){
                let me=this;
                var url='/dashboard';
                axios.get(url).then(function (response){
                    var respuesta= response.data;
                    me.compra = respuesta.compra;
                    // me.venta = respuesta.venta;
                    // me.ventaDirecta = respuesta.venta_directa;
                    // me.ventaServicio = respuesta.venta_servicio;
                    //cargamos los datos del chart
                    me.loadCompra();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getVenta(){
                let me=this;
                var url='/dashboard';
                axios.get(url).then(function (response){
                    var respuesta= response.data;
                    me.venta = respuesta.venta;
                    //cargamos los datos del chart
                    me.loadVenta();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getVentaDirecta(){
                let me=this;
                var url='/dashboard';
                axios.get(url).then(function (response){
                    var respuesta= response.data;
                    me.ventaDirecta = respuesta.venta_directa;
                    //cargamos los datos del chart
                    me.loadVentaDirecta();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getVentaServicio(){
                let me=this;
                var url='/dashboard';
                axios.get(url).then(function (response){
                    var respuesta= response.data;
                    me.ventaServicio = respuesta.venta_servicio;
                    //cargamos los datos del chart
                    me.loadVentaServicio();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getVentaMontoTotal(){
                let me=this;
                var url='/notaventa/montoT';
                axios.get(url).then(function (response){
                    me.totalVenta = parseFloat(response.data[0].total) ? parseFloat(response.data[0].total) : 0;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getCompraMontoTotal(){
                let me=this;
                var url='/notacompra/montoT';
                axios.get(url).then(function (response){
                    me.totalCompra = parseFloat(response.data[0].total) ? parseFloat(response.data[0].total) : 0;
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getVentaMontoTotalD(){
                let me=this;
                var url='/notaventa/montoD';
                axios.get(url).then(function (response){
                    me.totalVentaD = parseFloat(response.data[0].total) ? parseFloat(response.data[0].total) : 0;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getVentaMontoTotalS(){
                let me=this;
                var url='/notaventa/montoS';
                axios.get(url).then(function (response){
                    me.totalVentaS = parseFloat(response.data[0].total) ? parseFloat(response.data[0].total) : 0;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getAdministrador(){
                let me=this;
                var url='/notaventa/montoUsuario';
                axios.get(url).then(function (response){
                    me.totalAdministrador = parseFloat(response.data[0].usuario) ? parseFloat(response.data[0].usuario) : 0;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            }, 
            getCajeros(){
                let me=this;
                var url='/notaventa/montoUsuario/Cajero';
                axios.get(url).then(function (response){
                    me.totalCajero = parseFloat(response.data[0].usuario) ? parseFloat(response.data[0].usuario) : 0;
                    
                })
                .catch(function (error) {
                    console.log(error);
                });
            },       
            loadCompra(){
                let me=this;
                me.compra.map(function(x){
                    me.varMesCompra.push(x.mes);
                    me.varTotalCompra.push(x.total);
                });
                me.varCompra=document.getElementById('compra').getContext('2d');

                me.charCompra = new Chart(me.varCompra, {
                    type: 'bar',
                    data: {
                        labels: me.varMesCompra,
                        datasets: [{
                            label: 'Compras',
                            data: me.varTotalCompra,
                            backgroundColor: ["#30E8AA", "#6291FF", "#B2C12C", "#FBBD43", "#E65E72"],
                            borderColor: ["#30E8AA", "#6291FF", "#B2C12C", "#FBBD43", "#E65E72"],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },


            loadVenta(){
                let me=this;
                me.venta.map(function(x){
                    me.varTienda.push(x.tienda);
                    //me.varMesVenta.push(x.mes);
                    me.varTotalVenta.push(x.total);
                });
                me.varVenta=document.getElementById('venta').getContext('2d');
                me.charVenta = new Chart(me.varVenta, {
                    type: 'pie', //bar
                    data: {
                        labels: me.varTienda,
                        datasets: [{
                            label: me.varTienda,
                            data: me.varTotalVenta,
                            backgroundColor: ["#30E8AA", "#6291FF", "#B2C12C", "#FBBD43", "#E65E72"],
                            borderColor: ["#30E8AA", "#6291FF", "#B2C12C", "#FBBD43", "#E65E72"],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },

            loadVentaDirecta(){
                let me=this;
                me.ventaDirecta.map(function(x){
                    me.varTiendaDirecta.push(x.tienda);
                    me.varTotalVentaDirecta.push(x.total);
                });
                me.varVentaDirecta=document.getElementById('ventaDirecta').getContext('2d');
                me.charVentaDirecta = new Chart(me.varVentaDirecta, {
                    type: 'bar', //bar
                    data: {
                        labels: me.varTiendaDirecta,
                        datasets: [{
                            label: 'Venta Directa',
                            data: me.varTotalVentaDirecta,
                            backgroundColor: ["#30E8AA", "#6291FF", "#B2C12C", "#FBBD43", "#E65E72"],
                            borderColor: ["#30E8AA", "#6291FF", "#B2C12C", "#FBBD43", "#E65E72"],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },

            loadVentaServicio(){
                let me=this;
                me.ventaServicio.map(function(x){
                    me.varTiendaServicio.push(x.tienda);
                    me.varTotalVentaServicio.push(x.total);
                });
                me.varVentaServicio=document.getElementById('ventaServicio').getContext('2d');
                me.charVentaServicio = new Chart(me.varVentaServicio, {
                    type: 'bar', //bar
                    data: {
                        labels: me.varTiendaServicio,
                        datasets: [{
                            label: 'Ventas Servicio',
                            data: me.varTotalVentaServicio,
                            backgroundColor: ["#30E8AA", "#6291FF", "#B2C12C", "#FBBD43", "#E65E72"],
                            borderColor: ["#30E8AA", "#6291FF", "#B2C12C", "#FBBD43", "#E65E72"],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
        },
        mounted(){
            this.listarProducto();
            this.listarProductoMeses();
            this.getCompra();
            this.getVenta();
            this.getVentaDirecta();
            this.getVentaServicio();
            this.getVentaMontoTotal();
            this.getCompraMontoTotal();
            this.getVentaMontoTotalD();
            this.getVentaMontoTotalS();
            this.getAdministrador();
            this.getCajeros();
        
        }
    }
</script>
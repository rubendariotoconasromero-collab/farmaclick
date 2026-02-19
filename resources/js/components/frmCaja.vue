<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                   <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">CAJA</h3>
                    </div>
                    <!-- prueba card -->
                    <div class="row mt-2" style='width:95%;margin-left: 1.3%;'>
                    <div class="col-sm-4" style='width:20%;'>
                        <div class="card" style="--cui-card-cap-bg: green;">
                            <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                            <strong> APERTURA INICIAL DE CAJA </strong>
                            </div>
                            <div class="card-body row text-center">

                                <div class="col" style='width:10%'>
                                    <div class="text-uppercase text-medium-emphasis small ">Monto</div>
                                    <div><input type="text" class="form-control" v-model="datos.apertura"></div>
                                </div>
                                <div>
                                    &nbsp;
                                </div>
                                <div style='width:100%;display: flex;text-align:center'>

                                    <div style='float: left;text-align:center;width:100%'>
                                        <button type="button" class="btn btn-success text-white position-relative" @click="aperturarCaja()">
                                            Dar Apertura
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div style="height:60px;">
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-sm-4" style='width:15% ;height:20%'>
                        <div class="card" style="--cui-card-cap-bg: orange">
                            <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                            <strong> ARQUEO DE CAJA </strong>
                            </div>
                            <div class="card-body row text-center">

                                <div class="col" style='width:60%'>
                                    <div class="text-uppercase text-medium-emphasis small ">Configurar</div>
                                    <div>
                                        <button  type="button" class="btn btn-outline-warning position-relative" @click="btnArqueo()">
                                            Arqueo
                                        </button>
                                    </div>
                                </div>

                            </div>
                            <div style="height:123px;">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" style='width:30%'>

                            <div class="card" style="--cui-card-cap-bg: green">
                            <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                            <strong> PRODUCTO POR AÑO </strong>
                            &nbsp;
                            <button class="btn btn-danger text-white" @click="cargarPdfProducto()">Imprimir</button>
                            </div>   

                        <div class="table-responsive" style="overflow-y: auto; height: 200px;">
                        <table class="table table-striped table-hover" style='width:96%;margin-left: 2.5%'>
                            <thead  >

                                <tr>
                                    <th scope="col" style="font-size: 10px;">Nombre</th>
                                    <th scope="col" style="font-size: 10px; widows: 150px;" >Vencimiento</th>
                                    <th scope="col" style="font-size: 10px;">Laboratorio</th>
                                    <th scope="col" style="font-size: 10px;">Presentacion</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="producto in arrayProducto" :key="producto.id">
                                    <td v-text="producto.articulo" style="font-size: 10px;"></td>
                                    <td v-text="producto.fecha_vecimiento" style="font-size: 11px;"></td>
                                    <td v-text="producto.laboratorio" style="font-size: 11px;"></td>
                                    <td v-text="producto.presentacion" style="font-size: 11px;"></td>

                                </tr>
                            </tbody>
                        </table>
                        </div>
                        </div>

                    </div>
                    <div class="col-sm-4" style='width:30%'>

                                <div class="card" style="--cui-card-cap-bg: green">
                                <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                <strong> PRODUCTO POR 3 MESES</strong>
                                &nbsp;
                                <button class="btn btn-danger text-white" @click="cargarPdfProductoMeses()">Imprimir</button>
                                </div>   

                            <div class="table-responsive" style="overflow-y: auto; height: 200px;">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2.5%'>
                                <thead  >

                                    <tr>
                                        <th scope="col" style="font-size: 10px;">Nombre</th>
                                        <th scope="col" style="font-size: 10px;">Vencimiento</th>
                                        <th scope="col" style="font-size: 10px;">Laboratorio</th>
                                        <th scope="col" style="font-size: 10px;">Presentacion</th>
                                        <th scope="col" style="font-size: 10px;">Stock</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="producto in arrayProductoMeses" :key="producto.id">
                                        <td v-text="producto.articulo" style="font-size: 11px;"></td>
                                        <td v-text="producto.fecha_vecimiento" style="font-size: 11px;" ></td>
                                        <td v-text="producto.laboratorio" style="font-size: 11px;"></td>
                                        <td v-text="producto.presentacion" style="font-size: 11px;"></td>
                                        <td v-text="producto.stock" style="font-size: 11px;"></td>

                                    </tr>
                                </tbody>
                            </table>
                            </div>
                            </div>

                    </div>
                    </div>
                    <!-- prueba fin card -->


                    <!-- Card Pagination -->
                    <!-- Light table -->
                    <div class="card-body px-1 pb-0">
                    <div class="table-responsive">
                    <template v-if="usuario.id_grupo == 1">
                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                        <thead style="background-color: #46546C">
                            <tr>                      
                                <th scope="col" class="text-white" style='font-size: 0.8rem'>Fecha Apertura</th>
                                <th scope="col" class="text-white" style='font-size: 0.8rem'>Monto Apertura</th>
                                <th scope="col" class="text-white" style='font-size: 0.8rem'>Total Ingreso General</th>
                                <th scope="col" class="text-white" style='font-size: 0.8rem'>Total Ingreso Efectivo</th>
                                <th scope="col" class="text-white" style='font-size: 0.8rem'>Total Egresos General</th>
                                <th scope="col" class="text-white" style='font-size: 0.8rem'>Total Egresos Efectivo</th>
                                <th scope="col" class="text-white" style='font-size: 0.8rem'>Total Neto</th>
                                <th scope="col" class="text-white" style='font-size: 0.8rem'>Usuario Responsable</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="caja in arrayCaja" :key="caja.id">
                                <td v-text="caja.fecha_apertura" style='font-size: 0.8rem'></td>
                                <td v-text="caja.apertura" style='font-size: 0.8rem'></td>
                                <td v-text="caja.total_ingreso_general" style='font-size: 0.8rem'></td>
                                <td v-text="caja.total_ingreso_efectivo" style='font-size: 0.8rem'></td>
                                <td v-text="caja.total_egreso_general " style='font-size: 0.8rem'></td>
                                <td v-text="caja.total_egreso_efectivo " style='font-size: 0.8rem'></td>
                                <td v-text="(caja.total_neto).toFixed(2)" style='font-size: 0.8rem'></td>
                                <td v-text="caja.name" style='font-size: 0.8rem'></td>     
                            </tr>
                        </tbody>
                    </table>
                    </template>
                    <template v-else>
                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                        <thead style="background-color: #46546C">
                            <tr>                      
                                <th scope="col" class="text-white">Fecha Apertura</th>
                                <th scope="col" class="text-white">Monto Apertura</th>
                                <!-- <th scope="col" class="text-white">Ingresos</th>
                                <th scope="col" class="text-white">Egresos</th>
                                <th scope="col" class="text-white">Total Efectivo</th> -->
                                <th scope="col" class="text-white">Usuario Responsable</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="caja in arrayCaja" :key="caja.id">
                                <td v-text="caja.fecha_apertura"></td>
                                <td v-text="caja.apertura"></td> 
                                <!-- <td v-text="caja.ingreso"></td>
                                <td v-text="caja.egreso"></td> 
                                <td v-text="caja.total_efectivo"></td> -->
                                <td v-text="caja.name"></td>      
                            </tr>
                        </tbody>
                    </table>
                    </template>
                    </div>
                    </div>
                    <template v-if="listado==1">
                        <div class="card-body pt-0">
                            <div class="card-body pt-0">
                            <div class="card-header text-center">
                                <div class="row">
                                    <div class="col-md-1">
                                        <button type="button" @click="ocultarListado1()" class="btn btn-danger text-white " >
                                            <i class="fa fa-reply-all"></i>&nbsp;Volver
                                        </button>
                                    </div>
                                    <div class="col-md-9 text-center">
                                        <h3 class="mb-0">ARQUEO DE CAJA</h3>  
                                    </div>
                                </div>
                            </div>
                            <div class="card-header text-center line p-0 m-0"  style="background-color: #3399FF"></div>&nbsp;
                        <form class="row pb-4" style="height:60%;width:100%;text-align: center;">
                            <div class="container" style="text-align: center;">
                                <div class="row g-2">
                                    <template v-if="usuario.id_grupo == 1">
                                    <div class="col-md-6">
                                            <div class="p-2 mb-2 border border-warning">
                                                <h5 class="mx-4 px-4 my-1">Apertura: {{datos.apertura}}</h5>
                                            </div>
                                            <div class="p-2 border border-warning">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col" width="30%">C</th>
                                                        <th scope="col" width="35%">PIEZA</th>
                                                        <th scope="col" width="35%" class="text-center">CANTIDAD</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="align-middle">200</td>
                                                            <td><input class="form-control form-control-sm" type="number" v-on:keyup="calcularArqueo()" v-model="datos.doscientos"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.doscientos*200).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">100</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cien"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cien*100).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">50</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cincuenta"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cincuenta*50).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">20</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.veinte"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.veinte*20).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">10</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.diez"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.diez*10).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">5</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cinco"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cinco*5).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">2</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.dos"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.dos*2).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">1</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.uno"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.uno*1).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">0.5</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cerocinco"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cerocinco*0.5).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">0.2</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.ceroveinte"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.ceroveinte*0.2).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">100 $</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cien_dolar"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cien_dolar*100*7).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th height="260px"></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                    </div>
                                        <div class="col-md-6">
                                            <div class="p-2 mb-2 border border-warning">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-info">Apertura Caja</th>
                                                            <th class="text-info">{{datos.apertura}} </th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-info">Total Ventas Efectivo</th>
                                                            <th class="text-info">{{datos.total_contado}} </th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-info">Total Ventas Depositos</th>
                                                            <th class="text-info">{{datos.total_contado_deposito}} </th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-info">Total Pagos Efectivo V. Credito</th>
                                                            <th class="text-info">{{datos.total_credito}} </th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-info">Total Pagos Deposito V. Credito</th>
                                                            <th class="text-info">{{datos.total_credito_deposito}} </th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th >Total Ingresos General</th>
                                                            <th >{{datos.registro_venta}}</th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th >Total Ingresos Efectivo</th>
                                                            <th >{{datos.total_ingreso_efec}}</th>
                                                        </tr>    
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-danger">Total Gastos Efectivo</th>
                                                            <th class="text-danger">{{datos.gastos}}</th>
                                                        </tr>  
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-danger">Total Gastos Depositos</th>
                                                            <th class="text-danger">{{datos.gastos_deposito}}</th>
                                                        </tr>  
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-danger">Total Compra Efectivo</th>
                                                            <th class="text-danger">{{datos.total_contado_compra}} </th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-danger">Total Compra Deposito</th>
                                                            <th class="text-danger">{{datos.total_contado_deposito_compra}} </th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-danger">Total Pago Efectivo C. Credito</th>
                                                            <th class="text-danger">{{datos.total_credito_compra}} </th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th class="text-danger">Total Pago Deposito C. Credito</th>
                                                            <th class="text-danger">{{datos.total_credito_deposito_compra}} </th>
                                                        </tr>
                                                        <!-- <tr>
                                                            <th></th>
                                                            <th class="text-danger">Total Egresos</th>
                                                            <th class="text-danger">{{datos.saldo_sistema}}</th>
                                                        </tr> -->
                                                        <tr>
                                                            <th></th>
                                                            <th >Total Egresos General</th>
                                                            <th >{{datos.total_egreso_efectivo}}</th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th >Total Egresos Efectivo</th>
                                                            <th >{{datos.gasto_efectivo2}}</th>
                                                        </tr>
                                                        <!-- <tr>
                                                            <th></th>
                                                            <th >Total Egresos Efectivo</th>
                                                            <th >{{datos.total_egreso_efectivo}}</th>
                                                        </tr> -->
                                                        <tr>
                                                            <th></th>
                                                            <th>Saldo Total</th>
                                                            <th>{{(datos.saldo_total).toFixed(2)}}</th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th>Cierre de Caja</th>
                                                            <th>{{(total_efec).toFixed(2)}}</th>
                                                        </tr>

                                                    </thead>
                                                </table>
                                                <div class="p-2 mb-2 border border-warning">
                                                    <h5 class="mx-4 px-4 my-1">Total Dinero en Efectivo: {{(datos.saldo_total).toFixed(2)}}</h5>
                                                </div>
                                                <div class="p-2 mb-2 border border-warning">
                                                    <h6 class="mx-4 px-4 my-1">Diferencia: {{(datos.diferencia).toFixed(2)}}</h6>
                                                </div>
                                                <div class="p-2 mb-3 border border-warning">
                                                    <button style="width:100%" type="button" class="btn btn-warning text-white" @click="cerrarCaja()">
                                                        Cerrar Caja
                                                    </button>
                                                </div>
                                            </div>
                                    </div>
                                    </template>
                                    <template v-else>
                                        <div class="col-md-3">
                                        </div>
                                        <div class="col-md-6">
                                            <!-- <div class="p-2 mb-2 border border-warning">
                                                <h5 class="mx-4 px-4 my-1">Apertura: {{datos.apertura}}</h5>
                                            </div> -->
                                            <div class="p-2 border border-warning">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col" width="30%">C</th>
                                                        <th scope="col" width="35%">PIEZA</th>
                                                        <th scope="col" width="35%" class="text-center">CANTIDAD</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="align-middle">200</td>
                                                            <td><input class="form-control form-control-sm" type="number" v-on:keyup="calcularArqueo()" v-model="datos.doscientos"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.doscientos*200).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">100</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cien"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cien*100).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">50</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cincuenta"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cincuenta*50).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">20</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.veinte"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.veinte*20).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">10</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.diez"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.diez*10).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">5</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cinco"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cinco*5).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">2</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.dos"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.dos*2).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">1</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.uno"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.uno*1).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">0.5</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cerocinco"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cerocinco*0.5).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">0.2</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.ceroveinte"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.ceroveinte*0.2).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">100 $</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cien_dolar"></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cien_dolar*100*7).toFixed(2)}}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="p-2 mb-3 border border-warning">
                                                    <button style="width:100%" type="button" class="btn btn-warning text-white" @click="cerrarCaja()">
                                                        Cerrar Caja
                                                    </button>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                        </div>
                                    </template>
                                </div>

                            </div>
                            
                        </form>
                            <div class="header-divider"></div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-danger me-md-2 text-white" type="button" @click="ocultarListado1()">Cancelar</button>
                                <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==1"  type="button" @click="guardarcliente()">Guardar cliente</button>
                                <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==2"  type="button" @click="modificarcliente()">Modificar cliente</button>
                            </div>
                            </div>
                        </div>
                    </template>

                </div>
                </div>
            </div>
        <!-- </div> -->
    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    import debounce from 'lodash/debounce'
    import moment from 'moment';
    export default {
        created() {
            this.VentaEfectivo();
            this.VentaDeposito();
            this.CompraEfectivo();
            this.CompraDeposito();
            this.GastoEfectivo();
            this.GastoDeposito();
            this.VentaCobrarEfectivo();
            this.VentaCobrarDeposito();
            this.CompraCobrarEfectivo();
            this.CompraCobrarDeposito();
            this.listarCajaAbierta();
            this.registrosimportacion();
            this.usuarioAuth();
            this.listarProducto();
            this.listarProductoMeses();
        },
        data(){
            return {
                datos : {
                    id : 0,
                    fecha_apertura : '',
                    fecha_cierre : '',
                    doscientos : 0,
                    cien : 0,
                    cincuenta : 0,
                    veinte : 0,
                    diez : 0,
                    cinco : 0,
                    dos : 0,
                    uno : 0,
                    cerocinco : 0,
                    ceroveinte : 0,
                    cien_dolar : 0,
                    registro_venta : 0,
                    total_ingreso_efec: 0,
                    registro_venta2 : 0,
                    alojamiento : 0,
                    apertura : 0,
                    total : 0,
                    gastos : 0,
                    gastos_deposito : 0,
                    registro_compra : 0,
                    saldo_sistema : 0,
                    saldo_efectivo : 0,
                    diferencia : 0,
                    id_usuario : 0,
                    estado : 'abierta',
                    grupo :0,
                    total_contado :0,
                    total_contado_deposito :0,
                    total_credito :0,
                    total_credito_deposito :0,
                    total_contado_compra :0,
                    total_contado_deposito_compra :0,
                    total_credito_compra :0,
                    total_credito_deposito_compra :0,
                    total_ingreso_efectivo :0,
                    total_egreso_efectivo:0,
                    gasto_efectivo2 : 0,
                    saldo_total : 0,
                },   
                mesProducto : moment().format('MM'),
                anio : moment().format('YYYY'),
                total_efec:0,
                listado:0,
                variable:0,                            
                arrayimportacion : [],
                arrayCaja : [],
                arrayArqueo : [],
                arrayProducto : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                importacion_registro :0, 
                errorimportacion : 0,
                errorMostrarMsjimportacion : [],
                arrayProductoMeses : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'name',
                buscar : '',
                total_egreso_efectivo:0,

                usuario : {},
                ventaefectivo: {},
                ventaDeposito: {},
                ventaCobrarefectivo: {},
                ventaCobrarDeposito: {},
                gastoEfec: {},
                gastoDep : {},
                compraefectivo: {},
                compraDeposito: {},
                compraCobrarefectivo: {},
                compraCobrarDeposito: {},
                is_busy: 0,
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
            
            usuarioAuth(){
                let me=this;
                var url='/usuario_auth';
                axios.get(url).then(function(response){
                    me.usuario=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            VentaEfectivo() {
                let me = this;
                var url = '/venta_tienda1/VentaArqueoEfectivo';
                axios.get(url).then(function (response) {
                        me.ventaefectivo = response.data[0];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            VentaDeposito() {
                let me = this;
                var url = '/venta_tienda1/VentaArqueoDeposito';
                axios.get(url).then(function (response) {
                        me.ventaDeposito = response.data[0];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            VentaCobrarEfectivo() {
                let me = this;
                var url = '/c_x_cobrar/VentaCobrarArqueoEfectivo';
                axios.get(url).then(function (response) {
                        me.ventaCobrarefectivo = response.data[0];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            VentaCobrarDeposito() {
                let me = this;
                var url = '/c_x_cobrar/VentaCobrarArqueoDeposito';
                axios.get(url).then(function (response) {
                        me.ventaCobrarDeposito = response.data[0];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            GastoEfectivo() {
                let me = this;
                var url = '/gasto/GastoArqueoEfectivo';
                axios.get(url).then(function (response) {
                        me.gastoEfec = response.data[0];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            GastoDeposito() {
                let me = this;
                var url = '/gasto/GastoArqueoDeposito';
                axios.get(url).then(function (response) {
                        me.gastoDep = response.data[0];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            CompraEfectivo() {
                let me = this;
                var url = '/compra/CompraArqueoEfectivo';
                axios.get(url).then(function (response) {
                        me.compraefectivo = response.data[0];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            CompraDeposito() {
                let me = this;
                var url = '/compra/CompraArqueoDeposito';
                axios.get(url).then(function (response) {
                        me.compraDeposito = response.data[0];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            CompraCobrarEfectivo() {
                let me = this;
                var url = '/c_x_pagar/CompraCobrarArqueoEfectivo';
                axios.get(url).then(function (response) {
                        me.compraCobrarefectivo = response.data[0];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            CompraCobrarDeposito() {
                let me = this;
                var url = '/c_x_pagar/CompraCobrarArqueoDeposito';
                axios.get(url).then(function (response) {
                        me.compraCobrarDeposito = response.data[0];
                    })
                    .catch(function (error) {
                        console.log(error);
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
            cargarPdfProducto() {
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
            listarCaja(page, buscar, criterio){
                let me=this;
                var url='/importacion?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayimportacion=response.data.data;
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
            calcularArqueo: function(){
                var x1=0;
                var x2=0;
                var x3=0;
                var x4=0;
                var x5=0;
                var x6=0;
                var x7=0;
                var x8=0;
                var x9=0;
                var x10=0;
                var x11=0;
                var x11=0;
                var x12=0;
                var x13=0;
                var x14=0;
                var x15=0;
                var x16=0;
                var x17=0;
                var x18=0;
                var x19=0;
                //x1=parseFloat(this.datos.registro_venta);
                x2=parseFloat(this.datos.apertura);
                // x3=x1+x2;

                // x4=parseFloat(this.datos.gastos);
                // x13=parseFloat(this.datos.gastos_deposito);
                // x5=parseFloat(this.datos.registro_compra);
                // x6=x4+x5+x13;

                // x7=x3-x6;
                x8=(this.datos.doscientos*200)+(this.datos.cien*100)+(this.datos.cincuenta*50)+(this.datos.veinte*20)+
                    (this.datos.diez*10)+(this.datos.cinco*5)+(this.datos.dos*2)+(this.datos.uno*1)+
                    (this.datos.cerocinco*0.5)+(this.datos.ceroveinte*0.2)+(this.datos.cien_dolar*700);
                

                //total ingreso efectivo
                x10=isNaN(parseFloat(this.ventaefectivo.total_e)) ? 0 : parseFloat(this.ventaefectivo.total_e);
                x11=isNaN(parseFloat(this.ventaDeposito.total_d)) ? 0 : parseFloat(this.ventaDeposito.total_d);
                x12=isNaN(parseFloat(this.ventaCobrarefectivo.total_e)) ? 0 : parseFloat(this.ventaCobrarefectivo.total_e);
                x13=isNaN(parseFloat(this.ventaCobrarDeposito.total_d)) ? 0 : parseFloat(this.ventaCobrarDeposito.total_d);


                //gasto efectivo y deposito
                x14 =  isNaN(parseFloat(this.gastoEfec.total_e)) ? 0 : parseFloat(this.gastoEfec.total_e);
                x15 =  isNaN(parseFloat(this.gastoDep.total_d)) ? 0 : parseFloat(this.gastoDep.total_d);

                //total compra efectivo y deposito
                x16 =  isNaN(parseFloat(this.compraefectivo.total_e)) ? 0 : parseFloat(this.compraefectivo.total_e);
                x17 =  isNaN(parseFloat(this.compraDeposito.total_d)) ? 0 : parseFloat(this.compraDeposito.total_d);

                x18 =  isNaN(parseFloat(this.compraCobrarefectivo.total_e)) ? 0 : parseFloat(this.compraCobrarefectivo.total_e);
                x19 =  isNaN(parseFloat(this.compraCobrarDeposito.total_d)) ? 0 : parseFloat(this.compraCobrarDeposito.total_d);


                
                

                //total egreso efectivo
                // x14=parseFloat(this.datos.total_contado_compra);
                // x15=parseFloat(this.datos.total_credito_compra);
                // x17=parseFloat(this.datos.gastos);
                // x16= x14+x15+x17;


                this.datos.total=x3;
                this.datos.saldo_sistema=x6;
                this.datos.saldo_efectivo=x7;
                this.datos.total_ingreso_efectivo=x12;
               // this.datos.total_egreso_efectivo=x16;

                this.datos.total_contado = x10;
                this.datos.total_contado_deposito = x11;
                this.datos.total_credito = x12
                this.datos.total_credito_deposito = x13;
                this.datos.registro_venta = x10 + x11 + x12 + x13 + x2;
                this.datos.total_ingreso_efec = x2 + x10 + x12;
                this.datos.gastos = x14;
                this.datos.gastos_deposito = x15;
                this.datos.total_contado_compra = x16;
                this.datos.total_contado_deposito_compra = x17;
                this.datos.total_credito_compra = x18;
                this.datos.total_credito_deposito_compra = x19;
                this.datos.total_egreso_efectivo = x14 + x15 + x16 + x17 + x18 + x19;
                this.datos.gasto_efectivo2 = x14 + x16 + x18;
                this.datos.saldo_total =  parseFloat(this.datos.total_ingreso_efec) - parseFloat(this.datos.gasto_efectivo2);
                this.total_efec=x8;

                
                this.datos.diferencia=  x8 - parseFloat(this.datos.saldo_total);
                
                return this.datos,this.total_efec;
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarCajaAbierta;
            },
            registrosimportacion(){
                let me=this;
                var url='/importacion/cantidad';
                axios.get(url).then(function(response){
                    me.importacion_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            listarCajaAbierta(){
                let me = this;
                var url='/arqueo_usuario';
                axios.get(url).then(function(response){
                    me.arrayCaja= response.data;
                    me.datos.grupo = me.arrayCaja[0].grupo;

                    me.arrayCaja.forEach(
                        item => {
                            var t_v_efectivo = isNaN(parseFloat(me.ventaefectivo.total_e)) ? 0 : parseFloat(me.ventaefectivo.total_e);
                            var t_v_deposito = isNaN(parseFloat(me.ventaDeposito.total_d)) ? 0 : parseFloat(me.ventaDeposito.total_d);
                            var t_v_c_efectivo = isNaN(parseFloat(me.ventaCobrarefectivo.total_e)) ? 0 : parseFloat(me.ventaCobrarefectivo.total_e);
                            var t_v_c_deposito = isNaN(parseFloat(me.ventaCobrarDeposito.total_d)) ? 0 : parseFloat(me.ventaCobrarDeposito.total_d);

                            var t_c_efectivo = isNaN(parseFloat(me.compraefectivo.total_e)) ? 0 : parseFloat(me.compraefectivo.total_e);
                            var t_c_deposito = isNaN(parseFloat(me.compraDeposito.total_d)) ? 0 : parseFloat(me.compraDeposito.total_d);
                            var t_c_c_efectivo = isNaN(parseFloat(me.compraCobrarefectivo.total_e)) ? 0 : parseFloat(me.compraCobrarefectivo.total_e);
                            var t_c_c_deposito = isNaN(parseFloat(me.compraCobrarDeposito.total_d)) ? 0 : parseFloat(me.compraCobrarDeposito.total_d);

                            var t_g_efectivo = isNaN(parseFloat(me.gastoEfec.total_e)) ? 0 : parseFloat(me.gastoEfec.total_e);
                            var t_g_deposito = isNaN(parseFloat(me.gastoDep.total_d)) ? 0 : parseFloat(me.gastoDep.total_d);

                            var t_v_efectivo = isNaN(parseFloat(me.ventaefectivo.total_e)) ? 0 : parseFloat(me.ventaefectivo.total_e);
                            var t_v_deposito = isNaN(parseFloat(me.ventaDeposito.total_d)) ? 0 : parseFloat(me.ventaDeposito.total_d);


                            //console.log(t_efectivo,t_deposito);
                            item.total_ingreso_general = t_v_efectivo + t_v_deposito + t_v_c_efectivo + t_v_c_deposito;
                            item.total_ingreso_efectivo = t_v_efectivo + t_v_c_efectivo;
                            item.total_egreso_general = t_c_efectivo + t_c_deposito + t_g_efectivo + t_g_deposito + t_c_c_efectivo + t_c_c_deposito;
                            item.total_egreso_efectivo = t_g_efectivo + t_c_efectivo + t_c_c_efectivo;
                            item.total_neto = (parseFloat(item.apertura) + item.total_ingreso_efectivo) -item.total_egreso_efectivo;
                           
                            // me.arrayArticulo.forEach( item2 => {item2.id == item.id_articulo ?  (item2.stock=item2.stock-me.cantidad_producto, console.log('Prueba')) : ''})
                        });

                })
                .catch(function(error){
                    console.log(error);
                });
            },
            traerArqueo(){
                let me = this;
                var url='/arqueo2';
                axios.get(url).then(function(response){
                    me.arrayArqueo= response.data;

                    me.datos.id=parseFloat(me.arrayArqueo[0].id);
                    //me.datos.registro_venta=parseFloat(me.arrayArqueo[0].registro_venta);
                    me.datos.apertura=parseFloat(me.arrayArqueo[0].apertura);
                    // me.datos.gastos=parseFloat(me.arrayArqueo[0].gastos);
                    me.datos.registro_compra=parseFloat(me.arrayArqueo[0].registro_compra);

                    // me.datos.total_contado=parseFloat(me.arrayArqueo[0].total_contado);
                    // me.datos.total_contado_deposito=parseFloat(me.arrayArqueo[0].total_contado_deposito);
                    // me.datos.total_credito=parseFloat(me.arrayArqueo[0].total_credito);
                    // me.datos.total_credito_deposito=parseFloat(me.arrayArqueo[0].total_credito_deposito);

                    // me.datos.total_contado_compra=parseFloat(me.arrayArqueo[0].total_contado_compra);
                    // me.datos.total_credito_compra=parseFloat(me.arrayArqueo[0].total_credito_compra);

                    // me.datos.total_contado_deposito_compra=parseFloat(me.arrayArqueo[0].total_contado_deposito_compra);
                    // me.datos.total_credito_deposito_compra=parseFloat(me.arrayArqueo[0].total_credito_deposito_compra);

                    // me.datos.gastos_deposito=parseFloat(me.arrayArqueo[0].gastos_deposito);


                    // if(isNaN(me.datos.total_contado)){
                    // me.datos.total_contado = 0;
                    // }
                    // if(isNaN(me.datos.total_contado_deposito)){
                    // me.datos.total_contado_deposito = 0;
                    // }
                    // if(isNaN(me.datos.total_credito)){
                    // me.datos.total_credito = 0;
                    // }
                    // if(isNaN(me.datos.total_credito_deposito)){
                    // me.datos.total_credito_deposito = 0;
                    // }

                    // if(isNaN(me.datos.total_contado_compra)){
                    // me.datos.total_contado_compra = 0;
                    // }
                    // if(isNaN(me.datos.total_credito_compra)){
                    // me.datos.total_credito_compra = 0;
                    // }
                    // if(isNaN(me.datos.total_contado_deposito_compra)){
                    // me.datos.total_contado_deposito_compra = 0;
                    // }
                    // if(isNaN(me.datos.total_credito_deposito_compra)){
                    // me.datos.total_credito_deposito_compra = 0;
                    // }


                    // if(isNaN(me.datos.gastos_deposito)){
                    // me.datos.gastos_deposito = 0;
                    // }


                    me.calcularArqueo();
                })
                .catch(function(error){
                    console.log(error);
                });
            }, 
            ocultarListado1(){
                this.listado=0;
            }, 

            aperturarCaja(){
                this.validarCajaAbierta();
                if(this.datos.apertura < 0)
                {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'El monto no puede ser negativo...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 

                }else{
                    if(this.is_busy==0){
                        if(this.errorimportacion==0){
                            let me = this;
                            axios.post('/arqueo/guardar',this.datos).then(function(response){
                                Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Registro agregado...',
                                showConfirmButton: false,
                                timer: 1500
                            }) 
                            me.cerrarModal();
                            me.listarCajaAbierta(); 
                            me.datos.apertura=0;     
                            })
                            .catch(function(error){
                                console.log(error);
                            });
                        }
                    }
                    this.is_busy=1;
               } 
            }, 
            // aperturarC(){
            //     if(this.is_busy == 0){
            //         this.aperturarCaja();
            //         this.is_busy=1;
            //     }
            // },
            // async aperturarCaja(){

            //     try {

            //         //this.estadoCaja();
            //         this.listarCajaAbierta();
            //         this.validarCajaAbierta();

            //         let me = this;
            //         var url='/arqueo/estado';
            //         const res = await axios.get(url)
            //         me.estado_caja= res.data;

            //         console.log(me.arrayCaja);

            //         if(this.arrayCaja.length>0)
            //         {
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Ya Existe una Caja Abierta...',
            //                 text: 'Cierre Caja!'
            //             })
            //         }else{
            //             if(this.errorimportacion==0){
            //                 //let me = this;
            //                 axios.post('/arqueo/guardar',this.datos).then(function(response){
            //                     Swal.fire({
            //                     position: 'top-end',
            //                     icon: 'success',
            //                     title: 'Registro agregado...',
            //                     showConfirmButton: false,
            //                     timer: 1500
            //                 })
            //                 // me.cerrarModal();
            //                 me.listarCajaAbierta();
            //                 me.datos.apertura=0;
            //                 })
            //                 .catch(function(error){
            //                     console.log(error);
            //                 });
            //             }
            //         }
            //     } catch (error) {
            //         if(error.response.data){
            //             this.errores=error.response.data.errors;
            //             this.is_busy=0;
            //         }
            //     }


            // },
            Limpiar(){
                this.datos ={
                    id : 0,
                    fecha_apertura : '',
                    fecha_cierre : '',
                    doscientos : 0,
                    cien : 0,
                    cincuenta : 0,
                    veinte : 0,
                    diez : 0,
                    cinco : 0,
                    dos : 0,
                    uno : 0,
                    cerocinco : 0,
                    ceroveinte : 0,
                    cien_dolar : 0,
                    registro_venta : 0,
                    alojamiento : 0,
                    apertura : 0,
                    total : 0,
                    gastos : 0,
                    registro_compra : 0,
                    saldo_sistema : 0,
                    saldo_efectivo : 0,
                    diferencia : 0,
                    id_usuario : 0,
                    estado : 'abierta',
                },   
                this.total_efec=0;
                this.listado=0,
                this.variable=0,                            
                this.arrayimportacion = [],
                this.arrayCaja = [],
                this.arrayArqueo = []
            },  
            guardarimportacion(){
                if(this.validarCajaAbierta()){
                    return;
                }
                let me = this;
                axios.post('/importacion/guardar',this.datos).then(function(response){
                    if(response.data.error==0){
                        Swal.fire({
                            icon: 'error',
                            title: 'Este Nombre ya existe...',
                            text: 'Debe usar otro Nombre!'
                        })
                    }
                    else{
                        Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.cerrarModal();
                    me.listarCajaAbierta(); 
                    me.registrosimportacion();   
                    }   
                })
                .catch(function(error){
                    console.log(error);
                });
            },   
            // cerrarCaja(){
            //     let me = this;
            //     axios.put('/arqueo/modificar',this.datos).then(function(response){
            //         Swal.fire({
            //             position: 'top-end',
            //             icon: 'success',
            //             title: 'Caja Cerrada Exitosamente',
            //             showConfirmButton: false,
            //             timer: 1500
            //         }) 
            //         me.cerrarModal();
            //         me.Limpiar();
            //         me.listarCajaAbierta();                    
            //     }).catch(function(error){
            //         console.log(error);
            //     });
            // },   
            cerrarCaja(){
                let me = this;

                if (me.datos.doscientos < 0) {
                    this.validacionError('200 No se permite monto negativo',3000);
                }
                else if (me.datos.cien < 0){
                    this.validacionError('100 No se permite monto negativo',3000);
                }
                else if (me.datos.cincuenta < 0) {
                    this.validacionError('50 No se permite monto negativo',3000);
                }
                else if (me.datos.veinte < 0) {
                    this.validacionError('20 No se permite monto negativo',3000);
                }
                else if (me.datos.diez < 0) {
                    this.validacionError('10 No se permite monto negativo',3000);
                }
                else if (me.datos.cinco < 0) {
                    this.validacionError('5 No se permite monto negativo',3000);
                }
                else if (me.datos.dos < 0) {
                    this.validacionError('2 No se permite monto negativo',3000);
                }
                else if (me.datos.uno < 0) {
                    this.validacionError('1 No se permite monto negativo',3000);
                }
                else if (me.datos.cerocinco < 0) {
                    this.validacionError('0.50 No se permite monto negativo',3000);
                }
                else if (me.datos.ceroveinte < 0) {
                    this.validacionError('0.2 No se permite monto negativo',3000);
                }
                else if (me.datos.cien_dolar < 0) {
                    this.validacionError('100 Dólares No se permite monto negativo',3000);
                }
                else {
                    this.cerrar();
                }

            },
            validacionError(nombreError,tiempo){
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: tiempo,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                    })

                    Toast.fire({
                    icon: 'error',
                    title: '<span style="color: red; font-size: 15px">ERROR!!!</div><span style="color: black; font-size: 15px">...' + nombreError +'</span>'
                    })
            },
            cerrar(){
                let me = this;
                axios.put('/arqueo/modificar',this.datos).then(function(response){
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Caja Cerrada Exitosamente',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.actualizarVentasAlCerrarCaja();
                        me.Limpiar();
                        me.listarCajaAbierta();
                    }).catch(function(error){
                        console.log(error);
                    });
            },
            actualizarVentasAlCerrarCaja() {
                axios.post('/arqueo/actualizar_ventas?id_caja=' + this.datos.id).then(function (response) {
                        console.log(response.data.error);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },         
            validarCajaAbierta(){
                this.errorimportacion = 0;
                this.errorMostrarMsjimportacion = [];

                if(this.datos.apertura==0)
                { 
                    Swal.fire({
                        icon: 'error',
                        title: 'El monto no puede estar vacío...',
                        text: 'Ingrese Monto!'
                    })
                    this.errorimportacion=1
                }
                if(this.arrayCaja.length>0)
                { 
                    Swal.fire({
                        icon: 'error',
                        title: 'Ya Existe una Caja Abierta...',
                        text: 'Cierre Caja!'
                    })
                    this.errorimportacion=1
                }
                
                return this.errorimportacion;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.errorimportacion = 0;
                this.datos = {
                    id : 0,
                    nombre : '',
                    descripcion : '',
                    estado : '1',
                }
            },
            btnArqueo(){
                this.is_busy=0;
                if(this.arrayCaja.length>0)
                { 
                    let me = this;
                    me.listado=1;
                    // me.datos = {
                    //     id : 0,
                    //     fecha_apertura : '',
                    //     fecha_cierre : '',
                    //     doscientos : 0,
                    //     cien : 0,
                    //     cincuenta : 0,
                    //     veinte : 0,
                    //     diez : 0,
                    //     cinco : 0,
                    //     dos : 0,
                    //     uno : 0,
                    //     cerocinco : 0,
                    //     ceroveinte : 0,
                    //     cien_dolar : 0,
                    //     total : 0,
                    //     gastos : 0,
                    //     saldo_efectivo : 0,
                    //     diferencia : 0,
                    //     id_usuario : 0,
                    // };
                    me.traerArqueo();
                    me.VentaEfectivo();
                    me.VentaDeposito();
                    me.VentaCobrarEfectivo();
                    me.VentaCobrarDeposito();
                    me.GastoEfectivo();
                    me.GastoDeposito();
                    me.CompraEfectivo();
                    me.CompraDeposito();
                    me.CompraCobrarEfectivo();
                    me.CompraCobrarDeposito();
                    
                }
                else
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'No hay ninguna caja abierta para este usuario',
                        text: 'Aperture Caja!'
                    })
                }

            },
            abrirModal(modelo, accion, data=[]){
                this.errorimportacion = 0;
                this.errorMostrarMsjimportacion = [];
                switch(modelo){
                    case "importacion":
                        {
                            switch(accion){
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal='Registro Importación'
                                        this.datos = {
                                            id : 0,
                                            nombre : '',
                                            descripcion : '',
                                            estado : '1',
                                        }                                      
                                        this.tipoAccion = 1;
                                        break;
                                    }
                                case 'modificar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'Modificar Importación';
                                        this.tipoAccion = 2;
                                        this.datos.id = data['id'];
                                        this.datos.nombre = data['nombre'];
                                        this.datos.descripcion = data['descripcion'];
                                        this.datos.estado = data['estado'];
                                       break;
                                    }
                            }
                        }
              }
        
            },
            desactivarimportacion(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Inhabilitar esta Importación??',
                    text: "Puede revertir esta decisión!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Inhabilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/importacion/desactivar',{'id': id}).then(function (response) {
                        me.listarCaja(1,'', 'name');
                        swalWithBootstrapButtons.fire(
                        'Inhabilitado!',
                        'Esta Importación se ha Inhabilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Esta Importación no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            activarimportacion(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Habilitar esta Importación??',
                    text: "Puede revertir esta decisión!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/importacion/activar',{'id': id}).then(function (response) {
                        me.listarCaja(1,'', 'name');
                        swalWithBootstrapButtons.fire(
                        'Habilitado!',
                        'Esta Importación se ha Habilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Esta Importación no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            } 
        },
        mounted() {
            this.listarCajaAbierta();
            this.VentaEfectivo();
            this.VentaDeposito();
            this.VentaCobrarEfectivo();
            this.VentaCobrarDeposito();
            this.GastoEfectivo();
            this.GastoDeposito();
            this.CompraEfectivo();
            this.CompraDeposito();
            this.CompraCobrarEfectivo();
            this.CompraCobrarDeposito();
            this.usuarioAuth();
            this.registrosimportacion();
            this.listarProducto();
            this.listarProductoMeses();
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
    .AA{
        float: left;
        text-align:center;
    }
    .BB{
        float: left;
        text-align:center;
    }
    .cuadro {
        border-width: 1px;
        border-style: solid;
        border-color: black;
    }

    .tt {
        height: 33px;
    }
    #ttt {
        height: 25px;
        width: 100px;
    }
    .pp {
        height: 100%;
    }
    .border-detalle {
        width: 300px;
        height: 580px;
        border: 2px solid orange;
        box-sizing: border-box;
    }
    .border-apertura {
        width: 300px;
        height: 50px;
        border: 2px solid orange;
        box-sizing: border-box;
    }
    .border-registro {
        width: 300px;
        height: 525px;
        border: 2px solid #3399FF;
    }
    .border-efectivo {
        width: 300px;
        height: 200px;
        border: 2px solid #3399FF;
    }
    .size-div{
        width: 35% !important;
    }
</style>
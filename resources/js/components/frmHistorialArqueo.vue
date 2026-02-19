<template>
    <main class="main">
        <!-- <div class="container"> -->
            <!-- falta terminar el solo esta inicio -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                   <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">HISTORIAL ARQUEO DE CAJA</h3></div>
                    <template v-if="listado==0">
                        
                        <div class="form-group row">
                            <div class="col-md-8">
                                
                                &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                    <div>
                                        <input type="date" class="form-control"  v-model="datos.fecha_inicio" @click="listarArqueoCaja(1,buscar,criterio)" @change="listarArqueoCaja(1,buscar,criterio)">  
                                    </div>
                                    &nbsp;&nbsp;&nbsp;
                                    <div>
                                        <input type="date" class="form-control"  v-model="datos.fecha_final" @click="listarArqueoCaja(1,buscar,criterio)" @change="listarArqueoCaja(1,buscar,criterio)">  
                                    </div>
                                    &nbsp;&nbsp;&nbsp;
                                    <select class="form-select col-md-3" v-model="criterio">
                                        <option value="u.name">Responsable</option>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup.enter="listarArqueoCaja(1, buscar, criterio)" @keyup="BuscandoCaja()" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArqueoCaja(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Light table -->
                        <div class="table-responsive">
                        <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                            <thead style="background-color: #46546C">
                                <tr>                      
                                    <th scope="col" class="text-white">Fecha Apertura</th>
                                    <th scope="col" class="text-white">Fecha Cierre</th>
                                    <th scope="col" class="text-white">Monto Apertura</th>
                                    <th scope="col" class="text-white">Total Ingreso General</th>
                                    <th scope="col" class="text-white">Total Ingreso Efectivo</th>
                                    <th scope="col" class="text-white">Total Egresos General</th>
                                    <th scope="col" class="text-white">Total Egresos Efectivo</th>
                                    <th scope="col" class="text-white">Total Neto</th>
                                    <th scope="col" class="text-white">Usuario Responsable</th>
                                    <th scope="col" class="text-white">Estado</th>
                                    <th scope="col" class="text-white">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="caja in arrayArqueo" :key="caja.id">
                                    <td v-text="caja.fecha_apertura"></td>
                                    <td v-text="caja.fecha_cierre"></td>
                                    <td v-text="caja.apertura"></td> 
                                    <td v-text="caja.total_ingreso_general"></td>
                                    <td v-text="caja.total_ingreso_efectivo"></td>
                                    <td v-text="caja.total_egreso_general"></td> 
                                    <td v-text="caja.total_egreso_efectivo"></td> 
                                    <td> {{ ((parseFloat(caja.apertura) + parseFloat(caja.total_ingreso_efectivo)) - parseFloat(caja.total_egreso_efectivo)).toFixed(2)}}</td>
                                    <td v-text="caja.name"></td>  
                                    <td class="text-center">
                                        <template v-if="caja.estado=='Abierta'">
                                            <span class="badge bg-success tamaño text-white">{{caja.estado}}</span>
                                        </template>
                                        <template v-if="caja.estado=='Cerrada'">
                                            <span class="badge bg-danger tamaño text-white">{{caja.estado}}</span>                                           
                                        </template>
                                    </td>
                                    <td>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <template v-if="caja.estado=='Cerrada'">
                                                <li><a class="dropdown-item" href="#" @click="verArqueo(caja)"><i class="fa fa-eye text-success"></i> Ver detalle</a></li>
                                            </template>
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
                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger text-white" @click="volverHistorialListado()">
                                    <i class="fa fa-reply-all"></i>&nbsp;Volver
                                </button>
                            </div>
                            <div class="col-md-4 text-center"><h3 class="mb-0">DETALLE DE CAJA</h3></div>
                            <div class="col-md-4">&nbsp;</div>
                            <!-- <div class="col-md-2">
                                <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt=""> 
                            </div>  -->
                        </div>
                        <div class="card-header text-center line p-0 m-0"  style="background-color: #3399FF"></div>&nbsp;
                        <div class="card-body pt-0">
                            <div class="card-body pt-0">
                        
                        <form class="row pb-4" style="height:60%;width:100%;text-align: center;">
                            <div class="container" style="text-align: center;">
                                <div class="row g-2">
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
                                                            <td><input class="form-control form-control-sm" type="number" v-on:keyup="calcularArqueo()" v-model="datos.doscientos" disabled></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.doscientos*200).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">100</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cien" disabled></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cien*100).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">50</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cincuenta" disabled></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cincuenta*50).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">20</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.veinte" disabled></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.veinte*20).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">10</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.diez" disabled></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.diez*10).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">5</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cinco" disabled></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cinco*5).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">2</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.dos" disabled></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.dos*2).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">1</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.uno" disabled></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.uno*1).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">0.5</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cerocinco" disabled></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cerocinco*0.5).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">0.2</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.ceroveinte" disabled></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.ceroveinte*0.2).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="align-middle">100 $</td>
                                                            <td><input type="number" class="form-control form-control-sm" v-on:keyup="calcularArqueo()" v-model="datos.cien_dolar" disabled></td>
                                                            <td class="align-middle text-center">{{parseFloat(datos.cien_dolar*100*7).toFixed(2)}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th height="188px"></th>
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
                                                            <th scope="col" width="10%" height="100px"></th>
                                                            <th scope="col" width="60%" class="text-info">Apertura Caja</th>
                                                            <th scope="col" width="30%" class="text-info">{{datos.apertura}}</th>
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
                                                            <th class="">Total Ingreso General</th>
                                                            <th class="">{{(datos.total).toFixed(2)}} </th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th class="">Total Ingreso Efectivo</th>
                                                            <th class="">{{datos.total_ingreso_efectivo}} </th>
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
                                                        <tr>
                                                            <th></th>
                                                            <th >Total Egresos General</th>
                                                            <th >{{datos.total_egreso}}</th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th >Total Egresos Efectivo</th>
                                                            <th >{{datos.total_egreso_efectivo}}</th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th>Saldo Efectivo</th>
                                                            <th>{{(datos.saldo_efectivo).toFixed(2)}}</th>
                                                        </tr>
                                                        <tr>
                                                            <th></th>
                                                            <th>Diferencia</th>
                                                            <th>{{datos.diferencia}}</th>
                                                        </tr>

                                                    </thead>
                                                </table>
                                                <div class="p-2 mb-2 border border-warning">
                                                    <h5 class="mx-4 px-4 my-1">Total Efectivo Entregado: {{total_efec}}</h5>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                            <div class="header-divider"></div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==1"  type="button" @click="guardarcliente()">Guardar cliente</button>
                                <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==2"  type="button" @click="modificarcliente()">Modificar cliente</button>
                            </div>
                            </div>
                        </div>
                    </template>
                    
                </div>
                </div>
            </div>

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
                    total_egreso:0,
                    gastos_deposito : 0,


                    fecha_inicio: moment().format('YYYY-MM-DD'),
                    fecha_final: moment().format('YYYY-MM-DD'),
                },
                total_efec:0,  
                arrayArqueo : [],
                arrayDetalle : [],
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
                criterio : 'u.name',
                buscar : '',
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
            listarArqueoCaja(page, buscar, criterio){
                let me=this;
                var url='/arqueo?fecha_inicio=' + me.datos.fecha_inicio +'&fecha_final=' + me.datos.fecha_final +'&page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayArqueo=response.data.data;
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
            listarArticuloBusquedaRapida(){
                let me=this;
                var url='/arqueo?fecha_inicio=' + me.datos.fecha_inicio +'&fecha_final=' + me.datos.fecha_final +'&page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayArqueo=response.data.data;
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
            BuscandoCaja(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticuloBusquedaRapida,350)
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
                x1=parseFloat(this.datos.registro_venta);
                x2=parseFloat(this.datos.apertura);
                x3=x1+x2;

                x4=parseFloat(this.datos.gastos);
                x13=parseFloat(this.datos.gastos_deposito);
                x5=parseFloat(this.datos.registro_compra);
                x6=x4+x5+x13;

                x7=x3-x6;
                x8=(this.datos.doscientos*200)+(this.datos.cien*100)+(this.datos.cincuenta*50)+(this.datos.veinte*20)+
                    (this.datos.diez*10)+(this.datos.cinco*5)+(this.datos.dos*2)+(this.datos.uno*1)+
                    (this.datos.cerocinco*0.5)+(this.datos.ceroveinte*0.2)+(this.datos.cien_dolar*700);
                x9=x8-x7;

                //total ingreso efectivo
                x10=parseFloat(this.datos.total_contado);
                x11=parseFloat(this.datos.total_credito);
                x12= x10+x11;

                //total egreso efectivo
                x14=parseFloat(this.datos.total_contado_compra);
                x15=parseFloat(this.datos.total_credito_compra);
                x17=parseFloat(this.datos.gastos);
                x16= x14+x15+x17;


                this.datos.total=x3;
                this.datos.saldo_sistema=x6;
                //this.datos.saldo_efectivo=x7;
                // this.datos.diferencia=x9;
                this.total_efec=x8;
                // this.datos.total_ingreso_efectivo=x12;
                this.datos.total_egreso_efectivo=x16;
                return this.datos,this.total_efec;
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarArqueoCaja(page, buscar, criterio);
            },
            volverHistorialListado(){
                let me = this;
                me.listado = 0;
                me.limpiarArqueoCaja();
                me.criterio = 'u.name',
                me.buscar = '';
                me.datos.fecha_inicio = moment().format('YYYY-MM-DD');
                me.datos.fecha_final = moment().format('YYYY-MM-DD');
            },
            verArqueo(data=[]){
                let me = this;
                me.listado = 1;
                me.datos.id = data['id'];
                me.datos.fecha_apertura = data['fecha_apertura'];
                me.datos.fecha_cierre = data['fecha_cierre'];
                me.datos.doscientos = data['doscientos'];
                me.datos.cien = data['cien'];
                me.datos.cincuenta = data['cincuenta'];
                me.datos.veinte = data['veinte'];
                me.datos.diez = data['diez'];
                me.datos.cinco = data['cinco'];
                me.datos.dos = data['dos'];
                me.datos.uno = data['uno'];
                me.datos.cerocinco = data['cerocinco'];
                me.datos.ceroveinte = data['ceroveinte'];
                me.datos.cien_dolar = data['cien_dolar'];
                me.datos.registro_venta =  parseFloat(data['ingreso']);
                me.datos.apertura = data['apertura'];
                //me.datos.total = parseFloat(data['total_ingreso']);
                me.datos.gastos = data['gastos'];
                me.datos.registro_compra = data['registro_compra'];
                //me.datos.saldo_sistema = parseFloat(data['egreso']);
                me.datos.saldo_efectivo = parseFloat(data['saldo_efectivo']);
                me.datos.diferencia = parseFloat(data['diferencia']);
                me.datos.id_usuario = data['id_usuario'];
                me.datos.estado = data['estado'];

                me.datos.total_contado = data['total_contado'];
                me.datos.total_contado_deposito = data['total_contado_deposito'];
                me.datos.total_credito = data['total_credito'];
                me.datos.total_credito_deposito = data['total_credito_deposito'];
                me.datos.total_contado_compra = data['total_contado_compra'];
                me.datos.total_contado_deposito_compra = data['total_contado_deposito_compra'];
                me.datos.total_credito_compra = data['total_credito_compra'];
                me.datos.total_credito_deposito_compra = data['total_credito_deposito_compra'];
                me.datos.total_ingreso_efectivo= data['total_ingreso_efectivo1'];
                
                me.datos.gastos_deposito = data['gastos_deposito'];
                me.datos.total_egreso = data['total_egreso_general'];
                me.datos.diferencia=data['diferencia'];

                
                me.total_efec = (data['doscientos']*200)+(data['cien']*100)+(data['cincuenta']*50)+(data['veinte']*20)+(data['diez']*10)+(data['cinco']*5)+(data['dos']*2)+(data['uno']*1)+(data['cerocinco']*0.5)+(data['ceroveinte']*0.20)+(data['cien_dolar']*100*7);
                me.calcularArqueo();
            },
            // cargarPdf(id,foto) {
            //     axios.get('/compra/pdfCompraGeneral?id=' + id  + '&foto='+ foto,{responseType: 'blob'})
            //         .then(response => {
            //             var blob = new Blob([response.data], {type: 'application/pdf'});
            //             var downloadUrl = URL.createObjectURL(blob);
            //             window.open(downloadUrl, '_blank');
            //         })
            //         .catch(error => {
            //             console.log(error);
            //         })
            // },
            limpiarArqueoCaja(){
                this.datos = {
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
                }
                this.criterio = 'u.name',
                this.buscar = ''
            }, 
        },
        mounted() {
            this.listarArqueoCaja(1, this.buscar, this.criterio);

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
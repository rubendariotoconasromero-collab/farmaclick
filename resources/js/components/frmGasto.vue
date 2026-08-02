<template>
    <main class="main">
        <expense-registry-workspace
            :rows="arrayGasto"
            :reasons="arrayTipoMotivoGasto"
            :payment-forms="arrayForma"
            :datos="datos"
            :reason-data="datosMotivoGasto"
            :expense-count="gasto_registro"
            :reason-count="motivo_registro"
            :modal="modal"
            :reason-modal="quickReasonModal"
            :action="tipoAccion"
            :pagination="pagination"
            :pages="pagesNumber"
            :search="buscar"
            :criterion="criterio"
            :server-errors="errores"
            :validation-errors="errorMostrarMsjGasto"
            :saving="isSaving"
            @update:search="buscar = $event"
            @update:criterion="criterio = $event"
            @search="listarGasto(1, buscar, criterio)"
            @page="cambiarPagina($event, buscar, criterio)"
            @create="abrirModal('gasto', 'registrar')"
            @edit="abrirModal('gasto', 'modificar', $event)"
            @close="cerrarModal"
            @save="guardarGasto"
            @update="modificarGasto"
            @calculate-deposit="cambiarDeposito"
            @create-reason="quickReasonModal = true"
            @close-reason="quickReasonModal = false"
            @save-reason="guardarMotivoGasto"
        />
        <template v-if="false">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                    <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">GASTO</h3>
                    </div>
                    <!-- prueba card -->
                    <div class="row mt-2" style='width:90%;margin-left: 1.5%'>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card mb-4" style="--cui-card-cap-bg: #FF0000">
                            <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                            <strong> GASTO </strong>
                            </div>
                            <div class="card-body row text-center">
                                <div class="col">
                                        <div class="avatar avatar-md"><img class="avatar-img" src="img/sit_norte/icons8-producto-48.png"></div>
                                    </div>
                                <div class="col">
                                    <div class="fs-5 fw-semibold">{{gasto_registro}}</div>
                                    <div class="text-uppercase text-medium-emphasis small">Registros</div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <!-- /.col-->
                        <div class="col-sm-6 col-lg-3">
                        <div class="card mb-4" style="--cui-card-cap-bg: #0E91FF">
                            <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                            <strong> MOTIVO GASTO </strong>
                            </div>
                            <div class="card-body row text-center">
                            <div class="col">
                                <div class="fs-5 fw-semibold">{{motivo_registro}}</div>
                                <div class="text-uppercase text-medium-emphasis small">Registros</div>
                            </div>
                            <div class="vr"></div>
                            <div class="col">
                                <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#frmMotivoGasto">
                                    <i  class="icon-plus"></i>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                        new
                                        <span class="visually-hidden">unread messages</span>
                                    </span>
                                </button>
                            </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <!-- prueba fin card -->
                    <div class="card-header">
                        <button type="button" @click="abrirModal('gasto','registrar')" class="btn btn-info text-white" style='margin-left: 1.2%'>
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-8">
                            &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                <div class="col-md-4">
                                    <select class="form-select" v-model="criterio">
                                        <option value="motivo_gasto.nombre">Motivo Gasto</option>
                                        <option value="gasto.monto">Monto</option>
                                    </select>
                                </div>
                                &nbsp;&nbsp;&nbsp;
                                <input type="text" v-model="buscar" @keyup.enter="listarGasto(1, buscar, criterio)" @keyup="BuscandoGasto()" class="form-control" placeholder="Texto a buscar">
                                &nbsp;&nbsp;&nbsp;
                                <button type="submit" @click="listarGasto(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
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
                                <th scope="col" class="text-white">Motivo Gasto</th>
                                <th scope="col" class="text-white">Monto</th>
                                <th scope="col" class="text-white">Forma Pago</th>
                                <th scope="col" class="text-white">Descripcion</th>
                                <th scope="col" class="text-white">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="gasto in arrayGasto" :key="gasto.id">
                                <td v-text="gasto.fecha"></td>
                                <td v-text="gasto.motivo"></td>
                                <td v-text="gasto.monto"></td>
                                <td v-text="gasto.forma"></td>
                                <td v-text="gasto.descripcion"></td>      
                                <td>
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" @click="abrirModal('gasto','modificar',gasto)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
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
                </div>
                </div>
            </div>
        <!-- </div> -->
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' :modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="cerrarModal()"></button>
                    </div>

                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="row" :class="errores.id_motivo_gasto ? 'mb-1' : 'mb-3'">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Motivo</label>
                                <div class="col-sm-10">
                                    <select class="form-select" v-model="datos.id_motivo_gasto">
                                        <option value="0" disabled>Seleccione el Motivo Gasto</option>
                                        <option v-for="motivo_gasto in arrayTipoMotivoGasto" :key="motivo_gasto.id" :value="motivo_gasto.id" v-text="motivo_gasto.nombre"></option>
                                    </select>
                                    <div class="row mb-2" v-if="errores.id_motivo_gasto">
                                        <div class="col-sm-10"><span class="text-danger">{{errores.id_motivo_gasto[0]}}</span></div>
                                    </div>
                                </div>
                            </div> 
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Forma Pago</label>
                                <div class="col-sm-10">
                                    <select class="form-select" v-model="datos.id_forma_pago">
                                        <option value="0" disabled>Seleccione el Motivo Gasto</option>
                                        <option v-for="forma in arrayForma" :key="forma.id" :value="forma.id" v-text="forma.nombre"></option>
                                    </select>
                                </div>
                            </div> 
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Fecha</label>
                                <div class="col-sm-10"><input type="date" class="form-control" v-model="datos.fecha" placeholder="Nombre del Personal"></div>
                            </div>
                            <div class="row" :class="errores.monto ? 'mb-1' : 'mb-3'">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Monto</label>
                                <div class="col-sm-10"><input type="number" class="form-control" v-model="datos.monto"></div>
                            </div>
                            <div class="row mb-2" v-if="errores.monto">
                                <div class="col-sm-2">&nbsp;</div>
                                <div class="col-sm-10"><span class="text-danger">{{errores.monto[0]}}</span></div>
                            </div>

                            <template v-if="datos.id_forma_pago == 6">
                                <div class="row mb-3">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Efectivo</label>
                                    <div class="col-sm-10"><input type="number" class="form-control" v-model="datos.efectivo" @keyup="cambiarDeposito()"></div>
                               </div>
                               <div class="row mb-3">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Deposito</label>
                                    <div class="col-sm-10"><input type="number" class="form-control" v-model="datos.deposito" ></div>
                               </div>
                            </template>

                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.descripcion"></div>
                            </div>
                            <div v-show="errorGasto" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjGasto" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-info text-white" @click="guardarGasto()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-info text-white" @click="modificarGasto()">Modificar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->   

        <!--Modal Formulario Cargo-->
        <div class="modal fade" id="frmMotivoGasto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white">
                        <h4 class="modal-title ">Registro Motivo Gasto</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nombre</label>
                                <div><input type="text" class="form-control" v-model="datosMotivoGasto.nombre"></div>
                            </div>
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
                                <textarea class="form-control" id="message-text" v-model="datosMotivoGasto.descripcion"></textarea>
                                <!-- <div class="col-sm-10"><input type="text" class="form-control" v-model="datosMotivoGasto.descripcion"></div> -->
                            </div>
                            <div v-show="errorGasto" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjGasto" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-info text-white" @click="guardarMotivoGasto() ">Guardar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Cargo-->
        </template>
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
                    fecha : moment().format('YYYY-MM-DD'),
                    monto : '',
                    descripcion : '',
                    id_motivo_gasto : 0,
                    id_forma_pago : 2,
                    efectivo:0,
                    deposito:0,
                    monto_aux:0,
                    efectivo_aux:0,
                    deposito_aux:0,
                    id_forma_pago_aux:0,
                },
                datosMotivoGasto : {
                    id : 0,
                    nombre : '',
                    descripcion : '',
                }, 
                motivo_registro :0,
                gasto_registro :0,                                
                arrayGasto : [],
                arrayForma : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorGasto : 0,
                errores: {},
                errorMostrarMsjGasto : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'motivo_gasto.nombre',
                buscar : '',
                arrayTipoMotivoGasto : [],
                setTimeoutBuscador: '',
                quickReasonModal: false,
                isSaving: false,
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
            cambiarDeposito(){
                let me = this;
                me.datos.deposito = me.datos.monto - me.datos.efectivo;
            },
            listarGasto(page, buscar, criterio){
                let me=this;
                var url='/gasto?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayGasto=response.data.data;
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
            listarGastoBusquedaRapida(){
                let me=this;
                var url='/gasto?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayGasto=response.data.data;
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
            BuscandoGasto(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarGastoBusquedaRapida,500)
            },
            registrosMotivoGasto(){
                let me=this;
                var url='/motivo_gasto/cantidad';
                axios.get(url).then(function(response){
                    me.motivo_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            registrosGasto(){
                let me=this;
                var url='/gasto/cantidad';
                axios.get(url).then(function(response){
                    me.gasto_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            selectMotivoGasto(){
                let me=this;
                var url='/motivo_gasto/selectMotivoGasto';
                axios.get(url).then(function(response){
                    me.arrayTipoMotivoGasto=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectForma(){
                let me=this;
                var url='/formaPago/selectFormaPago2';
                axios.get(url).then(function(response){
                    me.arrayForma=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarGasto(page, buscar, criterio);
            },
            /* guardarGasto(){
                if(this.validarGasto()){
                    return;
                }
                let me = this;
                axios.post('/gasto/guardar',this.datos).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.cerrarModal();
                    me.listarGasto(1, '', 'motivo_gasto.nombre');
                    me.registrosGasto();      
                })
                .catch(function(error){
                    console.log(error);
                });
            }, */
            async guardarGasto(){
                if (this.validarGasto()) return;
                this.isSaving = true;
                try {
                    let me = this;
                    console.log(this.datos);
                    let data = {
                        descripcion: me.datos.descripcion,
                        fecha: me.datos.fecha,
                        monto: me.datos.monto,
                        id: me.datos.id,
                        id_motivo_gasto: me.datos.id_motivo_gasto > 0 ? me.datos.id_motivo_gasto : '',
                        id_forma_pago: me.datos.id_forma_pago > 0 ? me.datos.id_forma_pago : '',
                        efectivo: me.datos.efectivo,
                        deposito: me.datos.deposito,
                    }
                    const res = await axios.post('/gasto/guardar',data);
                    me.cerrarModal();
                    me.registrosGasto();
                    me.listarGasto(1, '', 'motivo_gasto.nombre');
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    })
                } catch (error) {
                    if(error.response && error.response.data){
                        this.errores=error.response.data.errors;
                    }
                } finally {
                    this.isSaving = false;
                }
                
            },
            guardarMotivoGasto(){
            //   if(this.validarGasto()){
            //         return;
            //     }
                let me = this;
                axios.post('/motivo_gasto/guardar',this.datosMotivoGasto).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.registrosMotivoGasto();  
                    me.limpiarDatosMotivo();
                    me.selectMotivoGasto();
                    me.quickReasonModal = false;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            limpiarDatosMotivo()
            {
                    this.datosMotivoGasto = {
                    id : 0,
                    nombre : '',
                    descripcion : '',
                }
            },
            modificarGasto(){
                if(this.validarGasto()){
                    return;
                }
                let me = this;
                me.isSaving = true;
                axios.put('/gasto/modificar',this.datos).then(function(response){
                     Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro modificado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.cerrarModal();
                    me.listarGasto(1,'', 'motivo_gasto.nombre');
                    me.isSaving = false;
                }).catch(function(error){
                    me.isSaving = false;
                    console.log(error);
                });
            },         
            validarGasto(){
                this.errorGasto = 0;
                this.errorMostrarMsjGasto = [];

                if(!this.datos.id_motivo_gasto || Number(this.datos.id_motivo_gasto) === 0) this.errorMostrarMsjGasto.push("Seleccione un motivo de gasto");
                if(!this.datos.id_forma_pago || Number(this.datos.id_forma_pago) === 0) this.errorMostrarMsjGasto.push("Seleccione una forma de pago");
                if(!this.datos.monto || Number(this.datos.monto) <= 0) this.errorMostrarMsjGasto.push("El monto debe ser mayor a cero");
                if(Number(this.datos.id_forma_pago) === 6 && Number(this.datos.efectivo) > Number(this.datos.monto)) this.errorMostrarMsjGasto.push("El efectivo no puede superar el monto total");
                if(this.errorMostrarMsjGasto.length) this.errorGasto=1;
                return this.errorGasto;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.errorGasto = 0;
                this.datos = {
                    id : 0,
                    fecha : '',
                    motivo : '',
                    descripcion : '',
                    id_forma_pago : 2,
                    efectivo:0,
                    deposito:0,
                }
                this.errores= {}
            },
            abrirModal(modelo, accion, data=[]){
                this.errorGasto = 0;
                this.errorMostrarMsjGasto = [];
                this.selectForma();
                this.selectMotivoGasto();
                switch(modelo){
                    case "gasto":
                        {
                            switch(accion){
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal='Registro Gasto'
                                        this.datos = {
                                            id : 0,
                                            fecha : moment().format('YYYY-MM-DD'),
                                            monto : '',
                                            descripcion : '',
                                            id_motivo_gasto: 0,
                                            id_forma_pago : 2,
                                            efectivo:0,
                                            deposito:0,
                                        }                                      
                                        this.tipoAccion = 1;
                                        break;
                                    }
                                case 'modificar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'Modificar Gasto';
                                        this.tipoAccion = 2;
                                        this.datos.id = data['id'];
                                        this.datos.fecha = data['fecha'];
                                        this.datos.monto = data['monto'];
                                        this.datos.descripcion = data['descripcion'];
                                        this.datos.id_motivo_gasto = data['id_motivo_gasto'];
                                        this.datos.id_forma_pago = data['id_forma_pago'];
                                        this.datos.id_forma_pago_aux = data['id_forma_pago'];

                                        this.datos.efectivo = data['efectivo'];
                                        this.datos.deposito = data['deposito'];
                                        this.datos.efectivo_aux = data['efectivo'];
                                        this.datos.deposito_aux = data['deposito'];
                                        this.datos.monto_aux = data['monto'];

                                        //this.datos.id_motivo_gasto = data['id_motivo_gasto'];
                                       break;
                                    }
                            }
                        }
              }
        
            },
        },
        mounted() {
            this.listarGasto(1, this.buscar, this.criterio);
            this.registrosMotivoGasto();
            this.registrosGasto();
        }
    }
</script>
<style scoped>
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

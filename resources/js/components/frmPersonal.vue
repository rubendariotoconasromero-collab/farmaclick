<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                    <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">PERSONAL</h3>
                    </div>
                    <!-- prueba card -->
                    <div class="row mt-2" style='width:90%;margin-left: 1.5%'>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card mb-4" style="--cui-card-cap-bg: #00C29D">
                            <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                            <strong> PERSONAL </strong>
                            </div>
                            <div class="card-body row text-center">
                                <div class="col">
                                    <div class="avatar avatar-md"><img class="avatar-img" src="img/sit_norte/Personal.png"></div>
                                </div>
                                <div class="vr"></div>
                                <div class="col">
                                    <div class="fs-5 fw-semibold">{{personal_registro}}</div>
                                    <div class="text-uppercase text-medium-emphasis small ">Registros</div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <!-- /.col-->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card mb-4" style="--cui-card-cap-bg: #0E91FF">
                            <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                            <strong> CARGOS </strong>
                            </div>
                            <div class="card-body row text-center">
                            <div class="col">
                                <div class="fs-5 fw-semibold">{{cargo_registro}}</div>
                                <div class="text-uppercase text-medium-emphasis small">Registros</div>
                            </div>
                            <div class="vr"></div>
                            <div class="col">
                                <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#frmCargo">
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
                        <button type="button" @click="abrirModal('personal','registrar')" class="btn btn-info text-white"  style='margin-left: 1.2%'>
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-8">
                            &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.8%'>
                                <div class="col-md-3">
                                    <select class="form-select" v-model="criterio">
                                        <option value="personal.nombre">Nombre</option>
                                        <option value="cargo.nombre">Cargo</option>
                                        <option value="personal.telefono">Telefono</option>
                                    </select>
                                </div>
                                &nbsp;&nbsp;&nbsp;
                                <input type="text" v-model="buscar" @keyup.enter="listarPersonal(1, buscar, criterio)" @keyup="BuscandoPersonal()" class="form-control" placeholder="Texto a buscar">
                                &nbsp;&nbsp;&nbsp;
                                <button type="submit" @click="listarPersonal(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- Light table -->
                    <div class="table-responsive">
                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2.5%'>
                        <thead style="background-color: #46546C">
                            <tr>
                                <th scope="col" class="text-white">Nombre</th>
                                <th scope="col" class="text-white">Cargo</th>
                                <th scope="col" class="text-white">Telefono</th>
                                <th scope="col" class="text-white">Direccion</th>
                                <th scope="col" class="text-white">Descripcion</th>
                                <th scope="col" class="text-white">Estado</th>
                                <th scope="col" class="text-white">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="personal in arrayPersonal" :key="personal.id">
                                <td v-text="personal.nombre"></td>
                                <td v-text="personal.cargo"></td>
                                <td v-text="personal.telefono"></td>
                                <td v-text="personal.direccion"></td>
                                <td v-text="personal.descripcion"></td>
                                <td>
                                    <template v-if="personal.estado==1">
                                        <span class="badge bg-success">Activo</span>
                                    </template>
                                    <template v-else>
                                        <span class="badge bg-danger">Desactivo</span>
                                    </template>
                                </td>       
                                <td>
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" @click="abrirModal('personal','modificar',personal)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                        <template v-if="personal.estado==1">
                                            <li><a class="dropdown-item" href="#" @click="desactivarPersonal(personal.id)"><i class="fa fa-unlock text-success"></i> Desactivar</a></li>
                                        </template>
                                        <template v-else>
                                            <li><a class="dropdown-item" href="#" @click="activarPersonal(personal.id)"><i class="fa fa-lock text-danger"></i> Activar</a></li>
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
                            <div class="row" :class="erroresPersonal.id_cargo ? 'mb-2' : 'mb-3'">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Cargo</label>
                                <div class="col-sm-10">
                                    <select class="form-select" v-model="datos.id_cargo">
                                        <option value="0" disabled>Seleccione el Cargo</option>
                                        <option v-for="cargo in arrayTipoCargo" :key="cargo.id" :value="cargo.id" v-text="cargo.nombre"></option>
                                    </select>
                                </div>
                                <div class="row" v-if="erroresPersonal.id_cargo">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10">&nbsp;<span class="text-danger">{{erroresPersonal.id_cargo[0]}}</span></div>
                                </div>
                            </div>

                            <div class="row" :class="erroresPersonal.nombre ? 'mb-1' : 'mb-3'">
                                <label for="inputPassword" class="col-sm-2 col-form-label" >Nombre</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.nombre" placeholder="Nombre del Personal"></div>
                            </div>

                            <div class="row mb-2" v-if="erroresPersonal.nombre">
                                <div class="col-sm-2">&nbsp;</div>
                                <div class="col-sm-10"><span class="text-danger">{{erroresPersonal.nombre[0]}}</span></div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Telefono</label>
                                <div class="col-sm-10"><input type="number" class="form-control" v-model="datos.telefono"></div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Direccion</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.direccion"></div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.descripcion"></div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Estado</label>
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
                            <div v-show="errorPersonal" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjPersonal" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-info text-white" @click="guardarPersonal()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-info text-white" @click="modificarPersonal()">Modificar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->   

        <!--Modal Formulario Cargo-->
        <div class="modal fade" id="frmCargo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white">
                        <h4 class="modal-title ">Registro Cargo</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div :class="errores.nombre ? 'mb-1' : 'mb-3'">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nombre</label>
                                <div ><input type="text" class="form-control" v-model="datosCargo.nombre"></div>
                            </div>
                            <div class="row mb-2" v-if="errores.nombre">
                                <div class="col-sm-10"><span class="text-danger">{{errores.nombre[0]}}</span></div>
                            </div>
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
                                <textarea class="form-control" id="message-text" v-model="datosCargo.descripcion"></textarea>
                                <!-- <div class="col-sm-10"><input type="text" class="form-control" v-model="datosCargo.descripcion"></div> -->
                            </div>
                            <!-- <div v-show="errorPersonal" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjPersonal" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div> -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal" @click="limpiarDatosCargo()">Cerrar</button>
                        <button type="button" class="btn btn-info text-white" @click="guardarCargo() ">Guardar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Cargo-->
    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    export default {
        data(){
            return {
                datos : {
                    id : 0,
                    nombre : '',
                    telefono : '',
                    direccion : '',
                    descripcion : '',
                    estado:'1',
                    id_cargo : 0,
                },
                datosCargo : {
                    id : 0,
                    nombre : '',
                    descripcion : '',
                    estado : '1',
                }, 
                cargo_registro :0,
                personal_registro :0,                                
                arrayPersonal : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorPersonal : 0,
                errorMostrarMsjPersonal : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'personal.nombre',
                buscar : '',
                arrayTipoCargo : [],
                erroresPersonal: {},
                errores: {},
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
            listarPersonal(page, buscar, criterio){
                let me=this;
                var url='/personal?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayPersonal=response.data.data;
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
            listarPersonalBusquedaRapida(){
                let me=this;
                var url='/personal?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayPersonal=response.data.data;
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
            BuscandoPersonal(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarPersonalBusquedaRapida,350)
            },
            registrosCargos(){
                let me=this;
                var url='/cargo/cantidad';
                axios.get(url).then(function(response){
                    me.cargo_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            registrosPersonal(){
                let me=this;
                var url='/personal/cantidad';
                axios.get(url).then(function(response){
                    me.personal_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            selectCargo(){
                let me=this;
                var url='/cargo/selectCargo';
                axios.get(url).then(function(response){
                    me.arrayTipoCargo=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarPersonal(page, buscar, criterio);
            },
            async guardarPersonal(){
                try {
                    let me = this;
                    let data = {
                        id : me.datos.id,
                        nombre : me.datos.nombre,
                        telefono : me.datos.telefono,
                        direccion : me.datos.direccion,
                        descripcion : me.datos.descripcion,
                        estado : me.datos.estado,
                        id_cargo : me.datos.id_cargo > 0 ? me.datos.id_cargo : "" ,
                    }
                    const res = await axios.post('/personal/guardar',data)
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.cerrarModal();
                    me.listarPersonal(1, '', 'personal.nombre');
                    me.registrosPersonal(); 
                } catch (error) {
                    if(error.response.data){
                        this.erroresPersonal=error.response.data.errors;
                    }
                } 
            },
            async guardarCargo(){
                try {
                    let me = this;
                    const res = await axios.post('/cargo/guardar',this.datosCargo);
                    me.registrosCargos();  
                    me.limpiarDatosCargo();  
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    })
                } catch (error) {
                    console.log(error.response.data.errors);
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }
            },
            /* guardarCargo(){
            //   if(this.validarPersonal()){
            //         return;
            //     }
                let me = this;
                axios.post('/cargo/guardar',this.datosCargo).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.registrosCargos();  
                    me.limpiarDatosCargo();    
                })
                .catch(function(error){
                    console.log(error);
                });
            }, */
            limpiarDatosCargo(){
                this.errores = {};
                this.datosCargo = {
                    id : 0,
                    nombre : '',
                    descripcion : '',
                    estado : '1',
                    
                }
            },
            modificarPersonal(){
                if(this.validarPersonal()){
                    return;
                }
                let me = this;
                this.datos.id_cargo = this.datos.id_cargo > 0 ? this.datos.id_cargo : "";
                axios.put('/personal/modificar',this.datos).then(function(response){
                     Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro modificado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.cerrarModal();
                    me.listarPersonal(1,'', 'personal.nombre');                    
                }).catch(function(error){
                    console.log(error);
                });
            },         
            validarPersonal(){
                this.errorPersonal = 0;
                this.errorMostrarMsjPersonal = [];

                if(!this.datos.nombre) this.errorMostrarMsjPersonal.push("El nombre del Cliente no puede estar vacio ");
                if(this.errorMostrarMsjPersonal.length) this.errorPersonal=1;
                return this.errorPersonal;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.errorPersonal = 0;
                this.erroresPersonal = {};
                this.datos = {
                    id : 0,
                    nombre : '',
                    telefono : '',
                    direccion : '',
                    descripcion : '',
                }
            },
            abrirModal(modelo, accion, data=[]){
                this.errorPersonal = 0;
                this.errorMostrarMsjPersonal = [];
                this.selectCargo();
                switch(modelo){
                    case "personal":
                        {
                            switch(accion){
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal='Registro Personal'
                                        this.datos = {
                                            id : 0,
                                            nombre : '',
                                            telefono : '',
                                            direccion : '',
                                            descripcion : '',
                                            estado : '1',
                                            id_cargo: 0,
                                        }                                      
                                        this.tipoAccion = 1;
                                        break;
                                    }
                                case 'modificar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'Modificar Proveedor';
                                        this.tipoAccion = 2;
                                        this.datos.id = data['id'];
                                        this.datos.nombre = data['nombre'];
                                        this.datos.telefono = data['telefono'];
                                        this.datos.direccion = data['direccion'];
                                        this.datos.descripcion = data['descripcion'];
                                        this.datos.estado = data['estado'];
                                        this.datos.id_cargo = data['id_cargo'];
                                       break;
                                    }
                            }
                        }
                }
        
            },
            desactivarPersonal(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Inhabilitar este Personal??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Inhabilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/personal/desactivar',{'id': id}).then(function (response) {
                        me.listarPersonal(1,'', 'personal.nombre');
                        swalWithBootstrapButtons.fire(
                        'Inhabilitado!',
                        'Este Personal se ha Inhabilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este Personal no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            activarPersonal(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Habilitar este Personal??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/personal/activar',{'id': id}).then(function (response) {
                        me.listarPersonal(1,'', 'personal.nombre');
                        swalWithBootstrapButtons.fire(
                        'Habilitado!',
                        'Este Personal se ha Habilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este Personal no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            } 
        },
        mounted() {
            this.listarPersonal(1, this.buscar, this.criterio);
            this.registrosCargos();
            this.registrosPersonal();
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
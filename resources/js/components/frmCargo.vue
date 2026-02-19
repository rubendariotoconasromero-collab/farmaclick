<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                   <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">CARGO</h3>
                    </div>

                    <!-- prueba card -->
                    <div class="row mt-2" style='width:90%;margin-left: 1.3%'>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card mb-4" style="--cui-card-cap-bg: #F76D00">
                            <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                            <strong> CARGO </strong>
                            </div>
                            <div class="card-body row text-center">
                                <div class="col">
                                    <div class="avatar avatar-md"><img class="avatar-img" src="img/sit_norte/cargo.png"></div>
                                </div>
                                <div class="vr"></div>
                                <div class="col">
                                    <div class="fs-5 fw-semibold">{{cargo_registro}}</div>
                                    <div class="text-uppercase text-medium-emphasis small ">Registros</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <!-- prueba fin card -->


                    <div class="card-header">
                        <button type="button" @click="abrirModal('cargo','registrar')" class="btn btn-info text-white" style='margin-left: 1%'>
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-8">
                            &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                <div class="col-md-3">
                                    <select class="form-select" v-model="criterio">
                                        <option value="nombre">Nombre</option>
                                    </select>
                                </div>
                                &nbsp;&nbsp;&nbsp;
                                <input type="text" v-model="buscar" @keyup.enter="listarCargo(1, buscar, criterio)" @keyup="BuscandoCargo()" class="form-control" placeholder="Texto a buscar">
                                &nbsp;&nbsp;&nbsp;
                                <button type="submit" @click="listarCargo(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- Light table -->
                    <div class="table-responsive">
                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                        <thead style="background-color: #46546C">
                            <tr>                      
                                <th scope="col" class="text-white">Nombre</th>
                                <th scope="col" class="text-white">Descripcion</th>
                                <th scope="col" class="text-white">Estado</th>
                                <th scope="col" class="text-white">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cargo in arrayCargo" :key="cargo.id">
                                <td v-text="cargo.nombre"></td>
                                <td v-text="cargo.descripcion"></td>      
                                <td>
                                    <template v-if="cargo.estado==1">
                                        <span class="badge bg-success">Activo</span>
                                    </template>
                                    <template v-else>
                                        <span class="badge bg-danger">Desactivo</span>
                                    </template>
                                </td> 
                                <td>
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" @click="abrirModal('cargo','modificar',cargo)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                        <template v-if="cargo.estado==1">
                                            <li><a class="dropdown-item" href="#" @click="desactivarCargo(cargo.id)"><i class="fa fa-unlock text-success"></i> Desactivar</a></li>
                                        </template>
                                        <template v-else>
                                            <li><a class="dropdown-item" href="#" @click="activarCargo(cargo.id)"><i class="fa fa-lock text-danger"></i> Activar</a></li>
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
                    <div class="modal-header text-white" style="background-color: #66B3FF">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="cerrarModal()"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                            <div class="row" :class="errores.nombre ? 'mb-1' : 'mb-3'">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.nombre" ></div>
                            </div>
                            <div class="row mb-2" v-if="errores.nombre">
                                <div class="col-sm-2">&nbsp;</div>
                                <div class="col-sm-10"><span class="text-danger">{{errores.nombre[0]}}</span></div>
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

                            <!-- <div v-show="errorCargo" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjCargo" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div> -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-info text-white" @click="guardarCargo()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-info text-white" @click="modificarCargo()">Modificar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->   
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
                    descripcion : '',
                    estado : '1',
                },                                 
                arrayCargo : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                cargo_registro :0, 
                errorCargo : 0,
                errorMostrarMsjCargo : [],
                errores: {},
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
            listarCargo(page, buscar, criterio){
                let me=this;
                var url='/cargo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayCargo=response.data.data;
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
            listarCargoBusquedaRapida(){
                let me=this;
                var url='/cargo?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayCargo=response.data.data;
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
            BuscandoCargo(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarCargoBusquedaRapida,350)
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarCargo(page, buscar, criterio);
            },
            registrosCargo(){
                let me=this;
                var url='/cargo/cantidad';
                axios.get(url).then(function(response){
                    me.cargo_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },  
            // guardarCargo(){
            //     /* if(this.validarCargo()){
            //         return;
            //     } */
            //     let me = this;
            //     axios.post('/cargo/guardar',this.datos).then(function(response){
            //         Swal.fire({
            //             position: 'top-end',
            //             icon: 'success',
            //             title: 'Registro agregado...',
            //             showConfirmButton: false,
            //             timer: 1500
            //         }) 
            //        me.cerrarModal();
            //         me.listarCargo(1, '', 'nombre'); 
            //         me.registrosCargo();       
            //     })
            //     .catch(function(error){
            //         console.log(error);
            //     });
            // }, 
            async guardarCargo(){
                try {
                    let me = this;
                    const res = await axios.post('/cargo/guardar',this.datos);
                    if(res.data.error==0){
                        //me.$toaster.error('Matricula ya existe...');
                        Swal.fire({
                            icon: 'error',
                            title: 'El Cargo ya existe...',
                            text: 'Debe usar otra cargo!'
                        })
                    }
                    else{
                        me.cerrarModal();
                        me.registrosCargo();
                        me.listarCargo(1, '', 'nombre');   
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Registro agregado...',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }  
                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }
            },
            async modificarCargo(){
                try {
                    let me = this;
                    const res = await axios.put('/cargo/modificar',this.datos);
                    if(res.data.error==0){
                        //me.$toaster.error('Matricula ya existe...');
                        Swal.fire({
                            icon: 'error',
                            title: 'El Cargo ya existe...',
                            text: 'Debe usar otra cargo!'
                        })
                    }
                    else{
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Registro modificado...',
                            showConfirmButton: false,
                            timer: 1500
                        }) 
                        me.cerrarModal();
                        me.listarCargo(1,'', 'nombre');
                    }
                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }
            },         
            validarCargo(){
                this.errorCargo = 0;
                this.errorMostrarMsjCargo = [];

                if(!this.datos.nombre) this.errorMostrarMsjCargo.push("El nombre del Cargo no puede estar vacio ");
                if(this.errorMostrarMsjCargo.length) this.errorCargo=1;
                return this.errorCargo;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.errorCargo = 0;
                this.datos = {
                    id : 0,
                    nombre : '',
                    descripcion : '',
                    estado : '1',
                }
                this.errores = {}
            },
            abrirModal(modelo, accion, data=[]){
                this.errorCargo = 0;
                this.errorMostrarMsjCargo = [];
                switch(modelo){
                    case "cargo":
                        {
                            switch(accion){
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal='Registro Cargo'
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
                                        this.tituloModal = 'Modificar Cargo';
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
            desactivarCargo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Inhabilitar este Cargo??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Inhabilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/cargo/desactivar',{'id': id}).then(function (response) {
                        me.listarCargo(1,'', 'nombre');
                        swalWithBootstrapButtons.fire(
                        'Inhabilitado!',
                        'Este cargo se ha Inhabilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este cargo no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            activarCargo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Habilitar este Cargo??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/cargo/activar',{'id': id}).then(function (response) {
                        me.listarCargo(1,'', 'nombre');
                        swalWithBootstrapButtons.fire(
                        'Habilitado!',
                        'Este cargo se ha Habilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este cargo no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            } 
        },
        mounted() {
            this.listarCargo(1, this.buscar, this.criterio);
            this.registrosCargo();
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
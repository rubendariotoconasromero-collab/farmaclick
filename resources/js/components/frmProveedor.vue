<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                    <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">LABORATORIO</h3>
                    </div>

                    <!-- prueba card -->
                    <div class="row mt-2" style='width:90%;margin-left: 1.3%'>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card mb-4" style="--cui-card-cap-bg: #00D56B">
                            <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                            <strong> LABORATORIO </strong>
                            </div>
                            <div class="card-body row text-center">
                                <div class="col">
                                    <div class="avatar avatar-md"><img class="avatar-img" src="img/sit_norte/Prooveedor.png"></div>
                                </div>
                                <div class="vr"></div>
                                <div class="col">
                                    <div class="fs-5 fw-semibold">{{proveedor_registro}}</div>
                                    <div class="text-uppercase text-medium-emphasis small ">Registros</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <!-- prueba fin card -->


                    <div class="card-header">
                        <button type="button" @click="abrirModal('proveedor','registrar')" class="btn btn-info text-white"  style='margin-left: 1%'>
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-8">
                            &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                <div class="col-md-3">
                                    <select class="form-select" v-model="criterio">
                                        <option value="nombre">Nombre</option>
                                        <option value="nit">Nit</option>
                                        <option value="telefono">Teléfono</option>
                                    </select>
                                </div>
                                &nbsp;&nbsp;&nbsp;
                                <input type="text" v-model="buscar" @keyup.enter="listarProveedor(1, buscar, criterio)" @keyup="BuscandoProveedor()" class="form-control" placeholder="Texto a buscar">
                                &nbsp;&nbsp;&nbsp;
                                <button type="submit" @click="listarProveedor(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- Light table -->
                    <div class="table-responsive">
                    <table class="table table-striped table-hover"  style='width:96%;margin-left: 2%'>
                        <thead style="background-color: #46546C">
                            <tr>
                                <th scope="col" class="text-white" >Cod. Proveedor</th>                       
                                <th scope="col" class="text-white">Nombre</th>
                                <th scope="col" class="text-white">Nit</th>
                                <th scope="col" class="text-white">Contacto</th>
                                <th scope="col" class="text-white">Direccion</th>
                                <th scope="col" class="text-white">telefono</th>
                                <th scope="col" class="text-white">Descripcion</th>
                                <th scope="col" class="text-white">Estado</th>
                                <th scope="col" class="text-white">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="proveedor in arrayProveedor" :key="proveedor.id">
                                <td v-text="proveedor.id"></td>
                                <td v-text="proveedor.nombre"></td>
                                <td v-text="proveedor.nit"></td>
                                <td v-text="proveedor.contacto"></td>
                                <td v-text="proveedor.direccion"></td>
                                <td v-text="proveedor.telefono"></td>
                                <td v-text="proveedor.descripcion"></td>  
                                <td>
                                    <template v-if="proveedor.estado==1">
                                        <span class="badge bg-success">Activo</span>
                                    </template>
                                    <template v-else>
                                        <span class="badge bg-danger">Desactivo</span>
                                    </template>
                                </td>     
                                <td>
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" @click="abrirModal('proveedor','modificar',proveedor)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                        <template v-if="proveedor.estado==1">
                                            <li><a class="dropdown-item" href="#" @click="desactivarProveedor(proveedor.id)"><i class="fa fa-unlock text-success"></i> Desactivar</a></li>
                                        </template>
                                        <template v-else>
                                            <li><a class="dropdown-item" href="#" @click="activarProveedor(proveedor.id)"><i class="fa fa-lock text-danger"></i> Activar</a></li>
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
                <div class="modal-content"  >
                    <div class="modal-header text-white" style="background-color: #66B3FF">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="cerrarModal()"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="row" :class="errores.nombre ? 'mb-1' : 'mb-3'">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.nombre" placeholder="Nombre del Laboratorio"></div>
                            </div>
                            <div class="row mb-2" v-if="errores.nombre">
                                <div class="col-sm-2">&nbsp;</div>
                                <div class="col-sm-10"><span class="text-danger">{{errores.nombre[0]}}</span></div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nit</label>
                                <div class="col-sm-10"><input type="number" class="form-control" v-model="datos.nit"></div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Contacto</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.contacto"></div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Direccion</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.direccion"></div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Telefono</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.telefono"></div>
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

                            <div v-show="errorProveedor" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjProveedor" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-info text-white" @click="guardarProveedor()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-info text-white" @click="modificarProveedor()">Modificar</button>
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
                    nit : 0,
                    contacto : '',
                    direccion : '',
                    telefono : '',
                    descripcion : '',
                    estado : '1',
                },                                 
                arrayProveedor : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errores: {},
                proveedor_registro :0, 
                errorProveedor : 0,
                errorMostrarMsjProveedor : [],
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
            listarProveedor(page, buscar, criterio){
                let me=this;
                var url='/proveedor?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayProveedor=response.data.data;
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
            listarProveedorBusquedaRapida(){
                let me=this;
                var url='/proveedor?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayProveedor=response.data.data;
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
            BuscandoProveedor(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarProveedorBusquedaRapida,350)
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarProveedor(page, buscar, criterio);
            },
            registrosProveedor(){
                let me=this;
                var url='/proveedor/cantidad';
                axios.get(url).then(function(response){
                    me.proveedor_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },  
            async guardarProveedor(){
                let me = this;
                try {
                    const res = await axios.post('/proveedor/guardar',this.datos)
                     Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.cerrarModal();
                    me.listarProveedor(1, '', 'nombre'); 
                    me.registrosProveedor(); 
                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }      
                
            },   
            async modificarProveedor(){
                try {
                    let me = this;
                    const res = await axios.put('/proveedor/modificar',this.datos)
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro modificado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.cerrarModal();
                    me.listarProveedor(1, '', 'nombre'); 
                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }
            },         
            validarProveedor(){
                this.errorProveedor = 0;
                this.errorMostrarMsjProveedor = [];

                if(!this.datos.nombre) this.errorMostrarMsjProveedor.push("El nombre del Cliente no puede estar vacio ");
                if(this.errorMostrarMsjProveedor.length) this.errorProveedor=1;
                return this.errorProveedor;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.errorProveedor = 0; 
                this.errores = {},
                this.datos = {
                    id : 0,
                    nombre : '',
                    nit : '',
                    contacto : '',
                    direccion : '',
                    telefono : '',
                    descripcion : '',
                    estado : '1',
                }
            },
            abrirModal(modelo, accion, data=[]){
                this.errorProveedor = 0;
                this.errorMostrarMsjProveedor = [];
                switch(modelo){
                    case "proveedor":
                        {
                            switch(accion){
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal='Registro Laboratorio'
                                        this.datos = {
                                            id : 0,
                                            nombre : '',
                                            nit : 0,
                                            contacto : '',
                                            direccion : '',
                                            telefono : '',
                                            descripcion : '',
                                            estado: '1',
                                        }                                      
                                        this.tipoAccion = 1;
                                        break;
                                    }
                                case 'modificar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'Modificar Laboratorio';
                                        this.tipoAccion = 2;
                                        this.datos.id = data['id'];
                                        this.datos.nombre = data['nombre'];
                                        this.datos.nit = data['nit'];
                                        this.datos.contacto = data['contacto'];
                                        this.datos.direccion = data['direccion'];
                                        this.datos.telefono = data['telefono'];
                                        this.datos.descripcion = data['descripcion'];
                                        this.datos.estado = data['estado'];
                                       break;
                                    }
                            }
                        }
              }
        
            },
            desactivarProveedor(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Inhabilitar este Proveedor??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Inhabilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/proveedor/desactivar',{'id': id}).then(function (response) {
                        me.listarProveedor(1,'', 'nombre');
                        swalWithBootstrapButtons.fire(
                        'Inhabilitado!',
                        'Este Proveedor se ha Inhabilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este cliente no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            activarProveedor(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Habilitar este Proveedor??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/proveedor/activar',{'id': id}).then(function (response) {
                        me.listarProveedor(1,'', 'nombre');
                        swalWithBootstrapButtons.fire(
                        'Habilitado!',
                        'Este Proveedor se ha Habilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este Proveedor no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            } 
        },
        mounted() {
            this.listarProveedor(1, this.buscar, this.criterio);
            this.registrosProveedor();
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
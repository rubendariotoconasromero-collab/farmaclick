<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                   <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">CLIENTES</h3>
                    </div>
                    <!-- prueba card -->
                    <div class="row mt-2" style='width:90%;margin-left: 1.3%'>
                    <div class="col-sm-6 col-lg-3">
                        <div class="card mb-4" style="--cui-card-cap-bg: #F1C40F">
                            <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                            <strong> CLIENTES</strong>
                            </div>
                            <div class="card-body row text-center">
                                <div class="col">
                                    <div class="avatar avatar-md"><img class="avatar-img" src="img/sit_norte/cliente.png"></div>
                                </div>
                                <div class="vr"></div>
                                <div class="col">
                                    <div class="fs-5 fw-semibold">{{cliente_registro}}</div>
                                    <div class="text-uppercase text-medium-emphasis small ">Registros</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <!-- prueba fin card -->
                    <div class="card-header">
                        <button type="button" @click="abrirModal('cliente','registrar')" class="btn btn-info text-white" style='margin-left: 1%'>
                            <i class="icon-plus"></i>&nbsp;Nuevo
                        </button>                     
                    </div>

                    <div class="form-group row">
                        <div class="col-md-8">
                            &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                <div class="col-md-3">
                                    <select class="form-select" v-model="criterio">
                                        <option value="matricula">NIT/CI</option>
                                        <option value="nombre">Nombre</option>
                                        <option value="telefono">Teléfono</option>
                                    </select>
                                </div>
                                &nbsp;&nbsp;&nbsp;
                                <input type="text" v-model="buscar" @keyup.enter="listarCliente(1, buscar, criterio)" @keyup="BuscandoCliente()" class="form-control" placeholder="Texto a buscar">
                                &nbsp;&nbsp;&nbsp;
                                <button type="submit" @click="listarCliente(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <br>
                    <!-- Light table -->
                    <div class="table-responsive">
                    <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                        <thead style="background-color: #46546c">
                            <tr>
                                <th scope="col" class="text-white">Nit/CI</th>                       
                                <th scope="col" class="text-white">Nombre</th>
                                <th scope="col" class="text-white">Telefono</th>
                                <th scope="col" class="text-white">Direccion</th>
                                <!-- <th scope="col" class="text-white">Descripcion</th>
                                <th scope="col" class="text-white">Descuento</th> -->
                                <th scope="col" class="text-white">Estado</th>
                                <th scope="col" class="text-white">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cliente in arrayCliente" :key="cliente.id">
                                <td v-text="cliente.matricula"></td>
                                <td v-text="cliente.nombre"></td>
                                <td v-text="cliente.telefono"></td>
                                <td v-text="cliente.direccion"></td>
                                <!-- <td v-text="cliente.descripcion"></td>
                                <td>
                                    <template v-if="cliente.descuento==1">
                                        <span>Unitario</span>
                                    </template>
                                    <template v-if="cliente.descuento==2">
                                        <span>Mayorista</span>
                                    </template>
                                    <template v-if="cliente.descuento==3">
                                        <span>Preferencial</span>
                                    </template>
                                </td>        -->
                                <td>
                                    <template v-if="cliente.estado==1">
                                        <span class="badge bg-success">Activo</span>
                                    </template>
                                    <template v-else>
                                        <span class="badge bg-danger">Desactivo</span>
                                    </template>
                                </td> 
                                <td>
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#" @click="abrirModal('cliente','modificar',cliente)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                        <template v-if="cliente.estado==1">
                                            <li><a class="dropdown-item" href="#" @click="desactivarCliente(cliente.id)"><i class="fa fa-unlock text-success"></i> Desactivar</a></li>
                                        </template>
                                        <template v-else>
                                            <li><a class="dropdown-item" href="#" @click="activarCliente(cliente.id)"><i class="fa fa-lock text-danger"></i> Activar</a></li>
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
                    <div class="modal-header text-white" style="background-color: #66b3ff">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="cerrarModal()"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="row" :class="errores.matricula ? 'mb-1' : 'mb-3'">
                                <label for="inputPassword" class="col-sm-2 col-form-label">NIT/CI:</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.matricula"></div>
                            </div>
                            <!-- <div class="row mb-2" v-if="errores.matricula">
                                <div class="col-sm-2">&nbsp;</div>
                                <div class="col-sm-10"><span class="text-danger">{{errores.matricula[0]}}</span></div>
                            </div> -->
                            <div class="row" :class="errores.nombre ? 'mb-1' : 'mb-3'">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.nombre"></div>
                            </div>
                            <div class="row mb-2" v-if="errores.nombre">
                                <div class="col-sm-2">&nbsp;</div>
                                <div class="col-sm-10"><span class="text-danger">{{errores.nombre[0]}}</span></div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Telefono</label>
                                <div class="col-sm-10"><input type="number" class="form-control" v-model="datos.telefono"></div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Direccion</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.direccion"></div>
                            </div>
                            <!-- <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.descripcion"></div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label mb-1">Descuento</label>
                                <div class="col-sm-10">
                                    <select class="form-select" v-model="datos.id_descuento" name="nombre_descuento" id="type">
                                        <option value="" disabled>Seleccione Descuento</option>
                                        <option v-for="descuento in arrayDescuento" :key="descuento.id" :value="descuento.id" v-text="descuento.nombre"></option>
                                    </select> 
                                </div>
                                <div class="row" v-if="errores.nombre_descuento">
                                    <div class="col-sm-2">&nbsp;</div>
                                    <div class="col-sm-10"><span class="text-danger">&nbsp;{{errores.nombre_descuento[0]}}</span></div>
                                </div>
                            </div> -->
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
                            <!-- <div v-show="errorCliente" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjCliente" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div> -->
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" @click="cerrarModal()">Cerrar</button>
                        <button type="button" v-if="tipoAccion==1" class="btn btn-info text-white" @click="guardarCliente()">Guardar</button>
                        <button type="button" v-if="tipoAccion==2" class="btn btn-info text-white" @click="modificarCliente()">Modificar</button>
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
                    matricula : '',
                    telefono : '',
                    direccion : '',
                    descripcion : '',
                    estado : '1',
                    id_descuento: 1,
                },                                 
                arrayCliente : [],
                arrayDescuento: [{id:1,nombre:'Unitario'},{id:2,nombre:'Mayorista'},{id:3,nombre:'Preferencial'}],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                cliente_registro :0, 
                errorCliente : 0,
                errorMostrarMsjCliente : [],
                errores:{},
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
            listarCliente(page, buscar, criterio){
                let me=this;
                var url='/cliente?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayCliente=response.data.data;
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
            listarClienteBusquedaRapida(){
                let me=this;
                var url='/cliente?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayCliente=response.data.data;
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
            BuscandoCliente(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarClienteBusquedaRapida,350)
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarCliente(page, buscar, criterio);
            },
            registrosCliente(){
                let me=this;
                var url='/cliente/cantidad';
                axios.get(url).then(function(response){
                    me.cliente_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },   
            async guardarCliente(){
                // if(this.validarCliente()){
                //     return;
                // }
                try {
                    let me = this;
                    const res = await axios.post('/cliente/guardar',this.datos);
                    if(res.data.error==0){
                        //me.$toaster.error('Matricula ya existe...');
                        Swal.fire({
                            icon: 'error',
                            title: 'NIT/CI ya existe...',
                            text: 'Debe usar otro NIT/CI!'
                        })
                    }
                    else{
                        me.cerrarModal();
                        me.registrosCliente();
                        me.listarCliente(1, '', 'nombre');   
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
            /* guardarCliente(){
                if(this.validarCliente()){
                    return;
                }
                let me = this;
                axios.post('/cliente/guardar',this.datos).then(function(response){
                    if(response.data.error==0){
                        me.$toaster.error('Matricula ya existe...');
                    }
                    else{
                        me.cerrarModal();
                        me.registrosCliente();
                        me.listarCliente(1, '', 'nombre');   
                        me.$toaster.success('Guardado Correctamente');
                    }     
                })
                .catch(function(error){
                    me.$toaster.error('Error');
                });
            },   */
            modificarCliente(){
                let me = this;
                if(me.validarCliente()){
                    return;
                }
                axios.put('/cliente/modificar',this.datos).then(function(response){
                    if(response.data.error==0){
                        me.$toaster.error('Matricula ya existe...');
                    }
                    else{
                        me.cerrarModal();
                        me.listarCliente(1,'', 'nombre');   
                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: 'Registro modificado...',
                            showConfirmButton: false,
                            timer: 1500
                        })   
                    }                
                }).catch(function(error){
                    me.$toaster.error('Error');
                });
            },         
            validarCliente(){
                this.errorCliente = 0;
                this.errorMostrarMsjCliente = [];
                if(!this.datos.matricula) {
                    this.errorMostrarMsjCliente.push("El NIT/CI no puede estar vacio")
                    this.$toaster.error(
                        "El NIT/CI no puede estar vacio"
                    );
                };
                if(!this.datos.nombre) {
                    this.errorMostrarMsjCliente.push("El nombre del Cliente no puede estar vacio")    
                    this.$toaster.error(
                        "El nombre del Cliente no puede estar vacio"
                    );
                };
                if(!this.datos.id_descuento) {
                    this.errorMostrarMsjCliente.push("Debe Seleccionar tipo de Descuento")    
                    this.$toaster.error(
                        "Debe Seleccionar tipo de Descuento"
                    );
                };
                if(this.errorMostrarMsjCliente.length) this.errorCliente=1;
                return this.errorCliente;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.errorCliente = 0;
                this.datos = {
                    id : 0,
                    nombre : '',
                    matricula : '',
                    telefono : '',
                    direccion : '',
                    descripcion : '',
                    estado : '1',
                    id_descuento: 1,
                };
                this.errores={};
            },
            abrirModal(modelo, accion, data=[]){
                this.errorCliente = 0;
                this.errorMostrarMsjCliente = [];
                switch(modelo){
                    case "cliente":
                        {
                            switch(accion){
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal='Registro de Cliente'
                                        this.datos = {
                                            id : 0,
                                            nombre : '',
                                            matricula : '',
                                            telefono : '',
                                            direccion : '',
                                            descripcion : '',
                                            id_descuento : 1,
                                            estado : '1'
                                        }                                      
                                        this.tipoAccion = 1;
                                        break;
                                    }
                                case 'modificar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'Modificar Cliente';
                                        this.tipoAccion = 2;
                                        this.datos.id = data['id'];
                                        this.datos.nombre = data['nombre'];
                                        this.datos.matricula = data['matricula'];
                                        this.datos.telefono = data['telefono'];
                                        this.datos.direccion = data['direccion'];
                                        this.datos.descripcion = data['descripcion'];
                                        this.datos.estado = data['estado'];
                                        this.datos.id_descuento = data['descuento'];
                                       break;
                                    }
                            }
                        }
              }
        
            },
            desactivarCliente(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Inhabilitar este Cliente??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Inhabilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/cliente/desactivar',{'id': id}).then(function (response) {
                        me.listarCliente(1,'', 'nombre');
                        swalWithBootstrapButtons.fire(
                        'Inhabilitado!',
                        'Este cliente se ha Inhabilitado.',
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
            activarCliente(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Habilitar este Cliente??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/cliente/activar',{'id': id}).then(function (response) {
                        me.listarCliente(1,'', 'nombre');
                        swalWithBootstrapButtons.fire(
                        'Habilitado!',
                        'Este cliente se ha Habilitado.',
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
            cargarPdf() {
                /* var params = {
                    id: id
                }; */
                axios.get('/cliente/pdfListarClientes', {responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
        },
        mounted() {
            this.listarCliente(1, this.buscar, this.criterio);
            this.registrosCliente();
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
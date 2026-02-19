<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                    <div class="card">
                        <div class="card-header text-center text-white text-white" style="background-color: #3399FF"><h3 class="mb-0">LISTADO DE GRUPO DE USUARIOS</h3></div>
                        <template v-if="listado==0">
                            <!-- <div class="card-header">
                                <button type="button" @click="frmGrupo()" class="btn btn-info text-white" style='margin-left: 1%'>
                                    <i class="icon-plus"></i>&nbsp;Nuevo
                                </button>
                            </div> -->

                            <div class="form-group row">
                                <div class="col-md-8">
                                    &nbsp;&nbsp;<div class="input-group" style='width:70%;margin-left: 3.3%'>
                                        <!-- <select class="form-select col-md-3" v-model="criterio">
                                            <option value="nombre">Nombre</option>
                                        </select>
                                        &nbsp;&nbsp;&nbsp; -->
                                        <input type="text" v-model="buscar" @keyup.enter="listarGrupo(buscar)" @keyup="BuscandoGrupo()" class="form-control" placeholder="Texto a buscar">
                                        &nbsp;&nbsp;&nbsp;
                                        <button type="submit" @click="listarGrupo(buscar)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <!-- Light table -->
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                                <thead style="background-color: #46546C">
                                    <tr>                      
                                        <th scope="col" class="text-white" width="10%">Número</th>
                                        <th scope="col" class="text-white" width="50%">Nombre</th>
                                        <th scope="col" class="text-white text-center">Nro Usuarios</th>
                                        <th scope="col" class="text-white text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(grupo, index) in arrayGrupo" :key="index">
                                        <td v-text="index + 1"></td>
                                        <td v-text="grupo.nombre"></td>
                                        <td class="text-center" v-text="grupo.nroUsuarios"></td> 
                                        <td class="text-center">
                                            <a class="dropdown-item" href="#" @click="verUsuarios(grupo)"><i class="icon-eye text-success"></i> Ver Usuarios</a>                                                                    
                                        </td>                                
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </template>

                        
                        <template v-if="listado==1">
                            <div class="card-header row m-0">
                                <div class="col-md-2"><button type="button" class="btn btn-danger text-white" @click="volverGrupoListado()">
                                    <i class="fa fa-reply-all"></i>&nbsp;Volver</button>
                                </div>
                                <div class="col-md-8 text-center"><h3 class="mb-0">GRUPOS DE USUARIOS</h3></div>
                            </div> 
                            <div class="card-body">                       
                                <br>
                                <div class="form-group row">
                                    <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead style="background-color: #46546C">
                                            <tr>                      
                                                <th scope="col" class="text-white" width="10%">Numero</th>
                                                <th scope="col" class="text-white" width="60%">Nombre</th>
                                                <th scope="col" class="text-white">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayGrupoUsuario.length">
                                            <tr v-for="(detalle, index) in arrayGrupoUsuario" :key="index">
                                                <td v-text="index+1"></td>
                                                <td v-text="detalle.nombre"></td>
                                                <td>
                                                    <template v-if="detalle.estado==1">
                                                        <span class="badge bg-success">Activo</span>
                                                    </template>
                                                    <template v-else>
                                                        <span class="badge bg-danger">Desactivo</span>
                                                    </template>
                                                </td>                                                                            
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="3">No hay Usuarios Agregados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-danger me-md-2 text-white" type="button" @click="cancelarGrupo()">Cancelar</button>
                                    <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==1" type="button" @click="guardarGrupo()">Guardar</button>
                                    <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==2" type="button" @click="modificarGrupo()">Modificar</button>
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
    export default {
        data(){
            return {
                datos : {
                    id : 0,
                    nombre : '',
                    descripcion : '',
                    estado : '1',
                },         
                arrayGrupoUsuario:[], 
                arrayDetalle : [],
                arrayPermiso : [],                     
                arrayGrupo : [],
                arrayFormulario : [],
                listado : 0,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorGrupo : 0,
                errorMostrarMsjGrupo : [],
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
                buscarP : '',
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
            listarGrupo(buscar){
                let me=this;
                var url='/grupo_listar?buscar=' + buscar;
                axios.get(url).then(function(response){
                    me.arrayGrupo=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            listarGrupoBusquedaRapida(){
                let me=this;
                var url='/grupo_listar?buscar=' + me.buscar;
                axios.get(url).then(function(response){
                    me.arrayGrupo=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            BuscandoGrupo(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarGrupoBusquedaRapida,350)
            },
            verUsuarios(data=[]){
                let me=this;
                me.datos.id=data['id'];
                me.datos.nombre=data['nombre'];
                me.listado=1;
                var url = '/usuario/grupo_usuario?id='+data['id'];
                axios.get(url).then(function(response){
                    me.arrayGrupoUsuario=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            listarPermiso(buscarP){
                let me = this;
                var url='/permiso/listarSinPaginate?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayPermiso= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].id_permiso==id){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            seleccionarPermiso(data=[]){
                let me = this;
                if(me.encuentra(data['id'])){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Este permiso ya se encuentra agregado!'
                    })
                }
                else{
                    me.arrayDetalle.push({
                        id_permiso : data['id'],
                        nombre : data['nombre'],
                    });
                }
            },
            cancelarGrupo(){
                let me = this;
                me.listado = 0;
                me.arrayDetalle = [];
                me.arrayPermiso = [];
                me.datos.nombre = '';
                me.datos.descripcion = '';
                me.datos.estado ='1';
                me.buscarP = '';
            },
            volverGrupoListado(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayPermiso = [];
                me.datos.nombre = '';
                me.datos.descripcion = '';
                me.datos.estado ='1';
                me.buscarP = '';
                me.listado = 0;
                me.tipoAccion = 0;
            },
            guardarGrupo(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe permisos agregados!'
                    })
                }
                let me = this;
                axios.post('/grupo/guardar',{
                    'nombre': me.datos.nombre,
                    'descripcion': me.datos.descripcion,
                    'estado': me.datos.estado,
                    'detalle': me.arrayDetalle
                }).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Grupo - Permiso registrado exitosamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    me.volverGrupoListado();
                    me.listarGrupo('');
                })
                .catch(function(error){
                    console.log(error);
                });
            },   
            editar(data=[]){
                let me = this;
                me.listado = 1;
                me.tipoAccion = 2;
                me.datos.id=data['id'];
                me.datos.nombre=data['nombre'];
                me.datos.descripcion=data['descripcion'];
                me.datos.estado=data['estado'];

                var url='/grupo/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            verDetallePermiso(data=[]){
                let me = this;
                me.listado = 2;
                me.datos.id=data['id'];
                me.datos.nombre=data['nombre'];
                me.datos.descripcion=data['descripcion'];
                me.datos.estado=data['estado'];

                var url='/grupo/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            modificarGrupo(){
                if(this.validarGrupo()){
                    return;
                }
                let me = this;
                axios.put('/grupo/modificar',{
                    'id': me.datos.id,
                    'nombre': me.datos.nombre,
                    'descripcion': me.datos.descripcion,
                    'estado': me.datos.estado,
                    'detalle': me.arrayDetalle
                }).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro modificado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.volverGrupoListado();
                    me.listarGrupo('');                    
                }).catch(function(error){
                    console.log(error);
                });
            },         
            validarGrupo(){
                this.errorGrupo = 0;
                this.errorMostrarMsjGrupo = [];

                if(!this.datos.nombre) this.errorMostrarMsjGrupo.push("El nombre del Grupo no puede estar vacio ");
                if(this.errorMostrarMsjGrupo.length) this.errorGrupo=1;
                return this.errorGrupo;
            },
            frmGrupo(){
                this.listado = 1;
                this.tipoAccion = 1;
                this.arrayDetalle=[];
                this.datos = {
                    id : 0,
                    nombre : '',
                    descripcion : '',
                    estado : '1',
                }
        
            },
            desactivarGrupo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Inhabilitar este Grupo??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Inhabilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/grupo/desactivar',{'id': id}).then(function (response) {
                        me.listarGrupo('');
                        swalWithBootstrapButtons.fire(
                        'Inhabilitado!',
                        'Este grupo se ha Inhabilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este grupo no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            activarGrupo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Habilitar este Grupo??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/grupo/activar',{'id': id}).then(function (response) {
                        me.listarGrupo('');
                        swalWithBootstrapButtons.fire(
                        'Habilitado!',
                        'Este grupo se ha Habilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este grupo no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            } 
        },
        mounted() {
            this.listarGrupo(this.buscar);
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
<template>
    <main class="main">
        <!-- Templates -->
        <div class="row">
            <div class="col">
                &nbsp;
                <div class="card">
                    <template v-if="listado==0">
                    <div class="card-header text-center text-white" style="background-color: #3399FF">
                            <div class="col-sm-12 bg-black d-flex justify-content-between align-items-center">
                                <h3 class="mb-0 col-md-11">USUARIOS</h3>
                                <!-- <button type="button" class="btn-close btn-close-white" @click="volverMenu()" aria-label="Close"></button> -->
                            </div>
                    </div>
                        <div class="card-header">
                            <button type="button" @click="mostrarDetalle()" class="btn btn-info text-white" style='margin-left: 1%'>
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8">
                                &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                    <div class="col-md-3">
                                        <select class="form-select" v-model="criterio" >
                                            <option value="users.name">Usuario</option>
                                            <option value="grupo.nombre">Grupo</option>
                                            <option value="personal.nombre">Personal</option>
                                        </select>
                                    </div>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup.enter="listarUsuario(1, buscar, criterio)" @keyup="BuscandoUsuario()" class="form-control" placeholder="Texto a buscar" >
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarUsuario(1, buscar, criterio)" class="btn btn-info text-white" ><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Light table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                                <thead style="background-color: #46546C">
                                    <tr>
                                        <th scope="col" class="text-white"  width="10%">Número</th>                  
                                        <th scope="col" class="text-white" >Usuario</th>
                                        <!-- <th scope="col" class="text-white" >Matricula</th> -->
                                        <!-- <th scope="col" class="text-white" >Email</th> -->
                                        <th scope="col" class="text-white" >Estado</th>
                                        <th scope="col" class="text-white" >Grupo</th>
                                        <th scope="col" class="text-white" >Personal</th>
                                        <th scope="col" class="text-white" >Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(usuario, index) in arrayUsuario" :key="index">
                                        <td v-text="index +  1" ></td>
                                        <td v-text="usuario.name" ></td>
                                        <!-- <td v-text="usuario.matricula"></td> -->
                                        <!-- <td v-text="usuario.email"></td> -->
                                        <td>
                                            <template v-if="usuario.estado==1">
                                                <span class="badge bg-success">Activo</span>
                                            </template>
                                            <template v-else>
                                                <span class="badge bg-danger">Desactivo</span>
                                            </template>
                                        </td> 
                                        <td v-text="usuario.grupo" ></td>
                                        <td v-text="usuario.personal" ></td>
                                        <td >
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" >Accion</button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <template v-if="usuario.estado==1">
                                                    <li><a class="dropdown-item" href="#" @click="abrirModal('usuario','modificar',usuario)"><i class="fa fa-edit text-warning"></i> Modificar Datos</a></li>
                                                </template>
                                                <template v-else>
                                                    <li><a class="dropdown-item" href="#"><i class="fa fa-edit text-secundary"></i> Modificar Datos</a></li>
                                                </template>
                                                <template v-if="usuario.estado==1">
                                                    <li><a class="dropdown-item" href="#" @click="desactivarUsuario(usuario.id)"><i class="fa fa-unlock text-success"></i> Desactivar</a></li>
                                                </template>
                                                <template v-else>
                                                    <li><a class="dropdown-item" href="#" @click="activarUsuario(usuario.id)"><i class="fa fa-lock text-danger"></i> Activar</a></li>
                                                </template>
                                                <template v-if="usuario.estado==1">
                                                    <li><a class="dropdown-item" href="#" @click="modificarPermUsuario(usuario)"><i class="fa fa-eye text-info"></i> Ver Permisos</a></li>
                                                </template>
                                                <template v-else>
                                                    <li><a class="dropdown-item" href="#"><i class="fa fa-wrench text-secundary"></i> Ver Permisos</a></li>
                                                </template>
                                            </ul>
                                        </td>
                                        <!-- <td>
                                            <button type="button" class="btn btn-warning btn-sm text-white" @click="abrirModal('usuario','modificar',usuario)">
                                                <i class="icon-pencil"></i>
                                            </button> &nbsp;
                                            <button type="button" class="btn btn-danger btn-sm text-white" @click="eliminarPermiso(usuario.id)">
                                                <i class="icon-trash"></i>
                                            </button>
                                        </td> -->                                 
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
                        <div class="card-header text-center">
                            <div class="col-sm-12 bg-black d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 col-md-11">REGISTRO DE USUARIOS</h5>
                                <button type="button" class="btn-close btn-close" @click="volverGrupoListado()" aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="card-body">                               
                                <div class="form-group m-2 mt-0 mr-0 row">          
                                    <form class="row">
                                        <!-- <div class="col-md-6 mb-2">
                                            <label for="exampleInputEmail1" class="form-label">Codigo</label>
                                            <input type="text" class="form-control" aria-describedby="emailHelp" v-model="datos.matricula" disabled>
                                            <div class="row" v-if="erroresUsuario.matricula">
                                                <div class="col-sm-10"><span class="text-danger">{{erroresUsuario.matricula[0]}}</span></div>
                                            </div>
                                        </div> -->

                                        <div class="col-md-6 mb-2">
                                            <label for="exampleInputPassword1" class="form-label">
                                            <span style="color:red;" >* </span>
                                                Usuario
                                            </label>
                                            <input type="text" class="form-control" v-model="datos.nombre" autocomplete="xkcd936">
                                            <div class="row" v-if="erroresUsuario.nombre">
                                                <div class="col-sm-10"><span class="text-danger">{{erroresUsuario.nombre[0]}}</span></div>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-6 mb-2">
                                            <label for="con" class="form-label">
                                            <span style="color:red;" >* </span>
                                                Contraseña
                                            </label>
                                            <input type="password" class="form-control" v-model="datos.password" value="''" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" tabindex="-1">
                                            <div class="row" v-if="erroresUsuario.password">
                                                <div class="col-sm-10"><span class="text-danger">{{erroresUsuario.password[0]}}</span></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="exampleInputPassword1" class="form-label">
                                            <span style="color:red;" >* </span>
                                                Seleccione el Grupo
                                            </label>
                                            <select class="form-select" v-model="datos.id_grupo"  @change="cargarPermisosGrupo()">
                                                <option value="0" disabled>Seleccione el Grupo</option>
                                                <option v-for="grupo in arrayGrupo" :key="grupo.id" :value="grupo.id" v-text="grupo.nombre"></option>
                                            </select>
                                            <div class="row" v-if="erroresUsuario.id_grupo">
                                                <div class="col-sm-10"><span class="text-danger">{{erroresUsuario.id_grupo[0]}}</span></div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="exampleInputPassword1" class="form-label">
                                            <span style="color:red;" >* </span>
                                                Personal
                                            </label>
                                            <select class="form-select" v-model="datos.id_personal">
                                                <option value="0" disabled>Seleccione el Personal</option>
                                                <option v-for="personal in arrayPersonal" :key="personal.id" :value="personal.id" v-text="personal.nombre"></option>
                                            </select>
                                            <div class="row" v-if="erroresUsuario.id_personal">
                                                <div class="col-sm-10"><span class="text-danger">{{erroresUsuario.id_personal[0]}}</span></div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                        <label for="inputPassword" class="form-label">Estado</label>
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

                                        
                                    </form>

        
                                    <div class="col-md-8">
                                        <label>Permisos que tiene el Grupo 
                                        <!--<span style="color:red;" >(*Seleccione)
                                        </span>-->
                                        </label>
                                        <!--<template v-if="datos.id_grupo==0">
                                            <button type="submit" disabled="disabled" class="btn btn-info text-white"><i class="fa fa-search text-white"></i> Seleccionar Permisos</button>
                                        </template>
                                        <template v-else>
                                            <button type="submit" data-toggle="modal" @click="modalPermisoForm(buscarP)"  data-bs-toggle="modal" data-bs-target="#modalPermiso" class="btn btn-info text-white">
                                                <i class="fa fa-search text-white"></i> Seleccionar Permisos
                                            </button>
                                        </template>-->
                                        <!-- <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalPermiso" disabled><i class="fa fa-search"></i> Agregar permisos</button> -->
                                    </div>
                                </div>

                            <br>
                            <div class="form-group row">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead style="background-color: #46546C">
                                            <tr>
                                                <!--<th scope="col" class="text-white" width="10%">Opción</th>-->
                                                <th scope="col" class="text-white" width="10%">Número</th>                     
                                                <th scope="col" class="text-white">Nombre</th>
                                                <th scope="col" class="text-white">Grupo</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle,index) in arrayDetalle" :key="index">
                                                <!--<td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                                </td>-->
                                                <td v-text="index + 1"></td> 
                                                <td v-text="detalle.nombre"></td>                                                                            
                                                <td v-text="detalle.grupo_nombre"></td>                                                                            
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="3">No hay Permisos agregados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-danger me-md-2 text-white" type="button" @click="cancelarUsuario()">Cancelar</button>
                                <button class="btn btn-info btn-lg text-white" type="button" @click="guardarUsuario()">Guardar</button>
                                <!-- <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==2" type="button" @click="modificarGrupo()">Modificar</button> -->
                            </div>
                        </div>
                    </template>

                    <template v-if="listado==2">
                        <div class="card-body">
                                
                                <div class="card-header row">
                                    <div class="col-md-2"><button type="button" class="btn btn-danger text-white" @click="volverGrupoListado()">
                                        <i class="fa fa-reply-all"></i>&nbsp;Volver</button>
                                    </div>
                                    <div class="col-md-8 text-center"><h3 class="mb-0">Listado de Permisos a Formularios</h3></div>
                                    <div class="col-md-2">&nbsp;</div>
                                </div>     
                                <br>                  
                                <div class="form-group row">          
                                    
                                    <div class="col-md-10">
                                        <label style="font-style: italic; font-family: cursive; font-size: 18px; color: #555; letter-spacing: 1px;">Permisos de {{datos.nombre}} - Grupo: {{arrayDetalle[0].grupo_nombre}}</label>
                                        <!--<template v-if="datos.id_grupo==0">
                                            <button type="submit" disabled="disabled" class="btn btn-info text-white"><i class="fa fa-search text-white"></i> Seleccionar Permisos</button>
                                        </template>
                                        <template v-else>
                                            <button type="submit" data-toggle="modal" data-bs-toggle="modal" data-bs-target="#modalPermiso" class="btn btn-info text-white"><i class="fa fa-search text-white"></i> Seleccionar Permisos</button>
                                        </template>-->
                                        <!-- <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalPermiso" disabled><i class="fa fa-search"></i> Agregar permisos</button> -->
                                    </div>
                                </div>
                            <br>
                            <div class="form-group row">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead style="background-color: #46546C">
                                            <tr>                     
                                                <!--<th scope="col" class="text-white" width="10%">Opción</th>-->
                                                <th scope="col" class="text-white" width="10%">Número</th>   
                                                <th scope="col" class="text-white">Nombre</th>
                                                <!--<th scope="col" class="text-white">Grupo</th>-->
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle,index) in arrayDetalle" :key="index">
                                                <!--<td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                                </td>-->
                                                <td v-text="index+1"></td> 
                                                <td v-text="detalle.nombre"></td>                                                                           
                                                <!--<td v-text="detalle.grupo_nombre"></td>-->                                                                     
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="3">No hay Permisos agregados</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button class="btn btn-danger me-md-2 text-white" type="button" @click="cancelarUsuario()">Cancelar</button>
                                <button class="btn btn-info btn-lg text-white" type="button" @click="modificarPermiso()">Guardar</button>
                                <!-- <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==2" type="button" @click="modificarGrupo()">Modificar</button> -->
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <!-- Fin Templates -->

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

                            <!-- <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Código:</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.matricula" disabled></div>
                                <div class="row" v-if="erroresUsuario.matricula">
                                    <div class="col-sm-2">&nbsp;</div>
                                    <div class="col-sm-10"><span class="text-danger p-1">{{erroresUsuario.matricula[0]}}</span></div>
                                </div>
                            </div> -->
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Usuario</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.nombre"></div>
                                <div class="row" v-if="erroresUsuario.nombre">
                                    <div class="col-sm-2">&nbsp;</div>
                                    <div class="col-sm-10"><span class="text-danger p-1">{{erroresUsuario.nombre[0]}}</span></div>
                                </div>
                            </div>
                            <!-- <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.email"></div>
                                <div class="row" v-if="erroresUsuario.email">
                                    <div class="col-sm-2">&nbsp;</div>
                                    <div class="col-sm-10"><span class="text-danger p-1">{{erroresUsuario.email[0]}}</span></div>
                                </div>
                            </div> -->
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Contraseña</label>
                                <div class="col-sm-10"><input type="password" class="form-control" v-model="datos.password"></div>
                                <div class="row" v-if="erroresUsuario.password">
                                    <div class="col-sm-2">&nbsp;</div>
                                    <div class="col-sm-10"><span class="text-danger p-1">{{erroresUsuario.password[0]}}</span></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Grupo</label>
                                <div class="col-sm-10">
                                    <select class="form-select" v-model="datos.id_grupo">
                                        <option value="0" disabled>Seleccione el Grupo</option>
                                        <option v-for="grupo in arrayGrupo" :key="grupo.id" :value="grupo.id" v-text="grupo.nombre"></option>
                                    </select>
                                </div>
                            </div> 
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Personal</label>
                                <div class="col-sm-10">
                                    <select class="form-select" v-model="datos.id_personal">
                                        <option value="0" disabled>Seleccione el Personal</option>
                                        <option v-for="personal in arrayPersonal" :key="personal.id" :value="personal.id" v-text="personal.nombre"></option>
                                    </select>
                                </div>
                            </div>

                            <div v-show="errorPermiso" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjPermiso" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" @click="cerrarModal()">Cerrar</button>
                        <!-- <button type="button" v-if="tipoAccion==1" class="btn btn-info text-white" @click="guardarUsuario()">Guardar</button> -->
                        <button type="button" v-if="tipoAccion==2" class="btn btn-info text-white" @click="modificarUsuario()">Modificar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->   

        <!--Modal Formulario Grupo-->
        <div class="modal fade" id="modalPermiso" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white">
                        <h4 class="modal-title ">Busqueda de Permisos</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="modalPermisoForm(buscarP)" @keyup="BuscandoPermisoForm()" class="form-control" placeholder="Texto a buscar">&nbsp;&nbsp;
                                    <button type="submit" @click="modalPermisoForm(buscarP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>
                        &nbsp;
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr> 
                                    <th scope="col" class="text-white" width="10%">Número</th>                     
                                    <th scope="col" class="text-white" width="70%">Nombre</th>
                                    <th scope="col" class="text-white" width="20%">Opción
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <span>
                                            <label for="form-checkbox">
                                                <input type="checkbox" v-model="selectAll" :data-bs-dismiss="selectAll == false ? 'modal' : ''" @click="selected()">
                                            </label>
                                        </span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(permisoForm, index) in arrayPermisoForm" :key="index">
                                    <td v-text="index + 1"></td>
                                    <td v-text="permisoForm.nombre"></td>
                                    <td>
                                        <button type="button" @click="seleccionarPermiso(permisoForm,index)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>   
                                    </td>                                 
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal" @click="cerrarFormulario()">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Grupo-->
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
                    password : '',
                    id_grupo : '0',
                    email : '',
                    id_personal : '0',
                    estado : '1',
                    detalle : [],
                    id_grupo_cambio : '',
                },                                 
                arrayUsuario : [],
                arrayPersonal : [],
                arrayGrupo : [],
                arrayPermisoForm:[],
                arrayFormulario: [],
                arrayDetalle:[],
                modal : 0,
                tituloModal : '',
                tipoAccion : 1,
                errorPermiso : 0,
                errorMostrarMsjPermiso : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'users.name',
                buscar : '',
                buscarP: '',
                listado : 0,
                erroresUsuario: {},
                codigo_usuario: '',
                selectAll: false,
                setTimeoutBuscador: '',
                vista:0,
                listadoMenu: 0,
                is_busy : 0,
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
            volverMenu(){
                this.vista = 0;
                this.$emit('cerrarMenu', this.listadoMenu);
            },
            listarUsuario(page, buscar, criterio){
                let me=this;
                var url='/usuario?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayUsuario=response.data.data;
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
            listarUsuarioBusquedaRapida(){
                let me=this;
                var url='/usuario?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayUsuario=response.data.data;
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
            BuscandoUsuario(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarUsuarioBusquedaRapida,350)
            },

            selected(){
                
                this.arrayDetalle=[];
                if(!this.selectAll){
                    for(let i in this.arrayPermisoForm){
                        this.arrayDetalle.push({
                            id_permiso : this.arrayPermisoForm[i].id,
                            nombre : this.arrayPermisoForm[i].nombre,
                        });
                    }
                    this.arrayPermisoForm = [];
                }else{
                    this.modalPermisoForm('');
                }
                
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarUsuario(page, buscar, criterio);
            },
            selectGrupo(){
                let me=this;
                var url='/grupo/selectGrupo';
                axios.get(url).then(function(response){
                    me.arrayGrupo=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectPersonal(){
                let me=this;
                var url='/personal/selectPersonal';
                axios.get(url).then(function(response){
                    me.arrayPersonal=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            async guardarUsuario(){
                // if(this.validarPermiso()){
                //     return;
                // }
                try {
                    let me = this;
                    let data = {
                        id : me.datos.id,
                        nombre : me.datos.nombre,
                        matricula : me.datos.matricula,
                        password : me.datos.password,
                        id_grupo : me.datos.id_grupo > 0 ? me.datos.id_grupo : "" ,
                        email : me.datos.email,
                        id_personal : me.datos.id_personal >0 ? me.datos.id_personal : "",
                        estado : me.datos.estado,
                        detalle : me.arrayDetalle,
                    }
                    const res = await axios.post('/usuario/guardar',data)
                    // if(res.data.error==0){
                    //     //me.$toaster.error('Matricula ya existe...');
                    //     Swal.fire({
                    //         icon: 'error',
                    //         title: 'Código ya existe...',
                    //         text: 'Debe usar otro código!'
                    //     })
                    // }
                    // else{
                        if(res.data.error==1){
                        //me.$toaster.error('Matricula ya existe...');
                            Swal.fire({
                                icon: 'error',
                                title: 'El nombre del usuario ya existe...',
                                text: 'Debe usar otro nombre!'
                            })
                        }else{
                            if(res.data.error==2)
                            {
                                Swal.fire({
                                icon: 'error',
                                title: 'Debe seleccionar un personal...',
                                
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
                                me.listarUsuario(1, '', 'users.name'); 
                            }
                        }
                    //}
                } catch (error) {
                    if(error.response.data){
                        this.erroresUsuario=error.response.data.errors;
                    }
                }
            },   


            async cargarUsuario(datos){
                try {

                    const res = await axios.put('/usuario/modificar',datos)
                    if(res.data.error==1){
                        //me.$toaster.error('Matricula ya existe...');
                            Swal.fire({
                                icon: 'error',
                                title: 'El nombre del usuario ya existe...',
                                text: 'Debe usar otro nombre!'
                            })
                        }else{
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Registro modificado...',
                                showConfirmButton: false,
                                timer: 1500
                            }) 
                            this.cerrarModal();
                            this.listarUsuario(1,'', 'users.name'); 
                        }
                    
                } catch (error) {
                    if(error.response.data){
                        this.erroresUsuario=error.response.data.errors;
                    }
                }
            },

            modificarUsuario(){

                let me = this;
                let data = {
                    id : me.datos.id,
                    nombre : me.datos.nombre,
                    matricula : me.datos.matricula,
                    password : me.datos.password,
                    id_grupo_cambio : me.datos.id_grupo_cambio,
                    id_grupo : me.datos.id_grupo > 0 ? me.datos.id_grupo : "" ,
                    email : me.datos.email,
                    id_personal : me.datos.id_personal >0 ? me.datos.id_personal : "",
                    estado : me.datos.estado,
                    detalle : me.arrayDetalle,
                }

                if(data.id_grupo_cambio != data.id_grupo) {
                    const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                    })

                    swalWithBootstrapButtons.fire({
                    title: 'Esta Seguro de Cambiar de Grupo?',
                    text: "Cambiar de Grupo eliminará los permisos asignados!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, modificar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                    }).then((result) => {
                    if (result.isConfirmed) {
                        this.cargarUsuario(data);
                    } else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                        'Cancelado',
                        'Este usuario no ha sufrido cambios :)',
                        'error'
                        )
                    }
                })
                } else {
                    this.cargarUsuario(data);
                }
               
            },         
            validarPermiso(){
                this.errorPermiso = 0;
                this.errorMostrarMsjPermiso = [];

                if(!this.datos.nombre) this.errorMostrarMsjPermiso.push("El nombre del Permiso no puede estar vacio ");
                if(!this.datos.tipo_permiso) this.errorMostrarMsjPermiso.push("Debe seleccionar un tipo de Permiso");
                if(this.errorMostrarMsjPermiso.length) this.errorPermiso=1;
                return this.errorPermiso;
            },
            cerrarModal(){
                this.listado=0;
                this.modal = 0;
                this.tituloModal = '';
                this.errorPermiso = 0;
                this.datos = {
                    id : 0,
                    nombre : '',
                    matricula : '',
                    password : '',
                    id_grupo : '0',
                    email : '',
                    id_personal : '0',
                    estado : '1',
                    detalle : [],
                }
                this.erroresUsuario = {}
            },
            abrirModal(modelo, accion, data=[]){
                this.errorPermiso = 0;
                this.errorMostrarMsjPermiso = [];
                switch(modelo){
                    case "usuario":
                        {
                            switch(accion){
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal='Registro de Usuario'
                                        this.datos = {
                                            id : 0,
                                            nombre : '',
                                            matricula : '',
                                            password : '',
                                            id_grupo : '0',
                                            id_personal : '0',
                                            estado : '1',
                                        }                                      
                                        this.tipoAccion = 1;
                                        break;
                                    }
                                case 'modificar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'Modificion de Usuario';
                                        this.tipoAccion = 2;
                                        this.datos.id = data['id'];
                                        this.datos.nombre = data['name'];
                                        this.datos.matricula = data['matricula'];
                                        this.datos.email = data['email'];
                                        this.datos.password = '';
                                        this.datos.id_grupo = data['id_grupo'];
                                        this.datos.id_personal = data['id_personal'];
                                        this.datos.estado = data['estado'];
                                        this.datos.id_grupo_cambio = data['id_grupo'];
                                       break;
                                    }
                            }
                        }
              }
        
            },
            eliminarPermiso(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Eliminar este permiso??',
                    text: "No Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Eliminar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.delete('/permiso/eliminar/'+id).then(function (response) {
                        me.listarUsuario(1,'', 'nombre');
                        swalWithBootstrapButtons.fire(
                            'Eliminado!',
                            'Este permiso se ha Eliminado.',
                            'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este permiso no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },


            mostrarDetalle(){
               let me = this;
                me.listado=1;
                
                this.selectGrupo();
                this.arrayUsuario = [];
                this.arrayDetalle = [];
                this.arrayGrupo = [];
                this.errorMostrarMsjUsuario=[];

                me.datos = {
                    id : 0,
                    nombre : '',
                    matricula : me.codigo_usuario,
                    password : '',
                    id_grupo : '0',
                    email : '',
                    id_personal : '0',
                    estado : '1',
                    detalle : [],
                }
            },
            volverGrupoListado(){
                let me = this;
                me.listarUsuario(1, '', 'nombre');
                // me.arrayDetalle = [];
                // me.arrayPermiso = [];
                // me.datos.nombre = '';
                // me.datos.descripcion = '';
                // me.datos.estado ='1';
                // me.buscarP = '';
                me.listado = 0;
                me.tipoAccion = 0;
                me.buscarP = '';
                me.buscar = '';
            },
            modalPermisoForm(buscar){
                let me = this;
                if(me.datos.id_grupo==0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe seleccionar el Grupo de Usuario!'
                    })
                }
                else{
                    
                    var url='/detalle_form?id_grupo='+ me.datos.id_grupo +'&buscar=' + buscar;
                    axios.get(url).then(function(response){
                        // me.arrayPermisoForm= response.data;
                        me.arrayDetalle= response.data;

                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }
            },
            cargarPermisosGrupo(){
                let me = this;
                if(me.datos.id_grupo==0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe seleccionar el Grupo de Usuario!'
                    })
                }
                else{
                    
                    var url='/detalle_form?id_grupo='+ me.datos.id_grupo;
                    axios.get(url).then(function(response){
                        me.arrayDetalle= response.data;
                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }
            },
            modalPermisoFormBusquedaRapida(){
                let me = this;
                if(me.datos.id_grupo==0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe seleccionar el Grupo de Usuario!'
                    })
                }
                else{
                    
                    var url='/detalle_form?id_grupo='+ me.datos.id_grupo +'&buscar=' + me.buscarP;
                    axios.get(url).then(function(response){
                        me.arrayPermisoForm= response.data;
                    })
                    .catch(function(error){
                        console.log(error);
                    });
                }
            },
            BuscandoPermisoForm(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.modalPermisoFormBusquedaRapida,350)
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
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
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].id_permiso==id){
                        sw=true;
                    }
                }
                return sw;
            },
            modificarPermUsuario(data=[]){
                let me = this;
                me.datos.id=data['id'];
                me.datos.email=data['email'];
                me.datos.nombre=data['name'];
                me.datos.id_grupo=data['id_grupo'];
                me.modalPermisoForm('');
                me.listado=2;
                /*var url = '/usuario_permiso/obtenerDetalles?id='+data['id'];
                axios.get(url).then(function (response) {
                    console.log(response);
                    me.arrayDetalle=response.data;                    
                })
                .catch(function (error) {
                    console.log(error);
                });*/
                me.modalPermisoForm("");
            }, 
            seleccionarPermiso(data=[],index){
                let me = this;
                let id = data['id'];
                if(me.encuentra(id)){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Ese formulario ya se encuentra agregado!'
                    })
                }
                else{
                    me.arrayDetalle.push({
                        id_permiso : data['id'],
                        nombre : data['nombre']
                    });
                    me.arrayPermisoForm.splice(index,1);
                }
            },

            modificarPermiso(){
                let me = this;
                axios.delete('/usuario_permiso/eliminar/'+ me.datos.id).then(function (response) {
                    //
                }).catch(function (error) {
                    console.log(error);
                });
                me.datos.detalle= me.arrayDetalle;
                axios.post('/usuario_permiso/modificarPermisos',me.datos).then(function(response){
                    me.ocultarListado1();             
                }).catch(function(error){
                    console.log(error);
                });
                console.log(me.datos);
            },
            ocultarListado1(){
                this.listado=0;
                this.listarUsuario(1, '', 'users.name');
            },

            desactivarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de desactivar este usuario?',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Inhabilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/usuario/desactivar',{'id': id}).then(function (response) {
                        me.listarUsuario(1, '', 'users.name');
                        swalWithBootstrapButtons.fire(
                        'Inhabilitado!',
                        'Este articulo se ha Inhabilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este personal no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            activarUsuario(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de activar este usuario?',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/usuario/activar',{'id': id}).then(function (response) {
                        me.listarUsuario(1, '', 'users.name');
                        swalWithBootstrapButtons.fire(
                        'Habilitado!',
                        'Este usuario se ha Habilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este usuario no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            cancelarUsuario(){
                this.erroresUsuario={};
                this.limpiarDatos();
                this.listarUsuario(1, this.buscar, this.criterio);
                this.listado=0,
                this.buscar = '',
                this.buscarP = ''
            },
            cerrarFormulario(){
                this.buscar = '',
                this.buscarP = ''
            },
            limpiarDatos(){
                this.datos = {
                    id : 0,
                    nombre : '',
                    matricula : '',
                    password : '',
                    id_grupo : '0',
                    email : '',
                    id_personal : '0',
                    estado : '1',
                    detalle : [],
                    id_grupo_cambio : '',
                }
                this.arrayDetalle = []
            },
            getCodigoUsuario(){
                let me=this;
                var url='/usuario_maximo_id';
                axios.get(url).then(function(response){
                    me.codigo_usuario=response.data[0].id + 1;
                })
                .catch(function(error){
                    console.log(error)
                });          
            }

        },
        mounted() {
            this.listarUsuario(1, this.buscar, this.criterio);
            this.selectGrupo();
            this.selectPersonal();
            this.getCodigoUsuario();
        },

    }
</script>
<style>

    input[type="password"][readonly] {
        background-color: #f8f8f8; /* Color de fondo cuando está en modo de solo lectura */
        border: 1px solid #ccc; /* Borde del campo de contraseña */
    }
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
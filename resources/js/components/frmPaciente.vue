<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                    <div class="card">
                    <template v-if="listado==0">
                        <div class="card-header text-center text-white" style="background-color: #3399FF">
                            <h3 class="mb-0">Mascota</h3>
                        </div>
                        <!-- prueba card -->
                        <div class="row mt-2" style='width:90%;margin-left: 1.5%'>
                            <div class="col-sm-6 col-lg-3" >
                                <div class="card mb-4" style="--cui-card-cap-bg: #F76D00">
                                    <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                    <strong> Mascota </strong>
                                    </div>
                                    <div class="card-body row text-center">
                                        <div class="col">
                                            <div class="avatar avatar-md"><img class="avatar-img" src="img/sit_norte/icons8-producto-48.png"></div>
                                        </div>
                                        <div class="col">
                                            <div class="fs-5 fw-semibold">{{producto_registro}}</div>
                                            <div class="text-uppercase text-medium-emphasis small">Registros</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- col 3-->
                            <div class="col-sm-6 col-lg-3">
                                <div class="card mb-4" style="--cui-card-cap-bg: #0E91FF">
                                    <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                    <strong> ASIGNACIÓN DE VACUNAS </strong>
                                    </div>
                                    <div class="card-body row text-center">
                                        <div class="col">
                                            <div class="avatar avatar-md"><img class="avatar-img" src="img/sit_norte/vac.png"></div>
                                        </div>
                                    <div class="vr"></div>
                                    <div class="col">
                                        <button type="button" class="btn btn-info text-white position-relative" @click="seleccionar_menu()">
                                            <i  class="icon-plus"></i>
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                                new
                                                <span class="visually-hidden">unread messages</span>
                                            </span>
                                        </button>
                                    </div>
                                    <br>
                                    <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <!-- prueba fin card -->

                            <div class="card-header">
                                <button type="button" @click="btnNuevoArticulo()" class="btn btn-info text-white" style='margin-left: 1.2%'>
                                    <i class="icon-plus"></i>&nbsp;Nuevo
                                </button>
                            </div>
                            <div class="form-group row" id="home">
                                <div class="col-md-8" >
                                    &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                        <div class="col-md-3">
                                            <select class="form-select col-md-3" v-model="criterio">
                                                <option value="cliente.nombre">Propietario</option>
                                                <option value="paciente.nombre">Mascota</option>
                                                <option value="paciente.raza">Raza</option>                                       
                                            </select>
                                        </div>
                                        &nbsp;&nbsp;&nbsp;
                                        <input type="text" v-model="buscar" @keyup.enter="listarArticulo(1, buscar, criterio)" @keyup="BuscandoArticulo()" class="form-control" placeholder="Texto a buscar">
                                        &nbsp;&nbsp;&nbsp;
                                        <button type="submit" @click="listarArticulo(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <!-- Light table -->
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                                <thead style="background-color: #46546C">
                                    <tr>
                                        <th scope="col" class="text-white">Propietario</th> 
                                        <th scope="col" class="text-white">Mascota</th> 
                                        <th scope="col" class="text-white">Raza</th>                      
                                        <th scope="col" class="text-white">Edad</th>
                                        <th scope="col" class="text-white">Color</th>
                                        <th scope="col" class="text-white">Peso</th>
                                        <th scope="col" class="text-white">Especie</th>
                                        <th scope="col" class="text-white">Estado</th>
                                        <th scope="col" class="text-white">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayPaciente.length">
                                    <tr v-for="paciente in arrayPaciente" :key="paciente.id">
                                        <td v-text="paciente.cliente"></td>
                                        <td v-text="paciente.mascota"></td>
                                        <td v-text="paciente.raza"></td>
                                        <td v-text="paciente.edad"></td>
                                        <td v-text="paciente.color"></td>
                                        <td v-text="paciente.peso"></td>
                                        <td v-text="paciente.animal"></td>
                                        <td>
                                            <template v-if="paciente.estado==1">
                                                <span class="badge bg-success">Activo</span>
                                            </template>
                                            <template v-else>
                                                <span class="badge bg-danger">Desactivo</span>
                                            </template>
                                        </td> 
                                        <td>
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" @click="editarArticulo(paciente)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                                <template v-if="paciente.estado==1">
                                                <li><a class="dropdown-item" href="#" @click="desactivarArticulo(paciente.id)"><i class="fa fa-unlock text-success"></i> Desactivar</a></li>
                                            </template>
                                            <template v-else>
                                                <li><a class="dropdown-item" href="#" @click="activarArticulo(paciente.id)"><i class="fa fa-lock text-danger"></i> Activar</a></li>
                                            </template>
                                            </ul>
                                        </td>                                 
                                    </tr>
                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="13">No hay Productos agregados</td>
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
                            <div class="card-body">
                                <div class="card-header text-center">
                                <div class="row">
                                <div class="col-md-1">
                                    <button type="button" @click="ocultarListado1()" class="btn btn-danger text-white " >
                                        <i class="fa fa-reply-all"></i>&nbsp;Volver
                                    
                                    </button>
                                    
                                </div>
                                <div class="col-md-9 text-center">
                                    <h3 class="mb-0">DATOS MASCOTAS</h3>  
                                </div>


                                </div>
                                </div>
                                <div class="card-header text-center line p-0 m-0"  style="background-color: #3399FF">
                                    
                                </div>&nbsp;
                                
                                <form class="row g-3">
                                        <div class="col-md-6">
                                        <label for="exampleInputEmail1" class="form-label">Propietario</label>
                                        <div class="input-group mb-6">

                                            <section class="dropdown-wrapper form-control bg-disabled">
                                                <div @click="isVisible = !isVisible" class="selected-item">
                                                    <span v-if="datos.cliente==''">Seleccione Propietario</span>
                                                    <span  v-else>{{datos.cliente }} </span>
                                                    <svg :class="isVisible && datos.tipo_venta != 'Venta Servicio' ? 'dropdown' : ''" class="drop-down-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>
                                                </div>
                                                <div :class="isVisible  && datos.tipo_venta != 'Venta Servicio' ? 'visible' : 'invisible'" class="dropdown-popover">
                                                    <input type="text" class="form-control" placeholder="Buscar Propietario.."  v-model="buscarCliente" aria-label="Buscar Propietario..">
                                                    <div class="text-center"><span v-if="filteredCliente.length === 0">No existen Propietario</span></div>
                                                    <div class="options">
                                                        <ul>
                                                            <li @click="selectedCliente(cliente)" v-for="(cliente, index) in filteredCliente" :key="`cliente-${index}`" @change="cambiar()">{{cliente.nombre}}</li>
                                                        </ul>
                                                    </div>
                                                </div> 
                                            </section>
                                            &nbsp;&nbsp;&nbsp;
                                            <button type="button" class="btn btn-info text-white position-relative" @click="abrirModal('cliente','registrar')"><i class="fa fa-address-card-o"></i> Registrar Propietario</button>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Cliente</label>
                                        <select class="form-select" v-model="datos.id_cliente">
                                            <option value="0" disabled>Seleccione el Cliente</option>
                                            <option v-for="cleinte in arrayCliente" :key="cleinte.id" :value="cleinte.id" v-text="cleinte.nombre"></option>
                                        </select>
                                        <div class="row" v-if="errores.id_categoria">
                                            <div class="col-sm-10"><span class="text-danger">{{errores.id_cliente[0]}}</span></div>
                                        </div>
                                    </div> -->
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Especie</label>
                                        <select class="form-select" v-model="datos.id_animal">
                                            <option value="0" disabled>Seleccione la Especie</option>
                                            <option v-for="animal in arrayAnimal" :key="animal.id" :value="animal.id" v-text="animal.nombre"></option>
                                        </select>
                                        <div class="row" v-if="errores.id_categoria">
                                            <!-- <div class="col-sm-2">&nbsp;</div> -->
                                            <div class="col-sm-10"><span class="text-danger">{{errores.id_animal[0]}}</span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" v-model="datos.nombre">
                                    </div>

                                    <!-- <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Especie</label>
                                        <input type="text" class="form-control" v-model="datos.especie">

                                        <div class="row" v-if="errores.especie">
                                            <div class="col-sm-10"><span class="text-danger">{{errores.especie[0]}}</span></div>
                                        </div>
                                    </div> -->

                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Edad</label>
                                        <input type="string" class="form-control" v-model="datos.edad">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Color</label>
                                        <input type="text" class="form-control" v-model="datos.color">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Raza</label>
                                        <input type="text" class="form-control" v-model="datos.raza">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="inputPassword" class="form-label">Sexo</label>
                                        <div class="col-sm-10">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1" v-model="datos.sexo">
                                                <label class="form-check-label" for="inlineRadio1">Macho</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="0" v-model="datos.sexo">
                                                <label class="form-check-label" for="inlineRadio2">Hembra</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Peso</label>
                                        <input type="number" class="form-control" v-model="datos.peso" min="0">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Cirugias Previas</label>
                                        <input type="text" class="form-control" v-model="datos.cirugias" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Enfermedades Previas</label>
                                        <input type="text" class="form-control" v-model="datos.enfermedades">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Vacunas Previas</label>
                                        <input type="text" class="form-control" v-model="datos.vacunas">
                                    </div>
                                </form>
                                <div class="header-divider"></div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-danger me-md-2 text-white" type="button" @click="ocultarListado1()">Cancelar</button>
                                    <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==1"  type="button" @click="guardarArticulo()">Guardar Mascota</button>
                                    <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==2"  type="button" @click="modificarArticulo()">Modificar Mascota</button>
                                </div>
                            </div>
                            
                        </template>
                        <template v-if="listado==2">
                            <frm-animal :selectAnimal2="selectAnimal2" @cerrarVentaTienda="listadoVenta"></frm-animal>
                        </template>  
                    </div>
                </div>
            </div>
        <!-- </div> -->

        <!--Modal Formulario Categoria-->
        <div class="modal fade" id="frmCategoria" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white">
                        <h4 class="modal-title ">Registro Categoria</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nombre</label>
                                <div><input type="text" class="form-control" v-model="datosCategoria.nombre"></div>
                            </div>
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
                                <textarea class="form-control" id="message-text" v-model="datosCategoria.descripcion"></textarea>
                                <!-- <div class="col-sm-10"><input type="text" class="form-control" v-model="datosCategoria.descripcion"></div> -->
                            </div>
                            <div v-show="errorArticulo" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjArticulo" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        <!-- </form> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-info text-white" @click="guardarCategoria() ">Guardar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Categoria-->
        <!--Modal Formulario Unidad Medida-->
        <div class="modal fade" id="frmUnidad" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white">
                        <h4 class="modal-title"> Registro Unidad Medida</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->

                            <div class="mb-3"  :class="errores.nombre ? 'mb-1' : 'mb-3'">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nombre</label>
                                <div><input type="text" class="form-control" v-model="datosUnidad.nombre" ></div>
                            </div>
                           <div class="row mb-2" v-if="errores.nombre">
                                <div class="col-sm-2">&nbsp;</div>
                                <div class="col-sm-10"><span class="text-danger">{{errores.nombre[0]}}</span></div>
                            </div>

                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Abreviatura</label>
                                <div><input type="text" class="form-control" v-model="datosUnidad.abreviatura"></div>
                            </div>
                        <!-- </form> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-info text-white" @click="guardarUnidad()">Guardar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Unidad Medida-->
        <!--Modal Formulario Proveedor-->
        <div class="modal fade" id="frmProveedor" tabindex="-1" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content"  >
                    <div class="modal-header btn btn-info text-white">
                        <h4 class="modal-title">Registro Proveedor</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nombre</label>
                                <div><input type="text" class="form-control" v-model="datosProveedor.nombre" placeholder="Nombre del proveedor"></div>
                            </div>
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nit</label>
                                <div><input type="number" class="form-control" v-model="datosProveedor.nit"></div>
                            </div>
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Contacto</label>
                                <div><input type="text" class="form-control" v-model="datosProveedor.contacto"></div>
                            </div>

                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Direccion</label>
                                <div><input type="text" class="form-control" v-model="datosProveedor.direccion"></div>
                            </div>

                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Telefono</label>
                                <div><input type="text" class="form-control" v-model="datosProveedor.telefono"></div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
                                <div><input type="text" class="form-control" v-model="datosProveedor.descripcion"></div>
                            </div>
                        <!-- </form> -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-info text-white" @click="guardarProveedor()">Guardar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Proveedor-->
        <!--Inicio del modal agregar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' :modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white" style="background-color: #66b3ff">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="cerrarModal()"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datosCliente.nombre"></div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">NIT/CI:</label>
                                <div class="col-sm-10"><input type="number" class="form-control" v-model="datosCliente.matricula" min="0"></div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Telefono</label>
                                <div class="col-sm-10"><input type="number" class="form-control" v-model="datosCliente.telefono"></div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Dirección</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datosCliente.direccion"></div>
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
                        <button type="button" v-if="tipoAccion==1" class="btn btn-info text-white" @click="guardarCliente(),datosNuevoCliente(datosCliente)">Guardar</button>
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
    import Swal, { dangerConfirm } from '../utils/appSwal';
    import moment from 'moment';
    export default {

        data(){
            return {
                datos : {
                    id : 0,
                    edad :  0,
                    enfermedades : '',
                    vacunas : '',
                    cod_proveedor : '',
                    cod_veterinaria : '',
                    cod_ean : '',
                    nombre : '',
                    especie: '',
                    color: '',
                    raza: '',
                    peso: 0,
                    cirugias: '',
                    tipo : '0',
                    tipo_producto : 'Producto Venta',
                    descripcion : '',
                    estado : '1',
                    sexo : '1',
                    id_categoria : 0,
                    id_animal : 0,
                    id_cliente : 0,
                    cliente:'',
                    

                },
                selectAnimal2:{
                    // id_animal:0,
                    // animal:'',
                },
                datosCliente:{
                    id : 0,
                    nombre : '',
                    matricula : '',
                    telefono : '',
                    direccion : '',
                    descripcion : '',
                    estado : '1',
                    id_descuento: 1,
                },   
                datosCategoria : {
                    id : 0,
                    nombre : '',
                    descripcion : '',
                    estado : '1',
                }, 
                datosUnidad : {
                    id : 0,
                    nombre : '',
                    abreviatura : '',
                    estado : '1',
                },
                datosProveedor : {
                    id : 0,
                    nombre : '',
                    nit : 0,
                    contacto : '',
                    direccion : '',
                    telefono : '',
                    descripcion : '',
                    estado : '1',
                },   
                unidad_registro :0,
                categoria_registro :0,
                personal_registro :0, 
                proveedor_registro :0, 
                producto_registro :0,                                
                arrayPaciente : [],
                arrayArticulo2 : [],
                errores:{},
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorArticulo : 0,
                errorMostrarMsjArticulo : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'cliente.nombre',
                buscar : '',
                arrayTipoCategoria : [],
                arrayAnimal : [],
                arrayCliente : [],
                listado: 0,
                setTimeoutBuscador: '',
                isVisible: false,
                buscarCliente: '',
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
            filteredCliente(){
                const data = this.buscarCliente.toLowerCase();
                if(this.buscarCliente == ""){
                    return this.arrayCliente;
                }
                return this.arrayCliente.filter((item)=>{
                    return Object.values(item).some((word=>String(word).toLowerCase().includes(data)))
                })
            },
        },
        methods : {
            seleccionar_menu()
            {
              this.listado=2;
            },
            listadoVenta(listado){
                let me=this;
                me.listado = listado;
            },
            cambiarVenta()
            {
                if(this.datos.tipo == '0')
                {
                    this.listarArticulo(1, '', 'producto.nombre'); 
                }
                else
                {
                    this.listarArticulo2(1, '', 'producto.nombre'); 
                }
            },
            listarArticulo(page, buscar, criterio){
                let me=this;
                var url='/paciente?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayPaciente=response.data.data;
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
            listarArticulo2(page, buscar, criterio){
                let me=this;
                var url='/articuloFarmacia?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayPaciente=response.data.data;
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
                var url='/paciente?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayPaciente=response.data.data;
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
            listarArticuloBusquedaRapida2(){
                let me=this;
                var url='/articuloFarmacia?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayPaciente=response.data.data;
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
            BuscandoArticulo(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticuloBusquedaRapida,350)
            },
            BuscandoArticulo2(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticuloBusquedaRapida2,350)
            },
            listar2(page, buscar, criterio){
                let me=this;
                var url='/articulo2?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayArticulo2=response.data.data;
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
            btnNuevoArticulo(){
                this.selectCategoria();
                this.selectAnimal();
                this.selectCliente();
                let me = this;
                me.listado=1;
                this.tipoAccion = 1;
                me.datos = {
                    id : 0,
                    nombre : '',
                    especie: '',
                    edad :  0,
                    color: '',
                    raza: '',
                    peso: 0,
                    cirugias: '',
                    enfermedades :  '',
                    vacunas :  '',
                    estado : '1',
                    sexo : '1',
                    id_animal : 0,
                    id_cliente : 0,
                    cliente:'',

                };
            },
           async guardarArticulo(){
 
                try {
                    let me = this;
                    let data = {
                        id : me.datos.id,
                        nombre : me.datos.nombre,
                        especie : me.datos.especie,
                        edad : me.datos.edad,
                        color: me.datos.color,
                        raza: me.datos.raza,
                        sexo : me.datos.sexo,
                        peso: me.datos.peso,
                        cirugias: me.datos.cirugias,
                        enfermedades: me.datos.enfermedades,
                        vacunas: me.datos.vacunas,
                        estado : me.datos.estado,
                        id_animal : me.datos.id_animal >0 ? me.datos.id_animal : "",
                        id_cliente : me.datos.id_cliente >0 ? me.datos.id_cliente : "",
                    }
                    const res = await axios.post('/paciente/guardar',data);
                    if(res.data.error==0){
                        //me.$toaster.error('Matricula ya existe...');
                        Swal.fire({
                            icon: 'error',
                            title: 'Nombre ya existe...',
                            text: 'Debe usar otra nombre!'
                        })
                    }
                    else{
                        me.listado =0;
                        me.registrosArticulo();
                        me.listarArticulo(1, '', 'producto.nombre');   
                        me.listar2(1, '', 'producto.nombre');
                        me.errores ={};
                        me.datos = {
                        id : 0,
                        cod_producto : '',
                        cod_proveedor : '',
                        cod_ean : '',
                        nombre : '',
                        marca : '',
                        raza: '',
                        peso: 0,
                        costo_mayorista: 0,
                        costo_preferencial: 0,
                        cirugias: '',
                        tipo_producto : 'Producto Venta',
                        descripcion : '',
                        estado : '1',
                        id_categoria : 0,
                        tipo : '0',
                    },
                    me.registrosArticulo();
                    me.registrosArticuloProducto()
                    me.arrayPaciente = [];
                    me.arrayArticulo2 = [];
                    me.arrayTipoCategoria = [];
   
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
            async modificarArticulo(){
                try {
                    let me = this;
                    const res = await axios.put('/paciente/modificar',me.datos);
                    if(res.data.error==0){
                        Swal.fire({
                            icon: 'error',
                            title: 'Nombre ya existe...',
                            text: 'Debe usar otra nombre! o Agregar nueva Categoría'
                        })
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Registro modificado...',
                            showConfirmButton: false,
                            timer: 1500
                        }) 
                        me.listado =0;
                        me.listarArticulo(1,'', 'cliente.nombre');
                       // me.listar2(1,'', 'cliente.nombre');
                        me.datos = {
                        tipo : '0',
                        
                        }
                    }    
                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }
            },
            registrosCategoria(){
                let me=this;
                var url='/categoria/cantidad';
                axios.get(url).then(function(response){
                    me.categoria_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            registrosUnidad(){
                let me=this;
                var url='/unidad/cantidad';
                axios.get(url).then(function(response){
                    me.unidad_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            registrosArticulo(){
                let me=this;
                var url='/paciente/cantidad';
                axios.get(url).then(function(response){
                    me.personal_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
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

            registrosArticuloProducto(){
                let me=this;
                var url='/paciente/cantidad';
                axios.get(url).then(function(response){
                    me.producto_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            },
            selectCategoria(){
                let me=this;
                var url='/categoria/selectCategoria';
                axios.get(url).then(function(response){
                    me.arrayTipoCategoria=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectAnimal(){
                let me=this;
                var url='/animal/selectAnimal';
                axios.get(url).then(function(response){
                    me.arrayAnimal=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectCliente(){
                let me=this;
                var url='/cliente/selectCliente';
                axios.get(url).then(function(response){
                    me.arrayCliente=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            editarArticulo(data=[]){
                this.selectAnimal();
                this.selectCliente();
                let me = this;
                me.listado=1;
                this.tipoAccion = 2;
                this.datos.id = data['id'];
                this.datos.nombre = data['mascota'];
                this.datos.especie = data['especie'];
                this.datos.edad = data['edad'];
                this.datos.color = data['color'];
                this.datos.raza = data['raza'];
                this.datos.sexo = data['sexo'];
                this.datos.peso = data['peso'];
                this.datos.cirugias = data['cirugias'];
                this.datos.enfermedades = data['enfermedades'];
                this.datos.vacunas = data['vacunas'];
                this.datos.estado = data['estado'];
                this.datos.id_cliente = data['id_cliente'];
                this.datos.id_animal = data['id_animal'];
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarArticulo(page, buscar, criterio);
            },
            ocultarListado1(){
                this.listado=0;
                this.errores = {};
            },
            guardarCategoria(){
                let me = this;
                axios.post('/categoria/guardar',this.datosCategoria).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.registrosCategoria();  
                    me.limpiarDatosCategoria();   
                })
                .catch(function(error){
                    console.log(error);
                });
                this.selectCategoria();
                this.selectAnimal();
                this.selectCliente();
            },
            async guardarUnidad(){
                try {
                    let me = this;
                    const res = await axios.post('/unidad/guardar',this.datosUnidad);
                    if(res.data.error==0){
                        //me.$toaster.error('Matricula ya existe...');
                        Swal.fire({
                            icon: 'error',
                            title: 'Nombre ya existe...',
                            text: 'Debe usar otra nombre!'
                        })
                    }
                    else{
                        me.registrosUnidad();
                        me.limpiarDatosUnidad();   
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
            async guardarProveedor(){
                let me = this;
                try {
                    const res = await axios.post('/proveedor/guardar',this.datosProveedor)
                     Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.registrosProveedor(); 
                    me.limpiarDatosProveedor(); 
                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                }      
                
            },      
            limpiarDatosCategoria(){
                this.datosCategoria = {
                    id : 0,
                    nombre : '',
                    descripcion : '',
                    estado : '1',
                }
                this.errores = {}
            },
            limpiarDatosUnidad(){
                this.datosUnidad = {
                    id : 0,
                    nombre : '',
                    abreviatura : '',
                    estado : '1',
                }
                this.errores = {}
            },
            limpiarDatosProveedor(){
                this.datosProveedor = {
                    id : 0,
                    nombre : '',
                    nit : '',
                    contacto : '',
                    direccion : '',
                    telefono : '',
                    descripcion : '',
                    estado : '1',
                }
                this.errores = {}

            },
            validarArticulo(){
                this.errorArticulo = 0;
                this.errorMostrarMsjArticulo = [];

                if(!this.datos.nombre) this.errorMostrarMsjArticulo.push("El nombre del Cliente no puede estar vacio ");
                if(this.errorMostrarMsjArticulo.length) this.errorArticulo=1;
                return this.errorArticulo;
            },
            selectedCliente(cliente){
                this.datos.cliente='';
                this.datos.id_cliente=0;
                this.isVisible = false;
                this.datos.cliente = cliente.nombre;
                this.datos.id_cliente = cliente.id;
            },
            desactivarArticulo(id){
                dangerConfirm.fire({
                    title: 'Esta seguro de Inhabilitar este Paciente??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Inhabilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/paciente/desactivar',{'id': id}).then(function (response) {
                        me.listarArticulo(1,'', 'nombre');
                        //me.listar2(1,'', 'nombre');
                        Swal.fire(
                        'Inhabilitado!',
                        'Este paciente se ha Inhabilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                    'Cancelado',
                    'Este paciente no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            activarArticulo(id){
                dangerConfirm.fire({
                    title: 'Esta seguro de Habilitar este Paciente??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/paciente/activar',{'id': id}).then(function (response) {
                        me.listarArticulo(1,'', 'nombre');
                        me.listar2(1,'', 'nombre');
                        Swal.fire(
                        'Habilitado!',
                        'Este paciente se ha Habilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                    'Cancelado',
                    'Este articulo no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            async guardarCliente(){
                // if(this.validarCliente()){
                //     return;
                // }
                try {
                    let me = this;
                    const res = await axios.post('/cliente/guardar',this.datosCliente);
                    if(res.data.error==0){
                        //me.$toaster.error('Matricula ya existe...');
                        // Swal.fire({
                        //     icon: 'error',
                        //     title: 'Matricula ya existe...',
                        //     text: 'Debe usar otra matricula!'
                        // })
                    }
                    else{
                        me.cerrarModal();
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
            obtenerCliente(){
                let me = this;
                var url='/cliente_id';
                axios.get(url).then(function(response){
                    me.datos.id_cliente= response.data.id_cliente.id;
                })
                .catch(function(error){
                    console.log(error);
                });
            }, 
            
            datosNuevoCliente(cliente) {
            //console.log(cliente);
            this.datosCliente = cliente;
                this.obtenerCliente();
                this.datos.cliente = cliente.nombre;
                //this.listarCliente("");
                this.selectCliente();



            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.datosCliente = {
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

                switch(modelo){
                    case "cliente":
                        {
                            switch(accion){
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal='Registro de Propietario'
                                        this.datosCliente = {
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
                                        this.datos.ciudad = data['ciudad'];
                                        this.datos.estado = data['estado'];
                                        this.datos.fecha_nacimiento = data['fecha_nacimiento'];
                                        this.datos.descuento = data['descuento'];
                                       break;
                                    }
                            }
                        }
              }
        
            }, 
        },
        mounted() {
            this.listarArticulo(1, this.buscar, this.criterio);
            //this.listar2(1, this.buscar, this.criterio);
            this.registrosCategoria();
            this.registrosUnidad();
            this.registrosProveedor();
            this.registrosArticulo();
            this.registrosArticuloProducto();
        }
    }
</script>
<style scoped lang="scss">
    .dropdown-wrapper{
        position: relative;
        //margin: 0 auto;

        .selected-item{
            height: 25px;
            //border: 2px solid lightgray;
            border-radius: 5px;
            padding: 5px 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            //font-size: 16px;
            //font-weight: 500;

            .drop-down-icon{
                transform: rotate(0deg);
                transition: all 0.5s ease;
                &.dropdown{
                    transform: rotate(180deg);
                }
            }
        }

        .dropdown-popover{
            position: absolute;
            border: 2px solid lightgray;
            top: 46;
            left: 0;
            right: 0;
            background-color: #fff;
            max-width: 100%;
            align-items: center;
            padding: 10px;
            visibility: hidden;
            transition: all 0.35s linear;
            max-height: 0px;
            overflow: hidden;


            &.visible{
                max-height: 450px;
                visibility: visible;
            }
            input{
                width: 100%;
                height: 30px;
                border: 2px solid lightgray;
                font-size: 18px;
                padding-left: 8px;
            }

            .options{
                width: 100%;
                padding-top: 12px;

                ul{
                    list-style: none;
                    text-align:left;
                    padding-left: 2px;
                    max-height: 200px;
                    overflow-y: scroll;
                    overflow-x: hidden;
                }

                li{
                    width: 100%;
                    border-bottom: 1px solid lightgray;
                    padding: 5px;
                    border: 1px solid lightgray;
                    background-color: #f1f1f1;
                    cursor: pointer;
                    &:hover {
                        background: #44536E;
                        color: #fff;
                        font-weight: bold;
                    }
                }
            }
        }
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
    .tamaño{
        width: 100px !important; 
    }
    .bg-disabled{
        background-color: #D8DBE1;
    }
    .bg-rojo{
     background-color: 	#FFD2D6 !important;
     /* color: white !important; */
    }
    .bg-azul{
     background-color: 	#E0FEFE !important;
     /* color: white !important; */
    }
    .line{
        height:3px !important;
    }

</style>

<template>
    <main class="main">
        <!-- <div class="container"> -->
        <div class="row">
            <div class="col">
                &nbsp;
                <div class="card">
                    <template v-if="listado==0">
                        <div class="card-header text-center text-white" style="background-color: #3399FF">
                            <div class="col-sm-12 bg-black d-flex justify-content-between align-items-center">
                                <h3 class="mb-0 col-md-11">PERMISOS</h3>
                                <!-- <button type="button" class="btn-close btn-close-white" @click="volverMenu()" aria-label="Close"></button> -->
                            </div>
                        </div>
                        <div class="card-header">
                            <button type="button" @click="frmGrupo()" class="btn btn-info text-white"
                                style='margin-left: 1%'>
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8">
                                &nbsp;&nbsp;
                                <div class="input-group" style='width:70%;margin-left: 3.3%'>
                                    <!-- <select class="form-select col-md-3" v-model="criterio">
                                        <option value="nombre">Nombre</option>
                                    </select>
                                    &nbsp;&nbsp;&nbsp; -->
                                    <input type="text" v-model="buscar" @keyup.enter="listarGrupo(1, buscar, criterio)"
                                        @keyup="BuscandoGrupo()" class="form-control" placeholder="Texto a buscar"
                                        >
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarGrupo(1, buscar, criterio)"
                                        class="btn btn-info text-white" ><i
                                            class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Light table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover"
                                style='width:96%;margin-left: 2.2%'>
                                <thead style="background-color: #46546C">
                                    <tr>
                                        <th scope="col" class="text-white"  width="10%">Número
                                        </th>
                                        <th scope="col" class="text-white" >Nombre</th>
                                        <th scope="col" class="text-white" >Descripcion</th>
                                        <th scope="col" class="text-white" >Estado</th>
                                        <th scope="col" class="text-white" >Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(grupo, index) in arrayGrupo" :key="index">
                                        <td v-text="index + 1" ></td>
                                        <td v-text="grupo.nombre" ></td>
                                        <td v-text="grupo.descripcion" ></td>
                                        <td>
                                            <template v-if="grupo.estado==1">
                                                <span class="badge bg-success">Activo</span>
                                            </template>
                                            <template v-else>
                                                <span class="badge bg-danger">Desactivo</span>
                                            </template>
                                        </td>
                                        <td >
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false"
                                                >Accion</button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#"
                                                        @click="verDetallePermiso(grupo)"><i
                                                            class="fa fa-eye text-secondary"></i> Ver detalle</a></li>
                                                <li><a class="dropdown-item" href="#"
                                                        @click="modificarDetallePermiso(grupo)"><i
                                                            class="fa fa-edit text-warning"></i> Modificar</a></li>

                                                <template v-if="grupo.estado==1">
                                                    <li><a class="dropdown-item" href="#"
                                                            @click="desactivarGrupo(grupo.id)"><i
                                                                class="fa fa-unlock text-success"></i> Desactivar</a>
                                                    </li>
                                                </template>
                                                <template v-else>
                                                    <li><a class="dropdown-item" href="#"
                                                            @click="activarGrupo(grupo.id)"><i
                                                                class="fa fa-lock text-danger"></i> Activar</a></li>
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
                                        <a class="page-link" href="#"
                                            @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page"
                                        :class="[page==isActived ? 'active' :'']">
                                        <a class="page-link" href="#"
                                            @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page">1</a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#"
                                            @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </template>





                    <template v-if="listado==1">
                        <div class="card-header text-center">
                            <div class="col-sm-12 bg-black d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 col-md-11">REGISTRO DE GRUPO - PERMISOS</h5>
                                <button type="button" class="btn-close btn-close" @click="volverGrupoListado()"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- <button type="button" class="btn btn-danger text-white" @click="volverGrupoListado()"><i class="fa fa-reply-all"></i>&nbsp;Volver</button>
                            <div class="card-header text-center"><h3 class="mb-0">REGISTRO DE GRUPO - PERMISOS</h3></div>       -->
                            <div class="form-group row">
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Nombre Grupo</label>
                                    <input type="text" class="form-control" v-model="datos.nombre">
                                    <div class="row" v-if="erroresGrupo.nombre">
                                        <div class="col-sm-10"><span
                                                class="text-danger p-1">{{erroresGrupo.nombre[0]}}</span></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Descripcion</label>
                                    <textarea class="form-control" v-model="datos.descripcion" rows="2"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="inputPassword" class="form-label">Estado</label>
                                    <div class="col-sm-10">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                id="inlineRadio1" value="1" v-model="datos.estado">
                                            <label class="form-check-label" for="inlineRadio1">Activo</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                id="inlineRadio2" value="0" v-model="datos.estado">
                                            <label class="form-check-label" for="inlineRadio2">Inactivo</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label>Permisos de Grupo <span style="color:red;">(*Seleccione)</span></label>
                                    <button type="button" class="btn btn-info text-white position-relative"
                                        data-bs-toggle="modal" data-bs-target="#modalPermiso"><i
                                            class="fa fa-search"></i> Agregar permisos</button>
                                </div>
                                <div class="col-md-12">
                                    <div v-show="errorGrupo" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjGrupo" :key="error" v-text="error">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="form-group row">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead style="background-color: #46546C">
                                            <tr>
                                                <th scope="col" class="text-white" width="10%">Opción</th>
                                                <th scope="col" class="text-white" width="10%">Numero</th>
                                                <th scope="col" class="text-white">Nombre</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle, index) in arrayDetalle" :key="index">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button"
                                                        class="btn btn-danger btn-sm"><i
                                                            class="icon-trash text-white"></i></button>
                                                </td>
                                                <td v-text="index+1"></td>
                                                <td v-text="detalle.nombre"></td>
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
                                <button class="btn btn-danger me-md-2 text-white" type="button"
                                    @click="cancelarGrupo()">Cancelar</button>
                                <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==1" type="button"
                                    @click="guardarDetalleForm()">Guardar</button>
                                <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==2" type="button"
                                    @click="modificarGrupo()">Modificar</button>
                            </div>
                        </div>
                    </template>

                    <template v-if="listado==2">
                        <div class="card-header text-center">
                            <div class="col-sm-12 bg-black d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 col-md-11">REGISTRO DE GRUPO - PERMISOS</h5>
                                <button type="button" class="btn-close btn-close" @click="volverGrupoListado()"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- <button type="button" class="btn btn-danger text-white" @click="volverGrupoListado()"><i class="fa fa-reply-all"></i>&nbsp;Volver</button>
                            <div class="card-header text-center"><h3 class="mb-0">REGISTRO DE GRUPO - PERMISOS</h3></div>     -->
                            <div class="form-group row">
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Nombre Grupo</label>
                                    <input type="text" class="form-control" v-model="datos.nombre" disabled>
                                </div>

                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Descripcion</label>
                                    <textarea class="form-control" v-model="datos.descripcion" rows="2"
                                        disabled></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="inputPassword" class="form-label">Estado</label>
                                    <div class="col-sm-10">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                id="inlineRadio1" value="1" v-model="datos.estado" disabled>
                                            <label class="form-check-label" for="inlineRadio1">Activo</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                id="inlineRadio2" value="0" v-model="datos.estado" disabled>
                                            <label class="form-check-label" for="inlineRadio2">Inactivo</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <!--<label>Permisos de Grupo <span style="color:red;">(*Seleccione)</span></label>
                                    <button type="button" class="btn btn-info text-white position-relative"
                                        data-bs-toggle="modal" data-bs-target="#modalPermiso" disabled><i
                                            class="fa fa-search"></i> Agregar permisos</button>-->
                                </div>
                            </div>

                            <br>
                            <div class="form-group row">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead style="background-color: #46546C">
                                            <tr>

                                                <th scope="col" class="text-white">Numero</th>
                                                <th scope="col" class="text-white">Nombre</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle, index) in arrayDetalle" :key="index">
                                                <td v-text="index+1"></td>
                                                <td v-text="detalle.nombre"></td>
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
                        </div>
                    </template>

                    <template v-if="listado==3">
                        <div class="card-header text-center">
                            <div class="col-sm-12 bg-black d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 col-md-11">REGISTRO DE GRUPO - PERMISOS</h5>
                                <button type="button" class="btn-close btn-close" @click="volverGrupoListado()"
                                    aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- <button type="button" class="btn btn-danger text-white" @click="volverGrupoListado()"><i class="fa fa-reply-all"></i>&nbsp;Volver</button>
                            <div class="card-header text-center"><h3 class="mb-0">REGISTRO DE GRUPO - PERMISOS</h3></div>     -->
                            <div class="form-group row">
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Nombre Grupo</label>
                                    <input type="text" class="form-control" v-model="datos.nombre">
                                </div>

                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Descripcion</label>
                                    <textarea class="form-control" v-model="datos.descripcion" rows="2"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="inputPassword" class="form-label">Estado</label>
                                    <div class="col-sm-10">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                id="inlineRadio1" value="1" v-model="datos.estado">
                                            <label class="form-check-label" for="inlineRadio1">Activo</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                                id="inlineRadio2" value="0" v-model="datos.estado">
                                            <label class="form-check-label" for="inlineRadio2">Inactivo</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label>Permisos de Grupo <span style="color:red;">(*Seleccione)</span></label>
                                    <button @click="modalPermisoForm('')" type="button"
                                        class="btn btn-info text-white position-relative" data-bs-toggle="modal"
                                        data-bs-target="#modalPermiso"><i class="fa fa-search"></i> Agregar
                                        permisos</button>
                                </div>
                            </div>

                            <br>
                            <div class="form-group row">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead style="background-color: #46546C">
                                            <tr>
                                                <th scope="col" class="text-white">Opcion</th>
                                                <th scope="col" class="text-white" width="10%">Numero</th>
                                                <th scope="col" class="text-white">Nombre</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle, index) in arrayDetalle" :key="index">
                                                <td>
                                                    <button @click="eliminarDetalle2(index)" type="button"
                                                        class="btn btn-danger btn-sm"><i
                                                            class="icon-trash text-white"></i></button>
                                                </td>
                                                <td v-text="index+1"></td>
                                                <td v-text="detalle.nombre"></td>
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
                        </div>
                        <div class="card-footer d-flex justify-content-end">
                            <button class="btn btn-info btn-lg text-white"
                                @click="modificarDetalleForm(), modificarDetalleForm()">Modificar</button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <!-- </div> -->
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' :modal}" role="dialog" aria-labelledby="myModalLabel"
            style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header text-white" style="background-color: #66B3FF">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            @click="cerrarModal()"></button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">

                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-10"><input type="text" class="form-control" v-model="datos.nombre">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
                                <div class="col-sm-10"><input type="text" class="form-control"
                                        v-model="datos.descripcion"></div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Estado</label>
                                <div class="col-sm-10">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio1" value="1" v-model="datos.estado">
                                        <label class="form-check-label" for="inlineRadio1">Activo</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio2" value="0" v-model="datos.estado">
                                        <label class="form-check-label" for="inlineRadio2">Inactivo</label>
                                    </div>
                                </div>
                            </div>

                            <div v-show="errorGrupo" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjGrupo" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" @click="cerrarModal()">Cerrar</button>
                        <button type="button" class="btn btn-info text-white"
                            @click="modificarGrupo()">modificarCliente</button>
                        <!--<button type="button" v-if="tipoAccion==2" class="btn btn-info text-white" @click="modificarGrupo()">Modificar</button>-->
                    </div>
                </div>
            </div>
        </div>
        <!--Fin del modal-->

        <!--Modal Formulario Grupo-->
        <div class="modal fade" id="modalPermiso" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
            style="display: none;">
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
                                    <input type="text" v-model="buscarP" @keyup.enter="modalPermisoForm(buscarP)"
                                        @keyup="BuscandoPermisoForm()" class="form-control"
                                        placeholder="Texto a buscar">&nbsp;&nbsp;
                                    <button type="submit" @click="modalPermisoForm(buscarP)"
                                        class="btn btn-info text-white"><i class="fa fa-search text-white"></i>
                                        Buscar</button>
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
                                                    <input type="checkbox" v-model="selectAll"
                                                        :data-bs-dismiss="selectAll == false ? 'modal' : ''"
                                                        @click="selected()">
                                                </label>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(permiso, index) in arrayFormulario" :key="index">
                                        <td v-text="index+1"></td>
                                        <td v-text="permiso.nombre"></td>
                                        <td>
                                            <button type="button" @click="seleccionarPermiso(permiso,index)"
                                                class="btn btn-success btn-sm"><i
                                                    class="fa fa-check text-white"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cerrar</button>
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
        data() {
            return {
                datos: {
                    id: 0,
                    nombre: '',
                    descripcion: '',
                    estado: '1',
                    detalle: []
                },

                arrayDetalle: [],
                arrayPermiso: [],
                arrayGrupo: [],
                arrayFormulario: [],
                listado: 0,
                modal: 0,
                tituloModal: '',
                tipoAccion: 0,
                errorGrupo: 0,
                errorMostrarMsjGrupo: [],
                pagination: {
                    'total': 0,
                    'current_page': 0,
                    'per_page': 0,
                    'last_page': 0,
                    'from': 0,
                    'to': 0,
                },
                offset: 3,
                criterio: 'nombre',
                buscar: '',
                buscarP: '',
                erroresGrupo: {},
                selectAll: false,
                setTimeoutBuscador: '',
                vista: 0,
                listadoMenu: 0,
                is_busy: 0,
            }
        },
        computed: {
            isActived: function () {
                return this.pagination.current_page;
            },
            pagesNumber: function () {
                if (!this.pagination.to) {
                    return [];
                }
                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods: {
            volverMenu() {
                this.vista = 0;
                this.$emit('cerrarMenu', this.listadoMenu);
            },
            modalPermisoForm(buscar) {
                let me = this;
                var url = '/formulario/listar?buscar=' + buscar;
                axios.get(url).then(function (response) {
                        me.arrayFormulario = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            modalPermisoForm2(buscar, id_grupo) {
                let me = this;
                var url = '/formulario/listar2?buscar=' + buscar + '&id_grupo=' + id_grupo;
                axios.get(url).then(function (response) {
                        me.arrayFormulario = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },

            modalPermisoFormBusquedaRapida() {
                let me = this;
                var url = '/formulario/listar?buscar=' + me.buscarP;
                axios.get(url).then(function (response) {
                        me.arrayFormulario = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            BuscandoPermisoForm() {
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.modalPermisoFormBusquedaRapida, 350)
            },
            selected() {
                this.arrayDetalle = [];
                if (!this.selectAll) {
                    for (let i in this.arrayFormulario) {
                        this.arrayDetalle.push({
                            id_formulario: this.arrayFormulario[i].id,
                            nombre: this.arrayFormulario[i].nombre,
                        });
                    }
                    this.arrayFormulario = []
                } else {
                    this.modalPermisoForm('');
                }
            },
            async guardarDetalleForm() {
                try {
                    if (this.arrayDetalle.length <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'No Existen Permisos agregados!'
                        })
                    } else {
                        let me = this;
                        me.datos.detalle = me.arrayDetalle;
                        const res = await axios.post('/detalle_form/guardar', this.datos)
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Registro agregado...',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.volverGrupoListado();
                        me.listarGrupo(1, '', 'nombre');
                    }
                } catch (error) {
                    if (error.response.data) {
                        this.erroresGrupo = error.response.data.errors;
                    }
                }
            },
            async modificarDetalleForm() {
                let me = this;
                try {
                    if (this.arrayDetalle.length <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'No Existen Permisos agregados!'
                        })
                    } else {
                        me.datos.detalle = me.arrayDetalle;
                        const res = await axios.post('/detalle_form/modificar2', this.datos)
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Registro agregado...',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        me.volverGrupoListado();
                        me.listarGrupo(1, '', 'nombre');
                    }
                } catch (error) {
                    if (error.response.data) {
                        this.erroresGrupo = error.response.data.errors;
                    }
                }
            },

            listarGrupo(page, buscar, criterio) {
                let me = this;
                var url = '/grupo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function (response) {
                        me.arrayGrupo = response.data.data;
                        me.pagination = {
                            total: response.data.total,
                            current_page: response.data.current_page,
                            per_page: response.data.per_page,
                            last_page: response.data.last_page,
                            from: response.data.from,
                            to: response.data.to
                        }
                    })
                    .catch(function (error) {
                        console.log(error)
                    });
            },
            listarGrupoBusquedaRapida() {
                let me = this;
                var url = '/grupo?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function (response) {
                        me.arrayGrupo = response.data.data;
                        me.pagination = {
                            total: response.data.total,
                            current_page: response.data.current_page,
                            per_page: response.data.per_page,
                            last_page: response.data.last_page,
                            from: response.data.from,
                            to: response.data.to
                        }
                    })
                    .catch(function (error) {
                        console.log(error)
                    });
            },
            BuscandoGrupo() {
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarGrupoBusquedaRapida, 350)
            },

            cambiarPagina(page, buscar, criterio) {
                let me = this;
                me.pagination.current_page = page;
                me.listarGrupo(page, buscar, criterio);
            },
            // listarPermiso(buscarP){
            //     let me = this;
            //     var url='/permiso/listarSinPaginate?buscar=' + buscarP;
            //     axios.get(url).then(function(response){
            //         me.arrayPermiso= response.data;
            //     })
            //     .catch(function(error){
            //         console.log(error);
            //     });
            // },
            encuentra(id) {
                var sw = 0;
                for (var i = 0; i < this.arrayDetalle.length; i++) {
                    if (this.arrayDetalle[i].id_formulario == id) {
                        sw = true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index) {
                let me = this;
                me.arrayDetalle.splice(index, 1);
            },
            eliminarDetalle2(index) {
                let me = this;
                if (this.arrayDetalle.length == 1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No se puede eliminar todos los detalles'
                    })
                } else {
                    me.arrayDetalle.splice(index, 1);
                }
            },
            seleccionarPermiso(data = [], index) {
                let me = this;
                if (me.encuentra(data['id'])) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Ese formulario ya se encuentra agregado!'
                    })
                } else {
                    me.arrayDetalle.push({
                        id_formulario: data['id'],
                        nombre: data['nombre'],
                    });
                    me.arrayFormulario.splice(index, 1);
                }
            },
            cancelarGrupo() {
                let me = this;
                me.arrayDetalle = [];
                me.arrayPermiso = [];
                me.arrayFormulario = [],
                    me.datos.nombre = '';
                me.datos.descripcion = '';
                me.datos.estado = '1';
                me.buscarP = '';
                me.listado = 0;
                me.tipoAccion = 0;
                me.erroresGrupo = {};
                me.buscar = '';
                me.selectAll = false
            },
            volverGrupoListado() {
                let me = this;
                me.arrayDetalle = [];
                me.arrayPermiso = [];
                me.arrayFormulario = [],
                    me.datos.nombre = '';
                me.datos.descripcion = '';
                me.datos.estado = '1';
                me.buscarP = '';
                me.listado = 0;
                me.tipoAccion = 0;
                me.erroresGrupo = {};
                me.buscar = '';
                me.selectAll = false

            },
            editar(data = []) {
                let me = this;
                me.listado = 1;
                me.tipoAccion = 2;
                me.datos.id = data['id'];
                me.datos.nombre = data['nombre'];
                me.datos.descripcion = data['descripcion'];
                me.datos.estado = data['estado'];

                var url = '/grupo/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function (response) {
                        me.arrayDetalle = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            verDetallePermiso(data = []) {
                let me = this;
                me.listado = 2;
                me.datos.id = data['id'];
                me.datos.nombre = data['nombre'];
                me.datos.descripcion = data['descripcion'];
                me.datos.estado = data['estado'];

                var url = '/grupo/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function (response) {
                        me.arrayDetalle = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            modificarDetallePermiso(data = []) {
                let me = this;
                me.listado = 3;
                me.datos.id = data['id'];
                me.datos.nombre = data['nombre'];
                me.datos.descripcion = data['descripcion'];
                me.datos.estado = data['estado'];

                var url = '/grupo/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function (response) {
                        me.arrayDetalle = response.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            modificarGrupo() {
                if (this.validarGrupo()) {
                    return;
                }
                let me = this;
                axios.put('/grupo/modificar', {
                    'id': me.datos.id,
                    'nombre': me.datos.nombre,
                    'descripcion': me.datos.descripcion,
                    'estado': me.datos.estado,
                    'detalle': me.arrayDetalle
                }).then(function (response) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro modificado...',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    me.volverGrupoListado();
                    me.listarGrupo(1, this.buscar, this.criterio);
                }).catch(function (error) {
                    console.log(error);
                });
            },
            validarGrupo() {
                this.errorGrupo = 0;
                this.errorMostrarMsjGrupo = [];

                if (!this.datos.nombre) this.errorMostrarMsjGrupo.push("El nombre del Grupo no puede estar vacio ");
                if (this.errorMostrarMsjGrupo.length) this.errorGrupo = 1;
                return this.errorGrupo;
            },
            frmGrupo() {
                this.listado = 1;
                this.tipoAccion = 1;
                this.arrayDetalle = [];
                this.modalPermisoForm('');
                this.datos = {
                    id: 0,
                    nombre: '',
                    descripcion: '',
                    estado: '1',
                }

            },
            desactivarGrupo(id) {
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
                        axios.put('/grupo/desactivar', {
                            'id': id
                        }).then(function (response) {
                            me.listarGrupo(1, '', 'nombre');
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
            activarGrupo(id) {
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
                        axios.put('/grupo/activar', {
                            'id': id
                        }).then(function (response) {
                            me.listarGrupo(1, '', 'nombre');
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
            //this.listarGrupo(1, this.buscar, this.criterio);
        },
        created() {
            this.listarGrupo(1, this.buscar, this.criterio);
            this.modalPermisoForm('');
        }
    }

</script>
<style>
    .modal-content {
        width: 100% !important;
        position: absolute !important;
    }

    .mostrar {
        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #d1cdcd7a !important;
    }

    .div-error {
        display: flex;
        justify-content: center;
    }

    .text-error {
        color: red !important;
        font-weight: bold;
    }

    .footer {
        position: relative !important;
        width: auto !important;
    }

</style>

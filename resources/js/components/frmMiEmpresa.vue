<template>
    <main class="main master-company">
        <company-settings-workspace
            :data="datosEmpresa"
            :user="usuario"
            :report-logo="imagenMinEmpresa"
            :system-logo="imagenMinEmpresaLogo"
            :user-logo="imagenMinEmpresaLogoUsuario"
            :login-logo="imagenMinEmpresaLogoLogin"
            :login-background="imagenMinEmpresaFondoLogin"
            :disabled="isDisabled"
            :loading="loading"
            :saving="saving"
            @edit="modificarEmpresa"
            @cancel="cancelarModificarEmpresa"
            @save="guardarEmpresa"
            @report-logo="obtenerImagenTienda"
            @system-logo="obtenerLogo"
            @user-logo="obtenerLogoUsuario"
            @login-logo="obtenerLogoLogin"
            @login-background="obtenerFondoLogin"
            @preview-system-logo="verLogo"
            @preview-user-logo="verLogoUsuario"
            @preview-login-logo="verLogoLogin"
            @preview-login-background="verFondoLogin"
        />
        <div v-if="false">
        <app-module-header
            eyebrow="Datos maestros · Configuración"
            title="Mi empresa"
            subtitle="Gestiona la identidad comercial y los recursos visuales utilizados en reportes, menú y acceso al sistema."
        >
            <template #actions>
                <app-button
                    v-if="tipoAccionEmpresa == 1"
                    icon="img/menu/configuracion.png"
                    @click="modificarEmpresa"
                >
                    Editar configuración
                </app-button>
                <template v-else>
                    <app-button variant="ghost" @click="cancelarModificarEmpresa">Cancelar</app-button>
                    <app-button @click="guardarEmpresa">Guardar cambios</app-button>
                </template>
            </template>
        </app-module-header>

        <section class="master-company__metrics">
            <app-metric-card
                label="Empresa"
                :value="datosEmpresa.nombre || 'Sin configurar'"
                hint="Identidad principal"
                icon="img/menu/tienda.png"
            />
            <app-metric-card
                label="NIT"
                :value="datosEmpresa.nit || 'Sin registrar'"
                hint="Identificación tributaria"
                icon="img/menu/configuracion.png"
                tone="cyan"
            />
            <app-metric-card
                label="Configuración"
                :value="isDisabled ? 'Protegida' : 'En edición'"
                :hint="isDisabled ? 'Activa edición para realizar cambios' : 'Revisa antes de guardar'"
                icon="img/menu/control.png"
                tone="neutral"
            />
        </section>
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                    <div class="card-header text-center text-white" style="background-color: #3399FF">
                        <h3 class="mb-0">Mi Empresa</h3>
                    </div>
                    <!-- prueba card -->
                    <div class="row mt-2">
                        <div class="row m-4">
                            <div class="row col-md-9">
                                <div class="col-md-6 mb-3">
                                    <label for="exampleInputPassword1" class="form-label">
                                        NOMBRE DE LA EMPRESA
                                    </label>
                                    <input type="text" class="form-control" v-model="datosEmpresa.nombre" :disabled="isDisabled">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        NIT
                                    </label>
                                    <input type="text" class="form-control" v-model="datosEmpresa.nit" :disabled="isDisabled">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="exampleInputPassword1" class="form-label">
                                        LOGO (Reportes)<span style="color:red;" >(*Resolucion 300x300)</span>
                                    </label>
                                    <div class="mb-6">
                                        <input class="form-control" type="file" id="formFile" @change="obtenerImagenTienda" :disabled="isDisabled">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="exampleInputPassword1" class="form-label">
                                        TELEFONO
                                    </label>
                                    <input type="text" class="form-control" v-model="datosEmpresa.telefono" value="''" :disabled="isDisabled">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="exampleInputPassword1" class="form-label">
                                        DIRECCION
                                    </label>
                                    <input type="text" class="form-control" v-model="datosEmpresa.direccion" value="''" :disabled="isDisabled">
                                </div>
                                <div class="row" v-if="usuario.id == 1">
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">
                                            LOGO SISTEMA (Resolucion largo 100px)
                                        </label>
                                        <div class="input-group mb-3">
                                            <input class="form-control" type="file" id="formFile" @change="obtenerLogo" :disabled="isDisabled">
                                            <div class="input-group-append">
                                                <button class="btn btn-success me-md-2 text-white" type="button" @click="verLogo()">Ver</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">
                                            LOGO USUARIO (Resolucion largo 100px)
                                        </label>
                                        <div class="input-group mb-3">
                                            <input class="form-control" type="file" id="formFile" @change="obtenerLogoUsuario" :disabled="isDisabled">
                                            <div class="input-group-append">
                                                <button class="btn btn-success me-md-2 text-white" type="button" @click="verLogoUsuario()">Ver</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label pb-0 mb-0">
                                            IMAGEN DE FONDO LOGIN
                                        </label>
                                        <label for="exampleInputPassword1" class="form-label p-0 m-0">
                                            <span style="color:red;" >(*Resolución Recomendada 1920x1080 tamaño max 200kb)</span>
                                        </label>
                                        <div class="input-group mb-3">
                                            <input class="form-control" type="file" id="formFile" @change="obtenerFondoLogin" :disabled="isDisabled">
                                            <div class="input-group-append">
                                                <button class="btn btn-success text-white" type="button" @click="verFondoLogin()">Ver</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label pb-0 mb-0">
                                            LOGO (Inicio de Sesion)
                                        </label>
                                        <label for="exampleInputPassword1" class="form-label p-0 m-0">
                                            <span style="color:red;" >(*Resolución Recomendada 430x310 tamaño max 100kb)</span>
                                        </label>
                                        <div class="input-group mb-3">
                                            <input class="form-control" type="file" id="formFile" @change="obtenerLogoLogin" :disabled="isDisabled">
                                            <div class="input-group-append">
                                                <button class="btn btn-success me-md-2 text-white" type="button" @click="verLogoLogin()">Ver</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" v-else>
                                    <div class="col-md-12 row">
                                        <div>
                                            <button class="btn btn-success text-white button" v-if="tipoAccionEmpresa==1" type="button" @click="modificarEmpresa()">Actualizar</button>
                                            <button class="btn btn-info text-white button" type="button" v-if="tipoAccionEmpresa==2" @click="guardarEmpresa()">Modificar</button>
                                            <button class="btn btn-danger text-white button" v-if="tipoAccionEmpresa==2" type="button" @click="cancelarModificarEmpresa()">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div v-if="usuario.id == 1">
                                    <div class="card mb-4" style="--cui-card-cap-bg: #00C29D">
                                        <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                            <strong> LOGO </strong>
                                        </div>
                                        <div class="card-body row text-center">
                                            <div class="col">
                                                <center>
                                                    <figure><img width="200" height="200" :src="imagenMinEmpresa" onerror="this.src='img/logo_default/logo.png'" alt=" "></figure>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="col-md-12 mb-2 mt-2 row">
                                            <div class="col-md-1">&nbsp;</div>
                                            <div class="col-md-6 row  mt-0 pt-0 border border-light align-bottom text-center">
                                                <span>COLOR LOGIN</span>
                                                <div>
                                                    <input class="border border-light" type="color" v-model="datosEmpresa.colorLogin"  id="color"  :disabled="isDisabled">
                                                </div>
                                            </div>
                                            <div class="col-md-6 row  mt-0 pt-0 border border-light align-bottom text-center">
                                                <span>COLOR MENU</span>
                                                <div>
                                                    <input class="border border-light" type="color" v-model="datosEmpresa.colorMenu"  id="color"  :disabled="isDisabled">
                                                </div>
                                            </div>
                                            <div class="col-md-1">&nbsp;</div>
                                            <div class="col-md-4">
                                                <div>
                                                    <button class="btn btn-success text-white button" v-if="tipoAccionEmpresa==1" type="button" @click="modificarEmpresa()">Actualizar</button>
                                                </div>
                                                <div class="pb-1">
                                                    <button class="btn btn-info text-white button" type="button" v-if="tipoAccionEmpresa==2" @click="guardarEmpresa()">Modificar</button>
                                                </div>
                                                <div>
                                                    <button class="btn btn-danger text-white button" v-if="tipoAccionEmpresa==2" type="button" @click="cancelarModificarEmpresa()">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="card mb-4" style="--cui-card-cap-bg: #00C29D">
                                        <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                            <strong> LOGO </strong>
                                        </div>
                                        <div class="card-body row text-center">
                                            <div class="col">
                                                <center>
                                                    <figure><img width="120" height="120" :src="imagenMinEmpresa" onerror="this.src='img/logo_default/logo.png'" alt=" "></figure>
                                                </center>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                    </div>
                    <!-- prueba fin card -->
                    <!-- Light table -->
                    <!-- <div class="table-responsive">
                        <div v-if="usuario.id == 1">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2.5%'>
                                <thead style="background-color: #46546C">
                                    <tr>
                                        <th scope="col" class="text-white">Nombre</th>
                                        <th scope="col" class="text-white">COD. Tienda</th>
                                        <th scope="col" class="text-white">COD. Almacen</th>
                                        <th scope="col" class="text-white">Direccion</th>
                                        <th scope="col" class="text-white">Foto</th>
                                        <th scope="col" class="text-white">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="personal in arrayPersonal" :key="personal.id">
                                        <td v-text="personal.cod_tienda"></td>
                                        <td v-text="personal.cod_almacen"></td>
                                        <td v-text="personal.nombre"></td>
                                        <td v-text="personal.direccion"></td>
                                        <td><img :src="'img/mi_empresa/'+ personal.foto" onerror="this.src='img/logo_default/logo.png'" width="60" align="left" alt=""></td>
                                        <td>
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Acción</button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" @click="abrirModal('personal','modificar',personal)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> -->
                    <!-- Card Pagination -->
                    <!-- <div class="card-footer py-4">
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
                    </div> -->
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
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Nombre</label>
                                <div class="col-sm-9"><input type="text" class="form-control" v-model="datos.nombre" placeholder="Nombre"></div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Código Tienda</label>
                                <div class="col-sm-9"><input type="text" class="form-control" v-model="datos.cod_tienda" placeholder="Código Tienda"></div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Código Almacen</label>
                                <div class="col-sm-9"><input type="text" class="form-control" v-model="datos.cod_almacen" placeholder="Codigo Almacen"></div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Dirección</label>
                                <div class="col-sm-9"><input type="text" class="form-control" v-model="datos.direccion" placeholder="Dirección"></div>
                            </div>
                            <div class="row mb-3">
                                <label for="inputPassword" class="col-sm-3 col-form-label">Estado</label>
                                <div class="col-sm-9">
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
                            <div class="row pb-3">
                                    <label class="col-md-3 form-control-label" for="imagen">Foto</label>
                                    <div class="col-md-9">
                                        <input class="form-control" type="file" id="formFile" @change="obtenerImagen">
                                    </div>
                            </div>
                            <center>
                                <figure><img width="100" height="100" :src="imagenMin" alt=" " onerror="this.src='img/logo_default/logo.png'"></figure>
                            </center>
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
                        <button type="button" v-if="tipoAccion==2" class="btn btn-info text-white" @click="modificarTienda()">Modificar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->

        </div>
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
                    nit : '',
                    representante : '',
                    direccion : '',
                    telefono : '',
                    descripcion : '',
                    foto : '',
                    imagenActual : '',
                    correo : '',
                    sitio_web : '',
                    localidad:'',
                    cod_tienda:'',
                    cod_almacen:'',
                    estado: '1',
                },
                datosEmpresa: {
                    id : 0,
                    nombre : '',
                    nit : '',
                    telefono : '',
                    direccion: '',

                    foto: '',
                    imagenActual: '',

                    logo: '',
                    imagenActualLogo: '',

                    logo_login: '',
                    imagenActualLogoLogin: '',

                    logo_usuario: '',
                    imagenActualLogoUsuario: '',

                    fondo_login: '',
                    imagenActualFondoLogin: '',

                    colorLogin: '',
                    colorMenu: '',
                },
                buffer : '',

                cargo_registro :0,
                departamento_registro :0,
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
                arrayTipodepartamento: [],

                tipoAccionEmpresa : 1,
                isDisabled : true,

                imagenMiniatura : '',
                imagenMiniaturaTienda : '',

                imagenMiniaturaL : '',
                imagenMiniaturaLogo : '',

                imagenMiniaturaLL: '',
                imagenMiniaturaLogoLogin : '',

                imagenMiniaturaU: '',
                imagenMiniaturaLogoUsuario : '',

                imagenMiniaturaFL: '',
                imagenMiniaturaFondoLogin : '',

                usuario : {},
                loading: true,
                saving: false,
            }
        },
        created(){
                this.imagenMiniaturaTienda = 'img/logo_default/cargando.gif'
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
            imagenMin(){
                return this.imagenMiniatura;
                return this.datos.imagenActual;
            },
            imagenMinEmpresa(){
                return this.imagenMiniaturaTienda;
                return this.mi_empresa.imagenActual;
            },
            imagenMinEmpresaLogo(){
                return this.imagenMiniaturaL;
                return this.datosEmpresa.imagenActualLogo;
            },
            imagenMinEmpresaLogoLogin(){
                return this.imagenMiniaturaLL;
                return this.datosEmpresa.imagenActualLogoLogin;
            },
            imagenMinEmpresaLogoUsuario(){
                return this.imagenMiniaturaU;
                return this.datosEmpresa.imagenActualLogoUsuario;
            },
            imagenMinEmpresaFondoLogin(){
                return this.imagenMiniaturaFL;
                return this.datosEmpresa.imagenActualFondoLogin;
            }
        },

        methods : {
            usuarioAuth(){
                let me=this;
                var url='/usuario_auth';
                axios.get(url).then(function(response){
                    me.usuario=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            datosMiEmpresa(){
                let me=this;
                me.imagenMiniaturaTienda = 'img/logo_default/cargando.gif';
                return new Promise(function(resolve){
                setTimeout(function(){

                    var url='/mi_empresa/datos';
                    axios.get(url).then(function(response){
                        me.datosEmpresa.id=response.data[0].id;
                        me.datosEmpresa.nombre=response.data[0].nombre;
                        me.datosEmpresa.nit=response.data[0].nit;
                        me.datosEmpresa.telefono=response.data[0].telefono;
                        me.datosEmpresa.direccion=response.data[0].direccion;

                        me.datosEmpresa.foto = response.data[0].foto;
                        me.imagenMiniaturaTienda = 'img/logo/' + me.datosEmpresa.foto;
                        me.datosEmpresa.imagenActual = me.datosEmpresa.foto;

                        me.datosEmpresa.logo = response.data[0].logo_sistema;
                        me.imagenMiniaturaL = 'img/logo/' + me.datosEmpresa.logo;
                        me.datosEmpresa.imagenActualLogo = me.datosEmpresa.logo;

                        me.datosEmpresa.logo_login = response.data[0].logo_login;
                        me.imagenMiniaturaLL = 'img/logo/' + me.datosEmpresa.logo_login;
                        me.datosEmpresa.imagenActualLogoLogin = me.datosEmpresa.logo_login;

                        me.datosEmpresa.logo_usuario = response.data[0].logo_usuario;
                        me.imagenMiniaturaU = 'img/logo/' + me.datosEmpresa.logo_usuario;
                        me.datosEmpresa.imagenActualLogoUsuario = me.datosEmpresa.logo_usuario;

                        me.datosEmpresa.fondo_login = response.data[0].fondo_login;
                        me.imagenMiniaturaFL = 'img/logo/' + me.datosEmpresa.fondo_login;
                        me.datosEmpresa.imagenActualFondoLogin = me.datosEmpresa.fondo_login;

                        me.datosEmpresa.colorLogin = response.data[0].color_login;
                        me.datosEmpresa.colorMenu = response.data[0].color_menu;
                    })
                    .catch(function(error){
                        console.log(error, 'PRUEBA ERROR')
                    })
                    .finally(resolve);
                }, 300);
                });
            },
            listarEmpresa(page, buscar, criterio){
                let me=this;
                var url='/tienda?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
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

            cantidadTiendas(){
                let me=this;
                var url='/mi_empresa/cantidad';
                axios.get(url).then(function(response){
                    me.personal_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarEmpresa(page, buscar, criterio);
            },
            modificarTienda(){
                if(this.validarTienda()){
                    return;
                }
                if(this.datos.imagenActual != this.datos.foto){
                    axios.delete('/tienda/deleteTienda/' + this.datos.imagenActual).then(function(response){
                    }).catch(function(error){
                        console.log(error);
                    });
                }
                let me = this;
                axios.put('/tienda/modificar',this.datos).then(function(response){
                     Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro modificado...',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    me.cerrarModal();
                    me.listarEmpresa(1,'', 'personal.nombre');
                }).catch(function(error){
                    console.log(error);
                });
            },
            validarTienda(){
                this.errorPersonal = 0;
                this.errorMostrarMsjPersonal = [];

                if(!this.datos.nombre) this.errorMostrarMsjPersonal.push("El nombre de Mi Empresa no puede estar vacío ");
                if(this.errorMostrarMsjPersonal.length) this.errorPersonal=1;
                return this.errorPersonal;
            },
            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.errorPersonal = 0;
                this.datos = {
                    id : 0,
                    nombre : '',
                    nit : '',
                    representante : '',
                    direccion : '',
                    telefono : '',
                    descripcion : '',
                    foto : '',
                    imagenActual : '',
                    correo : '',
                    sitio_web : '',
                    localidad:'',
                    cod_tienda:'',
                    cod_almacen:'',
                    estado: '1',
                }
            },
            abrirModal(modelo, accion, data=[]){
                this.errorPersonal = 0;
                this.errorMostrarMsjPersonal = [];
                this.imagenMiniatura = '';
                this.datos.imagenActual = '';
                switch(modelo){
                    case "personal":
                        {
                            switch(accion){
                                case 'modificar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'Modificar Mi Empresa';
                                        this.tipoAccion = 2;
                                        this.datos.id = data['id'];
                                        this.datos.nombre = data['nombre'];
                                        this.datos.ci = data['ci'];
                                        this.datos.nit = data['nit'];
                                        this.datos.representante = data['representante'];
                                        this.datos.direccion = data['direccion'];
                                        this.datos.telefono = data['telefono'];
                                        this.datos.descripcion = data['descripcion'];
                                        this.datos.localidad = data['localidad'];
                                        this.datos.correo = data['correo'];
                                        this.datos.sitio_web = data['sitio_web'];
                                        this.datos.foto = data['foto'];
                                        this.datos.imagenActual = data['foto'];
                                        this.imagenMiniatura= 'img/mi_empresa/'+ this.datos.foto;
                                        this.datos.cod_tienda=data['cod_tienda'];
                                        this.datos.cod_almacen=data['cod_almacen'];
                                        this.datos.estado=data['estado'];
                                       break;
                                    }
                            }
                        }
                }

            },

            guardarEmpresa(){
                let me = this;
                me.saving = true;
                if(me.datosEmpresa.imagenActual != me.datosEmpresa.foto){
                    axios.delete('/tienda/delete/' + me.datosEmpresa.imagenActual).then(function(response){
                    }).catch(function(error){
                        console.log(error);
                    });
                }

                if(me.datosEmpresa.imagenActualLogo != me.datosEmpresa.logo){
                    axios.delete('/tienda/delete/' + me.datosEmpresa.imagenActualLogo).then(function(response){
                    }).catch(function(error){
                        console.log(error);
                    });
                }

                if(me.datosEmpresa.imagenActualLogoLogin != me.datosEmpresa.logo_login){
                    axios.delete('/tienda/delete/' + me.datosEmpresa.imagenActualLogoLogin).then(function(response){
                    }).catch(function(error){
                        console.log(error);
                    });
                }

                if(me.datosEmpresa.imagenActualLogoUsuario != me.datosEmpresa.logo_usuario){
                    axios.delete('/tienda/delete/' + me.datosEmpresa.imagenActualLogoUsuario).then(function(response){
                    }).catch(function(error){
                        console.log(error);
                    });
                }

                if(me.datosEmpresa.imagenActualFondoLogin != me.datosEmpresa.fondo_login){
                    axios.delete('/tienda/delete/' + me.datosEmpresa.imagenActualFondoLogin).then(function(response){
                    }).catch(function(error){
                        console.log(error);
                    });
                }
                return axios.put('/mi_empresa/modificar',this.datosEmpresa).then(function(response){
                     Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro modificado...',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    me.cerrarModal();
                    me.datosMiEmpresa();
                    //me.selectTienda();
                    me.cancelarModificarEmpresa();
                    location.reload();
                }).catch(function(error){
                    console.log(error);
                }).finally(function(){
                    me.saving = false;
                });
            },
            verLogo(){
                Swal.fire({
                imageUrl: this.imagenMinEmpresaLogo,
                imageHeight: 200,
                imageAlt: 'Custom image',
                })
            },

            verLogoLogin(){
                Swal.fire({
                imageUrl: this.imagenMinEmpresaLogoLogin,
                imageHeight: 200,
                imageAlt: 'Custom image',
                })
            },

            verLogoUsuario(){
                Swal.fire({
                imageUrl: this.imagenMinEmpresaLogoUsuario,
                imageHeight: 200,
                imageAlt: 'Custom image',
                })
            },

            verFondoLogin(){
                Swal.fire({
                imageUrl: this.imagenMinEmpresaFondoLogin,
                imageHeight: 200,
                imageAlt: 'Custom image',
                })
            },

            obtenerImagen(e){
                var fileReader = new FileReader();
                fileReader.readAsDataURL(e.target.files[0]);
                fileReader.onload = e => {
                    this.datos.foto = e.target.result;
                    this.imagenMiniatura=this.datos.foto;
                };
            },

            obtenerImagenLogo(e){
                var fileReader = new FileReader();
                fileReader.readAsDataURL(e.target.files[0]);
                fileReader.onload = e => {
                    this.datosEmpresa.logo = e.target.result;
                    this.imagenMiniaturaL=this.datosEmpresa.logo;
                };
            },

            obtenerImagenLogoLogin(e){
                var fileReader = new FileReader();
                fileReader.readAsDataURL(e.target.files[0]);
                fileReader.onload = e => {
                    this.datosEmpresa.logo_login = e.target.result;
                    this.imagenMiniaturaLL=this.datosEmpresa.logo_login;
                };
            },

            obtenerImagenTienda(e){
                var fileReader = new FileReader();
                fileReader.readAsDataURL(e.target.files[0]);
                fileReader.onload = e => {
                    this.datosEmpresa.foto = e.target.result;
                    this.imagenMiniaturaTienda=this.datosEmpresa.foto;
                };
            },

            obtenerLogo(e){
                var fileReader = new FileReader();
                fileReader.readAsDataURL(e.target.files[0]);
                fileReader.onload = e => {
                    this.datosEmpresa.logo = e.target.result;
                    this.imagenMiniaturaL=this.datosEmpresa.logo;
                };
            },

            obtenerLogoLogin(e){
                var fileReader = new FileReader();
                fileReader.readAsDataURL(e.target.files[0]);
                fileReader.onload = e => {
                    this.datosEmpresa.logo_login = e.target.result;
                    this.imagenMiniaturaLL=this.datosEmpresa.logo_login;
                };
            },

            obtenerLogoUsuario(e){
                var fileReader = new FileReader();
                fileReader.readAsDataURL(e.target.files[0]);
                fileReader.onload = e => {
                    this.datosEmpresa.logo_usuario = e.target.result;
                    this.imagenMiniaturaU=this.datosEmpresa.logo_usuario;
                };
            },

            obtenerFondoLogin(e){
                var fileReader = new FileReader();
                fileReader.readAsDataURL(e.target.files[0]);
                fileReader.onload = e => {
                    this.datosEmpresa.fondo_login = e.target.result;
                    this.imagenMiniaturaFL=this.datosEmpresa.fondo_login;
                };
            },


            modificarEmpresa() {
                let me = this;
                me.isDisabled = false;
                me.tipoAccionEmpresa = 2;
            },
            cancelarModificarEmpresa() {
                let me = this;
                me.isDisabled = true;
                me.tipoAccionEmpresa = 1;
                me.datosMiEmpresa();
            },
        },
        async mounted() {
            this.listarEmpresa(1, this.buscar, this.criterio);
            this.cantidadTiendas();
            this.usuarioAuth();
            try {
                await this.datosMiEmpresa();
            } finally {
                this.loading = false;
            }
        }
    }
</script>
<style>
    .master-company {
        display: grid;
        gap: 1rem;
        padding: 1.25rem !important;
        background: #f4f8f6;
    }
    .master-company__metrics {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1rem;
    }
    .master-company > .row {
        margin: 0;
    }
    .master-company > .row > .col {
        padding: 0;
    }
    .master-company > .row > .col > .card {
        overflow: hidden;
        margin: 0;
        background: #fff;
        border: 1px solid #d8e5df;
        border-radius: 14px;
        box-shadow: 0 6px 18px rgba(23, 54, 43, .065);
    }
    .master-company > .row > .col > .card > .card-header {
        display: none;
    }
    .master-company > .row > .col > .card > .row {
        margin: 0 !important;
    }
    .master-company > .row > .col > .card > .row > .row {
        width: 100%;
        margin: 0 !important;
        padding: 1.25rem;
    }
    .master-company .form-label {
        color: #315044;
        font-size: .72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .025em;
    }
    .master-company .form-control {
        min-height: 40px;
        color: #17362b;
        border-color: #bdd2c9;
        border-radius: 8px;
    }
    .master-company .form-control:focus {
        border-color: #0e93b5;
        box-shadow: 0 0 0 3px rgba(62, 198, 224, .18);
    }
    .master-company .form-control:disabled {
        color: #60746b;
        background: #f2f6f4;
        border-color: #d8e5df;
    }
    .master-company .card.mb-4 {
        overflow: hidden;
        border: 1px solid #d8e5df;
        border-radius: 14px;
        box-shadow: none;
    }
    .master-company .card.mb-4 .card-header {
        color: #17362b !important;
        background: #edf7f1 !important;
        border-color: #d8e5df;
    }
    .master-company figure {
        display: grid;
        min-height: 160px;
        margin: 0;
        padding: 1rem;
        place-items: center;
        background: #f8fbf9;
        border: 1px dashed #b9d6ca;
        border-radius: 12px;
    }
    .master-company figure img {
        max-width: 100%;
        object-fit: contain;
        border-radius: 10px;
    }
    .master-company input[type="color"] {
        width: 64px;
        height: 42px;
        padding: .2rem;
        background: #fff;
        border: 1px solid #bdd2c9 !important;
        border-radius: 8px;
    }
    .master-company .button {
        display: none !important;
    }
    .master-company .btn-success {
        color: #17693c !important;
        background: #effaf4 !important;
        border-color: #b9d6ca !important;
    }
    @media (max-width: 820px) {
        .master-company__metrics {
            grid-template-columns: 1fr;
        }
    }
    @media (max-width: 640px) {
        .master-company {
            padding: .75rem !important;
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
    .button{
        display: inline-block;
        width: 120px;
        height: 30px;
        padding-top: 0;
    }
</style>

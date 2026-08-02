<template>
    <main class="main warehouse-legacy warehouse-legacy--products">
        <warehouse-products-workspace
            :rows="arrayArticulo"
            :data="datos"
            :errors="errores"
            :pagination="pagination"
            :pages="pagesNumber"
            :categories="arrayTipoCategoria"
            :units="arrayUnidad"
            :providers="arrayProveedor"
            :lines="arrayLinea"
            :product-count="producto_registro"
            :category-count="categoria_registro"
            :provider-count="proveedor_registro"
            :search="buscar"
            :criterion="criterio"
            :view="listado == 1 ? 'form' : 'list'"
            :editing="tipoAccion == 2"
            :loading="loading"
            :table-loading="tableLoading"
            :saving="saving"
            @update:search="buscar = $event"
            @update:criterion="criterio = $event"
            @search="refreshProducts(1)"
            @page="refreshProducts($event)"
            @create="btnNuevoArticulo"
            @edit="editarArticulo"
            @toggle="toggleProductStatus"
            @cancel="ocultarListado1"
            @save="saveProduct"
        />
        <div v-if="false">
        <warehouse-module-intro
            title="Productos"
            subtitle="Administra la ficha comercial, clasificación, presentación, costos y condiciones de conservación de cada producto."
            primary-label="Productos registrados"
            :primary-value="pagination.total || producto_registro"
            primary-hint="Catálogo disponible"
            secondary-label="Categorías"
            :secondary-value="categoria_registro"
            secondary-hint="Clasificaciones configuradas"
            tertiary-label="Líneas"
            :tertiary-value="linea_registro"
            tertiary-hint="Líneas comerciales"
        />
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                    <div class="card">
                        <!-- prueba fin card -->
                        <template v-if="listado==0">
                        <div class="card-header text-center text-white" style="background-color: #3399FF">
                            <h3 class="mb-0">PRODUCTOS</h3>
                        </div>
                        <!-- prueba card -->
                        <div class="row mt-2" style='width:90%;margin-left: 1.5%'>
                            <!-- <div class="col-sm-6 col-lg-3" >
                                <div class="card mb-4" style="--cui-card-cap-bg: #F76D00">
                                    <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                    <strong> PRODUCTOS </strong>
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
                            </div> -->
                                <!-- /.col-->
                            <div class="col-sm-6 col-lg-3">
                                <div class="card mb-4" style="--cui-card-cap-bg: #0E91FF">
                                    <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                    <strong> CATEGORIA </strong>
                                    </div>
                                    <div class="card-body row text-center">
                                    <div class="col">
                                        <div class="fs-5 fw-semibold">{{categoria_registro}}</div>
                                        <div class="text-uppercase text-medium-emphasis small">Registros</div>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="col">
                                        <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#frmCategoria">
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
                            <!-- /.col-->
                            <div class="col-sm-6 col-lg-3">
                                <div class="card mb-4" style="--cui-card-cap-bg: #0E91FF">
                                    <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                    <strong> PRESENTACIÓN </strong>
                                    </div>
                                    <div class="card-body row text-center">
                                    <div class="col">
                                        <div class="fs-5 fw-semibold">{{unidad_registro}}</div>
                                        <div class="text-uppercase text-medium-emphasis small">Registros</div>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="col">
                                        <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#frmUnidad">
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
                            <!-- /.col-->
                            <div class="col-sm-6 col-lg-3">
                                <div class="card mb-4" style="--cui-card-cap-bg: #0E91FF">
                                    <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                    <strong> LABORATORIO </strong>
                                    </div>
                                    <div class="card-body row text-center">
                                    <div class="col">
                                        <div class="fs-5 fw-semibold">{{proveedor_registro}}</div>
                                        <div class="text-uppercase text-medium-emphasis small">Registros</div>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="col">
                                        <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#frmProveedor">
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
                            <!-- /.col-->
                            <div class="col-sm-6 col-lg-3">
                                <div class="card mb-4" style="--cui-card-cap-bg: #0E91FF">
                                    <div class="card-header position-relative d-flex justify-content-center align-items-center text-white">
                                    <strong> LINEA </strong>
                                    </div>
                                    <div class="card-body row text-center">
                                    <div class="col">
                                        <div class="fs-5 fw-semibold">{{linea_registro}}</div>
                                        <div class="text-uppercase text-medium-emphasis small">Registros</div>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="col">
                                        <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#frmMarca">
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
                        
                        

                            <div class="card-header">
                                <button type="button" @click="btnNuevoArticulo()" class="btn btn-info text-white" style='margin-left: 1.2%'>
                                    <i class="icon-plus"></i>&nbsp;Nuevo
                                </button>
                            </div>



                            <div class="form-group row" id="home">
                                <div class="col-md-8" >
                                    &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                        <!-- <div class="col-md-3">
                                            <select class="form-select col-md-3" v-model="datos.tipo" @change="cambiarVenta()">
                                                <option value="0">PETSHOP</option>
                                                <option value="1">FARMACIA</option>                                  
                                            </select>
                                        </div>&nbsp;&nbsp; -->
                                        <div class="col-md-3">
                                            <select class="form-select col-md-3" v-model="criterio">
                                                <option value="articulo.nombre_comercial">Nombre Comercial</option>
                                                <option value="articulo.nombre_generico">Nombre Generico</option>
                                                <option value="categoria.nombre">Categoria</option>
                                                <option value="proveedor.nombre">Laboratorio</option>                                       
                                            </select>
                                        </div>
                                        <!-- <template v-if="datos.tipo == '0'"> -->
                                        &nbsp;&nbsp;&nbsp;
                                        <input type="text" v-model="buscar" @keyup.enter="listarArticulo(1, buscar, criterio)" @keyup="BuscandoArticulo()" class="form-control" placeholder="Texto a buscar">
                                        &nbsp;&nbsp;&nbsp;
                                        <button type="submit" @click="listarArticulo(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                        <!-- </template> -->
                                        <!-- <template v-else>
                                        &nbsp;&nbsp;&nbsp;
                                        <input type="text" v-model="buscar" @keyup.enter="listarArticulo2(1, buscar, criterio)" @keyup="BuscandoArticulo2()" class="form-control" placeholder="Texto a buscar">
                                        &nbsp;&nbsp;&nbsp;
                                        <button type="submit" @click="listarArticulo2(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                        </template> -->
                                    </div>
                                </div>
                            </div>
                            <br>
                            <!-- Light table -->
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                                <thead style="background-color: #46546C">
                                    <tr>
                                        <!-- <th scope="col" class="text-white">Cod. Sistema</th>                     -->
                                        <th scope="col" class="text-white">Nombre Comerical</th>
                                        <th scope="col" class="text-white">Nombre Generico</th>
                                        <th scope="col" class="text-white">Unidad</th>
                                        <th scope="col" class="text-white">Costo de Compra</th>
                                        <th scope="col" class="text-white">Laboratorio</th>
                                        <th scope="col" class="text-white">Precio Venta</th>
                                        <th scope="col" class="text-white">Precio Blister</th>
                                        <th scope="col" class="text-white">Precio Caja</th>
                                        <th scope="col" class="text-white">Estado</th>
                                        <th scope="col" class="text-white">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayArticulo.length">
                                    <tr v-for="articulo in arrayArticulo" :key="articulo.id">
                                        <!-- <td v-text="articulo.cod_sistema"></td> -->
                                        <td v-text="articulo.nombre_comercial"></td>
                                        <td v-text="articulo.nombre_generico"></td>
                                        <td v-text="articulo.unidad"></td>
                                        <td v-text="articulo.costo_compra"></td>
                                        <td v-text="articulo.proveedor"></td>
                                        <td v-text="articulo.costo_unitario"></td>
                                        <td v-text="articulo.precio_blister"></td>
                                        <td v-text="articulo.precio_caja"></td>
                                        <td>
                                            <template v-if="articulo.estado==1">
                                                <span class="badge bg-success">Activo</span>
                                            </template>
                                            <template v-else>
                                                <span class="badge bg-danger">Desactivo</span>
                                            </template>
                                        </td> 
                                        <td>
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" @click="editarArticulo(articulo)"><i class="fa fa-edit text-warning"></i> Modificar</a></li>
                                                <template v-if="articulo.estado==1">
                                                <li><a class="dropdown-item" href="#" @click="desactivarArticulo(articulo.id)"><i class="fa fa-unlock text-success"></i> Desactivar</a></li>
                                            </template>
                                            <template v-else>
                                                <li><a class="dropdown-item" href="#" @click="activarArticulo(articulo.id)"><i class="fa fa-lock text-danger"></i> Activar</a></li>
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
                                    <h3 class="mb-0">DATOS DE PRODUCTOS</h3>  
                                </div>


                                </div>
                                </div>
                                <div class="card-header text-center line p-0 m-0"  style="background-color: #3399FF">
                                    
                                </div>&nbsp;
                                
                                <form class="row g-3">

                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Codigo Proveedor</label>
                                        <input type="text" class="form-control" v-model="datos.cod_proveedor" >
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Codigo Barra</label>
                                        <input type="number" class="form-control" v-model="datos.cod_barra" >
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Nombre Comercial</label>
                                        <input type="text" class="form-control" v-model="datos.nombre_comercial">

                                        <!-- <div class="row" v-if="errores.nombre_comercial">
                                            <div class="col-sm-10"><span class="text-danger">{{errores.nombre_comercial[0]}}</span></div>
                                        </div> -->
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Nombre Generico</label>
                                        <input type="text" class="form-control" v-model="datos.nombre_generico">
<!-- 
                                        <div class="row" v-if="errores.nombre_generico">
                                            <div class="col-sm-10"><span class="text-danger">{{errores.nombre_generico[0]}}</span></div>
                                        </div> -->
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Presentacion</label>
                                        <select class="form-select" v-model="datos.id_unidad">
                                            <option value="0" disabled>Seleccione la Presentación</option>
                                            <option v-for="unidad in arrayUnidad" :key="unidad.id" :value="unidad.id" v-text="unidad.nombre"></option>
                                        </select>
                                        <div class="row" v-if="errores.id_unidad">
                                            <!-- <div class="col-sm-2">&nbsp;</div> -->
                                            <div class="col-sm-10"><span class="text-danger">{{errores.id_unidad[0]}}</span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Concentración</label>
                                        <input type="text" class="form-control" v-model="datos.composicion">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Precio Compra</label>
                                        <input type="text" class="form-control" v-model="datos.costo_compra">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Precio Compra Caja</label>
                                        <input type="number" class="form-control" v-model="datos.costo_compra_caja" min="0" >
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Presentacion Unitaria</label>
                                        <input type="number" class="form-control" v-model="datos.venta_presentacion" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Precio Venta Presentacion</label>
                                        <input type="number" class="form-control" v-model="datos.costo_unitario" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Cantidad Blister</label>
                                        <input type="number" class="form-control" v-model="datos.cantidad_blister" min="0">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Precio Venta Blister</label>
                                        <input type="number" class="form-control" v-model="datos.precio_blister" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Cantidad Caja</label>
                                        <input type="number" class="form-control" v-model="datos.cantidad_caja" min="0">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Precio Venta Caja</label>
                                        <input type="number" class="form-control" v-model="datos.precio_caja" min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Stock Minimo</label>
                                        <input type="number" class="form-control" v-model="datos.stock_minimo" min="0">
                                    </div>


                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Categoria</label>
                                        <select class="form-select" v-model="datos.id_categoria">
                                            <option value="0" disabled>Seleccione la Categoria</option>
                                            <option v-for="articulo in arrayTipoCategoria" :key="articulo.id" :value="articulo.id" v-text="articulo.nombre"></option>
                                        </select>
                                        <div class="row" v-if="errores.id_categoria">
                                            <!-- <div class="col-sm-2">&nbsp;</div> -->
                                            <div class="col-sm-10"><span class="text-danger">{{errores.id_categoria[0]}}</span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Laboratorio</label>
                                        <select class="form-select" v-model="datos.id_proveedor">
                                            <option value="0" disabled>Seleccione el Laboratorio</option>
                                            <option v-for="proveedor in arrayProveedor" :key="proveedor.id" :value="proveedor.id" v-text="proveedor.nombre"></option>
                                        </select>
                                        <div class="row" v-if="errores.id_proveedor">
                                            <!-- <div class="col-sm-2">&nbsp;</div> -->
                                            <div class="col-sm-10"><span class="text-danger">{{errores.id_proveedor[0]}}</span></div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Linea</label>
                                        <select class="form-select" v-model="datos.id_marca">
                                            <option value="0" disabled>Seleccione la Linea</option>
                                            <option v-for="linea in arrayLinea" :key="linea.id" :value="linea.id" v-text="linea.nombre"></option>
                                        </select>
                                        <div class="row" v-if="errores.id_marca">
                                            <!-- <div class="col-sm-2">&nbsp;</div> -->
                                            <div class="col-sm-10"><span class="text-danger">{{errores.id_marca[0]}}</span></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Acción Terapeutica</label>
                                        <input type="text" class="form-control" v-model="datos.descripcion">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Ubicación</label>
                                        <input type="text" class="form-control" v-model="datos.ubicacion">
                                    </div>
                                    <div class="col-md-6">
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
                                    <div class="col-md-6">
                                    <div class="col-md-12">
                                            <table class="leght">
                                    <tr>
                                        <td>
                                            <label for="exampleInputPassword1" class="form-label">Caracteristica Generales</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.psicotropico">
                                                <label class="form-check-label" >
                                                    PSICOTROPICO
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckIndeterminate" v-model="datos.refrigerado">
                                                <label class="form-check-label" >
                                                    REFRIGERADO
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                    </div>
                                    </div>




                                </form>
                                <div class="header-divider"></div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button class="btn btn-danger me-md-2 text-white" type="button" @click="ocultarListado1()">Cancelar</button>
                                    <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==1"  type="button" @click="guardarArticulo()">Guardar Articulo</button>
                                    <button class="btn btn-info btn-lg text-white" v-if="tipoAccion==2"  type="button" @click="modificarArticulo()">Modificar Articulo</button>
                                </div>
                            </div>
                            
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
                        <h4 class="modal-title"> Registro Presentación</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->

                            <div class="mb-3"  :class="errores.nombre ? 'mb-1' : 'mb-3'">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Abreviatura</label>
                                <div><input type="text" class="form-control" v-model="datosUnidad.nombre" ></div>
                            </div>
                           <div class="row mb-2" v-if="errores.nombre">
                                <div class="col-sm-2">&nbsp;</div>
                                <div class="col-sm-10"><span class="text-danger">{{errores.nombre[0]}}</span></div>
                            </div>

                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
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
        <!--Modal Formulario Marca-->
        <div class="modal fade" id="frmMarca" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white">
                        <h4 class="modal-title ">Registro Linea</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- <form action="" method="post" enctype="multipart/form-data" class="form-horizontal"> -->
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Nombre</label>
                                <div><input type="text" class="form-control" v-model="datosMarca.nombre"></div>
                            </div>
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
                                <textarea class="form-control" id="message-text" v-model="datosMarca.descripcion"></textarea>
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
                        <button type="button" class="btn btn-info text-white" @click="guardarMarca() ">Guardar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Marca-->


        </div>
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
                    cod_proveedor : '',
                    cod_barra : '',
                    nombre_comercial : '',
                    nombre_generico: '',
                    costo_compra: 0,
                    costo_compra_caja: 0,
                    costo_unitario: 0,
                    stock_minimo: 0,
                    ubicacion : '',
                    descripcion : '',
                    composicion : '',
                    venta_presentacion : 1,
                    cantidad_caja : 0,
                    cantidad_blister : 0,
                    precio_caja : 0,
                    precio_blister : 0,
                    psicotropico : 0,
                    refrigerado : 0,
                    estado : '1',
                    id_categoria : 0,
                    id_unidad : 0,
                    id_proveedor : 0,
                    id_marca : 0,
                    accion : '1',
                },
                datosCategoria : {
                    id : 0,
                    nombre : '',
                    descripcion : '',
                    estado : '1',
                }, 
                datosMarca : {
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
                linea_registro :0, 
                producto_registro :0,                                
                arrayArticulo : [],
                arrayArticulo2 : [],
                arrayNroHistoria : [],
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
                criterio : 'articulo.nombre_comercial',
                buscar : '',
                arrayTipoCategoria : [],
                arrayUnidad : [],
                arrayLinea : [],
                arrayProveedor : [],
                listado: 0,
                setTimeoutBuscador: '',
                loading: true,
                tableLoading: false,
                saving: false,
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
            async refreshProducts(page){
                this.tableLoading = true;
                try {
                    await this.listarArticulo(page, this.buscar, this.criterio);
                } finally {
                    this.tableLoading = false;
                }
            },
            toggleProductStatus(row){
                if(Number(row.estado) === 1){
                    this.desactivarArticulo(row.id);
                } else {
                    this.activarArticulo(row.id);
                }
            },
            saveProduct(){
                if(this.tipoAccion === 2){
                    this.modificarArticulo();
                } else {
                    this.guardarArticulo();
                }
            },
            cambiarVenta()
            {
                if(this.datos.tipo == '0')
                {
                    this.listarArticulo(1, '', 'articulo.nombre_comercial'); 
                }
                else
                {
                    this.listarArticulo2(1, '', 'articulo.nombre_comercial'); 
                }
            },
            listarArticulo(page, buscar, criterio){
                let me=this;
                var url='/articulo?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                return axios.get(url).then(function(response){
                    me.arrayArticulo=response.data.data;
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
                    me.arrayArticulo=response.data.data;
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
                var url='/articulo?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayArticulo=response.data.data;
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
                    me.arrayArticulo=response.data.data;
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
                this.ultimo_id();
                this.selectCategoria();
                this.selectUnidad();
                this.selectProveedor();
                this.selectLinea();
                let me = this;
                me.listado=1;
                this.tipoAccion = 1;
                me.datos = {
                    id : 0,
                    cod_proveedor : '',
                    cod_barra : '',
                    nombre_comercial : '',
                    nombre_generico: '',
                    costo_compra: 0,
                    costo_compra_caja: 0,
                    costo_unitario: 0,
                    stock_minimo: 0,
                    ubicacion : '',
                    descripcion : '',
                    composicion : '',
                    venta_presentacion : 1,
                    cantidad_caja : 0,
                    cantidad_blister : 0,
                    precio_caja : 0,
                    precio_blister : 0,
                    psicotropico : 0,
                    refrigerado : 0,
                    estado : '1',
                    id_categoria : 0,
                    id_unidad : 0,
                    id_proveedor : 0,
                    id_marca : 0,
                    accion : '1',

                };
            },
           async guardarArticulo(){
                this.saving = true;
                try {
                    let me = this;
                    let data = {
                        id : me.datos.id,
                        cod_proveedor : me.datos.cod_proveedor,
                        cod_barra : me.datos.cod_barra,
                        nombre_comercial : me.datos.nombre_comercial,
                        nombre_generico : me.datos.nombre_generico,
                        costo_compra: me.datos.costo_compra,
                        costo_compra_caja: me.datos.costo_compra_caja,
                        costo_unitario: me.datos.costo_unitario,
                        stock_minimo: me.datos.stock_minimo,
                        ubicacion: me.datos.ubicacion,
                        descripcion : me.datos.descripcion,
                        composicion : me.datos.composicion,
                        venta_presentacion : me.datos.venta_presentacion,
                        cantidad_caja : me.datos.cantidad_caja,
                        cantidad_blister : me.datos.cantidad_blister,
                        precio_blister : me.datos.precio_blister,
                        precio_caja : me.datos.precio_caja,
                        psicotropico : me.datos.psicotropico,
                        refrigerado : me.datos.refrigerado,
                        estado : me.datos.estado,
                        id_categoria : me.datos.id_categoria >0 ? me.datos.id_categoria : "",
                        id_marca : me.datos.id_marca >0 ? me.datos.id_marca : "",
                        id_unidad : me.datos.id_unidad >0 ? me.datos.id_unidad : "",
                        id_proveedor : me.datos.id_proveedor >0 ? me.datos.id_proveedor : "",
                    }
                    const res = await axios.post('/articulo/guardar',data);
                    if(res.data.error==0){
                        //me.$toaster.error('Matricula ya existe...');
                        Swal.fire({
                            icon: 'error',
                            title: 'Nombre ya existe...',
                            text: 'Debe usar otra nombre! o Agregar nueva Categoría'
                        })
                    }
                    else{
                        me.listado =0;
                        me.registrosArticulo();
                        me.listarArticulo(1, '', 'nombre_comercial');   
                       // me.listar2(1, '', 'nombre_comercial');
                        me.errores ={};
                        me.datos = {
                        id : 0,
                        cod_producto : '',
                        cod_proveedor : '',
                        cod_ean : '',
                        nombre : '',
                        marca : '',
                        costo_compra: 0,
                        costo_unitario: 0,
                        costo_mayorista: 0,
                        costo_preferencial: 0,
                        stock_minimo: 0,
                        tipo_producto : 'Producto Venta',
                        descripcion : '',
                        estado : '1',
                        id_categoria : 0,
                        tipo : '0',
                    },
                    me.registrosArticulo();
                    me.registrosArticuloProducto()
                    me.arrayArticulo = [];
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
                } finally {
                    this.saving = false;
                }
            },
            async modificarArticulo(){
                this.saving = true;
                try {
                    let me = this;
                    const res = await axios.put('/articulo/modificar',me.datos);
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
                        me.listarArticulo(1,'', 'nombre_comercial');
                        //me.listar2(1,'', 'nombre_comercial');
                        me.datos = {
                        tipo : '0',
                        
                        }
                    }    
                } catch (error) {
                    if(error.response.data){
                        this.errores=error.response.data.errors;
                    }
                } finally {
                    this.saving = false;
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
                var url='/articulo/cantidad';
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
                var url='/articulo/cantidadProducto';
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
            selectUnidad(){
                let me=this;
                var url='/unidad/selectUnidad';
                axios.get(url).then(function(response){
                    me.arrayUnidad=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectLinea(){
                let me=this;
                var url='/marca/selectMarca';
                axios.get(url).then(function(response){
                    me.arrayLinea=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectProveedor(){
                let me=this;
                var url='/proveedor/selectProveedor';
                axios.get(url).then(function(response){
                    me.arrayProveedor=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            editarArticulo(data=[]){
                this.selectCategoria();
                this.selectUnidad();
                this.selectProveedor();
                this.selectLinea();
                let me = this;
                me.listado=1;
                this.tipoAccion = 2;
                this.datos.id = data['id'];
                this.datos.cod_proveedor = data['cod_proveedor'];
                this.datos.cod_barra = data['cod_barra'];
                this.datos.nombre_comercial = data['nombre_comercial'];
                this.datos.nombre_generico = data['nombre_generico'];
                this.datos.costo_compra = data['costo_compra'];
                this.datos.costo_compra_caja = data['costo_compra_caja'];
                this.datos.costo_unitario = data['costo_unitario'];
                this.datos.stock_minimo = data['stock_minimo'];
                this.datos.ubicacion = data['ubicacion'];
                this.datos.composicion = data['composicion'];
                this.datos.venta_presentacion = data['venta_presentacion'];
                this.datos.cantidad_caja = data['cantidad_caja'];
                this.datos.cantidad_blister = data['cantidad_blister'];
                this.datos.precio_caja = data['precio_caja'];
                this.datos.precio_blister = data['precio_blister'];
                this.datos.psicotropico = data['psicotropico'];
                this.datos.refrigerado = data['refrigerado'];
                this.datos.descripcion = data['descripcion'];
                this.datos.estado = data['estado'];
                this.datos.id_categoria = data['id_categoria'];
                this.datos.id_proveedor = data['id_proveedor'];
                this.datos.id_marca = data['id_marca'];
                this.datos.id_unidad = data['id_unidad'];
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
                this.selectUnidad();
                this.selectProveedor();
                this.selectLinea();
            },
            guardarMarca(){
                let me = this;
                axios.post('/marca/guardar',this.datosMarca).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Registro agregado...',
                        showConfirmButton: false,
                        timer: 1500
                    }) 
                    me.registrosMarca();
                    me.limpiarDatosCategoria();   
                })
                .catch(function(error){
                    console.log(error);
                });
                this.selectCategoria();
                this.selectUnidad();
                this.selectProveedor();
                this.selectLinea();
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
            desactivarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Inhabilitar este Articulo??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Inhabilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/articulo/desactivar',{'id': id}).then(function (response) {
                        me.listarArticulo(1,'', 'nombre_comercial');
                        //me.listar2(1,'', 'nombre_comercial');
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
                    'Este cargo no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            activarArticulo(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Habilitar este Articulo??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/articulo/activar',{'id': id}).then(function (response) {
                        me.listarArticulo(1,'', 'nombre_comercial');
                       // me.listar2(1,'', 'nombre_comercial');
                        swalWithBootstrapButtons.fire(
                        'Habilitado!',
                        'Este articulo se ha Habilitado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este articulo no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            registrosMarca(){
                let me=this;
                var url='/marca/cantidad';
                axios.get(url).then(function(response){
                    me.linea_registro=response.data.nro;
                })
                .catch(function(error){
                    console.log(error)
                });                
            }, 
            ultimo_id(){
                let me=this;
                var url='/articulo/ultimo/id';
                axios.get(url).then(function(response){
                    me.arrayNroHistoria=response.data;
                    me.datos.cod_sistema = response.data[0].id+1;
                    
                    // if(me.datos.id == NULL){
                         
                        //  me.datos.nro_nuevo = parseFloat(response.data[0].id+1) ? parseFloat(response.data[0].id) : 0+1;
                    // }else{
                    // }
                })
                .catch(function(error){
                    console.log(error)
                });
            }
        },
        async mounted() {
            try {
                await this.listarArticulo(1, this.buscar, this.criterio);
            } finally {
                this.loading = false;
            }
            //this.listar2(1, this.buscar, this.criterio);
            this.registrosCategoria();
            this.registrosUnidad();
            this.registrosMarca();
            this.registrosProveedor();
            this.registrosArticulo();
            this.registrosArticuloProducto();
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
    .line{
        height:3px !important;
    }
</style>

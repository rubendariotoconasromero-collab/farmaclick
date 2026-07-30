<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                   <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">HISTORIAL DE CLIENTE</h3></div>
                    <template v-if="listado==0">

                        <!-- Inicio -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Contado</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Credito</button>
                        </li>
                        <!-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cotizacion-tab" data-bs-toggle="tab" data-bs-target="#cotizacion" type="button" role="tab" aria-controls="cotizacion" aria-selected="false">Vacunas</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="antiparasitario-tab" data-bs-toggle="tab" data-bs-target="#antiparasitario" type="button" role="tab" aria-controls="antiparasitario" aria-selected="false">Antiparasitario</button>
                        </li> -->
                        <!-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="cotizacion-tab" data-bs-toggle="tab" data-bs-target="#cotizacion" type="button" role="tab" aria-controls="cotizacion" aria-selected="false">Cotizacion</button>
                        </li> -->
                        </ul>
                        <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                            <div class="form-group row" id="home">

                            <div class="col-md-8">
                                &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                    <!-- <select class="form-select col-md-3" v-model="criterio">
                                        <option value="cliente.nombre">Nombre</option>
                                        <option value="forma_pago.nombre">Forma Pago</option>
                                    </select>
                                    &nbsp;&nbsp;&nbsp; -->
                                    <select class="form-select col-md-3" v-model="criterio">
                                        <option value="cliente.nombre">Nombre</option>
                                        <option value="forma_pago.nombre">Forma Pago</option>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup.enter="listarVenta(1, buscar, criterio)" @keyup="BuscandoVenta()" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarVenta(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Light table -->
                        <div class="table-responsive">
                        <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                            <thead style="background-color: #46546C">
                                <tr>
                                    <th scope="col" class="text-white">Fecha</th>
                                    <th scope="col" class="text-white">Cliente</th>
                                    <!-- <th scope="col" class="text-white">Tienda</th> -->
                                    <th scope="col" class="text-white">Descuento</th>
                                    <th scope="col" class="text-white">Total</th>
                                    <th scope="col" class="text-white text-center">Estado</th>
                                    <th scope="col" class="text-white">Tipo Pago</th>
                                    <th scope="col" class="text-white">Forma Pago</th>
                                    <th scope="col" class="text-white">Usuario</th>
                                    <!-- <th scope="col" class="text-white">Modificar</th> -->
                                    <!-- <th scope="col" class="text-white">Pagos</th> -->
                                    <th scope="col" class="text-white">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="venta in arrayVenta" :key="venta.id">
                                    <td v-text="venta.fecha"></td>
                                    <td v-text="venta.cliente"></td>
                                    <!-- <td v-text="venta.tienda"></td> -->
                                    <td v-text="venta.descuento"></td>
                                    <td v-text="venta.total"></td>
                                    <td class="text-center">
                                        <template v-if="venta.estado=='Recepcionado'">
                                            <span class="badge bg-info tamaño">{{venta.estado}}</span>
                                        </template>
                                        <template v-if="venta.estado=='Concluido'">
                                            <span class="badge bg-warning tamaño">{{venta.estado}}</span>
                                        </template>
                                        <template v-if="venta.estado=='Entregado'">
                                            <span class="badge bg-success tamaño">{{venta.estado}}</span>
                                        </template>
                                        <template v-if="venta.estado=='Anulado'">
                                            <span class="badge bg-danger tamaño">{{venta.estado}}</span>
                                        </template>
                                        <template v-if="venta.estado=='Devolucion'">
                                            <span class="badge bg-danger tamaño">Devolución</span>
                                        </template>
                                    </td>
                                    <td v-text="venta.tipoP"></td>
                                    <td v-text="venta.formaP"></td>
                                    <td v-text="venta.name"></td>

                                    <!-- <td>
                                        <a class="dropdown-item" href="#"><i class="fa fa-edit text-warning"></i> Modificar</a>
                                    </td> -->
                                    <!-- <td>
                                        <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalPago" :disabled="venta.tipoP == 'Contado' || venta.estado == 'Anulado' ? true : false" @click="realizarPagos(venta)">Pagos</button>
                                    </td>  -->
                                    <td>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" @click="verVenta(venta)"><i class="fa fa-eye text-success"></i> Ver detalle</a></li>
                                        <template v-if="venta.estado=='Entregado'">
                                            <li><a class="dropdown-item" href="#" @click="verModificar(venta)"><i class="fa fa-low-vision text-danger"></i> Devolución</a></li>
                                        </template>
                                        <template v-if="usuario.id_grupo == 1">
                                            <template v-if="venta.estado=='Entregado'  || venta.estado=='Devolucion'">
                                                <li><a class="dropdown-item" href="#" @click="anularVenta(venta.id,venta.id_usuario)"><i class="fa fa-lock text-danger"></i> Anular</a></li>
                                            </template>
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
                                    <li class="page-item bg-color" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                                    </li>
                                    <li class="page-item bg-color" v-for="page in pagesNumber" :key="page" :class="[page==isActived ? 'active' :'']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page">1</a>
                                    </li>
                                    <li class="page-item bg-color" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>


                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                        <div class="form-group row" id="profile">
                          <div class="col-md-8" >
                                &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                    <select class="form-select col-md-3" v-model="criterio">
                                        <option value="cliente.nombre">Nombre</option>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscarC" @keyup.enter="listarVentaServicio(1, buscarC, criterio)" @keyup="BuscandoVentaServicio()" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarVentaServicio(1, buscarC, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Light table -->
                        <div class="table-responsive">
                        <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                            <thead style="background-color: #46546C">
                                <tr>
                                    <th scope="col" class="text-white">Cliente</th>
                                    <th scope="col" class="text-white">Numero Credito</th>
                                    <th scope="col" class="text-white">Total</th>
                                    <th scope="col" class="text-white">Estado</th>
                                    <!-- <th scope="col" class="text-white">Pagos</th> -->
                                    <th scope="col" class="text-white">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="servicio in arrayVentaServicio" :key="servicio.id">
                                    <td v-text="servicio.cliente"></td>
                                    <td v-text="servicio.contador"></td>
                                    <td v-text="servicio.total"></td>
                                    <td>
                                        <template v-if="servicio.estado_pendiente=='Pendiente'">
                                            <span class="badge bg-success text-white">{{servicio.estado_pendiente}}</span>
                                        </template>
                                        <template v-if="servicio.estado_pendiente=='Cancelado'">
                                            <span class="badge bg-danger text-white">{{servicio.estado_pendiente}}</span>
                                        </template>
                                    </td>

                                    <!-- <td>
                                        <a class="dropdown-item" href="#"><i class="fa fa-edit text-warning"></i> Modificar</a>
                                    </td> -->
                                    <!-- <td>
                                        <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalPago" :disabled="servicio.tipoP == 'Contado' || servicio.estado == 'Anulado' ? true : false" @click="realizarPagos(servicio)">Pagos</button>
                                    </td>  -->
                                    <td>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" @click="ver(servicio.id_cliente)"><i class="fa fa-eye text-success"></i> Ver detalle</a></li>
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
                                    <li class="page-item bg-color" v-if="paginationServicio.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaServicio(paginationServicio.current_page - 1, buscarC, criterio)">Ant</a>
                                    </li>
                                    <li class="page-item bg-color" v-for="page in pagesNumberServicio" :key="page" :class="[page==isActivedServicio ? 'active' :'']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaServicio(page, buscarC, criterio)" v-text="page">1</a>
                                    </li>
                                    <li class="page-item bg-color" v-if="paginationServicio.current_page < paginationServicio.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaServicio(paginationServicio.current_page + 1, buscarC, criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        </div>
                        <div class="tab-pane fade" id="cotizacion" role="tabpanel" aria-labelledby="cotizacion-tab">

                        <div class="form-group row" id="cotizacion">
                          <div class="col-md-8" >
                                &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                    <select class="form-select col-md-3" v-model="criterio">
                                        <option value="cliente.nombre">Nombre</option>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup.enter="listarVentaCotizacion(1, buscar, criterio)" @keyup="BuscandoVentaServicio()" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarVentaCotizacion(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Light table -->
                        <div class="table-responsive">
                        <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                            <thead style="background-color: #46546C">
                                <tr>
                                    <th scope="col" class="text-white">Fecha</th>
                                    <th scope="col" class="text-white">Cliente</th>
                                    <th scope="col" class="text-white">Tienda</th>
                                    <th scope="col" class="text-white">Descuento</th>
                                    <th scope="col" class="text-white">Total</th>
                                    <th scope="col" class="text-white">Estado</th>
                                    <th scope="col" class="text-white">Tipo de Pago</th>
                                    <!-- <th scope="col" class="text-white">Pagos</th> -->
                                    <th scope="col" class="text-white">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="vacuna in arrayVentaVacuna" :key="vacuna.id">
                                    <td v-text="vacuna.fecha"></td>
                                    <td v-text="vacuna.cliente"></td>
                                    <td v-text="vacuna.tienda"></td>
                                    <td v-text="vacuna.descuento"></td>
                                    <td v-text="vacuna.total"></td>
                                    <td class="text-center">
                                        <template v-if="vacuna.estado=='Recepcionado'">
                                            <span class="badge bg-info tamaño">{{vacuna.estado}}</span>
                                        </template>
                                        <template v-if="vacuna.estado=='Concluido'">
                                            <span class="badge bg-warning tamaño">{{vacuna.estado}}</span>
                                        </template>
                                        <template v-if="vacuna.estado=='Entregado'">
                                            <span class="badge bg-success tamaño">{{vacuna.estado}}</span>
                                        </template>
                                        <template v-if="vacuna.estado=='Anulado'">
                                            <span class="badge bg-danger tamaño">{{vacuna.estado}}</span>
                                        </template>
                                    </td>
                                    <td v-text="vacuna.tipoP"></td>

                                    <!-- <td>
                                        <a class="dropdown-item" href="#"><i class="fa fa-edit text-warning"></i> Modificar</a>
                                    </td> -->
                                    <!-- <td>
                                        <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalPago" :disabled="vacuna.tipoP == 'Contado' || vacuna.estado == 'Anulado' ? true : false" @click="realizarPagos(vacuna)">Pagos</button>
                                    </td>  -->
                                    <td>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" @click="verVenta(vacuna)"><i class="fa fa-eye text-success"></i> Ver detalle</a></li>
                                        <template v-if="vacuna.estado!='Anulado'">
                                            <template v-if="vacuna.estado!='Entregado'">
                                                <li><a class="dropdown-item" href="#" @click="editarDetalle(vacuna)"><i class="fa fa-edit text-warning"></i> Cobrar Vacuna</a></li>
                                            </template>
                                        </template>
                                        <template v-if="vacuna.estado!='Anulado'">
                                            <template v-if="vacuna.estado!='Entregado'">
                                                <li><a class="dropdown-item" href="#" @click="anularServicio(vacuna.id)"><i class="fa fa-lock text-danger"></i> Anular</a></li>
                                            </template>
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
                                    <li class="page-item bg-color" v-if="paginationCotizacion.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaCotizacion(paginationCotizacion.current_page - 1, buscar, criterio)">Ant</a>
                                    </li>
                                    <li class="page-item bg-color" v-for="page in pagesNumberCotizacion" :key="page" :class="[page==isActivedCotizacion ? 'active' :'']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaCotizacion(page, buscar, criterio)" v-text="page">1</a>
                                    </li>
                                    <li class="page-item bg-color" v-if="paginationCotizacion.current_page < paginationCotizacion.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaCotizacion(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        </div>

                        <div class="tab-pane fade" id="antiparasitario" role="tabpanel" aria-labelledby="antiparasitario-tab">

                        <div class="form-group row" id="antiparasitario">
                          <div class="col-md-8" >
                                &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                    <select class="form-select col-md-3" v-model="criterio">
                                        <option value="cliente.nombre">Nombre</option>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup.enter="listarVentaAntiparasitario(1, buscar, criterio)" @keyup="BuscandoVentaServicio()" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarVentaAntiparasitario(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <br>
                        <!-- Light table -->
                        <div class="table-responsive">
                        <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                            <thead style="background-color: #46546C">
                                <tr>
                                    <th scope="col" class="text-white">Fecha</th>
                                    <th scope="col" class="text-white">Cliente</th>
                                    <th scope="col" class="text-white">Tienda</th>
                                    <th scope="col" class="text-white">Descuento</th>
                                    <th scope="col" class="text-white">Total</th>
                                    <th scope="col" class="text-white">Estado</th>
                                    <th scope="col" class="text-white">Tipo de Pago</th>
                                    <!-- <th scope="col" class="text-white">Pagos</th> -->
                                    <th scope="col" class="text-white">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="antiparasitario in arrayVentaAntiparasitario" :key="antiparasitario.id">
                                    <td v-text="antiparasitario.fecha"></td>
                                    <td v-text="antiparasitario.cliente"></td>
                                    <td v-text="antiparasitario.tienda"></td>
                                    <td v-text="antiparasitario.descuento"></td>
                                    <td v-text="antiparasitario.total"></td>
                                    <td class="text-center">
                                        <template v-if="antiparasitario.estado=='Recepcionado'">
                                            <span class="badge bg-info tamaño">{{antiparasitario.estado}}</span>
                                        </template>
                                        <template v-if="antiparasitario.estado=='Concluido'">
                                            <span class="badge bg-warning tamaño">{{antiparasitario.estado}}</span>
                                        </template>
                                        <template v-if="antiparasitario.estado=='Entregado'">
                                            <span class="badge bg-success tamaño">{{antiparasitario.estado}}</span>
                                        </template>
                                        <template v-if="antiparasitario.estado=='Anulado'">
                                            <span class="badge bg-danger tamaño">{{antiparasitario.estado}}</span>
                                        </template>
                                    </td>
                                    <td v-text="antiparasitario.tipoP"></td>

                                    <!-- <td>
                                        <a class="dropdown-item" href="#"><i class="fa fa-edit text-warning"></i> Modificar</a>
                                    </td> -->
                                    <!-- <td>
                                        <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalPago" :disabled="antiparasitario.tipoP == 'Contado' || antiparasitario.estado == 'Anulado' ? true : false" @click="realizarPagos(antiparasitario)">Pagos</button>
                                    </td>  -->
                                    <td>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" @click="verVenta(antiparasitario)"><i class="fa fa-eye text-success"></i> Ver detalle</a></li>
                                        <template v-if="antiparasitario.estado!='Anulado'">
                                            <template v-if="antiparasitario.estado!='Entregado'">
                                                <li><a class="dropdown-item" href="#" @click="editarDetalle2(antiparasitario)"><i class="fa fa-edit text-warning"></i> Cobrar Antiparasitario</a></li>
                                            </template>
                                        </template>
                                        <template v-if="antiparasitario.estado!='Anulado'">
                                            <template v-if="antiparasitario.estado!='Entregado'">
                                                <li><a class="dropdown-item" href="#" @click="anularServicio(antiparasitario.id)"><i class="fa fa-lock text-danger"></i> Anular</a></li>
                                            </template>
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
                                    <li class="page-item bg-color" v-if="paginationCotizacion.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaCotizacion(paginationCotizacion.current_page - 1, buscar, criterio)">Ant</a>
                                    </li>
                                    <li class="page-item bg-color" v-for="page in pagesNumberCotizacion" :key="page" :class="[page==isActivedCotizacion ? 'active' :'']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaCotizacion(page, buscar, criterio)" v-text="page">1</a>
                                    </li>
                                    <li class="page-item bg-color" v-if="paginationCotizacion.current_page < paginationCotizacion.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaCotizacion(pagination.current_page + 1, buscar, criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        </div>

                        </div>
                        <!--fin  -->

                    </template>
                    <!-- MODIFICAR -->
                    <template v-if="listado==1">
                        <div class="card-body">
                        <div class="form-group row" style='margin-left: 1%'>
                            <form class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1" class="form-label">Cliente</label>
                                    <div class="input-group mb-6">
                                        <input type="text" readonly class="form-control" placeholder="Buscar Clientes.."  v-model="datos.cliente" aria-label="Buscar Productos.." aria-describedby="button-addon2">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Tipo Pago</label>
                                        <select class="form-select" v-model="datos.id_tipo_pago" disabled>
                                            <option value="0" disabled>Seleccione el Tipo Pago</option>
                                            <option v-for="tipo_pago in arrayPago" :key="tipo_pago.id" :value="tipo_pago.id" v-text="tipo_pago.nombre"></option>
                                        </select>
                                    </div>
                                    <div class="col-md-6" v-if="datos.id_tipo_pago == 2">
                                        <label for="exampleInputPassword1" class="form-label">Fecha Final</label>
                                        <input type="date" class="form-control"  v-model="datosPago.fecha_final">
                                    </div>
                                    <div class="col-md-6" v-if="datos.id_tipo_pago == 1">
                                        <label for="exampleInputPassword1" class="form-label">Forma Pago</label>
                                        <template>
                                            <select class="form-select" v-model="datos.id_forma_pago" disabled>
                                                <option value="0" disabled>Seleccione la Forma de Pago</option>
                                                <option v-for="forma_pago in arrayForma2" :key="forma_pago.id" :value="forma_pago.id" v-text="forma_pago.nombre"></option>
                                            </select>
                                        </template>
                                    </div>
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Estado</label>
                                    <select class="form-select" v-model="datos.estado" :disabled="disabledAnulado || disabledEntregado">
                                        <option value="Entregado">Entregado</option>
                                        <option value="Concluido">Concluido</option>
                                        <!-- <template v-if="datos.estado == 'Entregado'">
                                            <option value="Entregado">Entregado</option>
                                        </template> -->
                                        <template v-if="datos.estado == 'Anulado'">
                                            <option value="Anulado">Anulado</option>
                                        </template>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                </div>
                                <!-- <div class="col-md-12">
                                    <label for="exampleInputPassword1" class="form-label">Descripcion</label>
                                    <textarea class="form-control" v-model="datos.descripcion" rows="2" disabled></textarea>
                                </div>    -->
                                &nbsp;
                            </form>
                        </div>

                        <br>
                        <div class="form-group row">
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                                <thead style="background-color: #46546C">
                                    <tr>
                                        <!-- <th scope="col" class="text-white">Opción</th> -->
                                        <th scope="col" class="text-white">Categoria</th>
                                        <th scope="col" class="text-white">Nombre</th>
                                        <th scope="col" class="text-white">Articulo</th>
                                        <th scope="col" class="text-white">Tienda</th>
                                        <th scope="col" class="text-white">Precio</th>
                                        <th scope="col" class="text-white">Cantidad</th>
                                        <th scope="col" class="text-white">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayDetalle.length">
                                    <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                        <!-- <td>
                                            <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                        </td> -->
                                        <td v-text="detalle.categoria"></td>
                                        <td v-text="detalle.articulo"></td>
                                        <td v-text="detalle.marca"></td>
                                        <td v-text="detalle.tienda"></td>
                                        <td>
                                            <input v-model="detalle.costo_venta" type="number" value="3" class="form-control" disabled>
                                        </td>

                                        <td>
                                            <input v-model="detalle.cantidad" type="number" value="3" class="form-control" disabled>
                                            <!-- <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.stock}}</span> -->
                                        </td>
                                        <td>
                                            {{detalle.sub_total}} bs
                                        </td>
                                    </tr>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="6" align="right"> <strong>Sub Total:</strong> </td>
                                        <td>{{datos.sub_total}} bs</td>
                                    </tr>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="6" align="right"> <strong>Descuento:</strong> </td>
                                        <td><input v-model="datos.descuento" type="number" value="0" class="form-control" disabled></td>
                                    </tr>
                                        <tr style="background-color: #CEECF5">
                                        <td colspan="6" align="right"> <strong>Total:</strong> </td>
                                        <td>{{datos.total}} bs</td>
                                    </tr>

                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="6">No hay Productos agregados</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end" style='width:96%;margin-left: 2.2%'>
                            <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverVentaListado()">Cancelar</button>
                            <button class="btn btn-info btn-lg text-white" type="button" @click="actualizarControlVacuna()">Actualizar</button>
                        </div>
                    </div>
                    </template>
                    <!-- FIN MODIFICAR -->
                    <!-- VER -->
                    <template v-if="listado==2">
                        <div class="card-header row m-0">
                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger text-white" @click="volverVentaListado()">
                                    <i class="fa fa-reply-all"></i>&nbsp;Volver
                                </button>
                                <button type="button" @click="cargarPdf(datos.id, datos.foto, datos.empresa_nombre)" class="btn btn-info">
                                    <i class="icon-doc text-white"></i><span class="text-white">&nbsp;Reporte</span>
                                </button>
                            </div>
                            <div class="col-md-4 text-center" ><h3 class="mb-0">REGISTRO DE VENTA</h3></div>
                            <div class="col-md-4">&nbsp;</div>
                            <!-- <div class="col-md-2">
                                <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt="">
                            </div>  -->
                            </div>
                        <div class="card-body">


                            <!-- <div class="card-header text-center row">
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-8">
                                    <h3 class="mt-2 mb-0">REGISTRO DE VENTA</h3>
                                </div>
                                <div class="col-md-2">
                                    <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt="">
                                </div>
                            </div>  -->
                            <div class="form-group row">
                                <form class="row g-3">
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Cliente</label>
                                    <input type="text" class="form-control"  v-model="datos.cliente" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Tipo Pago</label>
                                    <input type="text" class="form-control"  v-model="datos.tipoPago" disabled>
                                </div>
                                <template v-if="datos.formaPago != 'Cuenta por Cobrar'">
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Forma Pago</label>
                                        <input type="text" class="form-control"  v-model="datos.formaPago" disabled>
                                    </div>
                                </template>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Pagos</label>
                                    <template v-if="datos.id_descuento == 1">
                                        <input value="Unitario" type="text"  id="Unitario" class="form-control" disabled>
                                    </template>
                                    <template v-if="datos.id_descuento == 2">
                                        <input value="Mayorista" type="text"  id="Mayorista" class="form-control" disabled>
                                    </template>
                                    <template v-if="datos.id_descuento == 3">
                                        <input value="Preferencial" type="text"  id="Preferencial" class="form-control" disabled>
                                    </template>
                                    <template v-if="datos.id_descuento == 0">
                                        <input value="" type="text"  id="Preferencial" class="form-control" disabled>
                                    </template>
                                </div>

                            </form>
                            </div>

                            <br>
                            <div class="form-group row">
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead style="background-color: #46546C">
                                        <tr>
                                            <!-- <th scope="col" class="text-white">Categoria</th>                   -->
                                            <th scope="col" class="text-white">Nombre</th>
                                            <!-- <th scope="col" class="text-white">Marca</th>
                                            <th scope="col" class="text-white">Tienda</th>
                                            <th scope="col" class="text-white">Precio Producto</th> -->
                                            <th scope="col" class="text-white">Precio Venta</th>
                                            <th scope="col" class="text-white">Cantidad</th>
                                            <th scope="col" class="text-white">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <!-- <td v-text="detalle.categoria"></td> -->
                                            <td v-text="detalle.articulo"></td>
                                            <!-- <td v-text="detalle.marca"></td>
                                            <td v-text="detalle.tienda"></td>

                                                <template v-if="datos.id_descuento == 1">
                                                    <td v-text="detalle.costo_unitario"></td>
                                                </template>
                                                <template v-if="datos.id_descuento == 2">
                                                    <td v-text="detalle.costo_mayorista"></td>
                                                </template>
                                                <template v-if="datos.id_descuento == 3">
                                                    <td v-text="detalle.preferencial"></td>
                                                </template>
                                                <template v-if="datos.id_descuento != 1 && datos.id_descuento != 2 && datos.id_descuento != 3">
                                                    <td>0</td>
                                                </template>  -->
                                            <td style="font-weight: bold" v-text="detalle.costo_venta"></td>
                                            <td v-text="detalle.cantidad"></td>
                                            <td v-text="detalle.sub_total"></td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="2"></td>
                                            <td align="right"><strong>Sub Total:</strong></td>
                                            <td>{{datos.sub_total}} bs</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="2"></td>
                                            <td align="right"> <strong>Descuento:</strong> </td>
                                            <td v-text="datos.descuento"></td>
                                        </tr>
                                         <tr style="background-color: #CEECF5">
                                             <td colspan="2"></td>
                                            <td align="right"> <strong>Total:</strong> </td>
                                            <td>{{datos.total}} bs</td>
                                        </tr>

                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="7">No hay Productos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template v-if="listado==10">
                        <div class="card-header row m-0">
                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger text-white" @click="ocultarListado2()">
                                    <i class="fa fa-reply-all"></i>&nbsp;Volver
                                </button>
                                <button type="button" @click="cargarPdf(datos.id, datos.foto, datos.empresa_nombre)" class="btn btn-info">
                                    <i class="icon-doc text-white"></i><span class="text-white">&nbsp;Reporte</span>
                                </button>
                            </div>
                            <div class="col-md-4 text-center" ><h3 class="mb-0">REGISTRO DE VENTA</h3></div>
                            <div class="col-md-4">&nbsp;</div>
                            <!-- <div class="col-md-2">
                                <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt="">
                            </div>  -->
                            </div>
                        <div class="card-body">


                            <!-- <div class="card-header text-center row">
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-8">
                                    <h3 class="mt-2 mb-0">REGISTRO DE VENTA</h3>
                                </div>
                                <div class="col-md-2">
                                    <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt="">
                                </div>
                            </div>  -->
                            <div class="form-group row">
                                <form class="row g-3">
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Cliente</label>
                                    <input type="text" class="form-control"  v-model="datos.cliente" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Tipo Pago</label>
                                    <input type="text" class="form-control"  v-model="datos.tipoPago" disabled>
                                </div>
                                <template v-if="datos.formaPago != 'Cuenta por Cobrar'">
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Forma Pago</label>
                                        <input type="text" class="form-control"  v-model="datos.formaPago" disabled>
                                    </div>
                                </template>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Pagos</label>
                                    <template v-if="datos.id_descuento == 1">
                                        <input value="Unitario" type="text"  id="Unitario" class="form-control" disabled>
                                    </template>
                                    <template v-if="datos.id_descuento == 2">
                                        <input value="Mayorista" type="text"  id="Mayorista" class="form-control" disabled>
                                    </template>
                                    <template v-if="datos.id_descuento == 3">
                                        <input value="Preferencial" type="text"  id="Preferencial" class="form-control" disabled>
                                    </template>
                                    <template v-if="datos.id_descuento == 0">
                                        <input value="" type="text"  id="Preferencial" class="form-control" disabled>
                                    </template>
                                </div>

                            </form>
                            </div>

                            <br>
                            <div class="form-group row">
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead style="background-color: #46546C">
                                        <tr>
                                            <!-- <th scope="col" class="text-white">Categoria</th>                   -->
                                            <th scope="col" class="text-white">Nombre</th>
                                            <!-- <th scope="col" class="text-white">Marca</th>
                                            <th scope="col" class="text-white">Tienda</th>
                                            <th scope="col" class="text-white">Precio Producto</th> -->
                                            <th scope="col" class="text-white">Precio Venta</th>
                                            <th scope="col" class="text-white">Cantidad</th>
                                            <th scope="col" class="text-white">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <!-- <td v-text="detalle.categoria"></td> -->
                                            <td v-text="detalle.articulo"></td>
                                            <!-- <td v-text="detalle.marca"></td>
                                            <td v-text="detalle.tienda"></td>

                                                <template v-if="datos.id_descuento == 1">
                                                    <td v-text="detalle.costo_unitario"></td>
                                                </template>
                                                <template v-if="datos.id_descuento == 2">
                                                    <td v-text="detalle.costo_mayorista"></td>
                                                </template>
                                                <template v-if="datos.id_descuento == 3">
                                                    <td v-text="detalle.preferencial"></td>
                                                </template>
                                                <template v-if="datos.id_descuento != 1 && datos.id_descuento != 2 && datos.id_descuento != 3">
                                                    <td>0</td>
                                                </template>  -->
                                            <td style="font-weight: bold" v-text="detalle.costo_venta"></td>
                                            <td v-text="detalle.cantidad"></td>
                                            <td v-text="detalle.sub_total"></td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="2"></td>
                                            <td align="right"><strong>Sub Total:</strong></td>
                                            <td>{{datos.sub_total}} bs</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="2"></td>
                                            <td align="right"> <strong>Descuento:</strong> </td>
                                            <td v-text="datos.descuento"></td>
                                        </tr>
                                         <tr style="background-color: #CEECF5">
                                             <td colspan="2"></td>
                                            <td align="right"> <strong>Total:</strong> </td>
                                            <td>{{datos.total}} bs</td>
                                        </tr>

                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="7">No hay Productos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </template>
                    <!-- FIN VER -->
                    <!-- MODIFICAR ANTIPARASITARIO -->
                    <template v-if="listado==3">
                        <div class="card-body">
                        <div class="form-group row" style='margin-left: 1%'>
                            <form class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1" class="form-label">Cliente</label>
                                    <div class="input-group mb-6">
                                        <input type="text" readonly class="form-control" placeholder="Buscar Clientes.."  v-model="datos.cliente" aria-label="Buscar Productos.." aria-describedby="button-addon2">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Tipo Pago</label>
                                        <select class="form-select" v-model="datos.id_tipo_pago" disabled>
                                            <option value="0" disabled>Seleccione el Tipo Pago</option>
                                            <option v-for="tipo_pago in arrayPago" :key="tipo_pago.id" :value="tipo_pago.id" v-text="tipo_pago.nombre"></option>
                                        </select>
                                    </div>
                                    <div class="col-md-6" v-if="datos.id_tipo_pago == 2">
                                        <label for="exampleInputPassword1" class="form-label">Fecha Final</label>
                                        <input type="date" class="form-control"  v-model="datosPago.fecha_final">
                                    </div>
                                    <div class="col-md-6" v-if="datos.id_tipo_pago == 1">
                                        <label for="exampleInputPassword1" class="form-label">Forma Pago</label>
                                        <template>
                                            <select class="form-select" v-model="datos.id_forma_pago" disabled>
                                                <option value="0" disabled>Seleccione la Forma de Pago</option>
                                                <option v-for="forma_pago in arrayForma2" :key="forma_pago.id" :value="forma_pago.id" v-text="forma_pago.nombre"></option>
                                            </select>
                                        </template>
                                    </div>
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Estado</label>
                                    <select class="form-select" v-model="datos.estado" :disabled="disabledAnulado || disabledEntregado">
                                        <option value="Entregado">Entregado</option>
                                        <option value="Concluido">Concluido</option>
                                        <!-- <template v-if="datos.estado == 'Entregado'">
                                            <option value="Entregado">Entregado</option>
                                        </template> -->
                                        <template v-if="datos.estado == 'Anulado'">
                                            <option value="Anulado">Anulado</option>
                                        </template>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                </div>
                                <!-- <div class="col-md-12">
                                    <label for="exampleInputPassword1" class="form-label">Descripcion</label>
                                    <textarea class="form-control" v-model="datos.descripcion" rows="2" disabled></textarea>
                                </div>    -->
                                &nbsp;
                            </form>
                        </div>

                        <br>
                        <div class="form-group row">
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                                <thead style="background-color: #46546C">
                                    <tr>
                                        <!-- <th scope="col" class="text-white">Opción</th> -->
                                        <th scope="col" class="text-white">Categoria</th>
                                        <th scope="col" class="text-white">Nombre</th>
                                        <th scope="col" class="text-white">Articulo</th>
                                        <th scope="col" class="text-white">Tienda</th>
                                        <th scope="col" class="text-white">Precio</th>
                                        <th scope="col" class="text-white">Cantidad</th>
                                        <th scope="col" class="text-white">Sub Total</th>
                                    </tr>
                                </thead>
                                <tbody v-if="arrayDetalle.length">
                                    <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                        <!-- <td>
                                            <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                        </td> -->
                                        <td v-text="detalle.categoria"></td>
                                        <td v-text="detalle.articulo"></td>
                                        <td v-text="detalle.marca"></td>
                                        <td v-text="detalle.tienda"></td>
                                        <td>
                                            <input v-model="detalle.costo_venta" type="number" value="3" class="form-control" disabled>
                                        </td>

                                        <td>
                                            <input v-model="detalle.cantidad" type="number" value="3" class="form-control" disabled>
                                            <!-- <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.stock}}</span> -->
                                        </td>
                                        <td>
                                            {{detalle.sub_total}} bs
                                        </td>
                                    </tr>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="6" align="right"> <strong>Sub Total:</strong> </td>
                                        <td>{{datos.sub_total}} bs</td>
                                    </tr>
                                    <tr style="background-color: #CEECF5">
                                        <td colspan="6" align="right"> <strong>Descuento:</strong> </td>
                                        <td><input v-model="datos.descuento" type="number" value="0" class="form-control" disabled></td>
                                    </tr>
                                        <tr style="background-color: #CEECF5">
                                        <td colspan="6" align="right"> <strong>Total:</strong> </td>
                                        <td>{{datos.total}} bs</td>
                                    </tr>

                                </tbody>
                                <tbody v-else>
                                    <tr>
                                        <td colspan="6">No hay Productos agregados</td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end" style='width:96%;margin-left: 2.2%'>
                            <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverVentaListado()">Cancelar</button>
                            <button class="btn btn-info btn-lg text-white" type="button" @click="actualizarAntiparasitario()">Actualizar</button>
                        </div>
                    </div>
                    </template>
                    <!-- FIN MODIFICAR -->

                    <template v-if="listado==5">
                            <div class="card-header">
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger text-white" @click="ocultarListado1()">
                                        <i class="fa fa-reply-all"></i>&nbsp;Volver
                                    </button>
                                </div>
                            </div>
                            <div class="form-group row" id="profile">
                              <div class="col-md-8" >
                                    &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                        <select class="form-select col-md-3" v-model="criterio">
                                            <option value="cliente.nombre">Nombre</option>
                                        </select>
                                        &nbsp;&nbsp;&nbsp;
                                        <input type="text" v-model="buscarC" @keyup.enter="listarVentaCredito(1, buscarC, criterio)" class="form-control" placeholder="Texto a buscar">
                                        &nbsp;&nbsp;&nbsp;
                                        <button type="submit" @click="listarVentaCredito(1, buscarC, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <!-- Light table -->
                            <div class="table-responsive">
                            <table class="table table-striped table-hover" style='width:96%;margin-left: 2.2%'>
                                <thead style="background-color: #46546C">
                                    <tr>
                                        <th scope="col" class="text-white">Fecha</th>
                                        <th scope="col" class="text-white">Cliente</th>
                                        <th scope="col" class="text-white">Tienda</th>
                                        <th scope="col" class="text-white">Descuento</th>
                                        <th scope="col" class="text-white">Total</th>
                                        <th scope="col" class="text-white">Total Deuda</th>
                                        <th scope="col" class="text-white">Estado</th>
                                        <th scope="col" class="text-white">Tipo Pago</th>
                                        <th scope="col" class="text-white">Forma Pago</th>
                                        <th scope="col" class="text-white">Usuario</th>
                                        <!-- <th scope="col" class="text-white">Pagos</th> -->
                                        <th scope="col" class="text-white">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="servicio in arrayCredito" :key="servicio.id">
                                        <td v-text="servicio.fecha"></td>
                                        <td v-text="servicio.cliente"></td>
                                        <td v-text="servicio.tienda"></td>
                                        <td v-text="servicio.descuento"></td>
                                        <td v-text="servicio.total"></td>
                                        <td v-text="servicio.saldo"></td>
                                        <td class="text-center">
                                            <template v-if="servicio.estado=='Recepcionado'">
                                                <span class="badge bg-info tamaño">{{servicio.estado}}</span>
                                            </template>
                                            <template v-if="servicio.estado=='Cancelado'">
                                                <span class="badge bg-warning tamaño">Pagado</span>
                                            </template>
                                            <template v-if="servicio.estado=='Entregado'">
                                                <span class="badge bg-danger tamaño">Deudor</span>
                                            </template>
                                            <template v-if="servicio.estado=='Anulado'">
                                                <span class="badge bg-danger tamaño">{{servicio.estado}}</span>
                                            </template>
                                        </td>
                                        <td v-text="servicio.tipoP"></td>
                                        <td v-text="servicio.formaP"></td>
                                        <td v-text="servicio.name"></td>

                                        <!-- <td>
                                            <a class="dropdown-item" href="#"><i class="fa fa-edit text-warning"></i> Modificar</a>
                                        </td> -->
                                        <!-- <td>
                                            <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalPago" :disabled="servicio.tipoP == 'Contado' || servicio.estado == 'Anulado' ? true : false" @click="realizarPagos(servicio)">Pagos</button>
                                        </td>  -->
                                        <td>
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#" @click="verVenta2(servicio)"><i class="fa fa-eye text-success"></i> Ver detalle</a></li>
                                            <template v-if="servicio.estado=='Entregado'">
                                                <li><a class="dropdown-item" href="#" @click="verModificar(servicio)"><i class="fa fa-low-vision text-danger"></i> Devolución</a></li>
                                            </template>
                                            <template v-if="usuario.id_grupo == 1">
                                                <template v-if="servicio.estado=='Entregado'">
                                                    <li><a class="dropdown-item" href="#" @click="anularVenta(servicio.id,servicio.id_usuario)"><i class="fa fa-lock text-danger"></i> Anular</a></li>
                                            </template>
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
                                        <li class="page-item bg-color" v-if="paginationServicio.current_page > 1">
                                            <a class="page-link" href="#" @click.prevent="cambiarPaginaServicio(paginationServicio.current_page - 1, buscarC, criterio)">Ant</a>
                                        </li>
                                        <li class="page-item bg-color" v-for="page in pagesNumberServicio" :key="page" :class="[page==isActivedServicio ? 'active' :'']">
                                            <a class="page-link" href="#" @click.prevent="cambiarPaginaServicio(page, buscarC, criterio)" v-text="page">1</a>
                                        </li>
                                        <li class="page-item bg-color" v-if="paginationServicio.current_page < paginationServicio.last_page">
                                            <a class="page-link" href="#" @click.prevent="cambiarPaginaServicio(paginationServicio.current_page + 1, buscarC, criterio)">Sig</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>



                    </template>

                    <!-- VER MODIFICAR-->
                    <template v-if="listado==6">
                        <div class="card-header row m-0">
                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger text-white" @click="volverVentaListado();modificarCantidad()">
                                    <i class="fa fa-reply-all"></i>&nbsp;Volver
                                </button>
                                <!-- <button type="button" @click="cargarPdf(datos.id, datos.foto, datos.empresa_nombre)" class="btn btn-info">
                                    <i class="icon-doc text-white"></i><span class="text-white">&nbsp;Reporte</span>
                                </button>  -->
                            </div>
                            <div class="col-md-4 text-center" ><h3 class="mb-0">DEVOLUCION</h3></div>
                            <div class="col-md-4">&nbsp;</div>
                            <!-- <div class="col-md-2">
                                <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt="">
                            </div>  -->
                            </div>
                        <div class="card-body">


                            <!-- <div class="card-header text-center row">
                                <div class="col-md-2">&nbsp;</div>
                                <div class="col-md-8">
                                    <h3 class="mt-2 mb-0">REGISTRO DE VENTA</h3>
                                </div>
                                <div class="col-md-2">
                                    <img :src="'img/mi_empresa/'+ datos.foto" height="50px" align="left" alt="">
                                </div>
                            </div>  -->
                            <div class="form-group row">
                                <form class="row g-3">
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Cliente</label>
                                    <input type="text" class="form-control"  v-model="datos.cliente" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Tipo Pago</label>
                                    <input type="text" class="form-control"  v-model="datos.tipoPago" disabled>
                                </div>
                                <template v-if="datos.formaPago != 'Cuenta por Cobrar'">
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Forma Pago</label>
                                        <input type="text" class="form-control"  v-model="datos.formaPago" disabled>
                                    </div>
                                </template>
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Pagos</label>
                                    <template v-if="datos.id_descuento == 1">
                                        <input value="Unitario" type="text"  id="Unitario" class="form-control" disabled>
                                    </template>
                                    <template v-if="datos.id_descuento == 2">
                                        <input value="Mayorista" type="text"  id="Mayorista" class="form-control" disabled>
                                    </template>
                                    <template v-if="datos.id_descuento == 3">
                                        <input value="Preferencial" type="text"  id="Preferencial" class="form-control" disabled>
                                    </template>
                                    <template v-if="datos.id_descuento == 0">
                                        <input value="" type="text"  id="Preferencial" class="form-control" disabled>
                                    </template>
                                </div>

                            </form>
                            </div>

                            <br>
                            <div class="form-group row">
                                <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead style="background-color: #46546C">
                                        <tr>
                                            <th scope="col" class="text-white">Eliminar</th>
                                            <th scope="col" class="text-white">Nombre</th>
                                            <!-- <th scope="col" class="text-white">Marca</th>
                                            <th scope="col" class="text-white">Tienda</th>
                                            <th scope="col" class="text-white">Precio Producto</th> -->
                                            <th scope="col" class="text-white">Precio Venta</th>
                                            <th scope="col" class="text-white">Cantidad</th>
                                            <th scope="col" class="text-white">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalleDb(detalle.id,detalle.id_usuario,$event)" type="button" class="btn btn-danger btn-sm" :disabled="eliminandoDetalle || detalleEliminandoIds.includes(detalle.id)"><i class="icon-trash text-white"></i></button>
                                            </td>
                                            <td v-text="detalle.articulo"></td>
                                            <td style="font-weight: bold" v-text="detalle.costo_venta"></td>
                                            <td v-text="detalle.cantidad"></td>
                                            <td v-text="detalle.sub_total"></td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="3"></td>
                                            <td align="right"><strong>Sub Total:</strong></td>
                                            <td>{{datos.sub_total =calcularTotal.toFixed(2)}} bs</td>
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="3"></td>
                                            <td align="right"> <strong>Descuento:</strong> </td>
                                            <td v-text="datos.descuento"></td>
                                        </tr>
                                         <tr style="background-color: #CEECF5">
                                             <td colspan="3"></td>
                                            <td align="right"> <strong>Total:</strong> </td>
                                            <td>{{datos.total = datos.sub_total- datos.descuento}} bs</td>
                                        </tr>
                                        <template v-if="datos.formaPago == 'Mixta'">
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="3"></td>
                                            <td align="right"> <strong>Total Efect.:</strong> </td>
                                            <td>
                                                <input type="text" class="form-control"  v-model="datos.total_efectivo" min="0">
                                            </td>
                                        </tr>
                                         <tr style="background-color: #CEECF5">
                                             <td colspan="3"></td>
                                            <td align="right"> <strong>Total Dep.:</strong> </td>
                                            <td>{{datos.total_deposito = datos.total- datos.total_efectivo}} bs</td>
                                        </tr>
                                        </template>
                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="7">No hay Productos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>

                                </div>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <!-- <button class="btn btn-danger me-md-2 text-white" type="button">Cancelar</button> -->
                                <button class="btn btn-info btn-lg text-white" type="button" @click="modificarCantidad()">Modificar</button>
                                 </div>
                            </div>

                        </div>
                    </template>
                    <!-- FIN VER -->
                </div>
                </div>
            </div>
        <!-- </div>   -->

        <!--Modal Formulario Articulo-->
        <div class="modal fade" id="modalArticulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white">
                        <h4 class="modal-title ">Busqueda de Articulos</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(buscarP)" class="form-control" placeholder="Texto a buscar">
                                    <button type="submit" @click="listarArticulo(buscarP)" class="btn btn-primary text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">Tienda</th>
                                    <th scope="col" class="text-white">Precio</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tienda_articulo in arrayArticulo" :key="tienda_articulo.id">
                                    <td v-text="tienda_articulo.articulo"></td>
                                    <td v-text="tienda_articulo.tienda"></td>
                                    <td v-text="tienda_articulo.costo_venta"></td>

                                    <td>
                                        <button type="button" @click="seleccionarTiendaArticulo(tienda_articulo)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>
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
            </div>
        </div>
        <!--Fin modal Formulario Articulo-->


         <!--Modal Formulario Pago al Crédito-->
        <div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white" style="height:55px;">
                        <h4 class="modal-title ">Pago al Credito</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="row">
                                <div class="mb-3 col-sm-6">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Fecha</label>
                                    <div><input type="date" class="form-control" v-model="datosPago.fecha" disabled></div>
                                </div>
                                <div class="mb-3 col-sm-6">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Fecha Final</label>
                                    <div><input type="date" class="form-control" v-model="datosPago.fecha_final" disabled></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-sm-6">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Pago</label>
                                    <div>
                                        <!-- <input type="number" class="form-control" v-model="datosPago.amortizacion" v-on:change="calcularSaldo"> -->
                                        <input type="number" class="form-control" v-model="datosPago.amortizacion" v-on:change="parseFloat(calcularSaldo)" :disabled="ultimoPago.saldo > 0 ? false : true">
                                        <!-- <vue-numeric class="form-control" v-model="datosPago.amortizacion" separator="," :precision="2" v-on:change="parseFloat(calcularSaldo)" :disabled="ultimoPago.saldo > 0 ? false : true"/> -->
                                    </div>
                                </div>
                                <div class="mb-3 col-sm-6">
                                    <label for="inputPassword" class="col-sm-2 col-form-label">Saldo</label>
                                    <div class="border border-secondary rounded p-2 bg-gris">
                                        <!-- <input type="number" class="form-control" v-model="ultimoPago.saldo" disabled> -->
                                        <!-- <vue-numeric class="form-control" v-model="ultimoPago.saldo" separator="," :precision="2" disabled/> -->
                                        {{calcularSaldo.toFixed(2)}}
                                    </div>
                                </div>
                                <div class="mb-3 col-sm-6">
                                    <label for="inputPassword" class="col-sm-4 col-form-label">Monto Total</label>
                                    <div><input type="number" class="form-control" v-model="ultimoPago.monto_total" disabled></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="inputPassword" class="col-sm-2 col-form-label">Descripcion</label>
                                <textarea class="form-control" id="message-text" v-model="datosPago.descripcion"></textarea>
                                <!-- <div class="col-sm-10"><input type="text" class="form-control" v-model="datosCategoria.descripcion"></div> -->
                            </div>&nbsp;&nbsp;&nbsp;
                            <!-- <div v-show="errorPago" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjPago" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div> -->

<!--                             <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" v-model="buscarP" @keyup.enter="listarPagos()" class="form-control" placeholder="Texto a buscar">
                                        &nbsp;&nbsp;&nbsp;
                                        <button type="submit" @click="listarPagos(buscarP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>&nbsp; -->



                            <!-- detalles de Pagos -->
                            <table class="table table-striped table-hover">
                                <thead style="background-color: #46546C">
                                    <tr>
                                        <th scope="col" class="text-white">Numero</th>
                                        <th scope="col" class="text-white">Fecha</th>
                                        <th scope="col" class="text-white">Monto Total</th>
                                        <th scope="col" class="text-white">Pagos</th>
                                        <th scope="col" class="text-white">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(c_x_cobrar,index) in arrayCXCobrar" :key="c_x_cobrar.id">
                                        <td v-if="c_x_cobrar.monto_total != c_x_cobrar.saldo" class="text-center" width=10%>{{index}}</td>
                                        <td v-if="c_x_cobrar.monto_total != c_x_cobrar.saldo" v-text="c_x_cobrar.fecha"></td>
                                        <td v-if="c_x_cobrar.monto_total != c_x_cobrar.saldo" v-text="c_x_cobrar.monto_total"></td>
                                        <td v-if="c_x_cobrar.monto_total != c_x_cobrar.saldo" v-text="c_x_cobrar.amortizacion"></td>
                                        <td v-if="c_x_cobrar.monto_total != c_x_cobrar.saldo" v-text="c_x_cobrar.saldo"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- fin detalles de Pagos -->




                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal" @click="limpiarCXCobrar()">Cerrar</button>
                        <button type="button" class="btn btn-info text-white"  data-bs-dismiss="modal" @click="guardarAmortizacion()"  :disabled="ultimoPago.saldo > 0 ? false : true">Guardar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Pago al credito-->
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
                    fecha : moment().format('YYYY-MM-DD'),
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_tipo_pago : 1,
                    id_forma_pago : 2,
                    id_cliente : 0,
                    cliente : '',
                    tipoPago : '',
                    formaPago : '',
                    id_descuento: '',
                    foto: '',
                    empresa_nombre: '',
                    empresa_direccion: '',
                    estado: '',
                    tipo_venta:'Venta Control Vacuna',
                    total_efectivo : 0,
                    total_deposito : 0,
                    totalAux:0,
                    id_usuario:0,
                    id_venta:0,
                    total_efectivo_aux:0,
                    saldo_aux:0,
                },

                datosPago:{
                    id: 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    fecha_final : moment().format('YYYY-MM-DD'),
                    monto_total: '',
                    saldo : 0,
                    amortizacion : '',
                    descripcion: '',
                    id_pago: '',
                    grupo:0,
                },

                arrayVenta : [],
                arrayDetalle : [],
                arrayDetalle2 : [],
                arrayArticulo : [],
                arrayCliente: [],
                arrayVentaServicio : [],
                arrayCXCobrar : [],
                arrayEmpresa : [],
                arrayPago: [],
                arrayVentaVacuna: [],
                arrayVentaAntiparasitario: [],
                arrayListaPagos: [],
                arrayCostoPago: [{id:1,nombre:'Unitario'},{id:2,nombre:'Mayorista'},{id:3,nombre:'Preferencial'}],
                arrayForma: [],
                arrayForma2: [],
                eliminandoDetalle: false,
                detalleEliminandoIds: [],
                listado : 0,
                tipoAccion : 0,
                errorCompra : 0,
                errorMostrarMsjCompra : [],
                errorPago : 0,
                errorMostrarMsjPago : [],
                ultimoPago : {},
                CXCobrar: [],
                arrayCredito: [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                paginationServicio : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                paginationCotizacion : {
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
                buscarC : '',
                buscarP : '',
                numero : 0,
                imagenMiniatura : '',
                empresa : {},
                setTimeoutBuscador : '',
                disabledEntregado: false,
                disabledAnulado: false,
                usuario : {},

            }
        },
        computed : {
            isActived: function(){
                return this.pagination.current_page;
            },
            isActivedServicio: function(){
                return this.paginationServicio.current_page;
            },
            isActivedCotizacion: function(){
                return this.paginationCotizacion.current_page;
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
            pagesNumberServicio: function(){
                if(!this.paginationServicio.to){
                    return [];
                }
                var from = this.paginationServicio.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if(to >= this.paginationServicio.last_page){
                    to = this.paginationServicio.last_page;
                }
                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            pagesNumberCotizacion: function(){
                if(!this.paginationCotizacion.to){
                    return [];
                }
                var from = this.paginationCotizacion.current_page - this.offset;
                if(from < 1){
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if(to >= this.paginationCotizacion.last_page){
                    to = this.paginationCotizacion.last_page;
                }
                var pagesArray = [];
                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            },
            calcularTotal: function(){
                var resultado = 0.0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.datos.id_descuento ==1){
                    resultado = resultado + (this.arrayDetalle[i].costo_unitario*this.arrayDetalle[i].cantidad);
                    }else if(this.datos.id_descuento ==2)
                    { resultado = resultado + (this.arrayDetalle[i].costo_mayorista*this.arrayDetalle[i].cantidad);
                    }else if(this.datos.id_descuento ==3){
                     resultado = resultado + (this.arrayDetalle[i].costo_preferencial*this.arrayDetalle[i].cantidad);
                    }else
                    {}

                }
                return resultado;
            },
            calcularSaldo: function(){
                var resultado = 0.0
                var saldo = this.datosPago.amortizacion <= this.ultimoPago.saldo ? this.datosPago.amortizacion : 0
                resultado = this.ultimoPago.saldo - saldo;
                return resultado
            },
            calcularSaldoAnticipado: function(){
                this.datosPago.saldo = this.datos.total - this.datosPago.anticipo;
            }
        },
        methods : {
            ocultarListado1(){
                this.listado=0;
                this.buscarC='';
                this.listarVentaServicio(1, '', 'nombre');
                this.arrayVentaServicio=[];
                this.errores = {};
            },
            ocultarListado2(){
                this.listado=5;
                // this.buscarC='';
                // this.listarVentaServicio(1, '', 'nombre');
                // this.arrayVentaServicio=[];
                // this.errores = {};
            },
            ver(id_cliente){
                this.listado = 5
                this.datos.id_cliente=id_cliente
                this.listarVentaCredito(id_cliente,'','cliente.nombre')
            },
            listarVentaCredito(id_cliente, buscarC, criterio){
                let me=this;
                var url='/venta_tienda1/credito?id_cliente=' + id_cliente +'buscar=' + buscarC + '&criterio=' + criterio;

                axios.get(url).then(function(response){
                    me.arrayCredito=response.data.data;

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
            verModificar(data=[]){
                let me = this;
                me.listado = 6;
                me.datos.id=data['id'];
                me.datos.cliente=data['cliente'];
                me.datos.fecha=data['fecha'];
                me.datos.descuento=data['descuento'];
                me.datos.tipoPago=data['tipoP'];
                me.datos.formaPago=data['formaP'];
                me.datos.id_descuento=data['id_descuento'];
                me.datos.id_usuario=data['id_usuario'];
                me.datos.total=data['total'];
                me.datos.totalAux=data['total'];
                me.datos.id_venta=data['id'];
                me.datos.saldo_aux=data['saldo'];
                //me.datos.sub_total=data['sub_total'];
                me.datos.total_efectivo=data['total_efectivo'];
                me.datos.total_efectivo_aux=data['total_efectivo'];

                me.datos.total_deposito=data['total_deposito'];

                var url='/venta/permiso/detalle_tienda1?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                // var url='/paquete/permiso/detalle/venta?id=' + data['id'];
                // axios.get(url).then(function(response){
                //     me.arrayDetalle2= response.data;
                //     if(me.arrayDetalle.length==0){
                //         me.arrayDetalle = me.arrayDetalle2;
                //     }else{
                //         me.arrayDetalle=me.arrayDetalle.concat(me.arrayDetalle2);
                //     }
                // })
                .catch(function(error){
                    console.log(error);
                });

                // var url='/tienda?page=' + 1 + '&buscar=' + this.buscar + '&criterio=' + this.criterio;
                // axios.get(url).then(function(response){
                //     me.arrayEmpresa=response.data.data;
                //     me.empresa = me.arrayEmpresa.find(seg => (seg.id == me.arrayDetalle[0].id_tienda));
                //     me.datos.foto = me.empresa.foto;
                //     me.datos.empresa_nombre = me.empresa.nombre;
                //     me.datos.empresa_direccion = me.empresa.direccion;
                //     me.pagination={total:response.data.total,
                //         current_page:response.data.current_page,
                //         per_page: response.data.per_page,
                //         last_page: response.data.last_page,
                //         from: response.data.from,
                //         to: response.data.to
                //     }
                // })
                // .catch(function(error){
                //     console.log(error)
                // });
            },
            async eliminarDetalleDb(id,id_usuario,event){
                let me = this;
                if(me.eliminandoDetalle || me.detalleEliminandoIds.includes(id)){
                    return;
                }
                if(me.arrayDetalle.length == 1){
                    Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'No se puede eliminar el último Producto!'
                        })
                }else
                {
                    me.eliminandoDetalle = true;
                    me.detalleEliminandoIds.push(id);
                    if(event && event.currentTarget){
                        event.currentTarget.disabled = true;
                    }

                    try{
                        await axios.put('/detalle/eliminar?id_detalle_venta='+id,{
                             'id': id,
                             'id_usuario': me.datos.id_usuario,
                             'totalAux': me.datos.totalAux,
                             'total': me.datos.total,
                             'id_tipo_pago': me.datos.tipoPago,
                             //'id_venta': me.datos.id_ventaM,
                            // 'id_producto_compuesto': id_producto_compuesto,
                            // 'monto': me.datos.monto,
                        });
                        me.arrayDetalle = me.arrayDetalle.filter(detalle => detalle.id !== id);
                        me.arrayDetalle2 = me.arrayDetalle2.filter(detalle => detalle.id !== id);
                        await me.actualizarDetalleModificar();
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Producto eliminado...',
                            showConfirmButton: false,
                            timer: 500
                        });
                    } catch(error){
                        console.log(error);
                    } finally{
                        me.eliminandoDetalle = false;
                        me.detalleEliminandoIds = me.detalleEliminandoIds.filter(detalleId => detalleId !== id);
                    }
                    //me.actualizarDetalleModificar(me.datos.id_ventaM)
                }

            },
            async actualizarDetalleModificar()
            {
                let me = this;
                try{
                    const detalleUrl='/venta/permiso/detalle_tienda1?id=' + me.datos.id;
                    const paqueteUrl='/paquete/permiso/detalle/venta?id=' + me.datos.id;
                    const tiendaUrl='/tienda?page=' + 1 + '&buscar=' + this.buscar + '&criterio=' + this.criterio;

                    const [detalleResponse, paqueteResponse, tiendaResponse] = await Promise.all([
                        axios.get(detalleUrl),
                        axios.get(paqueteUrl),
                        axios.get(tiendaUrl)
                    ]);

                    me.arrayDetalle= detalleResponse.data;
                    me.arrayDetalle2= paqueteResponse.data;
                    if(me.arrayDetalle.length==0){
                        me.arrayDetalle = me.arrayDetalle2;
                    }else{
                        me.arrayDetalle=me.arrayDetalle.concat(me.arrayDetalle2);
                    }

                    me.arrayEmpresa=tiendaResponse.data.data;
                    me.empresa = me.arrayEmpresa.find(seg => (seg.id == me.arrayDetalle[0].id_tienda));
                    if(me.empresa){
                        me.datos.foto = me.empresa.foto;
                        me.datos.empresa_nombre = me.empresa.nombre;
                        me.datos.empresa_direccion = me.empresa.direccion;
                    }
                    me.pagination={total:tiendaResponse.data.total,
                        current_page:tiendaResponse.data.current_page,
                        per_page: tiendaResponse.data.per_page,
                        last_page: tiendaResponse.data.last_page,
                        from: tiendaResponse.data.from,
                        to: tiendaResponse.data.to
                    }
                }
                catch(error){
                    console.log(error)
                }
            },
            modificarCantidad(){
                let me = this;
                // if(this.arrayDetalle.length==0){
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Error...',
                //         text: 'No Existe productos agregados!'
                //     })
                // }
                if(this.arrayDetalle.find(seg => (seg.cantidad - seg.cantidad_auxiliar < 0))){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No hay stock para el producto!'
                    })
                } else {
                axios.put('/venta/modificarVenta',{
                    //'id': me.datos.id,
                    'id_venta': me.datos.id_venta,
                    'totalAux': me.datos.totalAux,
                    'total': me.datos.total,
                    'sub_total': me.datos.sub_total,
                    'id_usuario': me.datos.id_usuario,
                    'formaPago': me.datos.formaPago,
                    'total_efectivo': me.datos.total_efectivo,
                    'total_deposito': me.datos.total_deposito,
                    'total_efectivo_aux': me.datos.total_efectivo_aux,
                    'sub_total': me.datos.sub_total,
                    'detalle': me.arrayDetalle
                }).then(function(response){

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Modificar modificada...',
                        showConfirmButton: false,
                        timer: 500
                    });
                    me.inventario = 1;
                    me.arrayDetalle=[];
                    me.listarVenta(1,'', 'nombre');
                    me.listarVentaServicio(1, '', 'nombre');
                    me.listarVentaCredito(me.datos.id_cliente,'','cliente.nombre')
                    console.log(me.arrayCredito);
                    //me.listarVentaCredito(id_cliente,'','cliente.nombre');

                    me.listado = 0;
                })
                .catch(function(error){
                    console.log(error);
                });
                }
            },
            actualizarAntiparasitario(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe Productos agregados!'
                    })
                }
                let me = this;
                axios.put('/venta/modificar/antiparasitario',{
                    'id': me.datos.id,
                    'fecha': me.datosPago.fecha,
                    'fecha_final': me.datosPago.fecha_final,
                    'estado': me.datos.estado,
                    'total': me.datos.total,
                    'id_tipo_pago': me.datos.id_tipo_pago,
                    'id_forma_pago': (me.datos.id_tipo_pago == 2) ? me.arrayForma[0].id : me.datos.id_forma_pago,
                    'tipo_venta': me.datos.tipo_venta,
                    'descripcion_pago': me.datosPago.descripcion,
                    'saldo': me.datosPago.saldo,
                    'monto_total': me.datos.total,
                    'detalle': me.arrayDetalle,
                }).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Venta registrado exitosamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    me.volverVentaListado();
                    me.listarVenta(1,'', 'nombre');
                    me.listarVentaAntiparasitario(1,'', 'nombre');
                    me.limpiarDatosVenta();
                    console.log(me.datos.sub_total)
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            actualizarControlVacuna(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe Productos agregados!'
                    })
                }
                let me = this;
                axios.put('/venta/modificar',{
                    'id': me.datos.id,
                    'fecha': me.datosPago.fecha,
                    'fecha_final': me.datosPago.fecha_final,
                    'estado': me.datos.estado,
                    'total': me.datos.total,
                    'id_tipo_pago': me.datos.id_tipo_pago,
                    'id_forma_pago': (me.datos.id_tipo_pago == 2) ? me.arrayForma[0].id : me.datos.id_forma_pago,
                    'tipo_venta': me.datos.tipo_venta,
                    'descripcion_pago': me.datosPago.descripcion,
                    'saldo': me.datosPago.saldo,
                    'monto_total': me.datos.total,
                    'detalle': me.arrayDetalle,
                }).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Venta registrado exitosamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    me.volverVentaListado();
                    me.listarVenta(1,'', 'nombre');
                    me.listarVentaCotizacion(1,'', 'nombre');
                    me.limpiarDatosVenta();
                    console.log(me.datos.sub_total)
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            limpiarDatosVenta(){
                this.datos = {
                    id : 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_tipo_pago : 1,
                    id_forma_pago : 2,
                    id_cliente : 0,
                    cliente : '',
                    tipoPago : '',
                    formaPago : '',
                    id_descuento: '',
                    foto: '',
                    empresa_nombre: '',
                    empresa_direccion: '',
                    estado: '',
                    tipo_venta:'Venta Control Vacuna',
                }
            },
            listarVenta(page, buscar, criterio){
                let me=this;
                var url='/venta_tienda1/contado?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayVenta=response.data.data;
                    me.datos.grupo=me.arrayVenta[0].grupo;
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
            listarVentaCotizacion(page, buscar, criterio){
                let me=this;
                var url='/venta_tienda1/credito?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayVentaVacuna=response.data.data;
                    me.paginationCotizacion={total:response.data.total,
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
            listarVentaAntiparasitario(page, buscar, criterio){
                let me=this;
                var url='/ventaAntiparasitario_tienda1?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayVentaAntiparasitario=response.data.data;
                    me.paginationCotizacion={total:response.data.total,
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
            listarVentaBusquedaRapida(){
                let me=this;
                var url='/venta_tienda1/contado?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayVenta=response.data.data;
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
            BuscandoVenta(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarVentaBusquedaRapida,350)
            },
            listarVentaServicio(page, buscarC, criterio){
                let me=this;
                var url='/historialCliente?page=' + page + '&buscar=' + buscarC + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayVentaServicio=response.data.data;
                    me.paginationServicio={total:response.data.total,
                        current_page:response.data.current_page,
                        per_page: response.data.per_page,
                        last_page: response.data.last_page,
                        from: response.data.from,
                        to: response.data.to
                    }
                    me.arrayVentaServicio.forEach(item => {

                        item.total == 0 ? item.estado_pendiente = 'Cancelado' : item.estado_pendiente = 'Pendiente'
                    })
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            listarVentaServicioVentaDirecta(){
                let me=this;
                var url='/historialCliente?page=' + 1 + '&buscar=' + me.buscarC + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayVentaServicio=response.data.data;
                    me.paginationServicio={total:response.data.total,
                        current_page:response.data.current_page,
                        per_page: response.data.per_page,
                        last_page: response.data.last_page,
                        from: response.data.from,
                        to: response.data.to
                    }
                    me.arrayVentaServicio.forEach(item => {

                    item.total == 0 ? item.estado_pendiente = 'Cancelado' : item.estado_pendiente = 'Pendiente'
                    })
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            BuscandoVentaServicio(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarVentaServicioVentaDirecta,350)
            },
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarVenta(page, buscar, criterio);
            },
            cambiarPaginaServicio(page, buscar, criterio){
                let me=this;
                me.paginationServicio.current_page=page;
                me.listarVentaServicio(page, buscar, criterio);
            },
            cambiarPaginaCotizacion(page, buscar, criterio){
                let me=this;
                me.paginationCotizacion.current_page=page;
                me.listarVentaCotizacion(page, buscar, criterio);
            },
            listarArticulo(buscarP){
                let me = this;
                var url='/tienda/listarSinPaginate2?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayArticulo= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            encuentra(id){
                var sw=0;
                for(var i=0;i<this.arrayDetalle.length;i++){
                    if(this.arrayDetalle[i].id_articulo==id){
                        sw=true;
                    }
                }
                return sw;
            },
            eliminarDetalle(index){
                let me = this;
                me.arrayDetalle.splice(index,1);
            },
            seleccionarTiendaArticulo(data=[]){
                let me = this;
                if(me.encuentra(data['id_articulo'])){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Este articulo ya se encuentra agregado!'
                    })
                }
                else{
                    me.arrayDetalle.push({
                        id_tienda_articulo : data['id'],
                        id_articulo : data['id_articulo'],
                        articulo : data['articulo'],
                        categoria : data['categoria'],
                        marca : data['marca'],
                        tienda : data['tienda'],
                        costo_venta : data['costo_venta'],
                        cantidad : 1,
                        sub_total : data['sub_total']
                    });
                }
            },
            cancelarCompra(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.nombre = '';
                me.buscarP = '';
            },
            volverVentaListado(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.nombre = '';
                me.buscarP = '';
                me.listado = 0;
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
            selectTipoP(){
                let me=this;
                var url='/tipoPago/selectTipoP';
                axios.get(url).then(function(response){
                    me.arrayPago=response.data;
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            selectFormaP(){
                let me=this;
                var url='/formaPago/selectFormaP';
                axios.get(url).then(function(response){
                    me.arrayForma=response.data;
                    me.arrayForma2 = response.data;
                    me.arrayForma2 = me.arrayForma2.filter((item) => item.id !== 1);
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            verVenta(data=[]){
                let me = this;
                me.listado = 2;
                me.datos.id=data['id'];
                me.datos.cliente=data['cliente'];
                me.datos.fecha=data['fecha'];
                me.datos.descuento=data['descuento'];
                me.datos.tipoPago=data['tipoP'];
                me.datos.formaPago=data['formaP'];
                me.datos.id_descuento=data['id_descuento'];
                me.datos.total=data['total'];
                me.datos.sub_total=data['sub_total'];

                var url='/venta/permiso/detalle_tienda1?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                var url='/paquete/permiso/detalle/venta?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle2= response.data;
                    if(me.arrayDetalle.length==0){
                        me.arrayDetalle = me.arrayDetalle2;
                    }else{
                        me.arrayDetalle=me.arrayDetalle.concat(me.arrayDetalle2);
                    }
                })
                .catch(function(error){
                    console.log(error);
                });

                var url='/tienda?page=' + 1 + '&buscar=' + this.buscar + '&criterio=' + this.criterio;
                axios.get(url).then(function(response){
                    me.arrayEmpresa=response.data.data;
                    me.empresa = me.arrayEmpresa.find(seg => (seg.id == me.arrayDetalle[0].id_tienda));
                    me.datos.foto = me.empresa.foto;
                    me.datos.empresa_nombre = me.empresa.nombre;
                    me.datos.empresa_direccion = me.empresa.direccion;
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
            verVenta2(data=[]){
                let me = this;
                me.listado = 10;
                me.datos.id=data['id'];
                me.datos.cliente=data['cliente'];
                me.datos.fecha=data['fecha'];
                me.datos.descuento=data['descuento'];
                me.datos.tipoPago=data['tipoP'];
                me.datos.formaPago=data['formaP'];
                me.datos.id_descuento=data['id_descuento'];
                me.datos.total=data['total'];
                me.datos.sub_total=data['sub_total'];

                var url='/venta/permiso/detalle_tienda1?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                var url='/paquete/permiso/detalle/venta?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle2= response.data;
                    if(me.arrayDetalle.length==0){
                        me.arrayDetalle = me.arrayDetalle2;
                    }else{
                        me.arrayDetalle=me.arrayDetalle.concat(me.arrayDetalle2);
                    }
                })
                .catch(function(error){
                    console.log(error);
                });

                var url='/tienda?page=' + 1 + '&buscar=' + this.buscar + '&criterio=' + this.criterio;
                axios.get(url).then(function(response){
                    me.arrayEmpresa=response.data.data;
                    me.empresa = me.arrayEmpresa.find(seg => (seg.id == me.arrayDetalle[0].id_tienda));
                    me.datos.foto = me.empresa.foto;
                    me.datos.empresa_nombre = me.empresa.nombre;
                    me.datos.empresa_direccion = me.empresa.direccion;
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
            actualizarVista()
            {
                var url='/venta/permiso/detalle_tienda1?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                var url='/paquete/permiso/detalle/venta?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle2= response.data;
                    if(me.arrayDetalle.length==0){
                        me.arrayDetalle = me.arrayDetalle2;
                    }else{
                        me.arrayDetalle=me.arrayDetalle.concat(me.arrayDetalle2);
                    }
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            editarDetalle(data=[]){
                let me = this;
                me.selectTipoP();
                me.selectFormaP();
                me.listado = 1;
                me.datos.id=data['id'];
                me.datos.id_cliente=data['id_cliente'];
                me.datos.cliente=data['cliente'];
                me.datos.estado=data['estado'];
                me.datos.fecha=data['fecha'];
                me.datos.descuento=data['descuento'];
                me.datos.descripcion=data['descripcion'];
                me.datos.tipoPago=data['tipoP'];
                me.datos.formaPago=data['formaP'];
                me.datos.id_descuento=data['id_descuento'];
                me.datos.sub_total=data['sub_total'];
                me.datos.total=data['total'];
                me.disabledEntregado = data['estado'] == 'Entregado' ? true : false;
                me.disabledAnulado = data['estado'] == 'Anulado' ? true : false;
                var url='/venta/permiso/detalle_tienda1?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            editarDetalle2(data=[]){
                let me = this;
                me.selectTipoP();
                me.selectFormaP();
                me.listado = 3;
                me.datos.id=data['id'];
                me.datos.id_cliente=data['id_cliente'];
                me.datos.cliente=data['cliente'];
                me.datos.estado=data['estado'];
                me.datos.fecha=data['fecha'];
                me.datos.descuento=data['descuento'];
                me.datos.descripcion=data['descripcion'];
                me.datos.tipoPago=data['tipoP'];
                me.datos.formaPago=data['formaP'];
                me.datos.id_descuento=data['id_descuento'];
                me.datos.sub_total=data['sub_total'];
                me.datos.total=data['total'];
                me.disabledEntregado = data['estado'] == 'Entregado' ? true : false;
                me.disabledAnulado = data['estado'] == 'Anulado' ? true : false;
                var url='/venta/permiso/detalle_tienda1?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            // cargarPdf(id,foto,empresa_nombre) {

            //     axios.get('/venta/pdfVentasGeneral?id='
            //      + id  + '&foto='+ foto
            //      + '&empresa_nombre=' + empresa_nombre,{responseType: 'blob'})
            //         .then(response => {
            //             var blob = new Blob([response.data], {type: 'application/pdf'});
            //             var downloadUrl = URL.createObjectURL(blob);
            //             window.open(downloadUrl, '_blank');
            //         })
            //         .catch(error => {
            //             this.errorReport();
            //             console.log(error);
            //         })
            // },

            guardarVenta(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe permisos agregados!'
                    })
                }
                let me = this;
                axios.post('/venta/guardar',{
                    'fecha': me.datos.fecha,
                    'sub_total': me.datos.sub_total,
                    'descuento': me.datos.descuento,
                    'total': me.datos.total,
                    'id_cliente': me.datos.id_cliente,
                    'id_tipo_pago': me.datos.id_tipo_pago,
                    'id_forma_pago': me.datos.id_forma_pago,
                    'detalle': me.arrayDetalle
                }).then(function(response){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Venta registrado exitosamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    me.volverVentaListado();
                    me.listarVenta(1,'', 'nombre');
                    me.limpiarDatosCategoria();
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            guardarAmortizacion(){
                let me = this;
                if(me.datosPago.amortizacion == 0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Ingrese un monto!'
                    })
                    me.limpiarCXCobrar();
                }else {
                    console.log(me.ultimoPago.amortizacion);
                    if(me.datosPago.amortizacion > parseFloat(me.ultimoPago.saldo)) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Monto Mayor al Saldo!'
                        })
                        me.limpiarPago();
                    } else {
                        axios.post('/c_x_cobrar/guardar',{
                            'fecha': me.datosPago.fecha,
                            'monto_total': parseFloat(me.ultimoPago.monto_total).toFixed(2),
                            'amortizacion': parseFloat(me.datosPago.amortizacion).toFixed(2),
                            'saldo': me.ultimoPago.saldo,
                            'descripcion': me.datosPago.descripcion,
                            'id_pago': me.datosPago.id_pago,
                        }).then(function(response){
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Pago registrado exitosamente',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            console.log('datosPago', me.datosPago);
                            me.volverVentaListado();
                            me.listarVenta(1,'', 'nombre');
                            me.listarCXCobrar(me.datosPago.id_pago);
                            me.limpiarDatosCategoria();
                        })
                        .catch(function(error){
                            console.log(error);
                        });
                    }

                }
            },
            validarCompra(){
                this.errorCompra = 0;
                this.errorMostrarMsjCompra = [];

                if(!this.datos.nombre) this.errorMostrarMsjCompra.push("El nombre del Compra no puede estar vacio ");
                if(this.errorMostrarMsjCompra.length) this.errorCompra=1;
                return this.errorCompra;
            },
            frmVenta(){
                this.listado = 1;
                this.selectCliente();
                this.selectTipoP();
                this.selectFormaP();
            },
            anularVenta(id,id_usuario){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Anular esta Venta??',
                    text: "No Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/venta/anular_tienda1?id='+ id + '&id_usuario=' + id_usuario).then(function (response) {
                        me.listarVentaCredito(me.datos.id_cliente,'','cliente.nombre');
                        me.listarVenta(1,'', 'nombre');
                        me.listarVentaServicio(1, '', 'nombre');
                        //me.listado=0;
                        swalWithBootstrapButtons.fire(
                        'Anulado!',
                        'Este venta se ha Anulado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Esta venta no ha tenido cambios :)',
                    'error'
                    )
                }
                })
            },
            limpiarDatosCategoria(){
                this.datos = {
                    id : 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_tipo_pago : 0,
                    id_forma_pago : 0,
                    id_cliente : 0,
                    cliente : '',
                    tipoPago : '',
                    formaPago : '',
                }
                this.datosPago.amortizacion=0;
            },
            listarPagos(buscarP){
                let me = this;
                var url='/pagos?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayListaPagos= response.data;
                    me.datosPago.fecha_final=me.arrayListaPagos[0].fecha_final;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarCXCobrar(pago){
                let me = this;
                var url='/c_x_cobrar?buscar=' + pago;
                axios.get(url).then(function(response){
                    me.arrayCXCobrar= response.data;
                    me.CXCobrar = response.data;
                    me.cargarUltimoPago();
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            realizarPagos(venta) {
                this.listarPagos(venta.id);
                this.datosPago.id_pago = venta.id;
                this.listarCXCobrar(this.datosPago.id_pago);
                this.datosPago.monto_total = venta.total;
                this.datosPago.saldo = this.datosPago.monto_total;
                this.datosPago.amortizacion = this.datosPago.amortizacion;
            },
            cargarUltimoPago(){
                const array = this.CXCobrar;
                this.ultimoPago=array[array.length-1];
                //this.tipoFecha = this.tipoFechas.find(f => f.id > 0);
                // this.ultimoPago = this.CXCobrar.pop();
                // console.log(this.CXCobrar.length)
                //this.ultimoPago = this.CXCobrar.find(f => Math.max(this.CXCobrar.id));
            },

            limpiarCXCobrar(){
                this.datosPago ={
                    id: 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    monto_total: 0,
                    saldo : 0,
                    amortizacion : 0,
                    descripcion: '',
                    amortizacion: 0,
                    id_pago: 0,
                },

                this.ultimoPago = {
                    monto_total: 0,
                    amorticacion: 0,
                    saldo: 0,
                };
            },
            limpiarPago(){
                this.datosPago.amortizacion = 0;
                this.datosPago.descripcion = '';
            },

            listarEmpresa(page, buscar, criterio){
                let me=this;

            },
            cargarPdf(id,foto) {
                let time=1000;
                this.downloadReport(time);
                axios.get('/venta/pdfVentasGeneral?id=' + id  + '&foto='+ foto,{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        this.errorReport();
                        console.log(error);
                    })
            },
            downloadReport(milisecond){
                let timerInterval
                    Swal.fire({
                    title: 'Cargando Reporte!',
                    html: 'Por favor espere!! <b></b> milliseconds.',
                    timer: milisecond,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                        timerInterval = setInterval(() => {
                        b.textContent = Swal.getTimerLeft()
                        }, 300)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                    }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        console.log('I was closed by the timer')
                    }
                })
            },
            errorReport(){
                Swal.fire({
                    icon: 'error',
                    title: 'Error al Cargar el Reporte!...',
                    text: 'Comuniquese con el Administrador del Sistema',
                })
            },
        },
        mounted() {
            this.listarVenta(1, this.buscar, this.criterio);
            this.listarVentaServicio(1, this.buscarC, this.criterio);
            this.listarVentaCotizacion(1, this.buscar, this.criterio);
            this.listarVentaAntiparasitario(1, this.buscar, this.criterio);
            this.usuarioAuth();

            //this.listarEmpresa(1, this.buscar, this.criterio);

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
    .bg-gris{
        background-color: #D8DBE0;
    }
    .tamaño{
       width: 100px !important;
    }
</style>

<template>
    <main class="main">
        <sales-payments-workspace
            :rows="arrayVenta"
            :details="arrayDetalle"
            :datos="datos"
            :payment="datosPago"
            :payment-history="arrayCXCobrar"
            :last-payment="ultimoPago"
            :payment-forms="arrayForma"
            :pagination="pagination"
            :pages="pagesNumber"
            :listado="listado"
            :search="buscar"
            :criterion="criterio"
            :saving="isSaving"
            @update:search="buscar = $event"
            @update:criterion="criterio = $event"
            @search="listarVenta(1, buscar, criterio)"
            @page="cambiarPagina($event, buscar, criterio)"
            @pay="realizarPagos"
            @clear-payment="limpiarCXCobrar"
            @save-payment="guardarAmortizacion"
            @view="verVenta"
            @back="volverVentaListado"
            @print="cargarPdf(datos.id, datos.foto)"
        />
        <template v-if="false">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                   <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">PAGOS</h3></div>
                    <template v-if="listado==0">
                        
                        <!-- Inicio -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Ventas</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Servicios</button>
                        </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

                            <div class="form-group row" id="home">
                            
                            <div class="col-md-8">
                                &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                    <select class="form-select col-md-3" v-model="criterio">
                                        <option value="cliente.nombre">Nombre</option>
                                        <option value="tienda.nombre">Tienda</option>
                                        <option value="users.name">Usuario</option>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup.enter="listarVenta(1, buscar, criterio)" @keyup="BuscandoVenta()"  class="form-control" placeholder="Texto a buscar">
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
                                    <th scope="col" class="text-white">Tienda</th>
                                    <th scope="col" class="text-white">Descuento</th>
                                    <th scope="col" class="text-white">Total</th>
                                    <th scope="col" class="text-white text-center">Estado</th>
                                    <!-- <th scope="col" class="text-white">Tipo de Pago</th> -->
                                    <th scope="col" class="text-white">Pago</th>
                                    <th scope="col" class="text-white">Usuario</th>
                                    <!-- <th scope="col" class="text-white">Modificar</th> -->
                                    <th scope="col" class="text-white">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="venta in arrayVenta" :key="venta.id">
                                    <td v-text="venta.fecha"></td>
                                    <td v-text="venta.cliente"></td>
                                    <td v-text="venta.tienda"></td>
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
                                    </td> 
                                    <!-- <td v-text="venta.tipoP"></td> -->
                                    <td>
                                        <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalPago" :disabled="venta.tipoP == 'Contado' ? true : false" @click="realizarPagos(venta)">Pagos</button>    
                                    </td>   
                                    <td v-text="venta.name"></td>
                                    <!-- <td>
                                        <a class="dropdown-item" href="#"><i class="fa fa-edit text-warning"></i> Modificar</a>
                                    </td> -->
                                    <td>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" @click="verVenta(venta)"><i class="fa fa-eye text-success"></i> Ver detalle</a></li>
                                            
                                            <!-- <template v-if="venta.estado=='Entregado'">
                                                <li><a class="dropdown-item" href="#" @click="anularVenta(venta.id)"><i class="fa fa-lock text-danger"></i> Anular</a></li>
                                            </template> -->
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
                                        <option value="tienda.nombre">Tienda</option>
                                        <option value="users.name">Usuario</option>                                     
                                    </select>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup.enter="listarVentaServicio(1, buscar, criterio)"  @keyup="BuscandoVentaServicio()"  class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarVentaServicio(1, buscar, criterio)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
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
                                    <!-- <th scope="col" class="text-white">Tipo de Pago</th> -->
                                    <th scope="col" class="text-white">Pagos</th>
                                    <th scope="col" class="text-white">Usuarios</th>
                                    <th scope="col" class="text-white">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="servicio in arrayVentaServicio" :key="servicio.id">
                                    <td v-text="servicio.fecha"></td>
                                    <td v-text="servicio.cliente"></td>
                                    <td v-text="servicio.tienda"></td>
                                    <td v-text="servicio.descuento"></td>
                                    <td v-text="servicio.total"></td>
                                    <td class="text-center">
                                        <template v-if="servicio.estado=='Recepcionado'">
                                            <span class="badge bg-info tamaño">{{servicio.estado}}</span>                                           
                                        </template>
                                        <template v-if="servicio.estado=='Concluido'">
                                            <span class="badge bg-warning tamaño">{{servicio.estado}}</span>
                                        </template>
                                        <template v-if="servicio.estado=='Entregado'">
                                            <span class="badge bg-success tamaño">{{servicio.estado}}</span>
                                        </template>
                                        <template v-if="servicio.estado=='Anulado'">
                                            <span class="badge bg-danger tamaño">{{servicio.estado}}</span>                                           
                                        </template>
                                    </td> 
                                    <!-- <td v-text="servicio.tipoP"></td> -->
                                    <!-- <td>
                                        <a class="dropdown-item" href="#"><i class="fa fa-edit text-warning"></i> Modificar</a>
                                    </td> -->
                                    <td>
                                        <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalPago" :disabled="servicio.tipoP == 'Contado' ? true : false" @click="realizarPagos(servicio)">Pagos</button>    
                                    </td>   
                                    <td v-text="servicio.name"></td>
                                    <td>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" @click="verVenta(servicio)"><i class="fa fa-eye text-success"></i> Ver detalle</a></li>
                                            <template v-if="servicio.estado=='Entregado'">
                                                <li><a class="dropdown-item" href="#" @click="anularVenta(servicio.id,servicio.id_orden_servicio)"><i class="fa fa-lock text-danger"></i> Anular</a></li>
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
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaServicio(paginationServicio.current_page - 1, buscar, criterio)">Ant</a>
                                    </li>
                                    <li class="page-item bg-color" v-for="page in pagesNumberServicio" :key="page" :class="[page==isActivedServicio ? 'active' :'']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaServicio(page, buscar, criterio)" v-text="page">1</a>
                                    </li>
                                    <li class="page-item bg-color" v-if="paginationServicio.current_page < paginationServicio.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPaginaServicio(paginationServicio.current_page + 1, buscar, criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        </div>
                        </div>
                        <!--fin  -->

                    </template>
                    
                    
                    <template v-if="listado==1">
                        <div class="card-body">
                            <div class="form-group row" style='margin-left: 1%'>   
                            <form class="row">
                                <div class="col-md-6">
                                <label for="exampleInputPassword1" class="form-label">Cliente</label>
                                    <select class="form-select" v-model="datos.id_cliente">
                                        <option value="0" disabled>Seleccione el Cliente</option>
                                        <option v-for="cliente in arrayCliente" :key="cliente.id" :value="cliente.id" v-text="cliente.nombre"></option>
                                    </select>  
                                </div>

                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha" disabled>  
                                </div>

                                <div class="col-md-6">
                                <label for="exampleInputPassword1" class="form-label">Tipo Pago</label>
                                    <select class="form-select" v-model="datos.id_tipo_pago">
                                        <option value="0" disabled>Seleccione el Tipo Pago</option>
                                        <option v-for="tipo_pago in arrayPago" :key="tipo_pago.id" :value="tipo_pago.id" v-text="tipo_pago.nombre"></option>
                                    </select>  
                                </div>

                                <div class="col-md-6">
                                <label for="exampleInputPassword1" class="form-label">Forma Pago</label>
                                    <select class="form-select" v-model="datos.id_forma_pago">
                                        <option value="0" disabled>Seleccione la Forma de Pago</option>
                                        <option v-for="forma_pago in arrayForma" :key="forma_pago.id" :value="forma_pago.id" v-text="forma_pago.nombre"></option>
                                    </select>  
                                </div>&nbsp;
                                <div class="col-md-12">
                                    <label>Articulos<span style="color:red;" >(*Seleccione)</span></label>
                                    <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalArticulo"><i class="fa fa-search"></i> Agregar Articulos</button>
                                </div>  
                            </form>           
                                <div class="col-md-12">
                                    <div v-show="errorCompra" class="form-group row div-error">
                                        <div class="text-center text-error">
                                            <div v-for="error in errorMostrarMsjCompra" :key="error" v-text="error">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="form-group row">
                                <div class="table-responsive">
                                <table class="table table-striped table-hover" style='width:96%;margin-left: 2%'>
                                    <thead style="background-color: #46546C">
                                        <tr>                      
                                            <th scope="col" class="text-white">Opción</th>
                                            <th scope="col" class="text-white">Nombre</th>
                                            <th scope="col" class="text-white">Tienda</th>
                                            <th scope="col" class="text-white">Precio</th>
                                            <th scope="col" class="text-white">Cantidad</th>
                                            <th scope="col" class="text-white">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td>
                                                <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                            </td>
                                            <td v-text="detalle.articulo"></td>
                                            <td v-text="detalle.tienda"></td>
                                            <td>
                                                <template v-if="datos.id_descuento == 1">
                                                        <input v-model="detalle.costo_unitario" type="number"  id="Unitario" class="form-control">
                                                </template> 
                                                <template v-if="datos.id_descuento == 2">
                                                        <input v-model="detalle.costo_mayorista" type="number"  id="Mayorista" class="form-control">
                                                </template>
                                                <template v-if="datos.id_descuento == 3">
                                                        <input v-model="detalle.costo_preferencial" type="number"  id="Preferencial" class="form-control">
                                                </template> 
                                            </td>
                                            <td><input v-model="detalle.cantidad" type="number" value="3" class="form-control"></td>
                                            <td>{{detalle.sub_total = detalle.costo_venta*detalle.cantidad}}</td>                                                                             
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="3"></td>
                                            <td align="right"> <strong>Sub Total:</strong> </td>
                                            <td>{{datos.sub_total = calcularSubTotal.toFixed(2)}} bs</td> 
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="3"></td>
                                            <td align="right"> <strong>Descuento:</strong> </td>
                                            <td><input v-model="datos.descuento" type="number" value="0" class="form-control"></td>
                                        </tr>
                                         <tr style="background-color: #CEECF5">
                                             <td colspan="3"></td>
                                            <td align="right"> <strong>Total:</strong> </td>
                                            <td>{{datos.total = datos.sub_total- datos.descuento}} bs</td>
                                        </tr>

                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">No hay Permisos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end" style='width:96%;margin-left: 2.2%'>
                                <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverVentaListado()">Cancelar</button>
                                <button class="btn btn-info btn-lg text-white" type="button" @click="guardarVenta()">Guardar</button>
                            </div>
                        </div>
                    
                    
                    
                    
                    
                    
                    
                    </template>
                    
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
                            <div class="col-md-4 text-center"><h3 class="mb-0">REGISTRO DE VENTA</h3></div>
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
                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Forma Pago</label>
                                    <input type="text" class="form-control"  v-model="datos.formaPago" disabled>  
                                </div> 
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
                                            <th scope="col" class="text-white">Nombre</th>
                                            <th scope="col" class="text-white">Tienda</th>
                                            <th scope="col" class="text-white">Precio</th>
                                            <th scope="col" class="text-white">Cantidad</th>
                                            <th scope="col" class="text-white">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody v-if="arrayDetalle.length">
                                        <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                            <td v-text="detalle.articulo"></td>
                                            <td v-text="detalle.tienda"></td>

                                            <td v-text="detalle.costo_venta"></td>


                                            <td v-text="detalle.cantidad"></td>
                                            <td v-text="detalle.sub_total"></td>                                                                             
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="3"></td>
                                            <td align="right"><strong>Sub Total:</strong></td>
                                            <td>{{datos.sub_total}} bs</td> 
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="3"></td>
                                            <td align="right"> <strong>Descuento:</strong> </td>
                                            <td v-text="datos.descuento"></td>
                                        </tr>
                                         <tr style="background-color: #CEECF5">
                                             <td colspan="3"></td>
                                            <td align="right"> <strong>Total:</strong> </td>
                                            <td>{{datos.total}} bs</td>
                                        </tr>

                                    </tbody>
                                    <tbody v-else>
                                        <tr>
                                            <td colspan="6">No hay Permisos agregados</td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </template>
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
                    <div class="modal-header btn btn-info text-white">
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
                            &nbsp;&nbsp;&nbsp;
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
                                        <td class="text-center" width=10%>{{index+1}}</td>
                                        <td v-text="c_x_cobrar.fecha"></td>
                                        <td v-text="c_x_cobrar.monto_total"></td>
                                        <td v-text="c_x_cobrar.amortizacion"></td>
                                        <td v-text="c_x_cobrar.saldo"></td>                                
                                    </tr>
                                </tbody>
                            </table>
                            <!-- fin detalles de Pagos -->




                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal" @click="limpiarCXCobrar()">Cerrar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin modal Formulario Pago al credito-->
        </template>
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
                    id_descuento: '',
                    foto: '',
                    empresa_nombre: '',
                    empresa_direccion: '',
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
                    id_forma_pago: 2,
                },  
                          
                arrayVenta : [],
                arrayDetalle : [],
                arrayArticulo : [],
                arrayCliente: [],
                arrayVentaServicio : [],
                arrayCXCobrar : [],
                arrayEmpresa : [],
                arrayPago: [],
                arrayListaPagos: [],
                arrayCostoPago: [{id:1,nombre:'Unitario'},{id:2,nombre:'Mayorista'},{id:3,nombre:'Preferencial'}],
                arrayForma: [],
                listado : 0,
                tipoAccion : 0,
                errorCompra : 0,
                errorMostrarMsjCompra : [],
                errorPago : 0,
                errorMostrarMsjPago : [],
                ultimoPago : {},
                CXCobrar: [],
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
                offset : 3,
                criterio : 'cliente.nombre',
                buscar : '',
                buscarP : '',
                numero : 0,
                imagenMiniatura : '',
                empresa : {},
                setTimeoutBuscador : '',
                isSaving: false
            }
        },
        computed : {
            isActived: function(){
                return this.pagination.current_page;
            },
            isActivedServicio: function(){
                return this.paginationServicio.current_page;
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
            }
        },
        methods : {
            listarVenta(page, buscar, criterio){
                let me=this;
                var url='/ventaPago?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
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
            listarVentaBusquedaRapida(){
                let me=this;
                var url='/ventaPago?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
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
            listarVentaServicio(page, buscar, criterio){
                let me=this;
                var url='/ventaServicioPago?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                axios.get(url).then(function(response){
                    me.arrayVentaServicio=response.data.data;
                    me.paginationServicio={total:response.data.total, 
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
            listarVentaServicioBusquedaRapida(){
                let me=this;
                var url='/ventaServicioPago?page=' + 1 + '&buscar=' + me.buscar + '&criterio=' + me.criterio;
                axios.get(url).then(function(response){
                    me.arrayVentaServicio=response.data.data;
                    me.paginationServicio={total:response.data.total, 
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
            BuscandoVentaServicio(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarVentaServicioBusquedaRapida,350)
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
                })
                .catch(function(error){
                    console.log(error)
                });
            },
            async verVenta(data=[]){
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

                try {
                    const detailResponse = await axios.get('/venta/permiso/detalle_tienda1?id=' + data['id']);
                    me.arrayDetalle = detailResponse.data;
                    if (me.arrayDetalle.length) {
                        const companyResponse = await axios.get('/tienda?page=1&buscar=&criterio=nombre');
                        me.arrayEmpresa = companyResponse.data.data || [];
                        me.empresa = me.arrayEmpresa.find(item => item.id == me.arrayDetalle[0].id_tienda) || {};
                        me.datos.foto = me.empresa.foto || '';
                        me.datos.empresa_nombre = me.empresa.nombre || '';
                        me.datos.empresa_direccion = me.empresa.direccion || '';
                    }
                } catch (error) {
                    console.log(error);
                }
            },
            cargarPdf(id,foto,empresa_nombre) {
                axios.get('/venta/pdfVentasGeneral?id='
                 + id  + '&foto='+ foto
                 + '&empresa_nombre=' + empresa_nombre,{responseType: 'blob'})
                    .then(response => {
                        var blob = new Blob([response.data], {type: 'application/pdf'});
                        var downloadUrl = URL.createObjectURL(blob);
                        window.open(downloadUrl, '_blank');
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
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
                const amount = Number(me.datosPago.amortizacion || 0);
                const balance = Number((me.ultimoPago && me.ultimoPago.saldo) || me.datosPago.saldo || 0);
                if(amount <= 0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Ingrese un monto!'
                    });
                    return;
                }
                if (!me.datosPago.id_forma_pago) {
                    Swal.fire({ icon: 'error', title: 'Error...', text: 'Seleccione una forma de pago!' });
                    return;
                }
                if(amount > balance) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'Monto Mayor al Saldo!'
                        });
                        return;
                }
                me.isSaving = true;
                        axios.post('/c_x_cobrar/guardar',{
                            'fecha': me.datosPago.fecha,
                            'monto_total': Number((me.ultimoPago && me.ultimoPago.monto_total) || me.datosPago.monto_total).toFixed(2),
                            'amortizacion': amount.toFixed(2),
                            'saldo': balance,
                            'descripcion': me.datosPago.descripcion,
                            'id_pago': me.datosPago.id_pago,
                            'id_forma_pago': me.datosPago.id_forma_pago,
                        }).then(function(response){
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Pago registrado exitosamente',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            me.listarCXCobrar(me.datosPago.id_pago);
                            me.listarVenta(me.pagination.current_page || 1, me.buscar, me.criterio);
                            me.limpiarPago();
                            me.isSaving = false;
                        })
                        .catch(function(error){
                            me.isSaving = false;
                            console.log(error);
                            Swal.fire({ icon: 'error', title: 'No se pudo registrar el pago', text: 'Revise los datos e intente nuevamente.' });
                        });
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
            anularVenta(id,id_servicio){
                dangerConfirm.fire({
                    title: 'Esta seguro de Anular esta Venta??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/venta/anular_tienda1',{'id': id,'id_orden_servicio': id_servicio}).then(function (response) {
                        me.listarVenta(1,'', 'nombre');
                        me.listarVentaServicio(1, '', 'nombre');
                        Swal.fire(
                        'Habilitado!',
                        'Este venta se ha Anulado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
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
                    me.datosPago.fecha_final=me.arrayListaPagos.length ? me.arrayListaPagos[0].fecha_final : moment().format('YYYY-MM-DD');
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
                this.limpiarCXCobrar();
                this.listarPagos(venta.id);
                this.datosPago.id_pago = venta.id;
                this.listarCXCobrar(this.datosPago.id_pago);
                this.datosPago.monto_total = venta.total;
                this.datosPago.saldo = this.datosPago.monto_total;
                this.datosPago.amortizacion = this.datosPago.amortizacion;
            },
            cargarUltimoPago(){
                const array = this.CXCobrar;
                this.ultimoPago=array.length ? array[array.length-1] : {
                    monto_total: this.datosPago.monto_total,
                    amortizacion: 0,
                    saldo: this.datosPago.saldo,
                };
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
                    id_forma_pago: 2,
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
                axios.get('/venta/pdfVentasGeneral?id=' + id  + '&foto='+ foto,{responseType: 'blob'})
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
            this.listarVenta(1, this.buscar, this.criterio);
            this.listarVentaServicio(1, this.buscar, this.criterio);
            this.selectFormaP();
            //this.listarEmpresa(1, this.buscar, this.criterio);
             
        }
    }
</script>
<style scoped>
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

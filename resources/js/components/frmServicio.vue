<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                <div class="card">
                   <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">SERVICIOS</h3></div>
                    <template v-if="listado==10">
                        <div class="card-header">
                            <button type="button" @click="frmServicio()" class="btn btn-info text-white" style='margin-left: 1%'>
                                <i class="icon-plus"></i>&nbsp;Nuevo
                            </button>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-8">
                                &nbsp;&nbsp;<div class="input-group" style='width:96%;margin-left: 3.3%'>
                                    <select class="form-select col-md-3" v-model="criterio">
                                        <option value="nombre">Nombre</option>
                                    </select>
                                    &nbsp;&nbsp;&nbsp;
                                    <input type="text" v-model="buscar" @keyup.enter="listarVenta(1, buscar, criterio)" class="form-control" placeholder="Texto a buscar">
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
                                    <th scope="col" class="text-white">Usuario</th>
                                    <th scope="col" class="text-white">Descuento</th>
                                    <th scope="col" class="text-white">Total</th>
                                    <th scope="col" class="text-white">Estado</th>
                                    <th scope="col" class="text-white">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="venta in arrayVenta" :key="venta.id">
                                    <td v-text="venta.fecha"></td>
                                    <td v-text="venta.name"></td>
                                    <td v-text="venta.descuento"></td>
                                    <td v-text="venta.total"></td>
                                    <td v-text="venta.estado"></td>
                                    <td>
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Accion</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#" @click="verVenta(venta)"><i class="fa fa-eye text-success"></i> Ver detalle</a></li>
                                            <template v-if="venta.estado==1">
                                                <li><a class="dropdown-item" href="#" @click="desactivarCompra(venta.id)"><i class="fa fa-unlock text-success"></i> Desactivar</a></li>
                                            </template>
                                            <template v-else>
                                                <li><a class="dropdown-item" href="#" @click="anularCompra(venta.id)"><i class="fa fa-lock text-danger"></i> Anular</a></li>
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
                    </template>
                    <template v-if="listado==0">
                        <div class="card-body">
                            <div class="form-group row" style='margin-left: 1%'>   
                            <form class="row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1" class="form-label">Cliente</label>
                                    <div class="input-group mb-6">
                                        <input type="text" readonly class="form-control" placeholder="Buscar Clientes.."  v-model="datos.cliente" aria-label="Buscar Productos.." aria-describedby="button-addon2">
                                        &nbsp;&nbsp;&nbsp;
                                        <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalCliente"><i class="fa fa-search"></i> Agregar Clientes</button>
                                    </div>

                                </div>

                                <div class="col-md-6">
                                            <label for="exampleInputEmail1" class="form-label">Tecnicos</label>
                                            <div class="input-group mb-6">
                                                <input type="text" readonly class="form-control"  placeholder="Buscar Tecnicos.."  v-model="datos.personal" aria-label="Buscar Tecnicos.." aria-describedby="button-addon2">
                                                &nbsp;&nbsp;&nbsp;
                                                <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalPersonal"><i class="fa fa-search"></i> Agregar Tecnicos</button>
                                            </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="exampleInputPassword1" class="form-label">Fecha</label>
                                    <input type="date" class="form-control"  v-model="datos.fecha" disabled>  
                                </div>
                                <div class="col-md-6">
                                            <label for="exampleInputPassword1" class="form-label">Estado</label>
                                            <select class="form-select" v-model="datos.estado">
                                                    <option value="Recepcionado">Recepcionado</option>
                                                    <option value="Concluido">Concluido</option>
                                                </select>
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
                                <div class="col-md-6"> 
                                </div>
                                <div class="col-md-12">
                                    <label for="exampleInputPassword1" class="form-label">Descripcion</label>
                                    <textarea class="form-control" v-model="datos.descripcion" rows="2"></textarea>
                                </div>   
                                &nbsp;
                               
                                 <div class="col-md-12">
                                    <label>Productos<span style="color:red;" >(*Seleccione)</span></label>
                                    <button type="button" class="btn btn-info text-white position-relative" data-bs-toggle="modal" data-bs-target="#modalArticulo2"><i class="fa fa-search"></i> Agregar Productos</button>
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
                                                <!-- <input v-model="detalle.costo_venta" type="number" value="3" class="form-control"> -->
                                                
                                                <template v-if="datos.id_descuento == 1">
                                                        <input v-model="detalle.costo_unitario" type="number"  id="Unitario" class="form-control" min='0'>
                                                </template> 
                                                <template v-if="datos.id_descuento == 2">
                                                        <input v-model="detalle.costo_mayorista" type="number"  id="Mayorista" class="form-control" min='0'>
                                                </template>
                                                <template v-if="datos.id_descuento == 3">
                                                        <input v-model="detalle.costo_preferencial" type="number"  id="Preferencial" class="form-control" min='0'>
                                                </template> 
                                                <!-- <template v-else-if="">
                                                        <input v-model="detalle.costo_unitario" type="number"  id="Unitario" class="form-control">
                                                </template>  -->
                                                <!-- <template v-else-if="">

                                                </template> -->
                                                <!-- <template v-else>
                                                        <input v-model="detalle.costo_mayorista" type="number"  id="Mayorista" class="form-control">
                                                </template>  -->
                                                
                                            </td>
                                            <td>
                                                <input v-model="detalle.cantidad" type="number" value="3" class="form-control" min='1'>
                                                <div v-if="detalle.tipo_producto == 'Producto Venta'">
                                                    <span style="color:red;" v-show="detalle.cantidad>detalle.stock">Stock: {{detalle.stock}}</span>
                                                </div>
                                            </td>
                                            <td>
                                                
                                                <template v-if="datos.id_descuento == 1">
                                                        {{detalle.sub_total = detalle.costo_unitario*detalle.cantidad}}
                                                </template>   
                                                <template v-if="datos.id_descuento == 2">
                                                        {{detalle.sub_total = detalle.costo_mayorista*detalle.cantidad}}
                                                </template>
                                                <template v-if="datos.id_descuento == 3">
                                                        {{detalle.sub_total = detalle.costo_preferencial*detalle.cantidad}}
                                                </template>     
                                            
                                            </td>                                                                             
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="5" align="right"> <strong>Total:</strong> </td>
                                            <td>{{datos.total = calcularTotal.toFixed(2)}} bs</td> 
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="5" align="right"> <strong>Descuento:</strong> </td>
                                            <td><input v-model="datos.descuento" type="number" value="0" class="form-control" min='0'></td>
                                        </tr>
                                         <tr style="background-color: #CEECF5">
                                            <td colspan="5" align="right"> <strong>Sub Total:</strong> </td>
                                            <td>{{datos.sub_total = datos.total- datos.descuento}} bs</td>
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
                                <button class="btn btn-info btn-lg text-white" type="button" @click="guardarServicio()">Guardar</button>
                            </div>
                        </div>
                    </template>
                    
                    <template v-if="listado==2">
                        <div class="card-body">
                            <button type="button" class="btn btn-danger text-white" @click="volverVentaListado()"><i class="fa fa-reply-all"></i>&nbsp;Volver</button>
                            <div class="card-header text-center"><h3 class="mb-0">REGISTRO DE VENTA</h3></div>                            
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
                                <!-- <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Descuento</label>
                                    <input type="number" class="form-control" v-model="datos.descuento">  
                                </div> -->
                            
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
                                            <td v-text="detalle.costo_unitario"></td>
                                            <td v-text="detalle.cantidad"></td>
                                            <td v-text="detalle.sub_total"></td>                                                                             
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="4" align="right"> <strong>Total:</strong> </td>
                                            <td>{{datos.total = calcularTotal.toFixed(2)}} bs</td> 
                                        </tr>
                                        <tr style="background-color: #CEECF5">
                                            <td colspan="4" align="right"> <strong>Descuento:</strong> </td>
                                            <td v-text="datos.descuento"></td>
                                        </tr>
                                         <tr style="background-color: #CEECF5">
                                            <td colspan="4" align="right"> <strong>Sub Total:</strong> </td>
                                            <td>{{datos.sub_total = (datos.total- datos.descuento).toFixed(2)}} bs</td>
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

        <!--Modal Formulario Producto/Servicio-->
        <div class="modal fade" id="modalArticulo2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white" style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDA DE PRODUCTOS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Productos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Servicios</button>
                    </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="form-group row">
                            <div class="col-md-6">
                                &nbsp;&nbsp;<div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(buscarP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticulo(buscarP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>                      
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">Tienda</th>
                                    <th scope="col" class="text-white">Costo Unitario</th>
                                    <th scope="col" class="text-white">Costo Mayorista</th>
                                    <th scope="col" class="text-white">Costo Preferencial</th>
                                    <th scope="col" class="text-white">Stock</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tienda_articulo in arrayArticulo" :key="tienda_articulo.id">
                                    <td v-text="tienda_articulo.articulo"></td>
                                    <td v-text="tienda_articulo.tienda"></td>
                                    <td v-text="tienda_articulo.costo_unitario"></td>
                                    <td v-text="tienda_articulo.costo_mayorista"></td>
                                    <td v-text="tienda_articulo.costo_preferencial"></td>
                                    <td v-text="tienda_articulo.stock"></td>
                                    <td>
                                        <button type="button" @click="seleccionarTiendaArticulo2(tienda_articulo)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>   
                                    </td>                                 
                                </tr>
                            </tbody>
                        </table>
                        </div>


                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                        <div class="form-group row">
                            <div class="col-md-6">
                                &nbsp;&nbsp;<div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarArticulo2(buscarP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticulo2(buscarP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>                      
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">Tienda</th>
                                    <th scope="col" class="text-white">Costo Unitario</th>
                                    <th scope="col" class="text-white">Costo Mayorista</th>
                                    <th scope="col" class="text-white">Costo Preferencial</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tienda_articulo in arrayArticuloServicio" :key="tienda_articulo.id">
                                    <td v-text="tienda_articulo.articulo"></td>
                                    <td v-text="tienda_articulo.tienda"></td>
                                    <td v-text="tienda_articulo.costo_unitario"></td>
                                    <td v-text="tienda_articulo.costo_mayorista"></td>
                                    <td v-text="tienda_articulo.costo_preferencial"></td>
                                    <td>
                                        <button type="button" @click="seleccionarTiendaArticulo2(tienda_articulo)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>   
                                    </td>                                 
                                </tr>
                            </tbody>
                        </table>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                    </div>
     
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal" @click="cerrarProductos()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin modal Formulario Producto/Servicio-->

        <!--Modal Formulario Cliente-->
        <div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white"  style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDAS DE CLIENTES</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarCliente(buscarP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarCliente(buscarP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>                      
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">CI/NIT</th>
                                    <th scope="col" class="text-white">Telefono</th>
                                    <th scope="col" class="text-white">Direccion</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cliente in arrayTipoCliente" :key="cliente.id">
                                    <td v-text="cliente.nombre"></td>
                                    <td v-text="cliente.matricula"></td>
                                    <td v-text="cliente.telefono"></td>
                                    <td v-text="cliente.direccion"></td>
                                    <td>
                                        <button type="button"  data-dismiss="modal" @click="seleccionarCliente(cliente)" class="btn btn-success btn-sm" data-bs-dismiss="modal"><i class="fa fa-check text-white"></i></button> 

                                    </td>                                 
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin modal Formulario Cliente-->

        <!--Modal Formulario Personal-->
        <div class="modal fade" id="modalPersonal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white"  style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDAS DE TECNICOS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarPersonal(buscarP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarPersonal(buscarP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>                      
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">Telefono</th>
                                    <th scope="col" class="text-white">Direccion</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="personal in arrayPersonal" :key="personal.id">
                                    <td v-text="personal.nombre"></td>
                                    <td v-text="personal.telefono"></td>
                                    <td v-text="personal.direccion"></td>
                                    <td>
                                        <button type="button"  data-dismiss="modal" @click="seleccionarPersonal(personal)" class="btn btn-success btn-sm" data-bs-dismiss="modal"><i class="fa fa-check text-white"></i></button> 

                                    </td>                                 
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin modal Formulario Personal-->
    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    import moment from 'moment';
    export default {
        created() {
            this.datos.costo_pago = 1;
             this.datos.estado = 'Recepcionado';
        },
        data(){
            return {
                datos : {
                    id : 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_cliente : 0,
                    id_personal : 0,
                    cliente : '',
                    tipoPago : '',
                    formaPago : '',
                    costo_pago : '',
                    id_descuento: '',
                    personal:'',
                    estado:'Recepcionado',
                    descripcion:'',
                    tipo_producto:'',

                },
                          
                arrayVenta : [],
                arrayDetalle : [],
                arrayArticulo : [],
                arrayArticuloServicio : [],
                arrayCliente: [],
                arrayCostoPago: [{id:1,nombre:'Unitario'},{id:2,nombre:'Mayorista'},{id:3,nombre:'Preferencial'}],
                arrayTipoCliente: [],
                arrayPersonal: [],
                arrayPago: [],
                arrayForma: [],
                listado : 0,
                tipoAccion : 0,
                errorCompra : 0,
                errorMostrarMsjCompra : [],
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
                selectProducto : true,
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
            }
        },
        methods : {
            listarVenta(page, buscar, criterio){
                let me=this;
                var url='/venta?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
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
            cambiarPagina(page, buscar, criterio){
                let me=this;
                me.pagination.current_page=page;
                me.listarVenta(page, buscar, criterio);
            },
            listarArticulo(buscarP){
                let me = this;
                var url='/tienda/producto?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayArticulo= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarArticulo2(buscarP){
                let me = this;
                var url='/tienda/servicio?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayArticuloServicio= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarCliente(buscarP){
                let me = this;
                var url='/cliente/listarSinPaginate?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayTipoCliente= response.data;
                    console.log(me.arrayTipoCliente);
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarPersonal(buscarP){
                let me = this;
                var url='/personal/listarSinPaginate?buscar=' + buscarP;
                axios.get(url).then(function(response){
                    me.arrayPersonal= response.data;
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
                        text: 'Este producto ya se encuentra agregado!'
                    })
                }
                else{
                    me.arrayDetalle.push({
                        id_tienda_articulo : data['id'],
                        id_articulo : data['id_articulo'],
                        articulo : data['articulo'],
                        tienda : data['tienda'],
                        costo_unitario : data['costo_unitario'],
                        costo_mayorista : data['costo_mayorista'],
                        costo_preferencial : data['costo_preferencial'],
                        tipo_producto : data['tipo_producto'],
                        stock : data['stock'],
                        cantidad : 1,
                        sub_total : data['sub_total']
                    });
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Producto agregado...',
                        showConfirmButton: false,
                        timer: 500
                    });
                }
            },
            seleccionarTiendaArticulo2(data=[]){
                let me = this;
                if(me.encuentra(data['id_articulo'])){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Este producto ya se encuentra agregado!'
                    })
                }
                else{
                    me.arrayDetalle.push({
                        id_tienda_articulo : data['id'],
                        id_articulo : data['id_articulo'],
                        articulo : data['articulo'],
                        tienda : data['tienda'],
                        costo_unitario : data['costo_unitario'],
                        costo_mayorista : data['costo_mayorista'],
                        costo_preferencial : data['costo_preferencial'],
                        tipo_producto : data['tipo_producto'],
                        stock : data['stock'],
                        cantidad : 1,
                        sub_total : data['sub_total']
                    });
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Producto agregado...',
                        showConfirmButton: false,
                        timer: 500
                    });
                }
            },
            seleccionarCliente(data=[]){
                this.datos.id_cliente=data['id'];
                this.datos.cliente= data['nombre'];
                this.datos.id_descuento= data['descuento'];
                this.arrayCliente=[];
            },
            seleccionarPersonal(data=[]){
                this.datos.id_personal=data['id'];
                this.datos.personal= data['nombre'];
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
            selectCostoPago(){
                let me=this;
                var url='/articulo/selectPrecio';
                axios.get(url).then(function(response){
                    me.arrayCostoPago=response.data;
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
            verVenta(data=[]){
                let me = this;
                me.listado = 2;
                me.datos.id=data['id'];
                me.datos.cliente=data['cliente'];
                me.datos.fecha=data['fecha'];
                me.datos.descuento=data['descuento'];
                me.datos.tipoPago=data['tipoP'];
                me.datos.formaPago=data['formaP'];

                var url='/servicio/permiso/detalle?id=' + data['id'];
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            guardarServicio(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe Productos agregados!'
                    })
                }
                if(this.datos.cliente == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar un Cliente!'
                    })
                }
                if(this.datos.personal == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar un Personal!'
                    })
                }
                let me = this;
                axios.post('/servicio/guardar',{
                    'fecha': me.datos.fecha,
                    'sub_total': me.datos.sub_total,
                    'descuento': me.datos.descuento,
                    'total': me.datos.total,
                    'estado': me.datos.estado,
                    'descripcion': me.datos.descripcion,
                    'id_cliente': me.datos.id_cliente,
                    'id_personal': me.datos.id_personal,
                    'id_costo_pago': me.datos.costo_pago,
                    'detalle': me.arrayDetalle,
                    'tipo_producto': me.arrayDetalle[0].tipo_producto,
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
                    console.log(me.arrayDetalle);
                    me.limpiarDatosVenta();
                })
                .catch(function(error){
                    console.log(error);
                });
            },        
            validarCompra(){
                this.errorCompra = 0;
                this.errorMostrarMsjCompra = [];

                if(!this.datos.nombre) this.errorMostrarMsjCompra.push("El nombre del Compra no puede estar vacio ");
                if(this.errorMostrarMsjCompra.length) this.errorCompra=1;
                return this.errorCompra;
            },
            frmServicio(){
                this.listado = 1;
                this.selectCliente();
                this.selectTipoP();
                this.selectFormaP();        
            },
            anularCompra(id){
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Esta seguro de Anular esta Compra??',
                    text: "Puede revertir esta decision!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Si, Habilitar!',
                    cancelButtonText: 'No, cancelar!',
                    reverseButtons: true
                }).then((result) => {
                if (result.isConfirmed) {
                    let me = this;
                    axios.put('/compra/anular',{'id': id}).then(function (response) {
                        me.listarVenta(1,'', 'nombre');
                        swalWithBootstrapButtons.fire(
                        'Habilitado!',
                        'Este compra se ha Anulado.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                    });                    
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire(
                    'Cancelado',
                    'Este categoria no ha tenido cambios :)',
                    'error'
                    )
                }
                })   
            },
            limpiarDatosVenta(){
                this.datos = {
                    id : 0,
                    fecha : moment().format('YYYY-MM-DD'),
                    sub_total : 0,
                    descuento : 0,
                    total : 0,
                    id_cliente : 0,
                    id_personal : 0,
                    cliente : '',
                    tipoPago : '',
                    formaPago : '',
                    costo_pago : '',
                    id_descuento: '',
                    tipo_producto: '',
                    personal:'',
                    estado:'Recepcionado',
                    descripcion:'',
                    arrayArticulo:[],
                    
                }
            },
            cerrarProductos(){
                this.arrayArticulo = [];
                this.arrayArticuloServicio = [];
                this.buscarP = '';
            }
        },
        mounted() {
            //this.listarVenta(1, this.buscar, this.criterio);
            this.selectCliente();
            this.selectTipoP();
            this.selectFormaP();  
  
             
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
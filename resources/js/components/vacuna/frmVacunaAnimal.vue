<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <div class="col">
                &nbsp;
                    <div class="card">
                    <div class="card-header text-center text-white" style="background-color: #3399FF"><h3 class="mb-0">Vacuna</h3></div>
                        <!-- Listado de Ventas -->
                        <template v-if="listado==0">
                            <div class="card-body">
                                <div class="form-group row" style='margin-left: 1%'>   
                                <form class="row">
                                    <div class="col-md-6">
                                        <label for="exampleInputEmail1" class="form-label">Animal</label>
                                        <div class="input-group mb-6">
                                            <input type="text" class="form-control"  v-model="datos.animal" disabled>  
                                            &nbsp;&nbsp;&nbsp;
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    </div>&nbsp;
                                    <div class="col-md-12">
                                        <label>Vacuna<span style="color:red;" >(*Seleccione)</span></label>
                                        <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modalArticulo" @click="listarArticulo(buscarP)"  :disabled="isDisabledProducto"><i class="fa fa-search"></i> Agregar Vacunas</button>
                                    </div> 
                                </form>           
                                    <div class="col-md-12">
                                        <div v-show="errorPago" class="form-group row div-error">
                                            <div class="text-center text-error">
                                                <div v-for="error in errorMostrarMsjPago" :key="error" v-text="error">

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
                                                <th scope="col" class="text-white">Categoria</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="(detalle,index) in arrayDetalle" :key="detalle.id">
                                                <td>
                                                    <button @click="eliminarDetalle(index)" type="button" class="btn btn-danger btn-sm"><i class="icon-trash text-white"></i></button>
                                                </td>
                                                <td v-text="detalle.articulo"></td>                              
                                                <td v-text="detalle.categoria"></td>                              
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
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end" style='width:96%;margin-left: 2.2%'>
                                    <button class="btn btn-danger me-md-2 text-white" type="button" @click="volverVentaMenu()">Cancelar</button>
                                    <button class="btn btn-info btn-lg text-white" type="button" @click="guardarVenta()">Guardar</button>
                                </div>
                            </div>
                        </template>
                        <!-- Fin Listado de Ventas -->
                    </div>
                </div>
            </div>
        <!-- </div>   -->
        <!--Modal Formulario Producto-->
        <div class="modal fade" id="modalArticulo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header btn btn-info text-white" style="height:45px;">
                        <h5 class="modal-title ">BUSQUEDA DE PRODUCTOS</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- nuevo -->
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <div class="col-md-3">
                                            <select class="form-select col-md-3" v-model="datos.tipo" @change="cambiarVacuna()">
                                                <option value="0">Vacunas</option>
                                                <option value="1">Antiparasitario</option>                                  
                                            </select>
                                    </div>&nbsp;&nbsp;
                                    <select class="form-select col-md-3" v-model="criterioP">
                                        <option value="articulo.nombre_comercial">Producto</option>   
                                        <option value="categoria.nombre">Categoria</option>
                                        <!-- <option value="articulo.marca">Marca</option>                                     -->
                                    </select>&nbsp;&nbsp;&nbsp;
                                    <template v-if="datos.tipo == '0'">
                                    <input type="text" v-model="buscarP" @keyup.enter="listarArticulo(buscarP, criterioP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticulo(buscarP, criterioP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                    </template>
                                    <template v-else>
                                    <input type="text" v-model="buscarP" @keyup.enter="listarArticulo2(buscarP, criterioP)" class="form-control" placeholder="Texto a buscar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button type="submit" @click="listarArticulo2(buscarP, criterioP)" class="btn btn-info text-white"><i class="fa fa-search"></i> Buscar</button>
                                    </template>
                                </div>  
                            </div>                   
                        </div>&nbsp;
                       <!-- fin -->
                        <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead style="background-color: #46546C">
                                <tr>
                                    <th scope="col" class="text-white">Categoria</th>                      
                                    <th scope="col" class="text-white">Nombre</th>
                                    <th scope="col" class="text-white">Tienda</th>
                                    <th scope="col" class="text-white">Precio Venta</th>
                                    <th scope="col" class="text-white">stock</th>
                                    <th scope="col" class="text-white">Opción</th>
                                </tr>
                            </thead>
                            <tbody v-if="arrayArticulo.length">
                                <tr v-for="tienda_articulo in arrayArticulo" :key="tienda_articulo.id">
                                    <td v-text="tienda_articulo.categoria"></td>
                                    <td v-text="tienda_articulo.articulo"></td>
                                    <td v-text="tienda_articulo.tienda"></td>
                                    <td v-text="tienda_articulo.costo_unitario"></td>
                                    <td v-text="tienda_articulo.stock"></td>
                                    <td>
                                        <button type="button" @click="seleccionarTiendaArticulo(tienda_articulo)" class="btn btn-success btn-sm"><i class="fa fa-check text-white"></i></button>   
                                    </td>                                 
                                </tr>
                            </tbody>
                            <tbody v-else>
                                <tr>
                                    <td colspan="9">No hay Productos agregados</td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal" @click="limpiarArticulo()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin moda Formulario Producto-->

    </main>
</template>

<script>
    import Swal from 'sweetalert2';
    import moment from 'moment';
    export default {
        // props: [
        //     'id_animal',
        //     'animal'
        // ],
        props: {
            selectAnimal2: {
                type: Object,
                required: true,
                id_animal: 0,
                animal: '',
            },
        },
        created() {
            this.datos.id_animal=this.selectAnimal2.id_animal;
            this.datos.animal=this.selectAnimal2.animal;
        },
        data(){
            return {
                datos : {
                    id : 0,
                    id_animal : 0,
                    animal : '',
                    tipo : '0',
                },
                arrayDetalle : [],
                arrayArticulo : [],
                arrayAnimal: [],
                listado : 0,
                tipoAccion : 0,
                errorPago : 0,
                errorMostrarMsjPago : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterioP : 'articulo.nombre_comercial',
                buscar : '',
                buscarP : '',
                isDisabled: true,
                isDisabledProducto: false,
                setTimeoutBuscador: '',
                isVisible: false,
                listadoVenta: 0,
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
            cambiarVacuna()
            {
                if(this.datos.tipo == '0')
                {
                    this.listarArticulo('', 'producto.nombre'); 
                }
                else
                {
                    this.listarArticulo2('', 'producto.nombre'); 
                }
            },
            cargarDetalle()
            {
                let me = this;
                 var url='/vacuna/permiso/detalle?id=' + me.selectAnimal2.id_animal;
                axios.get(url).then(function(response){
                    me.arrayDetalle= response.data;

                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarArticulo(buscarP, criterioP){
                let me = this;
                me.buscarCliente= '';
                me.isVisible = false;
                var url='/tienda/listarSinPaginateVacuna?buscar=' + buscarP + '&criterio=' + criterioP;
                axios.get(url).then(function(response){
                    me.arrayArticulo= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            listarArticuloBusquedaRapida(){
                let me = this;
                me.buscarCliente= '';
                me.isVisible = false;
                var url='/tienda/listarSinPaginateVacuna?buscar=' + me.buscarP;
                axios.get(url).then(function(response){
                    me.arrayArticulo= response.data;
                })
                .catch(function(error){
                    console.log(error);
                });
            },
            BuscandoProducto(){
                let me = this;
                clearTimeout(me.setTimeoutBuscador)
                me.setTimeoutBuscador = setTimeout(me.listarArticuloBusquedaRapida,350)
            },
            listarArticulo2(buscarP, criterioP){
                let me = this;
                me.buscarCliente= '';
                me.isVisible = false;
                var url='/tienda/listarSinPaginateVacuna2?buscar=' + buscarP + '&criterio=' + criterioP;
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
                    if(this.arrayDetalle[i].id_articulo==id ){
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
                        costo_compra : data['costo_compra'],
                        marca : data['marca'],
                        id_categoria : data['id_categoria'],
                        categoria : data['categoria'],
                        stock : data['stock'],
                        cantidad : 1,
                        sub_total : data['sub_total'],
                        producto_venta: 'Venta Producto'
                    });
                    me.datos.estado= 'Entregado';
                    me.datos.tipo_venta= 'Venta Directa'
                    me.isDisabled = false;
                    me.isDisabledOrden = true;
                    me.isDisabledPaquete = true;
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Producto agregado...',
                        showConfirmButton: false,
                        timer: 500
                    });
                }
            },
            volverVentaListado(){
                let me = this;
                me.arrayDetalle = [];
                me.arrayArticulo = [];
                me.datos.cliente = '';
                me.datos.estado = '';
                me.datos.id_tipo_pago = 1,
                me.datos.id_forma_pago = 2,
                me.buscarP = '';
                me.listado = 2;
                me.isDisabledCliente = false;
                me.isDisabledProducto = false;
                me.isDisabledPaquete = false;
                me.isDisabledOrden = false;
                me.buscar = '';
                this.$emit('cerrarVentaTienda', this.listadoVenta);
            },
            volverVentaMenu(){
                //this.listado = 0;
                this.$emit('cerrarVentaTienda', this.listadoVenta);
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
            guardarVenta(){
                if(this.arrayDetalle.length<=0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'No Existe Productos agregados!'
                    })
                }
                if(this.datos.animal == ''){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'Debe Agregar el nombre del animal!'
                    })
                }
                     else {
                            let me = this;
                            axios.post('/vacuna/guardar',{

                                'id_animal': me.datos.id_animal,
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
                                me.limpiarDatosVenta();
                            })
                            .catch(function(error){
                                console.log(error);
                            });
                        }
                        
                    
                
                
            },        
            validarCompra(){
                this.errorPago = 0;
                this.errorMostrarMsjPago = [];

                if(!this.datos.nombre) this.errorMostrarMsjPago.push("El nombre del Pago no puede estar vacio ");
                if(this.errorMostrarMsjPago.length) this.errorPago=1;
                return this.errorPago;
            },
            limpiarDatosVenta(){
                let me = this;
                me.datos = {
                    id : 0,
                    id_animal : 0,
                    animal : '',
   
                }
                me.arrayDetalle = [];
                me.isDisabledOrden = false;
                me.isDisabledProducto=false;
                me.buscarP = '';
                me.buscar = '';
                me.buscarCliente= '';
                me.isDisabled=false;
            },
            limpiarArticulo(){
                this.arrayArticulo = [];
                this.buscarP = '';
                this.buscar = '';
                this.arrayDetalle.forEach(item => item.saldoStock = 0);
            },
            numericValidate(event) {
                const keyValidate = new RegExp('^[0-9]*$');
                const keysCaracter = ['Delete', 'Backspace', 'ArrowLeft', 'ArrowRight', 'KeyX', 'KeyC', 'KeyV', 'Home', 'End', 'Tab'];
                if (keysCaracter.includes(event.code)) {
                    switch (event.code) {
                    case 'KeyX':
                        if (!event.ctrlKey) {
                        event.preventDefault();
                        }
                        break;
                    case 'KeyC':
                        if (!event.ctrlKey) {
                        event.preventDefault();
                        }
                        break;
                    case 'KeyV':
                        if (!event.ctrlKey) {
                        event.preventDefault();
                        }
                        break;
                    default:
                        break;
                    }
                    return;
                }
                if (!keyValidate.test(event.key)) {
                    event.preventDefault();
                }
            }

        },
        mounted() {
            this.selectAnimal();
            this.cargarDetalle();
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
</style>
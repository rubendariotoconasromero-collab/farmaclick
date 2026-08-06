<template>
    <main class="main">
        <!-- <div class="container"> -->
            <div class="row">
                <template v-if="control_vacuna==0">
                <div class="col">
                &nbsp;
                    <div class="card">
                    <!-- <div class="card-header text-center text-white"><h3 class="mb-0">CLIENTE RELACIONADOR</h3></div> -->
                        <!-- Listado de Ventas -->
                        <template v-if="listado==0">
                            <div class="card-body">
                                <h2 class="text-center">SELECCIONE LA VACUNA : </h2>
                                <div class="form-group row" style='margin-left: 1%'>   
                                <form class="row">
                                    <div class="row row-cols-1 row-cols-md-4 g-4">
                                    <div class="col">
                                    </div>
                                    <div class="col">
                                        <div class="card">
                                        <img src="img/menu/control.png" class="center"  alt="" height="180" @click="seleccionar_menu()">
                                        <div class="card-body center">
                                            <h5 class="card-title text-center">CONTROL VACUNA</h5>
                                            <!-- <div class="text-center"><button  type="button" class="btn btn-outline-info btn-sm center">Ingresar</button></div> -->

                                            <!-- <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p> -->
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card">
                                        <img src="img/menu/antiparasitario.jpg" class="center"  alt=""  height="180" @click="seleccionar_menu2()">
                                        <div class="card-body">
                                                        <!-- <img src="img/menu/almacen.png" class="center" width="300px" height="150px"></img> -->
                                            <h5 class="card-title text-center">ANTIPARASITARIO</h5>
                                            <!-- <div class="text-center"><button  type="button" class="btn btn-outline-info btn-sm center">Ingresar</button></div> -->

                                            <!-- <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p> -->
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                    </div>


                                    </div>

                                </form>   
   
                                </div>
                                <br><br><br><br>
                            </div>
                        </template>
                        <!-- Fin Listado de Ventas -->
                    </div>
                
                </div>
                </template>
                <template v-if="control_vacuna==1">
                     <frm-vacuna-control-vacuna :selectMenu="selectMenu" @cerrarVentaTienda="listadoVenta"></frm-vacuna-control-vacuna>
                </template>
                <template v-if="control_vacuna==2">
                     <frm-antiparasitario  :selectMenu="selectMenu" @cerrarVentaTienda="listadoVenta"></frm-antiparasitario>
                </template>
            </div>
        <!-- </div>   -->
                <!-- <template v-if="listado==1">
                    <frm-menu-vacuna :selectMenu="selectMenu" @cerrarVentaTienda="listadoVenta"></frm-menu-vacuna>
                </template>   -->
    </main>
</template>

<script>
    import Swal from '../../utils/appSwal';
    import moment from 'moment';
    export default {
        created() {
            this.datos.costo_pago = 1;
        },
        data(){
            return {
                datos : {

                }, 
                selectMenu:{
                    // id_animal:0,
                    // animal:'',
                },     
                control_vacuna:0,
                listado:0,
             
            }
        },
        computed : {

        },
        methods : {
            listadoVenta(listado){
                let me=this;
                me.control_vacuna = listado;
            },
            seleccionar_menu()
            {
              this.control_vacuna=1;
            },
            seleccionar_menu2()
            {
              this.control_vacuna=2;
            },

        },
        mounted() {

           // this.verificarCaja();
        }
    }
</script>

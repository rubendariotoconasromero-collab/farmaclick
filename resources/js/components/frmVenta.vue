<template>
    <main class="main">
        <sales-entry-workspace
            :datos="datos"
            :datos-pago="datosPago"
            :details="arrayDetalle"
            :products="arrayArticulo"
            :customers="arrayTipoCliente"
            :payment-types="arrayPago"
            :payment-forms="arrayFormaContado"
            :catalog-search="buscarProducto"
            :calculated-subtotal="subtotalVenta"
            :disable-customer="isSaving"
            :disable-products="isSaving"
            :saving="isSaving"
            @update:catalogSearch="buscarProducto = $event"
            @search-customers="listarClientes"
            @search-products="listarProductos"
            @select-customer="seleccionarCliente"
            @select-product="seleccionarProducto"
            @remove-line="eliminarDetalle"
            @clear-customer-search="limpiarBusquedaClientes"
            @clear-product-search="limpiarBusquedaProductos"
            @save="guardarVenta"
            @cancel="limpiarVenta"
        />
    </main>
</template>

<script>
import Swal from 'sweetalert2';
import moment from 'moment';

const crearDatosVenta = () => ({
    fecha: moment().format('YYYY-MM-DD'),
    descuento: 0,
    id_tipo_pago: 1,
    id_forma_pago: 2,
    id_cliente: 0,
    cliente: '',
    id_descuento: '',
});

const crearDatosPago = () => ({
    fecha_final: moment().format('YYYY-MM-DD'),
    descripcion: '',
});

export default {
    data() {
        return {
            datos: crearDatosVenta(),
            datosPago: crearDatosPago(),
            arrayDetalle: [],
            arrayArticulo: [],
            arrayTipoCliente: [],
            arrayPago: [],
            arrayForma: [],
            buscarProducto: '',
            isSaving: false,
        };
    },
    computed: {
        arrayFormaContado() {
            return this.arrayForma.filter(forma => Number(forma.id) !== 1);
        },
        campoPrecio() {
            return ({
                1: 'costo_unitario',
                2: 'costo_mayorista',
                3: 'costo_preferencial',
            })[Number(this.datos.id_descuento)] || 'costo_unitario';
        },
        subtotalVenta() {
            return this.arrayDetalle.reduce((total, detalle) => {
                const precio = Number(detalle[this.campoPrecio] || 0);
                const cantidad = Number(detalle.cantidad || 0);
                return total + (precio * cantidad);
            }, 0);
        },
        formaPagoCredito() {
            const cuentaPorCobrar = this.arrayForma.find(forma => Number(forma.id) === 1);
            return cuentaPorCobrar ? Number(cuentaPorCobrar.id) : 1;
        },
    },
    methods: {
        mostrarError(texto) {
            return Swal.fire({
                icon: 'error',
                title: 'No se puede registrar la venta',
                text: texto,
            });
        },
        async cargarCatalogos() {
            try {
                const [tiposPago, formasPago] = await Promise.all([
                    axios.get('/tipoPago/selectTipoP'),
                    axios.get('/formaPago/selectFormaP'),
                ]);
                this.arrayPago = tiposPago.data;
                this.arrayForma = formasPago.data;

                if (!this.arrayFormaContado.some(forma => Number(forma.id) === Number(this.datos.id_forma_pago))) {
                    this.datos.id_forma_pago = this.arrayFormaContado.length
                        ? Number(this.arrayFormaContado[0].id)
                        : 0;
                }
            } catch (error) {
                console.error(error);
                this.mostrarError('No fue posible cargar los tipos y formas de pago.');
            }
        },
        async listarProductos() {
            try {
                const response = await axios.get('/tienda/listarSinPaginate2', {
                    params: { buscar: this.buscarProducto },
                });
                this.arrayArticulo = response.data;
            } catch (error) {
                console.error(error);
                this.mostrarError('No fue posible consultar el inventario.');
            }
        },
        async listarClientes() {
            try {
                const response = await axios.get('/cliente/listarSinPaginate', {
                    params: { buscar: this.buscarProducto },
                });
                this.arrayTipoCliente = response.data;
            } catch (error) {
                console.error(error);
                this.mostrarError('No fue posible consultar los clientes.');
            }
        },
        seleccionarCliente(cliente) {
            const tarifa = [1, 2, 3].includes(Number(cliente.descuento))
                ? Number(cliente.descuento)
                : 1;
            this.datos.id_cliente = Number(cliente.id);
            this.datos.cliente = cliente.nombre;
            this.datos.id_descuento = tarifa;
            this.limpiarBusquedaClientes();
        },
        seleccionarProducto(producto) {
            const yaAgregado = this.arrayDetalle.some(
                detalle => Number(detalle.id_articulo) === Number(producto.id_articulo),
            );

            if (yaAgregado) {
                this.mostrarError('Este producto ya se encuentra agregado.');
                return;
            }

            this.arrayDetalle.push({
                id_tienda_articulo: Number(producto.id),
                id_articulo: Number(producto.id_articulo),
                articulo: producto.articulo,
                tienda: producto.tienda,
                costo_unitario: Number(producto.costo_unitario || 0),
                costo_mayorista: Number(producto.costo_mayorista || 0),
                costo_preferencial: Number(producto.costo_preferencial || 0),
                stock: Number(producto.stock || 0),
                cantidad: 1,
                sub_total: 0,
            });
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Producto agregado',
                showConfirmButton: false,
                timer: 500,
            });
        },
        eliminarDetalle(index) {
            this.arrayDetalle.splice(index, 1);
        },
        limpiarBusquedaClientes() {
            this.arrayTipoCliente = [];
            this.buscarProducto = '';
        },
        limpiarBusquedaProductos() {
            this.arrayArticulo = [];
            this.buscarProducto = '';
        },
        limpiarVenta() {
            this.datos = crearDatosVenta();
            this.datosPago = crearDatosPago();
            this.arrayDetalle = [];
            this.arrayArticulo = [];
            this.arrayTipoCliente = [];
            this.buscarProducto = '';

            if (this.arrayFormaContado.length) {
                this.datos.id_forma_pago = Number(this.arrayFormaContado[0].id);
            }
        },
        detallesNormalizados() {
            return this.arrayDetalle.map(detalle => {
                const cantidad = Number(detalle.cantidad);
                const costoUnitario = Number(detalle.costo_unitario || 0);
                const costoMayorista = Number(detalle.costo_mayorista || 0);
                const costoPreferencial = Number(detalle.costo_preferencial || 0);
                const precioSeleccionado = Number(detalle[this.campoPrecio] || 0);

                return {
                    ...detalle,
                    cantidad,
                    costo_unitario: costoUnitario,
                    costo_mayorista: costoMayorista,
                    costo_preferencial: costoPreferencial,
                    sub_total: Number((precioSeleccionado * cantidad).toFixed(2)),
                };
            });
        },
        validarVenta(detalles, subtotal, descuento) {
            if (!this.datos.fecha) return 'Seleccione la fecha de la venta.';
            if (!this.datos.id_cliente || !this.datos.cliente) return 'Debe seleccionar un cliente.';
            if (![1, 2].includes(Number(this.datos.id_tipo_pago))) return 'Seleccione un tipo de pago válido.';
            if (Number(this.datos.id_tipo_pago) === 1 && !Number(this.datos.id_forma_pago)) {
                return 'Seleccione una forma de pago.';
            }
            if (Number(this.datos.id_tipo_pago) === 2 && !this.datosPago.fecha_final) {
                return 'Seleccione la fecha de vencimiento del crédito.';
            }
            if (Number(this.datos.id_tipo_pago) === 2 && this.datosPago.fecha_final < this.datos.fecha) {
                return 'El vencimiento del crédito no puede ser anterior a la fecha de venta.';
            }
            if (!detalles.length) return 'Debe agregar al menos un producto.';
            if (!Number.isFinite(descuento) || descuento < 0) return 'El descuento no es válido.';
            if (descuento > subtotal) return 'El descuento no puede ser mayor al subtotal.';

            const detalleInvalido = detalles.find(detalle => (
                !Number.isInteger(detalle.cantidad)
                || detalle.cantidad <= 0
                || !Number.isFinite(Number(detalle.stock))
                || detalle.cantidad > Number(detalle.stock)
                || !Number.isFinite(detalle.sub_total)
                || detalle.sub_total <= 0
            ));

            if (detalleInvalido) {
                return `Revise la cantidad, precio y stock de ${detalleInvalido.articulo || 'los productos'}.`;
            }
            if (!Number.isFinite(subtotal) || subtotal <= 0) return 'El subtotal de la venta debe ser mayor a cero.';

            return '';
        },
        async guardarVenta() {
            if (this.isSaving) return;

            const detalles = this.detallesNormalizados();
            const subtotal = Number(detalles.reduce((total, detalle) => total + detalle.sub_total, 0).toFixed(2));
            const descuento = Number(Number(this.datos.descuento || 0).toFixed(2));
            const total = Number((subtotal - descuento).toFixed(2));
            const errorValidacion = this.validarVenta(detalles, subtotal, descuento);

            if (errorValidacion) {
                this.mostrarError(errorValidacion);
                return;
            }

            this.datos.descuento = descuento;
            this.arrayDetalle = detalles;
            this.isSaving = true;

            try {
                await axios.post('/venta/guardar', {
                    fecha: this.datos.fecha,
                    fecha_final: this.datosPago.fecha_final,
                    sub_total: subtotal,
                    descuento,
                    total,
                    estado: 'Entregado',
                    id_cliente: this.datos.id_cliente,
                    id_tipo_pago: Number(this.datos.id_tipo_pago),
                    id_forma_pago: Number(this.datos.id_tipo_pago) === 2
                        ? this.formaPagoCredito
                        : Number(this.datos.id_forma_pago),
                    id_costo_pago: Number(this.datos.id_descuento || 1),
                    detalle: detalles,
                    monto_total: total,
                    descripcion_pago: this.datosPago.descripcion,
                    saldo: total,
                    tipo_venta: 'Venta Directa',
                });

                await Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Venta registrada exitosamente',
                    showConfirmButton: false,
                    timer: 1500,
                });
                this.limpiarVenta();
            } catch (error) {
                console.error(error);
                this.mostrarError('Revise los datos e intente nuevamente.');
            } finally {
                this.isSaving = false;
            }
        },
    },
    mounted() {
        this.cargarCatalogos();
    },
};
</script>

import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const routeComponent = {
    render(createElement) {
        return createElement();
    },
};

const screens = [
    { path: '/', name: 'inicio', menu: 0, title: 'Datos gráficos' },
    { path: '/dashboard', name: 'dashboard', menu: 0, title: 'Datos gráficos' },
    { path: '/caja/arqueo', name: 'arqueo-caja', menu: 26, title: 'Arqueo de caja' },
    { path: '/compras/nueva', name: 'compra-nueva', menu: 10, title: 'Nueva compra' },
    { path: '/compras/historial', name: 'compras-historial', menu: 17, title: 'Historial de compras' },
    { path: '/compras/pagos', name: 'compras-pagos', menu: 70, title: 'Pagos de compras' },
    { path: '/ventas/nueva', name: 'ventas-nueva', menu: 33, title: 'Nueva venta' },
    { path: '/ventas/historial', name: 'ventas-historial', menu: 34, title: 'Historial de ventas' },
    { path: '/ventas/pagos', name: 'ventas-pagos', menu: 37, title: 'Pagos de ventas' },
    { path: '/ventas/historial-arqueos', name: 'historial-arqueos', menu: 27, title: 'Historial de arqueos' },
    { path: '/ventas/clientes', name: 'ventas-clientes', menu: 91, title: 'Ventas por cliente' },
    { path: '/ventas/productos', name: 'ventas-productos', menu: 92, title: 'Ventas por producto' },
    { path: '/almacen/inventario', name: 'inventario', menu: 9, title: 'Inventario' },
    { path: '/almacen/productos', name: 'productos', menu: 8, title: 'Productos' },
    { path: '/almacen/categorias', name: 'categorias', menu: 7, title: 'Categorías' },
    { path: '/almacen/ajustes', name: 'ajustes', menu: 14, title: 'Ajustes' },
    { path: '/almacen/presentaciones', name: 'presentaciones', menu: 50, title: 'Presentaciones' },
    { path: '/almacen/lineas', name: 'lineas', menu: 90, title: 'Líneas' },
    { path: '/almacen/lotes', name: 'lotes', menu: 83, title: 'Lotes' },
    { path: '/gastos/motivos', name: 'motivos-gasto', menu: 11, title: 'Motivos de gasto' },
    { path: '/gastos', name: 'gastos', menu: 12, title: 'Gastos' },
    { path: '/datos/clientes', name: 'clientes', menu: 1, title: 'Clientes' },
    { path: '/datos/laboratorios', name: 'laboratorios', menu: 3, title: 'Laboratorios' },
    { path: '/datos/personal', name: 'personal', menu: 2, title: 'Personal' },
    { path: '/datos/cargos', name: 'cargos', menu: 4, title: 'Cargos' },
    { path: '/datos/mi-empresa', name: 'mi-empresa', menu: 20, title: 'Mi empresa' },
    { path: '/usuarios/grupos', name: 'grupos-usuarios', menu: 22, title: 'Grupos de usuarios' },
    { path: '/usuarios', name: 'usuarios', menu: 21, title: 'Usuarios' },
    { path: '/usuarios/permisos', name: 'permisos', menu: 6, title: 'Permisos' },
    { path: '/reportes', name: 'reportes', menu: 28, title: 'Reportes' },
];

const routes = screens.map(screen => ({
    path: screen.path,
    name: screen.name,
    component: routeComponent,
    meta: {
        menu: screen.menu,
        title: screen.title,
    },
}));

routes.push({
    path: '*',
    redirect: '/',
});

const mainPathIndex = window.location.pathname.indexOf('/main');
const applicationBase = mainPathIndex >= 0
    ? window.location.pathname.substring(0, mainPathIndex)
    : '';

const router = new VueRouter({
    mode: 'history',
    base: `${applicationBase}/main`,
    routes,
    scrollBehavior() {
        return { x: 0, y: 0 };
    },
});

router.beforeEach((to, from, next) => {
    if (to.name === 'inicio') {
        next();
        return;
    }

    const permittedLink = document.querySelector(
        `#sidebar [data-route-name="${to.name || ''}"]`
    );

    next(permittedLink ? undefined : { name: 'inicio' });
});

export default router;

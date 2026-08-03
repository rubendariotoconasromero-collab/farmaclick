<template>
    <main class="main">
        <warehouse-inventory-workspace
            :product-rows="productRows"
            :lot-rows="lotRows"
            :details="lotDetails"
            :data="selectedProduct"
            :product-pagination="productPagination"
            :lot-pagination="lotPagination"
            :product-pages="productPages"
            :lot-pages="lotPages"
            :product-search="productSearch"
            :lot-search="lotSearch"
            :criterion="criterion"
            :view="view"
            :loading="initialLoading"
            :table-loading="tableLoading"
            @update:product-search="productSearch = $event"
            @update:lot-search="lotSearch = $event"
            @update:criterion="criterion = $event"
            @search-products="loadProducts(1)"
            @search-lots="loadLots(1)"
            @product-page="loadProducts"
            @lot-page="loadLots"
            @activate-lots="ensureLotsLoaded"
            @view-lots="openLotDetail"
            @back="closeLotDetail"
            @remove-lot="removeLot"
        />
    </main>
</template>

<script>
import moment from 'moment';
import Swal, { dangerConfirm } from '../utils/appSwal';

const emptyPagination = () => ({
    total: 0,
    current_page: 1,
    per_page: 0,
    last_page: 1,
    from: 0,
    to: 0,
});

export default {
    name: 'FrmInventario',
    data() {
        return {
            productRows: [],
            lotRows: [],
            lotDetails: [],
            selectedProduct: {
                id: 0,
                nombre: '',
                stock: 0,
                alertDate: moment().add(30, 'days').format('YYYY-MM-DD'),
            },
            productPagination: emptyPagination(),
            lotPagination: emptyPagination(),
            productSearch: '',
            lotSearch: '',
            criterion: 'articulo.nombre_comercial',
            view: 'list',
            initialLoading: true,
            tableLoading: false,
            lotsLoaded: false,
            productRequestSequence: 0,
            lotRequestSequence: 0,
            detailRequestSequence: 0,
            offset: 3,
        };
    },
    computed: {
        productPages() {
            return this.paginationPages(this.productPagination);
        },
        lotPages() {
            return this.paginationPages(this.lotPagination);
        },
    },
    methods: {
        paginationPages(pagination) {
            if (!pagination.to || pagination.last_page <= 1) return [];
            let from = Math.max(1, pagination.current_page - this.offset);
            const to = Math.min(pagination.last_page, from + (this.offset * 2));
            const pages = [];
            while (from <= to) pages.push(from++);
            return pages;
        },
        paginationFrom(data) {
            return {
                total: Number(data.total || 0),
                current_page: Number(data.current_page || 1),
                per_page: Number(data.per_page || 0),
                last_page: Number(data.last_page || 1),
                from: Number(data.from || 0),
                to: Number(data.to || 0),
            };
        },
        async loadProducts(page = 1) {
            const sequence = ++this.productRequestSequence;
            this.tableLoading = true;
            try {
                const { data } = await axios.get('/tienda/inventario', {
                    params: { page, buscar: this.productSearch, criterio: this.criterion },
                });
                if (sequence !== this.productRequestSequence) return;
                this.productRows = Array.isArray(data.data) ? data.data : [];
                this.productPagination = this.paginationFrom(data);
            } catch (error) {
                if (sequence === this.productRequestSequence) {
                    this.productRows = [];
                    this.productPagination = emptyPagination();
                    this.showLoadError('No fue posible cargar las existencias por producto.');
                }
                console.error(error);
            } finally {
                if (sequence === this.productRequestSequence) this.tableLoading = false;
            }
        },
        async loadLots(page = 1) {
            const sequence = ++this.lotRequestSequence;
            this.tableLoading = true;
            try {
                const { data } = await axios.get('/tienda/listarSinPaginateInventario', {
                    params: { page, buscar: this.lotSearch, criterio: this.criterion },
                });
                if (sequence !== this.lotRequestSequence) return;
                this.lotRows = Array.isArray(data.data) ? data.data : [];
                this.lotPagination = this.paginationFrom(data);
                this.lotsLoaded = true;
            } catch (error) {
                if (sequence === this.lotRequestSequence) {
                    this.lotRows = [];
                    this.lotPagination = emptyPagination();
                    this.showLoadError('No fue posible cargar el inventario por lote.');
                }
                console.error(error);
            } finally {
                if (sequence === this.lotRequestSequence) this.tableLoading = false;
            }
        },
        ensureLotsLoaded() {
            if (!this.lotsLoaded) this.loadLots(1);
        },
        async openLotDetail(product) {
            this.selectedProduct = {
                id: Number(product.id_tienda_articulo),
                nombre: product.nombre_comercial || '',
                stock: Number(product.stock || 0),
                alertDate: moment().add(30, 'days').format('YYYY-MM-DD'),
            };
            this.view = 'detail';
            await this.loadLotDetails();
        },
        closeLotDetail() {
            this.detailRequestSequence += 1;
            this.view = 'list';
            this.lotDetails = [];
        },
        async loadLotDetails() {
            if (!this.selectedProduct.id) return;
            const sequence = ++this.detailRequestSequence;
            this.tableLoading = true;
            try {
                const { data } = await axios.get('/articulo/detalleLote', {
                    params: { buscar: this.selectedProduct.id },
                });
                if (sequence !== this.detailRequestSequence) return;
                this.lotDetails = (Array.isArray(data) ? data : []).map(lot => ({
                    ...lot,
                    v_fecha: Boolean(lot.fecha_vecimiento && lot.fecha_vecimiento <= this.selectedProduct.alertDate),
                }));
            } catch (error) {
                if (sequence === this.detailRequestSequence) {
                    this.lotDetails = [];
                    this.showLoadError('No fue posible cargar los lotes del producto.');
                }
                console.error(error);
            } finally {
                if (sequence === this.detailRequestSequence) this.tableLoading = false;
            }
        },
        async removeLot(id) {
            const confirmation = await dangerConfirm.fire({
                title: '¿Eliminar este lote?',
                text: 'Esta decisión no se puede revertir.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            });
            if (!confirmation.isConfirmed) return;

            try {
                await axios.put('/lote/anular', { id });
                await this.loadProducts(this.productPagination.current_page || 1);
                if (this.lotsLoaded) await this.loadLots(this.lotPagination.current_page || 1);
                if (this.view === 'detail') await this.loadLotDetails();
                await Swal.fire({ icon: 'success', title: 'Lote eliminado', timer: 1100, showConfirmButton: false });
            } catch (error) {
                console.error(error);
                Swal.fire({ icon: 'error', title: 'No se pudo eliminar el lote' });
            }
        },
        showLoadError(message) {
            Swal.fire({ icon: 'error', title: 'No se pudo cargar el inventario', text: message });
        },
    },
    async mounted() {
        try {
            await this.loadProducts(1);
        } finally {
            this.initialLoading = false;
        }
    },
    beforeDestroy() {
        this.productRequestSequence += 1;
        this.lotRequestSequence += 1;
        this.detailRequestSequence += 1;
    },
};
</script>

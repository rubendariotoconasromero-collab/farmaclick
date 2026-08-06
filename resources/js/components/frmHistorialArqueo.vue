<template>
    <cash-history-workspace
        :mode="mode"
        :rows="arrayArqueo"
        :pagination="pagination"
        :pages="pagesNumber"
        :start-date="filters.fecha_inicio"
        :end-date="filters.fecha_final"
        :search="filters.buscar"
        :loading="loading"
        :selected-record="selectedRecord"
        @update:startDate="filters.fecha_inicio = $event"
        @update:endDate="filters.fecha_final = $event"
        @update:search="filters.buscar = $event; scheduleSearch()"
        @search="listarArqueoCaja(1)"
        @page="cambiarPagina"
        @view="verArqueo"
        @back="volverHistorialListado"
    />
</template>

<script>
import moment from 'moment';
import Swal from '../utils/appSwal';
import CashHistoryWorkspace from './cash/CashHistoryWorkspace.vue';

const emptyPagination = () => ({
    total: 0,
    current_page: 1,
    per_page: 0,
    last_page: 1,
    from: 0,
    to: 0,
});

export default {
    name: 'FrmHistorialArqueo',
    components: { CashHistoryWorkspace },
    data() {
        return {
            mode: 'list',
            arrayArqueo: [],
            selectedRecord: {},
            filters: {
                fecha_inicio: moment().format('YYYY-MM-DD'),
                fecha_final: moment().format('YYYY-MM-DD'),
                buscar: '',
                criterio: 'u.name',
            },
            pagination: emptyPagination(),
            offset: 3,
            loading: false,
            searchTimer: null,
            requestSequence: 0,
        };
    },
    computed: {
        pagesNumber() {
            if (!this.pagination.to || this.pagination.last_page <= 1) {
                return [];
            }

            let from = Math.max(1, this.pagination.current_page - this.offset);
            const to = Math.min(this.pagination.last_page, from + this.offset * 2);
            const pages = [];

            while (from <= to) {
                pages.push(from);
                from += 1;
            }

            return pages;
        },
    },
    methods: {
        async listarArqueoCaja(page = 1) {
            window.clearTimeout(this.searchTimer);

            if (!this.validDateRange()) {
                return;
            }

            const sequence = ++this.requestSequence;
            this.loading = true;

            try {
                const response = await axios.get('/arqueo', {
                    params: {
                        fecha_inicio: this.filters.fecha_inicio,
                        fecha_final: this.filters.fecha_final,
                        page,
                        buscar: this.filters.buscar,
                        criterio: this.filters.criterio,
                    },
                });

                if (sequence !== this.requestSequence) {
                    return;
                }

                this.arrayArqueo = Array.isArray(response.data.data) ? response.data.data : [];
                this.pagination = {
                    total: Number(response.data.total || 0),
                    current_page: Number(response.data.current_page || 1),
                    per_page: Number(response.data.per_page || 0),
                    last_page: Number(response.data.last_page || 1),
                    from: Number(response.data.from || 0),
                    to: Number(response.data.to || 0),
                };
            } catch (error) {
                if (sequence === this.requestSequence) {
                    this.arrayArqueo = [];
                    this.pagination = emptyPagination();
                    this.showError('No fue posible cargar el historial de arqueos.');
                }
                console.error(error);
            } finally {
                if (sequence === this.requestSequence) {
                    this.loading = false;
                }
            }
        },
        validDateRange() {
            if (!this.filters.fecha_inicio || !this.filters.fecha_final) {
                this.showError('Seleccione las fechas inicial y final.');
                return false;
            }

            if (this.filters.fecha_inicio > this.filters.fecha_final) {
                this.showError('La fecha inicial no puede ser posterior a la fecha final.');
                return false;
            }

            return true;
        },
        scheduleSearch() {
            window.clearTimeout(this.searchTimer);
            this.searchTimer = window.setTimeout(() => this.listarArqueoCaja(1), 350);
        },
        cambiarPagina(page) {
            if (
                page < 1
                || page > this.pagination.last_page
                || page === this.pagination.current_page
            ) {
                return;
            }

            this.listarArqueoCaja(page);
        },
        verArqueo(record) {
            if (record.estado !== 'Cerrada') {
                return;
            }

            this.selectedRecord = { ...record };
            this.mode = 'detail';
        },
        volverHistorialListado() {
            this.mode = 'list';
            this.selectedRecord = {};
        },
        showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Ocurrió un problema',
                text: message,
            });
        },
    },
    mounted() {
        this.listarArqueoCaja(1);
    },
    beforeDestroy() {
        window.clearTimeout(this.searchTimer);
        this.requestSequence += 1;
    },
};
</script>

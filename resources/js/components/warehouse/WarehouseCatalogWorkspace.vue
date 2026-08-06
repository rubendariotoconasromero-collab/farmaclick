<template>
    <main class="warehouse-catalog">
        <app-module-header
            eyebrow="Almacén · Catálogos"
            :title="title"
            :subtitle="subtitle"
        >
            <template #actions>
                <app-button icon="img/menu/Almacen.png" @click="openCreate">
                    Nuevo registro
                </app-button>
            </template>
        </app-module-header>

        <section v-if="false" class="warehouse-catalog__metrics">
            <app-metric-card
                label="Registros"
                :value="pagination.total || records.length"
                hint="Elementos disponibles en el catálogo"
                icon="img/menu/Almacen.png"
                :loading="loading"
            />
            <app-metric-card
                label="Activos en página"
                :value="activeCount"
                hint="Listos para utilizar"
                icon="img/menu/control.png"
                tone="cyan"
                :loading="loading"
            />
            <app-metric-card
                label="Inactivos en página"
                :value="inactiveCount"
                hint="Registros temporalmente deshabilitados"
                icon="img/menu/historial.png"
                tone="neutral"
                :loading="loading"
            />
        </section>

        <app-data-panel
            eyebrow="Consulta"
            :title="`Listado de ${title.toLowerCase()}`"
            subtitle="Busca, revisa el estado y administra cada registro desde una sola vista."
            flush
        >
            <template #actions>
                <span class="warehouse-catalog__result">
                    {{ pagination.from || 0 }}–{{ pagination.to || 0 }} de {{ pagination.total || 0 }}
                </span>
            </template>

            <form class="warehouse-catalog__toolbar" @submit.prevent="loadRecords(1)">
                <label class="warehouse-catalog__search">
                    <span>Buscar por nombre</span>
                    <input
                        v-model.trim="search"
                        type="search"
                        :placeholder="`Buscar ${singular.toLowerCase()}…`"
                        @input="scheduleSearch"
                    >
                </label>
                <app-button type="submit" variant="secondary">Buscar</app-button>
                <app-button v-if="search" variant="ghost" @click="clearSearch">Limpiar</app-button>
            </form>

            <app-table
                :columns="columns"
                :rows="records"
                :loading="loading"
                row-key="id"
                min-width="760px"
                empty-title="Catálogo sin registros"
                empty-message="No encontramos elementos con los filtros actuales."
            >
                <template #cell-nombre="{ value }"><strong>{{ value }}</strong></template>
                <template #cell-detail="{ row }">{{ row[detailField] || `Sin ${detailLabel.toLowerCase()}` }}</template>
                <template #cell-estado="{ row }">
                    <span class="warehouse-catalog__status" :class="{ 'is-inactive': Number(row.estado) !== 1 }">
                        {{ Number(row.estado) === 1 ? 'Activo' : 'Inactivo' }}
                    </span>
                </template>
                <template #cell-actions="{ row }">
                    <div class="warehouse-catalog__row-actions">
                        <button type="button" class="warehouse-catalog__action" @click="openEdit(row)">Editar</button>
                        <button
                            type="button"
                            class="warehouse-catalog__action"
                            :class="{ 'is-danger': Number(row.estado) === 1 }"
                            @click="toggleStatus(row)"
                        >
                            {{ Number(row.estado) === 1 ? 'Desactivar' : 'Activar' }}
                        </button>
                    </div>
                </template>
            </app-table>

            <purchase-pagination
                :pagination="pagination"
                :pages="pages"
                @change="loadRecords"
            />
        </app-data-panel>

        <div v-if="modalOpen" class="warehouse-catalog__backdrop" @click.self="closeModal">
            <section class="warehouse-catalog__dialog" role="dialog" aria-modal="true" :aria-label="modalTitle">
                <header>
                    <div>
                        <span>Almacén · {{ title }}</span>
                        <h2>{{ modalTitle }}</h2>
                    </div>
                    <button type="button" aria-label="Cerrar" @click="closeModal">×</button>
                </header>

                <form @submit.prevent="save">
                    <div class="warehouse-catalog__form">
                        <app-input
                            v-model.trim="form.nombre"
                            label="Nombre"
                            placeholder="Nombre del registro"
                            :error="fieldError('nombre')"
                            required
                        />
                        <app-input
                            v-model.trim="form.detail"
                            :label="detailLabel"
                            :placeholder="detailPlaceholder"
                            :multiline="detailMultiline"
                            :rows="detailMultiline ? 3 : 1"
                        />
                        <label class="warehouse-catalog__state">
                            <span>Estado inicial</span>
                            <select v-model="form.estado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </label>
                    </div>
                    <p v-if="formMessage" class="warehouse-catalog__form-error">{{ formMessage }}</p>
                    <footer>
                        <app-button variant="ghost" @click="closeModal">Cancelar</app-button>
                        <app-button type="submit" :loading="saving">
                            {{ editing ? 'Guardar cambios' : 'Crear registro' }}
                        </app-button>
                    </footer>
                </form>
            </section>
        </div>
    </main>
</template>

<script>
import Swal, { dangerConfirm } from '../../utils/appSwal';

const blankPagination = () => ({
    total: 0,
    current_page: 1,
    per_page: 0,
    last_page: 1,
    from: 0,
    to: 0,
});

export default {
    name: 'WarehouseCatalogWorkspace',
    props: {
        title: { type: String, required: true },
        singular: { type: String, required: true },
        subtitle: { type: String, required: true },
        endpoint: { type: String, required: true },
        detailField: { type: String, default: 'descripcion' },
        detailLabel: { type: String, default: 'Descripción' },
        detailPlaceholder: { type: String, default: 'Información adicional' },
        detailMultiline: { type: Boolean, default: true },
    },
    data() {
        return {
            records: [],
            pagination: blankPagination(),
            search: '',
            searchTimer: null,
            loading: false,
            saving: false,
            modalOpen: false,
            editing: false,
            errors: {},
            formMessage: '',
            form: this.blankForm(),
        };
    },
    computed: {
        columns() {
            return [
                { key: 'nombre', label: 'Nombre' },
                { key: 'detail', label: this.detailLabel },
                { key: 'estado', label: 'Estado' },
                { key: 'actions', label: 'Acciones' },
            ];
        },
        activeCount() {
            return this.records.filter(record => Number(record.estado) === 1).length;
        },
        inactiveCount() {
            return this.records.length - this.activeCount;
        },
        pages() {
            const current = Number(this.pagination.current_page || 1);
            const last = Number(this.pagination.last_page || 1);
            const from = Math.max(1, current - 2);
            const to = Math.min(last, current + 2);
            return Array.from({ length: Math.max(0, to - from + 1) }, (_, index) => from + index);
        },
        modalTitle() {
            return this.editing ? `Editar ${this.singular.toLowerCase()}` : `Nueva ${this.singular.toLowerCase()}`;
        },
    },
    beforeDestroy() {
        window.clearTimeout(this.searchTimer);
    },
    mounted() {
        this.loadRecords(1);
    },
    methods: {
        blankForm() {
            return { id: 0, nombre: '', detail: '', estado: '1' };
        },
        fieldError(field) {
            const error = this.errors[field];
            return Array.isArray(error) ? error[0] : (error || '');
        },
        async loadRecords(page = 1) {
            this.loading = true;
            try {
                const response = await axios.get(this.endpoint, {
                    params: { page, buscar: this.search, criterio: 'nombre' },
                });
                this.records = response.data.data || [];
                this.pagination = {
                    ...blankPagination(),
                    total: response.data.total || 0,
                    current_page: response.data.current_page || 1,
                    per_page: response.data.per_page || 0,
                    last_page: response.data.last_page || 1,
                    from: response.data.from || 0,
                    to: response.data.to || 0,
                };
            } catch (error) {
                this.notifyError('No se pudo cargar el catálogo.');
            } finally {
                this.loading = false;
            }
        },
        scheduleSearch() {
            window.clearTimeout(this.searchTimer);
            this.searchTimer = window.setTimeout(() => this.loadRecords(1), 350);
        },
        clearSearch() {
            this.search = '';
            this.loadRecords(1);
        },
        openCreate() {
            this.editing = false;
            this.form = this.blankForm();
            this.errors = {};
            this.formMessage = '';
            this.modalOpen = true;
        },
        openEdit(record) {
            this.editing = true;
            this.form = {
                id: record.id,
                nombre: record.nombre || '',
                detail: record[this.detailField] || '',
                estado: String(record.estado),
            };
            this.errors = {};
            this.formMessage = '';
            this.modalOpen = true;
        },
        closeModal() {
            if (this.saving) return;
            this.modalOpen = false;
            this.form = this.blankForm();
            this.errors = {};
            this.formMessage = '';
        },
        async save() {
            this.errors = {};
            this.formMessage = '';
            if (!this.form.nombre) {
                this.errors = { nombre: ['El nombre es obligatorio.'] };
                return;
            }
            this.saving = true;
            try {
                const action = this.editing ? 'modificar' : 'guardar';
                const request = this.editing ? axios.put : axios.post;
                const payload = {
                    id: this.form.id,
                    nombre: this.form.nombre,
                    estado: this.form.estado,
                    [this.detailField]: this.form.detail,
                };
                const response = await request(`${this.endpoint}/${action}`, payload);
                if (!this.editing && response.data && Number(response.data.error) === 0) {
                    this.formMessage = `Ya existe ${this.singular.toLowerCase()} con ese nombre.`;
                    return;
                }
                const targetPage = this.editing ? this.pagination.current_page : 1;
                this.modalOpen = false;
                this.form = this.blankForm();
                await this.loadRecords(targetPage);
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: this.editing ? 'Registro actualizado' : 'Registro agregado',
                    showConfirmButton: false,
                    timer: 1400,
                });
            } catch (error) {
                this.errors = error.response && error.response.data && error.response.data.errors
                    ? error.response.data.errors
                    : {};
                this.formMessage = Object.keys(this.errors).length ? '' : 'No fue posible guardar el registro.';
            } finally {
                this.saving = false;
            }
        },
        async toggleStatus(record) {
            const activating = Number(record.estado) !== 1;
            const result = await dangerConfirm.fire({
                title: `${activating ? 'Activar' : 'Desactivar'} ${this.singular.toLowerCase()}`,
                text: activating
                    ? 'El registro volverá a estar disponible.'
                    : 'El registro dejará de estar disponible para nuevas operaciones.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: activating ? 'Sí, activar' : 'Sí, desactivar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: activating ? '#1f8a4c' : '#d63c3c',
            });
            if (!result.isConfirmed) return;
            try {
                await axios.put(`${this.endpoint}/${activating ? 'activar' : 'desactivar'}`, { id: record.id });
                await this.loadRecords(this.pagination.current_page);
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: activating ? 'Registro activado' : 'Registro desactivado',
                    showConfirmButton: false,
                    timer: 1200,
                });
            } catch (error) {
                this.notifyError('No se pudo cambiar el estado del registro.');
            }
        },
        notifyError(message) {
            Swal.fire({ icon: 'error', title: 'Ocurrió un problema', text: message });
        },
    },
};
</script>

<style scoped>
.warehouse-catalog { display: grid; gap: 1rem; padding: 1.25rem; background: #f4f8f6; }
.warehouse-catalog__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
.warehouse-catalog__result { color: #5f716a; font-size: .72rem; font-weight: 800; white-space: nowrap; }
.warehouse-catalog__toolbar { display: flex; align-items: flex-end; gap: .55rem; padding: 1rem 1.15rem; background: #f8fbf9; border-bottom: 1px solid #d8e5df; }
.warehouse-catalog__search { display: grid; flex: 1; gap: .35rem; max-width: 560px; color: #315044; font-size: .72rem; font-weight: 800; }
.warehouse-catalog__search input, .warehouse-catalog__state select { min-height: 40px; padding: .52rem .72rem; color: #17362b; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; outline: none; }
.warehouse-catalog__search input:focus, .warehouse-catalog__state select:focus { border-color: #0e93b5; box-shadow: 0 0 0 3px rgba(62, 198, 224, .18); }
.warehouse-catalog__table-wrap { overflow-x: auto; }
.warehouse-catalog__table { width: 100%; border-collapse: collapse; }
.warehouse-catalog__table th { padding: .72rem 1rem; color: #315044; font-size: .68rem; text-align: left; text-transform: uppercase; letter-spacing: .04em; background: #edf7f1; border-bottom: 1px solid #cfe0d8; }
.warehouse-catalog__table td { padding: .82rem 1rem; color: #425c52; font-size: .8rem; border-bottom: 1px solid #e3ece8; }
.warehouse-catalog__table strong { color: #17362b; }
.warehouse-catalog__table tbody tr:hover { background: #f8fbf9; }
.warehouse-catalog__actions-heading { width: 220px; text-align: right !important; }
.warehouse-catalog__row-actions { display: flex; justify-content: flex-end; gap: .4rem; }
.warehouse-catalog__action { padding: .38rem .58rem; color: #17693c; font-size: .71rem; font-weight: 800; background: #effaf4; border: 1px solid #b9d6ca; border-radius: 7px; }
.warehouse-catalog__action.is-danger { color: #b92d2d; background: #fff4f4; border-color: #efc4c4; }
.warehouse-catalog__status { display: inline-flex; padding: .28rem .55rem; color: #17693c; font-size: .68rem; font-weight: 800; background: #e5f7ed; border-radius: 999px; }
.warehouse-catalog__status.is-inactive { color: #7c4a10; background: #fff1d8; }
.warehouse-catalog__empty { height: 140px; color: #6f817a !important; text-align: center; }
.warehouse-catalog__backdrop { position: fixed; z-index: 1050; inset: 0; display: grid; padding: 1rem; place-items: center; background: rgba(16, 45, 35, .58); backdrop-filter: blur(3px); }
.warehouse-catalog__dialog { width: min(620px, 100%); overflow: hidden; background: #fff; border-radius: 16px; box-shadow: 0 24px 70px rgba(10, 35, 27, .28); }
.warehouse-catalog__dialog header { display: flex; align-items: flex-start; justify-content: space-between; padding: 1.1rem 1.25rem; color: #fff; background: linear-gradient(115deg, #163f32, #1f8a4c); border-bottom: 3px solid #3ec6e0; }
.warehouse-catalog__dialog header span { color: #71d5e8; font-size: .65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; }
.warehouse-catalog__dialog h2 { margin: .25rem 0 0; font-size: 1.1rem; }
.warehouse-catalog__dialog header button { color: #fff; font-size: 1.6rem; line-height: 1; background: transparent; border: 0; }
.warehouse-catalog__form { display: grid; gap: 1rem; padding: 1.25rem; }
.warehouse-catalog__state { display: grid; gap: .35rem; color: #315044; font-size: .73rem; font-weight: 800; }
.warehouse-catalog__dialog footer { display: flex; justify-content: flex-end; gap: .5rem; padding: .9rem 1.25rem; background: #f8fbf9; border-top: 1px solid #d8e5df; }
.warehouse-catalog__form-error { margin: 0 1.25rem 1rem; padding: .7rem .8rem; color: #a52f2f; font-size: .75rem; background: #fff2f2; border-radius: 8px; }
@media (max-width: 820px) { .warehouse-catalog__metrics { grid-template-columns: 1fr; } }
@media (max-width: 640px) { .warehouse-catalog { padding: .75rem; } .warehouse-catalog__toolbar { align-items: stretch; flex-direction: column; } .warehouse-catalog__search { max-width: none; } .warehouse-catalog__row-actions { align-items: flex-end; flex-direction: column; } }
</style>

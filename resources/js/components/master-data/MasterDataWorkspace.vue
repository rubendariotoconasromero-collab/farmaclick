<template>
    <main class="master-workspace">
        <app-module-header eyebrow="Datos maestros" :title="title" :subtitle="subtitle">
            <template #actions>
                <app-button v-if="downloadUrl" variant="secondary" :loading="downloading" @click="download">
                    Exportar PDF
                </app-button>
                <app-button icon="img/menu/configuracion.png" @click="openCreate">
                    Nuevo registro
                </app-button>
            </template>
        </app-module-header>

        <section class="master-workspace__metrics">
            <app-metric-card
                label="Registros"
                :value="pagination.total || records.length"
                hint="Total disponible"
                icon="/icons/clipboard.svg"
                :loading="loading"
            />
            <app-metric-card
                label="Activos en página"
                :value="activeCount"
                hint="Disponibles para operar"
                icon="/icons/check.svg"
                tone="cyan"
                :loading="loading"
            />
            <app-metric-card
                label="Inactivos en página"
                :value="inactiveCount"
                hint="Temporalmente deshabilitados"
                icon="/icons/ban.svg"
                tone="neutral"
                :loading="loading"
            />
        </section>

        <app-data-panel
            eyebrow="Directorio"
            :title="`Listado de ${title.toLowerCase()}`"
            subtitle="Consulta y administra la información centralizada del sistema."
            flush
        >
            <template #actions>
                <span class="master-workspace__result">
                    {{ pagination.from || 0 }}–{{ pagination.to || 0 }} de {{ pagination.total || 0 }}
                </span>
            </template>

            <form class="master-workspace__toolbar" @submit.prevent="loadRecords(1)">
                <label>
                    <span>Buscar por</span>
                    <select v-model="criterion">
                        <option v-for="option in searchFields" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                </label>
                <label class="master-workspace__search">
                    <span>Término de búsqueda</span>
                    <input
                        v-model.trim="search"
                        type="search"
                        placeholder="Escribe para buscar…"
                        @input="scheduleSearch"
                    >
                </label>
                <app-button type="submit" variant="secondary">Buscar</app-button>
                <app-button v-if="search" variant="ghost" @click="clearSearch">Limpiar</app-button>
            </form>

            <app-table
                :columns="tableColumns"
                :rows="records"
                :loading="loading"
                row-key="id"
                min-width="880px"
                empty-title="Directorio sin registros"
                empty-message="No existen resultados para la búsqueda actual."
            >
                <template #cell-estado="{ row }">
                    <span class="master-workspace__status" :class="{ 'is-inactive': Number(row.estado) !== 1 }">
                        {{ Number(row.estado) === 1 ? 'Activo' : 'Inactivo' }}
                    </span>
                </template>
                <template #cell-actions="{ row }">
                    <div class="master-workspace__row-actions">
                        <app-button variant="secondary" @click="openEdit(row)">Editar</app-button>
                        <app-button
                            :variant="Number(row.estado) === 1 ? 'danger' : 'secondary'"
                            @click="toggleStatus(row)"
                        >
                            {{ Number(row.estado) === 1 ? 'Desactivar' : 'Activar' }}
                        </app-button>
                    </div>
                </template>
            </app-table>

            <div v-if="false" class="master-workspace__table-wrap">
                <table class="master-workspace__table">
                    <thead>
                        <tr>
                            <th v-for="column in columns" :key="column.key">{{ column.label }}</th>
                            <th>Estado</th>
                            <th class="master-workspace__actions-heading">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loading">
                            <td :colspan="columns.length + 2" class="master-workspace__empty">Cargando información…</td>
                        </tr>
                        <tr v-else-if="!records.length">
                            <td :colspan="columns.length + 2" class="master-workspace__empty">
                                No existen resultados para la búsqueda actual.
                            </td>
                        </tr>
                        <tr v-for="record in records" v-else :key="record.id">
                            <td v-for="column in columns" :key="column.key">
                                <strong v-if="column.primary">{{ displayValue(record, column) }}</strong>
                                <span v-else>{{ displayValue(record, column) }}</span>
                            </td>
                            <td>
                                <span class="master-workspace__status" :class="{ 'is-inactive': Number(record.estado) !== 1 }">
                                    {{ Number(record.estado) === 1 ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td>
                                <div class="master-workspace__row-actions">
                                    <button type="button" @click="openEdit(record)">Editar</button>
                                    <button
                                        type="button"
                                        :class="{ 'is-danger': Number(record.estado) === 1 }"
                                        @click="toggleStatus(record)"
                                    >
                                        {{ Number(record.estado) === 1 ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <purchase-pagination :pagination="pagination" :pages="pages" @change="loadRecords" />
        </app-data-panel>

        <div v-if="modalOpen" class="master-workspace__backdrop" @click.self="closeModal">
            <section class="master-workspace__dialog" role="dialog" aria-modal="true" :aria-label="modalTitle">
                <header>
                    <div>
                        <span>Datos maestros · {{ title }}</span>
                        <h2>{{ modalTitle }}</h2>
                    </div>
                    <button type="button" aria-label="Cerrar" @click="closeModal">×</button>
                </header>
                <form @submit.prevent="save">
                    <div class="master-workspace__form">
                        <template v-for="field in fields">
                            <input
                                v-if="field.type === 'hidden'"
                                :key="field.key"
                                v-model="form[field.key]"
                                type="hidden"
                            >
                            <label v-else-if="field.type === 'select'" :key="field.key" class="master-workspace__field">
                                <span>{{ field.label }} <b v-if="field.required">*</b></span>
                                <select v-model="form[field.key]">
                                    <option :value="field.emptyValue === undefined ? 0 : field.emptyValue" disabled>
                                        {{ field.placeholder || `Selecciona ${field.label.toLowerCase()}` }}
                                    </option>
                                    <option
                                        v-for="option in options[field.key] || []"
                                        :key="option.id"
                                        :value="option.id"
                                    >
                                        {{ option.nombre }}
                                    </option>
                                </select>
                                <small v-if="fieldError(field.key)">{{ fieldError(field.key) }}</small>
                            </label>
                            <label v-else-if="field.type === 'state'" :key="field.key" class="master-workspace__field">
                                <span>{{ field.label }}</span>
                                <select v-model="form[field.key]">
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </label>
                            <app-input
                                v-else
                                :key="field.key"
                                v-model="form[field.key]"
                                :type="field.type || 'text'"
                                :label="field.label"
                                :placeholder="field.placeholder || ''"
                                :multiline="field.multiline || false"
                                :rows="field.multiline ? 3 : 1"
                                :required="field.required || false"
                                :error="fieldError(field.key)"
                            />
                        </template>
                    </div>
                    <p v-if="formMessage" class="master-workspace__form-error">{{ formMessage }}</p>
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
    total: 0, current_page: 1, per_page: 0, last_page: 1, from: 0, to: 0,
});

export default {
    name: 'MasterDataWorkspace',
    props: {
        title: { type: String, required: true },
        singular: { type: String, required: true },
        subtitle: { type: String, required: true },
        endpoint: { type: String, required: true },
        columns: { type: Array, required: true },
        fields: { type: Array, required: true },
        searchFields: { type: Array, required: true },
        downloadUrl: { type: String, default: '' },
        extraParams: { type: Object, default: () => ({}) },
    },
    data() {
        return {
            records: [],
            pagination: blankPagination(),
            options: {},
            search: '',
            criterion: this.searchFields[0].value,
            searchTimer: null,
            requestSequence: 0,
            loading: true,
            saving: false,
            downloading: false,
            modalOpen: false,
            editing: false,
            errors: {},
            formMessage: '',
            form: this.blankForm(),
        };
    },
    computed: {
        tableColumns() {
            return [
                ...this.columns,
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
            return this.editing ? `Editar ${this.singular.toLowerCase()}` : `Nuevo registro de ${this.singular.toLowerCase()}`;
        },
    },
    beforeDestroy() {
        window.clearTimeout(this.searchTimer);
    },
    async mounted() {
        await this.loadOptions();
        await this.loadRecords(1);
    },
    methods: {
        blankForm() {
            return this.fields.reduce((form, field) => {
                form[field.key] = field.default !== undefined ? field.default : '';
                return form;
            }, { id: 0 });
        },
        fieldError(key) {
            const value = this.errors[key];
            return Array.isArray(value) ? value[0] : (value || '');
        },
        displayValue(record, column) {
            const value = record[column.key];
            if (value === null || value === undefined || value === '') return column.empty || '—';
            return column.formatter ? column.formatter(value, record) : value;
        },
        async loadOptions() {
            const optionFields = this.fields.filter(field => field.type === 'select' && field.optionsEndpoint);
            await Promise.all(optionFields.map(async field => {
                try {
                    const response = await axios.get(field.optionsEndpoint);
                    this.$set(this.options, field.key, response.data || []);
                } catch (error) {
                    this.$set(this.options, field.key, []);
                }
            }));
        },
        async loadRecords(page = 1) {
            const sequence = ++this.requestSequence;
            this.loading = true;
            try {
                const response = await axios.get(this.endpoint, {
                    params: {
                        page,
                        buscar: this.search,
                        criterio: this.criterion,
                        ...this.extraParams,
                    },
                });
                if (sequence !== this.requestSequence) return;
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
                this.notifyError('No se pudo cargar la información.');
            } finally {
                if (sequence === this.requestSequence) {
                    this.loading = false;
                }
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
            this.form = this.fields.reduce((form, field) => {
                const sourceKey = field.sourceKey || field.key;
                form[field.key] = record[sourceKey] === null || record[sourceKey] === undefined
                    ? (field.default !== undefined ? field.default : '')
                    : record[sourceKey];
                return form;
            }, { id: record.id });
            this.errors = {};
            this.formMessage = '';
            this.modalOpen = true;
        },
        closeModal() {
            if (this.saving) return;
            this.modalOpen = false;
            this.errors = {};
            this.formMessage = '';
        },
        async save() {
            this.errors = {};
            this.formMessage = '';
            const missing = this.fields.find(field => field.required && !this.form[field.key]);
            if (missing) {
                this.errors = { [missing.key]: [`${missing.label} es obligatorio.`] };
                return;
            }
            this.saving = true;
            const targetPage = this.editing ? this.pagination.current_page : 1;
            try {
                const action = this.editing ? 'modificar' : 'guardar';
                const response = this.editing
                    ? await axios.put(`${this.endpoint}/${action}`, this.form)
                    : await axios.post(`${this.endpoint}/${action}`, this.form);
                if (response.data && Number(response.data.error) === 0) {
                    this.formMessage = `Ya existe ${this.singular.toLowerCase()} con los datos ingresados.`;
                    return;
                }
                this.modalOpen = false;
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
                text: activating ? 'El registro volverá a estar disponible.' : 'El registro no podrá utilizarse en nuevas operaciones.',
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
            } catch (error) {
                this.notifyError('No se pudo cambiar el estado.');
            }
        },
        async download() {
            this.downloading = true;
            try {
                const response = await axios.get(this.downloadUrl, { responseType: 'blob' });
                const url = URL.createObjectURL(new Blob([response.data], { type: 'application/pdf' }));
                window.open(url, '_blank');
                window.setTimeout(() => URL.revokeObjectURL(url), 60000);
            } catch (error) {
                this.notifyError('No fue posible generar el documento.');
            } finally {
                this.downloading = false;
            }
        },
        notifyError(message) {
            Swal.fire({ icon: 'error', title: 'Ocurrió un problema', text: message });
        },
    },
};
</script>

<style scoped>
.master-workspace { display: grid; gap: 1rem; padding: 1.25rem; background: #f4f8f6; }
.master-workspace__metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 1rem; }
.master-workspace__result { color: #5f716a; font-size: .72rem; font-weight: 800; white-space: nowrap; }
.master-workspace__toolbar { display: flex; align-items: flex-end; gap: .55rem; padding: 1rem 1.15rem; background: #f8fbf9; border-bottom: 1px solid #d8e5df; }
.master-workspace__toolbar label, .master-workspace__field { display: grid; gap: .35rem; color: #315044; font-size: .72rem; font-weight: 800; }
.master-workspace__search { flex: 1; max-width: 540px; }
.master-workspace__toolbar input, .master-workspace__toolbar select, .master-workspace__field select { min-height: 40px; padding: .52rem .72rem; color: #17362b; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; outline: none; }
.master-workspace__toolbar input:focus, .master-workspace__toolbar select:focus, .master-workspace__field select:focus { border-color: #0e93b5; box-shadow: 0 0 0 3px rgba(62, 198, 224, .18); }
.master-workspace__table-wrap { overflow-x: auto; }
.master-workspace__table { width: 100%; border-collapse: collapse; }
.master-workspace__table th { padding: .72rem .9rem; color: #315044; font-size: .67rem; text-align: left; text-transform: uppercase; letter-spacing: .04em; background: #edf7f1; border-bottom: 1px solid #cfe0d8; }
.master-workspace__table td { padding: .78rem .9rem; color: #425c52; font-size: .78rem; border-bottom: 1px solid #e3ece8; }
.master-workspace__table strong { color: #17362b; }
.master-workspace__table tbody tr:hover { background: #f8fbf9; }
.master-workspace__actions-heading { width: 205px; text-align: right !important; }
.master-workspace__row-actions { display: flex; justify-content: flex-end; gap: .4rem; }
.master-workspace__row-actions button { padding: .38rem .58rem; color: #17693c; font-size: .7rem; font-weight: 800; background: #effaf4; border: 1px solid #b9d6ca; border-radius: 7px; }
.master-workspace__row-actions button.is-danger { color: #b92d2d; background: #fff4f4; border-color: #efc4c4; }
.master-workspace__status { display: inline-flex; padding: .28rem .55rem; color: #17693c; font-size: .68rem; font-weight: 800; background: #e5f7ed; border-radius: 999px; }
.master-workspace__status.is-inactive { color: #7c4a10; background: #fff1d8; }
.master-workspace__empty { height: 140px; color: #6f817a !important; text-align: center; }
.master-workspace__backdrop { position: fixed; z-index: 1050; inset: 0; display: grid; padding: 1rem; place-items: center; background: rgba(16, 45, 35, .58); backdrop-filter: blur(3px); }
.master-workspace__dialog { width: min(760px, 100%); max-height: calc(100vh - 2rem); overflow: auto; background: #fff; border-radius: 16px; box-shadow: 0 24px 70px rgba(10, 35, 27, .28); }
.master-workspace__dialog header { display: flex; align-items: flex-start; justify-content: space-between; padding: 1.1rem 1.25rem; color: #fff; background: linear-gradient(115deg, #163f32, #1f8a4c); border-bottom: 3px solid #3ec6e0; }
.master-workspace__dialog header span { color: #71d5e8; font-size: .65rem; font-weight: 800; text-transform: uppercase; letter-spacing: .06em; }
.master-workspace__dialog h2 { margin: .25rem 0 0; font-size: 1.1rem; }
.master-workspace__dialog header button { color: #fff; font-size: 1.6rem; line-height: 1; background: transparent; border: 0; }
.master-workspace__form { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; padding: 1.25rem; }
.master-workspace__field b { color: #d63c3c; }
.master-workspace__field small { color: #d63c3c; font-size: .7rem; }
.master-workspace__dialog footer { display: flex; justify-content: flex-end; gap: .5rem; padding: .9rem 1.25rem; background: #f8fbf9; border-top: 1px solid #d8e5df; }
.master-workspace__form-error { margin: 0 1.25rem 1rem; padding: .7rem .8rem; color: #a52f2f; font-size: .75rem; background: #fff2f2; border-radius: 8px; }
@media (max-width: 820px) { .master-workspace__metrics, .master-workspace__form { grid-template-columns: 1fr; } }
@media (max-width: 640px) { .master-workspace { padding: .75rem; } .master-workspace__toolbar { align-items: stretch; flex-direction: column; } .master-workspace__search { max-width: none; } }
</style>

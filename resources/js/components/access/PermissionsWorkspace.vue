<template>
    <main class="permissions-workspace">
        <app-module-header
            eyebrow="Usuarios · Seguridad"
            title="Permisos"
            subtitle="Define grupos de acceso y controla qué formularios puede utilizar cada perfil."
        >
            <template #actions>
                <app-button icon="img/menu/configuracion.png" @click="openCreate">Nuevo grupo</app-button>
            </template>
        </app-module-header>

        <access-summary
            :total="pagination.total || groups.length"
            :active="activeCount"
            :inactive="groups.length - activeCount"
            :loading="loading"
        />

        <app-data-panel
            v-if="mode === 'list'"
            eyebrow="Matriz de acceso"
            title="Grupos y permisos"
            subtitle="Administra el estado y el alcance funcional de cada grupo."
            flush
        >
            <template #actions>
                <span class="permissions-workspace__result">
                    {{ pagination.from || 0 }}–{{ pagination.to || 0 }} de {{ pagination.total || 0 }}
                </span>
            </template>
            <access-toolbar
                v-model="search"
                :criterion.sync="criterion"
                :criteria="[{ value: 'nombre', label: 'Nombre del grupo' }]"
                placeholder="Buscar grupo…"
                @typing="scheduleSearch"
                @search="loadGroups(1)"
                @clear="clearSearch"
            />
            <app-table
                :columns="groupColumns"
                :rows="groups"
                :loading="loading"
                min-width="900px"
                empty-title="No hay grupos configurados"
                empty-message="Crea un grupo para comenzar a organizar los permisos."
            >
                <template #cell-nombre="{ value }"><strong>{{ value }}</strong></template>
                <template #cell-estado="{ row }">
                    <span class="permissions-workspace__status" :class="{ 'is-inactive': Number(row.estado) !== 1 }">
                        {{ Number(row.estado) === 1 ? 'Activo' : 'Inactivo' }}
                    </span>
                </template>
                <template #cell-actions="{ row }">
                    <div class="permissions-workspace__actions">
                        <app-button variant="ghost" @click="openView(row)">Ver</app-button>
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
            <purchase-pagination :pagination="pagination" :pages="pages" @change="loadGroups" />
        </app-data-panel>

        <app-data-panel
            v-else
            :eyebrow="mode === 'view' ? 'Consulta de permisos' : 'Configuración'"
            :title="editorTitle"
            subtitle="La selección se aplica a todos los usuarios que pertenecen al grupo."
        >
            <template #actions>
                <app-button variant="ghost" @click="closeEditor">Volver al listado</app-button>
            </template>
            <form class="permissions-workspace__form" @submit.prevent="save">
                <div class="permissions-workspace__fields">
                    <app-input
                        v-model.trim="form.nombre"
                        label="Nombre del grupo"
                        placeholder="Ej. Administradores"
                        :error="fieldError('nombre')"
                        :disabled="mode === 'view'"
                        required
                    />
                    <app-input
                        v-model.trim="form.descripcion"
                        label="Descripción"
                        placeholder="Responsabilidad y alcance del grupo"
                        :multiline="true"
                        :rows="3"
                        :disabled="mode === 'view'"
                    />
                    <label>
                        <span>Estado</span>
                        <select v-model="form.estado" :disabled="mode === 'view'">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </label>
                </div>
                <permission-editor
                    :selected="details"
                    :readonly="mode === 'view'"
                    :loading="detailLoading"
                    @open-picker="openPicker"
                    @remove="removePermission"
                />
                <footer v-if="mode !== 'view'">
                    <app-button variant="ghost" @click="closeEditor">Cancelar</app-button>
                    <app-button type="submit" :loading="saving">
                        {{ mode === 'create' ? 'Crear grupo' : 'Guardar cambios' }}
                    </app-button>
                </footer>
            </form>
        </app-data-panel>

        <div v-if="pickerOpen" class="permissions-workspace__backdrop" @click.self="pickerOpen = false">
            <section class="permissions-workspace__dialog" role="dialog" aria-modal="true" aria-label="Agregar permisos">
                <header>
                    <div>
                        <span>Catálogo de formularios</span>
                        <h2>Agregar permisos</h2>
                    </div>
                    <button type="button" aria-label="Cerrar" @click="pickerOpen = false">×</button>
                </header>
                <access-toolbar
                    v-model="permissionSearch"
                    placeholder="Buscar formulario…"
                    @typing="schedulePermissionSearch"
                    @search="loadAvailablePermissions"
                    @clear="clearPermissionSearch"
                />
                <app-table
                    :columns="permissionColumns"
                    :rows="availablePermissions"
                    :loading="permissionLoading"
                    min-width="560px"
                    empty-title="Sin permisos disponibles"
                    empty-message="Todos los formularios coincidentes ya fueron agregados."
                >
                    <template #cell-nombre="{ value }"><strong>{{ value }}</strong></template>
                    <template #cell-actions="{ row }">
                        <app-button variant="secondary" @click="addPermission(row)">Agregar</app-button>
                    </template>
                </app-table>
            </section>
        </div>
    </main>
</template>

<script>
import Swal from 'sweetalert2';

const blankPagination = () => ({
    total: 0, current_page: 1, per_page: 0, last_page: 1, from: 0, to: 0,
});

export default {
    name: 'PermissionsWorkspace',
    data() {
        return {
            groups: [],
            details: [],
            availablePermissions: [],
            pagination: blankPagination(),
            form: this.blankForm(),
            errors: {},
            mode: 'list',
            search: '',
            criterion: 'nombre',
            permissionSearch: '',
            searchTimer: null,
            permissionTimer: null,
            loading: true,
            detailLoading: false,
            permissionLoading: false,
            saving: false,
            pickerOpen: false,
        };
    },
    computed: {
        groupColumns() {
            return [
                { key: 'nombre', label: 'Grupo' },
                { key: 'descripcion', label: 'Descripción' },
                { key: 'estado', label: 'Estado' },
                { key: 'actions', label: 'Acciones' },
            ];
        },
        permissionColumns() {
            return [
                { key: 'nombre', label: 'Formulario / acceso' },
                { key: 'actions', label: 'Acciones' },
            ];
        },
        activeCount() {
            return this.groups.filter(group => Number(group.estado) === 1).length;
        },
        pages() {
            const current = Number(this.pagination.current_page || 1);
            const last = Number(this.pagination.last_page || 1);
            const from = Math.max(1, current - 2);
            const to = Math.min(last, current + 2);
            return Array.from({ length: Math.max(0, to - from + 1) }, (_, index) => from + index);
        },
        editorTitle() {
            if (this.mode === 'create') return 'Nuevo grupo de acceso';
            if (this.mode === 'view') return this.form.nombre || 'Detalle del grupo';
            return `Editar ${this.form.nombre || 'grupo'}`;
        },
    },
    mounted() {
        this.loadGroups(1);
    },
    beforeDestroy() {
        window.clearTimeout(this.searchTimer);
        window.clearTimeout(this.permissionTimer);
    },
    methods: {
        blankForm() {
            return { id: 0, nombre: '', descripcion: '', estado: '1' };
        },
        async loadGroups(page = 1) {
            this.loading = true;
            try {
                const response = await axios.get('/grupo', {
                    params: { page, buscar: this.search, criterio: this.criterion },
                });
                this.groups = response.data.data || [];
                this.pagination = {
                    total: response.data.total || 0,
                    current_page: response.data.current_page || 1,
                    per_page: response.data.per_page || 0,
                    last_page: response.data.last_page || 1,
                    from: response.data.from || 0,
                    to: response.data.to || 0,
                };
            } catch (error) {
                this.$toaster.error('No fue posible cargar los grupos y permisos.');
            } finally {
                this.loading = false;
            }
        },
        scheduleSearch() {
            window.clearTimeout(this.searchTimer);
            this.searchTimer = window.setTimeout(() => this.loadGroups(1), 350);
        },
        clearSearch() {
            this.search = '';
            this.loadGroups(1);
        },
        openCreate() {
            this.mode = 'create';
            this.form = this.blankForm();
            this.details = [];
            this.errors = {};
        },
        openView(group) {
            this.openExisting(group, 'view');
        },
        openEdit(group) {
            this.openExisting(group, 'edit');
        },
        async openExisting(group, mode) {
            this.mode = mode;
            this.form = {
                id: group.id,
                nombre: group.nombre || '',
                descripcion: group.descripcion || '',
                estado: String(group.estado),
            };
            this.details = [];
            this.errors = {};
            this.detailLoading = true;
            try {
                const response = await axios.get('/grupo/permiso/detalle', { params: { id: group.id } });
                this.details = Array.isArray(response.data) ? response.data : [];
            } catch (error) {
                this.$toaster.error('No fue posible cargar el detalle de permisos.');
            } finally {
                this.detailLoading = false;
            }
        },
        closeEditor() {
            this.mode = 'list';
            this.details = [];
            this.form = this.blankForm();
            this.errors = {};
        },
        async openPicker() {
            this.pickerOpen = true;
            this.permissionSearch = '';
            await this.loadAvailablePermissions();
        },
        async loadAvailablePermissions() {
            this.permissionLoading = true;
            try {
                const response = await axios.get('/formulario/listar', {
                    params: { buscar: this.permissionSearch },
                });
                const selectedIds = this.details.map(item => Number(item.id_formulario || item.id));
                this.availablePermissions = (response.data || [])
                    .filter(item => !selectedIds.includes(Number(item.id)));
            } catch (error) {
                this.$toaster.error('No fue posible cargar el catálogo de formularios.');
            } finally {
                this.permissionLoading = false;
            }
        },
        schedulePermissionSearch() {
            window.clearTimeout(this.permissionTimer);
            this.permissionTimer = window.setTimeout(this.loadAvailablePermissions, 350);
        },
        clearPermissionSearch() {
            this.permissionSearch = '';
            this.loadAvailablePermissions();
        },
        addPermission(permission) {
            this.details.push({
                id_formulario: permission.id,
                nombre: permission.nombre,
            });
            this.availablePermissions = this.availablePermissions.filter(item => item.id !== permission.id);
        },
        removePermission(permission) {
            this.details = this.details.filter(item => item !== permission);
        },
        fieldError(field) {
            return this.errors[field] ? this.errors[field][0] : '';
        },
        async save() {
            if (!this.form.nombre || !this.details.length) {
                this.$toaster.error('Completa el nombre y agrega al menos un permiso.');
                return;
            }
            this.saving = true;
            this.errors = {};
            try {
                const payload = { ...this.form, detalle: this.details };
                if (this.mode === 'create') {
                    await axios.post('/detalle_form/guardar', payload);
                } else {
                    await axios.post('/detalle_form/modificar2', payload);
                }
                this.$toaster.success(this.mode === 'create' ? 'Grupo creado correctamente.' : 'Permisos actualizados.');
                this.closeEditor();
                await this.loadGroups(1);
            } catch (error) {
                this.errors = error.response && error.response.data && error.response.data.errors
                    ? error.response.data.errors
                    : {};
                this.$toaster.error('No fue posible guardar la configuración.');
            } finally {
                this.saving = false;
            }
        },
        async toggleStatus(group) {
            const active = Number(group.estado) === 1;
            const result = await Swal.fire({
                title: active ? '¿Desactivar este grupo?' : '¿Activar este grupo?',
                text: 'El cambio afectará a las cuentas asociadas.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: active ? 'Sí, desactivar' : 'Sí, activar',
                cancelButtonText: 'Cancelar',
            });
            if (!result.isConfirmed) return;
            try {
                await axios.put(active ? '/grupo/desactivar' : '/grupo/activar', { id: group.id });
                await this.loadGroups(this.pagination.current_page || 1);
                this.$toaster.success('Estado actualizado correctamente.');
            } catch (error) {
                this.$toaster.error('No fue posible actualizar el estado.');
            }
        },
    },
};
</script>

<style scoped>
.permissions-workspace {
    display: grid;
    gap: 1.1rem;
    padding: 1.35rem;
}

.permissions-workspace__result {
    color: #60786f;
    font-size: 0.76rem;
    font-weight: 700;
}

.permissions-workspace__status {
    display: inline-flex;
    padding: 0.3rem 0.65rem;
    border-radius: 999px;
    color: #14784e;
    background: #e6f7ee;
    font-size: 0.72rem;
    font-weight: 800;
}

.permissions-workspace__status.is-inactive {
    color: #7a4d52;
    background: #f9e9eb;
}

.permissions-workspace__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 0.4rem;
}

.permissions-workspace__form {
    display: grid;
    gap: 1rem;
}

.permissions-workspace__fields {
    display: grid;
    grid-template-columns: 1fr 1.5fr 180px;
    gap: 1rem;
}

.permissions-workspace__fields label {
    display: grid;
    align-content: start;
    gap: 0.38rem;
    margin: 0;
}

.permissions-workspace__fields label > span {
    color: #3e5c51;
    font-size: 0.74rem;
    font-weight: 800;
}

.permissions-workspace__fields select {
    min-height: 42px;
    padding: 0.55rem 0.75rem;
    border: 1px solid #cfe0d8;
    border-radius: 10px;
    background: #fff;
}

.permissions-workspace__form footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.65rem;
}

.permissions-workspace__backdrop {
    position: fixed;
    z-index: 1100;
    inset: 0;
    display: grid;
    place-items: center;
    padding: 1rem;
    background: rgba(10, 35, 27, 0.58);
}

.permissions-workspace__dialog {
    width: min(760px, 100%);
    max-height: 88vh;
    overflow: auto;
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 24px 70px rgba(8, 38, 27, 0.26);
}

.permissions-workspace__dialog > header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.2rem;
    border-bottom: 1px solid #deebe5;
}

.permissions-workspace__dialog header span {
    color: #20905f;
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.permissions-workspace__dialog h2 { margin: 0.15rem 0 0; color: #17362b; font-size: 1.15rem; }
.permissions-workspace__dialog header button { border: 0; color: #557067; background: transparent; font-size: 1.8rem; }

@media (max-width: 900px) {
    .permissions-workspace__fields { grid-template-columns: 1fr; }
}

@media (max-width: 720px) {
    .permissions-workspace { padding: 0.9rem; }
}
</style>

<template>
    <main class="users-workspace">
        <app-module-header
            eyebrow="Usuarios · Cuentas"
            title="Usuarios"
            subtitle="Administra credenciales, estado, personal vinculado y alcance de acceso desde una sola vista."
        >
            <template #actions>
                <app-button icon="img/menu/usuarios.png" @click="openCreate">Nuevo usuario</app-button>
            </template>
        </app-module-header>

        <access-summary
            :total="pagination.total || users.length"
            :active="activeCount"
            :inactive="users.length - activeCount"
            :loading="loading"
        />

        <app-data-panel
            v-if="view === 'list'"
            eyebrow="Directorio de acceso"
            title="Cuentas del sistema"
            subtitle="Busca por usuario, grupo o personal y administra cada cuenta."
            flush
        >
            <template #actions>
                <span class="users-workspace__result">
                    {{ pagination.from || 0 }}–{{ pagination.to || 0 }} de {{ pagination.total || 0 }}
                </span>
            </template>
            <access-toolbar
                v-model="search"
                :criterion.sync="criterion"
                :criteria="searchCriteria"
                placeholder="Buscar cuenta…"
                @typing="scheduleSearch"
                @search="loadUsers(1)"
                @clear="clearSearch"
            />
            <app-table
                :columns="columns"
                :rows="users"
                :loading="loading"
                min-width="980px"
                empty-title="No hay usuarios"
                empty-message="No encontramos cuentas con los filtros actuales."
            >
                <template #cell-name="{ row }">
                    <div class="users-workspace__identity">
                        <span>{{ initials(row.name) }}</span>
                        <div>
                            <strong>{{ row.name }}</strong>
                            <small>{{ row.email || 'Sin correo registrado' }}</small>
                        </div>
                    </div>
                </template>
                <template #cell-estado="{ row }">
                    <span class="users-workspace__status" :class="{ 'is-inactive': Number(row.estado) !== 1 }">
                        {{ Number(row.estado) === 1 ? 'Activo' : 'Inactivo' }}
                    </span>
                </template>
                <template #cell-actions="{ row }">
                    <div class="users-workspace__actions">
                        <app-button variant="secondary" :disabled="Number(row.estado) !== 1" @click="openEdit(row)">
                            Editar
                        </app-button>
                        <app-button variant="ghost" :disabled="Number(row.estado) !== 1" @click="openPermissions(row)">
                            Permisos
                        </app-button>
                        <app-button
                            :variant="Number(row.estado) === 1 ? 'danger' : 'secondary'"
                            @click="toggleStatus(row)"
                        >
                            {{ Number(row.estado) === 1 ? 'Desactivar' : 'Activar' }}
                        </app-button>
                    </div>
                </template>
            </app-table>
            <purchase-pagination :pagination="pagination" :pages="pages" @change="loadUsers" />
        </app-data-panel>

        <app-data-panel
            v-else
            eyebrow="Permisos efectivos"
            :title="form.nombre || 'Usuario'"
            :subtitle="`Grupo base: ${selectedUser && selectedUser.grupo ? selectedUser.grupo : 'sin grupo'}`"
        >
            <template #actions>
                <app-button variant="ghost" @click="closePermissions">Volver al listado</app-button>
            </template>
            <div class="users-workspace__permission-tools">
                <p>Los accesos efectivos se heredan del rol y se administran de forma centralizada.</p>
            </div>
            <permission-editor
                :selected="permissions"
                :loading="permissionLoading"
                :show-action="false"
                readonly
            />
            <div class="users-workspace__permission-footer">
                <app-button variant="ghost" @click="closePermissions">Volver</app-button>
            </div>
        </app-data-panel>

        <div v-if="editorOpen" class="users-workspace__backdrop" @click.self="closeEditor">
            <section class="users-workspace__dialog" role="dialog" aria-modal="true" :aria-label="editorTitle">
                <header>
                    <div>
                        <span>Cuenta de acceso</span>
                        <h2>{{ editorTitle }}</h2>
                    </div>
                    <button type="button" aria-label="Cerrar" @click="closeEditor">×</button>
                </header>
                <form @submit.prevent="saveUser">
                    <div class="users-workspace__form">
                        <app-input
                            v-model.trim="form.nombre"
                            label="Nombre de usuario"
                            placeholder="Nombre para iniciar sesión"
                            :error="fieldError('nombre')"
                            required
                        />
                        <app-input
                            v-model.trim="form.email"
                            type="email"
                            label="Correo electrónico"
                            placeholder="usuario@empresa.com"
                            :error="fieldError('email')"
                        />
                        <app-input
                            v-model="form.password"
                            type="password"
                            :label="editing ? 'Nueva contraseña (opcional)' : 'Contraseña'"
                            :placeholder="editing ? 'Déjala vacía para conservar la actual' : 'Mínimo 8 caracteres'"
                            :error="fieldError('password')"
                            :required="!editing"
                        />
                        <label>
                            <span>Grupo de usuario <b>*</b></span>
                            <select v-model="form.id_grupo" @change="loadEditorPermissions">
                                <option value="0" disabled>Selecciona un grupo</option>
                                <option v-for="group in groups" :key="group.id" :value="group.id">
                                    {{ group.nombre }}
                                </option>
                            </select>
                            <small v-if="fieldError('id_grupo')">{{ fieldError('id_grupo') }}</small>
                        </label>
                        <label>
                            <span>Personal vinculado <b>*</b></span>
                            <div class="users-workspace__personal-picker">
                                <select v-model="form.id_personal">
                                    <option value="0" disabled>Selecciona una persona</option>
                                    <option v-for="person in staff" :key="person.id" :value="person.id">
                                        {{ person.nombre }}
                                    </option>
                                </select>
                                <button
                                    type="button"
                                    class="users-workspace__personal-add"
                                    title="Crear un nuevo personal"
                                    @click="openPersonalCreator"
                                >
                                    + Nuevo
                                </button>
                            </div>
                            <small v-if="fieldError('id_personal')">{{ fieldError('id_personal') }}</small>
                        </label>
                        <label>
                            <span>Estado</span>
                            <select v-model="form.estado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </label>
                    </div>

                    <div v-if="personalCreatorOpen" class="users-workspace__personal-creator">
                        <header>
                            <strong>Nuevo personal</strong>
                            <button type="button" aria-label="Cerrar" @click="closePersonalCreator">×</button>
                        </header>
                        <div class="users-workspace__personal-creator-fields">
                            <app-input
                                v-model.trim="personalForm.nombre"
                                label="Nombre completo"
                                placeholder="Nombre y apellido"
                                :error="personalFieldError('nombre')"
                                required
                            />
                            <label>
                                <span>Cargo <b>*</b></span>
                                <select v-model="personalForm.id_cargo">
                                    <option value="0" disabled>Selecciona un cargo</option>
                                    <option v-for="cargo in cargos" :key="cargo.id" :value="cargo.id">
                                        {{ cargo.nombre }}
                                    </option>
                                </select>
                                <small v-if="personalFieldError('id_cargo')">{{ personalFieldError('id_cargo') }}</small>
                            </label>
                            <app-input v-model.trim="personalForm.telefono" label="Teléfono" placeholder="Opcional" />
                            <app-input v-model.trim="personalForm.direccion" label="Dirección" placeholder="Opcional" />
                        </div>
                        <footer>
                            <app-button variant="ghost" type="button" @click="closePersonalCreator">Cancelar</app-button>
                            <app-button type="button" :loading="personalSaving" @click="savePersonal">
                                Guardar personal
                            </app-button>
                        </footer>
                    </div>

                    <div v-if="!editing" class="users-workspace__permission-preview">
                        <span>Permisos heredados del grupo</span>
                        <strong>{{ editorPermissions.length }} accesos se asignarán a la cuenta</strong>
                    </div>
                    <footer>
                        <app-button variant="ghost" @click="closeEditor">Cancelar</app-button>
                        <app-button type="submit" :loading="saving">
                            {{ editing ? 'Guardar cambios' : 'Crear usuario' }}
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
    name: 'UsersWorkspace',
    data() {
        return {
            users: [],
            groups: [],
            staff: [],
            cargos: [],
            personalCreatorOpen: false,
            personalSaving: false,
            personalErrors: {},
            personalForm: this.blankPersonalForm(),
            permissions: [],
            editorPermissions: [],
            selectedUser: null,
            pagination: blankPagination(),
            search: '',
            criterion: 'users.name',
            searchTimer: null,
            loading: true,
            editorOpen: false,
            editing: false,
            saving: false,
            permissionLoading: false,
            view: 'list',
            errors: {},
            form: this.blankForm(),
        };
    },
    computed: {
        columns() {
            return [
                { key: 'name', label: 'Usuario' },
                { key: 'grupo', label: 'Grupo' },
                { key: 'personal', label: 'Personal' },
                { key: 'estado', label: 'Estado' },
                { key: 'actions', label: 'Acciones' },
            ];
        },
        searchCriteria() {
            return [
                { value: 'users.name', label: 'Usuario' },
                { value: 'grupo.nombre', label: 'Grupo' },
                { value: 'personal.nombre', label: 'Personal' },
            ];
        },
        activeCount() {
            return this.users.filter(user => Number(user.estado) === 1).length;
        },
        pages() {
            const current = Number(this.pagination.current_page || 1);
            const last = Number(this.pagination.last_page || 1);
            const from = Math.max(1, current - 2);
            const to = Math.min(last, current + 2);
            return Array.from({ length: Math.max(0, to - from + 1) }, (_, index) => from + index);
        },
        editorTitle() {
            return this.editing ? `Editar ${this.form.nombre || 'usuario'}` : 'Nuevo usuario';
        },
    },
    async mounted() {
        await Promise.all([this.loadUsers(1), this.loadCatalogs()]);
    },
    beforeDestroy() {
        window.clearTimeout(this.searchTimer);
    },
    methods: {
        blankForm() {
            return {
                id: 0,
                nombre: '',
                matricula: '',
                email: '',
                password: '',
                id_grupo: '0',
                id_personal: '0',
                estado: '1',
                id_grupo_cambio: '',
            };
        },
        blankPersonalForm() {
            return { nombre: '', id_cargo: '0', telefono: '', direccion: '', descripcion: '', estado: '1' };
        },
        async loadUsers(page = 1) {
            this.loading = true;
            try {
                const response = await axios.get('/usuario', {
                    params: { page, buscar: this.search, criterio: this.criterion },
                });
                this.users = response.data.data || [];
                this.pagination = {
                    total: response.data.total || 0,
                    current_page: response.data.current_page || 1,
                    per_page: response.data.per_page || 0,
                    last_page: response.data.last_page || 1,
                    from: response.data.from || 0,
                    to: response.data.to || 0,
                };
            } catch (error) {
                toast.fire({ icon: 'error', title: 'No fue posible cargar los usuarios.' });
            } finally {
                this.loading = false;
            }
        },
        async loadCatalogs() {
            try {
                const [groups, staff, cargos] = await Promise.all([
                    axios.get('/grupo/selectGrupo'),
                    axios.get('/personal/selectPersonal'),
                    axios.get('/cargo/selectCargo'),
                ]);
                this.groups = groups.data || [];
                this.staff = staff.data || [];
                this.cargos = cargos.data || [];
            } catch (error) {
                toast.fire({ icon: 'error', title: 'No fue posible cargar los grupos, el personal o los cargos.' });
            }
        },
        scheduleSearch() {
            window.clearTimeout(this.searchTimer);
            this.searchTimer = window.setTimeout(() => this.loadUsers(1), 350);
        },
        clearSearch() {
            this.search = '';
            this.loadUsers(1);
        },
        initials(name) {
            return String(name || 'U')
                .split(/\s+/)
                .slice(0, 2)
                .map(part => part.charAt(0))
                .join('')
                .toUpperCase();
        },
        async openCreate() {
            this.editing = false;
            this.form = this.blankForm();
            this.editorPermissions = [];
            this.errors = {};
            this.editorOpen = true;
            try {
                const response = await axios.get('/usuario_maximo_id');
                const lastId = response.data && response.data[0] ? Number(response.data[0].id || 0) : 0;
                this.form.matricula = String(lastId + 1);
            } catch (error) {
                this.form.matricula = '';
            }
        },
        openEdit(user) {
            this.editing = true;
            this.form = {
                id: user.id,
                nombre: user.name || '',
                matricula: user.matricula || '',
                email: user.email || '',
                password: '',
                id_grupo: user.id_grupo || '0',
                id_personal: user.id_personal || '0',
                estado: String(user.estado),
                id_grupo_cambio: user.id_grupo || '',
            };
            this.editorPermissions = [];
            this.errors = {};
            this.editorOpen = true;
        },
        closeEditor() {
            this.editorOpen = false;
            this.form = this.blankForm();
            this.editorPermissions = [];
            this.errors = {};
            this.closePersonalCreator();
        },
        openPersonalCreator() {
            this.personalForm = this.blankPersonalForm();
            this.personalErrors = {};
            this.personalCreatorOpen = true;
        },
        closePersonalCreator() {
            if (this.personalSaving) return;
            this.personalCreatorOpen = false;
            this.personalForm = this.blankPersonalForm();
            this.personalErrors = {};
        },
        personalFieldError(field) {
            return this.personalErrors[field] ? this.personalErrors[field][0] : '';
        },
        async savePersonal() {
            const errors = {};
            if (!this.personalForm.nombre) errors.nombre = ['El nombre es obligatorio.'];
            if (Number(this.personalForm.id_cargo) === 0) errors.id_cargo = ['Selecciona un cargo.'];
            if (Object.keys(errors).length) {
                this.personalErrors = errors;
                return;
            }
            this.personalSaving = true;
            this.personalErrors = {};
            try {
                const { data } = await axios.post('/personal/guardar', this.personalForm);
                if (data && data.id) {
                    this.staff = [{ id: data.id, nombre: data.nombre }, ...this.staff];
                    this.form.id_personal = data.id;
                    this.personalCreatorOpen = false;
                    this.personalForm = this.blankPersonalForm();
                    toast.fire({ icon: 'success', title: 'Personal creado y vinculado a la cuenta.' });
                } else {
                    toast.fire({ icon: 'error', title: 'No fue posible crear el personal.' });
                }
            } catch (error) {
                this.personalErrors = error.response && error.response.data && error.response.data.errors
                    ? error.response.data.errors
                    : {};
                if (!Object.keys(this.personalErrors).length) {
                    toast.fire({ icon: 'error', title: 'No fue posible crear el personal.' });
                }
            } finally {
                this.personalSaving = false;
            }
        },
        async loadEditorPermissions() {
            if (!this.form.id_grupo || Number(this.form.id_grupo) === 0) {
                this.editorPermissions = [];
                return;
            }
            try {
                const response = await axios.get(`/rbac/roles/${this.form.id_grupo}`);
                this.editorPermissions = response.data.permissions || [];
            } catch (error) {
                toast.fire({ icon: 'error', title: 'No fue posible cargar los permisos del grupo.' });
            }
        },
        fieldError(field) {
            return this.errors[field] ? this.errors[field][0] : '';
        },
        async saveUser() {
            this.saving = true;
            this.errors = {};
            try {
                const payload = {
                    ...this.form,
                    id_grupo: Number(this.form.id_grupo) > 0 ? this.form.id_grupo : '',
                    id_personal: Number(this.form.id_personal) > 0 ? this.form.id_personal : '',
                };
                const response = this.editing
                    ? await axios.put('/usuario/modificar', payload)
                    : await axios.post('/usuario/guardar', payload);
                if (response.data && response.data.error) {
                    const messages = {
                        1: 'El nombre del usuario ya existe.',
                        2: 'Debes seleccionar un personal.',
                    };
                    toast.fire({ icon: 'error', title: messages[response.data.error] || 'No fue posible guardar el usuario.' });
                    return;
                }
                this.closeEditor();
                await this.loadUsers(1);
                toast.fire({ icon: 'success', title: this.editing ? 'Usuario actualizado.' : 'Usuario creado correctamente.' });
            } catch (error) {
                this.errors = error.response && error.response.data && error.response.data.errors
                    ? error.response.data.errors
                    : {};
                toast.fire({ icon: 'error', title: 'Revisa los datos requeridos del usuario.' });
            } finally {
                this.saving = false;
            }
        },
        async openPermissions(user) {
            this.selectedUser = user;
            this.form = {
                ...this.blankForm(),
                id: user.id,
                nombre: user.name || '',
                email: user.email || '',
                id_grupo: user.id_grupo || '0',
            };
            this.permissions = [];
            this.view = 'permissions';
            await this.loadGroupPermissions();
        },
        async loadGroupPermissions() {
            if (!this.form.id_grupo || Number(this.form.id_grupo) === 0) return;
            this.permissionLoading = true;
            try {
                const response = await axios.get(`/rbac/roles/${this.form.id_grupo}`);
                this.permissions = response.data.permissions || [];
            } catch (error) {
                toast.fire({ icon: 'error', title: 'No fue posible cargar los permisos del usuario.' });
            } finally {
                this.permissionLoading = false;
            }
        },
        closePermissions() {
            this.view = 'list';
            this.selectedUser = null;
            this.permissions = [];
            this.form = this.blankForm();
        },
        async toggleStatus(user) {
            const active = Number(user.estado) === 1;
            const result = await dangerConfirm.fire({
                title: active ? '¿Desactivar este usuario?' : '¿Activar este usuario?',
                text: active ? 'La cuenta no podrá ingresar al sistema.' : 'La cuenta recuperará el acceso.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: active ? 'Sí, desactivar' : 'Sí, activar',
                cancelButtonText: 'Cancelar',
            });
            if (!result.isConfirmed) return;
            try {
                await axios.put(active ? '/usuario/desactivar' : '/usuario/activar', { id: user.id });
                await this.loadUsers(this.pagination.current_page || 1);
                toast.fire({ icon: 'success', title: 'Estado del usuario actualizado.' });
            } catch (error) {
                toast.fire({ icon: 'error', title: 'No fue posible actualizar el estado.' });
            }
        },
    },
};
</script>

<style scoped>
.users-workspace {
    display: grid;
    gap: 1.1rem;
    padding: 1.35rem;
}

.users-workspace__result { color: #60786f; font-size: 0.76rem; font-weight: 700; }

.users-workspace__identity {
    display: flex;
    align-items: center;
    gap: 0.7rem;
}

.users-workspace__identity > span {
    display: grid;
    width: 34px;
    height: 34px;
    flex: 0 0 34px;
    place-items: center;
    border-radius: 10px;
    color: #fff;
    background: linear-gradient(145deg, #1f9b63, #1da8be);
    font-size: 0.68rem;
    font-weight: 900;
}

.users-workspace__identity div { display: grid; }
.users-workspace__identity small { color: #73877f; font-size: 0.68rem; }

.users-workspace__status {
    display: inline-flex;
    padding: 0.3rem 0.65rem;
    border-radius: 999px;
    color: #14784e;
    background: #e6f7ee;
    font-size: 0.72rem;
    font-weight: 800;
}

.users-workspace__status.is-inactive { color: #7a4d52; background: #f9e9eb; }
.users-workspace__actions { display: flex; flex-wrap: wrap; gap: 0.4rem; }

.users-workspace__permission-tools {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.85rem 1rem;
    border: 1px solid #dce9e3;
    border-radius: 12px;
    background: #f3faf6;
}

.users-workspace__permission-tools p {
    max-width: 700px;
    margin: 0;
    color: #526c62;
    font-size: 0.78rem;
}

.users-workspace__permission-footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.65rem;
}

.users-workspace__backdrop {
    position: fixed;
    z-index: 1100;
    inset: 0;
    display: grid;
    place-items: center;
    padding: 1rem;
    background: rgba(10, 35, 27, 0.58);
}

.users-workspace__dialog {
    width: min(850px, 100%);
    max-height: 90vh;
    overflow: auto;
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 24px 70px rgba(8, 38, 27, 0.26);
}

.users-workspace__dialog > header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.2rem;
    border-bottom: 1px solid #deebe5;
}

.users-workspace__dialog header span {
    color: #20905f;
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.users-workspace__dialog h2 { margin: 0.15rem 0 0; color: #17362b; font-size: 1.15rem; }
.users-workspace__dialog header button { border: 0; color: #557067; background: transparent; font-size: 1.8rem; }

.users-workspace__dialog form { display: grid; gap: 1rem; padding: 1.1rem 1.2rem; }

.users-workspace__form {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

.users-workspace__form label {
    display: grid;
    align-content: start;
    gap: 0.38rem;
    margin: 0;
}

.users-workspace__form label > span {
    color: #3e5c51;
    font-size: 0.74rem;
    font-weight: 800;
}

.users-workspace__form label b,
.users-workspace__form label small { color: #cf4e59; }

.users-workspace__form select {
    min-height: 42px;
    padding: 0.55rem 0.75rem;
    border: 1px solid #cfe0d8;
    border-radius: 10px;
    color: #17362b;
    background: #fff;
}

.users-workspace__personal-picker {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 0.5rem;
}

.users-workspace__personal-add {
    padding: 0 0.85rem;
    border: 1px solid #1f9b63;
    border-radius: 10px;
    color: #1f9b63;
    background: #eaf8f1;
    font-size: 0.76rem;
    font-weight: 800;
    white-space: nowrap;
}

.users-workspace__personal-add:hover { color: #fff; background: #1f9b63; }

.users-workspace__personal-creator {
    display: grid;
    gap: 0.85rem;
    padding: 1rem;
    border: 1px solid #cfe0d8;
    border-radius: 12px;
    background: #f7faf8;
}

.users-workspace__personal-creator > header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.users-workspace__personal-creator > header strong {
    color: #17362b;
    font-size: 0.85rem;
}

.users-workspace__personal-creator > header button {
    border: 0;
    color: #557067;
    background: transparent;
    font-size: 1.4rem;
    line-height: 1;
}

.users-workspace__personal-creator-fields {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

.users-workspace__personal-creator-fields label {
    display: grid;
    align-content: start;
    gap: 0.38rem;
    margin: 0;
}

.users-workspace__personal-creator-fields label > span {
    color: #3e5c51;
    font-size: 0.74rem;
    font-weight: 800;
}

.users-workspace__personal-creator-fields select {
    min-height: 42px;
    padding: 0.55rem 0.75rem;
    border: 1px solid #cfe0d8;
    border-radius: 10px;
    color: #17362b;
    background: #fff;
}

.users-workspace__personal-creator > footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.6rem;
}

.users-workspace__permission-preview {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.8rem 1rem;
    border-radius: 10px;
    color: #315b4b;
    background: #eaf8f1;
    font-size: 0.76rem;
}

.users-workspace__dialog footer {
    display: flex;
    justify-content: flex-end;
    gap: 0.65rem;
}

@media (max-width: 720px) {
    .users-workspace { padding: 0.9rem; }
    .users-workspace__form { grid-template-columns: 1fr; }
    .users-workspace__permission-tools { align-items: stretch; flex-direction: column; }
    .users-workspace__personal-creator-fields { grid-template-columns: 1fr; }
}
</style>

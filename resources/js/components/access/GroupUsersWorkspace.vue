<template>
    <main class="group-users">
        <app-module-header
            eyebrow="Usuarios · Organización"
            title="Grupos de usuarios"
            subtitle="Consulta cómo se distribuyen las cuentas del sistema entre los grupos de acceso."
        />

        <access-summary
            :total="groups.length"
            :active="assignedUsers"
            :inactive="emptyGroups"
            :loading="loading"
            active-label="Usuarios asignados"
            active-hint="Cuentas distribuidas en grupos"
            inactive-label="Grupos vacíos"
            inactive-hint="Sin cuentas asociadas"
        />

        <app-data-panel
            v-if="!selectedGroup"
            eyebrow="Directorio"
            title="Distribución por grupo"
            subtitle="Selecciona un grupo para revisar las cuentas vinculadas."
            flush
        >
            <access-toolbar
                v-model="search"
                placeholder="Buscar grupo…"
                @typing="scheduleSearch"
                @search="loadGroups"
                @clear="clearSearch"
            />
            <app-table
                :columns="groupColumns"
                :rows="groups"
                :loading="loading"
                min-width="720px"
                empty-title="No hay grupos"
                empty-message="No encontramos grupos con el criterio actual."
            >
                <template #cell-nombre="{ value }"><strong>{{ value }}</strong></template>
                <template #cell-nroUsuarios="{ value }">
                    <span class="group-users__count">{{ Number(value || 0) }} usuarios</span>
                </template>
                <template #cell-actions="{ row }">
                    <app-button variant="secondary" @click="openGroup(row)">Ver integrantes</app-button>
                </template>
            </app-table>
        </app-data-panel>

        <app-data-panel
            v-else
            eyebrow="Detalle del grupo"
            :title="selectedGroup.nombre"
            :subtitle="`${members.length} cuentas vinculadas a este grupo de acceso.`"
            flush
        >
            <template #actions>
                <app-button variant="ghost" @click="closeGroup">Volver al listado</app-button>
            </template>
            <app-table
                :columns="memberColumns"
                :rows="members"
                :loading="detailLoading"
                min-width="640px"
                empty-title="Grupo sin usuarios"
                empty-message="Todavía no existen cuentas asignadas a este grupo."
            >
                <template #cell-nombre="{ value }"><strong>{{ value }}</strong></template>
                <template #cell-estado="{ row }">
                    <span class="group-users__status" :class="{ 'is-inactive': Number(row.estado) !== 1 }">
                        {{ Number(row.estado) === 1 ? 'Activo' : 'Inactivo' }}
                    </span>
                </template>
            </app-table>
        </app-data-panel>
    </main>
</template>

<script>
export default {
    name: 'GroupUsersWorkspace',
    data() {
        return {
            groups: [],
            members: [],
            selectedGroup: null,
            search: '',
            searchTimer: null,
            loading: true,
            detailLoading: false,
        };
    },
    computed: {
        groupColumns() {
            return [
                { key: 'nombre', label: 'Grupo' },
                { key: 'nroUsuarios', label: 'Cuentas asignadas' },
                { key: 'actions', label: 'Acciones' },
            ];
        },
        memberColumns() {
            return [
                { key: 'nombre', label: 'Usuario' },
                { key: 'estado', label: 'Estado' },
            ];
        },
        assignedUsers() {
            return this.groups.reduce((total, group) => total + Number(group.nroUsuarios || 0), 0);
        },
        emptyGroups() {
            return this.groups.filter(group => Number(group.nroUsuarios || 0) === 0).length;
        },
    },
    mounted() {
        this.loadGroups();
    },
    beforeDestroy() {
        window.clearTimeout(this.searchTimer);
    },
    methods: {
        async loadGroups() {
            this.loading = true;
            try {
                const response = await axios.get('/grupo_listar', { params: { buscar: this.search } });
                this.groups = Array.isArray(response.data) ? response.data : [];
            } catch (error) {
                this.$toaster.error('No fue posible cargar los grupos de usuarios.');
            } finally {
                this.loading = false;
            }
        },
        scheduleSearch() {
            window.clearTimeout(this.searchTimer);
            this.searchTimer = window.setTimeout(this.loadGroups, 350);
        },
        clearSearch() {
            this.search = '';
            this.loadGroups();
        },
        async openGroup(group) {
            this.selectedGroup = group;
            this.members = [];
            this.detailLoading = true;
            try {
                const response = await axios.get('/usuario/grupo_usuario', { params: { id: group.id } });
                this.members = Array.isArray(response.data) ? response.data : [];
            } catch (error) {
                this.$toaster.error('No fue posible cargar los integrantes del grupo.');
            } finally {
                this.detailLoading = false;
            }
        },
        closeGroup() {
            this.selectedGroup = null;
            this.members = [];
        },
    },
};
</script>

<style scoped>
.group-users {
    display: grid;
    gap: 1.1rem;
    padding: 1.35rem;
}

.group-users__count {
    color: #197b56;
    font-weight: 700;
}

.group-users__status {
    display: inline-flex;
    padding: 0.3rem 0.65rem;
    border-radius: 999px;
    color: #14784e;
    background: #e6f7ee;
    font-size: 0.72rem;
    font-weight: 800;
}

.group-users__status.is-inactive {
    color: #7a4d52;
    background: #f9e9eb;
}

@media (max-width: 720px) {
    .group-users { padding: 0.9rem; }
}
</style>

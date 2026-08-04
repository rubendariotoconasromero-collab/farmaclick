<template>
    <section class="permission-editor">
        <header class="permission-editor__header">
            <div>
                <span>Permisos autorizados</span>
                <strong>{{ selected.length }} permisos seleccionados</strong>
            </div>
            <app-button v-if="!readonly && showAction" variant="secondary" @click="$emit('open-picker')">
                {{ actionLabel }}
            </app-button>
        </header>
        <app-table
            :columns="columns"
            :rows="selected"
            :loading="loading"
            row-key="id"
            min-width="520px"
            empty-title="Sin permisos asignados"
            empty-message="Agrega permisos para definir el alcance del rol."
        >
            <template #cell-name="{ value }"><strong>{{ value }}</strong></template>
            <template v-if="!readonly" #cell-actions="{ row }">
                <app-button variant="danger" @click="$emit('remove', row)">Quitar</app-button>
            </template>
        </app-table>
    </section>
</template>

<script>
export default {
    name: 'PermissionEditor',
    props: {
        selected: { type: Array, default: () => [] },
        readonly: { type: Boolean, default: false },
        loading: { type: Boolean, default: false },
        showAction: { type: Boolean, default: true },
        actionLabel: { type: String, default: 'Agregar permisos' },
    },
    computed: {
        columns() {
            const columns = [
                { key: 'module', label: 'Módulo' },
                { key: 'name', label: 'Permiso funcional' },
            ];
            if (!this.readonly) columns.push({ key: 'actions', label: 'Acciones' });
            return columns;
        },
    },
};
</script>

<style scoped>
.permission-editor {
    overflow: hidden;
    border: 1px solid #dce9e3;
    border-radius: 14px;
}

.permission-editor__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    padding: 0.9rem 1rem;
    border-bottom: 1px solid #dce9e3;
    background: #f3faf6;
}

.permission-editor__header div {
    display: grid;
    gap: 0.15rem;
}

.permission-editor__header span {
    color: #668078;
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.permission-editor__header strong { color: #17362b; }
</style>

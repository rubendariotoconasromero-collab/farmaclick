<template>
    <master-data-workspace
        title="Cargos"
        singular="Cargo"
        subtitle="Define las funciones internas que permiten clasificar y organizar al personal."
        endpoint="/cargo"
        :columns="columns"
        :fields="fields"
        :search-fields="searchFields"
    />
</template>

<script>
import MasterDataWorkspace from './master-data/MasterDataWorkspace.vue';

export default {
    name: 'FrmCargo',
    components: { MasterDataWorkspace },
    data() {
        return {
            columns: [
                { key: 'nombre', label: 'Cargo', primary: true },
                { key: 'descripcion', label: 'Descripción', empty: 'Sin descripción' },
            ],
            fields: [
                { key: 'nombre', label: 'Nombre del cargo', required: true },
                { key: 'descripcion', label: 'Descripción', multiline: true },
                { key: 'estado', label: 'Estado', type: 'state', default: '1' },
            ],
            searchFields: [
                { value: 'nombre', label: 'Nombre' },
                { value: 'descripcion', label: 'Descripción' },
            ],
        };
    },
};
</script>

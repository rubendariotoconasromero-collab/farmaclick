<template>
    <master-data-workspace
        title="Personal"
        singular="Personal"
        subtitle="Organiza el directorio interno y asigna el cargo correspondiente a cada integrante."
        endpoint="/personal"
        :columns="columns"
        :fields="fields"
        :search-fields="searchFields"
        :extra-params="{ pag: 15 }"
    />
</template>

<script>
import MasterDataWorkspace from './master-data/MasterDataWorkspace.vue';

export default {
    name: 'FrmPersonal',
    components: { MasterDataWorkspace },
    data() {
        return {
            columns: [
                { key: 'nombre', label: 'Personal', primary: true },
                { key: 'cargo', label: 'Cargo', empty: 'Sin cargo' },
                { key: 'telefono', label: 'Teléfono', empty: 'Sin teléfono' },
                { key: 'direccion', label: 'Dirección', empty: 'Sin dirección' },
            ],
            fields: [
                { key: 'nombre', label: 'Nombre completo', required: true },
                {
                    key: 'id_cargo',
                    label: 'Cargo',
                    type: 'select',
                    required: true,
                    default: 0,
                    optionsEndpoint: '/cargo/selectCargo',
                },
                { key: 'telefono', label: 'Teléfono', type: 'tel' },
                { key: 'direccion', label: 'Dirección' },
                { key: 'descripcion', label: 'Observaciones', multiline: true },
                { key: 'estado', label: 'Estado', type: 'state', default: '1' },
            ],
            searchFields: [
                { value: 'personal.nombre', label: 'Nombre' },
                { value: 'cargo.nombre', label: 'Cargo' },
                { value: 'personal.telefono', label: 'Teléfono' },
            ],
        };
    },
};
</script>

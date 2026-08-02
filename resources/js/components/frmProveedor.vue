<template>
    <master-data-workspace
        title="Laboratorios"
        singular="Laboratorio"
        subtitle="Administra laboratorios y proveedores asociados a compras, productos y trazabilidad comercial."
        endpoint="/proveedor"
        :columns="columns"
        :fields="fields"
        :search-fields="searchFields"
    />
</template>

<script>
import MasterDataWorkspace from './master-data/MasterDataWorkspace.vue';

export default {
    name: 'FrmProveedor',
    components: { MasterDataWorkspace },
    data() {
        return {
            columns: [
                { key: 'nombre', label: 'Laboratorio', primary: true },
                { key: 'nit', label: 'NIT', empty: 'Sin NIT' },
                { key: 'contacto', label: 'Contacto', empty: 'Sin contacto' },
                { key: 'telefono', label: 'Teléfono', empty: 'Sin teléfono' },
            ],
            fields: [
                { key: 'nombre', label: 'Nombre del laboratorio', required: true },
                { key: 'nit', label: 'NIT', placeholder: 'Identificación tributaria' },
                { key: 'contacto', label: 'Persona de contacto' },
                { key: 'telefono', label: 'Teléfono', type: 'tel' },
                { key: 'direccion', label: 'Dirección' },
                { key: 'descripcion', label: 'Observaciones', multiline: true },
                { key: 'estado', label: 'Estado', type: 'state', default: '1' },
            ],
            searchFields: [
                { value: 'nombre', label: 'Nombre' },
                { value: 'nit', label: 'NIT' },
                { value: 'contacto', label: 'Contacto' },
            ],
        };
    },
};
</script>

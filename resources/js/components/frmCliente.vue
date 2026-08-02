<template>
    <master-data-workspace
        title="Clientes"
        singular="Cliente"
        subtitle="Centraliza los datos de contacto e identificación utilizados en ventas, pagos y reportes."
        endpoint="/cliente"
        download-url="/cliente/pdfListarClientes"
        :columns="columns"
        :fields="fields"
        :search-fields="searchFields"
    />
</template>

<script>
import MasterDataWorkspace from './master-data/MasterDataWorkspace.vue';

export default {
    name: 'FrmCliente',
    components: { MasterDataWorkspace },
    data() {
        return {
            columns: [
                { key: 'nombre', label: 'Cliente', primary: true },
                { key: 'matricula', label: 'NIT / CI', empty: 'Sin documento' },
                { key: 'telefono', label: 'Teléfono', empty: 'Sin teléfono' },
                { key: 'direccion', label: 'Dirección', empty: 'Sin dirección' },
            ],
            fields: [
                { key: 'nombre', label: 'Nombre o razón social', required: true },
                { key: 'matricula', label: 'NIT / CI', placeholder: 'Documento de identificación' },
                { key: 'telefono', label: 'Teléfono', type: 'tel' },
                { key: 'direccion', label: 'Dirección' },
                { key: 'descripcion', label: 'Observaciones', multiline: true },
                { key: 'id_descuento', sourceKey: 'descuento', type: 'hidden', default: 1 },
                { key: 'estado', label: 'Estado', type: 'state', default: '1' },
            ],
            searchFields: [
                { value: 'nombre', label: 'Nombre' },
                { value: 'matricula', label: 'NIT / CI' },
                { value: 'telefono', label: 'Teléfono' },
            ],
        };
    },
};
</script>

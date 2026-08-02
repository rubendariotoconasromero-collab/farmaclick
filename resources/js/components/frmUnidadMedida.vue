<template>
    <warehouse-catalog-workspace
        title="Presentaciones"
        singular="Presentación"
        subtitle="Administra las formas de presentación utilizadas al registrar y vender productos."
        endpoint="/unidad"
        detail-field="abreviatura"
        detail-label="Abreviatura"
        detail-placeholder="Ej.: TAB, CAP, ML"
        :detail-multiline="false"
    />
</template>

<script>
import WarehouseCatalogWorkspace from './warehouse/WarehouseCatalogWorkspace.vue';

export default {
    name: 'FrmUnidadMedida',
    components: { WarehouseCatalogWorkspace },
};
</script>

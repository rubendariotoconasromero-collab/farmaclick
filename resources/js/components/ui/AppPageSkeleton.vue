<template>
    <warehouse-page-skeleton :rows="rows" :columns="columns" :label="label" />
</template>

<script>
export default {
    name: 'AppPageSkeleton',
    props: {
        rows: { type: Number, default: 5 },
        columns: { type: Number, default: 6 },
        label: { type: String, default: 'Cargando módulo' },
    },
};
</script>

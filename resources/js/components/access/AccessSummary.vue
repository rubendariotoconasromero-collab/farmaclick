<template>
    <section class="access-summary">
        <app-metric-card
            label="Registros"
            :value="total"
            hint="Total disponible"
            icon="img/menu/usuario.png"
            :loading="loading"
        />
        <app-metric-card
            :label="activeLabel"
            :value="active"
            :hint="activeHint"
            icon="img/menu/control.png"
            tone="cyan"
            :loading="loading"
        />
        <app-metric-card
            :label="inactiveLabel"
            :value="inactive"
            :hint="inactiveHint"
            icon="img/menu/historial.png"
            tone="neutral"
            :loading="loading"
        />
    </section>
</template>

<script>
export default {
    name: 'AccessSummary',
    props: {
        total: { type: Number, default: 0 },
        active: { type: Number, default: 0 },
        inactive: { type: Number, default: 0 },
        loading: { type: Boolean, default: false },
        activeLabel: { type: String, default: 'Activos' },
        activeHint: { type: String, default: 'Con acceso habilitado' },
        inactiveLabel: { type: String, default: 'Inactivos' },
        inactiveHint: { type: String, default: 'Acceso suspendido' },
    },
};
</script>

<style scoped>
.access-summary {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
}

@media (max-width: 900px) {
    .access-summary { grid-template-columns: 1fr; }
}
</style>

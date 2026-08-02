<template>
    <section class="app-data-panel">
        <header class="app-data-panel__header">
            <div>
                <span v-if="eyebrow" class="app-data-panel__eyebrow">{{ eyebrow }}</span>
                <h2>{{ title }}</h2>
                <p v-if="subtitle">{{ subtitle }}</p>
            </div>
            <div v-if="$slots.actions" class="app-data-panel__actions">
                <slot name="actions"></slot>
            </div>
        </header>
        <div class="app-data-panel__body" :class="{ 'app-data-panel__body--flush': flush }">
            <slot></slot>
        </div>
    </section>
</template>

<script>
export default {
    name: 'AppDataPanel',
    props: {
        title: {
            type: String,
            required: true,
        },
        subtitle: {
            type: String,
            default: '',
        },
        eyebrow: {
            type: String,
            default: '',
        },
        flush: {
            type: Boolean,
            default: false,
        },
    },
};
</script>

<style scoped>
.app-data-panel {
    overflow: hidden;
    background: #fff;
    border: 1px solid #d8e5df;
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(23, 54, 43, 0.065);
}

.app-data-panel__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    min-height: 78px;
    padding: 1rem 1.15rem;
    border-bottom: 1px solid #d8e5df;
}

.app-data-panel h2,
.app-data-panel p {
    margin: 0;
}

.app-data-panel h2 {
    color: #17362b;
    font-size: 1rem;
    font-weight: 800;
}

.app-data-panel p {
    margin-top: 0.22rem;
    color: #6f817a;
    font-size: 0.76rem;
}

.app-data-panel__eyebrow {
    display: block;
    margin-bottom: 0.2rem;
    color: #0e93b5;
    font-size: 0.65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.app-data-panel__actions {
    display: flex;
    gap: 0.45rem;
}

.app-data-panel__body {
    padding: 1.1rem;
}

.app-data-panel__body--flush {
    padding: 0;
}

@media (max-width: 640px) {
    .app-data-panel__header {
        align-items: flex-start;
        flex-direction: column;
    }

    .app-data-panel__actions {
        width: 100%;
    }
}
</style>

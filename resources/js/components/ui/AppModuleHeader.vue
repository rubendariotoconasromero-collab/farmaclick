<template>
    <header class="app-module-header">
        <div class="app-module-header__copy">
            <span v-if="eyebrow" class="app-module-header__eyebrow">{{ eyebrow }}</span>
            <h1>{{ title }}</h1>
            <p v-if="subtitle">{{ subtitle }}</p>
        </div>
        <div v-if="$slots.actions" class="app-module-header__actions">
            <slot name="actions"></slot>
        </div>
    </header>
</template>

<script>
export default {
    name: 'AppModuleHeader',
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
    },
};
</script>

<style scoped>
.app-module-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1.5rem;
    padding: 1.4rem 1.5rem;
    color: #fff;
    background:
        radial-gradient(circle at 88% 20%, rgba(62, 198, 224, 0.22), transparent 18rem),
        linear-gradient(115deg, #163f32, #1f8a4c);
    border-bottom: 3px solid #3ec6e0;
    border-radius: 14px;
    box-shadow: 0 10px 28px rgba(23, 54, 43, 0.14);
}

.app-module-header h1,
.app-module-header p {
    margin: 0;
}

.app-module-header h1 {
    font-size: 1.45rem;
    font-weight: 800;
    line-height: 1.2;
}

.app-module-header p {
    max-width: 680px;
    margin-top: 0.35rem;
    color: #d8eee5;
    font-size: 0.84rem;
    line-height: 1.5;
}

.app-module-header__eyebrow {
    display: block;
    margin-bottom: 0.3rem;
    color: #71d5e8;
    font-size: 0.69rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.app-module-header__actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.app-module-header__actions ::v-deep .app-button--ghost {
    color: #eafff5;
    background: rgba(255, 255, 255, 0.14);
    border-color: rgba(255, 255, 255, 0.35);
}

.app-module-header__actions ::v-deep .app-button--ghost:hover:not(:disabled) {
    color: #fff;
    background: rgba(255, 255, 255, 0.24);
    border-color: rgba(255, 255, 255, 0.55);
}

.app-module-header__actions ::v-deep .app-button--ghost .app-button__icon {
    filter: brightness(0) invert(1);
}

@media (max-width: 640px) {
    .app-module-header {
        flex-direction: column;
        padding: 1.15rem;
    }

    .app-module-header__actions {
        width: 100%;
    }
}
</style>

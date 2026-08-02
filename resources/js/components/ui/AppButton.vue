<template>
    <button
        v-bind="$attrs"
        :type="type"
        class="app-button"
        :class="[`app-button--${variant}`, { 'app-button--block': block }]"
        :disabled="disabled || loading"
        :aria-busy="loading ? 'true' : 'false'"
        v-on="$listeners"
    >
        <span v-if="loading" class="app-button__spinner" aria-hidden="true"></span>
        <img v-else-if="icon" class="app-button__icon" :src="resolvedIcon" alt="" aria-hidden="true">
        <span><slot></slot></span>
    </button>
</template>

<script>
export default {
    name: 'AppButton',
    inheritAttrs: false,
    props: {
        type: {
            type: String,
            default: 'button',
        },
        variant: {
            type: String,
            default: 'primary',
            validator: value => ['primary', 'secondary', 'danger', 'ghost'].includes(value),
        },
        icon: {
            type: String,
            default: '',
        },
        loading: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        block: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        resolvedIcon() {
            if (!this.icon || /^(https?:|data:|\/)/.test(this.icon)) {
                return this.icon;
            }

            const mainPathIndex = window.location.pathname.indexOf('/main');
            const applicationBase = mainPathIndex >= 0
                ? window.location.pathname.substring(0, mainPathIndex)
                : '';

            return `${applicationBase}/${this.icon.replace(/^\/+/, '')}`;
        },
    },
};
</script>

<style scoped>
.app-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    min-height: 38px;
    padding: 0.5rem 0.9rem;
    color: #fff;
    font: inherit;
    font-size: 0.79rem;
    font-weight: 700;
    background: #1f8a4c;
    border: 1px solid #1f8a4c;
    border-radius: 8px;
    cursor: pointer;
}

.app-button:hover:not(:disabled) {
    color: #fff;
    background: #1f6b45;
    border-color: #1f6b45;
    transform: translateY(-1px);
}

.app-button:focus-visible {
    outline: 3px solid rgba(62, 198, 224, 0.3);
    outline-offset: 2px;
}

.app-button:disabled {
    cursor: not-allowed;
    opacity: 0.58;
}

.app-button--secondary {
    color: #1f6b45;
    background: #fff;
    border-color: #b9d6ca;
}

.app-button--secondary:hover:not(:disabled) {
    color: #1f6b45;
    background: #effaf4;
    border-color: #2fae66;
}

.app-button--danger {
    background: #d63c3c;
    border-color: #d63c3c;
}

.app-button--danger:hover:not(:disabled) {
    background: #b92d2d;
    border-color: #b92d2d;
}

.app-button--ghost {
    color: #5f716a;
    background: transparent;
    border-color: transparent;
}

.app-button--ghost:hover:not(:disabled) {
    color: #17362b;
    background: #effaf4;
    border-color: transparent;
}

.app-button--block {
    width: 100%;
}

.app-button__icon {
    width: 16px;
    height: 16px;
    object-fit: contain;
    filter: brightness(0) invert(1);
}

.app-button--secondary .app-button__icon,
.app-button--ghost .app-button__icon {
    filter: invert(42%) sepia(17%) saturate(856%) hue-rotate(101deg) brightness(88%);
}

.app-button__spinner {
    width: 15px;
    height: 15px;
    border: 2px solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: app-button-spin 650ms linear infinite;
}

@keyframes app-button-spin {
    to {
        transform: rotate(360deg);
    }
}
</style>

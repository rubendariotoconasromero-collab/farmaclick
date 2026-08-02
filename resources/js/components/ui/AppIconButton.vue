<template>
    <button
        type="button"
        class="app-icon-button"
        :class="[`app-icon-button--${variant}`]"
        :title="label"
        :aria-label="label"
        :disabled="disabled"
        v-on="$listeners"
    >
        <img :src="resolvedIcon" :alt="label" aria-hidden="true">
    </button>
</template>

<script>
export default {
    name: 'AppIconButton',
    props: {
        icon: { type: String, required: true },
        label: { type: String, default: '' },
        variant: {
            type: String,
            default: 'default',
            validator: value => ['default', 'danger'].includes(value),
        },
        disabled: { type: Boolean, default: false },
    },
    computed: {
        resolvedIcon() {
            if (/^(https?:|data:|\/)/.test(this.icon)) {
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
.app-icon-button {
    display: grid;
    width: 30px;
    height: 30px;
    place-items: center;
    background: #fff;
    border: 1px solid #cbdcd4;
    border-radius: 6px;
    cursor: pointer;
}

.app-icon-button:hover:not(:disabled) {
    background: #effaf4;
    border-color: #2fae66;
}

.app-icon-button--danger:hover:not(:disabled) {
    background: #fff0f0;
    border-color: #d63c3c;
}

.app-icon-button:disabled {
    cursor: not-allowed;
    opacity: 0.5;
}

.app-icon-button img {
    width: 15px;
    height: 15px;
    filter: invert(37%) sepia(13%) saturate(621%) hue-rotate(101deg);
}
</style>

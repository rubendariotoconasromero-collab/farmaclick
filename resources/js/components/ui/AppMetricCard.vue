<template>
    <article class="app-metric-card" :class="`app-metric-card--${tone}`">
        <div class="app-metric-card__icon">
            <img :src="resolvedIcon" alt="" aria-hidden="true">
        </div>
        <div class="app-metric-card__content">
            <span class="app-metric-card__label">{{ label }}</span>
            <span v-if="loading" class="app-metric-card__skeleton" aria-label="Cargando"></span>
            <strong v-else>{{ value }}</strong>
            <small v-if="hint">{{ hint }}</small>
        </div>
    </article>
</template>

<script>
export default {
    name: 'AppMetricCard',
    props: {
        label: {
            type: String,
            required: true,
        },
        value: {
            type: [String, Number],
            default: '0',
        },
        hint: {
            type: String,
            default: '',
        },
        icon: {
            type: String,
            required: true,
        },
        tone: {
            type: String,
            default: 'green',
            validator: value => ['green', 'cyan', 'blue', 'neutral'].includes(value),
        },
        loading: {
            type: Boolean,
            default: false,
        },
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
.app-metric-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    min-height: 118px;
    padding: 1.15rem;
    overflow: hidden;
    background: #fff;
    border: 1px solid #d8e5df;
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(23, 54, 43, 0.07);
}

.app-metric-card__icon {
    display: grid;
    flex: 0 0 48px;
    width: 48px;
    height: 48px;
    place-items: center;
    background: #d9f0e3;
    border-radius: 12px;
}

.app-metric-card__icon img {
    width: 23px;
    height: 23px;
    object-fit: contain;
    filter: invert(47%) sepia(50%) saturate(718%) hue-rotate(95deg) brightness(88%);
}

.app-metric-card__content {
    display: flex;
    min-width: 0;
    flex-direction: column;
}

.app-metric-card__label {
    margin-bottom: 0.28rem;
    color: #5f716a;
    font-size: 0.72rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.app-metric-card strong {
    color: #17362b;
    font-size: 1.5rem;
    font-weight: 800;
    line-height: 1.15;
}

.app-metric-card small {
    margin-top: 0.28rem;
    color: #83948d;
    font-size: 0.72rem;
}

.app-metric-card--cyan .app-metric-card__icon {
    background: #d8f6fb;
}

.app-metric-card--cyan .app-metric-card__icon img {
    filter: invert(51%) sepia(75%) saturate(833%) hue-rotate(147deg) brightness(85%);
}

.app-metric-card--blue .app-metric-card__icon {
    background: #e2f4f8;
}

.app-metric-card--blue .app-metric-card__icon img {
    filter: invert(48%) sepia(86%) saturate(738%) hue-rotate(146deg) brightness(80%);
}

.app-metric-card--neutral .app-metric-card__icon {
    background: #edf3f0;
}

.app-metric-card--neutral .app-metric-card__icon img {
    filter: invert(36%) sepia(10%) saturate(691%) hue-rotate(102deg) brightness(94%);
}

.app-metric-card__skeleton {
    display: block;
    width: 72%;
    height: 1.55rem;
    border-radius: 6px;
    background: linear-gradient(90deg, #e4ece8 25%, #f7faf8 50%, #e4ece8 75%);
    background-size: 200% 100%;
    animation: app-metric-loading 1.2s ease-in-out infinite;
}

@keyframes app-metric-loading {
    from { background-position: 200% 0; }
    to { background-position: -200% 0; }
}
</style>

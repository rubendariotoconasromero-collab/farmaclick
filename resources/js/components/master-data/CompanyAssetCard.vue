<template>
    <article class="company-asset" :class="{ 'company-asset--wide': wide }">
        <div class="company-asset__preview">
            <img :src="src || fallback" :alt="title" @error="$event.target.src = fallback">
        </div>
        <div class="company-asset__copy">
            <strong>{{ title }}</strong>
            <small>{{ hint }}</small>
        </div>
        <div class="company-asset__actions">
            <label :class="{ 'is-disabled': disabled }">
                <span>Seleccionar imagen</span>
                <input type="file" accept="image/png,image/jpeg,image/webp" :disabled="disabled" @change="$emit('change', $event)">
            </label>
            <app-button v-if="$listeners.preview" variant="secondary" @click="$emit('preview')">Ampliar</app-button>
        </div>
    </article>
</template>

<script>
export default {
    name: 'CompanyAssetCard',
    props: {
        title: { type: String, required: true },
        hint: { type: String, default: '' },
        src: { type: String, default: '' },
        disabled: { type: Boolean, default: false },
        wide: { type: Boolean, default: false },
    },
    data() {
        return { fallback: 'img/logo_default/logo.png' };
    },
};
</script>

<style scoped>
.company-asset { display: grid; grid-template-columns: 112px minmax(0, 1fr); gap: .9rem; padding: .9rem; background: #f8fbf9; border: 1px solid #d8e5df; border-radius: 12px; }
.company-asset__preview { display: grid; width: 112px; height: 96px; overflow: hidden; place-items: center; background: #fff; border: 1px dashed #b9d6ca; border-radius: 9px; }
.company-asset--wide .company-asset__preview { background: #eaf2ee; }
.company-asset__preview img { max-width: 100%; max-height: 100%; object-fit: contain; }
.company-asset__copy { display: flex; min-width: 0; flex-direction: column; }
.company-asset__copy strong { color: #17362b; font-size: .82rem; }
.company-asset__copy small { margin-top: .25rem; color: #6f817a; font-size: .69rem; line-height: 1.4; }
.company-asset__actions { display: flex; grid-column: 1 / -1; flex-wrap: wrap; gap: .5rem; }
.company-asset__actions label { display: inline-flex; align-items: center; min-height: 38px; padding: .5rem .8rem; color: #17693c; font-size: .72rem; font-weight: 800; background: #fff; border: 1px solid #b9d6ca; border-radius: 8px; cursor: pointer; }
.company-asset__actions label.is-disabled { cursor: not-allowed; opacity: .55; }
.company-asset__actions input { position: absolute; width: 1px; height: 1px; overflow: hidden; opacity: 0; }
</style>

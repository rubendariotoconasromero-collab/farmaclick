<template>
    <div ref="root" class="app-multiselect" :class="{ 'is-open': open, 'is-disabled': disabled }">
        <label v-if="label" class="app-multiselect__label" :for="fieldId">{{ label }}</label>
        <button
            :id="fieldId"
            type="button"
            class="app-multiselect__trigger"
            :disabled="disabled"
            :aria-expanded="open ? 'true' : 'false'"
            aria-haspopup="listbox"
            @click="toggle"
            @keydown.esc="close"
        >
            <span class="app-multiselect__summary" :class="{ 'is-placeholder': !selectedItems.length }">{{ summary }}</span>
            <span v-if="selectedItems.length" class="app-multiselect__count">{{ selectedItems.length }}</span>
            <svg class="app-multiselect__chevron" viewBox="0 0 20 20" aria-hidden="true"><path d="m5 7.5 5 5 5-5" /></svg>
        </button>

        <div v-if="open" class="app-multiselect__menu">
            <div class="app-multiselect__menu-head">
                <strong>{{ menuTitle }}</strong>
                <button v-if="selectedItems.length" type="button" @click="clear">Limpiar</button>
            </div>
            <div class="app-multiselect__options" role="listbox" aria-multiselectable="true">
                <button
                    v-for="option in options"
                    :key="optionValue(option)"
                    type="button"
                    class="app-multiselect__option"
                    :class="{ 'is-selected': isSelected(option) }"
                    role="option"
                    :aria-selected="isSelected(option) ? 'true' : 'false'"
                    @click="toggleOption(option)"
                >
                    <span class="app-multiselect__check">
                        <svg v-if="isSelected(option)" viewBox="0 0 20 20" aria-hidden="true"><path d="m4 10 4 4 8-8" /></svg>
                    </span>
                    <span>{{ optionLabel(option) }}</span>
                </button>
                <div v-if="!options.length" class="app-multiselect__empty">{{ emptyText }}</div>
            </div>
            <div class="app-multiselect__footer">
                <button type="button" @click="selectAll">Seleccionar todas</button>
                <button type="button" class="is-primary" @click="close">Listo</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AppMultiSelectDropdown',
    props: {
        value: { type: Array, default: () => [] },
        options: { type: Array, default: () => [] },
        trackBy: { type: String, default: 'id' },
        displayBy: { type: String, default: 'nombre' },
        id: { type: String, default: '' },
        label: { type: String, default: '' },
        placeholder: { type: String, default: 'Todas' },
        menuTitle: { type: String, default: 'Seleccione opciones' },
        emptyText: { type: String, default: 'Sin opciones disponibles' },
        disabled: { type: Boolean, default: false },
    },
    data() {
        return { open: false };
    },
    computed: {
        fieldId() { return this.id || `app-multiselect-${this._uid}`; },
        selectedItems() {
            return this.options.filter(option => this.value.some(value => String(value) === String(this.optionValue(option))));
        },
        summary() {
            if (!this.selectedItems.length) return this.placeholder;
            if (this.selectedItems.length === 1) return this.optionLabel(this.selectedItems[0]);
            return `${this.selectedItems.length} seleccionadas`;
        },
    },
    mounted() {
        document.addEventListener('mousedown', this.onOutsideClick);
        document.addEventListener('keydown', this.onKeydown);
    },
    beforeDestroy() {
        document.removeEventListener('mousedown', this.onOutsideClick);
        document.removeEventListener('keydown', this.onKeydown);
    },
    methods: {
        optionValue(option) { return option && typeof option === 'object' ? option[this.trackBy] : option; },
        optionLabel(option) { return option && typeof option === 'object' ? option[this.displayBy] : String(option); },
        isSelected(option) { return this.value.some(value => String(value) === String(this.optionValue(option))); },
        toggle() { if (!this.disabled) this.open = !this.open; },
        close() { this.open = false; },
        toggleOption(option) {
            const optionValue = this.optionValue(option);
            const next = this.isSelected(option)
                ? this.value.filter(value => String(value) !== String(optionValue))
                : [...this.value, optionValue];
            this.$emit('input', next);
            this.$emit('change', next);
        },
        clear() { this.$emit('input', []); this.$emit('change', []); },
        selectAll() {
            const values = this.options.map(option => this.optionValue(option));
            this.$emit('input', values);
            this.$emit('change', values);
        },
        onOutsideClick(event) { if (this.open && !this.$refs.root.contains(event.target)) this.close(); },
        onKeydown(event) { if (event.key === 'Escape') this.close(); },
    },
};
</script>

<style scoped>
.app-multiselect { position: relative; width: 100%; text-align: left; }
.app-multiselect__label { display: block; margin-bottom: .35rem; color: #315044; font-size: .73rem; font-weight: 800; }
.app-multiselect__trigger { display: grid; grid-template-columns: minmax(0, 1fr) auto auto; gap: .45rem; align-items: center; width: 100%; min-height: 40px; padding: .48rem .65rem; color: #17362b; text-align: left; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; cursor: pointer; transition: 150ms ease; }
.app-multiselect__trigger:hover, .app-multiselect.is-open .app-multiselect__trigger { border-color: #0e93b5; box-shadow: 0 0 0 3px rgba(62,198,224,.18); }
.app-multiselect__summary { overflow: hidden; font-size: .8rem; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
.app-multiselect__summary.is-placeholder { color: #6f817a; font-weight: 500; }
.app-multiselect__count { display: grid; min-width: 20px; height: 20px; padding: 0 5px; place-items: center; color: #fff; background: #1f8a4c; border-radius: 999px; font-size: .64rem; font-weight: 900; }
.app-multiselect__chevron { width: 17px; fill: none; stroke: #527067; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; transition: transform 150ms ease; }
.app-multiselect.is-open .app-multiselect__chevron { transform: rotate(180deg); }
.app-multiselect__menu { position: absolute; top: calc(100% + 6px); right: 0; z-index: 40; width: max(100%, 280px); overflow: hidden; background: #fff; border: 1px solid #bdd2c9; border-radius: 11px; box-shadow: 0 16px 34px rgba(23,54,43,.18); }
.app-multiselect__menu-head, .app-multiselect__footer { display: flex; justify-content: space-between; align-items: center; gap: .5rem; padding: .65rem .75rem; background: #f4f9f6; }
.app-multiselect__menu-head { border-bottom: 1px solid #dfebe6; }
.app-multiselect__menu-head strong { color: #173f32; font-size: .73rem; }
.app-multiselect__menu-head button, .app-multiselect__footer button { padding: .25rem .4rem; color: #1f8a4c; background: transparent; border: 0; font-size: .68rem; font-weight: 800; cursor: pointer; }
.app-multiselect__options { max-height: 245px; padding: .35rem; overflow-y: auto; }
.app-multiselect__option { display: grid; grid-template-columns: 22px 1fr; gap: .5rem; align-items: center; width: 100%; padding: .5rem .55rem; color: #315044; text-align: left; background: transparent; border: 0; border-radius: 7px; cursor: pointer; }
.app-multiselect__option:hover { background: #effaf4; }
.app-multiselect__option.is-selected { color: #17693c; background: #e8f7ee; font-weight: 800; }
.app-multiselect__check { display: grid; width: 18px; height: 18px; place-items: center; background: #fff; border: 1px solid #aec8bd; border-radius: 5px; }
.app-multiselect__option.is-selected .app-multiselect__check { background: #1f8a4c; border-color: #1f8a4c; }
.app-multiselect__check svg { width: 13px; fill: none; stroke: #fff; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
.app-multiselect__footer { border-top: 1px solid #dfebe6; }
.app-multiselect__footer .is-primary { padding: .38rem .65rem; color: #fff; background: #1f8a4c; border-radius: 6px; }
.app-multiselect__empty { padding: .75rem; color: #6f817a; font-size: .74rem; text-align: center; }
.app-multiselect.is-disabled { opacity: .6; }
@media (max-width: 520px) { .app-multiselect__menu { right: auto; left: 0; width: min(310px, calc(100vw - 2.5rem)); } }
</style>

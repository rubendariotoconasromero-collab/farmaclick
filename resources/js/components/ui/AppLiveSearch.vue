<template>
    <div class="app-live-search" :class="{ 'app-live-search--invalid': hasError }">
        <label v-if="label" class="app-live-search__label" :for="fieldId">
            {{ label }} <span v-if="required" class="app-live-search__required" aria-hidden="true">*</span>
        </label>
        <div class="app-live-search__control" :class="{ 'app-live-search__control--open': open }">
            <input
                :id="fieldId"
                ref="field"
                v-model="query"
                type="text"
                class="app-live-search__field"
                :placeholder="placeholder"
                :disabled="disabled"
                autocomplete="off"
                :aria-invalid="hasError ? 'true' : 'false'"
                @focus="onFocus"
                @blur="onBlur"
                @input="onInput"
                @keydown.down.prevent="move(1)"
                @keydown.up.prevent="move(-1)"
                @keydown.enter.prevent="selectHighlighted"
                @keydown.esc="close"
            >
            <button
                v-if="query && !disabled"
                type="button"
                class="app-live-search__clear"
                tabindex="-1"
                aria-label="Limpiar"
                @mousedown.prevent="clear"
            >×</button>
            <ul v-if="open" class="app-live-search__menu">
                <li v-if="loading" class="app-live-search__empty">Buscando…</li>
                <template v-else-if="filtered.length">
                    <li
                        v-for="(item, index) in filtered"
                        :key="itemKey(item, index)"
                        class="app-live-search__option"
                        :class="{ 'is-active': index === highlighted }"
                        @mousedown.prevent="select(item)"
                        @mouseenter="highlighted = index"
                    >{{ itemLabel(item) }}</li>
                </template>
                <li v-else class="app-live-search__empty">{{ emptyText }}</li>
            </ul>
        </div>
        <small v-if="hasError" class="app-live-search__message app-live-search__message--error">{{ error }}</small>
        <small v-else-if="hint" class="app-live-search__message">{{ hint }}</small>
    </div>
</template>

<script>
export default {
    name: 'AppLiveSearch',
    props: {
        value: { type: [String, Number], default: '' },
        searchValue: { type: [String, Number], default: null },
        items: { type: Array, default: () => [] },
        trackBy: { type: String, default: 'id' },
        displayBy: { type: String, default: 'nombre' },
        id: { type: String, default: '' },
        label: { type: String, default: '' },
        placeholder: { type: String, default: 'Buscar…' },
        hint: { type: String, default: '' },
        error: { type: [String, Array], default: '' },
        disabled: { type: Boolean, default: false },
        required: { type: Boolean, default: false },
        loading: { type: Boolean, default: false },
        emptyText: { type: String, default: 'Sin resultados' },
    },
    data() {
        return {
            query: '',
            open: false,
            highlighted: -1,
        };
    },
    computed: {
        fieldId() {
            return this.id || `app-live-search-${this._uid}`;
        },
        hasError() {
            return Array.isArray(this.error) ? this.error.length > 0 : Boolean(this.error);
        },
        selectedItem() {
            return this.items.find(item => String(item[this.trackBy]) === String(this.value)) || null;
        },
        filtered() {
            const term = this.query.trim().toLowerCase();
            if (!term) return this.items;
            return this.items.filter(item => this.itemLabel(item).toLowerCase().includes(term));
        },
    },
    watch: {
        value: { immediate: true, handler() { this.syncQuery(); } },
        searchValue() { this.syncQuery(); },
        items() { this.syncQuery(); },
    },
    methods: {
        itemLabel(item) {
            return item ? String(item[this.displayBy] ?? '') : '';
        },
        itemKey(item, index) {
            return item ? item[this.trackBy] : index;
        },
        syncQuery() {
            if (this.open) return;
            if (this.searchValue !== null && this.searchValue !== undefined) {
                this.query = String(this.searchValue);
                return;
            }
            this.query = this.selectedItem ? this.itemLabel(this.selectedItem) : '';
        },
        onFocus() {
            if (this.disabled) return;
            this.open = true;
            this.highlighted = this.filtered.findIndex(item => String(item[this.trackBy]) === String(this.value));
            this.$nextTick(() => this.$refs.field.select());
        },
        onBlur() {
            this.close();
            this.syncQuery();
        },
        onInput() {
            this.open = true;
            this.highlighted = 0;
            this.$emit('search', this.query);
            if (!this.query) this.$emit('input', '');
        },
        move(step) {
            if (!this.open) { this.open = true; return; }
            const count = this.filtered.length;
            if (!count) return;
            this.highlighted = (this.highlighted + step + count) % count;
        },
        selectHighlighted() {
            const item = this.filtered[this.highlighted];
            if (item) this.select(item);
        },
        select(item) {
            this.$emit('input', item[this.trackBy]);
            this.$emit('select', item);
            this.query = this.itemLabel(item);
            this.close();
        },
        clear() {
            this.query = '';
            this.$emit('input', '');
            this.$emit('search', '');
            this.$emit('select', null);
            this.$refs.field.focus();
        },
        close() {
            this.open = false;
            this.highlighted = -1;
        },
    },
};
</script>

<style scoped>
.app-live-search { position: relative; width: 100%; text-align: left; }
.app-live-search__label { display: block; margin-bottom: .35rem; color: #315044; font-size: .73rem; font-weight: 800; }
.app-live-search__required { color: #d63c3c; }
.app-live-search__control { position: relative; display: flex; align-items: center; min-height: 40px; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; transition: border-color 150ms ease, box-shadow 150ms ease; }
.app-live-search__control--open,
.app-live-search__control:focus-within { border-color: #0e93b5; box-shadow: 0 0 0 3px rgba(62, 198, 224, .18); }
.app-live-search__field { width: 100%; min-width: 0; min-height: 38px; padding: .52rem .72rem; color: #17362b; font: inherit; font-size: .82rem; background: transparent; border: 0; outline: 0; }
.app-live-search__field:disabled { color: #7d8e87; background: #edf3f0; cursor: not-allowed; }
.app-live-search__clear { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; margin-right: .35rem; color: #6f817a; font-size: 1rem; line-height: 1; background: transparent; border: 0; border-radius: 50%; cursor: pointer; }
.app-live-search__clear:hover { color: #d63c3c; background: #fff0f0; }
.app-live-search__menu { position: absolute; top: calc(100% + 4px); left: 0; z-index: 20; width: 100%; max-height: 240px; margin: 0; padding: .3rem; overflow-y: auto; list-style: none; background: #fff; border: 1px solid #bdd2c9; border-radius: 8px; box-shadow: 0 10px 24px rgba(23,54,43,.14); }
.app-live-search__option { padding: .5rem .6rem; color: #17362b; font-size: .8rem; border-radius: 6px; cursor: pointer; }
.app-live-search__option.is-active,
.app-live-search__option:hover { background: #effaf4; color: #0e93b5; }
.app-live-search__empty { padding: .5rem .6rem; color: #6f817a; font-size: .76rem; }
.app-live-search__message { display: block; margin-top: .3rem; color: #6f817a; font-size: .7rem; }
.app-live-search__message--error { color: #d63c3c; }
.app-live-search--invalid .app-live-search__control { border-color: #d63c3c; }
</style>

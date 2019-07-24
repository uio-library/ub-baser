<template>
    <div class="d-flex my-1">
        <div class="flex-grow-0">
            <select class="form-control field-select"
                :name="`f${index}`"
                :value="field"
                @input="$emit('field', $event.target.value)"
            >
                <option v-for="field in fields.fields"
                        v-if="field.search && (advanced || !field.search.advanced)"
                        :value="field.key"
                >{{ field.label }}</option>

                <optgroup v-for="fieldGroup in fields.groups" :key="fieldGroup.label" :label="fieldGroup.label">
                    <option v-for="field in fieldGroup.fields"
                            v-if="field.search && (advanced || !field.search.advanced)"
                            :value="field.key"
                    >{{ field.label }}</option>
                </optgroup>
            </select>
        </div>

        <div v-if="advanced" class="flex-grow-0 mx-1">
            <select v-if="advanced"
                class="form-control field-select"
                :name="`o${index}`"
                :value="operator"
                @input="$emit('operator', $event.target.value)"
            >
                <option v-for="option in activeOperators"
                        :value="option.value"
                >{{ option.label }}</option>
            </select>
        </div>

        <div class="flex-grow-1 mx-1">
            <component
                v-if="operator != 'isnull' && operator != 'notnull'"
                :is="definition.search.type || definition.type"
                :name="`v${index}`"
                :value="value"
                :definition="definition"
                @value="$emit('value', $event)"
            ></component>
        </div>

        <div class="flex-grow-0">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    import * as components from './input'

    let fieldMap = null;

    export default {
        name: "LitteraturkritikkSearchField",

        components: {
            ...components,
        },

        props: {
            advanced: Boolean,
            index: Number,
            fields: Object,
            field: String,
            operators: Array,
            operator: String,
            value: String,
        },

        computed: {
            definition() {
                if (fieldMap === null) {
                    // Lazy-load field map
                    fieldMap = new Map();
                    this.fields.fields.forEach(field => fieldMap[field.key] = field);
                    this.fields.groups.forEach(fieldGroup => {
                        fieldGroup.fields.forEach(field => fieldMap[field.key] = field);
                    })
                }
                return fieldMap[this.field];
            },
            activeOperators() {
                return this.operators.filter(op => this.definition.search.operators.indexOf(op.value) !== -1);
            }
        }
    }
</script>

<style scoped lang="sass">
td
    padding: 2px 6px
</style>
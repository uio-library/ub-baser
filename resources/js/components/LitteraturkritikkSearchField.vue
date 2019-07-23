<template>
    <tr>
        <td style="width: 200px;">
            <select class="form-control field-select"
                :name="`f${index}`"
                :value="field"
                @input="$emit('field', $event.target.value)"
            >
                <option v-for="field in fields.fields"
                        v-if="advanced || !field.advanced"
                        :value="field.id"
                >{{ field.label }}</option>

                <optgroup v-for="fieldGroup in fields.groups" :key="fieldGroup.label" :label="fieldGroup.label">
                    <option v-for="field in fieldGroup.fields"
                            v-if="advanced || !field.advanced"
                            :value="field.id"
                    >{{ field.label }}</option>
                </optgroup>
            </select>
        </td>

        <td v-if="advanced" style="width: 200px;">
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
        </td>

        <td>
            <component
                v-if="operator != 'isnull' && operator != 'notnull'"
                :is="definition.type"
                :name="`v${index}`"
                :value="value"
                :definition="definition"
                @value="$emit('value', $event)"
            ></component>
        </td>

        <td style="width: 100px;">
            <slot></slot>
        </td>

    </tr>
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
                    this.fields.fields.forEach(field => fieldMap[field.id] = field);
                    this.fields.groups.forEach(fieldGroup => {
                        fieldGroup.fields.forEach(field => fieldMap[field.id] = field);
                    })
                }
                return fieldMap[this.field];
            },
            activeOperators() {
                return this.operators.filter(op => this.definition.operators.indexOf(op.value) !== -1);
            }
        }
    }
</script>

<style scoped lang="sass">
td
    padding: 2px 6px
</style>
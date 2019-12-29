<template>
    <div v-if="!currentSchema || !currentSchema.searchOptions || !currentSchema.searchOptions.operators" class="text-danger">
        <span v-if="!currentSchema">Error: No schema found for {{ field }}.</span>
        <span v-else>Invalid: Schema for "{{ field }}" lacks "searchOptions" or "searchOptions.operators"</span>
    </div>
    <div v-else class="d-flex my-1" :id="'searchField' + index">
        <div class="flex-grow-0">
            <select class="form-control field-select"
                :name="`f${index}`"
                :value="field"
                @change="onFieldChange($event.target.value)"
            >
                <option v-for="field in fields"
                        :key="field.key"
                        :value="field.key"
                >{{ field.label }}</option>

                <optgroup v-for="fieldGroup in groups" :key="fieldGroup.label" :label="fieldGroup.label">
                    <option v-for="field in fieldGroup.fields"
                            :key="field.key"
                            :value="field.key"
                    >{{ field.label }}</option>
                </optgroup>
            </select>
        </div>

        <div v-if="advanced" class="flex-grow-0 mx-1">
            <select
                class="form-control field-select"
                :name="`o${index}`"
                :value="operator"
                @input="$emit('operator', $event.target.value)"
            >
                <option v-for="option in currentOperators"
                        :key="option.value"
                        :value="option.value"
                >{{ option.label }}</option>
            </select>
        </div>

        <div class="flex-grow-1 mx-1">
            <component
                v-if="operator != 'isnull' && operator != 'notnull'"
                :is="currentType"
                :name="`v${index}`"
                :value="value"
                :schema="currentSchema"
                :settings="settings"
                @value="$emit('value', $event)"
            ></component>
        </div>

        <div class="flex-grow-0">
            <slot></slot>
        </div>
    </div>
</template>

<script>
import { get } from 'lodash/object'
import * as components from './input'

let fieldMap = null

export default {
  name: 'search-field',

  components: {
    ...components,
  },

  props: {
    advanced: Boolean,
    index: Number,
    schema: Object,
    settings: Object,
    field: String,
    operators: Array,
    operator: String,
    value: String,
  },

  computed: {
    currentSchema () {
      if (fieldMap === null) {
        // Lazy-load field map
        fieldMap = new Map()
        fieldMap = this.schema.fields.reduce((out, field) => { out[field.key] = field; return out }, fieldMap)
        this.schema.groups.forEach(fieldGroup => {
          fieldMap = fieldGroup.fields.reduce((out, field) => { out[field.key] = field; return out }, fieldMap)
        })
      }
      return fieldMap[this.field]
    },
    currentType () {
      const type = get(this.currentSchema, 'searchOptions.type', this.currentSchema.type)
      return type.substr(0, 1).toUpperCase() + type.substr(1) + 'Input'
    },
    currentOperators () {
      return this.operators.filter(
        op => this.currentSchema.searchOptions.operators.indexOf(op.value) !== -1
      )
    },
    fields () {
      return this.schema.fields.filter(field => this.fieldIsVisible(field))
    },
    groups () {
      return this.schema.groups.map(group => ({
        label: group.label,
        fields: group.fields.filter(field => this.fieldIsVisible(field)),
      }))
    },
  },

  watch: {
    currentOperators (operators) {
      const values = operators.map(x => x.value)
      if (values.indexOf(this.operator) === -1) {
        console.log('Operator no longer part of menu. Resetting to:', values[0])
        this.$emit('operator', values[0])
      }
    }
  },

  methods: {
    fieldIsVisible (field) {
      return field.searchable === 'simple' || (this.advanced && field.searchable === 'advanced')
    },
    onFieldChange (value) {
      this.$emit('field', value)
    },
  },
}
</script>

<style scoped lang="sass">
td
    padding: 2px 6px
</style>

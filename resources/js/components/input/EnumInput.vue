<template>
  <div>
    <selectize
      :name="name"
      :settings="selectizeSettings"
      :value="value"
      :placeholder="placeholder"
      @input="onInput($event)"
    >
      <option v-for="val in values" :key="val.id" :value="val.id">{{ val.label }}</option>
    </selectize>
  </div>
</template>

<script>
import { cloneDeep } from 'lodash/lang'
import { get } from 'lodash/object'
import Selectize from 'vue2-selectize'

export default {
  name: 'enum-input',
  components: {
    Selectize,
  },
  props: {
    name: String,
    schema: Object,
    settings: Object,
    value: String,
  },
  data () {
    return {
      placeholder: get(this.schema, 'edit.placeholder', ''),
      values: cloneDeep(this.schema.values),
      selectizeSettings: {
        // Ref: https://github.com/selectize/selectize.js/blob/master/docs/usage.md
        create: false,
        valueField: 'id',
        labelField: 'label',
        searchField: 'label',
        openOnFocus: true,
        closeAfterSelect: true,
      },
    }
  },
  methods: {
    onInput ($event) {
      this.$emit('value', $event)
    },
  },
}
</script>

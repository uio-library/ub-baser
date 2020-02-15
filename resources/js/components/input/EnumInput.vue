<template>
  <div>
    <selectize
      :name="name"
      :settings="selectizeSettings"
      :value="value"
      :placeholder="placeholder"
      @input="onInput($event)"
    >
      <option v-for="val in values" :key="val.id" :value="val.id" :data-extra="val.extra">{{ val.label }}</option>
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

    let values = cloneDeep(this.schema.values)
    values.forEach(val => val.extra = JSON.stringify({invalid: false}))
    if (this.value && values.map(x => String(x.id)).indexOf(String(this.value)) === -1) {
      const invalid = this.schema.values && this.schema.values.length > 0
      values.push({ id: this.value, label: this.value, extra: JSON.stringify({invalid: invalid}) })
    }

    return {
      placeholder: get(this.schema, 'edit.placeholder', ''),
      values: values,
      selectizeSettings: {
        // Ref: https://github.com/selectize/selectize.js/blob/master/docs/usage.md
        create: false,
        valueField: 'id',
        labelField: 'label',
        searchField: 'label',
        openOnFocus: true,
        closeAfterSelect: true,
        dataAttr: 'data-extra',
        render: {
          item: this.renderItem.bind(this),
        },
      },
    }
  },
  methods: {
    onInput ($event) {
      this.$emit('value', $event)
    },
    renderItem(data, escape) {
      if (data.invalid) {
        return `<div class="text-danger">${escape(data.label)}</div>`
      }
      return `<div>${escape(data.label)}</div>`
    },
  },
}
</script>

<template>
  <div>
    <div v-if="!options.length">
      <div v-if="failed" class="text-danger p-1">Det oppsto en feil. Prøv å laste siden på nytt.</div>
      <div v-else class="p-1">Et øyeblikk...</div>
    </div>
    <div v-else>
      <selectize
        :name="name"
        :settings="selectSettings"
        :value="value"
        @input="onInput($event)"
      >
        <option v-for="option in options" :key="option.value" :value="option.value">{{ option.label }}</option>
      </selectize>
    </div>
  </div>
</template>

<script>
import { get } from 'lodash/object'
import Selectize from 'vue2-selectize'

export default {
  name: 'select-input',
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
      options: [],
      failed: false,
      selectSettings: {
        // Ref: https://github.com/selectize/selectize.js/blob/master/docs/usage.md
        create: false,
        valueField: 'value',
        labelField: 'label',
        searchField: 'label',
        openOnFocus: true,
        closeAfterSelect: true,
      },
    }
  },
  mounted () {
    const url = get(this.settings, 'baseUrl') + '/autocomplete'
    this.$http.get(url, {
      params: {
        field: this.schema.key,
      },
    }).then(res => {
      this.options = res.data
    }).catch(() => {
      this.failed = true
    })
  },
  methods: {
    onInput ($event) {
      this.$emit('value', $event)
    },
  },
}
</script>

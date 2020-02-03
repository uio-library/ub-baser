<template>
    <div>
        <input type="text"
               class="form-control"
               :name="name"
               :value="value"
               :placeholder="placeholder"
               @input="onInput($event.target.value)"
        >
    </div>
</template>

<script>
import { get } from 'lodash/object'

export default {
  name: 'simple-input',
  props: {
    name: String,
    schema: Object,
    settings: Object,
    value: String,
  },
  computed: {
    placeholder () {
      return get(this.schema, 'search.placeholder')
    },
  },
  methods: {
    onInput (newValue) {
      this.$emit('value', newValue)
    },
  },
}
</script>

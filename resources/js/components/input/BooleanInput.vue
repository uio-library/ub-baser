<template>
    <div class="d-inline-block mr-2 mb-1">
        <label>
          <input type="checkbox"
                 :name="name"
                 :checked="value"
                 @input="onInput($event)"
          >
          {{ label}}
        </label>
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
    value: Boolean,
  },
  computed: {
    label () {
      return get(this.schema, 'edit.label')
    },
  },
  methods: {
    onInput ($event) {
      this.$emit('value', $event.target.checked)
    },
  },
}
</script>

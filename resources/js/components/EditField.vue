<template>
    <tr>
        <td style="width: 150px;" valign="top">
            {{ schema.label }}
        </td>
        <td>
            <component
                :is="currentType"
                :name="name"
                :value="value"
                :schema="schema"
                @value="$emit('value', $event)"
            ></component>
            <tt>{{ this.currentType }}</tt>
        </td>
    </tr>
</template>

<script>
import { get } from 'lodash/object'
import * as components from './input'

export default {
  name: 'edit-field',
  components: {
    ...components
  },
  props: [
    'schema',
    'value'
  ],
  computed: {
    currentType () {
      const type = this.schema.type
      return type.substr(0, 1).toUpperCase() + type.substr(1) + 'Input'
    },

    name () {
      return get(this.schema, 'edit.column', this.schema.key)
    }
  }
}
</script>

<style scoped>

</style>

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
                @value="onValue($event)"
            ></component>
            <!-- <tt>[{{ this.currentType }}] {{ name}}: {{ value }}</tt>-->
        </td>
    </tr>
</template>

<script>
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
  methods: {
    onValue (newValue) {
      console.log('[EditField] New value: ', newValue)
      this.$emit('value', newValue)
    }
  },
  computed: {
    currentType () {
      const type = this.schema.type
      return type.substr(0, 1).toUpperCase() + type.substr(1) + 'Input'
    },

    name () {
      return this.schema.key
    }
  }
}
</script>

<style scoped>

</style>

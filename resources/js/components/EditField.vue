<template>
  <div>
    <div class="edit-label">
      <v-popover :auto-hide="true" trigger="click" v-if="helpText">
        <span style="border-bottom: 1px dotted #ccd; cursor: help">{{ schema.label }}</span>:
        <template slot="popover"><div v-html="helpText"></div></template>
      </v-popover>
      <span v-else>
        {{ schema.label }}:
      </span>
    </div>
    <div class="flex-grow-1">
      <component
        :is="currentType"
        :name="name"
        :value="value"
        :placeholder="placeholder"
        :schema="schema"
        :settings="settings"
        ref="component"
        @value="onValue($event)"
      ></component>
      <!-- <tt>[{{ this.currentType }}] {{ name}}: {{ value }}</tt>-->
    </div>
  </div>
</template>

<script>
import { get } from 'lodash/object'
import * as components from './input'

export default {
  name: 'edit-field',
  components: {
    ...components,
  },
  props: [
    'schema',
    'settings',
    'value',
  ],
  methods: {
    onValue (newValue) {
      console.log('[EditField] New value: ', newValue)
      this.$emit('value', newValue)
    },
    focus () {
      this.$refs.component.focus()
    },
  },
  computed: {
    helpText () {
      return get(this.schema, 'edit.help')
    },
    placeholder () {
      return get(this.schema, 'edit.placeholder')
    },
    currentType () {
      const type = this.schema.type
      return type.substr(0, 1).toUpperCase() + type.substr(1) + 'Input'
    },
    name () {
      return this.schema.key
    },
  },
}
</script>

<style scoped>

</style>

<template>
  <div :class="cssClass">

    <div class="d-flex mx-2">

        <div class="flex-shrink-0 pt-2 pr-2 text-right" style="width: 150px;" valign="top">
            {{ schema.label }}:
        </div>
        <div class="flex-grow-1">
            <component
                :is="currentType"
                :name="name"
                :value="value"
                :placeholder="placeholder"
                :schema="schema"
                :settings="settings"
                @value="onValue($event)"
            ></component>
            <div class="text-muted small d-inline-block">
              <span v-if="schema.edit.help">
                <em class="fa fa-info-circle"></em>
                {{ schema.edit.help }}
              </span>
            </div>
            <!-- <tt>[{{ this.currentType }}] {{ name}}: {{ value }}</tt>-->
        </div>
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
  },
  computed: {
    cssClass () {
      return get(this.schema, 'edit.cssClass') || 'col-md-6'
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

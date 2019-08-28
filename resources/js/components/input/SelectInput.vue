<template>
  <div>
    <div v-if="!values.length">
      <div v-if="failed" class="text-danger p-1">Det oppsto en feil. Prøv å laste siden på nytt.</div>
      <div v-else class="p-1">Et øyeblikk...</div>
    </div>
    <div v-else>
      <selectize
        :name="name"
        :settings="settings"
        :value="value"
        @input="onInput($event)"
      >
        <option v-for="val in values" :key="val.id" :value="val.id">{{ val.label }}</option>
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
    name: {
      type: String
    },
    schema: {
      type: Object
    },
    value: {
      type: Number
    },
  },
  data() {
    return {
      values: [],
      failed: false,
      settings: {
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
  mounted () {
    const url = get(this.schema, 'search.options.target');
    this.$http.get(url, {
      params: {
        field: this.schema.key
      }
    }).then(res => {
      this.values = res.data;
    }).catch(err => {
      this.failed = true;
    })
  },
  beforeDestroy () {
    //this.destroySelectize()
  },
  methods: {
    onInput ($event) {
      this.$emit('value', Number($event));
    }
  },
}
</script>

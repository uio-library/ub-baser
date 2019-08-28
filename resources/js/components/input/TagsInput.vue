<template>
    <div>
      <selectize
        multiple
        :name="name + '[]'"
        :settings="settings"
        :value="value"
        @input="onInput($event)"
      >
        <!-- eslint-disable-next-line vue/require-v-for-key -->
        <option v-for="item in value" :value="item">{{ item }}</option>
      </selectize>
    </div>
</template>

<script>
import { get } from 'lodash/object'
import Selectize from 'vue2-selectize'

export default {
  name: 'tags-input',
  components: {
    Selectize,
  },
  props: {
    name: String,
    schema: Object,
    value: Array,
  },
  data () {
    return {
      settings: {
        create: true,
        preload: true,
        labelField: 'value',
        valueField: 'value',
        searchField: 'value',
        openOnFocus: true,
        closeAfterSelect: true,
        load: (query, callback) => {
          const url = get(this.schema, 'searchOptions.autocompleteUrl')
          this.$http.get(url, {
            params: {
              field: this.schema.key,
              q: query,
            },
          })
            .then(res => callback(res.data))
            .catch(() => callback([]))
        },
        render: {
          option_create: (data, escape) => `<div class="create">Opprett «${escape(data.input)}»</div>`,
        },
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

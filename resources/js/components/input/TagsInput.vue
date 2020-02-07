<template>
    <div>
      <selectize
        multiple
        :name="name + '[]'"
        :settings="selectizeSettings"
        :value="value"
        :placeholder="placeholder"
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
    settings: Object,
    value: Array,
  },
  data () {
    return {
      placeholder: get(this.schema, 'edit.placeholder', ''),
      selectizeSettings: {
        create: true,
        preload: true,
        labelField: 'value',
        valueField: 'value',
        searchField: 'value',
        openOnFocus: true,
        closeAfterSelect: true,
        load: (query, callback) => {
          const url = get(this.settings, 'baseUrl') + '/autocomplete'

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

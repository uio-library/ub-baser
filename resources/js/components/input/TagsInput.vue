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
import Selectize from '../wrappers/Selectize'
import axios from 'axios'

const http = axios.create({
  timeout: 2000,
  headers: {}
})

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
    let preload = get(this.schema, 'edit.preload', false)
    return {
      placeholder: get(this.schema, 'edit.placeholder', ''),
      selectizeSettings: {
        create: get(this.schema, 'edit.allow_new_values', true),
        preload: preload,
        valueField: 'prefLabel',
        searchField: ['prefLabel', 'altLabel'],
        openOnFocus: preload,
        closeAfterSelect: true,
        load: this.autocomplete.bind(this),
        render: {
          option: this.renderOption.bind(this),
          item: this.renderItem.bind(this),
          option_create: (data, escape) => `<div class="create">Opprett «${escape(data.input)}»</div>`,
        },
      },
    }
  },
  methods: {
    source () {
      return get(this.schema, 'edit.remote_source') || {
        url: get(this.settings, 'baseUrl') + `/autocomplete?field=${this.schema.key}&q={QUERY}`
      }
    },
    onInput ($event) {
      this.$emit('value', $event)
    },
    renderOption(data, escape) {
      if (data.altLabel) {
        return `<div class="option"><em>${escape(data.altLabel)}</em> → ${escape(data.prefLabel)}</div>`
      }
      return `<div class="option">${escape(data.prefLabel)}</div>`
    },
    renderItem(data, escape) {
      return `<div>${escape(data.prefLabel)}</div>`
    },
    autocomplete(query, callback) {
      http.get(this.source().url.replace('{QUERY}', query))
        .then(res => {
          console.log('GOT DATA', res.data)
          callback(res.data.results)
        })
        .catch(() => callback([]))
    }
  },
}
</script>

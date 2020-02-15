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
        <option v-for="value in values"
          :key="value.id"
          :value="value.id"
          :data-extra="value.extra">{{ value.prefLabel }}</option>
      </selectize>
    </div>
</template>

<script>
import { get } from 'lodash/object'
import Selectize from '../wrappers/Selectize'
import axios from 'axios'
import {cloneDeep} from "lodash/lang";

const http = axios.create({
  timeout: 10000,
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

    let values = cloneDeep(this.schema.values) || []
    values.forEach(val => val.extra = JSON.stringify({invalid: false}))
    if (this.value) {
      this.value.forEach(val => {
        if (values.map(x => x.id).indexOf(String(val)) === -1) {
          const invalid = this.schema.values && this.schema.values.length > 0
          values.push({ id: val, prefLabel: val, extra: JSON.stringify({invalid: invalid}) })
        }
      })
    }

    let data = {
      values: values,
      placeholder: get(this.schema, 'edit.placeholder', ''),
      selectizeSettings: {
        create: get(this.schema, 'edit.allow_new_values', true),
        valueField: 'id',
        labelField: 'prefLabel',
        searchField: ['prefLabel', 'altLabel'],
        closeAfterSelect: true,
        dataAttr: 'data-extra',
        render: {
          option: this.renderOption.bind(this),
          item: this.renderItem.bind(this),
          option_create: (data, escape) => `<div class="create">Opprett «${escape(data.input)}»</div>`,
        },
      },
    }

    if (this.schema.values === undefined) {
      data.selectizeSettings.load = this.autocomplete.bind(this)
      data.selectizeSettings.preload = get(this.schema, 'edit.preload', false)
    }
    if (data.selectizeSettings.preload) {
      data.selectizeSettings.openOnFocus = true
    } else {
      data.selectizeSettings.openOnFocus = false
    }
    return data
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
      if (data.count) {
        return `<div class="option">${escape(data.prefLabel)} (${escape(data.count)})</div>`
      }
      return `<div class="option">${escape(data.prefLabel)}</div>`
    },
    renderItem(data, escape) {
      if (data.invalid) {
        return `<div class="text-danger">${escape(data.prefLabel)}</div>`
      }
      return `<div>${escape(data.prefLabel)}</div>`
    },
    autocomplete(query, callback) {
      http.get(this.source().url.replace('{QUERY}', query))
        .then(res => {
          callback(res.data.results.map(res => ({
            id: res.prefLabel,
            prefLabel: res.prefLabel,
            count: res.count,
          })))
        })
        .catch(err => {
          console.log('Autocomplete aborted', err)
          callback([])
        })
    }
  },
}
</script>

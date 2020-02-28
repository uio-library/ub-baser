<template>
  <div>
    <selectize
      :multiple="multiple"
      :name="inputName"
      :settings="selectizeSettings"
      :value="currentValue"
      :placeholder="placeholder"
      @initialize="onInitialize"
      @change="onChange"
      @load="onLoad"
    >
      <option v-if="!multiple" value="">{{ placeholder }}</option>
      <option v-for="option in options"
              :key="option.value"
              :value="option.value"
              :data-extra="option.extra">{{ option.prefLabel }}</option>
    </selectize>
  </div>
</template>

<script>
import { get } from 'lodash/object'
import Selectize from '../wrappers/Selectize'
import {cloneDeep} from "lodash/lang";
import axios from 'axios'

const http = axios.create({
  timeout: 10000,
  headers: {},
})

export default {
  name: 'select-input',
  components: {
    Selectize,
  },
  props: {
    name: String,
    schema: Object,
    settings: Object,
    value: String | Array,
    placeholder: String,
  },
  computed: {
    multiple () {
      return get(this.schema, 'multiple', false)
    },
    inputName () {
      if (this.multiple) {
        return this.name + '[]'
      }
      return this.name
    },
  },
  data () {
    let options = cloneDeep(this.schema.values) || []
    let preload = get(this.schema, 'edit.preload', false)
    options.forEach(val => val.extra = JSON.stringify({invalid: false}))

    let canCreate = false
    if (get(this.schema, 'edit.allow_new_values', false)) {
      canCreate = (input) => ({
        value: input,
        prefLabel: input,
        invalid: false,
      })
    }

    let selectizeSettings = {
      // Ref: https://github.com/selectize/selectize.js/blob/master/docs/usage.md
      create: canCreate,
      valueField: 'value',
      labelField: 'prefLabel',
      searchField: ['prefLabel', 'altLabel'],
      openOnFocus: false,
      closeAfterSelect: true,
      dataAttr: 'data-extra',
      render: {
        option: this.renderOption.bind(this),
        item: this.renderItem.bind(this),
        option_create: this.renderOptionCreate.bind(this),
      },
    }

    if (this.schema.values === undefined) {
      selectizeSettings.load = this.autocomplete.bind(this)
      selectizeSettings.preload = preload
      if (selectizeSettings.preload) {
        selectizeSettings.openOnFocus = true
      }
    } else {
      selectizeSettings.openOnFocus = true
    }
    return {
      currentValue: null,
      preload: preload,
      preloadComplete: false,
      options: options,
      selectizeSettings: selectizeSettings,
    }
  },
  methods: {
    componentReady (selectize) {
      const options = Object.keys(selectize.getSelectize().options)
      // console.log(this.name, options)
      if (this.value) {
        const value = (typeof this.value === 'string') ? [this.value] : this.value
        value.forEach(val => {
          if (options.indexOf(String(val)) === -1) {
            console.log(`Add value «${val}» to '${this.name}'.`)
            const invalid = (this.schema.values && this.schema.values.length > 0)
            selectize.getSelectize().addOption({
              value: val,
              prefLabel: val,
              invalid: invalid,
            })
          }
        })
      }
      this.currentValue = this.value
    },
    source () {
      return get(this.schema, 'edit.remote_source') || {
        url: get(this.settings, 'baseUrl') + `/autocomplete?field=${this.schema.key}&q={QUERY}`
      }
    },
    renderOptionCreate(data, escape) {
      return `<div class="option create"><em>Opprett «${escape(data.input)}»</em></div>`
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
        .then(res => callback(res.data.results.map(res => {
          if (res.value === undefined) {
            res.value = res.prefLabel
          }
          return res
        })))
        .catch(err => {
          console.log('Autocomplete aborted', err)
          callback([])
        })
    },
    onChange(value) {
      this.$emit('value', value)
    },
    onInitialize(selectize) {
      if (!this.preload) {
        this.componentReady(selectize)
      }
    },
    onLoad(data, selectize) {
      if (this.preload && !this.preloadComplete) {
        this.preloadComplete = true
        this.componentReady(selectize)
      }
    },
  },
}
</script>

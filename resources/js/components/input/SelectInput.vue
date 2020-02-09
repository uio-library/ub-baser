<template>
  <div>
    <div v-if="state === 'failed'" class="text-danger p-1">{{ $t('messages.unknown_error') }}</div>
    <div v-else-if="state === 'loading'" class="p-1">{{ $t('messages.please_wait') }}</div>
    <div v-else-if="state === 'empty'" class="p-1">{{ $t('messages.no_values') }}</div>
    <div v-else>
      <selectize
        :name="name"
        :settings="selectSettings"
        :value="value"
        :placeholder="placeholder"
        @input="onInput($event)"
      >
        <option v-for="option in options" :key="option.value" :value="option.value">{{ option.prefLabel }}</option>
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
    name: String,
    schema: Object,
    settings: Object,
    value: String,
  },
  data () {
    return {
      options: [],
      placeholder: get(this.schema, 'edit.placeholder', ''),
      state: 'loading',
      selectSettings: {
        // Ref: https://github.com/selectize/selectize.js/blob/master/docs/usage.md
        create: get(this.schema, 'edit.allow_new_values', false),
        valueField: 'value',
        labelField: 'label',
        searchField: 'label',
        openOnFocus: true,
        closeAfterSelect: true,
        render: {
          option: function(item, escape) {
            return `<div class="option">${escape(item.label || item.value)}</div>`
          },
          item: function(item, escape) {
            return `<div>${escape(item.label || item.value)}</div>`
          },
        },

        onOptionAdd: function(item) {
          console.log('YO', item)
        }
      },
    }
  },
  mounted () {
    const url = get(this.settings, 'baseUrl') + '/autocomplete'
    this.$http.get(url, {
      params: {
        field: this.schema.key,
      },
    }).then(res => {
      this.options = res.data.results.map(res => {
        if (res.value === undefined) {
          res.value = res.prefLabel
        }
        return res
      })
      if (!this.options.length) {
        this.state = 'empty'
      } else {
        this.state = 'ok'
      }
    }).catch(() => {
      this.state = 'failed'
    })
  },
  methods: {
    onInput ($event) {
      this.$emit('value', $event)
    },
  },
}
</script>

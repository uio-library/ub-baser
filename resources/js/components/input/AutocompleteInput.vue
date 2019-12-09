<template>
    <div class="autocomplete-input">
        <input type="text"
              class="form-control"
              autocomplete="off"
              ref="input"
              :name="name"
              :value="value"
              :placeholder="placeholder"
              @input="$emit('value', $event.target.value)"
        >
        <span class="autocomplete-icon" v-if="working">
          <em class="fa fa-circle-o-notch fa-spin fa-fw"></em>
        </span>
    </div>
</template>

<style>

.autocomplete-input {
  position: relative;
}
.autocomplete-icon {
  position: absolute;
  color: green;
  top: 4px;
  right: 6px;
  z-index: 10;
  font-size: 130%;
}
</style>
<script>
import autocomplete from 'autocomplete.js'
import { get } from 'lodash/object'

let search = null

export default {
  name: 'autocomplete-input',
  props: {
    name: String,
    schema: Object,
    value: String,
  },
  data () {
    return {
      working: false,
    }
  },
  computed: {
    placeholder () {
      return get(this.schema, 'searchOptions.placeholder')
    },
  },
  mounted () {
    this.initAutocomplete()
  },
  beforeDestroy () {
    if (search) {
      search.autocomplete.destroy()
      search = null
    }
  },
  methods: {
    initAutocomplete () {
      let cancel = null

      const url = get(this.schema, 'searchOptions.autocompleteUrl')
      search = autocomplete(this.$refs.input, {}, [
        {
          source: (query, callback) => {
            this.working = true
            if (cancel) {
              cancel.cancel()
            }
            cancel = this.$http.CancelToken.source()

            this.$http.get(url, {
              cancelToken: cancel.token,
              params: {
                field: this.schema.key,
                q: query,
              },
            })
              .then(
                res => {
                  this.working = false
                  callback(res.data)
                },
                err => {
                  if (!this.$http.isCancel(err)) {
                    this.working = false
                  }
                  callback([])
                }
              )
          },
        },
      ])
        .on('autocomplete:selected', (event, suggestion, dataset, context) => {
          this.$emit('value', suggestion.value)
          this.$emit('selected', suggestion)
        })
        .on('autocomplete:autocompleted', (event, suggestion, dataset, context) => {
          this.$emit('value', suggestion.value)
          this.$emit('selected', suggestion)
        })
    },

    focus () {
      this.$refs.input.focus()
    },
  },
}
</script>

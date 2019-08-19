<template>
    <div>
        <select multiple :id="'select_' + name" :name="name + '[]'">
            <option v-for="item in value" :value="item" selected="selected">{{ item }}</option>
        </select>
    </div>
</template>

<script>
import { get } from 'lodash/object'

export default {
  name: 'tags-input',
  props: {
    name: String,
    schema: Object,
    value: Array
  },
  mounted () {
    this.initSelectize()
  },
  beforeDestroy () {
    this.destroySelectize()
  },
  methods: {
    initSelectize () {
      const url = get(this.schema, 'search.options.target')
      $(`#select_${this.name}`).selectize({
        create: true,
        preload: true,
        labelField: 'value',
        valueField: 'value',
        searchField: 'value',
        openOnFocus: true,
        closeAfterSelect: true,
        load: (query, callback) => {
          this.$http.get(url, {
            params: {
              field: this.schema.key,
              q: query
            }
          }).then(
            res => callback(res.data),
            err => callback([])
          )
        },
        render: {
          option_create: (data, escape) => `<div class="create">Opprett «${escape(data.input)}»</div>`
        }
      })
    },
    destroySelectize () {
      // Note that selectize is not completely destroyed: https://github.com/selectize/selectize.js/issues/1257
      // Should not be an issue for us since we're not making a SPA.
      $(`#select_${this.name}`).selectize()[0].selectize.destroy()
    }
  }
}
</script>

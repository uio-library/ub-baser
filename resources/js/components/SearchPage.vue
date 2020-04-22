<template>
  <div>

    <div>
      searchParams: {{ params }}
    </div>

    <div>
      query: {{ query }}
    </div>

    <div>
      order: {{ order }}
    </div>

    DEF-ORDER: {{ defaultOrder }}

    <search-form
      :initial-query="query"
      :schema="schema"
      :settings="settings"
      :advanced-search="advancedSearch"
      @submit="onSubmit($event)"
    ></search-form>

    <div v-if="params">
      <component
        :is="resultsComponent"
        :prefix="baseId"
        :base-url="baseUrl"
        :schema="schema"
        :default-columns="defaultColumns"
        :default-order="defaultOrder"
        :search-params="params"
        :initial-order="order"
        @order="onOrder($event)"
      ></component>
    </div>

  </div>
</template>

<script>

import { pick } from 'lodash/object'
import { cloneDeep } from 'lodash/lang'
import searchParams from './SearchStateService'

export default {

  name: "SearchPage",

  props: {
    schema: Object,
    // Search form
    settings: Object,
    // initialQuery: Array,
    advancedSearch: Boolean,
    // Results
    resultsComponent: String,
    baseId: String,
    baseUrl: String,
    defaultColumns: Array,
    defaultOrder: Array,
    // initialOrder: Array,
  },

  data() {
    searchParams.init(window.location.search)

    return {
      query: searchParams.parseQuery(),
      order: searchParams.parseOrder(),
      params: pick(searchParams.asObject(), ['q', 'advanced']),
    }
  },

  mounted() {
    window.onpopstate = (event) => {
      console.log('POP state', window.location.search)
      // Since DataTable is not really reactive, we re-load it
      searchParams.init(window.location.search)
      this.params = null
      this.$nextTick(() => {
        this.query = searchParams.parseQuery()
        this.order = searchParams.parseOrder()
        this.params = pick(searchParams.asObject(), ['q', 'advanced'])
      })
    }
  },

  methods: {

    onSubmit(evt) {
      if (evt.updateHistory) {
        searchParams.setQuery(evt.search)
      }

      // Since DataTable is not really reactive, we re-load it
      this.params = null
      this.$nextTick(() => {
        this.params = pick(searchParams.asObject(), ['q', 'advanced'])
      })
    },

    onOrder(value) {
      console.log('onOrder', value)
      searchParams.setOrder(value)
    }
  },
}
</script>

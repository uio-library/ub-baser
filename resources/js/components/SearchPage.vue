<template>
  <div>

    <search-form
      :initial-query="initialQuery"
      :schema="schema"
      :settings="settings"
      :advanced-search="advancedSearch"
      @submit="onSubmit($event)"
    ></search-form>

    <div v-if="query">
      <component
        :is="resultsComponent"
        :prefix="baseId"
        :base-url="baseUrl"
        :schema="schema"
        :default-columns="defaultColumns"
        :default-order="defaultOrder"
        :initial-order="order"
        :query="query"
        @order="onOrder($event)"
      ></component>
    </div>

  </div>
</template>

<script>

import { cloneDeep } from 'lodash/lang'
export default {

  name: "SearchPage",

  props: {
    schema: Object,
    // Search form
    settings: Object,
    initialQuery: Array,
    advancedSearch: Boolean,
    // Results
    resultsComponent: String,
    baseId: String,
    baseUrl: String,
    defaultColumns: Array,
    defaultOrder: Array,
    initialOrder: Array,
  },

  data() {
    return {
      query: null,
      order: cloneDeep(this.initialOrder)
    }
  },

  methods: {
    pushState(query) {
      let searchParams = new URLSearchParams(window.location.search)
      Object.keys(query).forEach(q => {
        if (query[q] === '') {
          searchParams.delete(q)
        } else {
          searchParams.set(q, query[q])
        }
      })
      let params = searchParams.toString()
      if (params) params = '?' + params

      window.history.pushState(
        null,
        '',
        window.location.pathname + params
      )
    },

    onSubmit(query) {
      this.pushState(query)
      this.query = null
      this.$nextTick(() => {
        this.query = cloneDeep(query)
      })
    },

    onOrder(query) {
      if (!query.order) {
        this.order = cloneDeep(this.initialOrder)
      } else {
        this.order = query.order.split(',').map(item => {
          const t = item.split(':')
          return {key: t[0], direction: t.length ? t[1] : 'asc'}
        })
      }
      this.pushState(query)
    }
  },
}
</script>

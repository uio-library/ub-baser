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
        :query="query"
        @order="onOrder($event)"
      ></component>
    </div>

    <!--:order="{{ json_encode($order) }}"
    # :query="{{ json_encode($query, JSON_FORCE_OBJECT) }}"-->

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
  },

  data: () => {
    return {
      query: null
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
      this.pushState(query)
    }
  },
}
</script>

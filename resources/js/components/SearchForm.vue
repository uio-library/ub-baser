<template>
  <div>
    <form id="searchForm" method="GET" :action="baseUrl" class="pb-3 search-form" @submit.prevent="onSubmit()">

        <diy v-if="error" class="alert alert-danger">
            {{ error }}
        </diy>

        <search-field
                v-for="(field, fieldIndex) in query"
                :key="field.key"
                class="form-group field-set"
                :data-index="fieldIndex"
                :advanced="advanced"
                :index="fieldIndex"
                :schema="schema"
                :settings="settings"

                :field="field.field"
                @field="field.field = $event"

                :operator="field.operator"
                @operator="field.operator = $event"

                :value="field.value"
                @value="field.value = $event"

                :boolean="field.boolean"
                @boolean="field.boolean = $event"

                :is-last="fieldIndex === query.length - 1"

                @addField="addField()"
        >
        </search-field>

        <div class="d-flex py-1">
            <div class="flex-grow-1">
                <button type="submit" class="btn btn-primary"><em class="zmdi zmdi-search"></em> {{ __('messages.search') }}</button>
                <a :href="baseUrl" class="btn btn-secondary">{{ __('messages.reset') }}</a>
            </div>

            <div class="flex-grow-0">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="customSwitch1" name="advanced" v-model="advanced">
                    <label class="custom-control-label" for="customSwitch1">{{ __('messages.advanced_search') }}</label>
                </div>
            </div>
        </div>
    </form>
  </div>
</template>

<script>
import SearchField from './SearchField'
import { get } from 'lodash/object'

export default {
  name: 'search-form',

  components: {
    SearchField,
  },

  props: {
    settings: Object,
    initialQuery: Array,
    schema: Object,
    advancedSearch: Boolean,
  },

  computed: {

    baseUrl () {
      return get(this.settings, 'baseUrl')
    },

    allFields () {
      const fields = []
      this.schema.fields.forEach(field => fields.push(field))
      this.schema.groups.forEach(fieldGroup => {
        fieldGroup.fields.forEach(field => fields.push(field))
      })
      return fields
    },

  },

  data () {
    return {
      error: null,
      advanced: this.advancedSearch,
      query: this.initialQuery.map(x => ({
        field: x.field,
        operator: x.operator,
        value: x.value,
        boolean: x.boolean,
      })),
    }
  },

  methods: {
    addField () {
      if (this.query.length) {
        const lastField = this.query[this.query.length - 1]
        this.query.push({ field: lastField.field, operator: lastField.operator, value: '', boolean: 'and' })
      } else {
        const enabled = this.allFields.filter(field => field.search.enabled)
        this.query.push({ field: enabled[0].key, operator: enabled[0].search.operators[0], value: '', boolean: 'and' })
      }
    },
    onSubmit () {
      let query = this.query
        .filter(q => q.value !== '' || ['isnull', 'notnull'].indexOf(q.operator) !== -1)
        .map(q => `${q.field} ${q.operator} ${q.value} ${q.boolean.toUpperCase()}`)
        .join(' ')
      if (query.endsWith(' AND')) query = query.substr(0, query.length - 4)
      if (query.endsWith(' OR')) query = query.substr(0, query.length - 3)

      this.$emit('submit', {
        q: query,
        advanced: this.advanced ? 'true' : '',
      })
    }
  },

  mounted () {
    if (!this.settings) {
      this.error = `SearchForm is missing a value for the property: settings`
    }
    if (!this.schema) {
      this.error = `SearchForm is missing a value for the property: schema`
    }
    if (!this.query.length) {
      this.addField()
    }
    this.onSubmit()
  },
}
</script>

<style scoped lang="sass">
label
    font-weight: normal

</style>

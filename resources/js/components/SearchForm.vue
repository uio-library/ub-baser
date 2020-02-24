<template>
    <form id="searchForm" method="GET" :action="baseUrl" class="pb-3 search-form">

        <div v-if="error" class="alert alert-danger">
            {{ error }}
        </div>

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
        >
            <button v-if="fieldIndex == query.length - 1"
                    type="button"
                    class="btn btn-primary"
                    id="addFieldButton"
                    @click="addField()"
                    style="width: 4.4rem"
            ><em class="fa fa-plus"></em></button>

            <select v-else
              style="width: 4.4rem"
              class="custom-select field-select"
              :name="`c${fieldIndex}`"
            >
              <option value="and">{{ $t('messages.and') }}</option>
              <option value="or">{{ $t('messages.or') }}</option>
            </select>
        </search-field>

        <div class="d-flex py-1">
            <div class="flex-grow-1">
                <button type="submit" class="btn btn-primary"><em class="zmdi zmdi-search"></em> {{ $t('messages.search') }}</button>
                <a :href="baseUrl" class="btn btn-secondary">{{ $t('messages.reset') }}</a>
            </div>

            <div class="flex-grow-0">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="customSwitch1" name="advanced" v-model="advanced">
                    <label class="custom-control-label" for="customSwitch1">{{ $t('messages.advanced_search') }}</label>
                </div>
            </div>
        </div>
    </form>
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
      })),
    }
  },

  methods: {
    addField () {
      if (this.query.length) {
        const lastField = this.query[this.query.length - 1]
        this.query.push({ field: lastField.field, operator: lastField.operator, value: '' })
      } else {
        const enabled = this.allFields.filter(field => field.search.enabled)
        this.query.push({ field: enabled[0].key, operator: enabled[0].search.operators[0], value: '' })
      }
    },

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
  },
}
</script>

<style scoped lang="sass">
label
    font-weight: normal

</style>

<template>
    <form id="searchForm" method="GET" :action="baseUrl" class="pb-3">

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
                :operators="operators"
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
                    style="width:50px"
            ><em class="fa fa-plus"></em></button>

            <button v-else
                    type="button"
                    disabled
                    class="btn"
                    style="width:50px"
            >og</button>
        </search-field>

        <div class="d-flex py-1">
            <div class="flex-grow-1">
                <button type="submit" class="btn btn-primary"><em class="zmdi zmdi-search"></em> Søk</button>
                <a :href="baseUrl" class="btn btn-secondary">Nullstill</a>
            </div>

            <div class="flex-grow-0">
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="customSwitch1" name="advanced" v-model="advanced">
                    <label class="custom-control-label" for="customSwitch1">Avansert søk</label>
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

    firstSearchField () {
      for (let i = 0; i < this.allFields.length; i++) {
        if (this.allFields[i].searchable !== 'disabled') {
          return this.allFields[i]
        }
      }
      throw new Error('Found no search fields!')
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
      operators: [
        { label: 'lik', value: 'ex' },
        { label: 'inneholder', value: 'eq' },
        { label: 'inneholder ikke', value: 'neq' },
        { label: 'mangler verdi', value: 'isnull' },
        { label: 'har verdi', value: 'notnull' },
      ],
    }
  },

  methods: {

    addField () {
      console.log(this.firstSearchField)
      this.query.push({
        field: this.firstSearchField.key,
        operator: get(this.firstSearchField, 'searchOptions.operators.0', 'eq'),
        value: '',
      })
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

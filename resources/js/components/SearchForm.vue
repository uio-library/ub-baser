<template>
    <form id="searchForm" method="GET" :action="action" class="pb-3">

        <table style="width: 100%">
            <search-field
                    v-for="(field, fieldIndex) in query"
                    :key="field.key"
                    class="form-group field-set"
                    :data-index="fieldIndex"
                    :advanced="advanced"
                    :index="fieldIndex"
                    :schema="schema"
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
                ><i class="fa fa-plus"></i></button>

                <button v-else
                        type="button"
                        disabled
                        class="btn"
                        style="width:50px"
                >og</button>
            </search-field>
        </table>

        <div class="d-flex py-1">
            <div class="flex-grow-1">
                <button type="submit" class="btn btn-primary"><i class="zmdi zmdi-search"></i> Søk</button>
                <a :href="action" class="btn btn-secondary">Nullstill</a>
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

export default {
  name: 'search-form',

  components: {
    SearchField
  },

  props: {
    action: String,
    initialQuery: Array,
    schema: Object,
    advancedSearch: Boolean
  },

  computed: {

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
        if (this.allFields[i].search !== false) {
          return this.allFields[i].key
        }
      }
      throw new Error('Found no search fields!')
    }

  },

  data () {
    return {
      advanced: this.advancedSearch,
      query: this.initialQuery.map(x => ({
        field: x.field,
        operator: x.operator,
        value: x.value
      })),
      operators: [
        { label: 'matcher', value: 'eq' },
        { label: 'matcher ikke', value: 'neq' },
        { label: 'mangler verdi', value: 'isnull' },
        { label: 'har verdi', value: 'notnull' }
      ]
    }
  },

  methods: {

    addField () {
      this.query.push({
        field: this.firstSearchField,
        operator: 'eq',
        value: ''
      })
    }

  },

  mounted () {
    if (!this.query.length) {
      this.addField()
    }
  }
}
</script>

<style scoped lang="sass">
label
    font-weight: normal

</style>

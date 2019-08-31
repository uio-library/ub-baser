<template>
    <div>

        <!-- Top level fields -->
        <table class="table table-borderless table-sm">
            <edit-field
                    v-for="field in fields"
                    :key="field.key"
                    :schema="schemas[field.key]"
                    :value="currentValues[field.key]"
                    @value="onValue(field.key, $event)"
            ></edit-field>
        </table>

        <!-- Grouped fields -->
        <div class="panel panel-default" v-for="group in groups" :key="group.label">
            <div class="panel-heading">
                <h4 class="panel-title">{{ group.label }}</h4>
            </div>
            <div class="panel-body">
                <table class="table table-borderless table-sm">
                    <edit-field
                            v-for="field in group.fields"
                            :key="field.key"
                            :schema="schemas[field.key]"
                            :value="currentValues[field.key]"
                            @value="onValue(field.key, $event)"
                    ></edit-field>
                </table>
            </div>
            <!--{{ json_encode( old($field['key'], $record->{$field['key']}) ) }}-->
        </div>

    </div>
</template>

<script>
import EditField from './EditField'
import { cloneDeep } from 'lodash/lang'

export default {
  name: 'edit-form',
  components: {
    EditField,
  },
  props: {
    schema: {
      type: Object,
    },
    values: {
      type: Object,
    },
  },
  computed: {
    fields () {
      return this.schema.fields.filter(field => field.editable)
    },
    groups () {
      return this.schema.groups.map(group => ({
        label: group.label,
        fields: group.fields.filter(field => field.editable),
      }))
    },
    schemas () {
      let out = this.fields.reduce((out, field) => { out[field.key] = field; return out }, {})
      this.groups.forEach(fieldGroup => {
        out = fieldGroup.fields.reduce((out, field) => { out[field.key] = field; return out }, out)
      })
      return out
    },
  },
  data () {
    const values = cloneDeep(this.values)

    // Cast all integers as strings (@see SelectInput)
    Object.keys(values).forEach(key => {
      if (typeof values[key] === 'number') {
        values[key] = String(values[key])
      }
    })

    return {
      currentValues: values,
    }
  },
  methods: {
    onValue (key, newValue) {
      console.log('[EditForm] Changed: ', key, newValue)
      this.currentValues[key] = newValue
    },
  },
}
</script>

<template>
    <div>

        <div v-if="error" class="alert alert-danger">
            {{ error }}
        </div>

        <!-- Top level fields -->
          <div class="row no-gutters">
            <edit-field
                    v-for="field in fields"
                    :key="field.key"
                    :class="'edit-field toplevel-field ' + cssClasses[field.key]"
                    :schema="schemas[field.key]"
                    :settings="settings"
                    :value="currentValues[field.key]"
                    @value="onValue(field.key, $event)"
            ></edit-field>
        </div>

        <!-- Grouped fields -->
        <div class="panel panel-default" v-for="group in groups" :key="group.label">
            <div class="panel-heading">
                <h4 class="panel-title" style="border-bottom: 1px solid #666">{{ group.label }}</h4>
            </div>
            <div class="row no-gutters">
                <edit-field
                    v-for="field in group.fields"
                    :key="field.key"
                    :class="'edit-field toplevel-field ' + cssClasses[field.key]"
                    :schema="schemas[field.key]"
                    :settings="settings"
                    :value="currentValues[field.key]"
                    @value="onValue(field.key, $event)"
                ></edit-field>
            </div>
            <!--{{ json_encode( old($field['key'], $record->{$field['key']}) ) }}-->
        </div>

    </div>
</template>

<script>
import { get } from 'lodash/object'
import { cloneDeep } from 'lodash/lang'
import EditField from './EditField'

export default {
  name: 'edit-form',
  components: {
    EditField,
  },
  props: {
    schema: {
      type: Object,
    },
    settings: {
      type: Object,
    },
    values: {
      type: Object,
    },
  },
  computed: {
    fields () {
      return this.schema.fields.filter(field => field.edit.enabled)
    },
    groups () {
      return this.schema.groups.map(group => ({
        label: group.label,
        fields: group.fields.filter(field => field.edit.enabled),
      }))
    },
    schemas () {
      let out = this.fields.reduce((out, field) => { out[field.key] = field; return out }, {})
      this.groups.forEach(fieldGroup => {
        out = fieldGroup.fields.reduce((out, field) => { out[field.key] = field; return out }, out)
      })
      return out
    },
    cssClasses () {
      let out = {}
      Object.keys(this.schemas).forEach(key => {
        out[key] = get(this.schemas[key], 'edit.cssClass') || 'col-md-6'
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
      error: null,
      currentValues: values,
    }
  },
  methods: {
    onValue (key, newValue) {
      console.log('[EditForm] Changed: ', key, newValue)
      this.currentValues[key] = newValue
    },
  },
  mounted () {
    if (!this.settings) {
      this.error = `EditForm is missing a value for the property: settings`
    }
    if (!this.schema) {
      this.error = `EditForm is missing a value for the property: schema`
    }
  },
}
</script>

<template>
  <form method="POST" :action="action" class="edit-form">

    <input type="hidden" name="_token" :value="csrfToken">
    <input type="hidden" name="_method" :value="method" v-if="method == 'PUT'">

    <div v-if="error" class="alert alert-danger">
      {{ error }}
    </div>

    <!-- Top level fields -->
    <div class="row no-gutters">
      <edit-field
        v-for="field in fields"
        :key="field.key"
        :class="['edit-field', 'toplevel-field', field.type]"
        :schema="schemas[field.key]"
        :settings="settings"
        :value="currentValues[field.key]"
        @value="onValue(field.key, $event)"
      ></edit-field>
    </div>

    <!-- Grouped fields -->
    <div class="panel panel-default" v-for="group in groups" :key="group.label">
      <div class="panel-heading">
        <h3 class="panel-title" style="border-bottom: 1px solid #666">{{ group.label }}</h3>
      </div>
      <div class="row no-gutters">
        <edit-field
          v-for="field in group.fields"
          :key="field.key"
          :class="['edit-field', 'toplevel-field', field.type]"
          :schema="schemas[field.key]"
          :settings="settings"
          :value="currentValues[field.key]"
          @value="onValue(field.key, $event)"
        ></edit-field>
      </div>
      <!--{{ json_encode( old($field['key'], $record->{$field['key']}) ) }}-->
    </div>

    <div class="form-group">
      <div class="col-sm-10">
        <button type="submit" class="btn btn-primary" v-if="method == 'PUT'">{{ $t('messages.update') }}</button>
        <button type="submit" class="btn btn-primary" v-else>{{ $t('messages.create') }}</button>
      </div>
    </div>

    <div style="height: 100px;">
      <!-- Some spacing for menus -->
    </div>

  </form>
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
    method: {
      type: String
    },
    action: {
      type: String
    },
    csrfToken: {
      type: String
    },
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

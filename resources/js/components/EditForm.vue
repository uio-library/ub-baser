<template>
    <div>

        <!-- Top level fields -->
        <table class="table table-borderless table-sm">
            <edit-field
                    v-for="field in schema.fields"
                    v-if="field.editable"
                    :key="field.key"
                    :schema="schemas[field.key]"
                    :value="currentValues[field.key]"
                    @value="onValue(field.key, $event)"
            ></edit-field>
        </table>

        <!-- Grouped fields -->
        <div class="panel panel-default" v-for="group in schema.groups" :key="group.label" v-if="group.display !== false">
            <div class="panel-heading">
                <h4 class="panel-title">{{ group.label }}</h4>
            </div>
            <div class="panel-body">
                <table class="table table-borderless table-sm">
                    <edit-field
                            v-for="field in group.fields"
                            v-if="field.editable"
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
    EditField
  },
  props: {
    schema: {
      type: Object
    },
    values: {
      type: Object
    }
  },
  data() {
    return {
      currentValues () {
        let values = cloneDeep(this.values)

        // Cast all integers as strings (@see SelectInput)
        Object.keys(values).forEach(key => {
          if (typeof values[key] === 'number') {
            values[key] = String(values[key])
          }
        })

        return values
      }
    }
  },
  methods: {
    onValue(key, newValue) {
      console.log('[EditForm] Changed: ', key, newValue);
      this.currentValues[key] = newValue;
    }
  },
  computed: {
    schemas () {
      const out = []
      this.schema.fields.forEach(col => out[col.key] = col)
      this.schema.groups.forEach(fieldGroup => {
        fieldGroup.fields.forEach(col => out[col.key] = col)
      })
      return out
    }
  }
}
</script>

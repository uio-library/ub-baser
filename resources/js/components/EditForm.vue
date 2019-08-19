<template>
    <div>

        <!-- Top level fields -->
        <table class="table table-borderless table-sm">
            <edit-field
                    v-for="field in schema.fields"
                    v-if="field.edit !== false"
                    :key="field.key"
                    :schema="schemas[field.key]"
                    :value="values[field.key]"
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
                            v-if="field.edit !== false"
                            :key="field.key"
                            :schema="schemas[field.key]"
                            :value="values[field.key]"
                    ></edit-field>
                </table>
            </div>
            <!--{{ json_encode( old($field['key'], $record->{$field['key']}) ) }}-->
        </div>

    </div>
</template>

<script>
import EditField from './EditField'
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

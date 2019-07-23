<template>
    <div>
        <div class="panel panel-default" v-for="group in columns" :key="group.label" v-if="group.display !== false">
            <div class="panel-heading">
                <h3 class="panel-title">{{ group.label }}</h3>
            </div>
            <table class="table">
                <litteraturkritikk-field-editor
                        v-for="field in group.fields"
                        :key="field.key"
                        :column-definition="columnsByKey[field.key]"
                        :field="field.key"
                        :label="labels[field.key]"
                        :value="values[field.key]"
                ></litteraturkritikk-field-editor>
            </table>
            <!--{{ json_encode( old($field['key'], $record->{$field['key']}) ) }}-->
        </div>
    </div>
</template>

<script>
    export default {
        name: "LitteraturkritikkEditForm",
        props: {
            columns: {
                type: Array,
            },
            labels: {
                type: Object,
            },
            values: {

            }
        },
        computed: {
            columnsByKey() {
                let out = [];
                this.columns.forEach(columnGroup => {
                    columnGroup.fields.forEach(col => out[col.key] = col)
                });
                return out
            }
        }
    }
</script>

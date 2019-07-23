<template>
    <div>
        <div class="panel panel-default" v-for="group in columns.groups" :key="group.label" v-if="group.display !== false">
            <div class="panel-heading">
                <h3 class="panel-title">{{ group.label }}</h3>
            </div>
            <div class="panel-body">
                <table class="table">
                    <litteraturkritikk-edit-field
                            v-for="field in group.fields"
                            :key="field.key"
                            :definition="definitions[field.key]"
                            :label="labels[field.key]"
                            :value="values[field.key]"
                    ></litteraturkritikk-edit-field>
                </table>
            </div>
            <!--{{ json_encode( old($field['key'], $record->{$field['key']}) ) }}-->
        </div>
    </div>
</template>

<script>
    import LitteraturkritikkEditField from './LitteraturkritikkEditField'
    export default {
        name: "LitteraturkritikkEditForm",
        components: {
            LitteraturkritikkEditField,
        },
        props: {
            columns: {
                type: Object,
            },
            labels: {
                type: Object,
            },
            values: {

            }
        },
        computed: {
            definitions() {
                let out = [];
                this.columns.groups.forEach(columnGroup => {
                    columnGroup.fields.forEach(col => out[col.key] = col)
                });
                return out
            }
        }
    }
</script>

<style scoped lang="sass">

</style>
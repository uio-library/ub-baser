<template>
    <form id="searchForm" method="GET" action="/norsk-litteraturkritikk">

        <table style="width: 100%">
            <litteraturkritikk-search-field
                    v-for="(field, fieldIndex) in query"
                    :key="field.key"
                    class="form-group field-set"
                    :data-index="fieldIndex"
                    :advanced="advanced"
                    :index="fieldIndex"
                    :fields="searchFields"
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
                        class="btn btn-info"
                        id="addFieldButton"
                        @click="addField()"
                ><i class="fa fa-plus"></i></button>
                <div v-else class="help-block">og</div>
            </litteraturkritikk-search-field>
        </table>

        <div style="padding: 6px">

            <button type="submit" class="btn btn-primary"><i class="zmdi zmdi-search"></i> Søk</button>

            <a href="/norsk-litteraturkritikk" class="btn btn-default">Nullstill</a>

            <label>
                <input type="checkbox" name="advanced" v-model="advanced"> Avansert
            </label>
        </div>
    </form>
</template>

<script>
    import LitteraturkritikkSearchField from './LitteraturkritikkSearchField'

    export default {
        name: "LitteraturkritikkSearch",
        components: {
            LitteraturkritikkSearchField,
        },
        props: {
            initialQuery: Array,
            searchFields: Object,
            advancedSearch: Boolean,
        },
        data() {
            return {
                advanced: this.advancedSearch,
                query: this.initialQuery.map(x => ({
                    field: x.field,
                    operator: x.operator,
                    value: x.value,
                })),
                operators: [
                    {label: 'matcher', value: 'eq'},
                    {label: 'matcher ikke', value: 'neq'},
                    {label: 'mangler verdi', value: 'isnull'},
                    {label: 'har verdi', value: 'notnull'},
                ],
            }
        },
        methods: {
            addField() {
                this.query.push({field: 'q', value: ''});
            }
        }
    }
</script>

<style scoped lang="sass">
label
    font-weight: normal

</style>
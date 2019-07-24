<template>
    <form id="searchForm" method="GET" action="/norsk-litteraturkritikk" class="pb-3">

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
            </litteraturkritikk-search-field>
        </table>

        <div class="d-flex py-1">
            <div class="flex-grow-1">
                <button type="submit" class="btn btn-primary"><i class="zmdi zmdi-search"></i> Søk</button>
                <a href="/norsk-litteraturkritikk" class="btn btn-secondary">Nullstill</a>
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
                this.query.push({
                    field: 'q',
                    op: 'eq',
                    value: ''
                });
            }
        }
    }
</script>

<style scoped lang="sass">
label
    font-weight: normal

</style>
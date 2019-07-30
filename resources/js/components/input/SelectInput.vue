<template>
    <div>
        <select :id="'select_' + name" :name="name">
            <option :value="value.id" selected="selected">{{ value.label }}</option>
        </select>
        {{ value }}
    </div>
</template>

<script>
    import { get } from 'lodash/object';

    export default {
        name: "select-input",
        props: {
            name: {
                type: String,
            },
            schema: {
                type: Object,
            },
            value: {
                type: Object,
            },
        },
        mounted() {
            this.initSelectize();
        },
        beforeDestroy() {
            this.destroySelectize();
        },
        methods: {
            initSelectize() {
                let url = get(this.schema, 'search.options.target');
                $(`#select_${this.name}`).selectize({
                    create: false,
                    preload: true,

                    valueField: 'id',
                    labelField: 'label',
                    searchField: 'label',

                    openOnFocus: true,
                    closeAfterSelect: true,

                    load: (query, callback) => {
                        this.$http.get(url, {
                            params: {
                                field: this.schema.key,
                                q: query,
                            }
                        }).then(
                            res => callback(res.data),
                            err => callback([])
                        )
                    },
                });
            },
            destroySelectize() {
                // Note that selectize is not completely destroyed: https://github.com/selectize/selectize.js/issues/1257
                // Should not be an issue for us since we're not making a SPA.
                $(`#select_${this.name}`).selectize()[0].selectize.destroy();
            },
        },
    }
</script>

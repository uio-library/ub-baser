<template>
    <div>
        <input type="text"
              class="form-control"
              autocomplete="off"
              ref="input"
              :name="name"
              :value="value"
              :placeholder="placeholder"
              @input="$emit('value', $event.target.value)"
        >
    </div>
</template>

<script>
    import autocomplete from 'autocomplete.js';
    import { get } from 'lodash/object';

    let search = null;

    export default {
        name: "autocomplete-input",
        props: {
            name: String,
            schema: Object,
            value: String,
        },
        computed: {
            placeholder() {
                return get(this.schema, 'search.placeholder');
            }
        },
        mounted() {
            this.initAutocomplete();
        },
        beforeDestroy() {
            if (search) {
                search.autocomplete.destroy();
                search = null;
            }
        },
        methods: {
            initAutocomplete() {
                let url = get(this.schema, 'search.options.target');
                search = autocomplete(this.$refs.input, {}, [
                    {
                        source: (query, callback) => {
                            this.$http.get(url, {
                                params: {
                                    field: this.schema.key,
                                    q: query,
                                },
                            })
                            .then(
                                res => {
                                    // let suggestions = countries.filter(n => n.label.toLowerCase().startsWith(text))
                                    callback(res.data);
                                },
                                err => {
                                    callback([]);
                                }
                            );
                        }
                    }
                ])
                .on('autocomplete:selected', (event, suggestion, dataset, context) => {
                    this.$emit('value', suggestion.value);
                    this.$emit('selected', suggestion);
                })
                .on('autocomplete:autocompleted', (event, suggestion, dataset, context) => {
                    this.$emit('value', suggestion.value);
                    this.$emit('selected', suggestion);
                });
            },

            focus() {
                this.$refs.input.focus();
            }
        }
    }
</script>

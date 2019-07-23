<template>
    <div>
        <input type="text"
              class="form-control"
              autocomplete="off"
              ref="input"
              :name="name"
              :value="value"
              :placeholder="definition.placeholder"
              @input="$emit('value', $event.target.value)"
        >
    </div>
</template>

<script>
    import autocomplete from "autocomplete.js";

    let search = null;

    export default {
        name: "AutocompleteInput",
        props: {
            name: String,
            definition: Object,
            value: String,
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
                search = autocomplete(this.$refs.input, {}, [
                    {
                        source: (query, callback) => {
                            if (this.definition.type !== 'autocomplete') {
                                callback([]);
                            } else {
                                this.$http.get('/norsk-litteraturkritikk/autocomplete', {
                                    params: {
                                        field: this.definition.key,
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

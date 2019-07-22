<template>
    <div>
        <input type="text"
              class="form-control"
              autocomplete="off"
              ref="input"
              :name="`v${index}`"
              :value="value"
              :placeholder="field.placeholder"
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
            index: Number,
            field: Object,
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
                            if (this.field.type !== 'autocomplete') {
                                callback([]);
                            } else {
                                this.$http.get('/norsk-litteraturkritikk/autocomplete', {
                                    params: {
                                        field: this.field.id,
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
                    this.$emit('value', suggestion.value)
                })
                .on('autocomplete:autocompleted', (event, suggestion, dataset, context) => {
                    this.$emit('value', suggestion.value)
                });
            }
        }
    }
</script>

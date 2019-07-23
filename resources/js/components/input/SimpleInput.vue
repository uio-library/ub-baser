<template>
    <div>
        <input type="text"
               class="form-control"
               :name="name"
               :value="value"
               :placeholder="placeholder"
               @input="$emit('value', $event.target.value)"
        >
    </div>
</template>

<script>
    import { get } from 'lodash/object';

    export default {
        name: "SimpleInput",
        props: {
            name: String,
            definition: Object,
            value: String,
        },
        computed: {
            placeholder() {
                return get(this.definition, 'search.placeholder');
            }
        },
    }
</script>

<template>
    <div>
        <input type="text"
               class="form-control"
               :name="`v${index}`"
               :value="value"
               :placeholder="field.placeholder"
               @input="$emit('value', $event.target.value)"
        >
    </div>
</template>

<script>
    export default {
        name: "SimpleInput",
        props: {
            index: Number,
            field: Object,
            value: String,
        }
    }
</script>

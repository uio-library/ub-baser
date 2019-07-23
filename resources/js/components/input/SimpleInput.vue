<template>
    <div>
        <input type="text"
               class="form-control"
               :name="name"
               :value="value"
               :placeholder="definition.placeholder"
               @input="$emit('value', $event.target.value)"
        >
    </div>
</template>

<script>
    export default {
        name: "SimpleInput",
        props: {
            name: String,
            definition: Object,
            value: String,
        }
    }
</script>

<template>
    <div style="padding: 10px 0">
        <input type="checkbox"
               :name="name"
               :checked="value"
               @input="$emit('value', $event.target.value)"
        >
        {{ definition.help }}
    </div>
</template>

<script>
    export default {
        name: "SimpleInput",
        props: {
            name: String,
            definition: Object,
            value: Boolean,
        }
    }
</script>

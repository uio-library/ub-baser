<template>
    <div style="padding: 10px 0">
        <input type="checkbox"
               :name="name"
               :checked="value"
               @input="$emit('value', $event.target.value)"
        >
        {{ schema.help }}
    </div>
</template>

<script>
export default {
  name: 'simple-input',
  props: {
    name: String,
    schema: Object,
    value: Boolean
  }
}
</script>

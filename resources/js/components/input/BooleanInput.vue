<template>
    <div style="padding: 10px 0">
        <input type="checkbox"
               :name="name"
               :checked="value"
               @input="onInput($event)"
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
    value: Boolean,
  },
  methods: {
    onInput ($event) {
      this.$emit('value', $event.target.checked)
    },
  },
}
</script>

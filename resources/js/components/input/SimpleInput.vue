<template>
    <div>
        <input type="text"
               class="form-control"
               :name="name"
               :value="value"
               :placeholder="placeholder"
               @input="onInput($event.target.value)"
        >
    </div>
</template>

<script>

export default {
  name: 'simple-input',
  props: {
    name: String,
    schema: Object,
    settings: Object,
    value: String,
    placeholder: String,
  },
  methods: {
    onInput (newValue) {
      this.$emit('value', newValue)
    },
  },
}
</script>

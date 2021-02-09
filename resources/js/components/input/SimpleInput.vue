<template>
    <div>
      <div v-if="schema.multiline && context === 'edit'">
        <textarea
           class="form-control"
           ref="input"
           :name="name"
           :value="value"
           :placeholder="placeholder"
           style="height: 200px"
           @input="onInput($event.target.value)"
        ></textarea>
      </div>
      <div v-else>
        <input type="text"
               class="form-control"
               ref="input"
               :name="name"
               :value="value"
               :placeholder="placeholder"
               @input="onInput($event.target.value)"
        >
      </div>
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
    context: String,
  },
  methods: {
    onInput (newValue) {
      this.$emit('value', newValue)
    },
    focus () {
      this.$refs.input.focus()
    },
  },
}
</script>

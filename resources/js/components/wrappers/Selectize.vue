<template>
  <select v-once>
    <slot/>
  </select>
</template>

<script>

export default {
  props: {
    value: {
      default: ''
    },
    settings: {
      type: Object,
      default: () => ({})
    },
  },
  mounted () {
    $(this.$el).selectize({
      onInitialize: () => {
        this.setValue()
      },
      onChange: value => {
        //this.$emit('input', value)
      },
      ...this.settings
    })
  },
  destroyed () {
    if (this.$el.selectize) {
      this.$el.selectize.destroy()
    }
  },
  methods: {
    setValue () {
      this.$el.selectize.setValue(this.value, true)
    },
  }
}
</script>

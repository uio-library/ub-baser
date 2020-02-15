<template>
  <select v-once>
    <slot/>
  </select>
</template>

<script>

export default {
  props: {
    value: {
      type: Array | String,
    },
    settings: {
      type: Object,
      default: () => ({})
    },
  },
  mounted () {
    $(this.$el).selectize({
      onInitialize: () => {
        this.setValue(this.value)
      },
      ...this.settings
    })
  },
  watch: {
    value: function(val) {
      this.setValue(val)
    }
  },
  destroyed () {
    if (this.$el.selectize) {
      this.$el.selectize.destroy()
    }
  },
  methods: {
    setValue (value) {
      this.$el.selectize.setValue(value, true)
    },
  }
}
</script>

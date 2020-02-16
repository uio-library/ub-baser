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
        this.$emit('initialize', this)
        // this.setValue(this.value)
      },
      onChange: val => {
        this.$emit('change', val)
      },
      onLoad: data => {
        this.$emit('load', data, this)
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
    getSelectize () {
      return this.$el.selectize
    },
    setValue (value) {
      this.getSelectize().setValue(value, true)
    },
  }
}
</script>

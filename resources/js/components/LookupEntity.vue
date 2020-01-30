<template>
  <form @submit.prevent="submit()" style="display:flex">

    <autocomplete-input
      :value="value"
      :schema="inputSchema"
      :settings="settings"
      ref="input"
      @value="value=$event"
      @selected="selectedEntity=$event"
    ></autocomplete-input>

    <button type="button" class="btn btn-danger" @click="cancel()">Avbryt</button>
    <button type="submit" class="btn btn-primary">Ok</button>
  </form>
</template>

<script>
import { get } from 'lodash/object'
import AutocompleteInput from "./input/AutocompleteInput";

export default {
  name: 'lookup-entity',
  components: {
    AutocompleteInput,
  },
  props: {
    type: String,
    schema: Object,
    settings: Object,
  },
  data () {
    return {
      value: null,
      selectedEntity: null,
    }
  },
  computed: {
    inputSchema() {
      return {
        type: 'autocomplete',
        key: this.type,
      }
    },
  },
  mounted() {
    console.log(this.schema)
    this.$nextTick(() => {
      this.$refs.input.focus()
    })
  },
  methods: {
    cancel () {
      this.$emit('cancel')
    },
    submit () {
      this.$emit('submit', this.value, this.selectedEntity)
    },
  },
}
</script>

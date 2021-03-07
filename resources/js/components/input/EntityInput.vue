<template>
  <div style="border: 1px solid #ddd; border-radius: 3px; padding: 3px">
    <input type="hidden" :name="this.schema.key" :value="jsonSerialized">

    <!-- eslint-disable-next-line vue/require-v-for-key -->
    <div v-if="entity" class="px-1" style="min-width: 250px;">
      <a :href="baseUrl + '/' + entity.id + '/edit'" target="_blank" class="pivot-entity" >
        {{ entity.string_representation }}
      </a>
    </div>

    <div v-if="entity" class="px-1">
      <button type="button" class="btn btn-outline-danger btn-sm" @click="removeEntity()">Fjern</button>
    </div>

    <!-- Skjema for å legge til ny entitet -->
    <div v-if="mode === 'addEntity'">
      <form @submit.prevent="newEntityForm()" style="background: #fff6a1; border: 1px solid #eee; padding: 2px;" class="d-flex py-1 px-1">
        <autocomplete-input
          :value="searchValue"
          :schema="autocompleteSchema"
          :settings="settings"
          ref="acinput"
          @value="searchValue=$event"
          @selected="selectEntity($event.record)"
          input-class="form-control form-control-sm"
        ></autocomplete-input>
        <button type="button" class="btn btn-danger btn-sm mx-1" @click="clearInput()">Avbryt</button>
        <button type="submit" class="btn btn-success btn-sm mx-1">Opprett ny</button>
      </form>
    </div>

    <!-- Skjema for å registrere ny entitet -->
    <div v-else-if="mode === 'createEntity'">
      <form @submit.prevent="storeNewEntity()" style="background: #fff6a1; border: 1px solid #eee; padding: 2px;" class="d-flex flex-wrap align-items-end">

        <template v-for="(field, idx) in schema.entitySchema.fields">
          <edit-field
            class="edit-field pivot-field"
            ref="newEntityInputs"
            :key="field.key"
            :schema="field"
            :settings="settings"
            :value="newEntity[field.shortKey]"
            @value="newEntity[field.shortKey] = $event"
          ></edit-field>
        </template>

        <div v-if="error" class="text-danger">{{ error }}</div>
        <button type="button" class="btn btn-danger btn-sm mx-1" @click="clearInput()">Avbryt</button>
        <button :disabled="busy" type="submit" class="btn btn-primary btn-sm mx-1">Opprett</button>
      </form>
    </div>

    <!-- Knapp for å legge til entitet -->
    <div v-else-if="!entity">
      <button type="button" class="btn btn-outline-primary btn-sm m-1" @click="addOrCreateEntity()">Legg til</button>
    </div>

    <code style="display: block; width: 500px; overflow: scroll">
      {{jsonSerialized}}
    </code>
  </div>
</template>

<script>
import { cloneDeep } from 'lodash/lang'
import { get } from 'lodash/object'
import Selectize from 'vue2-selectize'
import AutocompleteInput from './AutocompleteInput'

/**
 * Note: This component does not support two-way data-binding,
 * Updating the `value` property will not update the component.
 * This is ok as it's only used with editing, not with search.
 */
export default {
  name: 'entity-input',
  components: {
    Selectize,
    AutocompleteInput,
    EditField: () => import('../EditField'),
  },
  props: {
    name: String,
    schema: Object,
    settings: Object,
    value: Object,
    context: String,
  },
  data () {
    return {
      mode: 'normal',
      busy: false,
      error: null,
      entity: cloneDeep(this.value),
      newEntity: {},
      searchValue: '',
    }
  },
  computed: {
    entityType () {
      return this.schema.entityType.split('\\').pop().toLowerCase()
    },
    baseUrl () {
      return get(this.settings, 'baseUrl') + '/' + this.entityType
    },
    autocompleteSchema () {
      return {
        type: 'autocomplete',
        key: this.entityType,
      }
    },
    jsonSerialized () {
      return JSON.stringify(this.entity ? this.entity.id : null)
    },
  },
  watch: {
    entity: (newValue) => {
      console.log('Entity changed: ', newValue)
    },
  },
  methods: {
    removeEntity() {
      this.entity = null
    },
    addOrCreateEntity () {
      this.addEntity()
    },
    addEntity () {
      this.mode = 'addEntity'

      this.$nextTick(() => {
        this.$refs.acinput.focus()
      })
    },
    clearInput () {
      this.searchValue = ''
      this.mode = 'normal'
    },
    selectEntity (selected) {
      this.entity = cloneDeep(selected)
      this.clearInput()
    },
    newEntityForm() {
      this.busy = false
      this.error = null
      this.newEntity = {
        id: null,
      }
      this.schema.entitySchema.fields.forEach(field => this.newEntity[field.shortKey] = get(field, 'defaultValue', ''))

      const firstKey = this.schema.entitySchema.fields[0].shortKey
      this.newEntity[firstKey] = this.searchValue

      this.mode = 'createEntity'
      setTimeout(() => {
        this.$refs.newEntityInputs[0].focus()
      }, 100)  // nextTick wasn't enough here
    },
    storeNewEntity() {
      this.busy = true
      this.error = null
      this.$http.post(this.baseUrl, this.newEntity)
        .then(res => {
          console.log(res.data)
          this.selectEntity(res.data.record)
        })
        .catch(err => {
          this.busy = false
          this.error = err
        })
    },
  },
}
</script>

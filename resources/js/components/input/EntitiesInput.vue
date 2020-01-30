<template>
  <div style="border: 1px solid #ddd; border-radius: 3px; padding: 3px">
    <input type="hidden" :name="schema.key" :value="jsonSerialized">

    <!--
    {{ baseUrl }}

    <div>
      ENTITIES: <code>{{ entities }}</code>
    </div>

    <div>
      NEW ENTITY: {{ newEntity }}
    </div>

    -->

    <!-- eslint-disable-next-line vue/require-v-for-key -->
    <div v-for="(entity, entityIdx) in entities" class="d-flex pb-1 align-items-end">
      <div class="px-1" style="min-width: 250px;">
        <a :href="baseUrl + '/' + entity.id" target="_blank" class="pivot-entity" >
          {{ entity.string_representation }}
        </a>
      </div>
      <template v-for="field in schema.pivotFields">
        <edit-field
          v-if="field.edit.enabled"
          :class="['edit-field', 'pivot-field', field.type]"
          :key="field.key"
          :schema="field"
          :settings="settings"
          :value="entity.pivot[field.shortKey]"
          @value="entity.pivot[field.shortKey] = $event"
        ></edit-field>
      </template>
      <div class="px-1">
        <button type="button" class="btn btn-outline-danger btn-sm" @click="removeEntity(entityIdx)">Fjern</button>
      </div>
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

        <template v-for="(field, idx) in schema.entityType.schema.fields">
          <edit-field
            :class="['edit-field', 'pivot-field', field.type]"
            ref="newEntityInputs"
            :key="field.key"
            :schema="field"
            :settings="settings"
            :value="newEntity[field.shortKey]"
            @value="onNewEntityChange(field, $event)"
          ></edit-field>
        </template>

        <div v-for="group in schema.entityType.schema.groups" :key="group.label">
          <h4>{{ group.label}}</h4>
          <template v-for="(field, idx) in group.fields">
            <edit-field
              :class="['edit-field', 'pivot-field', field.type]"
              ref="newEntityInputs"
              :key="field.key"
              :schema="field"
              :settings="settings"
              :value="newEntity[field.shortKey]"
              @value="onNewEntityChange(field, $event)"
            ></edit-field>
          </template>
        </div>

        <div v-if="error" class="text-danger">{{ error }}</div>
        <button type="button" class="btn btn-danger btn-sm mx-1" @click="clearInput()">Avbryt</button>
        <button :disabled="busy" type="submit" class="btn btn-primary btn-sm mx-1">Opprett</button>
      </form>
    </div>

    <!-- Knapp for å legge til entitet -->
    <div v-else>
      <button type="button" class="btn btn-outline-primary btn-sm m-1" @click="addEntity()">Legg til</button>
    </div>

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
  name: 'entities-input',
  components: {
    Selectize,
    AutocompleteInput,
    EditField: () => import('../EditField'),
  },
  props: {
    schema: Object,
    settings: Object,
    value: Array,
  },
  data () {
    return {
      mode: 'normal',
      busy: false,
      error: null,
      entities: cloneDeep(this.value),
      newEntity: {},
      searchValue: '',
    }
  },
  computed: {
    entityType () {
      return this.schema.entityType.name
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
      return JSON.stringify(this.entities)
    },
  },
  watch: {
    entities: (newValue) => {
      console.log('Entities changed: ', newValue)
    },
  },
  methods: {
    removeEntity(idx) {
      this.entities.splice(idx, 1)
      this.$emit('value', this.entities)
    },
    addEntity () {
      this.mode = 'addEntity'

      this.$nextTick(() => {
        this.$refs.acinput.focus()
      })
    },
    clearInput () {
      this.searchValue = ''
      this.newEntity = {}
      this.mode = 'normal'
    },
    getNextPosition() {
      let pos = this.entities.length ? Number(this.entities[this.entities.length - 1].pivot.position) + 1 : 1
      return String(pos)
    },
    selectEntity (selected) {
      let entity = cloneDeep(selected)
      entity.pivot = {}
      this.schema.pivotFields.forEach(pivotField => {
        entity.pivot[pivotField.shortKey] = get(pivotField, 'defaultValue', '')
      })
      entity.pivot.position = this.getNextPosition()


      console.log('SELECT ENTITY', entity)
      this.entities.push(entity)
      this.$emit('value', this.entities)
      this.clearInput()
    },
    newEntityForm() {
      this.busy = false
      this.error = null
      let newEntity = {
        id: null,
        pivot: {},
      }
      console.log(this.schema)
      this.schema.entityType.schema.fields.forEach(field => newEntity[field.shortKey] = get(field, 'defaultValue', ''))
      this.schema.pivotFields.forEach(field => newEntity.pivot[field.shortKey] = get(field, 'defaultValue', ''))

      const firstKey = this.schema.entityType.schema.fields[0].shortKey
      newEntity[firstKey] = this.searchValue

      console.log('newEntityForm', newEntity)

      this.mode = 'createEntity'
      setTimeout(() => {
        this.$refs.newEntityInputs[0].focus()
      }, 300)

      this.newEntity = newEntity
    },
    onNewEntityChange(field, newValue) {
      console.log('[EntitiesInput] Changed', field.shortKey)
      this.$set(this.newEntity, field.shortKey, newValue)
    },
    storeNewEntity() {
      console.log('storeNewEntity', this.newEntity)
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

<template>
    <div>

        <input type="hidden" :name="this.definition.key" :value="personsJson">

        <table v-if="persons.length">
            <tr>
                <th>Etternavn</th>
                <th>Fornavn</th>
                <th>Kjønn</th>
                <th>Pseudonym</th>
                <th>Rolle</th>
                <th>Kommentar</th>
            </tr>
            <tr v-for="(person, personIdx) in persons">
                <td>
                    <input type="text" v-model="person.etternavn" class="form-control" :disabled="person.id">
                </td>
                <td>
                    <input type="text" v-model="person.fornavn" class="form-control" :disabled="person.id">
                </td>
                <td style="width:100px">
                    <select v-model="person.kjonn" :id="`person_${definition.key}_${personIdx}`" :disabled="person.id">
                        <option value="">(ingen verdi)</option>
                        <option value="f">kvinne</option>
                        <option value="m">mann</option>
                        <option value="u">ukjent</option>
                    </select>
                </td>
                <td>
                    <input type="text" v-model="person.pivot.pseudonym" class="form-control">
                </td>
                <td>
                    <input type="text" v-model="person.pivot.person_role" class="form-control">
                </td>
                <td>
                    <input type="text" v-model="person.pivot.kommentar" class="form-control">
                </td>
                <td>
                    <button type="button" class="btn btn-danger" @click="removePerson(personIdx)">Fjern</button>
                </td>
            </tr>
        </table>

        <!-- Ny person -->
        <div v-if="mode === 'addPerson'">
            <form @submit.prevent="storePerson()" style="display:flex">

                <autocomplete-input
                        :value="newPersonValue"
                        :definition="{type:'autocomplete', 'key': 'person'}"
                        ref="newperson"
                        @value="newPersonValue=$event"
                        @selected="selectedPerson=$event"
                ></autocomplete-input>
                <button type="button" class="btn btn-danger" @click="clearPerson()">Avbryt</button>
                <button type="submit" class="btn btn-primary">Ok</button>
            </form>
        </div>
        <div v-else>
            <button type="button" class="btn btn-primary" @click="addPerson()">Legg til person</button>
        </div>

    </div>
</template>

<script>
    import { cloneDeep } from 'lodash/lang';
    import AutocompleteInput from './AutocompleteInput'

    export default {
        name: "PersonInput",
        components: {
            AutocompleteInput,
        },
        props: {
            name: String,
            definition: Object,
            value: Array,
        },
        data() {
            return {
                mode: 'normal',
                persons: cloneDeep(this.value),
                newPersonValue: '',
                selectedPerson: null,
            };
        },
        computed: {
            personsJson() {
                return JSON.stringify(this.persons);
            }
        },
        watch: {
            persons: (newValue) => {
                console.log('persons changed: ', newValue);
            },
        },
        mounted() {
            this.initSelectize();
        },
        beforeDestroy() {
            this.destroySelectize();
        },
        methods: {
            initSelectize() {
                this.persons.forEach((person, personIdx) => {
                    $(`#person_${this.definition.key}_${personIdx}`).selectize({
                        openOnFocus: true,
                        closeAfterSelect: true,
                    });
                });
            },
            destroySelectize() {
                // Note that selectize is not completely destroyed: https://github.com/selectize/selectize.js/issues/1257
                // Should not be an issue for us since we're not making a SPA.
                this.persons.forEach((person, personIdx) => {
                    $(`#person_${personIdx}`).selectize()[0].selectize.destroy();
                });
            },
            removePerson(personIdx) {
                this.persons.splice(personIdx, 1)
            },
            addPerson() {
                this.mode='addPerson';

                this.$nextTick(() => {
                    console.log(this.$refs);
                    this.$refs.newperson.focus()
                });
            },
            clearPerson() {
                this.newPersonValue = '';
                this.selectedPerson = null;
                this.mode = 'normal';
            },
            storePerson() {
                let person = {
                    id: null,
                    etternavn: '',
                    fornavn: '',
                    kjonn: '',
                    pivot: {
                        person_role: this.definition.person_role,
                        kommentar: '',
                        pseudonym: '',
                    },
                };
                if (this.selectedPerson) {
                    person.id = this.selectedPerson.id;
                    person.etternavn = this.selectedPerson.record.etternavn;
                    person.fornavn = this.selectedPerson.record.fornavn;
                    person.kjonn = this.selectedPerson.record.kjonn;
                } else {
                    let etternavn = '', fornavn = '', navn = this.newPersonValue.split(', ');
                    if (navn.length === 2) {
                        person.etternavn = navn[0];
                        person.fornavn = navn[1];
                    } else {
                        person.etternavn = this.newPersonValue;
                    }
                }
                this.persons.push(person);
                this.clearPerson();
                this.$nextTick(() => this.initSelectize());
            },
        }
    }
</script>

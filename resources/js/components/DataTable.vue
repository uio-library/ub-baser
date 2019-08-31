<!--
    Important:
    This component is mostly a wrapper for old-school jQuery stuff.
    Please always use it with v-once! And please be careful.
    See https://vuejsdevelopers.com/2017/05/20/vue-js-safely-jquery-plugin/ to learn more.
-->
<template>

    <div>

        <div ref="spinner" style="height:300px; display: flex; justify-content: center; align-items: center">
            <div class="lds-heart"><div></div></div>
        </div>

        <div ref="main" style="opacity: 0">

            <div ref="columnSelectorWrapper"  class="d-flex align-items-center">
                <div class="flex-grow-0">
                    Vis kolonner:
                </div>
                <select multiple
                        ref="columnSelector"
                        class="selectpicker col flex-grow-1"
                        data-style=""
                        data-style-base="form-control form-control-sm"
                >
                    <option
                            v-for="field in fields"
                            :key="field-key"
                            :value="field.key">{{ field.label }}</option>

                    <optgroup v-for="group in groups" :key="group.label" :label="group.label">

                        <option
                                v-for="field in group.fields"
                                :key="field.key"
                                :value="field.key">{{ field.label }}</option>

                    </optgroup>
                </select>
            </div>

            <table ref="theTable" class="table hover" style="width:100%">
                <thead>
                    <tr v-if="groups.length">
                        <th v-for="field in fields" :key="field.key"></th>
                        <th v-for="group in groups" :key="group.label" :colspan="group.fields.length">{{ group.label }}</th>
                    </tr>
                    <tr>
                        <th v-for="col in columns" :key="col.data">
                            {{ col.columnLabel }}
                        </th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>
</template>

<script>
import { get } from 'lodash/object'
export default {
  name: 'data-table',
  props: {
    schema: {
      type: Object,
    },
    defaultColumns: {
      type: Array,
    },
    order: {
      type: Array,
    },
    url: {
      type: String,
    },
    prefix: {
      type: String,
    },
    query: {
      type: Object,
    },
  },
  computed: {

    fields () {
      return this.schema.fields.filter(field => field.displayable !== false)
    },

    storageKey () {
      return `ub-baser-${this.prefix}-columns`
    },

    groups () {
      return this.schema.groups.map(group => (
        {
          label: group.label,
          fields: group.fields.filter(field => field.displayable !== false),
        }
      ))
    },

    visibleColumns () {
      let visible = this.defaultColumns

      if (sessionStorage.getItem(this.storageKey) !== null) {
        visible = JSON.parse(sessionStorage.getItem(this.storageKey))
      }

      return visible
    },

    columns () {
      const columns = []

      const processField = (field) => {
        if (field.displayable === false) {
          return
        }
        const col = {
          data: field.key,
          columnLabel: field.label,
          visible: this.visibleColumns.indexOf(field.key) !== -1,
          render: (data, type, row) => {
            if (data === null) {
              data = '–'
            }
            return data
          },
        }
        if (get(field, 'columnClassName')) {
          col.className = field.columnClassName
        }
        columns.push(col)
      }

      this.schema.fields.forEach(field => processField(field))
      this.schema.groups.forEach(fieldGroup => {
        fieldGroup.fields.forEach(field => processField(field))
      })
      return columns
    },

    defaultOrder () {
      const keys = this.columns.map(col => col.data)
      const order = this.order.filter(item => this.visibleColumns.indexOf(item.key) !== -1)

      if (!order.length) {
        // Fallback to asc sort on first column
        return [[0, 'asc']]
      }

      return order.map(item => [keys.indexOf(item.key), item.direction])
    },

  },

  methods: {

    initColumnSelector () {
      return $(this.$refs.columnSelector).val(this.visibleColumns)
    },

    initTable () {
      let drag = false
      const table = $(this.$refs.theTable).DataTable({
        language: {
          sEmptyTable: 'Ingen data tilgjengelig i tabellen',
          sInfo: 'Viser _START_ til _END_ av _TOTAL_ poster',
          sInfoEmpty: 'Viser 0 til 0 av 0 poster',
          sInfoFiltered: '(filtrert fra _MAX_ totalt antall poster)',
          sInfoPostFix: '',
          sInfoThousands: ' ',
          sLengthMenu: 'Vis _MENU_ poster',
          sLoadingRecords: 'Laster...',
          sProcessing: 'Laster...',
          sSearch: 'S&oslash;k:',
          sUrl: '',
          sZeroRecords: 'Ingen treff',
          oPaginate: {
            sFirst: 'F&oslash;rste',
            sPrevious: 'Forrige',
            sNext: 'Neste',
            sLast: 'Siste',
          },
          oAria: {
            sSortAscending: ': aktiver for å sortere kolonnen stigende',
            sSortDescending: ': aktiver for å sortere kolonnen synkende',
          },
        },
        pageLength: 50,
        lengthMenu: [10, 50, 100, 500, 1000],
        ajax: {
          url: this.url,
          data: (input) => {
            return Object.assign({}, input, this.query)
          },
        },
        columns: this.columns,
        order: this.defaultOrder,

        searching: false,
        processing: true,
        serverSide: true,
        // Make the tr elements focusable
        createdRow: (row, data, dataIndex) => {
          $(row)
            .attr('tabindex', '0')
            .on('mousedown', () => { drag = false })
            .on('mousemove', () => { drag = true })
            .on('keypress', $event => {
              if ($event.keyCode === 13) {
                const link = this.url + '/' + data.id
                window.location = link
              }
            })
            .on('click', $event => {
              const link = this.url + '/' + data.id
              if (drag) {
                return
              }
              if ($event.ctrlKey || $event.metaKey) {
                window.location = link
              } else {
                window.open(link, '_blank')
              }
            })
        },
      })

      return table
    },
  },

  mounted () {
    // Initialize the column selector and the table
    const $columnSelector = this.initColumnSelector()
    const table = this.initTable()

    // Connect them together: Update the table when the column selector change.
    $columnSelector.on('change', () => {
      const visibleColumns = $columnSelector.val() // array of keys

      sessionStorage.setItem(
        this.storageKey,
        JSON.stringify(visibleColumns)
      )

      this.columns.forEach((col, idx) => {
        const visible = visibleColumns.indexOf(col.data) !== -1
        table.column(idx).visible(visible)
      })
    })

    // Tweak the table header and move the column selector into it
    table.on('init', (event) => {
      // Let's first get a reference to the row above the table. This row contains
      // two cells, the first one containing "Vis X poster", the second one being empty
      // (it's used for the search box that we have disabled above).
      const $tableWrapper = $(table.containers()[0])
      const $tableHeader = $tableWrapper.find('> .row:first')

      // Swap the columns, so that the empty cell comes first.
      // We will use this for our column selector.
      const $container = $tableHeader.find('> div:last').detach()
      $tableHeader.prepend($container)

      // Then move our column selector into it
      $container.append($(this.$refs.columnSelectorWrapper).detach())

      // Right-align the "Vis X poster" cell (which is now the last cell)
      $tableHeader.find('> div:last').addClass('text-right')

      // We're ready!
      $(this.$refs.spinner).hide()
      $(this.$refs.main).css('opacity', '1')
    })
  },
}

</script>

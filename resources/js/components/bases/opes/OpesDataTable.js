import DataTable from '../../DataTable'

export default {
  name: 'opes-data-table',
  extends: DataTable,
  methods: {
    renderCell (data, type, row, meta) {
      if (!data) {
        return '–'
      }
      let columnKey = this.columns[meta.col].data
      if (columnKey === 'fullsizeback_r1' || columnKey === 'fullsizefront_r1') {
        let thumbPath = 'https://ub-media.uio.no/OPES/thumbs/tn_' + data
        return `<img style="height: 75px;" src="${thumbPath}">`
      }
      return data
    },
  }
}


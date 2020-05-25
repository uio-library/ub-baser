const Page = require('./page')

class RecordPage extends Page {
  getFieldValue (fieldKey) {
    return $('.' + fieldKey)
  }

  getRecordTitle () {
    return $('.record-title').getText()
  }

  openRecord (id) {
    this.open(`${id}`)
  }
}

module.exports = RecordPage

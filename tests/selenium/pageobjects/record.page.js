const Page = require('./page')

class RecordPage extends Page {
  getFieldValue (fieldKey) {
    return $('.' + fieldKey)
  }
  openRecord(id) {
    this.open(`${id}`)
  }
}

module.exports = RecordPage

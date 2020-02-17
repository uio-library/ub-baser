const Page = require('./page')

class RecordPage extends Page {
  getFieldValue (fieldKey) {
    return $('.' + fieldKey)
  }
}

module.exports = RecordPage

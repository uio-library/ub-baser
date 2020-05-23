const RecordPage = require('./record.page')

class BibsysRecordPage extends RecordPage {
  constructor () {
    super()
    this.baseUrl = '/bibsys/record/'
  }
}

module.exports = new BibsysRecordPage()

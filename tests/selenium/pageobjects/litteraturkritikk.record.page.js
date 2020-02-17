const RecordPage = require('./record.page')

class LitteraturkritikkRecordPage extends RecordPage {
  constructor () {
    super()
    this.baseUrl = '/norsk-litteraturkritikk/record/'
  }
}

module.exports = new LitteraturkritikkRecordPage()

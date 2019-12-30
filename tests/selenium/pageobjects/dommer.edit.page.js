const EditPage = require('./edit.page')

class DommerEditPage extends EditPage {
  constructor () {
    super()
    this.baseUrl = '/dommer/'
  }

  openRecord(id) {
    this.open(`poster/${id}/edit`)
  }
}

module.exports = new DommerEditPage()

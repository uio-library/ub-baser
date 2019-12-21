const IndexPage = require('./index.page')

class DommerIndexPage extends IndexPage {
  constructor () {
    super()
    this.baseUrl = '/dommer/'
  }
}

module.exports = new DommerIndexPage()

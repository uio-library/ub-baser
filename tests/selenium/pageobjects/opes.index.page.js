const IndexPage = require('./index.page')

class OpesIndexPage extends IndexPage {
  constructor () {
    super()
    this.baseUrl = '/opes/'
  }
}

module.exports = new OpesIndexPage()

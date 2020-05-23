const IndexPage = require('./index.page')

class BibsysIndexPage extends IndexPage {
  constructor () {
    super()
    this.baseUrl = '/bibsys/'
  }
}

module.exports = new BibsysIndexPage()

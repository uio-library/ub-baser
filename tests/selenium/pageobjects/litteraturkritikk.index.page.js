const IndexPage = require('./index.page')

class LitteraturkritikkIndexPage extends IndexPage {
  constructor () {
    super()
    this.baseUrl = '/norsk-litteraturkritikk/'
  }
}

module.exports = new LitteraturkritikkIndexPage()

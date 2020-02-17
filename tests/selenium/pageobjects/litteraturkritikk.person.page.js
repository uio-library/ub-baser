const Page = require('./page')

class LitteraturkritikkPersonPage extends Page {
  constructor () {
    super()
    this.baseUrl = '/norsk-litteraturkritikk/person/'
  }
}

module.exports = new LitteraturkritikkPersonPage()

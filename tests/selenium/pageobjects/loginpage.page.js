const Page = require('./page')

class LoginPage extends Page {
  get title () { return browser.getTitle() }

  open () {
    super.open('/login')
  }
}
module.exports = new LoginPage()

const Page = require('./page')

class LoginPage extends Page {
  get title () { return browser.getTitle() }

  open () {
    super.open('/login')
  }

  loginAs(username, password) {
    $('#collapse1_toggle').click()
    $('input[name="email"]').setValue(username)
    $('input[name="password"]').setValue(password)
    $('button[type="submit"]').click()
    browser.getUrl()
  }
}
module.exports = new LoginPage()

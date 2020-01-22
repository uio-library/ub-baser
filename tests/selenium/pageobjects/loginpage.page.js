const Page = require('./page')

class LoginPage extends Page {
  open () {
    super.open('/login')
  }

  loginAs (username, password) {
    $('#collapse1_toggle').click()
    $('input[name="email"]').addValue(username)
    $('input[name="password"]').addValue(password)
    $('button[type="submit"]').click()
    browser.getUrl()
  }
}
module.exports = new LoginPage()

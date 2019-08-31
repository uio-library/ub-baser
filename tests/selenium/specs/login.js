const expect = require('chai').expect
const LoginPage = require('../pageobjects/loginpage.page')

describe('Login page', function () {
  it('should let you log in', function () {
    LoginPage.open()
    expect(LoginPage.getLoggedInUser()).to.equal(null)
    LoginPage.loginAs('admin@example.org', 'secret')
    expect(LoginPage.getLoggedInUser()).to.equal('Georg Sverdrup')
  })
})

// describe('login page', function () {
//     it('should identify as UB-baser', () => {
//         browser.url('/');
//         const title = browser.getTitle();
//         assert.strictEqual(title, 'UB-baser');
//     });
// });

const expect = require('chai').expect
const LoginPage = require('../pageobjects/loginpage.page')

describe('Login page', function () {
  it('should let you log in', function () {
    LoginPage.open()
    expect(LoginPage.getLoggedInUser()).to.equal(null)
    $('input[name="email"]').setValue('admin@example.org')
    $('input[name="password"]').setValue('secret')
    $('button[type="submit"]').click()

    const pageUrl = browser.getUrl()
    expect(pageUrl).to.not.contain('login')
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

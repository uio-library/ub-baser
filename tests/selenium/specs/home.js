const expect = require('chai').expect
const HomePage = require('../pageobjects/homepage.page')

describe('Home page', function () {
  it('should have the right title', function () {
    HomePage.open()
    expect(HomePage.title).to.equal('UB-baser')
  })

  it('should contain a list of databases', function () {
    HomePage.open()
    expect(HomePage.getDatabaseList().length).to.be.at.least(4)
  })
})

// describe('login page', function () {
//     it('should identify as UB-baser', () => {
//         browser.url('/');
//         const title = browser.getTitle();
//         assert.strictEqual(title, 'UB-baser');
//     });
// });

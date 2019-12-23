const expect = require('chai').expect
const LitteraturkritikkIndexPage = require('../pageobjects/litteraturkritikk.index.page')

describe('Norsk litteraturkritikk', function () {
  it('should have the right title', function () {
    LitteraturkritikkIndexPage.open()
    expect(LitteraturkritikkIndexPage.title).to.equal('Norsk litteraturkritikk')
  })

  it('should have default results', function () {
    LitteraturkritikkIndexPage.open()
    expect(LitteraturkritikkIndexPage.isTableEmpty()).to.be.false
  })

})

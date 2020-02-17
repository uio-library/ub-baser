const expect = require('chai').expect
const LitteraturkritikkIndexPage = require('../pageobjects/litteraturkritikk.index.page')
const LitteraturkritikkRecordPage = require('../pageobjects/litteraturkritikk.record.page')
const LitteraturkritikkPersonPage = require('../pageobjects/litteraturkritikk.person.page')

describe('Norsk litteraturkritikk', function () {

  it('should have the right title', function () {
    LitteraturkritikkIndexPage.open()
    expect(LitteraturkritikkIndexPage.title).to.equal('Norsk litteraturkritikk')
  })

  it('should have default results', function () {
    LitteraturkritikkIndexPage.open()
    expect(LitteraturkritikkIndexPage.isTableEmpty()).to.be.false
  })

  it('should be possible to open a result', function () {
    LitteraturkritikkIndexPage.open()

    LitteraturkritikkIndexPage.waitForResults()[0].click()

    expect(browser.getUrl()).to.contain('/record/')

    const verkTittel = LitteraturkritikkRecordPage.getFieldValue('verk_tittel').getText()
    const verkForfatter = LitteraturkritikkRecordPage.getFieldValue('verk_forfatter').$('a')
    const verkForfatterName = verkForfatter.getText()

    verkForfatter.click()

    expect(browser.getUrl()).to.contain('/person/')
    expect(LitteraturkritikkPersonPage.title).to.contain(verkForfatterName)
    expect($('#recordsAsAuthor').getText()).to.contain(verkTittel)
  })

})

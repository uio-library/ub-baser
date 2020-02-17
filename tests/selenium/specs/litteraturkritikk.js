const expect = require('chai').expect
const IndexPage = require('../pageobjects/litteraturkritikk.index.page')
const RecordPage = require('../pageobjects/litteraturkritikk.record.page')
const PersonPage = require('../pageobjects/litteraturkritikk.person.page')

describe('Norsk litteraturkritikk', function () {

  it('should have the right title', function () {
    IndexPage.open()
    expect(IndexPage.title).to.equal('Norsk litteraturkritikk')
  })

  it('should have default results', function () {
    IndexPage.open()
    expect(IndexPage.isTableEmpty()).to.be.false
  })

  it('should be possible to view a result', function () {
    IndexPage.open()

    IndexPage.dataTable.scrollIntoView()
    const verkTittelFromTable = IndexPage.firstResult.$('td').getText()
    IndexPage.firstResult.$('td').click()

    expect(browser.getUrl()).to.contain('/record/')
    const verkTittelFromRecordPage = RecordPage.getFieldValue('verk_tittel').getText()

    expect(verkTittelFromRecordPage).to.equal(verkTittelFromTable)
  })


  it('should be possible to view an associated person record', function () {
    RecordPage.openRecord('1')

    const verkTittel = RecordPage.getFieldValue('verk_tittel').getText()
    const verkForfatter = RecordPage.getFieldValue('verk_forfatter').$('a')
    const verkForfatterName = verkForfatter.getText()

    verkForfatter.scrollIntoView()
    verkForfatter.click()

    expect(browser.getUrl()).to.contain('/person/')
    expect(PersonPage.title).to.contain(verkForfatterName)
    expect($('#recordsAsAuthor').getText()).to.contain(verkTittel)
  })
})

const expect = require('chai').expect
const indexPage = require('../pageobjects/litteraturkritikk.index.page')
const recordPage = require('../pageobjects/litteraturkritikk.record.page')
const personPage = require('../pageobjects/litteraturkritikk.person.page')

describe('Norsk litteraturkritikk: search page', function () {

  it('should have the right title', function () {
    indexPage.open()
    expect(indexPage.title).to.equal('Norsk litteraturkritikk')
  })

  it('should have default results', function () {
    indexPage.open()
    expect(indexPage.isTableEmpty()).to.be.false
  })

  it('should keep input when changing to a search field of similar type', function () {
    const searchField = indexPage.open().searchFields[0]
    searchField.setValue('Hello world')
    searchField.setType('person')
    expect(searchField.getValue()).to.equal('Hello world')
  })

  it('should discard input when changing to a search field of a different type', function () {
    const searchField = indexPage.open().searchFields[0]
    searchField.setValue('Hello world')
    searchField.setType('kritikktype')
    expect(searchField.getValue()).to.equal('')
  })

  it('should provide a selectize menu for "kritikktype", with no default selection', function () {
    const searchField = indexPage.open().searchFields[0]
    searchField.setType('kritikktype')
    expect(searchField.getValue()).to.equal('')
  })

  it('should be possible to select a value from the "kritikktype" selectize menu', function () {
    const searchField = indexPage.open().searchFields[0]
    searchField.setType('kritikktype')
    searchField.component.selectAndReturnFirstOption()
    indexPage.submitSearch()
    browser.waitUntil(() => {
      return browser.getUrl().indexOf('f0=kritikktype') > -1
    }, 5000)
    indexPage.waitForResults()
    expect(indexPage.isTableEmpty()).to.be.false
  })

  it('should be possible to click a result', function () {
    indexPage.open()

    indexPage.dataTable.scrollIntoView()
    const verkTittelFromTable = indexPage.firstResult.$('td').getText()
    indexPage.firstResult.$('td').click()

    expect(browser.getUrl()).to.contain('/record/')
    const verkTittelFromrecordPage = recordPage.getFieldValue('verk_tittel').getText()

    expect(verkTittelFromrecordPage).to.equal(verkTittelFromTable)
  })

})

describe('Norsk litteraturkritikk: record page', function () {

  it('should be possible to view an associated person record', function () {
    recordPage.openRecord('1')

    const verkTittel = recordPage.getFieldValue('verk_tittel').getText()
    const verkForfatter = recordPage.getFieldValue('verk_forfatter').$('a')
    const verkForfatterName = verkForfatter.getText()

    verkForfatter.scrollIntoView()
    verkForfatter.click()

    expect(browser.getUrl()).to.contain('/person/')
    expect(personPage.title).to.contain(verkForfatterName)
    expect($('#recordsAsAuthor').getText()).to.contain(verkTittel)
  })

})

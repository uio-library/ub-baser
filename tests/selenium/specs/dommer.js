const expect = require('chai').expect
const DommerIndexPage = require('../pageobjects/dommer.index.page')

describe('Dommers populærnavn', function () {
  it('should have the right title', function () {
    DommerIndexPage.open()
    expect(DommerIndexPage.title).to.equal('Dommers populærnavn')
  })

  it('should have default results', function () {
    DommerIndexPage.open()
    expect(DommerIndexPage.isTableEmpty()).to.be.false
  })

  it('should support search for "kilde"', function () {
    DommerIndexPage.open()
    DommerIndexPage.waitForTableToLoad()

    DommerIndexPage.setSearchField('kilde')

    // Open the Selectize dropdown
    const sel = $('#searchField0 div.selectize-input')
    sel.waitForDisplayed(3000)
    sel.click()

    // Select the first value from the Selectize dropdown
    $('#searchField0 div.selectize-dropdown-content > div').click()

    DommerIndexPage.submitSearch()

    browser.waitUntil(() => {
      const url = browser.getUrl()
      console.log('URL: ' + url)
      return url.indexOf('f0=kilde') > -1
    }, 5000)
    DommerIndexPage.waitForTableToLoad()
    expect(DommerIndexPage.isTableEmpty()).to.be.false
  })
})

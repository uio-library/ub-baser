const expect = require('chai').expect
const page = require('../pageobjects/dommer.index.page')
const SelectizeComponent = require('../components/selectize.component')

describe('Dommers populærnavn', function () {
  it('should have the right title', function () {
    page.open()
    expect(page.title).to.equal('Dommers populærnavn')
  })

  it('should have default results', function () {
    page.open()
    expect(page.isTableEmpty()).to.be.false
  })

  it('should support search for "kilde"', function () {
    const searchField = page.open().searchFields[0]
    searchField.setType('kilde')
    searchField.component.selectAndReturnFirstOption()
    page.submitSearch()
    browser.waitUntil(() => {
      return browser.getUrl().indexOf('q=kilde') !== -1
    }, 5000)
    page.waitForResults()
    expect(page.isTableEmpty()).to.be.false
  })
})

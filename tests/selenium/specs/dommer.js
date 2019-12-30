const expect = require('chai').expect
const page = require('../pageobjects/dommer.index.page')
const selectizeComponent = require('../components/selectize.component')

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
    page.open()
    page.waitForTableToLoad()

    page.setSearchField('kilde')
    selectizeComponent.selectAndReturnFirstOption('#searchField0')

    page.submitSearch()

    browser.waitUntil(() => {
      return browser.getUrl().indexOf('f0=kilde') > -1
    }, 5000)

    page.waitForTableToLoad()
    expect(page.isTableEmpty()).to.be.false
  })
})

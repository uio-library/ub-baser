const expect = require('chai').expect
const indexPage = require('../pageobjects/bibsys.index.page')
const recordPage = require('../pageobjects/bibsys.record.page')

describe('Bibsys katalogdump', function () {
  it('should have the right title', function () {
    indexPage.open()
    expect(indexPage.title).to.equal('Bibsys-arkiv UBO')
  })

  it('should have default results', function () {
    indexPage.open()
    expect(indexPage.isTableEmpty()).to.be.false
  })

  it('should be possible to click a result', function () {
    indexPage.open()

    indexPage.dataTable.scrollIntoView()
    const dokidFromTable = indexPage.firstResult.$('./td[3]').getText()
    console.log(dokidFromTable)
    indexPage.firstResult.$('td').click()

    expect(browser.getUrl()).to.contain('/record/')
    const dokidFromrecordPage = recordPage.getFieldValue('dokid').getText()

    expect(dokidFromrecordPage).to.equal(dokidFromTable)
  })
})

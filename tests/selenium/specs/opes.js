const expect = require('chai').expect
const page = require('../pageobjects/opes.index.page')

describe('OPES', function () {
  it('should have the right title', function () {
    page.open()
    expect(page.title).to.equal('OPES')
  })
})

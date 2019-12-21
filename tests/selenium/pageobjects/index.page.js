const Page = require('./page')

class IndexPage extends Page {
  setSearchField (value, fieldNo) {
    fieldNo = fieldNo || '0'
    const selectBox = $(`select[name="f${fieldNo}"]`)
    selectBox.selectByAttribute('value', value)
  }

  setSearchFieldValue (value, fieldNo) {
    fieldNo = fieldNo || '0'
    const elem = $(`input[name="v${fieldNo}"]`)
    elem.waitForDisplayed(3000)
    elem.setValue(value)
  }

  submitSearch () {
    $('form[id="searchForm"] button[type="submit"]').click()
  }

  waitForTableToLoad () {
    browser.waitUntil(() => {
      return this.getResults().length !== 0
    }, 5000, 'expected results within 5 secs')
  }

  isTableEmpty () {
    this.waitForTableToLoad()
    if ($$('#DataTables_Table_0_wrapper td.dataTables_empty').length) {
      return true
    }
    return false
  }

  getResults () {
    return $$('#DataTables_Table_0_wrapper tbody tr')
  }
}
module.exports = IndexPage

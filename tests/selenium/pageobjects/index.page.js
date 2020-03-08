const Page = require('./page')
const SearchField = require('../components/searchField.component')

class IndexPage extends Page {

  open () {
    super.open()
    this.searchFields = [
      new SearchField(0),
    ]
    this.waitForResults()
    return this
  }

  submitSearch () {
    $('form[id="searchForm"] button[type="submit"]').click()
  }

  getResults () {
    return $$('.dataTables_wrapper tbody tr')
  }

  waitForResults () {
    browser.waitUntil(() => {
      return this.getResults().length !== 0
    }, 10000, 'expected results within 10 secs')
    return this.getResults()
  }

  get dataTable () {
    this.waitForResults()
    return $('.dataTables_wrapper')
  }

  get firstResult () {
    this.waitForResults()
    return $('#DataTables_Table_0_wrapper tbody tr')
  }

  isTableEmpty () {
    this.waitForResults()
    if ($$('.dataTables_wrapper td.dataTables_empty').length) {
      return true
    }
    return false
  }

}

module.exports = IndexPage

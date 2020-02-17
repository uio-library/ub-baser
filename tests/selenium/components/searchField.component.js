const SelectizeComponent = require('../components/selectize.component')

class SearchField {

  constructor(index) {
    this.index = index
    this.fieldSelect = $(`select[name="f${index}"]`)
    this.component = $(`input[name="v${this.index}"]`)
  }

  setType (value, type) {
    this.fieldSelect.selectByAttribute('value', value)
    browser.pause(300)

    if ($(`select[name="v${this.index}"]`).isExisting()) {
      this.component = new SelectizeComponent('#searchField0')
    } else {
      this.component = $(`input[name="v${this.index}"]`)
    }
  }

  setValue (value) {
    // this.component.waitForDisplayed(3000)
    this.component.setValue(value)
  }

  getValue (value) {
    // this.component.waitForDisplayed(3000)
    return this.component.getValue()
  }

}

module.exports = SearchField

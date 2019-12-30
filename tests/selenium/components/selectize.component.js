class SelectizeComponent {
  open(container) {
    const sel = $(`${container} .selectize-input`)
    sel.waitForDisplayed(3000)
    sel.click()
  }

  selectAndReturnFirstOption(container) {
    this.open(container)
    const firstOption = $(`${container} .selectize-dropdown-content > .option`)
    const value = firstOption.getText()
    firstOption.click()
    return value
  }
}

module.exports = new SelectizeComponent()


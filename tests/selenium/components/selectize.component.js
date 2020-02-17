class SelectizeComponent {

  constructor(container) {
    this.$container = $(container)
    this.isOpen = false
  }

  getValue() {
    return this.$container.$('.searchFieldValue select').getValue()
  }

  open () {
    if (this.isOpen) return
    this.$container.$('.selectize-input').waitForDisplayed(5000)
    this.$container.click()
    this.isOpen = true
  }

  get firstOption () {
    this.open()
    return this.$container.$('.selectize-dropdown-content > .option')
  }

  selectAndReturnFirstOption() {
    const value = this.firstOption.getText()
    this.firstOption.click()
    this.isOpen = false
    return value
  }

}

module.exports = SelectizeComponent

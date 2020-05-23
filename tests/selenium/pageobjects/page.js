const querystring = require('querystring')
const urljoin = require('url-join')

/**
 * Based on http://webdriver.io/guide/testrunner/pageobjects.html
 */
class Page {
  constructor () {
    this.baseUrl = ''
  }

  /**
   * Navigate the browser to a given page.
   *
   * @since 1.0.0
   * @see <http://webdriver.io/api/protocol/url.html>
   * @param {string} path Page path
   * @param {Object} [query] Query parameter
   * @param {string} [fragment] Fragment parameter
   * @return {void} This method runs a browser command.
   */
  open (path = '', query = {}, fragment = '') {
    const url = urljoin(browser.options.baseUrl, this.baseUrl, path)
    console.log('Open URL: ' + url)
    browser.url(
      url,
      (query ? ('?' + querystring.stringify(query)) : '') +
      (fragment ? ('#' + fragment) : ''),
    )
    return this
  }

  get title () { return browser.getTitle() }

  getLoggedInUser () {
    const user = $('#user_name').getText().trim()
    return (user === '') ? null : user
  }
}

module.exports = Page

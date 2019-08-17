const querystring = require( 'querystring' );
const url = require('url');

/**
 * Based on http://webdriver.io/guide/testrunner/pageobjects.html
 */
class Page {
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
	open( path, query = {}, fragment = '' ) {
		console.log("YO")
		const the_url = url.resolve( browser.options.baseUrl, path );
		browser.url(
			the_url,
			( query ? ('?' + querystring.stringify( query )) : '') +
			( fragment ? ( '#' + fragment ) : '' )
		);
	}

}
module.exports = Page;

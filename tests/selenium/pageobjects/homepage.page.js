const Page = require( './page' );

class HomePage extends Page {

    get title() { return browser.getTitle(); }

    open() {
        super.open( '/' );
    }

    getDatabaseList() {
    	return $('#database_list').$$('li');
    }

}
module.exports = new HomePage();
const expect = require('chai').expect;
const { exec } = require( 'child_process' );
const log = console.log;
const chalk = require('chalk');
const HomePage = require( '../pageobjects/homepage.page' );
const DOCKER_APP_CONTAINER_NAME = process.env.DOCKER_APP_CONTAINER_NAME || 'ub-baser-app-testing';

function execPromise ( command ) {
  return new Promise( function ( resolve, reject ) {
    exec( command, ( error, stdout, stderr ) => {
      if ( error ) {
        reject( error );
        return;
      }

      resolve( stdout.trim() );
    });
  });
}

function artisan( args ) {
  return execPromise(`docker exec ${DOCKER_APP_CONTAINER_NAME} php artisan ${args}`)
}

before( function () {
  console.log( `Running migrations in Docker container "${DOCKER_APP_CONTAINER_NAME}"` );
  return artisan('migrate:fresh --seed')
    .then(out => log(chalk.gray(out)))
    .catch( function (error) {
      throw new Error(error)
    } );
} );

after( function () {
  console.log( `Rolling back migrations in Docker container "${DOCKER_APP_CONTAINER_NAME}"` );
  return artisan('migrate:rollback')
    .then( out => log(chalk.gray(out)))
    .catch( function (error) {
      throw new Error(error)
    } );
} );

describe( 'Home page', function () {

  it( 'should have the right title', function () {
      HomePage.open();
      expect( HomePage.title ).to.equal( 'UB-baser' );
  } );

  it( 'should contain a list of databases', function () {
      HomePage.open();
      expect( HomePage.getDatabaseList() ).to.have.length( 4 );
  } );

} );

// describe('login page', function () {
//     it('should identify as UB-baser', () => {
//         browser.url('/');
//         const title = browser.getTitle();
//         assert.strictEqual(title, 'UB-baser');
//     });
// });

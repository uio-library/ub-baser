const { runMigrations, rollbackMigrations, isDockerRunning } = require('./util')

const BASE_URL = process.env.BASE_URL || 'http://localhost:8080'

const sauceOptions = {
  'sauce:options': {
    tunnelIdentifier: process.env.TRAVIS_JOB_NUMBER,
    build: process.env.TRAVIS_BUILD_NUMBER,
    // trustAllSSLCertificates: true,
  }
}

exports.config = {

  services: ['sauce'],
  user: process.env.SAUCE_USERNAME,
  key: process.env.SAUCE_ACCESS_KEY,
  region: 'us', // 'eu' didn't work yet with my account
  // sauceConnect: true,

  //
  // Ref: https://docs.saucelabs.com/reference/platforms-configurator
  //
  capabilities: [
    {
      browserName: 'firefox',
      browserVersion: 'latest',
      platformName: 'Windows 10',
      ...sauceOptions,
    },
    {
      browserName: 'chrome',
      browserVersion: 'latest',
      platformName: 'Windows 10',
      ...sauceOptions,
    },
  ],

  specs: [
    './tests/selenium/specs/**/*.js',
  ],
  exclude: [
  ],
  maxInstances: 1,

  logLevel: 'warn',
  // If you only want to run your tests until a specific amount of tests have failed use
  // bail (default is 0 - don't bail, run all tests).
  bail: 0,

  baseUrl: BASE_URL,
  waitforTimeout: 10000,
  connectionRetryTimeout: 90000,
  connectionRetryCount: 3,

  framework: 'mocha',
  reporters: ['spec'],
  mochaOpts: {
    ui: 'bdd',
    timeout: 60000,
  },
  /**
     * Hook that gets executed before the suite starts
     * @param {Object} suite suite details
     */
  beforeSuite: function (suite) {
    // console.log(`BEFORE suite: ${suite.title}`);
    return runMigrations()
  },
  /**
     * Hook that gets executed after the suite has ended
     * @param {Object} suite suite details
     */
  afterSuite: function (suite) {
    // console.log(`AFTER suite: ${suite.title}`);
    return rollbackMigrations()
  },
}

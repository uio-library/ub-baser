const { exec } = require('child_process')
const log = console.log
const chalk = require('chalk')  // eslint-disable-line
const DOCKER_APP_CONTAINER_NAME = process.env.DOCKER_APP_CONTAINER_NAME || 'ub-baser-app-staging'

function execPromise (command) {
  return new Promise(function (resolve, reject) {
    exec(command, (error, stdout, stderr) => {
      if (error) {
        reject(error)
        return
      }

      resolve(stdout.trim())
    })
  })
}

function artisan (args) {
  return execPromise(`docker exec ${DOCKER_APP_CONTAINER_NAME} php artisan ${args}`)
}

function runMigrations () {
  log(`Running migrations in Docker container "${DOCKER_APP_CONTAINER_NAME}"`)
  return artisan('migrate:fresh --seed')
    .then(function (out) {
      // log(chalk.gray(out))
    })
    .catch(function (error) {
      throw new Error(error)
    })
}

function rollbackMigrations () {
  log(`Rolling back migrations in Docker container "${DOCKER_APP_CONTAINER_NAME}"`)
  return artisan('migrate:rollback')
    .then(function (out) {
      // log(chalk.gray(out))
    })
    .catch(function (error) {
      throw new Error(error)
    })
}

function isDockerRunning () {
  log('Checking if Docker is running')

  return new Promise(function (resolve, reject) {
    exec('docker ps', { timeout: 5000 }, (error, stdout, stderr) => {
      if (error) {
        reject(new Error('Docker is not running!'))
        return
      }

      resolve(stdout.trim())
    })
  })
}

module.exports = {
  runMigrations,
  rollbackMigrations,
  isDockerRunning,
}

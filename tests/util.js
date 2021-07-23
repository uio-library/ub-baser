const { exec } = require('child_process')
const log = console.log
const chalk = require('chalk')  // eslint-disable-line
const DOCKER_APP_CONTAINER_NAME = process.env.DOCKER_APP_CONTAINER_NAME

function execPromise (command) {
  return new Promise(function (resolve, reject) {
    exec(command, (error, stdout, stderr) => {
      if (error) {
        console.log('Command failed')
        console.log(stdout)
        console.log(stderr)
        reject(error)
        return
      }

      resolve(stdout.trim())
    })
  })
}

function artisan (args) {
  if (DOCKER_APP_CONTAINER_NAME) {
    log(`Running in Docker container ${DOCKER_APP_CONTAINER_NAME}: php artisan ${args}`)
    return execPromise(`docker exec ${DOCKER_APP_CONTAINER_NAME} php artisan ${args}`)
  } else {
    log(`Running: php artisan ${args}`)
    return execPromise(`php artisan ${args}`)
  }
}

function createAdminUser () {
  return artisan('create:admin -y')
}

function runMigrations () {
  return artisan('migrate:fresh --seed')
    .then(function (out) {
      return createAdminUser()
    })
    .catch(function (error) {
      throw new Error(error)
    })
}

function rollbackMigrations () {
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

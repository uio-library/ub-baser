const assert = require('assert');
const { spawnSync } = require( 'child_process' );

before(function () {
  console.log('Run migrations');
  const proc = spawnSync(
    'docker',
    ['exec', 'docker_app_1',
     'php', 'artisan', 'migrate:fresh', '--seed'],
    {
       encoding: 'utf-8',
       stdio: 'pipe',
    }
  );
  console.log(proc.stdout);
});

describe('webdriver.io page', function () {
    it('should have the right title', () => {
        browser.url('https://192.168.99.104');
        const title = browser.getTitle();
        assert.strictEqual(title, 'UB-baser');
    });
});

after(function () {
  console.log('Rollback migrations');
  const proc = spawnSync(
    'docker',
    ['exec', 'docker_app_1',
     'php', 'artisan', 'migrate:rollback'],
    {
       encoding: 'utf-8',
       stdio: 'pipe',
    }
  );
  console.log(proc.stdout);
});
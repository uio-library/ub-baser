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


describe('home page', function () {
    it('should identify as UB-baser', () => {
        browser.url('/');
        const title = browser.getTitle();
        assert.strictEqual(title, 'UB-baser');
    });
});

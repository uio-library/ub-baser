[![Build Status](https://img.shields.io/travis/scriptotek/ub-baser.svg?style=flat-square)](https://travis-ci.org/scriptotek/ub-baser)
[![StyleCI](https://styleci.io/repos/44453446/shield)](https://styleci.io/repos/44453446)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/scriptotek/ub-baser.svg?style=flat-square)](https://scrutinizer-ci.com/g/scriptotek/ub-baser/?branch=master)
[![Code Climate](https://img.shields.io/codeclimate/github/scriptotek/ub-baser.svg?style=flat-square)](https://codeclimate.com/github/scriptotek/ub-baser)
[![Codacy](https://img.shields.io/codacy/917a1e933d9044c0ba899514b59f29d2.svg?style=flat-square)](https://www.codacy.com/app/danmichaelo/ub-baser)


## UB-baser

Nytt, felles grensesnitt for basene OPES, Letras, Beyer og Dommers populærnavn.


### Installasjon

1. Sjekk at maskinen har PHP >= 7.1 og Composer, samt nødvendige PHP-tillegg (se http://laravel.com/docs/5.1#installation).
2. `git clone git@github.com:scriptotek/ub-baser.git`
3. `composer install`
4. Legg inn databasekonfigurasjon i `.env`
5. `sudo chown -R $WWWGROUP:$USERGROUP storage`, der `WWWGROUP` er brukeren
   til webserveren, f.eks. `apache` på Redhat, eller `www-data` på Ubuntu,
   og `USERGROUP` er en gruppe du er medlem av.
6. `chmod g+s storage`
7. `php artisan key:generate`
8. `npm install`
9. `npm run dev` for å bygge JavaScript og CSS i `public/build`

Eventuelt:

* Hvis SELinux: `setsebool -P httpd_can_network_connect_db 1`
* Migrere og seede databasen: `php artisan migrate --seed`
* Opprett en admin-bruker: `php artisan create:user g.sverdrup@ub.uio.no "Georg Sverdrup" --admin`

### Tips og triks

* `php artisan serve` for å starte en lokal utviklingsserver på port 8080.
* `php-cs-fixer fix` for å tilpasse koden til gjeldende kodestandard ved hjelp av [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer). Lurt å kjøre før commit.
* `npm run dev` for å bygge css og js. Sjekk at [Node og NPM](https://docs.npmjs.com/getting-started/installing-node) er installert, og kjør så `npm install`.

Optimering for PhpStorm:

	php artisan clear-compiled
	php artisan ide-helper:generate
	php artisan ide-helper:models --dir=app -N
	php artisan optimize


### Automatisk testing

```
psql -c 'CREATE DATABASE ub_baser_test;' -U postgres
php artisan migrate:refresh --database=pgsql_test --seed
php artisan db:dump --database pgsql_test
vendor/bin/codecept build
vendor/bin/codecept run functional
```

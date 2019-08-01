[![Build Status](https://img.shields.io/travis/scriptotek/ub-baser.svg?style=flat-square)](https://travis-ci.org/scriptotek/ub-baser)
[![StyleCI](https://styleci.io/repos/44453446/shield)](https://styleci.io/repos/44453446)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/scriptotek/ub-baser.svg?style=flat-square)](https://scrutinizer-ci.com/g/scriptotek/ub-baser/?branch=master)
[![Code Climate](https://img.shields.io/codeclimate/github/scriptotek/ub-baser.svg?style=flat-square)](https://codeclimate.com/github/scriptotek/ub-baser)
[![Codacy](https://img.shields.io/codacy/917a1e933d9044c0ba899514b59f29d2.svg?style=flat-square)](https://www.codacy.com/app/danmichaelo/ub-baser)


## UB-baser

Felles webgrensesnitt for mindre Postgres-baser driftet av Universitetsbiblioteket i Oslo som Letras (norske oversettelser av spansk og spanskamerikansk litteratur), Norsk Litteraturkrtikk (Beyer-basen)og Dommers populærnavn.

### Oppsett og utvikling

- Du trenger: PHP, NodeJS og Docker.
- `./dev.sh up` for å starte en utviklingsserver med Docker Compose.
- `npm run watch` for å bygge frontend (SASS og JS og sånt).
- Se [./docker/README.md](./docker/README.md) for flere detaljer.

#### Tips og triks

* `php-cs-fixer fix` for å tilpasse koden til gjeldende kodestandard ved hjelp av [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer). Lurt å kjøre før commit.

* Hvis du bruker PhpStorm:

	```
	php artisan clear-compiled
	php artisan ide-helper:generate
	php artisan ide-helper:models --dir=app -N
	php artisan optimize
	```

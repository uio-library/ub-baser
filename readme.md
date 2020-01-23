[![Build Status](https://img.shields.io/travis/scriptotek/ub-baser.svg?style=flat-square)](https://travis-ci.org/scriptotek/ub-baser)
[![StyleCI](https://styleci.io/repos/44453446/shield)](https://styleci.io/repos/44453446)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/scriptotek/ub-baser.svg?style=flat-square)](https://scrutinizer-ci.com/g/scriptotek/ub-baser/?branch=master)
[![Code Climate](https://img.shields.io/codeclimate/maintainability/scriptotek/ub-baser)](https://codeclimate.com/github/scriptotek/ub-baser)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/e7a0f0adfff2428f8bf457eca41580e4)](https://www.codacy.com/manual/danmichaelo/ub-baser)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=scriptotek_ub-baser&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=scriptotek_ub-baser)

## UB-baser

Felles webgrensesnitt for mindre Postgres-baser driftet av Universitetsbiblioteket i Oslo som Letras (norske oversettelser av spansk og spanskamerikansk litteratur), Norsk Litteraturkrtikk (Beyer-basen)og Dommers populærnavn.

### Oppsett og utvikling

- Du trenger: PHP, NodeJS og Docker.
- `./dev.sh up` for å starte en utviklingsserver med Docker Compose.
- `npm run watch` for å bygge frontend (SASS og JS og sånt).
- Se [./docker/README.md](./docker/README.md) for flere detaljer.
- Se [./ansible/README.md](./ansible/README.md) for info om deployering.

#### Tips og triks

* `php-cs-fixer fix` for å tilpasse koden til gjeldende kodestandard ved hjelp av [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer). Lurt å kjøre før commit.

* Hvis du bruker PhpStorm:

	```
	php artisan clear-compiled
	php artisan ide-helper:generate
	php artisan ide-helper:models --dir=app -N
	php artisan optimize
	```

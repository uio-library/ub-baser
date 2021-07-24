[![Build Status](https://github.com/uio-library/ub-baser/actions/workflows/test-on-push.yml/badge.svg)](https://github.com/uio-library/ub-baser/actions/workflows/test-on-push.yml)
[![StyleCI](https://styleci.io/repos/44453446/shield)](https://styleci.io/repos/44453446)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/uio-library/ub-baser/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/uio-library/ub-baser/?branch=main)
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

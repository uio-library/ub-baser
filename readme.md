[![Build Status](https://github.com/uio-library/ub-baser/actions/workflows/test-on-push.yml/badge.svg)](https://github.com/uio-library/ub-baser/actions/workflows/test-on-push.yml)
[![StyleCI](https://styleci.io/repos/44453446/shield)](https://styleci.io/repos/44453446)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/uio-library/ub-baser/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/uio-library/ub-baser/?branch=main)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=scriptotek_ub-baser&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=scriptotek_ub-baser)

## UB-baser

Felles webgrensesnitt for mindre Postgres-baser driftet av Universitetsbiblioteket i Oslo som Letras (norske oversettelser av spansk og spanskamerikansk litteratur), Norsk Litteraturkrtikk (Beyer-basen)og Dommers populærnavn.

### Oppsett og utvikling

- Du trenger: PHP, NodeJS og Docker.
- `npm install && npm run watch` for å bygge frontend (SASS og JS og sånt), må kjøres utenfor containeren.
- `./dev.sh up` for å starte en utviklingsserver med Docker Compose.
- Se [./docker/README.md](./docker/README.md) for flere detaljer.

### Oppdatere lokal versjon på et senere tidspunkt

- `git pull` for å hente siste versjon av koden fra GitHub
- `compose update` kan være lurt etterpå for å hente evt. oppdaterte PHP-avhengigheter
- `./dev.sh up -d` for å starte prosjektet
- Hvis containeren ikke starter, se "Troubleshooting" i [./docker/README.md](./docker/README.md).

#### Tips og triks

* `php-cs-fixer fix` for å tilpasse koden til gjeldende kodestandard ved hjelp av [php-cs-fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer). Lurt å kjøre før commit.

* Hvis du bruker PhpStorm:

	```
	php artisan clear-compiled
	php artisan ide-helper:generate
	php artisan ide-helper:models --dir=app -N
	php artisan optimize
	```

### GitHub Actions

Ved push til main-grenen, blir appen automatisk bygget og distribuert hvis de automatiske testene ikke feiler.
Hvis du jobber med noe som ikke er helt produksjonsklart, bør du opprette en egen gren:

    git checkout -b min-nye-base

Når ny base er klar til å prodsettes bør developer grenen merges med main branch

    git checkout main
    git merge min-nye-base

Når du dytter denne til GitHub, blir appen fremdeles bygget og testet, men ikke distribuert.
SSL sertifikat endres en gang i året

## UB-baser

Nytt, felles grensesnitt for basene OPES, Letras, Beyer og Dommers populærnavn.


### Installasjon

1. Sjekk at maskinen har PHP >= 5.5.9 og Composer, samt nødvendige PHP-tillegg (se http://laravel.com/docs/5.1#installation).
2. `git clone git@github.com:scriptotek/ub-baser.git`
3. `composer install`
4. Legg inn databasekonfigurasjon i `.env`
5. `php artisan migrate` for å migrere databasen (ikke på prod)
6. `sudo chown -R apache:ub-minus storage`
7. `php artisan key:generate`
8. `setsebool -P httpd_can_network_connect_db 1`

#### Utviklingsmaskin?

På en utviklingsmaskin trengs også Node, NPM, Gulp og Bower. Kjør `npm install` og `bower install`. `gulp` brukes for å bygge css og js.

Kjør `php artisan serve` for å starte en lokal utviklingsserver.

### Todo

* Sjekk om https://github.com/Crinsane/laravel-elixir-bower/pull/8 blir akseptert

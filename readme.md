[![Build Status](https://github.com/uio-library/ub-baser/actions/workflows/ci.yml/badge.svg)](https://github.com/uio-library/ub-baser/actions/workflows/ci.yml)
[![StyleCI](https://styleci.io/repos/44453446/shield)](https://styleci.io/repos/44453446)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=scriptotek_ub-baser&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=scriptotek_ub-baser)

# UB-baser

Felles webgrensesnitt for mindre Postgres-baser driftet av Universitetsbiblioteket i Oslo som Letras
(norske oversettelser av spansk og spanskamerikansk litteratur), Norsk Litteraturkrtikk
(Beyer-basen)og Dommers populærnavn.

**Innhold:**

- [Local development](#local-development)
  - [Getting started](#getting-started)
    - [Creating the first admin user](#creating-the-first-admin-user)
    - [Importing data](#importing-data)
  - [Troubleshooting local development](#troubleshooting-local-development)
  - [Resetting the database](#resetting-the-database)
  - [Making changes to the Docker image and Apache/PHP config](#making-changes-to-the-docker-image-and-apachephp-config)
  - [Running tests](#running-tests)
  - [Making changes to stylesheets or JavaScript](#making-changes-to-stylesheets-or-javascript)
- [Deployment instructions](#deployment-instructions)
  - [1. Install Apache, PHP and NodeJS](#1-install-apache-php-and-nodejs)
  - [2. Configure SSL certificate](#2-configure-ssl-certificate)
  - [3. Clone the app and install dependencies](#3-clone-the-app-and-install-dependencies)
  - [4. Add configuration to `.env`](#4-add-configuration-to-env)
  - [5. Run database migrations](#5-run-database-migrations)
  - [6. Setup SSO (SAML)](#6-setup-sso-saml)

## Local development

Requirements:

* PHP 7.1 and [Composer](https://getcomposer.org/)
* NodeJS 18
* Docker and docker-compose

The app can also be run locally without Docker, but then you need to install Apache (or another web
server) and Postgres.

### Getting started

1. `npm install` to install frontend dependencies into `./node_modules`
2. `npm run build` to build frontend components (CSS, JS)
3. `composer install` to install PHP dependencies into `./vendor`
4. `docker-compose up` to start the app.

Docker Compose starts two containers: `db` (the Postgres database) and `app` (Apache + PHP).

When the `app` container starts, it will first run the `entrypoint.sh` script
which takes care of [migrating](https://laravel.com/docs/master/migrations) the database,
creating all neccessary tables, collations etc.

Once ready, the app will be available at <http://localhost:8080>

To stop the server, press Ctrl-C.

#### Creating the first admin user

The `create:admin` artisan command is provided to create the initial admin user with a default
password, which can then be used to create additional users.

Without Docker: (requires local Postgres)

    php artisan create:admin

With Docker:

    docker-compose run --rm app php artisan create:admin

The source for this command is `app/Console/Commands/CreateAdminCommand.php`

Now you should be able to login at <http://localhost:8080/login>
and add additional rights at <http://localhost:8080/admin/users/1/edit>

#### Importing data

Data can be imported from the `initial-import` folder using the import commands. For instance you
can import data from `initial-import/litteraturkritikk` to the `litteraturkritikk` database like so:

Without Docker: (requires local Postgres install)

    php artisan import:litteraturkritikk initial-import/litteraturkritikk

With Docker:

    docker-compose run --rm -v "$(pwd)"/initial-import:/initial-import app php artisan import:litteraturkritikk /initial-import/litteraturkritikk

The source for this command is `app/Console/Commands/ImportLitteraturkritikkCommand.php`.

### Troubleshooting local development

If the app container fails to start, try rebuilding the image:

    docker-compose build

To clean up and start from scratch:

    docker-compose down
    docker system prune
    docker volume prune   # Note: Deletes the local database

### Resetting the database

If you need to delete the database and start over again, use the `migrate:fresh` artisan command:

    docker-compose run --rm app php artisan migrate:fresh

### Making changes to the Docker image and Apache/PHP config

If you need to rebuild the Docker image, e.g. after having made changes to the Dockerfile, run:

    docker-compose up --build

You don't need to rebuild the image after changes to the application itself, since the
current directory is mounted into the Docker container when using the development configuration
(see docker-compose.yml).

When testing changes to the Apache config, it can be useful to also mount the config file/folder,
to avoid having to rebuild the image for every change. Here's an example where we mount
the `sites-available` folder:

    docker-compose run --rm -v "$(pwd)"/docker/sites-available:/etc/apache2/sites-available/ app

### Running tests

Tests will run in the `staging` environment by default,
so that tests will run isolated from your development environment.
To start containers for this environment:

    APP_ENV=staging docker-compose up -d

Run the WebdriverIO tests against <http://localhost:8080>:

    npm run test

To run tests against another host, you can specify `TEST_BASE_URL`.
If you use Docker Machine:

    TEST_BASE_URL="http://$(docker-machine ip):8081" npm run test

To run a single test:

    APP_ENV=staging npx wdio tests/wdio.conf.js --spec ./tests/selenium/specs/login.js

### Making changes to stylesheets or JavaScript

Run `npm run watch` to build these resources as you make changes to the source files in the `resources` folder.

## Deployment instructions

### 1. Install Apache, PHP and NodeJS

Install Apache and PHP 8.1:

    dnf install httpd
    dnf module install php:8.1/common

Follow the steps in *[Product Documentation for Red Hat Enterprise Linux 9. Chapter 6. Using the PHP scripting language](https://access.redhat.com/documentation/en-us/red_hat_enterprise_linux/9/html/installing_and_using_dynamic_programming_languages/assembly_using-the-php-scripting-language_installing-and-using-dynamic-programming-languages)* to make Apache start at boot.

Install packages needed by PHP and NPM dependencies:

    dnf install autoconf automake libtool make cmake gettext zlib-devel

Install the Postgres client library and PHP module:

    dnf install postgresql php-pdo php-pgsql

Install the GD and Zip PHP modules and their dependencies:

    dnf install gd gd-devel php-gd php-zip

Git is needed to clone the app:

    dnf install git

[Node 18](https://nodejs.org/en/download/package-manager#centos-fedora-and-red-hat-enterprise-linux) is needed to build frontend components, but is not a *runtime* dependency:

    dnf module install nodejs:18/common

[Composer](https://getcomposer.org/download/) is needed for installing PHP dependencies:

    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
    php composer-setup.php
    rm composer-setup.php
    mv composer.phar /usr/local/bin/composer

### 2. Configure SSL certificate

Follow the steps *[Kokebok for bestilling og utstedelse av SSL-sertifikater](https://www.uio.no/tjenester/it/sikkerhet/sertifikater/kokebok.html)* to order a certificate and install it with Apache.

### 3. Clone the app and install dependencies

Clone the app and install Composer and NPM dependencies:

    cd /srv
    git clone https://github.com/uio-library/ub-baser
    cd ub-baser
    composer install
    npm install

### 4. Add configuration to `.env`

- Add your database settings to the `.env` file.
- In production, make sure to set `APP_DEBUG=false` to avoid leaking secrets.

### 5. Run database migrations

This will both test that we can connect to the database
and add/update any missing tables:

    php artisan migrate

### 6. Setup SSO (SAML)

Create a SAML tenant `uio-weblogin` for the UiO Weblogin IdP (the name "uio-weblogin" should match
the value for `SAML2_DEFAULT_TENANT` in the `.env` file), using metadata from
<https://weblogin.uio.no/simplesaml/saml2/idp/metadata.php?output=xhtml>.

    php artisan saml2:create-tenant \
        --key=uio-weblogin \
        --entityId=https://weblogin.uio.no/simplesaml/saml2/idp/metadata.php \
        --loginUrl=https://weblogin.uio.no/simplesaml/saml2/idp/SSOService.php \
        --logoutUrl=https://weblogin.uio.no/simplesaml/saml2/idp/SingleLogoutService.php \
        --x509cert="MIIFGDCCBACgAwIBAgICA0YwDQYJKoZIhvcNAQEEBQAwga0xCzAJBgNVBAYTAk5PMQ0wCwYDVQQHEwRPc2xvMRswGQYDVQQKExJVbml2ZXJzaXR5IG9mIE9zbG8xOjA4BgNVBAsTMUNlbnRlciBmb3IgSW5mb3JtYXRpb24gVGVjaG5vbG9neSBTZXJ2aWNlcyAoVVNJVCkxEDAOBgNVBAMTB1VTSVQgQ0ExJDAiBgkqhkiG9w0BCQEWFXdlYm1hc3RlckB1c2l0LnVpby5ubzAeFw0xMzExMTUwOTU2NTZaFw0zMzExMTAwOTU2NTZaMIGmMQswCQYDVQQGEwJOTzEbMBkGA1UEChMSVW5pdmVyc2l0eSBvZiBPc2xvMTowOAYDVQQLEzFDZW50ZXIgZm9yIEluZm9ybWF0aW9uIFRlY2hub2xvZ3kgU2VydmljZXMgKFVTSVQpMRgwFgYDVQQDEw93ZWJsb2dpbi51aW8ubm8xJDAiBgkqhkiG9w0BCQEWFXdlYm1hc3RlckB1c2l0LnVpby5ubzCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBALbX2nZYBbUO5tdeG3lVaF/1kdcf1HrGfE/IITNyMqogHT6VPbQxzL5mN1IcHZPDi6Wi+Lg8V9RcXvgP7X6ajUk0PTPmqwil/izS3fUtyKnyulZn/M3+JUW9BHIazhpAsifbqkp5YHt+bFQ/FlStb8ZVzVRIHJZ+GmLKPV+DJ1MsnPyC1InSsdMHEBpt6tYPk3n5qZ7gU3CqI/b/rGP8ECGszRuf8RiL2zO5tlg9mgQ4I+xryIRVf963b538xq+OzGTbIr6eo4+5DolNDsEStOYfAQhnKaZ7QV6PEzS0lcMIXj5L6vZ4aGXEbESB5r/iWUX0HF8qpuMKzw6OgPddEOkCAwEAAaOCAUUwggFBMAkGA1UdEwQCMAAwOAYJYIZIAYb4QgENBCsWKVczQ0Etc2lnbmVkIE9wZW5TU0wgR2VuZXJhdGVkIENlcnRpZmljYXRlMB0GA1UdDgQWBBSCTt0rB5AtfBv0H+svAC8CtdlQHzCB2gYDVR0jBIHSMIHPgBQvUjhkJlXqclR0W+ZyPM/xsI3mB6GBs6SBsDCBrTELMAkGA1UEBhMCTk8xDTALBgNVBAcTBE9zbG8xGzAZBgNVBAoTElVuaXZlcnNpdHkgb2YgT3NsbzE6MDgGA1UECxMxQ2VudGVyIGZvciBJbmZvcm1hdGlvbiBUZWNobm9sb2d5IFNlcnZpY2VzIChVU0lUKTEQMA4GA1UEAxMHVVNJVCBDQTEkMCIGCSqGSIb3DQEJARYVd2VibWFzdGVyQHVzaXQudWlvLm5vggEAMA0GCSqGSIb3DQEBBAUAA4IBAQA+0egBeRpeEC5aDJaJqgtDMMn1y9LmybNTjxKB6/7ICRt3Uhf3EII/oJeuAs99voK96OEu+IEffq4hXSjMnxbHrJWlfEvcIrF9kQfbJKZpXv7jCz8BoDDp4N8mUGeq7xEdWhQsH5+o5aj4WUPMeuZjTPnxNt/cGTJxh95v2Gm0JQ/XmwrUZ41xOxPPsfwQjGIqjWjIsDIvpSlFSqphH967uwWB84GLKHj3jvESUUIhz5Y0TBDcXpF1o0gD4RXBwJEES8cZlYk2pzpH2n6ZpUInfshq2MVCHCPkLN5Iykx+53WmUzeAzFRY4NkuSJxy+poRpbtygxvGateGiFEkm+dD"

See <https://github.com/24Slides/laravel-saml2> for more options.

To list configured IdPs:

    php artisan saml2:list-tenants

To use UiO's test environment instead, create a new tenant using metadata from <https://weblogin-test.uio.no/simplesaml/saml2/idp/metadata.php?output=xhtml>:

    php artisan saml2:create-tenant \
        --key=uio-weblogin-test \
        --entityId=https://weblogin-test.uio.no/simplesaml \
        --loginUrl=https://weblogin-test.uio.no/simplesaml/saml2/idp/SSOService.php \
        --logoutUrl=https://weblogin-test.uio.no/simplesaml/saml2/idp/SingleLogoutService.php \
        --x509cert="MIIFHTCCBAWgAwIBAgICA0UwDQYJKoZIhvcNAQEEBQAwga0xCzAJBgNVBAYTAk5PMQ0wCwYDVQQHEwRPc2xvMRswGQYDVQQKExJVbml2ZXJzaXR5IG9mIE9zbG8xOjA4BgNVBAsTMUNlbnRlciBmb3IgSW5mb3JtYXRpb24gVGVjaG5vbG9neSBTZXJ2aWNlcyAoVVNJVCkxEDAOBgNVBAMTB1VTSVQgQ0ExJDAiBgkqhkiG9w0BCQEWFXdlYm1hc3RlckB1c2l0LnVpby5ubzAeFw0xMzEwMjgxMjUxMzlaFw0yMzEwMjYxMjUxMzlaMIGrMQswCQYDVQQGEwJOTzEbMBkGA1UEChMSVW5pdmVyc2l0eSBvZiBPc2xvMTowOAYDVQQLEzFDZW50ZXIgZm9yIEluZm9ybWF0aW9uIFRlY2hub2xvZ3kgU2VydmljZXMgKFVTSVQpMR0wGwYDVQQDExR3ZWJsb2dpbi10ZXN0LnVpby5ubzEkMCIGCSqGSIb3DQEJARYVd2VibWFzdGVyQHVzaXQudWlvLm5vMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArditxDo2pV0pKddtUo1yH7Znjwkf+PSYMMiI+W1EaSAQ3zyayNnF/xGCK0FmPIs0eZACs/0mODn9flhyINjWb224GS45Ry592u6Ta9HTyWrnPvAgYw0TMs/evc76B+XATiQcw4xNFFhqG1hPGYaNHwZaWmngG2F+B5xY5twN/lMwwuD+Q3sJ/B39pfHy+Y6jy0bEDpM2RrqF5tARKnU1iikwViHI0bWlFEAF2piuj/M4Cha5seIxEZhZtLLMfFX7Q7JTwprisL3pwtALNPSm9sZRLCcpFIFRNUzpgf3HNFvsYdyDw/1gXj/2RBzLBImDG1QQxg67tT/OQpL9gqO2CwIDAQABo4IBRTCCAUEwCQYDVR0TBAIwADA4BglghkgBhvhCAQ0EKxYpVzNDQS1zaWduZWQgT3BlblNTTCBHZW5lcmF0ZWQgQ2VydGlmaWNhdGUwHQYDVR0OBBYEFNrZ2Qv6rFnLBBtjKZ9sm8eiWzc8MIHaBgNVHSMEgdIwgc+AFC9SOGQmVepyVHRb5nI8z/GwjeYHoYGzpIGwMIGtMQswCQYDVQQGEwJOTzENMAsGA1UEBxMET3NsbzEbMBkGA1UEChMSVW5pdmVyc2l0eSBvZiBPc2xvMTowOAYDVQQLEzFDZW50ZXIgZm9yIEluZm9ybWF0aW9uIFRlY2hub2xvZ3kgU2VydmljZXMgKFVTSVQpMRAwDgYDVQQDEwdVU0lUIENBMSQwIgYJKoZIhvcNAQkBFhV3ZWJtYXN0ZXJAdXNpdC51aW8ubm+CAQAwDQYJKoZIhvcNAQEEBQADggEBAFfb5ednPCcwA/U6/v4JIHEOREQlXcpcKsQHT9dNjKWSiXUxF1N3KlKRCrdOSe4DVS1BkmgnAUY1GSnT1acxvsBmW1m0qu6cFlr4K8qgkDio2nPQtIv608+e51Iop6JN1B9m1UX14DXxDjozH3bLO95mChhJ00jKdIFtAXOpjZJS8LC/ii/GjKrPUl8Yz9gcmxykkryr+HdZtBUpcLDCnPhkv5Qqkr0SZQBlsr2XzCydll4ZkYUYYLRG/wxlKop9PY3dKMXLf+jlNiVH9YbiRoa1NdxDsFKTpfhnzVNbGbNp4Gkrn4lut007fhMfcq1ZbATR39NzU84WkMjbhGaisNA="

and set `SAML2_DEFAULT_TENANT=uio-weblogin-test` in the `.env` file.

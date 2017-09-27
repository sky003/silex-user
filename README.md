# Silex authentication service

[![Build Status](https://travis-ci.org/sky003/silex-auth-service.svg?branch=master)](https://travis-ci.org/sky003/silex-auth-service)

Example of REST service built with Silex.

You can also use this as **boilerplate for your Silex application**. You can find here:
* Development environment built with Docker
* Configured application bootstrap
* Authenticator to support email-password auth
* Database migrations
* Unit and integration tests with the fixtures loading support

## Running

To run an application in Docker environment:
```
docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d
```
After that an application will be available on `localhost:8080`.

## Tests

To run the tests type this command:
```
docker-compose exec php-fpm vendor/bin/codecept run
```
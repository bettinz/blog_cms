Readme
===============

Requirements
-------------
- PHP >= 7.4 https://www.php.net
- Docker and docker-compose https://www.docker.com
- Symfony CLI https://symfony.com/download

First steps
-------------
1. install all packages `composer install`
2. run `docker-compose up -d` to start PostgreSQL database
3. sync db and migrations with ` php bin/console doctrine:migrations:migrate`
4. run fixtures `php bin/console doctrine:fixtures:load`
5. generate SSL keys `php bin/console lexik:jwt:generate-keypair`
6. run the application with `symfony server:start`


News queue 
-------------
1. When you send a news (`POST /api/news`) run `php bin/console messenger:consume` to consume the queue

Tests
-------------
1. You can run tests with `sh run_tests.sh`
2. Or you can view the coverage with `sh run_coverage.sh` (files are stored in _/tests/coverage-report_)

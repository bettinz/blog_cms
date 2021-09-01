Readme
===============

Requirements
-------------
- PHP >= 7.4 https://www.php.net
- Docker and docker-compose https://www.docker.com
- Symfony CLI https://symfony.com/download

First steps
-------------
1. Install all packages `composer install`
2. Run `docker-compose up -d` to start PostgreSQL database
3. Sync db and migrations with ` php bin/console doctrine:migrations:migrate`
4. Run fixtures `php bin/console doctrine:fixtures:load`
5. Generate SSL keys `php bin/console lexik:jwt:generate-keypair`
6. Run the application with `symfony server:start`
7. You can view the application's API at _https://localhost:8000/api/_
8. Or you can run a basic MVC application at _https://localhost:8000_

News queue 
-------------
1. When you send a news (`POST /api/news`) run `php bin/console messenger:consume` to consume the queue

Tests
-------------
1. You can run tests with `sh run_tests.sh`
2. Or you can view the coverage with `sh run_coverage.sh` (files are stored in _/tests/coverage-report_)

Users
-------------
Available Users after loading fixtures:

- editor: 
  - username: editor@blog.com
  - password: editor@blog.com
- publisher:
  - username: publisher@blog.com
  - password: publisher@blog.com
- reviewer:
  - username: reviewer@blog.com
  - password: reviewer@blog.com
- admin:
  - username: admin@blog.com
  - password: admin@blog.com

JWT Token
-------------
In order to login you need a token before each protected request;
The address is: `/api/login_check` and accepted parameters are `username` and `password`

An example with CURL is:
`curl -X POST -H "Content-Type: application/json" https://localhost:8000/api/login_check -d '{"username":"editor@blog.com","password":"editor@blog.com"}' -k`

Each request need this Authorization Header: `Bearer {token}`

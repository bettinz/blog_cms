php bin/console doctrine:database:drop --force --env=test
php bin/console doctrine:database:create --env=test
php bin/console doctrine:schema:create --env=test
php bin/console doctrine:fixtures:load --env=test -n
phpdbg -qrr vendor/bin/simple-phpunit --stop-on-failure --coverage-html tests/coverage-report

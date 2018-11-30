install:
	composer install
	php bin/console d:d:c
	php bin/console d:m:m

update:
	composer update
	php bin/console d:m:m